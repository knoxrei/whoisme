<nav class="sticky top-0 z-50 flex flex-col">
    <!-- Top Main Bar -->
    <div class="bg-[#050505] border-b border-white/5 py-3.5 px-4 md:px-12">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <!-- Logo & Main Navigation -->
            <div class="flex items-center gap-10">
                <a href="/" class="hover:opacity-80 transition-all active:scale-95">
                    <x-layouts.logo />
                </a>

                <!-- Desktop Nav (Primary) -->
                <div class="hidden lg:flex items-center gap-6">
                    <a href="{{ route('welcome') }}"
                        class="text-[11px] {{ request()->routeIs('welcome') || request()->routeIs('search.index') ? 'text-red-600' : 'text-gray-500' }} font-bold uppercase tracking-[0.2em] hover:text-red-500 transition-colors flex items-center gap-2">
                        Search
                    </a>

                    @auth
                        <a href="{{ route('dashboard') }}"
                            class="text-[11px] {{ request()->routeIs('dashboard') ? 'text-red-600' : 'text-gray-500' }} font-bold uppercase tracking-[0.2em] hover:text-red-500 transition-colors flex items-center gap-2">
                            Dashboard
                        </a>
                    @endauth
                    <a href="{{ route('pastebin.create') }}"
                        class="text-[11px] {{ request()->routeIs('pastebin.create') ? 'text-red-600' : 'text-gray-500' }} font-bold uppercase tracking-[0.2em] hover:text-red-500 transition-colors flex items-center gap-2">
                        New Paste
                    </a>
                </div>
            </div>

            <!-- Right Menu -->
            <div class="flex items-center gap-4">

                <!-- Vertical Divider -->
                <div class="hidden sm:block w-[1px] h-6 bg-white/5"></div>

                <!-- Auth/Guest Section -->
                <div class="flex items-center gap-6">
                    @guest
                        <div class="flex items-center gap-4">
                            <!-- Anonymous Identity -->
                            @if (session('anonuser'))
                                <div class="hidden md:flex flex-col items-end leading-none">
                                    <span class="text-[9px] uppercase tracking-widest text-gray-600 font-black mb-1">Session Identity</span>
                                    <span class="text-xs text-white/90 font-bold font-mono tracking-tighter">{{ session('anonuser') }}</span>
                                </div>
                            @endif

                            <div class="flex items-center gap-4">
                                <a href="{{ route('login') }}"
                                    class="text-xs font-bold text-gray-500 hover:text-white transition-colors uppercase tracking-widest hidden sm:block">
                                    Login
                                </a>
                                <a href="{{ route('register.index') }}"
                                    class="relative group overflow-hidden px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-xs font-black uppercase tracking-[0.15em] rounded-md transition-all shadow-lg shadow-red-600/10 active:scale-95">
                                    <span class="relative z-10">Join</span>
                                    <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/20 to-transparent -translate-x-full group-hover:translate-x-full transition-transform duration-700"></div>
                                </a>
                            </div>
                        </div>
                    @else
                        <!-- Authenticated User -->
                        <div class="flex items-center gap-4">
                            <div class="hidden md:flex flex-col items-end leading-none">
                                <span class="text-[9px] uppercase tracking-widest text-red-600 font-black mb-1">Authenticated</span>
                                <a href="{{ route('profile.show', auth()->user()->username) }}"
                                    class="text-sm text-white font-black tracking-tight hover:text-red-500 transition-colors">{{ auth()->user()->username ?? auth()->user()->name }}</a>
                            </div>

                            <div class="flex items-center gap-4">
                                <a href="{{ route('profile.show', auth()->user()->username) }}" class="shrink-0">
                                    <img src="{{ asset('storage/' . auth()->user()->identification->avatar_path) }}"
                                        alt="{{ auth()->user()->username }}" class="w-9 h-9 rounded-sm border border-red-900/20">
                                </a>

                                <form action="{{ route('logout') }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="p-2 text-gray-500 hover:text-red-500 transition-all group hidden sm:block"
                                        title="Logout">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                                            stroke-linecap="round" stroke-linejoin="round"
                                            class="group-hover:translate-x-0.5 transition-transform">
                                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
                                            <polyline points="16 17 21 12 16 7" />
                                            <line x1="21" y1="12" x2="9" y2="12" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endguest
                </div>
            </div>
        </div>
    </div>

    <!-- Bottom Secondary Bar (Desktop only) — Links + Pastebins -->
    <div class="hidden lg:block bg-[#050505] border-b border-white/5 py-2 px-6 md:px-12">
        <div class="max-w-7xl mx-auto flex items-center justify-center gap-12">
            <a href="{{ route('pastebin.list') }}"
                class="text-[10px] {{ request()->routeIs('pastebin.list') ? 'text-red-600' : 'text-gray-500' }} font-bold uppercase tracking-widest hover:text-red-500 transition-colors">
                Pastebins
            </a>
            <a href="{{ route('upgrade.index') }}"
                class="text-[10px] {{ request()->routeIs('upgrade.index') ? 'text-red-600' : 'text-gray-500' }} font-bold uppercase tracking-widest hover:text-red-500 transition-colors">
                Upgrade
            </a>
            <a href="{{ route('advertise') }}"
                class="text-[10px] {{ request()->routeIs('advertise') ? 'text-red-600' : 'text-gray-500' }} font-bold uppercase tracking-widest hover:text-red-500 transition-colors">
                Advertise
            </a>
            <a href="{{ route('support') }}"
                class="text-[10px] {{ request()->routeIs('support') ? 'text-red-600' : 'text-gray-500' }} font-bold uppercase tracking-widest hover:text-red-500 transition-colors">
                Support
            </a>
            <a href="{{ route('about') }}"
                class="text-[10px] {{ request()->routeIs('about') ? 'text-red-600' : 'text-gray-500' }} font-bold uppercase tracking-widest hover:text-red-500 transition-colors">
                About
            </a>
            <a href="{{ route('terms') }}"
                class="text-[10px] {{ request()->routeIs('terms') ? 'text-red-600' : 'text-gray-500' }} font-bold uppercase tracking-widest hover:text-red-500 transition-colors">
                Terms
            </a>
            <a href="{{ route('profile.users-list') }}"
                class="text-[10px] {{ request()->routeIs('profile.users-list') ? 'text-red-600' : 'text-gray-500' }} font-bold uppercase tracking-widest hover:text-red-500 transition-colors">
                Users
            </a>
        </div>
    </div>
