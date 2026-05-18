<?php

namespace App\Http\Controllers;

use App\Enum\Role;
use Illuminate\Http\Request;

class UpgradeController extends Controller
{
    public function index()
    {
        $title = 'Upgrade Account';
        $roles = [
            Role::VIP,
            Role::PRIME,
            Role::RICH,
        ];
        
        return view('upgrade.index', compact('title', 'roles'));
    }

    /**
     * Mock purchase function for demonstration.
     */
    public function purchase(Request $request, string $roleValue)
    {
        $role = Role::from($roleValue);
        
        /** @var \App\Models\User $user */
        $user = auth()->user();
        
        // Prevent duplicate pending requests
        $existing = \App\Models\UpgradeRequest::where('user_id', $user->id)
            ->where('requested_role', $role->value)
            ->where('status', 'pending')
            ->exists();

        if ($existing) {
            return redirect()->route('dashboard')->with('error', "You already have a pending upgrade request for {$role->label()}.");
        }

        \App\Models\UpgradeRequest::create([
            'user_id' => $user->id,
            'requested_role' => $role,
            'status' => 'pending',
        ]);

        return redirect()->route('dashboard')->with('success', "Your upgrade request to {$role->label()} has been successfully transmitted. Please await owner approval.");
    }
}
