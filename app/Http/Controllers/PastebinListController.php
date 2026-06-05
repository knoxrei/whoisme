<?php

namespace App\Http\Controllers;

use App\Models\Pastebin;
use App\Models\PinnedPastebin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class PastebinListController extends Controller
{
    public function index(Request $request)
    {
        $title         = 'All Pastebins';
        $query         = $request->input('q');
        $orderBy       = $request->input('order_by', 'created_at');
        $orderDirection = $request->input('order_direction', 'desc');

        // Pinned pastebins — always load on page (even when searching, so blade always has it)
        $pinnedPastebins = PinnedPastebin::with([
            'pastebin' => fn ($q) => $q->withCount('comments')->with(['user.identification', 'images']),
        ])
        ->orderBy('sort_order')
        ->get()
        ->filter(fn ($pin) => $pin->pastebin !== null);

        $pastebinsQuery = Pastebin::where('visibility', 'public')
            ->whereNull('password')
            ->where('is_self_destruct', false);

        if ($query) {
            $pastebinsQuery->where('title', 'like', "%{$query}%");
        }

        // Validate order_by
        $allowedOrderBy = [
            'created_at'     => 'Published',
            'views_count'    => 'Views',
            'download_count' => 'Downloads',
            'comments_count' => 'Comments',
        ];

        if (!array_key_exists($orderBy, $allowedOrderBy)) {
            $orderBy = 'created_at';
        }

        $pastebins = $pastebinsQuery
            ->with(['user', 'user.identification', 'pinnedRecord'])
            ->withCount('comments')
            ->orderBy($orderBy, $orderDirection === 'asc' ? 'asc' : 'desc')
            ->paginate(15)
            ->withQueryString();

        return view('pastebin.index', compact(
            'title', 'pastebins', 'pinnedPastebins',
            'query', 'orderBy', 'orderDirection', 'allowedOrderBy'
        ));
    }

    /**
     * JSON endpoint for realtime search.
     * Rate limiting is session-based (NOT IP-based) — safe for Tor users.
     *
     * Trigger: 3+ requests with < 2 second interval → soft block (return 429).
     * Recovery: 5 seconds of silence resets the counter.
     */
    public function search(Request $request)
    {
        // ── Rate Limiting (session-based, Tor-safe) ────────────────────────────
        $sessionId   = session()->getId();
        $lastKey     = "rl_last_{$sessionId}";
        $countKey    = "rl_count_{$sessionId}";
        $blockedKey  = "rl_blocked_{$sessionId}";

        // Check if currently soft-blocked
        if (Cache::has($blockedKey)) {
            $retryAfter = Cache::get($blockedKey);
            return response()->json([
                'rate_limited' => true,
                'message'      => 'Terlalu banyak request. Tunggu sebentar sebelum mencari lagi.',
                'retry_after'  => $retryAfter,
            ], 429)->header('Retry-After', $retryAfter);
        }

        $now      = microtime(true);
        $lastTime = Cache::get($lastKey, 0);
        $count    = Cache::get($countKey, 0);

        $interval = $now - $lastTime;

        if ($interval < 2.0) {
            // Rapid fire detected
            $count++;
            Cache::put($countKey, $count, now()->addSeconds(10));

            if ($count >= 3) {
                // Activate soft block for 5 seconds
                Cache::put($blockedKey, 5, now()->addSeconds(5));
                // Reset counter
                Cache::forget($countKey);

                return response()->json([
                    'rate_limited' => true,
                    'message'      => 'Terlalu banyak request. Tunggu sebentar sebelum mencari lagi.',
                    'retry_after'  => 5,
                ], 429)->header('Retry-After', '5');
            }
        } else {
            // Gap >= 2s → reset counter
            Cache::forget($countKey);
            $count = 0;
        }

        // Save last request time
        Cache::put($lastKey, $now, now()->addSeconds(30));
        // ── End Rate Limiting ──────────────────────────────────────────────────

        $query          = $request->input('q', '');
        $orderBy        = $request->input('order_by', 'created_at');
        $orderDirection = $request->input('order_direction', 'desc');
        $page           = (int) $request->input('page', 1);

        $allowedOrderBy = [
            'created_at'     => 'Published',
            'views_count'    => 'Views',
            'download_count' => 'Downloads',
            'comments_count' => 'Comments',
        ];

        if (!array_key_exists($orderBy, $allowedOrderBy)) {
            $orderBy = 'created_at';
        }

        // Always load pinned pastebins for the response
        $pinnedPastebins = PinnedPastebin::with([
            'pastebin' => fn ($q) => $q->withCount('comments')->with(['user.identification']),
        ])
        ->orderBy('sort_order')
        ->get()
        ->filter(fn ($pin) => $pin->pastebin !== null)
        ->values();

        $pastebinsQuery = Pastebin::where('visibility', 'public')
            ->whereNull('password')
            ->where('is_self_destruct', false);

        if ($query !== '') {
            $pastebinsQuery->where('title', 'like', "%{$query}%");
        }

        $paginator = $pastebinsQuery
            ->with(['user', 'user.identification', 'pinnedRecord'])
            ->withCount('comments')
            ->orderBy($orderBy, $orderDirection === 'asc' ? 'asc' : 'desc')
            ->paginate(15, ['*'], 'page', $page)
            ->withQueryString();

        // Build rows data
        $rows = $paginator->map(function ($paste) {
            return [
                'slug'          => $paste->slug,
                'title'         => \Str::limit($paste->title, 40),
                'has_password'  => !is_null($paste->password),
                'author'        => $paste->user ? [
                    'username'   => $paste->user->username,
                    'avatar_url' => $paste->user->identification && $paste->user->identification->avatar_path
                        ? \Storage::url($paste->user->identification->avatar_path)
                        : null,
                    'initial'    => strtoupper(substr($paste->user->username, 0, 1)),
                    'style_html' => $paste->user->identification?->role?->userStyle($paste->user->username),
                ] : null,
                'author_name'   => $paste->author_name,
                'views_count'   => number_format($paste->views_count ?? $paste->views ?? 0),
                'download_count' => number_format($paste->download_count ?? $paste->downloads_count ?? 0),
                'comments_count' => number_format($paste->comments_count ?? 0),
                'created_at'    => $paste->created_at->diffForHumans(),
                'is_pinned'     => !is_null($paste->pinnedRecord),
                'pin_route'     => route('pastebin.pin', $paste),
            ];
        });

        return response()->json([
            'rate_limited'    => false,
            'rows'            => $rows,
            'total'           => $paginator->total(),
            'current_page'    => $paginator->currentPage(),
            'last_page'       => $paginator->lastPage(),
            'per_page'        => $paginator->perPage(),
            'has_more_pages'  => $paginator->hasMorePages(),
            'query'           => $query,
        ]);
    }
}
