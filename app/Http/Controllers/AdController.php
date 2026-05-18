<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ad;
use App\Models\AdRequest;
use App\Models\AdRequestRevision;
use Illuminate\Support\Facades\Auth;

class AdController extends Controller
{
    // Moderation Index
    public function moderationIndex()
    {
        $pendingAds = AdRequest::where('status', 'pending')->with('ad.campaign.advertiser')->get();
        return view('admin.ads.moderation', compact('pendingAds'));
    }

    public function approve(Ad $ad)
    {
        $ad->update(['status' => 'active']);
        AdRequest::where('ad_id', $ad->id)->update([
            'status' => 'approved',
            'moderator_id' => Auth::id()
        ]);
        return back()->with('success', 'Ad approved successfully.');
    }

    public function reject(Request $request, Ad $ad)
    {
        $ad->update(['status' => 'rejected']);
        AdRequest::where('ad_id', $ad->id)->update([
            'status' => 'rejected',
            'moderator_id' => Auth::id(),
            'moderator_notes' => $request->notes
        ]);
        return back()->with('success', 'Ad rejected.');
    }

    public function requestRevision(Request $request, Ad $ad)
    {
        $ad->update(['status' => 'pending']);
        $adRequest = AdRequest::where('ad_id', $ad->id)->first();
        if ($adRequest) {
            $adRequest->update([
                'status' => 'revision_requested',
                'moderator_id' => Auth::id()
            ]);
            AdRequestRevision::create([
                'ad_request_id' => $adRequest->id,
                'notes' => $request->notes,
                'user_id' => Auth::id()
            ]);
        }
        return back()->with('success', 'Revision requested.');
    }

    // Other standard resource methods...
    public function index() {}
    
    public function create() 
    {
        return view('advertiser.ads.create');
    }

    public function store(Request $request) 
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'target_url' => 'required|url',
            'contact' => 'required|string|max:255',
            'image' => 'required|image|max:5120' // max 5MB
        ]);

        $user = Auth::user();
        if (!$user) {
            // Find or create the dedicated anonymous advertiser user to preserve foreign key constraints
            $user = \App\Models\User::firstOrCreate(
                ['email' => 'anonymous-ads@doxme.local'],
                [
                    'username' => 'anonymous_advertiser',
                    'password' => bcrypt(\Illuminate\Support\Str::random(32)),
                ]
            );

            // Ensure identification role is bound
            if (!$user->identification) {
                \App\Models\Identification::create([
                    'user_id' => $user->id,
                    'role' => \App\Enum\Role::MEMBER,
                    'avatar_path' => 'upload/defaultAvatar.png',
                    'website' => 'N/A',
                    'bio' => 'Anonymous Ads Account',
                ]);
            }
        }

        $advertiser = \App\Models\Advertiser::firstOrCreate(
            ['user_id' => $user->id],
            ['company_name' => $user->username . ' Ads', 'balance' => 0.00]
        );

        $campaign = \App\Models\AdCampaign::firstOrCreate(
            ['advertiser_id' => $advertiser->id, 'name' => 'Default Campaign'],
            ['status' => 'active', 'total_budget' => 0, 'daily_budget' => 0]
        );

        $file = $request->file('image');
        $tempPath = $file->getRealPath();
        $imageInfo = getimagesize($tempPath);
        $path = null;

        if ($imageInfo) {
            $width = $imageInfo[0];
            $height = $imageInfo[1];
            $mime = $imageInfo['mime'];

            if ($mime === 'image/gif') {
                // Store animated GIFs directly to preserve animation
                $filename = 'banner_' . uniqid() . '.gif';
                $path = $file->storeAs('ads', $filename, 'public');
            } else {
                switch ($mime) {
                    case 'image/jpeg':
                    case 'image/jpg':
                        $srcImage = @imagecreatefromjpeg($tempPath);
                        break;
                    case 'image/png':
                        $srcImage = @imagecreatefrompng($tempPath);
                        break;
                    case 'image/webp':
                        $srcImage = @imagecreatefromwebp($tempPath);
                        break;
                    default:
                        $srcImage = null;
                }

                if ($srcImage) {
                    $targetWidth = 670;
                    $targetHeight = 76;

                    $dstImage = imagecreatetruecolor($targetWidth, $targetHeight);

                    // Preserve transparency
                    imagealphablending($dstImage, false);
                    imagesavealpha($dstImage, true);

                    imagecopyresampled($dstImage, $srcImage, 0, 0, 0, 0, $targetWidth, $targetHeight, $width, $height);

                    $filename = 'ads/banner_' . uniqid() . '.webp';
                    $storagePath = storage_path('app/public/' . $filename);

                    if (!file_exists(dirname($storagePath))) {
                        mkdir(dirname($storagePath), 0755, true);
                    }

                    imagewebp($dstImage, $storagePath, 85);

                    imagedestroy($srcImage);
                    imagedestroy($dstImage);

                    $path = $filename;
                }
            }
        }

        if (!$path) {
            $path = $request->file('image')->store('ads', 'public');
        }

        $ad = Ad::create([
            'title' => $request->title,
            'ad_campaign_id' => $campaign->id,
            'target_url' => $request->target_url,
            'contact' => $request->contact,
            'media_url' => '/storage/' . $path,
            'type' => 'banner', // Default to banner for this request
            'status' => 'pending'
        ]);

        AdRequest::create([
            'ad_id' => $ad->id,
            'status' => 'pending'
        ]);

        if (Auth::check()) {
            return redirect()->route('advertiser.dashboard')->with('success', 'Ad request submitted successfully.');
        } else {
            return back()->with('success', 'Your anonymous ad request has been successfully submitted and is pending moderator approval.');
        }
    }

    public function trackClick(Ad $ad)
    {
        $stats = \App\Models\AdStatistic::firstOrCreate(
            ['ad_id' => $ad->id, 'date' => now()->toDateString()],
            ['impressions' => 0, 'clicks' => 0, 'spent' => 0]
        );
        $stats->increment('clicks');

        return redirect()->away($ad->target_url);
    }

    public function show(Ad $ad) {}
    public function edit(Ad $ad) {}
    public function update(Request $request, Ad $ad) {}
    public function destroy(Ad $ad) {}
}
