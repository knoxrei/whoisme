<?php

namespace App\Http\Controllers;

use App\Models\Pastebin;
use App\Models\PastebinEdit;
use Illuminate\Http\Request;

class PastebinEditController extends Controller
{
    public function store(Request $request, Pastebin $pastebin)
    {
        if (auth()->user()->identification->role === \App\Enum\Role::BANNED) {
            abort(403, 'Your signature has been banned.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'description' => 'nullable|string|max:255',
        ]);

        PastebinEdit::create([
            'pastebin_id' => $pastebin->id,
            'user_id' => auth()->id(),
            'title' => $request->title,
            'content' => $request->content,
            'description' => $request->description ?? null,
            'status' => 'pending',
        ]);

        return back()->with('success', 'Your edit suggestion has been submitted for approval.');
    }

    public function approve(PastebinEdit $edit)
    {
        $this->authorizeAction($edit->pastebin);

        $edit->pastebin->update([
            'title' => $edit->title,
            'content' => $edit->content,
            'description' => $edit->description,
        ]);

        $edit->update(['status' => 'approved']);

        return back()->with('success', 'Edit suggestion approved and applied.');
    }

    public function reject(PastebinEdit $edit)
    {
        $this->authorizeAction($edit->pastebin);

        $edit->update(['status' => 'rejected']);

        return back()->with('success', 'Edit suggestion rejected.');
    }

    protected function authorizeAction(Pastebin $pastebin)
    {
        if (auth()->id() !== $pastebin->user_id && !auth()->user()->canUsePremiumFeatures()) {
            abort(403, 'Unauthorized action.');
        }
    }
}
