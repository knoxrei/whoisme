@props([
    'title' => null,
    'description' => null,
    'ogImage' => null,
    'canonicalUrl' => null,
    'keywords' => null
])
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title> {{ $title ?? 'No have title' }} - {{ config('app.name') }}</title>

    @php
        $seoDescription = $description ?? 'doxme - leak database, threat intelligence search engine, and secure pastebin terminal.';
        $seoKeywords = $keywords ?? 'doxme, doxbin, pastebin, leaks, cyber security, threat intelligence';
        $seoImage = $ogImage ?? asset('storage/avatars/default.png');
        $seoUrl = $canonicalUrl ?? request()->url();
    @endphp

    <!-- SEO Meta Tags -->
    @if($seoDescription)
        <meta name="description" content="{{ $seoDescription }}">
    @endif
    <meta name="keywords" content="{{ $seoKeywords }}">
    <link rel="canonical" href="{{ $seoUrl }}">

    <!-- Open Graph / Facebook / Discord / Telegram -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ $seoUrl }}">
    <meta property="og:title" content="{{ $title ?? 'No have title' }} - {{ config('app.name') }}">
    @if($seoDescription)
        <meta property="og:description" content="{{ $seoDescription }}">
    @endif
    @if($seoImage)
        <meta property="og:image" content="{{ $seoImage }}">
    @endif
    <meta property="og:site_name" content="{{ config('app.name') }}">

    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:url" content="{{ $seoUrl }}">
    <meta name="twitter:title" content="{{ $title ?? 'No have title' }} - {{ config('app.name') }}">
    @if($seoDescription)
        <meta name="twitter:description" content="{{ $seoDescription }}">
    @endif
    @if($seoImage)
        <meta name="twitter:image" content="{{ $seoImage }}">
    @endif

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
<link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
<link rel="manifest" href="/site.webmanifest">
    @fonts

    <!-- Styles / Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-[#050505]">
    <x-navbar />
    <main class="flex-grow flex flex-col min-h-screen">
        {{ $slot }}
         <x-footer />
    </main>

   

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

    <!-- Reusable Modal Animation and Global Javascript Handler Service -->
    <script>
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

        // Programmatic Global Dialog Window API
        window.doxmeModal = function(options = {}) {
            const modal = document.getElementById('global-action-modal');
            const box = document.getElementById('global-modal-box');
            const titleEl = document.getElementById('global-modal-title');
            const bodyEl = document.getElementById('global-modal-body');
            const cancelBtn = document.getElementById('global-cancel-btn');
            const confirmBtn = document.getElementById('global-confirm-btn');
            
            if (!modal) return;

            // Reset standard theme style mappings
            box.style.borderColor = 'rgba(153, 27, 27, 0.4)';
            titleEl.className = 'text-xs font-black uppercase tracking-[0.2em] font-mono text-red-500';
            confirmBtn.className = 'text-[9px] font-black uppercase tracking-widest px-4 py-2 border rounded-sm bg-red-950/20 text-red-500 border-red-900/30 hover:bg-red-600 hover:text-white transition-colors duration-150';

            // Apply selected visual styles
            const theme = options.type || 'danger';
            if (theme === 'success') {
                box.style.borderColor = 'rgba(22, 101, 52, 0.4)';
                titleEl.className = 'text-xs font-black uppercase tracking-[0.2em] font-mono text-green-500';
                confirmBtn.className = 'text-[9px] font-black uppercase tracking-widest px-4 py-2 border rounded-sm bg-green-950/20 text-green-500 border-green-900/30 hover:bg-green-600 hover:text-white transition-colors duration-150';
            } else if (theme === 'info') {
                box.style.borderColor = 'rgba(30, 58, 138, 0.4)';
                titleEl.className = 'text-xs font-black uppercase tracking-[0.2em] font-mono text-blue-500';
                confirmBtn.className = 'text-[9px] font-black uppercase tracking-widest px-4 py-2 border rounded-sm bg-blue-950/20 text-blue-500 border-blue-900/30 hover:bg-blue-600 hover:text-white transition-colors duration-150';
            } else if (theme === 'warning') {
                box.style.borderColor = 'rgba(113, 63, 18, 0.4)';
                titleEl.className = 'text-xs font-black uppercase tracking-[0.2em] font-mono text-yellow-500';
                confirmBtn.className = 'text-[9px] font-black uppercase tracking-widest px-4 py-2 border rounded-sm bg-yellow-950/20 text-yellow-500 border-yellow-900/30 hover:bg-yellow-600 hover:text-white transition-colors duration-150';
            }

            titleEl.textContent = options.title || 'SYSTEM NOTICE';
            bodyEl.innerHTML = options.content || '';
            confirmBtn.textContent = options.confirmText || 'Proceed';
            
            if (options.cancelText === false) {
                cancelBtn.classList.add('hidden');
            } else {
                cancelBtn.classList.remove('hidden');
                cancelBtn.textContent = options.cancelText || 'Cancel';
            }

            confirmBtn.onclick = function() {
                if (options.onConfirm && typeof options.onConfirm === 'function') {
                    options.onConfirm();
                }
                closeModal('global-action-modal');
            };

            // Toggle show
            modal.classList.remove('hidden');
            void modal.offsetWidth;
            modal.classList.remove('opacity-0');
            box.classList.remove('scale-95');
            box.classList.add('scale-100');
            document.body.classList.add('overflow-hidden');
        };

        // Close bindings: ESC key and backdrop click
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                const openModals = document.querySelectorAll('[data-modal-container]:not(.hidden)');
                openModals.forEach(modal => closeModal(modal.id));
            }
        });

        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('[data-modal-container]').forEach(modal => {
                modal.addEventListener('click', (e) => {
                    if (e.target === modal) {
                        closeModal(modal.id);
                    }
                });
            });
        });
        
    </script>
      <!-- Histats.com  START  (aync)-->
<script type="text/javascript">var _Hasync= _Hasync|| [];
_Hasync.push(['Histats.start', '1,5027683,4,0,0,0,00010000']);
_Hasync.push(['Histats.fasi', '1']);
_Hasync.push(['Histats.track_hits', '']);
(function() {
var hs = document.createElement('script'); hs.type = 'text/javascript'; hs.async = true;
hs.src = ('//s10.histats.com/js15_as.js');
(document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(hs);
})();</script>
<noscript><a href="/" target="_blank"><img  src="//sstatic1.histats.com/0.gif?5027683&101" alt="" border="0"></a></noscript>
<!-- Histats.com  END  -->
</body>

</html>