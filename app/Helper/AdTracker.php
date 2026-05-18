<?php

namespace App\Helper;

use App\Models\Ad;
use App\Models\AdStatistic;

class AdTracker
{
    /**
     * Get a set of random active ads, ensuring min/max count and incrementing impressions.
     *
     * @param int $limit
     * @param int $min
     * @return \Illuminate\Support\Collection
     */
    public static function getBanners($limit = 4, $min = 2)
    {
        $ads = Ad::where('status', 'active')->inRandomOrder()->get();

        if ($ads->isEmpty()) {
            return collect();
        }

        $result = collect();
        while ($result->count() < $min && $ads->isNotEmpty()) {
            $result = $result->concat($ads);
        }

        $finalAds = $result->take($limit);

        // Track impressions
        $today = now()->toDateString();
        foreach ($finalAds as $ad) {
            AdStatistic::firstOrCreate(
                ['ad_id' => $ad->id, 'date' => $today],
                ['impressions' => 0, 'clicks' => 0, 'spent' => 0]
            )->increment('impressions');
        }

        return $finalAds;
    }
}
