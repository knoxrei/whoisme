<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Advertiser;
use App\Models\AdCampaign;
use App\Models\AdStatistic;
use Illuminate\Support\Facades\Auth;

class AdvertiserDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $advertiser = Advertiser::firstOrCreate(
            ['user_id' => $user->id],
            ['company_name' => $user->username . ' Ads', 'balance' => 0.00]
        );

        $campaigns = AdCampaign::where('advertiser_id', $advertiser->id)->with('ads')->get();
        
        $totalSpent = AdStatistic::whereIn('ad_id', $campaigns->pluck('ads')->flatten()->pluck('id'))->sum('spent');
        $totalImpressions = AdStatistic::whereIn('ad_id', $campaigns->pluck('ads')->flatten()->pluck('id'))->sum('impressions');
        $totalClicks = AdStatistic::whereIn('ad_id', $campaigns->pluck('ads')->flatten()->pluck('id'))->sum('clicks');

        // Fetch last 7 days of daily impressions & clicks for chart
        $adIds = $campaigns->pluck('ads')->flatten()->pluck('id');
        $chartData = AdStatistic::whereIn('ad_id', $adIds)
            ->where('date', '>=', now()->subDays(6)->toDateString())
            ->selectRaw('date, sum(impressions) as impressions, sum(clicks) as clicks')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $chartLabels = [];
        $chartImpressions = [];
        $chartClicks = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->toDateString();
            $label = now()->subDays($i)->format('D');
            $stat = $chartData->firstWhere('date', $date);

            $chartLabels[] = $label;
            $chartImpressions[] = $stat ? (int)$stat->impressions : 0;
            $chartClicks[] = $stat ? (int)$stat->clicks : 0;
        }

        return view('advertiser.dashboard', compact(
            'advertiser', 'campaigns', 'totalSpent', 'totalImpressions', 'totalClicks',
            'chartLabels', 'chartImpressions', 'chartClicks'
        ));
    }
}
