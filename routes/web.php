<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\PastebinController;
use App\Http\Controllers\PastebinEditController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UpgradeController;
use App\Http\Controllers\ValidateGate;
use App\Http\Controllers\AdvertiserDashboardController;
use App\Http\Controllers\AdCampaignController;
use App\Http\Controllers\AdController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\GateController;
use Illuminate\Support\Facades\Route;




// GATE PORTAL AND CONNECTION REDIRECTS
Route::get('/gate', [GateController::class, 'index'])->name('gate.index');
Route::get('/gate/tor', [GateController::class, 'tor'])->name('gate.tor');
Route::get('/gate/clearnet', [GateController::class, 'clearnet'])->name('gate.clearnet');

// GATE LOGIN AND REGISTER FUNCTION
Route::prefix('register')->middleware('guest')->name('register.')->group(function () {
    Route::get('/', [ValidateGate::class, 'register'])->name('index');
    Route::post('/', [ValidateGate::class, 'registerStore'])->name('store');
});

Route::prefix('login')->middleware('guest')->group(function () {
    Route::get('/', [ValidateGate::class, 'login'])->name('login');
    Route::post('/', [ValidateGate::class, 'loginStore'])->name('login.store');
});

Route::middleware('guest')->group(function () {
    Route::get('/forgot-password', [ValidateGate::class, 'forgotPassword'])->name('password.request');
    Route::post('/forgot-password', [ValidateGate::class, 'forgotPasswordStore'])->name('password.email');
    Route::get('/reset-password', [ValidateGate::class, 'resetPassword'])->name('password.reset');
    Route::post('/reset-password', [ValidateGate::class, 'resetPasswordStore'])->name('password.update');
});

Route::post('/logout', [ValidateGate::class, 'logout'])->name('logout')->middleware('auth');

// EMAIL VERIFICATION ROUTES FOR PENDING REGISTRATION
Route::get('/verify-registration', [ValidateGate::class, 'verifyRegistrationShow'])->name('verify.registration.show');
Route::post('/verify-registration', [ValidateGate::class, 'verifyRegistrationPost'])->name('verify.registration.post');
Route::post('/verify-registration/resend', [ValidateGate::class, 'verifyRegistrationResend'])->name('verify.registration.resend');


// Search Engine Routes
Route::get('/', [SearchController::class, 'index'])->name('welcome');
Route::get('/search', [SearchController::class, 'index'])->name('search.index');
Route::get('/advanced-search', [SearchController::class, 'advanced'])->name('search.advanced');
Route::get('/trending', [SearchController::class, 'trending'])->name('search.trending');
Route::get('/recent', [SearchController::class, 'recent'])->name('search.recent');
Route::get('/search/suggest', [SearchController::class, 'suggest'])->name('search.suggest');
Route::get('/search/stats', [SearchController::class, 'stats'])->name('search.stats');

// Root Page Visitor Tracking
Route::post('/visitors/root', [SearchController::class, 'trackRootVisit'])->name('visitors.root.track');
Route::get('/visitors/root', [SearchController::class, 'getRootVisitors'])->name('visitors.root.get');

// Pastebin List
Route::get('/pastebins', [\App\Http\Controllers\PastebinListController::class, 'index'])->name('pastebin.list');


Route::get('/support', function () {
    return view('support');
})->name('support');

Route::get('/terms', function () {
    return view('terms');
})->name('terms');

Route::get('/about', function () {
    return view('about');
})->name('about');

Route::get('/advertise', function () {
    return view('advertise');
})->name('advertise');

Route::post('/advertise', [\App\Http\Controllers\AdController::class, 'store'])->name('advertise.store');

