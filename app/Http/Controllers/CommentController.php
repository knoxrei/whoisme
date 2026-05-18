<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Pastebin;
use App\Models\Comment;

class CommentController extends Controller
{
    public function store(Request $request, Pastebin $pastebin)
    {
        if (auth()->user()->identification->role === \App\Enum\Role::BANNED) {
            abort(403, 'Your signature has been banned.');
        }

        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        $pastebin->comments()->create([
            'user_id' => auth()->id(),
            'content' => $request->content,
        ]);

        return back()->with('success', 'Comment posted successfully.');
    }
}
