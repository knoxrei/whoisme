<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UpdateLastActive
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check()) {
            $user = auth()->user();
            
            // Track login time in cache and session
            if (!\Illuminate\Support\Facades\Cache::has("user:login_time:{$user->id}")) {
                \Illuminate\Support\Facades\Cache::put("user:login_time:{$user->id}", now(), now()->addHours(2));
            }
            if (!session()->has('login_time')) {
                session()->put('login_time', now());
            }

            // Only update if last_active is null or it's been at least 1 minute
            if (!$user->last_active || $user->last_active->diffInMinutes(now()) >= 1) {
                $user->timestamps = false; // Prevent updated_at from being touched
                $user->forceFill(['last_active' => now()])->save();
            }
        }

        return $next($request);
    }
}
