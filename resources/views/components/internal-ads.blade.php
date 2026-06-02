@php
    $internalAds = \App\Helper\AdTracker::getBanners(0, 0);
@endphp

@if($internalAds->isNotEmpty())
    <div {{ $attributes->merge(['class' => 'w-full']) }}>
        <div class="flex flex-col items-center gap-3 w-full">
            @foreach($internalAds as $ad)
                <a href="{{ route('ads.click', $ad->id) }}" target="_blank"
                    class="block w-full max-w-[566px] h-[72px] border border-red-950/30 hover:border-red-500/80 overflow-hidden rounded bg-[#0a0a0a]/30 transition-all duration-150 relative group">
                    <img src="{{ asset($ad->media_url) }}" alt="{{ $ad->title }}"
                        class="w-full h-full object-cover opacity-80 group-hover:opacity-100 transition-opacity duration-150">
                </a>
            @endforeach
        </div>
    </div>
@endif
