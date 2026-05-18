<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AdCampaign;

class AdCampaignController extends Controller
{
    public function suspend(AdCampaign $campaign)
    {
        $campaign->update(['status' => 'suspended']);
        $campaign->ads()->update(['status' => 'paused']);
        return back()->with('success', 'Campaign suspended.');
    }

    public function index() {}
    public function create() {}
    public function store(Request $request) {}
    public function show(AdCampaign $campaign) {}
    public function edit(AdCampaign $campaign) {}
    public function update(Request $request, AdCampaign $campaign) {}
    public function destroy(AdCampaign $campaign) {}
}
