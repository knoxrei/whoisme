<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Cookie;

class HandleAnonymousUser
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            $anonId = $request->cookie('anon_tracker_id');
            $anonName = $request->cookie('anon_name');

            if (!$anonId) {
                $anonId = (string) Str::uuid();
                Cookie::queue('anon_tracker_id', $anonId, 60 * 24 * 30); // 30 days
            }

            if (!$anonName) {
                $firstname = 'Anonymous';
                $lastname = ['redHat', 'blueHat', 'greenHat', 'purpleHat', 'blackHat', 'brownHat', 'whiteHat'];
                $lastnameRandom = collect($lastname)->random();
                $anonName = "{$firstname}-{$lastnameRandom}" . Str::upper(Str::random(4));
                Cookie::queue('anon_name', $anonName, 60 * 24 * 30); // 30 days
            }

            // Make available in current request (before browser sends queued cookie back).
            $request->attributes->set('anon_tracker_id', $anonId);
            $request->attributes->set('anon_name', $anonName);
        }
        return $next($request);
    }
}
