<nav class="sticky top-0 z-50 flex flex-col">
    <!-- Top Main Bar -->
    <div class="bg-[#050505] border-b border-white/5 py-3.5 px-6 md:px-12">
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
                    <a href="{{ route('pastebin.list') }}"
                        class="text-[11px] {{ request()->routeIs('pastebin.list') ? 'text-red-600' : 'text-gray-500' }} font-bold uppercase tracking-[0.2em] hover:text-red-500 transition-colors flex items-center gap-2">
                        Pastebins
                    </a>
                </div>
            </div>

            <!-- Right Menu -->
            <div class="flex items-center gap-5 md:gap-8">
                <!-- Online Status -->
      
                </div>

                <!-- Vertical Divider -->
                <div class="hidden sm:block w-[1px] h-6 bg-white/5"></div>

                <!-- Auth/Guest Section -->
                <div class="flex items-center gap-6">
                    @guest
                        <div class="flex items-center gap-6">
                            <!-- Anonymous Identity -->
                            @if (session('anonuser'))
                                <div class="hidden md:flex flex-col items-end leading-none">
                                    <span class="text-[9px] uppercase tracking-widest text-gray-600 font-black mb-1">Session
                                        Identity</span>
                                    <span
                                        class="text-xs text-white/90 font-bold font-mono tracking-tighter">{{ session('anonuser') }}</span>
                                </div>
                            @endif

                            <div class="flex items-center gap-5">
                                <a href="{{ route('login') }}"
                                    class="text-xs font-bold text-gray-500 hover:text-white transition-colors uppercase tracking-widest">
                                    Login
                                </a>
                                <a href="{{ route('register.index') }}"
                                    class="relative group overflow-hidden px-5 py-2 bg-red-600 hover:bg-red-700 text-white text-xs font-black uppercase tracking-[0.15em] rounded-md transition-all shadow-lg shadow-red-600/10 active:scale-95">
                                    <span class="relative z-10">Join</span>
                                    <div
                                        class="absolute inset-0 bg-gradient-to-r from-transparent via-white/20 to-transparent -translate-x-full group-hover:translate-x-full transition-transform duration-700">
                                    </div>
                                </a>
                            </div>
                        </div>
                    @else
                        <!-- Authenticated User -->
                        <div class="flex items-center gap-5">
                            <div class="flex flex-col items-end leading-none">
                                <span
                                    class="text-[9px] uppercase tracking-widest text-red-600 font-black mb-1">Authenticated</span>
                                <a href="{{ route('profile.show', auth()->user()->username) }}"
                                    class="text-sm text-white font-black tracking-tight hover:text-red-500 transition-colors">{{ auth()->user()->username ?? auth()->user()->name }}</a>
                            </div>
                            <!-- foto profile -->

                            <div class="flex items-center gap-5">
                                <a href="{{ route('profile.show', auth()->user()->username) }}" class="shrink-0">
                                    <img src="{{ asset('storage/' . auth()->user()->identification->avatar_path) }}"
                                        alt="{{ auth()->user()->username }}" class="w-10 h-10 ">
                                </a>

                                <form action="{{ route('logout') }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="p-2 text-gray-500 hover:text-red-500 transition-all group"
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
                        @endguest
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bottom Secondary Bar (Links) -->
    <div class="hidden lg:block bg-[#050505] border-b border-white/5 py-2 px-6 md:px-12">
        <div class="max-w-7xl mx-auto flex items-center justify-center gap-12">
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
