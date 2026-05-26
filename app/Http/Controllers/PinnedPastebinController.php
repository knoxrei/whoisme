<?php

namespace App\Http\Controllers;

use App\Models\Pastebin;
use App\Models\PinnedPastebin;
use Illuminate\Http\Request;

class PinnedPastebinController extends Controller
{
    /**
     * Check if the current authenticated user can manage pinned pastebins.
     */
    private function authorizeStaff(): void
    {
        $user = auth()->user();
        if (!$user || !$user->identification?->role?->canManagePinned()) {
            abort(403, 'Only owner and moderator can manage pinned pastebins.');
        }
    }

    /**
     * Pin a pastebin (owner/moderator only).
     */
    public function pin(Pastebin $pastebin)
    {
        $this->authorizeStaff();

        // Prevent duplicate pinning
        if (PinnedPastebin::where('pastebin_id', $pastebin->id)->exists()) {
            return back()->with('error', 'This pastebin is already pinned.');
        }

        $maxOrder = PinnedPastebin::max('sort_order') ?? 0;

        PinnedPastebin::create([
            'pastebin_id' => $pastebin->id,
            'pinned_by'   => auth()->id(),
            'sort_order'  => $maxOrder + 1,
        ]);

        return back()->with('success', 'Pastebin pinned successfully.');
    }

    /**
     * Unpin a pastebin (owner/moderator only).
     */
    public function unpin(PinnedPastebin $pin)
    {
        $this->authorizeStaff();

        $pin->delete();

        return back()->with('success', 'Pastebin unpinned successfully.');
    }

    /**
     * Reorder pinned pastebins via AJAX (owner/moderator only).
     * Expects JSON body: { "order": [1, 5, 3, 2] } (array of pinned_pastebin IDs in desired order)
     */
    public function reorder(Request $request)
    {
        $this->authorizeStaff();

        $request->validate([
            'order'   => 'required|array',
            'order.*' => 'integer|exists:pinned_pastebins,id',
        ]);

        foreach ($request->order as $index => $pinnedId) {
            PinnedPastebin::where('id', $pinnedId)->update(['sort_order' => $index]);
        }

        return response()->json(['success' => true]);
    }
}