</nav>

<!-- ═══════════════════════════════════════════════════════════
     MOBILE BOTTOM NAVBAR — visible only on mobile (<lg)
═══════════════════════════════════════════════════════════ -->
<div class="lg:hidden fixed bottom-0 left-0 right-0 z-50 bg-[#050505]/95 backdrop-blur-md border-t border-red-900/20" id="mobile-bottom-nav">
    <div class="flex items-center justify-around px-2 py-2 safe-area-bottom">

        <!-- Home / Search -->
        <a href="{{ route('welcome') }}"
            class="flex flex-col items-center gap-1 px-3 py-1.5 rounded-sm transition-colors group {{ request()->routeIs('welcome') || request()->routeIs('search.index') ? 'text-red-500' : 'text-gray-600' }} hover:text-red-500">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <span class="text-[8px] font-black uppercase tracking-widest">Search</span>
        </a>

        <!-- Pastebins -->
        <a href="{{ route('pastebin.list') }}"
            class="flex flex-col items-center gap-1 px-3 py-1.5 rounded-sm transition-colors group {{ request()->routeIs('pastebin.list') ? 'text-red-500' : 'text-gray-600' }} hover:text-red-500">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <span class="text-[8px] font-black uppercase tracking-widest">Pastes</span>
        </a>

        <!-- New Paste (center, highlighted) -->
        <a href="{{ route('pastebin.create') }}"
            class="flex flex-col items-center gap-1 -mt-4">
            <div class="w-12 h-12 rounded-full bg-red-600 hover:bg-red-700 flex items-center justify-center shadow-lg shadow-red-600/30 transition-all active:scale-95 border-2 border-[#050505]">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                </svg>
            </div>
            <span class="text-[8px] font-black uppercase tracking-widest {{ request()->routeIs('pastebin.create') ? 'text-red-500' : 'text-gray-600' }}">New</span>
        </a>

        <!-- Dashboard (auth) / Login (guest) -->
        @auth
            <a href="{{ route('dashboard') }}"
                class="flex flex-col items-center gap-1 px-3 py-1.5 rounded-sm transition-colors {{ request()->routeIs('dashboard') ? 'text-red-500' : 'text-gray-600' }} hover:text-red-500">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h18M3 12h18M3 17h18"/>
                </svg>
                <span class="text-[8px] font-black uppercase tracking-widest">Board</span>
            </a>
        @else
            <a href="{{ route('login') }}"
                class="flex flex-col items-center gap-1 px-3 py-1.5 rounded-sm transition-colors text-gray-600 hover:text-red-500">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                </svg>
                <span class="text-[8px] font-black uppercase tracking-widest">Login</span>
            </a>
        @endauth

        <!-- Profile (auth) / More (guest) -->
        @auth
            <a href="{{ route('profile.show', auth()->user()->username) }}"
                class="flex flex-col items-center gap-1 px-3 py-1.5 rounded-sm transition-colors {{ request()->routeIs('profile.show') && request()->route('username') === auth()->user()->username ? 'text-red-500' : 'text-gray-600' }} hover:text-red-500">
                <div class="w-5 h-5 overflow-hidden rounded-sm border border-current/20">
                    <img src="{{ asset('storage/' . auth()->user()->identification->avatar_path) }}"
                        alt="{{ auth()->user()->username }}" class="w-full h-full object-cover">
                </div>
                <span class="text-[8px] font-black uppercase tracking-widest">Me</span>
            </a>
        @else
            <a href="{{ route('register.index') }}"
                class="flex flex-col items-center gap-1 px-3 py-1.5 rounded-sm transition-colors text-gray-600 hover:text-red-500">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                <span class="text-[8px] font-black uppercase tracking-widest">Join</span>
            </a>
        @endauth
    </div>
</div>

<!-- Add bottom padding to page body on mobile so content isn't hidden behind bottom nav -->
<style>
    @media (max-width: 1023px) {
        main {
            padding-bottom: 5rem !important;
        }
    }
    /* Safe area for modern phones with home indicator */
    .safe-area-bottom {
        padding-bottom: max(0.5rem, env(safe-area-inset-bottom));
    }
</style>
