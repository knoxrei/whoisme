<?php

namespace App\Http\Middleware;

use App\Helper\VisitorTracker;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TrackSiteVisitor
{
    /**
     * Paths that should not update the site-wide visitor list.
     *
     * @var list<string>
     */
    protected array $except = [
        'up',
        'favicon.ico',
        'favicon-32x32.png',
        'favicon-16x16.png',
        'apple-touch-icon.png',
        'site.webmanifest',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        if ($this->shouldTrack($request)) {
            VisitorTracker::trackRoot();
        }

        return $next($request);
    }

    protected function shouldTrack(Request $request): bool
    {
        if (!$request->isMethodSafe()) {
            return false;
        }

        foreach ($this->except as $pattern) {
            if ($request->is($pattern)) {
                return false;
            }
        }

        if ($request->routeIs('pastebin.raw', 'pastebin.download')) {
            return false;
        }

        return true;
    }
}
