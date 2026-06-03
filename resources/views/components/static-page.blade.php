@props([
    'title',
    'highlight' => null,
    'subtitle' => null,
])

<div class="text-gray-300 py-12 px-4">
    <main class="w-full max-w-4xl mx-auto">
        <header class="mb-10 border-b border-red-900/30 pb-6">
            <h1 class="text-3xl md:text-4xl font-bold text-white tracking-tight mb-2">
                {{ $title }}
                @if($highlight)
                    <span class="text-red-600">{{ $highlight }}</span>
                @endif
            </h1>
            @if($subtitle)
                <p class="text-sm text-gray-500">{{ $subtitle }}</p>
            @endif
        </header>

        {{ $slot }}
    </main>
</div>
