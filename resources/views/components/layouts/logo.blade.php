@props([
    'iconClass' => 'w-8 h-8 text-red-600 shrink-0',
    'showWordmark' => true,
])

<div {{ $attributes->merge(['class' => 'inline-flex items-center gap-2']) }}>
    <x-layouts.icon :class="$iconClass" />
    @if($showWordmark)
        <div class="flex items-baseline leading-none">
            <span class="text-xl font-bold text-white tracking-tight">Dox</span>
            <span class="text-xl font-bold text-red-600 tracking-tight">Me</span>
        </div>
    @endif
</div>
