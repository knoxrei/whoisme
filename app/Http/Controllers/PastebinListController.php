<?php

namespace App\Http\Controllers;

use App\Models\Pastebin;
use App\Models\PinnedPastebin;
use Illuminate\Http\Request;

class PastebinListController extends Controller
{
    public function index()
    {
        $title = 'All Pastebins';

        // Fetch pinned pastebins ordered by sort_order, eager-load pastebin + user + identification
        $pinnedPastebins = PinnedPastebin::with([
            'pastebin.user.identification',
            'pastebin.images',
        ])
        ->orderBy('sort_order')
        ->get()
        ->filter(fn($pin) => $pin->pastebin !== null); // safety: skip if pastebin was deleted

        $pastebins = Pastebin::where('visibility', 'public')
            ->whereNull('password')
            ->where('is_self_destruct', false)
            ->with(['user', 'user.identification', 'pinnedRecord'])
            ->latest()
            ->paginate(15);

        return view('pastebin.index', compact('title', 'pastebins', 'pinnedPastebins'));
    }
}
