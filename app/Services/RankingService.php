<?php

namespace App\Services;

use App\Models\Pastebin;
use Illuminate\Support\Collection;

class RankingService
{
    /**
     * Score and sort matching pastebins using a multi-signal ranking formula.
     *
     * Signals:
     *  1. DB FULLTEXT relevance (already weighted in SQL: title×5, desc×2, content×1)
     *  2. Title exact / phrase boost
     *  3. Popularity (logarithmic views + downloads)
     *  4. Freshness decay (1 / (1 + days_old))
     *  5. Comment activity signal
     */
    public function rank(Collection $pastebins, ?string $query): Collection
    {
        if ($pastebins->isEmpty()) {
            return $pastebins;
        }

        $queryLower = $query ? mb_strtolower(trim($query)) : '';
        // Extract individual terms for partial matching
        $terms = $queryLower
            ? array_filter(
                preg_split('/\s+/', preg_replace('/"([^"]+)"/', '$1', $queryLower)),
                fn ($t) => mb_strlen($t) > 2
              )
            : [];

        return $pastebins->map(function (Pastebin $paste) use ($queryLower, $terms) {
            // Base: weighted DB relevance score (already boosted in SQL)
            $score = floatval($paste->db_relevance ?? 0);

            if ($queryLower) {
                $titleLower = mb_strtolower($paste->title);

                // Exact title match — strongest possible signal
                if ($titleLower === $queryLower) {
                    $score += 200.0;
                } elseif (mb_strpos($titleLower, $queryLower) !== false) {
                    // Full phrase found in title
                    $score += 80.0;
                } else {
                    // Count how many individual terms appear in title
                    $termHits = 0;
                    foreach ($terms as $term) {
                        if (mb_strpos($titleLower, $term) !== false) {
                            $termHits++;
                        }
                    }
                    if ($termHits > 0) {
                        $score += $termHits * 15.0;
                    }
                }
            }

            // Popularity: log-dampened to prevent viral posts dominating completely
            $score += log($paste->views_count + 1) * 2.5;
            $score += log($paste->download_count + 1) * 4.0;

            // Freshness decay: value = 60 / (1 + days_old^0.7)
            // Smoother than integer days — uses fractional hours for recent posts
            $hoursOld = max(0, $paste->created_at->diffInMinutes(now()) / 60.0);
            $score += 60.0 / (1.0 + pow($hoursOld / 24.0, 0.7));

            $paste->rank_score = round($score, 2);
            return $paste;
        })->sortByDesc('rank_score')->values();
    }
}
