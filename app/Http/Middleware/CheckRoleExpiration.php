<?php

namespace App\Http\Middleware;

use App\Enum\Role;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRoleExpiration
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check()) {
            /** @var \App\Models\User $user */
            $user = auth()->user();
            $identification = $user->identification;

            if ($identification && $identification->expired_at && $identification->expired_at->isPast()) {
                // Role has expired, reset to member
                $identification->update([
                    'role' => Role::MEMBER,
                    'expired_at' => null,
                ]);

                session()->flash('warning', 'Your premium subscription has expired. Your role has been reset to Member.');
            }
        }

        return $next($request);
    }
}
