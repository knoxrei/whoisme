<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

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
            if (!session()->has('anonuser')) {
                $firstname = 'Anonymous';
                $lastname = ['redHat', "blueHat", "greenHat", "purpleHat", "blackHat", "brownHat", "whiteHat"];
                $lastnameRandom = collect($lastname)->random();
                $userName = "{$firstname}-{$lastnameRandom}" . Str::random(4);
                session()->put('anonuser', $userName); // 24 hours
            }
        }
        return $next($request);
    }
}
