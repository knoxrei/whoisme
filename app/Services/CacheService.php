<?php

namespace App\Services;

use App\DTOs\SearchQueryDTO;
use Illuminate\Support\Facades\Cache;

class CacheService
{
    /**
     * Get or set search results in the cache.
     */
    public function remember(SearchQueryDTO $dto, \Closure $callback)
    {
        $cacheKey = $this->generateCacheKey($dto);
        
        // Cache results for 10 minutes (600 seconds)
        return Cache::remember($cacheKey, 600, $callback);
    }

    /**
     * Clear cached searches when a new paste is published or updated to ensure data consistency.
     */
    public function flushSearchCache(): void
    {
        // For simple caching, we rely on TTL, but we can flush general tags if using Redis
        if (config('cache.default') === 'redis') {
            Cache::tags(['search_results'])->flush();
        }
    }

    /**
     * Compute a unique cryptographic key for the search criteria.
     */
    private function generateCacheKey(SearchQueryDTO $dto): string
    {
        $payload = [
            'q' => $dto->query,
            'author' => $dto->author,
            'sort' => $dto->sortBy,
            'date' => $dto->dateRange,
            'min' => $dto->minLength,
            'max' => $dto->maxLength,
            'cursor' => $dto->cursor,
            'limit' => $dto->perPage
        ];

        return 'search_' . md5(json_encode($payload));
    }
}
