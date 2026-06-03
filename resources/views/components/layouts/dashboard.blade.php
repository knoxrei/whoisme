@props(['title' => 'Dashboard', 'role' => null])

@php
    $resolvedRole = $role ?? (auth()->check() ? auth()->user()->identification->role : null);
    $roleValue = is_string($resolvedRole) ? $resolvedRole : ($resolvedRole->value ?? null);
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title }} - {{ config('app.name') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap"
        rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
<link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
<link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
<link rel="manifest" href="/site.webmanifest">
    <style>
        body {
            font-family: 'Outfit', sans-serif;
            background-color: #050505;
            color: #e5e7eb;
        }

        .glass {
            background: rgba(15, 15, 15, 0.7);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }

        .sidebar-item:hover {
            background: rgba(220, 38, 38, 0.05);
            color: #fff;
        }

        .sidebar-item.active {
            background: rgba(220, 38, 38, 0.1);
            border-left: 3px solid #dc2626;
            color: #dc2626;
            font-weight: 900;
        }

        /* Sidebar scrollbar */
        .sidebar-nav::-webkit-scrollbar {
            width: 3px;
        }
        .sidebar-nav::-webkit-scrollbar-track {
            background: transparent;
        }
        .sidebar-nav::-webkit-scrollbar-thumb {
            background: rgba(220, 38, 38, 0.1);
        }
    </style>
</head>

<body class="antialiased overflow-hidden flex flex-col h-screen">
    <x-navbar />

    <div id="dashboard-mobile-header" class="lg:hidden flex items-center justify-between px-4 py-3 border-b border-red-900/10 z-30 flex-shrink-0 bg-[#050505]">
        <button
            id="sidebar-toggle-btn"
            onclick="toggleSidebar()"
            class="flex flex-col gap-1.5 p-2 text-gray-500 hover:text-red-600 transition-colors focus:outline-none"
            aria-label="Toggle sidebar"
        >
            <span class="hamburger-line line-1"></span>
            <span class="hamburger-line line-2"></span>
            <span class="hamburger-line line-3"></span>
        </button>

        <div class="flex items-center gap-2">
            <span class="w-1.5 h-1.5 rounded-full bg-red-600 animate-pulse"></span>
            <span class="text-[9px] font-black uppercase tracking-[0.2em] text-gray-400 font-mono">
                TERMINAL : @if(request()->routeIs('dashboard')) OVERVIEW
                @elseif(request()->routeIs('profile.edit')) CONFIG
                @elseif(request()->routeIs('dashboard.pastes')) ASSETS
                @elseif(request()->routeIs('dashboard.suggestions')) PROPOSALS
                @elseif(request()->routeIs('dashboard.upgrades')) CLEARANCE
                @elseif(request()->routeIs('dashboard.reports')) INCIDENTS
                @elseif(request()->routeIs('dashboard.users')) DATABASE
                @else PANEL
                @endif
            </span>
        </div>

        <a href="{{ route('profile.show', auth()->user()->username) }}" class="shrink-0">
            <img src="{{ asset('storage/' . auth()->user()->identification->avatar_path) }}"
                alt="{{ auth()->user()->username }}"
                class="w-7 h-7 rounded-sm border border-red-900/40 object-cover shadow-sm">
        </a>
    </div>

    <div
        id="sidebar-backdrop"
        onclick="closeSidebar()"
        class="fixed inset-0 z-30 hidden lg:hidden bg-black/80 backdrop-blur-sm"
    ></div>

    <div class="flex flex-1 overflow-hidden bg-[#050505]">
        <aside id="sidebar"
            class="hidden fixed inset-y-0 left-0 z-40 w-72 lg:w-64 lg:block lg:static lg:inset-0 bg-[#070707] border-r border-red-900/10 pt-4"
            style="top: 0;">
            <div class="flex flex-col h-full">
                <div class="lg:hidden flex items-center justify-between px-6 py-4 border-b border-red-900/10 mb-2">
                    <x-layouts.logo />
                    <button onclick="closeSidebar()" class="p-2 text-gray-500 hover:text-red-600 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <nav class="sidebar-nav flex-1 overflow-y-auto py-6 space-y-1 px-4">
                    <div class="px-3 mb-3 text-[9px] font-black tracking-[0.2em] text-gray-600 uppercase font-mono">
                        Main Command
                    </div>

                    <a href="{{ route('dashboard') }}"
                        class="sidebar-item {{ request()->routeIs('dashboard') ? 'active' : '' }} flex items-center gap-3 px-4 py-2.5 rounded-sm text-xs font-bold uppercase tracking-widest transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        Overview
                    </a>

                    <a href="{{ route('dashboard.pastes') }}"
                        class="sidebar-item {{ request()->routeIs('dashboard.pastes') ? 'active' : '' }} flex items-center gap-3 px-4 py-2.5 rounded-sm text-xs font-bold uppercase tracking-widest text-gray-400 transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                        My Assets
                    </a>

                    <a href="{{ route('dashboard.suggestions') }}"
                        class="sidebar-item {{ request()->routeIs('dashboard.suggestions') ? 'active' : '' }} flex items-center gap-3 px-4 py-2.5 rounded-sm text-xs font-bold uppercase tracking-widest text-gray-400 transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        Proposals
                    </a>

                    <a href="{{ route('profile.edit') }}"
                        class="sidebar-item {{ request()->routeIs('profile.edit') ? 'active' : '' }} flex items-center gap-3 px-4 py-2.5 rounded-sm text-xs font-bold uppercase tracking-widest text-gray-400 transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        Config
                    </a>

                    @if($roleValue && in_array($roleValue, ['owner', 'moderator']))
                        <div class="pt-8 px-3 mb-3 text-[9px] font-black tracking-[0.2em] text-gray-600 uppercase font-mono">
                            Admin Root
                        </div>
                        <a href="{{ route('dashboard.upgrades') }}"
                            class="sidebar-item {{ request()->routeIs('dashboard.upgrades') ? 'active' : '' }} flex items-center gap-3 px-4 py-2.5 rounded-sm text-xs font-bold uppercase tracking-widest text-gray-400 transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 11l3-3m0 0l3 3m-3-3v8m0-13a9 9 0 110 18 9 9 0 010-18z" />
                            </svg>
                            Upgrades
                        </a>
                        <a href="{{ route('dashboard.reports') }}"
                            class="sidebar-item {{ request()->routeIs('dashboard.reports') ? 'active' : '' }} flex items-center gap-3 px-4 py-2.5 rounded-sm text-xs font-bold uppercase tracking-widest text-gray-400 transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            Reports
                        </a>
                        <a href="{{ route('dashboard.users') }}"
                            class="sidebar-item {{ request()->routeIs('dashboard.users') ? 'active' : '' }} flex items-center gap-3 px-4 py-2.5 rounded-sm text-xs font-bold uppercase tracking-widest text-gray-400 transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                            Database
                        </a>
                        @if($roleValue && in_array($roleValue, ['owner']))
                        <a href="{{ route('admin.ads.moderation.index') }}"
                            class="sidebar-item {{ request()->routeIs('admin.ads.*') ? 'active' : '' }} flex items-center gap-3 px-4 py-2.5 rounded-sm text-xs font-bold uppercase tracking-widest text-gray-400 transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            Ads Mod
                        </a>
                        @endif
                    @endif

                    @if($roleValue && in_array($roleValue, ['advertiser']))
                        <div class="pt-8 px-3 mb-3 text-[9px] font-black tracking-[0.2em] text-gray-600 uppercase font-mono">
                            Advertiser
                        </div>
                        <a href="{{ route('advertiser.dashboard') }}"
                            class="sidebar-item {{ request()->routeIs('advertiser.dashboard') ? 'active' : '' }} flex items-center gap-3 px-4 py-2.5 rounded-sm text-xs font-bold uppercase tracking-widest text-gray-400 transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"></path>
                            </svg>
                            Ad Ops
                        </a>
                    @endif
                </nav>

                <div class="p-6 border-t border-red-900/10">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="flex items-center w-full gap-3 px-4 py-2.5 rounded-sm text-xs font-black uppercase tracking-widest text-red-500 bg-red-950/10 hover:bg-red-600 hover:text-white transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                            Terminate
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <main class="flex-1 flex flex-col min-w-0 overflow-hidden bg-[#050505]">
            <div class="flex-1 overflow-y-auto p-6 lg:p-10 custom-scrollbar flex flex-col justify-between">
                <div class="max-w-7xl mx-auto w-full">
                    {{ $slot }}
                </div>

                <x-internal-ads class="mt-12 pt-8 border-t border-red-900/5 max-w-7xl mx-auto w-full" />
            </div>
        </main>
    </div>

    <div id="global-action-modal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4" style="background-color: rgba(0,0,0,0.85);" data-modal-container>
        <div class="relative w-full max-w-md bg-[#0a0a0a] border rounded-sm overflow-hidden" id="global-modal-box" data-modal-box style="border-color: rgba(153, 27, 27, 0.4);">
            <div class="flex items-center justify-between px-6 py-4 border-b bg-[#111]" id="global-modal-header" style="border-color: rgba(153, 27, 27, 0.2);">
                <h3 class="text-xs font-black uppercase tracking-[0.2em] font-mono text-red-500" id="global-modal-title">
                    SYSTEM ALERT
                </h3>
                <button type="button" onclick="closeModal('global-action-modal')" class="text-gray-500 hover:text-white transition-colors duration-150">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div class="p-6 md:p-8 text-xs text-gray-300 font-mono leading-relaxed" id="global-modal-body">
                SYSTEM MESSAGE BODY
            </div>

            <div class="px-6 py-4 border-t bg-[#050505] flex justify-end gap-3" id="global-modal-footer" style="border-color: rgba(153, 27, 27, 0.1);">
                <button type="button" onclick="closeModal('global-action-modal')" class="text-[9px] font-black uppercase tracking-widest text-gray-500 hover:text-white px-4 py-2 transition-colors duration-150" id="global-cancel-btn">Cancel</button>
                <button type="button" class="text-[9px] font-black uppercase tracking-widest px-4 py-2 border rounded-sm bg-red-950/20 text-red-500 border-red-900/30 hover:bg-red-600 hover:text-white transition-colors duration-150" id="global-confirm-btn">Proceed</button>
            </div>
        </div>
    </div>

    <script>
        var sidebar = document.getElementById('sidebar');
        var backdrop = document.getElementById('sidebar-backdrop');
        var sidebarOpen = false;

        function openSidebar() {
            sidebarOpen = true;
            sidebar.classList.remove('hidden');
            backdrop.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeSidebar() {
            sidebarOpen = false;
            sidebar.classList.add('hidden');
            backdrop.classList.add('hidden');
            document.body.style.overflow = '';
        }

        function toggleSidebar() {
            if (sidebarOpen) { closeSidebar(); } else { openSidebar(); }
        }

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && sidebarOpen) { closeSidebar(); }
        });

        if (window.innerWidth < 1024) {
            document.querySelectorAll('#sidebar a, #sidebar button[type="submit"]').forEach(function(el) {
                el.addEventListener('click', closeSidebar);
            });
        }

        function openModal(id) {
            const modal = document.getElementById(id);
            if (!modal) return;

            modal.classList.remove('hidden');
            void modal.offsetWidth;
            modal.classList.remove('opacity-0');

            const box = modal.querySelector('[data-modal-box]');
            if (box) {
                box.classList.remove('scale-95');
                box.classList.add('scale-100');
            }
            document.body.classList.add('overflow-hidden');
        }

        function closeModal(id) {
            const modal = document.getElementById(id);
            if (!modal) return;

            modal.classList.add('opacity-0');
            const box = modal.querySelector('[data-modal-box]');
            if (box) {
                box.classList.remove('scale-100');
                box.classList.add('scale-95');
            }

            setTimeout(() => {
                modal.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            }, 200);
        }

        window.confirmAction = function(message, onConfirm, title = 'SYSTEM ALERT') {
            const modal = document.getElementById('global-action-modal');
            const modalTitle = document.getElementById('global-modal-title');
            const modalBody = document.getElementById('global-modal-body');
            const confirmBtn = document.getElementById('global-confirm-btn');

            if (!modal || !modalBody || !confirmBtn) return;

            modalTitle.innerText = title;
            modalBody.innerText = message;

            confirmBtn.onclick = function() {
                onConfirm();
                closeModal('global-action-modal');
            };

            openModal('global-action-modal');
        };
</script>
</body>
</html>