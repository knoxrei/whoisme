<?php

namespace App\Services;

use App\DTOs\SearchQueryDTO;
use App\Repositories\SearchRepository;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class SearchService
{
    public function __construct(
        protected SearchRepository $repository,
        protected RankingService $rankingService,
        protected SnippetExtractionService $snippetService,
        protected CacheService $cacheService
    ) {}

    /**
     * Execute advanced search: parse → query → rank → snippet → cache.
     */
    public function execute(SearchQueryDTO $dto): array
    {
        $startTime = microtime(true);

        // Fetch results with transparent adaptive caching
        $results = $this->cacheService->remember($dto, function () use ($dto) {
            return $this->repository->search($dto);
        });

        // Check if there's a next page (we fetched perPage+1)
        $hasMore = $results->count() > $dto->perPage;
        $paginatedResults = $results->slice(0, $dto->perPage);

        // Apply multi-signal ranking
        $rankedResults = $this->rankingService->rank($paginatedResults, $dto->query);

        // Extract highlighted context snippets
        $snippetLength = mb_strlen($dto->query ?? '') > 20 ? 250 : 180;
        $rankedResults->each(function ($paste) use ($dto, $snippetLength) {
            $paste->snippet = $this->snippetService->extract(
                $paste->content,
                $dto->query,
                $snippetLength
            );
        });

        // Determine next cursor
        $nextCursor = null;
        if ($hasMore && $rankedResults->isNotEmpty()) {
            $nextCursor = $rankedResults->last()->id;
        }

        $executionTime = round((microtime(true) - $startTime) * 1000, 2);

        return [
            'results'          => $rankedResults,
            'next_cursor'      => $nextCursor,
            'count'            => $rankedResults->count(),
            'execution_time_ms' => $executionTime,
        ];
    }

    /**
     * Fast autocomplete suggestions — title-only FULLTEXT, cached 60s.
     */
    public function suggest(string $query): array
    {
        $q = trim($query);
        if (mb_strlen($q) < 2) return [];

        return $this->cacheService->rememberSuggest($q, function () use ($q) {
            return $this->repository->suggest($q, 8);
        });
    }

    /**
     * Site-wide public paste statistics, cached 5 minutes.
     */
    public function getSiteStats(): array
    {
        return $this->cacheService->rememberStats(function () {
            return $this->repository->getSiteStats();
        });
    }

    /**
     * Fetch trending pastebins for index dashboard.
     */
    public function getTrendingPastes(int $limit = 10)
    {
        return Cache::remember('trending_pastes_list', 300, function () use ($limit) {
            return $this->repository->getTrending($limit);
        });
    }
}
