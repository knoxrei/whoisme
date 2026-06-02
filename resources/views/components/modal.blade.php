@props([
    'id',
    'title' => '',
    'size' => 'md', 
])

@php
    $sizeClasses = match ($size) {
        'sm' => 'max-w-md',
        'lg' => 'max-w-2xl',
        'xl' => 'max-w-5xl',
        default => 'max-w-lg', 
    };
@endphp

<div id="{{ $id }}" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4 opacity-0 transition-opacity duration-200" style="backdrop-filter: blur(8px); background-color: rgba(0, 0, 0, 0.85);" data-modal-container>
    <div class="relative w-full {{ $sizeClasses }} bg-[#0a0a0a] border border-red-900/40 rounded-sm overflow-hidden transform scale-95 transition-transform duration-200 ease-out shadow-2xl shadow-black/80" data-modal-box>
        
        <div class="flex items-center justify-between px-6 py-4 border-b border-red-900/20 bg-[#111]">
            <h3 class="text-xs font-black text-red-500 uppercase tracking-[0.2em] font-mono">
                {{ $title }}
            </h3>
            <button type="button" onclick="closeModal('{{ $id }}')" class="text-gray-500 hover:text-white transition-colors duration-150">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <div class="p-6 md:p-8 text-xs text-gray-300 font-mono leading-relaxed max-h-[75vh] overflow-y-auto scrollbar-thin scrollbar-thumb-red-900 scrollbar-track-transparent">
            {{ $slot }}
        </div>

        @if(isset($footer))
            <div class="px-6 py-4 border-t border-red-900/10 bg-[#050505] flex justify-end gap-3">
                {{ $footer }}
            </div>
        @endif
    </div>
</div>
