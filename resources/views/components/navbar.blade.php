<nav class="sticky top-0 z-50 flex flex-col border-b border-red-950/40 bg-black">
    <div class="py-3 px-4 md:px-8">
        <div class="max-w-6xl mx-auto flex items-center justify-between gap-4">
            <div class="flex items-center gap-8">
                <a href="/" class="hover:opacity-90 transition-opacity shrink-0">
                    <x-layouts.logo icon-class="w-7 h-7 text-red-600" />
                </a>

                <div class="hidden lg:flex items-center gap-5 text-sm">
                    <a href="{{ route('welcome') }}"
                        class="{{ request()->routeIs('welcome', 'search.index') ? 'text-red-500' : 'text-gray-500' }} hover:text-red-400 transition-colors">
                        Search
                    </a>
                    @auth
                        <a href="{{ route('dashboard') }}"
                            class="{{ request()->routeIs('dashboard') ? 'text-red-500' : 'text-gray-500' }} hover:text-red-400 transition-colors">
                            Dashboard
                        </a>
                    @endauth
                    <a href="{{ route('pastebin.create') }}"
                        class="{{ request()->routeIs('pastebin.create') ? 'text-red-500' : 'text-gray-500' }} hover:text-red-400 transition-colors">
                        New paste
                    </a>
                </div>
            </div>

            <div class="flex items-center gap-4 text-sm">
                @guest
                    @if (session('anonuser'))
                        <span class="hidden md:inline text-gray-600 font-mono text-xs">{{ session('anonuser') }}</span>
                    @endif
                    <a href="{{ route('login') }}" class="hidden sm:inline text-gray-500 hover:text-white transition-colors">Log in</a>
                    <a href="{{ route('register.index') }}" class="px-3 py-1.5 rounded-md bg-red-700 text-white hover:bg-red-600 transition-colors">Sign up</a>
                @else
                    <a href="{{ route('profile.show', auth()->user()->username) }}" class="hidden md:inline text-gray-300 hover:text-red-500 transition-colors">
                        {{ auth()->user()->username ?? auth()->user()->name }}
                    </a>
                    <a href="{{ route('profile.show', auth()->user()->username) }}" class="shrink-0">
                        <img src="{{ asset('storage/' . auth()->user()->identification->avatar_path) }}" alt="" class="w-8 h-8 rounded border border-red-950/40 object-cover">
                    </a>
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="text-gray-500 hover:text-red-500 transition-colors hidden sm:inline text-sm">Log out</button>
                    </form>
                @endguest
            </div>
        </div>
    </div>

    <div class="hidden lg:block border-t border-red-950/30 py-2 px-8">
        <div class="max-w-6xl mx-auto flex items-center justify-center gap-6 text-sm text-gray-500">
            <a href="{{ route('pastebin.list') }}" class="hover:text-red-500 {{ request()->routeIs('pastebin.list') ? 'text-red-500' : '' }}">Pastebins</a>
            <a href="{{ route('upgrade.index') }}" class="hover:text-red-500 {{ request()->routeIs('upgrade.index') ? 'text-red-500' : '' }}">Upgrade</a>
            <a href="{{ route('advertise') }}" class="hover:text-red-500 {{ request()->routeIs('advertise') ? 'text-red-500' : '' }}">Advertise</a>
            <a href="{{ route('support') }}" class="hover:text-red-500 {{ request()->routeIs('support') ? 'text-red-500' : '' }}">Support</a>
            <a href="{{ route('about') }}" class="hover:text-red-500 {{ request()->routeIs('about') ? 'text-red-500' : '' }}">About</a>
            <a href="{{ route('terms') }}" class="hover:text-red-500 {{ request()->routeIs('terms') ? 'text-red-500' : '' }}">Terms</a>
            <a href="{{ route('profile.users-list') }}" class="hover:text-red-500 {{ request()->routeIs('profile.users-list') ? 'text-red-500' : '' }}">Users</a>
        </div>
    </div>
</nav>

