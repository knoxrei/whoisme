<?php

namespace App\Services;

use App\Models\Pastebin;
use Illuminate\Support\Collection;

class RankingService
{
    /**
     * Score and sort matching pastebins using content relevance, popularity, and freshness.
     */
    public function rank(Collection $pastebins, ?string $query): Collection
    {
        if ($pastebins->isEmpty()) {
            return $pastebins;
        }

        $queryLower = $query ? mb_strtolower(trim($query)) : '';

        return $pastebins->map(function (Pastebin $paste) use ($queryLower) {
            $score = floatval($paste->db_relevance);

            // 1. Exact Match Boost: if title is exactly the query
            $titleLower = mb_strtolower($paste->title);
            if ($queryLower && $titleLower === $queryLower) {
                $score += 150.0; // Mega boost for exact title matches
            } elseif ($queryLower && mb_strpos($titleLower, $queryLower) !== false) {
                $score += 50.0; // Partial title match boost
            }

            // 2. Popularity Score: logarithmic growth to prevent views from dominating
            // log(views + 1) * 2.0
            $viewsBoost = log($paste->views_count + 1) * 2.0;
            // log(downloads + 1) * 3.0
            $downloadsBoost = log($paste->download_count + 1) * 3.0;
            
            $score += $viewsBoost + $downloadsBoost;

            // 3. Freshness Score: decay based on days old
            // Formula: 50 / (1 + days_old)
            $daysOld = max(0, $paste->created_at->diffInDays(now()));
            $freshnessScore = 40.0 / (1.0 + $daysOld);
            
            $score += $freshnessScore;

            // Assign computed rank score to model temporary property
            $paste->rank_score = round($score, 2);

            return $paste;
        })->sortByDesc('rank_score')->values();
    }
}
