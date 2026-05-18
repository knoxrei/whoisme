<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FollowController extends Controller
{
    /**
     * Follow a user.
     */
    public function follow(User $user)
    {
        $follower = Auth::user();

        if ($follower->id === $user->id) {
            return back()->with('error', 'You cannot follow yourself.');
        }

        if (!$follower->following()->where('following_id', $user->id)->exists()) {
            $follower->following()->attach($user->id);
        }

        return back()->with('success', 'You are now following ' . $user->username);
    }

    /**
     * Unfollow a user.
     */
    public function unfollow(User $user)
    {
        $follower = Auth::user();

        $follower->following()->detach($user->id);

        return back()->with('success', 'You have unfollowed ' . $user->username);
    }
}
