<footer class=" border-t border-white/5 bg-[#050505] py-8 mt-12 relative overflow-hidden z-10">
    <div class="max-w-7xl mx-auto px-6 md:px-12 flex flex-col md:flex-row items-center justify-between gap-6 relative z-20">
        <div class="flex items-center gap-3">
          
            <x-layouts.logo class="text-xs font-black text-gray-500 tracking-widest uppercase font-mono"/>
        </div>
        
    @php
        $user = \App\Models\User::whereHas('identification', function($query) {
            $query->where('role', \App\Enum\Role::OWNER);
        })->first();
        $displayRichName = $user ? $user->identification->role->userStyle($user->username) : null;
    @endphp
        <div class="text-gray-600 text-[10px] font-mono tracking-widest  flex flex-col md:flex-row items-center gap-4 md:gap-8">
        
        
                <span class=" flex items-center gap-1">
                &copy; {{ date('Y') }} DoxMe. 
                @if($user)
                    Powered by <a href="{{ route('profile.show', $user->username) }}" class="hover:text-red-500 transition-colors">{!! $displayRichName !!}</a>
                @else
                    No rules apply.
                @endif
            </span>
        </div>
    </div>
</footer>
