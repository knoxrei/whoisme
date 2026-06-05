<?php

namespace App\Http\Controllers;

use App\Models\Pastebin;
use App\Models\PinnedPastebin;
use Illuminate\Http\Request;

class PastebinListController extends Controller
{
    public function index(Request $request)
    {
        $title = 'All Pastebins';
        $query = $request->input('q');
        $orderBy = $request->input('order_by', 'created_at');
        $orderDirection = $request->input('order_direction', 'desc');

        // Fetch pinned pastebins - only on the first page and if not searching
        $pinnedPastebins = collect();
        if (!$query && (!$request->has('page') || $request->page == 1)) {
            $pinnedPastebins = PinnedPastebin::with([
                'pastebin' => fn ($q) => $q->withCount('comments')->with(['user.identification', 'images']),
            ])
            ->orderBy('sort_order')
            ->get()
            ->filter(fn($pin) => $pin->pastebin !== null);
        }

        $pastebinsQuery = Pastebin::where('visibility', 'public')
            ->whereNull('password')
            ->where('is_self_destruct', false);

        if ($query) {
            $pastebinsQuery->where(function ($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                  ->orWhere('description', 'like', "%{$query}%")
                  ->orWhere('content', 'like', "%{$query}%");
            });
        }

        // Validate order_by
        $allowedOrderBy = [
            'created_at' => 'Published',
            'views_count' => 'Views',
            'download_count' => 'Downloads',
            'comments_count' => 'Comments'
        ];

        if (!array_key_exists($orderBy, $allowedOrderBy)) {
            $orderBy = 'created_at';
        }

        $pastebins = $pastebinsQuery->with(['user', 'user.identification', 'pinnedRecord'])
            ->withCount('comments')
            ->orderBy($orderBy, $orderDirection === 'asc' ? 'asc' : 'desc')
            ->paginate(15)
            ->withQueryString();

        return view('pastebin.index', compact('title', 'pastebins', 'pinnedPastebins', 'query', 'orderBy', 'orderDirection', 'allowedOrderBy'));
    }
}
