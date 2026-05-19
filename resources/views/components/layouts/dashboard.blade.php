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

        .sidebar-item {
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .sidebar-item:hover {
            background: rgba(255, 255, 255, 0.03);
            transform: translateX(4px);
        }

        .sidebar-item.active {
            background: rgba(255, 69, 0, 0.1);
            border-left: 3px solid #FF4500;
            color: #FF4500;
        }

        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 5px;
        }

        ::-webkit-scrollbar-track {
            background: #050505;
        }

        ::-webkit-scrollbar-thumb {
            background: #222;
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #333;
        }
    </style>
</head>

<body class="antialiased overflow-hidden flex flex-col h-screen">
    <!-- Global Top Navbar -->
    <x-navbar />

    <div class="flex flex-1 overflow-hidden bg-[#050505]">
        <!-- Sidebar -->
        <aside id="sidebar"
            class="fixed inset-y-0 left-0 z-40 w-64 transition-transform duration-300 transform lg:translate-x-0 lg:static lg:inset-0 glass border-r border-white/5 pt-4">
            <div class="flex flex-col h-full">
                <!-- Navigation Links -->
                <nav class="flex-1 overflow-y-auto py-6 space-y-1 px-3">
                    <div class="px-3 mb-2 text-xs font-semibold tracking-wider text-gray-500 uppercase">
                        Menu
                    </div>

                    <a href="{{ route('dashboard') }}"
                        class="sidebar-item {{ request()->routeIs('dashboard') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        Dashboard
                    </a>

                    <a href="{{ route('profile.edit') }}"
                        class="sidebar-item {{ request()->routeIs('profile.edit') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium text-gray-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Settings
                    </a>

                    <a href="{{ route('dashboard.pastes') }}"
                        class="sidebar-item {{ request()->routeIs('dashboard.pastes') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium text-gray-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                        My Pastes
                    </a>

                    <a href="{{ route('dashboard.suggestions') }}"
                        class="sidebar-item {{ request()->routeIs('dashboard.suggestions') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium text-gray-400 hover:text-red-500">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        Edit Suggestions
                    </a>

                    @if($roleValue && in_array($roleValue, ['owner', 'moderator']))
                        <div class="pt-6 px-3 mb-2 text-xs font-semibold tracking-wider text-gray-500 uppercase">
                            Administrative
                        </div>
                        <a href="{{ route('dashboard.upgrades') }}"
                            class="sidebar-item {{ request()->routeIs('dashboard.upgrades') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium text-gray-400 hover:text-red-500">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 11l3-3m0 0l3 3m-3-3v8m0-13a9 9 0 110 18 9 9 0 010-18z" />
                            </svg>
                            Manage Upgrades
                        </a>
                        <a href="{{ route('dashboard.reports') }}"
                            class="sidebar-item {{ request()->routeIs('dashboard.reports') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium text-gray-400 hover:text-red-500">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            Manage Reports
                        </a>
                        <a href="{{ route('dashboard.users') }}"
                            class="sidebar-item {{ request()->routeIs('dashboard.users') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium text-gray-400 hover:text-blue-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                            User Management
                        </a>
                        @if($roleValue && in_array($roleValue, ['owner']))
                        <a href="{{ route('admin.ads.moderation.index') }}"
                            class="sidebar-item {{ request()->routeIs('admin.ads.*') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium text-gray-400 hover:text-red-500">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            Ad Moderation
                        </a>
                        @endif
                    @endif

                    @if($roleValue && in_array($roleValue, ['advertiser']))
                        <div class="pt-6 px-3 mb-2 text-xs font-semibold tracking-wider text-gray-500 uppercase">
                            Advertiser
                        </div>
                        <a href="{{ route('advertiser.dashboard') }}"
                            class="sidebar-item {{ request()->routeIs('advertiser.dashboard') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium text-gray-400 hover:text-red-500">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"></path>
                            </svg>
                            Ads Dashboard
                        </a>
                        <a href="{{ route('advertiser.ads.create') }}"
                            class="sidebar-item {{ request()->routeIs('advertiser.ads.create') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium text-gray-400 hover:text-red-500">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Request Ad
                        </a>
                    @endif
                </nav>

                <!-- Sidebar Footer -->
                <div class="p-4 border-t border-white/5">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="flex items-center w-full gap-3 px-4 py-3 rounded-xl text-sm font-medium text-red-400 hover:bg-red-500/10 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- Main Content Area -->
        <main class="flex-1 flex flex-col min-w-0 overflow-hidden bg-[#050505]">
            <div class="flex-1 overflow-y-auto p-8 custom-scrollbar flex flex-col justify-between">
                <div>
                    {{ $slot }}
                </div>

                <!-- Bottom Internal Ads for Dashboard Content -->
                @php
                    $dashboardBottomBanners = \App\Helper\AdTracker::getBanners(2, 0);
                @endphp
                @if($dashboardBottomBanners->isNotEmpty())
                    <div class="mt-8 pt-6 border-t border-red-950/20 flex flex-col items-center gap-2">
                        <span class="text-[8px] font-black text-red-500 uppercase tracking-[0.2em] select-none flex items-center gap-1.5 font-mono">
                            <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                            OFFICIAL PLATFORM SPONSORS
                        </span>
                        <div class="flex flex-wrap justify-center gap-4 w-full">
                            @foreach($dashboardBottomBanners as $banner)
                                <a href="{{ route('ads.click', $banner->id) }}" target="_blank" class="block w-full max-w-[468px] h-[60px] border border-red-950/30 hover:border-red-650/40 overflow-hidden rounded bg-[#0a0a0a]/30 transition-all duration-150 relative group">
                                    <img src="{{ asset($banner->media_url) }}" alt="{{ $banner->title }}" class="w-full h-full object-cover opacity-80 group-hover:opacity-100 transition-opacity duration-150">
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </main>
    </div>

    <!-- Global Cyberpunk Modal Container -->
    <div id="global-action-modal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4 opacity-0 transition-opacity duration-200" style="backdrop-filter: blur(8px); background-color: rgba(0, 0, 0, 0.85);" data-modal-container>
        <div class="relative w-full max-w-md bg-[#0a0a0a] border rounded-sm overflow-hidden transform scale-95 transition-transform duration-200 ease-out shadow-2xl shadow-black/90" id="global-modal-box" data-modal-box style="border-color: rgba(153, 27, 27, 0.4);">
            <!-- Modal Header -->
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

            <!-- Modal Content -->
            <div class="p-6 md:p-8 text-xs text-gray-300 font-mono leading-relaxed" id="global-modal-body">
                SYSTEM MESSAGE BODY
            </div>

            <!-- Modal Footer -->
            <div class="px-6 py-4 border-t bg-[#050505] flex justify-end gap-3" id="global-modal-footer" style="border-color: rgba(153, 27, 27, 0.1);">
                <button type="button" onclick="closeModal('global-action-modal')" class="text-[9px] font-black uppercase tracking-widest text-gray-500 hover:text-white px-4 py-2 transition-colors duration-150" id="global-cancel-btn">Cancel</button>
                <button type="button" class="text-[9px] font-black uppercase tracking-widest px-4 py-2 border rounded-sm bg-red-950/20 text-red-500 border-red-900/30 hover:bg-red-600 hover:text-white transition-colors duration-150" id="global-confirm-btn">Proceed</button>
            </div>
        </div>
    </div>

    <script>
        // Simple sidebar toggle for mobile
        const sidebar = document.getElementById('sidebar');
        
        function openModal(id) {
            const modal = document.getElementById(id);
            if (!modal) return;
            
            modal.classList.remove('hidden');
            void modal.offsetWidth; // Trigger reflow
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

        // Global Modal Confirmation Helper
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