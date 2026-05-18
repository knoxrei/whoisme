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

        return view('advertiser.dashboard', compact('advertiser', 'campaigns', 'totalSpent', 'totalImpressions', 'totalClicks'));
    }
}
