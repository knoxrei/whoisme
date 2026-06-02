<?php

namespace App\Repositories;

use App\DTOs\SearchQueryDTO;
use App\Models\Pastebin;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class SearchRepository
{
    /**
     * Search and filter pastebins based on DTO options.
     * Fully optimized for MariaDB FULLTEXT + composite index usage.
     */
    public function search(SearchQueryDTO $dto)
    {
        $driver = DB::getDriverName();

        $query = Pastebin::query()
            ->with(['user.identification'])
            ->where('visibility', $dto->visibility->value)
            ->whereNull('password')
            ->where('is_self_destruct', false);

        // Filter: Author Name (uses idx_pastebins_author)
        if ($dto->author) {
            $query->where('author_name', 'like', $dto->author . '%'); // prefix match is faster
        }

        // Filter: Content Length
        if ($dto->minLength !== null) {
            $query->whereRaw('LENGTH(content) >= ?', [$dto->minLength]);
        }
        if ($dto->maxLength !== null) {
            $query->whereRaw('LENGTH(content) <= ?', [$dto->maxLength]);
        }

        // Filter: Date Range (uses idx_pastebins_feed covering created_at)
        if ($dto->dateRange) {
            $date = match ($dto->dateRange) {
                '24h' => now()->subDay(),
                '7d'  => now()->subWeek(),
                '30d' => now()->subMonth(),
                default => null
            };
            if ($date) {
                $query->where('created_at', '>=', $date);
            }
        }

        // Full-text search logic
        if ($dto->query) {
            if ($driver === 'mysql' || $driver === 'mariadb') {
                $parsedQuery = $this->parseBooleanQuery($dto->query);

                if ($parsedQuery !== '') {
                    // Combined FULLTEXT index scan (pastebins_search_fulltext)
                    $query->whereRaw(
                        "MATCH(title, description, content) AGAINST(? IN BOOLEAN MODE)",
                        [$parsedQuery]
                    );

                    // Weighted relevance: title × 5, description × 2, content × 1
                    // Use $parsedQuery (not raw $dto->query) to keep SQL valid
                    $query->select('pastebins.*')
                        ->selectRaw(
                            "(MATCH(title) AGAINST(? IN BOOLEAN MODE) * 5.0
                            + MATCH(description) AGAINST(? IN BOOLEAN MODE) * 2.0
                            + MATCH(content) AGAINST(? IN BOOLEAN MODE) * 1.0) as db_relevance",
                            [$parsedQuery, $parsedQuery, $parsedQuery]
                        );
                } else {
                    // Parsed query is empty (e.g. input was only special chars like "--")
                    $query->select('pastebins.*')->selectRaw('0 as db_relevance');
                }
            } else {
                // SQLite fallback: LIKE search
                $words = preg_split('/\s+/', trim($dto->query), -1, PREG_SPLIT_NO_EMPTY);
                $query->where(function (Builder $q) use ($words) {
                    foreach ($words as $word) {
                        $q->where(function (Builder $sub) use ($word) {
                            $sub->where('title', 'like', '%' . $word . '%')
                                ->orWhere('description', 'like', '%' . $word . '%')
                                ->orWhere('content', 'like', '%' . $word . '%');
                        });
                    }
                });
                $query->select('pastebins.*')->selectRaw('0 as db_relevance');
            }
        } else {
            $query->select('pastebins.*')->selectRaw('0 as db_relevance');
        }

        // Cursor pagination (always applied before ORDER BY to keep query fast)
        if ($dto->cursor) {
            $query->where('id', '<', $dto->cursor);
        }

        // Sorting strategy
        $query = match ($dto->sortBy) {
            'views'          => $query->orderBy('views_count', 'desc')->orderBy('id', 'desc'),
            'downloads'      => $query->orderBy('download_count', 'desc')->orderBy('id', 'desc'),
            'date_asc'       => $query->orderBy('created_at', 'asc')->orderBy('id', 'asc'),
            'date_desc'      => $query->orderBy('created_at', 'desc')->orderBy('id', 'desc'),
            'content_length' => $query->orderByRaw('LENGTH(content) desc')->orderBy('id', 'desc'),
            default => $dto->query
                ? $query->orderBy('db_relevance', 'desc')->orderBy('id', 'desc')
                : $query->orderBy('created_at', 'desc')->orderBy('id', 'desc'),
        };

        return $query->limit($dto->perPage + 1)->get();
    }

    /**
     * Fast FULLTEXT-powered autocomplete: returns matching titles only.
     * Extremely cheap query — only fetches id, title, slug, author_name.
     */
    public function suggest(string $query, int $limit = 8): array
    {
        $driver = DB::getDriverName();
        $safe   = substr(trim($query), 0, 100);

        if ($driver === 'mysql' || $driver === 'mariadb') {
            $parsed = $this->parseBooleanQuery($safe . '*'); // wildcard for prefix match
            return Pastebin::query()
                ->select(['id', 'title', 'slug', 'author_name'])
                ->where('visibility', 'public')
                ->whereNull('password')
                ->where('is_self_destruct', false)
                ->whereRaw("MATCH(title) AGAINST(? IN BOOLEAN MODE)", [$parsed])
                ->orderByRaw("MATCH(title) AGAINST(? IN BOOLEAN MODE) DESC", [$parsed])
                ->limit($limit)
                ->get()
                ->toArray();
        }

        // SQLite fallback
        return Pastebin::query()
            ->select(['id', 'title', 'slug', 'author_name'])
            ->where('visibility', 'public')
            ->whereNull('password')
            ->where('is_self_destruct', false)
            ->where('title', 'like', '%' . $safe . '%')
            ->orderBy('views_count', 'desc')
            ->limit($limit)
            ->get()
            ->toArray();
    }

    /**
     * Get trending pastes based on views and downloads (hotness algorithm).
     */
    public function getTrending(int $limit = 20)
    {
        return Pastebin::query()
            ->with(['user.identification'])
            ->where('visibility', 'public')
            ->whereNull('password')
            ->where('is_self_destruct', false)
            ->where('created_at', '>=', now()->subDays(14))
            ->select('pastebins.*')
            ->selectRaw('(views_count * 1.0 + download_count * 2.5) as hotness')
            ->orderBy('hotness', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Site-wide indexed paste stats (cached externally by SearchService).
     */
    public function getSiteStats(): array
    {
        $row = DB::table('pastebins')
            ->where('visibility', 'public')
            ->whereNull('password')
            ->where('is_self_destruct', false)
            ->selectRaw('COUNT(*) as total, SUM(views_count) as total_views, SUM(download_count) as total_downloads')
            ->first();

        $totalUsers = (int) \App\Models\User::query()->count();

        return [
            'total'           => (int) ($row->total ?? 0),
            'total_views'     => (int) ($row->total_views ?? 0),
            'total_downloads' => (int) ($row->total_downloads ?? 0),
            'total_users'     => $totalUsers,
        ];
    }

    /**
     * Translate clean search syntax to Boolean Fulltext Mode syntax.
     * e.g., "word1 AND word2 NOT word3" -> "+word1 +word2 -word3"
     */
    public function parseBooleanQuery(string $query): string
    {
        // Extract phrases inside double quotes
        preg_match_all('/"([^"]+)"/', $query, $matches);
        $phrases   = $matches[0];
        $remaining = preg_replace('/"([^"]+)"/', '', $query);

        $tokens = preg_split('/\s+/', trim($remaining), -1, PREG_SPLIT_NO_EMPTY);
        $parsed = [];

        foreach ($phrases as $phrase) {
            $parsed[] = '+' . $phrase;
        }

        $skipNext = false;
        for ($i = 0; $i < count($tokens); $i++) {
            if ($skipNext) { $skipNext = false; continue; }

            $token = strtoupper($tokens[$i]);

            if ($token === 'AND') {
                if (isset($tokens[$i + 1])) { $parsed[] = '+' . $tokens[$i + 1]; $skipNext = true; }
            } elseif ($token === 'NOT' || $token === '-') {
                if (isset($tokens[$i + 1])) { $parsed[] = '-' . $tokens[$i + 1]; $skipNext = true; }
            } elseif ($token === 'OR') {
                if (isset($tokens[$i + 1])) { $parsed[] = $tokens[$i + 1]; $skipNext = true; }
            } else {
                // Escape special FULLTEXT chars but keep wildcard *
                $clean = preg_replace('/[+\-><()~@"]/', '', $tokens[$i]);
                if ($clean) $parsed[] = $clean;
            }
        }

        return implode(' ', $parsed);
    }
}