<div id="mobile-more-panel" class="lg:hidden hidden fixed bottom-14 left-0 right-0 z-50 border-t border-red-950/40 bg-black">
    <div class="grid grid-cols-2 text-sm">
        <a href="{{ route('pastebin.list') }}" class="px-4 py-3 border-b border-r border-red-950/30 {{ request()->routeIs('pastebin.list') ? 'text-red-500' : 'text-gray-500' }}">Pastebins</a>
        <a href="{{ route('upgrade.index') }}" class="px-4 py-3 border-b border-red-950/30 {{ request()->routeIs('upgrade.index') ? 'text-red-500' : 'text-gray-500' }}">Upgrade</a>
        <a href="{{ route('advertise') }}" class="px-4 py-3 border-b border-r border-red-950/30 {{ request()->routeIs('advertise') ? 'text-red-500' : 'text-gray-500' }}">Advertise</a>
        <a href="{{ route('support') }}" class="px-4 py-3 border-b border-red-950/30 {{ request()->routeIs('support') ? 'text-red-500' : 'text-gray-500' }}">Support</a>
        <a href="{{ route('about') }}" class="px-4 py-3 border-r border-red-950/30 {{ request()->routeIs('about') ? 'text-red-500' : 'text-gray-500' }}">About</a>
        <a href="{{ route('terms') }}" class="px-4 py-3 {{ request()->routeIs('terms') ? 'text-red-500' : 'text-gray-500' }}">Terms</a>
        <a href="{{ route('profile.users-list') }}" class="col-span-2 px-4 py-3 border-t border-red-950/30 {{ request()->routeIs('profile.users-list') ? 'text-red-500' : 'text-gray-500' }}">Users</a>
    </div>
</div>

<div class="lg:hidden fixed bottom-0 left-0 right-0 z-50 border-t border-red-950/40 bg-black" id="mobile-bottom-nav">
    <div class="flex items-center justify-around px-2 py-2 safe-area-bottom text-gray-600">
        <a href="{{ route('welcome') }}" class="flex flex-col items-center gap-0.5 px-2 py-1 {{ request()->routeIs('welcome', 'search.index') ? 'text-red-500' : '' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            <span class="text-[10px]">Search</span>
        </a>
        @auth
            <a href="{{ route('dashboard') }}" class="flex flex-col items-center gap-0.5 px-2 py-1 {{ request()->routeIs('dashboard') ? 'text-red-500' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h18M3 12h18M3 17h18"/></svg>
                <span class="text-[10px]">Board</span>
            </a>
        @else
            <a href="{{ route('login') }}" class="flex flex-col items-center gap-0.5 px-2 py-1">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
                <span class="text-[10px]">Log in</span>
            </a>
        @endauth
        <a href="{{ route('pastebin.create') }}" class="flex flex-col items-center -mt-3">
            <div class="w-11 h-11 rounded-full bg-red-700 flex items-center justify-center border-2 border-black">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            </div>
            <span class="text-[10px] mt-0.5 {{ request()->routeIs('pastebin.create') ? 'text-red-500' : '' }}">New</span>
        </a>
        @auth
            <a href="{{ route('profile.show', auth()->user()->username) }}" class="flex flex-col items-center gap-0.5 px-2 py-1 {{ request()->routeIs('profile.show') && request()->route('username') === auth()->user()->username ? 'text-red-500' : '' }}">
                <img src="{{ asset('storage/' . auth()->user()->identification->avatar_path) }}" alt="" class="w-5 h-5 rounded object-cover border border-red-950/40">
                <span class="text-[10px]">Profile</span>
            </a>
        @else
            <a href="{{ route('register.index') }}" class="flex flex-col items-center gap-0.5 px-2 py-1">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                <span class="text-[10px]">Sign up</span>
            </a>
        @endauth
        <button id="mobile-more-btn" onclick="toggleMoreMenu()" type="button" class="flex flex-col items-center gap-0.5 px-2 py-1">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h.01M12 12h.01M19 12h.01"/></svg>
            <span class="text-[10px]">More</span>
        </button>
    </div>
</div>

<script>
    var morePanel = document.getElementById('mobile-more-panel');
    var morePanelOpen = false;
    function toggleMoreMenu() {
        morePanelOpen = !morePanelOpen;
        morePanel.classList.toggle('hidden', !morePanelOpen);
    }
    morePanel.querySelectorAll('a').forEach(function (el) {
        el.addEventListener('click', function () {
            morePanel.classList.add('hidden');
            morePanelOpen = false;
        });
    });
    document.addEventListener('scroll', function () {
        if (morePanelOpen) {
            morePanel.classList.add('hidden');
            morePanelOpen = false;
        }
    }, { passive: true });
</script>

<style>
    @media (max-width: 1023px) { main { padding-bottom: 4.5rem !important; } }
    .safe-area-bottom { padding-bottom: max(0.5rem, env(safe-area-inset-bottom)); }
</style>