Route::prefix('pastebin')->name('pastebin.')->group(function () {
    Route::get('/', [PastebinController::class, 'index'])->name('create');
    Route::post('/', [PastebinController::class, 'store'])->name('store');
    Route::get('/{slug}', [PastebinController::class, 'show'])->name('show');
    Route::get('/{slug}/raw', [PastebinController::class, 'raw'])->name('raw');
    Route::get('/{slug}/download', [PastebinController::class, 'download'])->name('download');
    Route::post('/{slug}/unlock', [PastebinController::class, 'unlock'])->name('unlock');
    Route::post('/{slug}/report', [PastebinController::class, 'report'])->name('report');

    // Visitor tracking (AJAX)
    Route::post('/{slug}/visit', [PastebinController::class, 'trackVisit'])->name('visit');
    Route::get('/{slug}/visitors', [PastebinController::class, 'getVisitors'])->name('visitors');

    // Suggested Edits
    Route::middleware('auth')->group(function () {
        Route::put('/{pastebin}', [PastebinController::class, 'update'])->name('update');
        Route::delete('/{pastebin}', [PastebinController::class, 'destroy'])->name('destroy');
        Route::post('/{pastebin}/edit', [PastebinEditController::class, 'store'])->name('edit.store');
        Route::post('/edit/{edit}/approve', [PastebinEditController::class, 'approve'])->name('edit.approve');
        Route::post('/edit/{edit}/reject', [PastebinEditController::class, 'reject'])->name('edit.reject');
        Route::post('/{pastebin}/comments', [CommentController::class, 'store'])->name('comments.store');
    });
});

Route::prefix('upgrade')->name('upgrade.')->group(function () {
    Route::get('/', [UpgradeController::class, 'index'])->name('index');
    Route::post('/{role}', [UpgradeController::class, 'purchase'])->name('purchase');
});


// Profile Routes
Route::get('/users', [ProfileController::class, 'usersList'])->middleware('auth')->name('profile.users-list');
Route::get('/user-{username}', [ProfileController::class, 'show'])->name('profile.show');
Route::get('/user-{username}/pastebins', [ProfileController::class, 'allPastebins'])->name('profile.pastebins');
Route::get('/user-{username}/posts', [ProfileController::class, 'allPosts'])->name('profile.posts');


Route::middleware('auth')->group(function () {
    Route::get('/settings/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/settings/profile', [ProfileController::class, 'update'])->name('profile.update');

    // Email verification from settings
    Route::post('/settings/send-verify-email', [ValidateGate::class, 'sendEmailVerification'])->name('profile.send.verify.email');
    Route::post('/settings/verify-email', [ValidateGate::class, 'confirmEmailVerification'])->name('profile.verify.email');

    // Follow Routes
    Route::post('/user/{user}/follow', [FollowController::class, 'follow'])->name('user.follow');
    Route::post('/user/{user}/unfollow', [FollowController::class, 'unfollow'])->name('user.unfollow');

    // Administrative Moderation
    Route::post('/user/{user}/ban', [ProfileController::class, 'ban'])->name('profile.ban');
    Route::post('/user/{user}/unban', [ProfileController::class, 'unban'])->name('profile.unban');
});

// Public Ad Routes
Route::get('/ads/{ad}/click', [App\Http\Controllers\AdController::class, 'trackClick'])->name('ads.click');

// Advertiser Routes
Route::middleware('auth')->prefix('advertiser')->name('advertiser.')->group(function () {
    Route::get('/dashboard', [AdvertiserDashboardController::class, 'index'])->name('dashboard');
    Route::resource('campaigns', AdCampaignController::class);
    Route::resource('ads', AdController::class);
    Route::post('/ads/{ad}/request-approval', [AdController::class, 'requestApproval'])->name('ads.request_approval');
});

// Admin Ad Moderation
Route::middleware(['auth', 'role:owner'])->prefix('admin/ads')->name('admin.ads.')->group(function () {
    Route::get('/moderation', [AdController::class, 'moderationIndex'])->name('moderation.index');
    Route::post('/create-manual', [AdController::class, 'storeManual'])->name('store_manual');
    Route::post('/{ad}/approve', [AdController::class, 'approve'])->name('approve');
    Route::post('/{ad}/reject', [AdController::class, 'reject'])->name('reject');
    Route::post('/{ad}/request-revision', [AdController::class, 'requestRevision'])->name('request_revision');
    
    // Administrative direct controls
    Route::post('/{ad}/activate', [AdController::class, 'activateAd'])->name('activate');
    Route::post('/{ad}/suspend', [AdController::class, 'suspendAd'])->name('suspend');
    Route::delete('/{ad}/delete', [AdController::class, 'deleteAd'])->name('delete');
    
    Route::post('/campaign/{campaign}/suspend', [AdCampaignController::class, 'suspend'])->name('campaigns.suspend');
});

require __DIR__ . '/dashboard.php';
