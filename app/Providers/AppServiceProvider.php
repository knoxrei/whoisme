<?php

namespace App\Providers;

use App\Models\Identification;
use App\Models\Pastebin;
use App\Policies\PostPastebin;
use App\Policies\PostProfile;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::policy(Identification::class, PostProfile::class);
        Gate::policy(Pastebin::class, PostPastebin::class);
        Gate::policy(\App\Models\User::class, \App\Policies\UserPolicy::class);
    }
}
