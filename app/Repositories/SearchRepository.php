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
     */
    public function search(SearchQueryDTO $dto)
    {
        $driver = DB::getDriverName();
        $query = Pastebin::query()
            ->with(['user.identification'])
            ->where('visibility', $dto->visibility->value)
            ->whereNull('password') // Exclude password-protected pastebins
            ->where('is_self_destruct', false); // Exclude self-destructing/burned pastes

        // Filter: Author Name
        if ($dto->author) {
            $query->where('author_name', 'like', '%' . $dto->author . '%');
        }

        // Filter: Content Length
        if ($dto->minLength !== null) {
            $query->whereRaw('LENGTH(content) >= ?', [$dto->minLength]);
        }
        if ($dto->maxLength !== null) {
            $query->whereRaw('LENGTH(content) <= ?', [$dto->maxLength]);
        }

        // Filter: Date Range
        if ($dto->dateRange) {
            $date = match ($dto->dateRange) {
                '24h' => now()->subDay(),
                '7d' => now()->subWeek(),
                '30d' => now()->subMonth(),
                default => null
            };
            if ($date) {
                $query->where('created_at', '>=', $date);
            }
        }

        // Search logic based on query presence
        if ($dto->query) {
            if ($driver === 'mysql' || $driver === 'mariadb') {
                // MariaDB/MySQL high-performance FULLTEXT BOOLEAN mode matching
                $parsedQuery = $this->parseBooleanQuery($dto->query);
                
                $query->whereRaw(
                    "MATCH(title, description, content) AGAINST(? IN BOOLEAN MODE)",
                    [$parsedQuery]
                );

                // Add raw relevancy score
                $query->select('*')
                    ->selectRaw(
                        "(MATCH(title) AGAINST(?)*3.0 + MATCH(description) AGAINST(?)*1.5 + MATCH(content) AGAINST(?)*0.8) as db_relevance",
                        [$dto->query, $dto->query, $dto->query]
                    );
            } else {
                // SQLite/other fallback using partial keyword matching
                $words = explode(' ', $dto->query);
                $query->where(function (Builder $q) use ($words) {
                    foreach ($words as $word) {
                        $q->where(function (Builder $sub) use ($word) {
                            $sub->where('title', 'like', '%' . $word . '%')
                                ->orWhere('description', 'like', '%' . $word . '%')
                                ->orWhere('content', 'like', '%' . $word . '%');
                        });
                    }
                });
                $query->select('*')->selectRaw('0 as db_relevance');
            }
        } else {
            $query->select('*')->selectRaw('0 as db_relevance');
        }

        // Sorting
        $query = match ($dto->sortBy) {
            'views' => $query->orderBy('views_count', 'desc'),
            'downloads' => $query->orderBy('download_count', 'desc'),
            'date_asc' => $query->orderBy('created_at', 'asc'),
            'date_desc' => $query->orderBy('created_at', 'desc'),
            'content_length' => $query->orderByRaw('LENGTH(content) desc'),
            default => $dto->query 
                ? $query->orderBy('db_relevance', 'desc') 
                : $query->orderBy('created_at', 'desc')
        };

        // Cursor Pagination fallback / manual limit
        if ($dto->cursor) {
            $query->where('id', '<', $dto->cursor);
        }

        return $query->limit($dto->perPage + 1)->get(); // Get 1 extra item to check for next page
    }

    /**
     * Get trending pastes based on views and downloads.
     */
    public function getTrending(int $limit = 20)
    {
        return Pastebin::query()
            ->with(['user.identification'])
            ->where('visibility', 'public')
            ->whereNull('password')
            ->where('is_self_destruct', false)
            ->where('created_at', '>=', now()->subDays(14)) // Recent hotness limit
            ->select('*')
            ->selectRaw('(views_count + download_count * 2) as hotness')
            ->orderBy('hotness', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Translate clean search syntax to Boolean Fulltext Mode syntax.
     * e.g., "word1 AND word2 NOT word3" -> "+word1 +word2 -word3"
     */
    private function parseBooleanQuery(string $query): string
    {
        // Extract phrases inside double quotes
        preg_match_all('/"([^"]+)"/', $query, $matches);
        $phrases = $matches[0];
        $remaining = preg_replace('/"([^"]+)"/', '', $query);

        // Split remaining search tokens
        $tokens = preg_split('/\s+/', trim($remaining), -1, PREG_SPLIT_NO_EMPTY);
        $parsed = [];

        foreach ($phrases as $phrase) {
            $parsed[] = '+' . $phrase; // Phrases are treated as mandatory
        }

        $skipNext = false;
        for ($i = 0; $i < count($tokens); $i++) {
            if ($skipNext) {
                $skipNext = false;
                continue;
            }

            $token = strtoupper($tokens[$i]);

            if ($token === 'AND') {
                if (isset($tokens[$i + 1])) {
                    $parsed[] = '+' . $tokens[$i + 1];
                    $skipNext = true;
                }
            } elseif ($token === 'NOT' || $token === '-') {
                if (isset($tokens[$i + 1])) {
                    $parsed[] = '-' . $tokens[$i + 1];
                    $skipNext = true;
                }
            } elseif ($token === 'OR') {
                // MySQL's default behavior for terms without +/- is OR
                if (isset($tokens[$i + 1])) {
                    $parsed[] = $tokens[$i + 1];
                    $skipNext = true;
                }
            } else {
                // If it's a standard term, append it
                $parsed[] = $tokens[$i];
            }
        }

        return implode(' ', $parsed);
    }
}
