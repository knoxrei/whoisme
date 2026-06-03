<footer class="border-t border-neutral-900 bg-neutral-950 py-8 mt-12">
    <div class="max-w-6xl mx-auto px-6 md:px-8 flex flex-col md:flex-row items-center justify-between gap-4">
        <x-layouts.logo class="opacity-80" />
        
    @php
        $user = \App\Models\User::whereHas('identification', function($query) {
            $query->where('role', \App\Enum\Role::OWNER);
        })->first();
        $displayRichName = $user ? $user->identification->role->userStyle($user->username) : null;
    @endphp
        <p class="text-sm text-neutral-600 text-center md:text-right">
            &copy; {{ date('Y') }} DoxMe
            @if($user)
                · <a href="{{ route('profile.show', $user->username) }}" class="hover:text-neutral-300 transition-colors">{!! $displayRichName !!}</a>
            @endif
        </p>
    </div>
</footer>
