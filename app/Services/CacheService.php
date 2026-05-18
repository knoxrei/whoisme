<?php

namespace App\Services;

use App\DTOs\SearchQueryDTO;
use Illuminate\Support\Facades\Cache;

class CacheService
{
    /**
     * Get or set search results in the cache with adaptive TTL.
     * Popular / short queries get longer cache; rare queries get shorter TTL.
     */
    public function remember(SearchQueryDTO $dto, \Closure $callback)
    {
        $cacheKey = $this->generateCacheKey($dto);
        $ttl      = $this->adaptiveTTL($dto);

        return Cache::remember($cacheKey, $ttl, $callback);
    }

    /**
     * Cache autocomplete suggestions — very short TTL for freshness.
     */
    public function rememberSuggest(string $query, \Closure $callback): array
    {
        $key = 'suggest_' . md5(mb_strtolower(trim($query)));
        return Cache::remember($key, 60, $callback); // 1 minute
    }

    /**
     * Cache site stats — refreshed every 5 minutes.
     */
    public function rememberStats(\Closure $callback): array
    {
        return Cache::remember('site_stats', 300, $callback);
    }

    /**
     * Clear cached searches when a new paste is published/updated.
     */
    public function flushSearchCache(): void
    {
        if (config('cache.default') === 'redis') {
            Cache::tags(['search_results'])->flush();
        }
        // Always flush site stats so counter updates fast
        Cache::forget('site_stats');
        Cache::forget('trending_pastes_list');
    }

    /**
     * Adaptive TTL: common queries (short, no filters) get longer cache.
     */
    private function adaptiveTTL(SearchQueryDTO $dto): int
    {
        $hasFilters = $dto->author || $dto->dateRange || $dto->minLength || $dto->maxLength;
        $queryLen   = mb_strlen($dto->query ?? '');

        if ($hasFilters) {
            return 120; // 2 min for filtered queries (more unique)
        }
        if ($queryLen <= 5) {
            return 900; // 15 min for very short/common terms
        }
        if ($queryLen <= 15) {
            return 600; // 10 min for medium queries
        }
        return 300;     // 5 min for long/specific queries
    }

    /**
     * Compute a unique cryptographic key for the search criteria.
     */
    private function generateCacheKey(SearchQueryDTO $dto): string
    {
        $payload = [
            'q'      => mb_strtolower(trim($dto->query ?? '')),
            'author' => $dto->author,
            'sort'   => $dto->sortBy,
            'date'   => $dto->dateRange,
            'min'    => $dto->minLength,
            'max'    => $dto->maxLength,
            'cursor' => $dto->cursor,
            'limit'  => $dto->perPage,
        ];

        return 'search_' . md5(json_encode($payload));
    }
}
