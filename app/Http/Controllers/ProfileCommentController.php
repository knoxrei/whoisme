<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ProfileComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileCommentController extends Controller
{
    /**
     * Store a newly created comment on a user's profile.
     */
    public function store(Request $request, User $user)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        ProfileComment::create([
            'user_id' => Auth::id(),
            'profile_user_id' => $user->id,
            'content' => $request->content,
        ]);

        return back()->with('success', 'Comment posted successfully.');
    }

    /**
     * Remove the specified comment.
     */
    public function destroy(ProfileComment $comment)
    {
        $user = Auth::user();
        
        // Authorization check
        $isAuthor = $comment->user_id === $user->id;
        $isProfileOwner = $comment->profile_user_id === $user->id;
        $isStaff = in_array($user->identification?->role?->value, ['owner', 'moderator']);

        if (!$isAuthor && !$isProfileOwner && !$isStaff) {
            abort(403, 'Unauthorized action.');
        }

        $comment->delete();

        return back()->with('success', 'Comment deleted successfully.');
    }
}
