<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HandleRole
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Pastikan user sudah login
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        /** @var \App\Models\User $user */
        $user = auth()->user();

        // Ambil role user dari relasi identification
        $userRole = $user->identification?->role->value;

        // Contoh di route: ->middleware('role:owner,moderator')
        if (!$userRole || !in_array($userRole, $roles)) {
            return redirect()->route('dashboard');
        }

        return $next($request);
    }
}
