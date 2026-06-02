<?php

use App\Http\Controllers\BulkMailController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/pastes', [DashboardController::class, 'pastes'])->name('dashboard.pastes');
    Route::get('/dashboard/suggestions', [DashboardController::class, 'suggestions'])->name('dashboard.suggestions');
    
    // Upgrades Management
    Route::get('/dashboard/upgrades', [DashboardController::class, 'upgrades'])->name('dashboard.upgrades');
    Route::post('/dashboard/upgrades/{upgrade}/approve', [DashboardController::class, 'approveUpgrade'])->name('dashboard.upgrades.approve');
    Route::post('/dashboard/upgrades/{upgrade}/reject', [DashboardController::class, 'rejectUpgrade'])->name('dashboard.upgrades.reject');

    // Reports Management
    Route::get('/dashboard/reports', [DashboardController::class, 'reports'])->name('dashboard.reports');
    Route::post('/dashboard/reports/{report}/dismiss', [DashboardController::class, 'dismissReport'])->name('dashboard.reports.dismiss');
    Route::post('/dashboard/reports/{report}/edit-thread', [DashboardController::class, 'instantEditThread'])->name('dashboard.reports.edit-thread');

    // User Management Control Panel
    Route::get('/dashboard/users', [DashboardController::class, 'users'])->name('dashboard.users');
    Route::post('/dashboard/users/create-advertiser', [DashboardController::class, 'createAdvertiser'])->name('dashboard.users.create-advertiser');
    Route::post('/dashboard/users/{user}/update', [DashboardController::class, 'updateUser'])->name('dashboard.users.update');
    Route::post('/dashboard/users/{user}/toggle-ban', [DashboardController::class, 'toggleBan'])->name('dashboard.users.toggle-ban');
    Route::delete('/dashboard/users/{user}/delete', [DashboardController::class, 'deleteUser'])->name('dashboard.users.delete');

    // Bulk email (owner only — enforced in controller)
    Route::get('/dashboard/bulk-mail', [BulkMailController::class, 'index'])->name('dashboard.bulk-mail');
    Route::get('/dashboard/bulk-mail/recipients', [BulkMailController::class, 'recipients'])->name('dashboard.bulk-mail.recipients');
    Route::post('/dashboard/bulk-mail/send', [BulkMailController::class, 'sendBatch'])->name('dashboard.bulk-mail.send');
});
