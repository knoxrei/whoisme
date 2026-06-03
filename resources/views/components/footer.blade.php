<footer class="border-t border-red-950/40 bg-black py-8 mt-12">
    <div class="max-w-6xl mx-auto px-6 md:px-8 flex flex-col md:flex-row items-center justify-between gap-4">
        <x-layouts.logo icon-class="w-6 h-6 text-red-600" class="opacity-90" />
        @php
            $user = \App\Models\User::whereHas('identification', function ($query) {
                $query->where('role', \App\Enum\Role::OWNER);
            })->first();
            $displayRichName = $user ? $user->identification->role->userStyle($user->username) : null;
        @endphp
        <p class="text-sm text-gray-600 text-center md:text-right">
            &copy; {{ date('Y') }} DoxMe
            @if($user)
                · <a href="{{ route('profile.show', $user->username) }}" class="hover:text-red-500 transition-colors">{!! $displayRichName !!}</a>
            @endif
        </p>
    </div>
</footer>
