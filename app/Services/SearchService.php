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
     * Executes advanced search parsing, querying, ranking, caching, and snippet extraction.
     */
    public function execute(SearchQueryDTO $dto): array
    {
        $startTime = microtime(true);

        // Fetch results with transparent caching
        $results = $this->cacheService->remember($dto, function () use ($dto) {
            return $this->repository->search($dto);
        });

        // Determine if a next page exists (we retrieved perPage + 1)
        $hasMore = $results->count() > $dto->perPage;
        
        // Slice the collection to requested size
        $paginatedResults = $results->slice(0, $dto->perPage);

        // Run custom ranking algorithm on the matching hits
        $rankedResults = $this->rankingService->rank($paginatedResults, $dto->query);

        // Populate highlights and relevant snippet on each item
        $rankedResults->each(function ($paste) use ($dto) {
            $paste->snippet = $this->snippetService->extract(
                $paste->content,
                $dto->query
            );
        });

        // Determine the next pagination cursor
        $nextCursor = null;
        if ($hasMore && $rankedResults->isNotEmpty()) {
            $nextCursor = $rankedResults->last()->id;
        }

        $executionTime = round((microtime(true) - $startTime) * 1000, 2);

        return [
            'results' => $rankedResults,
            'next_cursor' => $nextCursor,
            'count' => $rankedResults->count(),
            'execution_time_ms' => $executionTime
        ];
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
