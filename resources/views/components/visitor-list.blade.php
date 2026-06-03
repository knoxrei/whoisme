@props([
    'visitors' => [],
    'count' => null,
    'countId' => 'root-visitor-count',
    'listId' => 'root-visitor-list',
    'emptyText' => 'No one online right now.',
])

<div {{ $attributes->merge(['class' => 'w-full']) }}>
    <p class="text-sm text-gray-500 text-center mb-2">
        Online now —
        <span class="text-red-500 font-medium tabular-nums" id="{{ $countId }}">{{ $count ?? count($visitors) }}</span>
    </p>
    <div id="{{ $listId }}" class="text-sm text-gray-400 text-center leading-relaxed break-words">
        @if(count($visitors) > 0)
            @php
                $labels = collect($visitors)->map(function ($visitor) {
                    if (($visitor['type'] ?? null) === 'member' && !empty($visitor['role'])) {
                        $role = \App\Enum\Role::from($visitor['role']);
                        return $role->userStyle('@' . $visitor['name']);
                    }
                    return '<span class="text-gray-500">' . e($visitor['name']) . '</span>';
                });
            @endphp
            {!! $labels->implode('<span class="text-gray-700">, </span>') !!}
        @else
            <span class="text-gray-600">{{ $emptyText }}</span>
        @endif
    </div>
</div>
