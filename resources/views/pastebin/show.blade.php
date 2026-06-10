@php
// Strip HTML tags from markdown to get clean text for meta description
$plainContent = strip_tags($contentMarkdown);
$plainContent = preg_replace('/\s+/', ' ', $plainContent); // normalize whitespace
$plainContent = trim($plainContent);

$seoDescription = $pastebin->description
? $pastebin->description
: (\Illuminate\Support\Str::limit($plainContent, 160) ?: 'View this pastebin record on DoxMe.');

// Extract keywords dynamically
$titleWords = str_word_count(strtolower($pastebin->title), 1);
$descWords = str_word_count(strtolower($seoDescription), 1);
$allWords = array_merge($titleWords, $descWords);
$stopWords = ['the', 'and', 'a', 'to', 'of', 'in', 'is', 'that', 'it', 'on', 'for', 'this', 'with', 'as', 'by', 'at', 'an', 'or', 'but'];
$keywordsArray = array_filter($allWords, function($word) use ($stopWords) {
return strlen($word) > 3 && !in_array($word, $stopWords) && !is_numeric($word);
});
$keywordsArray = array_unique($keywordsArray);
$seoKeywords = implode(', ', array_slice($keywordsArray, 0, 10));
if (empty($seoKeywords)) {
$seoKeywords = 'pastebin, secure paste, doxme, leak database';
} else {
$seoKeywords .= ', pastebin, doxme, leak';
}

// Index control: noindex if private, password-protected, or self-destructing
$robots = ($pastebin->visibility === \App\Enum\Visibility::PRIVATE || $pastebin->password || $pastebin->is_self_destruct)
? 'noindex, nofollow'
: 'index, follow';

// Determine OG Image
$ogImage = $pastebin->cover_path && $pastebin->cover_path !== 'defaultCover.png'
? asset('storage/' . $pastebin->cover_path): ($pastebin->images->count() > 0 ? asset('storage/' . $pastebin->images->first()->image_path) : null);
@endphp

<x-layouts.app
    :title="$title"
    :description="$seoDescription"
    :ogImage="$ogImage"
    :keywords="$seoKeywords"
    :robots="$robots"
    :twitterLabel1="'Author'"
    :twitterData1="$pastebin->author_name"
    :twitterLabel2="'Views'"
    :twitterData2="number_format($pastebin->views_count)">
    <x-slot:extraHead>
        <!-- JSON-LD Structured Data for Pastebin -->
        <script type="application/ld+json">
        {
            "@@context": "https://schema.org",
            "@@type": "TextDigitalDocument",
            "name": "{{ e($pastebin->title) }}",
            "description": "{{ e($seoDescription) }}",
            "url": "{{ request()->url() }}",
            "dateCreated": "{{ $pastebin->created_at->toIso8601String() }}",
            "datePublished": "{{ $pastebin->created_at->toIso8601String() }}",
            "dateModified": "{{ $pastebin->updated_at->toIso8601String() }}",
            "author": {
                "@@type": "Person",
                "name": "{{ e($pastebin->author_name) }}",
                "url": "{{ $pastebin->user ? route('profile.show', $pastebin->user->username) : null }}"
            },
            "publisher": {
                "@@type": "Organization",
                "name": "{{ config('app.name') }}",
                "logo": {
                    "@@type": "ImageObject",
                    "url": "{{ asset('favicon-32x32.png') }}"
                }
            },
            "interactionStatistic": [
                {
                    "@@type": "InteractionCounter",
                    "interactionType": "https://schema.org/WatchAction",
                    "userInteractionCount": {{ $pastebin->views_count }}
                },
                {
                    "@@type": "InteractionCounter",
                    "interactionType": "https://schema.org/DownloadAction",
                    "userInteractionCount": {{ $pastebin->download_count }}
                }
            ]
        }
        </script>

        <!-- Advanced OpenGraph SEO Tags -->
        <meta property="article:published_time" content="{{ $pastebin->created_at->toIso8601String() }}">
        <meta property="article:modified_time" content="{{ $pastebin->updated_at->toIso8601String() }}">
        <meta property="article:author" content="{{ e($pastebin->author_name) }}">
        <meta property="article:section" content="Pastebin">
        <meta property="article:tag" content="{{ e($seoKeywords) }}">
    </x-slot:extraHead>
    <div class="min-h-screen text-gray-300 font-sans">
        <div class="max-w-[1280px] mx-auto px-4 py-6">
            @if(session('success'))
            <div class="mb-3 px-4 py-2.5 border-l-2 border-green-600 bg-[#0a0a0a] text-green-400 text-xs font-mono">
                {{ session('success') }}
            </div>
            @endif
            @if(session('reputation_awarded'))
            <div class="mb-3 px-4 py-2.5 border-l-2 border-yellow-600 bg-[#0a0a0a] text-yellow-400 text-xs font-mono">
                {{ session('reputation_awarded') }}
            </div>
            @endif
            @if(session('info'))
            <div class="mb-3 px-4 py-2.5 border-l-2 border-blue-600 bg-[#0a0a0a] text-blue-400 text-xs font-mono">
                {{ session('info') }}
            </div>
            @endif
            @if(session('error'))
            <div class="mb-3 px-4 py-2.5 border-l-2 border-red-600 bg-[#0a0a0a] text-red-400 text-xs font-mono">
                {{ session('error') }}
            </div>
            @endif

            <!-- Title Bar -->
            <div class="border border-[#1e1e1e] bg-[#0d0d0d] px-5 py-3 mb-0.5">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-1">
                    <div class="flex items-center gap-2.5 min-w-0">
                        {!! $pastebin->visibility->badge() !!}
                        <h1 class="text-white font-bold text-sm md:text-base truncate">
                            {{ $pastebin->title }}
                        </h1>
                    </div>
                    <div class="flex items-center gap-4 flex-shrink-0 text-[11px] text-gray-500">
                        <span>
                            by&nbsp;<a
                                href="{{ $pastebin->user ? route('profile.show', $pastebin->user->username) : '#' }}"
                                style="color: {{ $pastebin->user ? $pastebin->user->identification->role->color() : '#888' }}"
                                class="font-semibold hover:underline">{{ $pastebin->author_name }}</a>
                        </span>
                        <span class="text-[#2a2a2a]">|</span>
                        <span>{{ $pastebin->created_at->format('d M Y, H:i') }}</span>
                        <span class="text-[#2a2a2a]">|</span>
                        <span>{{ number_format($pastebin->views_count) }} views</span>
                    </div>
                </div>
            </div>

            <!-- Main Layout: Left Profile Card + Content -->
            <div class="flex flex-col lg:flex-row gap-0.5 items-stretch">

                <!-- Left Profile Card -->
                <aside class="w-full lg:w-52 flex-shrink-0 relative">
                    <!-- Background panel fills full height of the row -->
                    <div class="absolute inset-0 border border-red-900/40 bg-[#050505]"></div>
                    <!-- Sticky inner content -->
                    <div class="sticky top-20 px-4 py-5 flex flex-col gap-4 relative z-10">

                        <!-- Avatar + Name -->
                        <div class="flex flex-col items-center text-center">
                            <div class="w-16 h-16 overflow-hidden border border-red-900/30 mb-3 flex-shrink-0">
                                @if($pastebin->user && $pastebin->user->identification->avatar_path)
                                <img src="{{ asset('storage/' . $pastebin->user->identification->avatar_path) }}" class="w-full h-full object-cover" alt="avatar">
                                @else
                                <img src="{{ asset('storage/avatars/default.png') }}" class="w-full h-full object-cover" alt="avatar">
                                @endif
                            </div>
                            <a href="{{ $pastebin->user ? route('profile.show', $pastebin->user->username) : '#' }}" class="block text-[12px] font-bold text-white hover:text-red-400 leading-tight mb-0.5">
                                @if($pastebin->user)
                                {!! $pastebin->user->identification->role->userStyleWithBanner($pastebin->author_name, $pastebin->user->identification->color_username ?? '#ffffff') !!}
                                @else
                                {{ $pastebin->author_name }}
                                @endif
                            </a>
                            @if($pastebin->user)
                            <div class="text-[9px] text-red-500 font-semibold uppercase tracking-widest">{{ $pastebin->user->identification->role->label() }}</div>
                            @else
                            <div class="text-[9px] text-gray-600 uppercase tracking-widest">Guest</div>
                            @endif
                        </div>

                        <!-- Divider -->
                        <div class="border-t border-red-900/20"></div>

                        <!-- Stats -->
                        <div class="space-y-2">
                            @if($pastebin->user)
                            <div class="flex justify-between text-[11px]">
                                <span class="text-gray-600">Posts</span>
                                <span class="text-gray-300 font-medium">{{ $pastebin->user->pastebins()->count() }}</span>
                            </div>
                            <div class="flex justify-between text-[11px]">
                                <span class="text-gray-600">Followers</span>
                                <span class="text-gray-300 font-medium">{{ $pastebin->user->followers()->count() }}</span>
                            </div>
                            <div class="flex justify-between text-[11px]">
                                <span class="text-gray-600">Joined</span>
                                <span class="text-gray-400">{{ $pastebin->user->created_at->format('M Y') }}</span>
                            </div>
                            @endif
                            <div class="flex justify-between text-[11px]">
                                <span class="text-gray-600">Views</span>
                                <span class="text-red-500 font-medium">{{ number_format($pastebin->views_count) }}</span>
                            </div>
                            <div class="flex justify-between text-[11px]">
                                <span class="text-gray-600">Downloads</span>
                                <span class="text-gray-400">{{ number_format($pastebin->download_count ?? 0) }}</span>
                            </div>
                        </div>

                        <!-- Divider -->
                        <div class="border-t border-red-900/20"></div>

                        <!-- Online Visitors -->
                        <div>
                            <div class="flex items-center gap-1.5 mb-2">
                                <span class="w-1.5 h-1.5 rounded-full bg-green-500 flex-shrink-0"></span>
                                <span class="text-[9px] font-semibold text-gray-500 uppercase tracking-widest">Online (<span id="visitor-count">{{ count($visitors) }}</span>)</span>
                            </div>
                            <div id="visitor-list" class="text-[11px] text-gray-500 font-mono leading-relaxed break-words">
                                @if(count($visitors) > 0)
                                @php
                                $visitorLabels = collect($visitors)->map(function($visitor) {
                                    if ($visitor['type'] === 'member') {
                                        $role = \App\Enum\Role::from($visitor['role']);
                                        return $role->userStyle('@' . $visitor['name']);
                                    }
                                    return '<span class="text-gray-600">' . e($visitor['name']) . '</span>';
                                });
                                @endphp
                                {!! $visitorLabels->implode(', ') !!}
                                @else
                                <span class="text-gray-700">No active visitors</span>
                                @endif
                            </div>
                        </div>

                    </div>
                </aside>

                <!-- Main Content -->
                <main class="flex-1 min-w-0 flex flex-col gap-0.5">

                    <!-- Action Toolbar -->
                    <div class="border border-red-900/40 bg-[#050505] px-4 py-2 flex items-center justify-between">
                        <div class="flex items-center gap-1">
                            @auth
                            @can('delete', $pastebin)
                            <form id="delete-paste-form" action="{{ route('pastebin.destroy', $pastebin) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="button" onclick="confirmDelete()" class="px-3 py-1 text-[10px] font-semibold text-red-500 border border-[#2a1a1a] hover:bg-red-600 hover:text-white hover:border-red-600 uppercase">
                                    Delete
                                </button>
                            </form>
                            @endcan
                            @can('update', $pastebin)
                            <button onclick="document.getElementById('edit-modal').classList.remove('hidden')" class="px-3 py-1 text-[10px] font-semibold text-gray-400 border border-red-900/20 hover:border-red-600 hover:text-white uppercase">Edit</button>
                            @else
                            <button onclick="document.getElementById('edit-modal').classList.remove('hidden')" class="px-3 py-1 text-[10px] font-semibold text-gray-400 border border-red-900/20 hover:border-red-600 hover:text-white uppercase">Suggest Edit</button>
                            @endcan
                            @endauth
                            <button onclick="document.getElementById('report-modal').classList.remove('hidden')" class="px-3 py-1 text-[10px] font-semibold text-gray-500 border border-red-900/20 hover:border-red-600 hover:text-white uppercase">Report</button>
                            @guest
                            <a href="{{ route('login') }}" class="px-3 py-1 text-[10px] font-semibold text-gray-500 border border-red-900/20 hover:border-red-600 hover:text-white uppercase">Suggest Edit</a>
                            @endguest
                        </div>
                        <div class="flex items-center gap-1">
                            <button onclick="openShareModal()" class="px-3 py-1 text-[10px] font-semibold text-gray-400 border border-red-900/20 hover:border-red-600 hover:text-white uppercase">Share</button>
                            <a href="{{ route('pastebin.raw', $pastebin->slug) }}" target="_blank" class="px-3 py-1 text-[10px] font-semibold text-gray-400 border border-red-900/20 hover:border-red-600 hover:text-white uppercase">Raw</a>
                            <a href="{{ route('pastebin.download', $pastebin->slug) }}" class="px-3 py-1 text-[10px] font-semibold text-red-500 border border-[#2a1a1a] hover:bg-red-600 hover:text-white hover:border-red-600 uppercase">Download</a>
                        </div>
                    </div>

                    <!-- Content Area -->
                    <div class="border border-red-900/40 bg-[#050505]" id="content-section-container">

                        <!-- Burn-After-Reading Alert -->
                        @if(isset($isBurned) && $isBurned)
                        <div class="mx-5 mt-5 p-3 border border-dashed border-red-800 text-red-400 font-mono text-xs flex items-start gap-3">
                            <span class="flex-shrink-0">⚠</span>
                            <div>
                                <div class="font-bold uppercase mb-1 text-red-500">BURN AFTER READING — Content Cleared</div>
                                <p class="text-gray-500 text-[10px] leading-relaxed">This pastebin was flagged for zero-trace self-destruction. Records, cover pictures, gallery attachments, and comments have been permanently wiped. This is your only window to copy the content.</p>
                            </div>
                        </div>
                        @endif

                        <!-- Cover Image -->
                        @if($pastebin->cover_path && $pastebin->cover_path !== 'defaultCover.png')
                        <div class="border-b border-[#1a1a1a] overflow-hidden">
                            <img src="{{ asset('storage/' . $pastebin->cover_path) }}" class="w-full max-h-[380px] object-cover" alt="cover">
                        </div>
                        @endif

                        <!-- Maximize Button -->
                        <button id="minimize-fixed-btn" onclick="toggleMaximizeContent()" class="hidden fixed top-4 right-4 z-[1001] bg-[#050505] border border-red-900/40 p-2" title="Minimize">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 4v4m0 0H4m4 0l-5-5m11 1V4m0 0h4m-4 0l5-5M8 20v-4m0 0H4m4 0l5 5m11-1v4m0-4h4m-4 0l5 5" />
                            </svg>
                        </button>

                        <style>
                            .markdown-body {
                                font-size: 13px;
                                line-height: 1.7;
                                word-break: break-word;
                                overflow-wrap: anywhere;
                            }
                            .markdown-body p,
                            .markdown-body li {
                                margin-bottom: 0.6rem;
                            }
                            .markdown-body h1 {
                                font-size: 16px;
                                font-weight: 700;
                                color: #e5e5e5;
                                margin-bottom: 0.5rem;
                                margin-top: 1.25rem;
                                padding-bottom: 0.4rem;
                                border-bottom: 1px solid #1e1e1e;
                            }
                            .markdown-body h2 {
                                font-size: 14px;
                                font-weight: 700;
                                color: #e5e5e5;
                                margin-bottom: 0.4rem;
                                margin-top: 1rem;
                            }
                            .markdown-body h3 {
                                font-size: 13px;
                                font-weight: 600;
                                color: #d1d1d1;
                                margin-bottom: 0.3rem;
                                margin-top: 0.75rem;
                            }
                            .markdown-body a {
                                color: #ef4444;
                                text-decoration: underline;
                                word-break: break-all;
                            }
                            .markdown-body a:hover { color: #dc2626; }
                            .markdown-body ul {
                                list-style-type: disc;
                                padding-left: 1.5rem;
                                margin-bottom: 0.75rem;
                            }
                            .markdown-body ol {
                                list-style-type: decimal;
                                padding-left: 1.5rem;
                                margin-bottom: 0.75rem;
                            }
                            .markdown-body li { margin-bottom: 0.2rem; }
                            .markdown-body blockquote {
                                border-left: 3px solid #3a1a1a;
                                padding: 0.5rem 1rem;
                                color: #9ca3af;
                                font-style: italic;
                                background-color: #0d0d0d;
                                margin-bottom: 1rem;
                            }
                            .markdown-body code {
                                font-family: 'Courier New', Courier, monospace;
                                background-color: #111;
                                padding: 0.1rem 0.3rem;
                                font-size: 12px;
                                word-break: break-all;
                                color: #e5e5e5;
                                border: 1px solid #222;
                            }
                            .markdown-body pre {
                                background-color: #050505;
                                padding: 1rem 1.25rem;
                                overflow-x: auto;
                                border: 1px solid #1a1a1a;
                                margin-bottom: 1rem;
                                font-size: 12px;
                                white-space: pre-wrap;
                                word-wrap: break-word;
                            }
                            .markdown-body pre code {
                                background-color: transparent;
                                border: none;
                                padding: 0;
                                font-size: 12px;
                                word-break: normal;
                            }
                            .markdown-body hr {
                                border-color: #1e1e1e;
                                margin: 1.5rem 0;
                            }
                            .markdown-body table {
                                width: 100%;
                                border-collapse: collapse;
                                margin-bottom: 1rem;
                                font-size: 12px;
                            }
                            .markdown-body th,
                            .markdown-body td {
                                border: 1px solid #222;
                                padding: 0.4rem 0.75rem;
                                text-align: left;
                                overflow-wrap: anywhere;
                            }
                            .markdown-body th {
                                background-color: #0d0d0d;
                                font-weight: 600;
                                color: #ccc;
                            }
                            #pastebin-content-wrapper {
                                overflow: hidden;
                            }
                            #pastebin-content-wrapper.collapsed {
                                max-height: 700px;
                            }
                            #pastebin-content-wrapper.expanded {
                                max-height: none;
                            }
                            #content-section-container.maximized {
                                position: fixed;
                                top: 0; left: 0;
                                width: 100vw; height: 100vh;
                                z-index: 1000;
                                background-color: #050505;
                                padding: 2.5rem;
                                overflow-y: auto;
                            }
                            #content-section-container.maximized #pastebin-content-wrapper {
                                max-height: none !important;
                                max-width: 960px;
                                margin: 0 auto;
                            }
                            #content-section-container.maximized #view-full-btn-container {
                                position: sticky;
                                bottom: 0;
                                background: #050505;
                                padding: 1rem 0;
                                margin-top: 1.5rem;
                                border-top: 1px solid rgba(153,27,27,0.3);
                                z-index: 10;
                            }
                        </style>

                        <div id="pastebin-content-wrapper" class="collapsed markdown-body text-gray-300 px-6 py-5 font-mono text-[13px] overflow-x-auto leading-relaxed">
                            {!! $contentMarkdown !!}
                        </div>

                        <!-- View Full / Maximize Buttons -->
                        <div id="view-full-btn-container" class="border-t border-red-900/20 px-4 py-2 flex items-center gap-2 bg-[#050505]">
                            <button id="view-full-btn" onclick="toggleViewFull()" class="flex items-center gap-1.5 text-[10px] font-semibold text-red-500 hover:text-white uppercase border border-red-900/30 px-3 py-1 hover:border-red-600">
                                <svg id="view-full-icon" class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" />
                                </svg>
                                <span id="view-full-text">Show Full</span>
                            </button>
                            <button id="maximize-btn" onclick="toggleMaximizeContent()" class="flex items-center gap-1.5 text-[10px] font-semibold text-gray-500 hover:text-white uppercase border border-red-900/20 px-3 py-1 hover:border-red-600">
                                <svg id="maximize-icon" class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4" />
                                </svg>
                                <span id="maximize-text">Fullscreen</span>
                            </button>
                        </div>
                    </div>

                    <!-- Gallery Images -->
                    @if($pastebin->images && $pastebin->images->count() > 0)
                    <div class="border border-red-900/40 bg-[#050505] px-5 py-4">
                        <div class="text-[10px] font-semibold text-gray-500 uppercase tracking-widest mb-3 flex items-center gap-2">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            Gallery ({{ $pastebin->images->count() }})
                        </div>
                        <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-6 gap-2">
                            @foreach($pastebin->images as $image)
                            <a href="{{ asset('storage/' . $image->image_path) }}" target="_blank" class="aspect-square border border-red-900/20 overflow-hidden block">
                                <img src="{{ asset('storage/' . $image->image_path) }}" class="w-full h-full object-cover" alt="evidence">
                            </a>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- Sponsor Banners Section -->
                    @php
                    $showBanners = \App\Helper\AdTracker::getBanners(0, 0);
                    @endphp

                    @if($showBanners->isNotEmpty())
                    <div class="border border-red-900/30 bg-[#050505] px-5 py-3">
                        <p class="text-[9px] text-gray-600 font-semibold uppercase tracking-widest text-center mb-3 select-none">Sponsored</p>
                        <div class="flex flex-wrap justify-center gap-3">
                            @foreach($showBanners as $banner)
                            <a href="{{ route('ads.click', $banner->id) }}" target="_blank"
                                class="block w-full max-w-[466px] h-[58px] border border-red-950/50 hover:border-red-600/50 overflow-hidden bg-black">
                                <img src="{{ asset($banner->media_url) }}" alt="{{ $banner->title }}" class="w-full h-full object-cover opacity-75 hover:opacity-100">
                            </a>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- Comments Section -->
                    <div class="border border-red-900/40 bg-[#050505]">
                        <div class="px-5 py-3 border-b border-red-900/20 flex items-center justify-between">
                            <span class="text-[10px] font-semibold text-gray-500 uppercase tracking-widest">Comments</span>
                        </div>

                        <style>
                            .comment-content blockquote {
                                border-left: 2px solid #2a1a1a;
                                padding: 0.25rem 0.75rem;
                                font-style: italic;
                                color: #6b7280;
                                background-color: #0a0a0a;
                                margin: 0.4rem 0;
                            }
                            .comment-content p { margin-bottom: 0.4rem; }
                            .comment-content p:last-child { margin-bottom: 0; }
                        </style>

                        <div class="divide-y divide-red-900/10">
                            @if(isset($comments) && count($comments) > 0)
                            @foreach($comments as $comment)
                            <div class="px-5 py-4 flex gap-3">
                                <div class="w-7 h-7 overflow-hidden flex-shrink-0 border border-red-900/20">
                                    @if($comment->user->identification->avatar_path)
                                    <img src="{{ asset('storage/' . $comment->user->identification->avatar_path) }}" class="w-full h-full object-cover" alt="">
                                    @else
                                    <div class="w-full h-full flex items-center justify-center text-[10px] font-bold text-gray-600 bg-[#0a0a0a]">
                                        {{ strtoupper(substr($comment->user->username, 0, 1)) }}
                                    </div>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between mb-1.5">
                                        <a href="{{ route('profile.show', $comment->user->username) }}" class="text-[11px] font-semibold">
                                            {!! $comment->user->identification->role->userStyleWithBanner($comment->user->username, $comment->user->identification->custom_color ?? '#ffffff') !!}
                                        </a>
                                        <div class="flex items-center gap-3">
                                            <span class="text-[10px] text-gray-600">{{ $comment->created_at->diffForHumans() }}</span>
                                            @auth
                                            <button type="button" onclick="replyToComment('{{ $comment->user->username }}', {{ json_encode($comment->content) }})" class="text-[10px] text-gray-600 hover:text-red-500 uppercase">Reply</button>
                                            @endauth
                                        </div>
                                    </div>
                                    <div class="text-[12px] text-gray-400 font-mono leading-relaxed comment-content">
                                        {!! \Illuminate\Support\Str::markdown($comment->content) !!}
                                    </div>
                                </div>
                            </div>
                            @endforeach
                            @else
                            <div class="px-5 py-6 text-[11px] text-gray-700 font-mono text-center">
                                No comments yet.
                            </div>
                            @endif
                        </div>

                        @auth
                        <form action="{{ route('pastebin.comments.store', $pastebin) }}" method="POST" class="border-t border-red-900/20 px-5 py-4">
                            @csrf
                            <div class="flex flex-col gap-2">
                                <textarea id="comment-textarea" name="content" rows="3" placeholder="Write a comment..." required class="w-full bg-[#000] border border-red-900/20 px-3 py-2 text-[12px] font-mono text-gray-300 focus:outline-none focus:border-red-600 resize-none"></textarea>
                                @error('content') <span class="text-red-500 text-[10px]">{{ $message }}</span> @enderror
                                <div class="flex justify-end">
                                    <button type="submit" class="border border-red-900/40 text-red-500 hover:bg-red-600 hover:text-white hover:border-red-600 px-5 py-1.5 text-[10px] font-semibold uppercase">Post</button>
                                </div>
                            </div>
                        </form>
                        <script>
                            function replyToComment(username, text) {
                                const textarea = document.getElementById('comment-textarea');
                                const quotedText = text.split('\n').map(line => '> ' + line).join('\n');
                                const replyFormat = `> **@${username}** said:\n${quotedText}\n\n`;
                                textarea.value = textarea.value ? textarea.value + '\n' + replyFormat : replyFormat;
                                textarea.focus();
                                textarea.scrollIntoView({ behavior: 'smooth', block: 'center' });
                            }
                        </script>
                        @else
                        <div class="border-t border-red-900/20 px-5 py-4 text-[11px] text-gray-600 text-center">
                            <a href="{{ route('login') }}" class="text-red-500 hover:underline">Log in</a> to post a comment.
                        </div>
                        @endauth
                    </div>

                    <!-- Pending Suggestions Block -->
                    @if(auth()->check() && (auth()->id() === $pastebin->user_id || auth()->user()->canUsePremiumFeatures()))
                    @if(isset($pendingEdits) && count($pendingEdits) > 0)
                    <div class="border border-red-900/40 bg-[#050505]">
                        <div class="px-5 py-3 border-b border-red-900/20 flex items-center gap-2">
                            <span class="w-1.5 h-1.5 bg-red-600 rounded-full inline-block"></span>
                            <span class="text-[10px] font-semibold text-gray-400 uppercase tracking-widest">Pending Edits ({{ count($pendingEdits) }})</span>
                        </div>
                        <div class="divide-y divide-red-900/10">
                            @foreach($pendingEdits as $edit)
                            <div class="px-5 py-4 flex items-start gap-4">
                                <div class="flex-shrink-0 w-8 h-8 border border-red-900/20 flex items-center justify-center text-[10px] font-bold text-gray-500 bg-[#0a0a0a]">
                                    {{ strtoupper(substr($edit->user->username, 0, 1)) }}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="text-[11px] font-semibold text-gray-300">{{ $edit->user->username }}</div>
                                    <div class="text-[10px] text-gray-600 mb-2">{{ $edit->created_at->diffForHumans() }}</div>
                                    <div class="text-[11px] text-gray-500 italic border-l-2 border-[#2a1a1a] pl-2 mb-3">"{{ $edit->title }}"</div>
                                    <div class="flex gap-2">
                                        <form action="{{ route('pastebin.edit.approve', $edit) }}" method="POST">
                                            @csrf
                                            <button class="px-4 py-1 text-[10px] font-semibold text-green-500 border border-[#1a2a1a] hover:bg-green-700 hover:text-white hover:border-green-700 uppercase">Approve</button>
                                        </form>
                                        <form action="{{ route('pastebin.edit.reject', $edit) }}" method="POST">
                                            @csrf
                                            <button class="px-4 py-1 text-[10px] font-semibold text-gray-500 border border-[#222] hover:bg-red-800 hover:text-white hover:border-red-800 uppercase">Reject</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                    @endif

                </main>

            </div>
        </div>
    </div>

    <!-- Edit/Suggest Modal -->
    <div id="edit-modal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-black/95"></div>
        <div class="relative bg-[#0a0a0a] border border-red-900/40 w-full max-w-4xl rounded-sm overflow-hidden">
            <div class="flex items-center justify-between px-6 py-4 border-b border-red-900/40 bg-[#111]">
                <h2 class="text-sm font-black text-red-500 uppercase tracking-[0.2em]">
                    @can('update', $pastebin) Edit Thread @else Suggest Improvement @endcan
                </h2>
                <button onclick="document.getElementById('edit-modal').classList.add('hidden')" class="text-gray-500 hover:text-white">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form action="@can('update', $pastebin) {{ route('pastebin.update', $pastebin) }} @else {{ route('pastebin.edit.store', $pastebin) }} @endcan" method="POST" enctype="multipart/form-data" class="p-8 space-y-8">
                @csrf
                @can('update', $pastebin) @method('PUT') @endcan

                @can('update', $pastebin)
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-gray-600 uppercase tracking-widest">Title</label>
                        <input type="text" name="title" value="{{ $pastebin->title }}" class="w-full bg-[#050505] border border-red-900/20 px-4 py-3 text-xs text-gray-300 focus:outline-none focus:border-red-600 rounded-sm">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-gray-600 uppercase tracking-widest">Description <span class="text-[8px] font-normal text-gray-500 normal-case">(Optional - Helps in finding it on search engines)</span></label>
                        <input type="text" name="description" value="{{ $pastebin->description }}" class="w-full bg-[#050505] border border-red-900/20 px-4 py-3 text-xs text-gray-300 focus:outline-none focus:border-red-600 rounded-sm">
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mt-4">
                    <div class="space-y-2">
                        <label for="cover_path" class="text-[10px] font-black text-gray-600 uppercase tracking-widest">Cover Image <span class="text-[8px] font-normal text-red-500 normal-case">(Optional - New cover replaces old)</span></label>
                        <input type="file" name="cover_path" id="cover_path" class="w-full bg-[#050505] border border-red-900/20 px-4 py-2 text-xs text-gray-300 focus:outline-none focus:border-red-600 rounded-sm file:mr-4 file:py-1 file:px-3 file:rounded file:border-0 file:text-xs file:font-semibold file:bg-red-600 file:text-white hover:file:bg-red-700 cursor-pointer">
                    </div>
                    <div class="space-y-2">
                        <label for="edit_image" class="text-[10px] font-black text-gray-600 uppercase tracking-widest">Gallery Images (Max 5) <span class="text-[8px] font-normal text-red-500 normal-case">(Optional - Appends to gallery)</span></label>
                        <input type="file" name="image[]" id="edit_image" multiple class="w-full bg-[#050505] border border-red-900/20 px-4 py-2 text-xs text-gray-300 focus:outline-none focus:border-red-600 rounded-sm file:mr-4 file:py-1 file:px-3 file:rounded file:border-0 file:text-xs file:font-semibold file:bg-gray-800 file:text-white hover:file:bg-gray-700 cursor-pointer">
                    </div>
                </div>

                @if($pastebin->images && $pastebin->images->count() > 0)
                <div class="space-y-2 mt-6">
                    <label class="text-[10px] font-black text-gray-600 uppercase tracking-widest">Manage Existing Gallery Images</label>
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4 p-4 border border-red-900/20 bg-[#050505] rounded-sm">
                        @foreach($pastebin->images as $image)
                        <div class="relative aspect-square border border-red-900/20 p-1 group transition-all duration-150 image-to-delete-container">
                            <img src="{{ asset('storage/' . $image->image_path) }}" class="w-full h-full object-cover rounded-sm image-preview" alt="existing evidence">
                            <!-- Overlay with checkbox -->
                            <div class="absolute inset-0 bg-black/40 group-hover:bg-black/70 flex items-center justify-center transition-all duration-150 overlay-delete opacity-0 group-hover:opacity-100">
                                <label class="flex flex-col items-center justify-center cursor-pointer text-gray-400 hover:text-red-500 text-[9px] font-black uppercase tracking-wider gap-2 select-none w-full h-full">
                                    <input type="checkbox" name="delete_images[]" value="{{ $image->id }}" onchange="toggleImageDeleteState(this)" class="form-checkbox text-red-600 focus:ring-red-600 h-4 w-4 bg-black border-red-900/40 rounded-sm cursor-pointer">
                                    <span class="delete-label">Delete</span>
                                </label>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <p class="text-[9px] text-gray-500 font-mono italic">Check "Delete" on any existing images you wish to remove upon saving changes.</p>
                </div>
                @endif
                @else
                <input type="hidden" name="title" value="{{ $pastebin->title }}">
                <input type="hidden" name="description" value="{{ $pastebin->description }}">
                @endcan
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-gray-600 uppercase tracking-widest">Content</label>
                    <textarea name="content" rows="12" class="w-full bg-[#050505] border border-red-900/20 px-4 py-3 text-xs font-mono text-gray-300 focus:outline-none focus:border-red-600 resize-none rounded-sm">{{ $pastebin->content }}</textarea>
                </div>
                <div class="flex justify-end gap-6 pt-6 border-t border-red-900/10">
                    <button type="button" onclick="document.getElementById('edit-modal').classList.add('hidden')" class="text-[10px] font-black uppercase tracking-widest text-gray-600 hover:text-white">Dismiss</button>
                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-10 py-3.5 font-black uppercase text-[10px] tracking-widest rounded-sm">
                        @can('update', $pastebin) Save Changes @else Transmit Suggestion @endcan
                    </button>
                </div>
            </form>
        </div>
    </div>
    @auth
    @can('delete', $pastebin)
    <script>
        function confirmDelete() {
            window.doxmeModal({
                title: 'CONFIRM DESTRUCTION',
                content: 'WARNING: You are about to initiate database erasure of this record. This operation will permanently purge all comments, evidence galleries, and index data associated with this thread.<br><br><span class="text-red-500 font-bold uppercase">This cannot be undone.</span>',
                confirmText: 'Execute Purge',
                cancelText: 'Abort',
                type: 'danger',
                onConfirm: () => {
                    document.getElementById('delete-paste-form').submit();
                }
            });
        }
    </script>
    @endcan
    @endauth

    <!-- Report Modal -->
    <div id="report-modal" class="fixed inset-0 z-[100] hidden flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-black/95 backdrop-blur-sm" onclick="document.getElementById('report-modal').classList.add('hidden')"></div>
        <div class="relative bg-[#0a0a0a] border border-red-900/40 w-full max-w-md rounded-sm overflow-hidden shadow-2xl">
            <div class="bg-[#111] px-5 py-3 border-b border-red-900/40 flex justify-between items-center">
                <span class="text-xs font-black text-red-500 uppercase tracking-widest">Report Thread</span>
                <button onclick="document.getElementById('report-modal').classList.add('hidden')" class="text-gray-500 hover:text-white transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <form action="{{ route('pastebin.report', $pastebin->slug) }}" method="POST" class="p-6 space-y-4">
                @csrf
                <div>
                    <label for="reason" class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2">Reason for Report</label>
                    <textarea id="reason" name="reason" required placeholder="Describe why this thread " rows="4"
                        class="w-full bg-[#111] border border-white/10 rounded-sm px-4 py-3 text-xs text-white focus:outline-none focus:border-red-500 focus:ring-1 focus:ring-red-500 font-mono bg-black"></textarea>
                </div>
                <div class="flex justify-end gap-3 pt-2">
                    <button type="button" onclick="document.getElementById('report-modal').classList.add('hidden')" class="px-4 py-2 border border-gray-700 bg-gray-900 hover:bg-gray-800 text-gray-400 text-[10px] font-black uppercase tracking-widest rounded-sm">Cancel</button>
                    <button type="submit" class="px-5 py-2 bg-red-600 hover:bg-red-700 text-white text-[10px] font-black uppercase tracking-widest rounded-sm">Submit Report</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Share Modal -->
    <div id="share-modal" class="fixed inset-0 z-[100] hidden flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-black/95 backdrop-blur-sm" onclick="closeShareModal()"></div>
        <div class="relative bg-[#0a0a0a] border border-red-900/40 w-full max-w-md rounded-sm overflow-hidden shadow-2xl">
            <div class="bg-[#111] px-5 py-3.5 border-b border-red-900/40 flex justify-between items-center">
                <span class="text-xs font-black text-red-500 uppercase tracking-widest">Share Pastebin</span>
                <button onclick="closeShareModal()" class="text-gray-500 hover:text-white transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div class="p-6 space-y-6">
                <!-- Social Share Links -->
                <div class="grid grid-cols-2 gap-3">
                    <a href="https://t.me/share/url?url={{ urlencode(request()->url()) }}&text={{ urlencode($pastebin->title) }}" target="_blank"
                        class="flex items-center justify-center gap-2 p-3 bg-[#0a0a0a] border border-red-900/20 hover:border-red-600 hover:bg-red-950/10 rounded-sm text-xs font-black uppercase tracking-wider text-gray-300 transition-all active:scale-95">
                        <svg class="w-4 h-4 text-red-500" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm4.64 6.8c-.15 1.58-.8 5.42-1.13 7.19-.14.75-.42 1-.68 1.03-.58.05-1.02-.38-1.58-.75-.88-.58-1.38-.94-2.23-1.5-.99-.65-.35-1.01.22-1.59.15-.15 2.71-2.48 2.76-2.69.01-.03.01-.14-.07-.2-.08-.06-.19-.04-.27-.02-.11.02-1.93 1.23-5.46 3.62-.51.35-.98.53-1.39.51-.46-.01-1.35-.26-2.01-.48-.8-.27-1.44-.42-1.39-.88.03-.24.37-.49 1.02-.75 3.98-1.73 6.64-2.88 7.98-3.45 3.8-1.61 4.59-1.9 5.1-.19.06.12.08.26.06.4z" />
                        </svg>
                        <span>Telegram</span>
                    </a>

                    <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->url()) }}&text={{ urlencode($pastebin->title) }}" target="_blank"
                        class="flex items-center justify-center gap-2 p-3 bg-[#0a0a0a] border border-red-900/20 hover:border-red-600 hover:bg-red-950/10 rounded-sm text-xs font-black uppercase tracking-wider text-gray-300 transition-all active:scale-95">
                        <svg class="w-4 h-4 text-red-500" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z" />
                        </svg>
                        <span>Twitter / X</span>
                    </a>
                </div>

                <!-- Copy Link Input -->
                <div class="flex flex-col gap-2">
                    <label class="text-[10px] font-bold text-gray-500 uppercase">Share Link</label>
                    <div class="flex items-center gap-2 bg-black border border-red-900/20 p-2.5 rounded-sm">
                        <input type="text" readonly id="share-link-input" value="{{ request()->url() }}"
                            class="bg-transparent text-xs text-gray-300 font-mono focus:outline-none flex-1 select-all cursor-text" />
                        <button onclick="copyShareLink()" class="px-4 py-1.5 border border-red-600 bg-red-600/10 hover:bg-red-600/20 text-red-500 text-[10px] font-black uppercase tracking-widest transition-all rounded-sm flex items-center gap-1 active:scale-95">
                            <span id="copy-share-btn-text">Copy</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Gallery selection limit for edit modal
        document.addEventListener('DOMContentLoaded', function() {
            const editImageInput = document.getElementById('edit_image');
            if (editImageInput) {
                editImageInput.addEventListener('change', function() {
                    if (this.files.length > 5) {
                        alert("You can only upload a maximum of 5 images");
                        this.value = '';
                    }
                });
            }
        });

        function toggleImageDeleteState(checkbox) {
            const container = checkbox.closest('.image-to-delete-container');
            const img = container.querySelector('.image-preview');
            const label = container.querySelector('.delete-label');
            const overlay = container.querySelector('.overlay-delete');
            if (checkbox.checked) {
                container.classList.add('border-red-600');
                container.classList.remove('border-red-900/20');
                img.style.filter = 'grayscale(100%) brightness(40%)';
                label.innerText = 'To Delete';
                label.classList.add('text-red-500');
                label.classList.remove('text-gray-400');
                overlay.style.opacity = '1';
                overlay.style.pointerEvents = 'auto';
            } else {
                container.classList.remove('border-red-600');
                container.classList.add('border-red-900/20');
                img.style.filter = '';
                label.innerText = 'Delete';
                label.classList.remove('text-red-500');
                label.classList.add('text-gray-400');
                overlay.style.opacity = '';
                overlay.style.pointerEvents = '';
            }
        }

        function openShareModal() {
            const modal = document.getElementById('share-modal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.style.overflow = 'hidden';
        }

        function closeShareModal() {
            const modal = document.getElementById('share-modal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.style.overflow = 'auto';
        }

        function copyShareLink() {
            const copyText = document.getElementById("share-link-input");
            copyText.select();
            copyText.setSelectionRange(0, 99999);

            navigator.clipboard.writeText(copyText.value).then(() => {
                const btnText = document.getElementById("copy-share-btn-text");
                btnText.innerText = "Copied!";
                btnText.style.color = '#22c55e'; // Tailwind green-500

                setTimeout(() => {
                    btnText.innerText = "Copy";
                    btnText.style.color = '';
                }, 2000);
            }).catch((err) => {
                console.error('Failed to copy text: ', err);
            });
        }

        // ── View Full Content Toggle ──────────────────────────────────────
        let isExpanded = false;

        function toggleViewFull() {
            const wrapper = document.getElementById('pastebin-content-wrapper');
            const btnContainer = document.getElementById('view-full-btn-container');
            const btnText = document.getElementById('view-full-text');
            const icon = document.getElementById('view-full-icon');

            if (!isExpanded) {
                wrapper.classList.remove('collapsed');
                wrapper.classList.add('expanded');
                wrapper.style.maxHeight = 'none';
                btnText.innerText = 'Collapse';
                icon.style.transform = 'rotate(180deg)';
                isExpanded = true;
            } else {
                wrapper.style.maxHeight = '700px';
                wrapper.classList.remove('expanded');
                wrapper.classList.add('collapsed');
                btnText.innerText = 'Show Full';
                icon.style.transform = 'rotate(0deg)';
                isExpanded = false;
                document.getElementById('pastebin-content-wrapper').scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        }

        // ── Maximize Content Toggle ───────────────────────────────────────
        let isMaximized = false;

        function toggleMaximizeContent() {
            const container = document.getElementById('content-section-container');
            const maximizeText = document.getElementById('maximize-text');
            const maximizeIcon = document.getElementById('maximize-icon');
            const fixedCloseBtn = document.getElementById('minimize-fixed-btn');

            if (!isMaximized) {
                container.classList.add('maximized');
                document.body.style.overflow = 'hidden';
                maximizeText.innerText = 'Exit';
                maximizeIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 4v4m0 0H4m4 0l-5-5m11 1V4m0 0h4m-4 0l5-5M8 20v-4m0 0H4m4 0l5 5m11-1v4m0-4h4m-4 0l5 5"/>';
                fixedCloseBtn.classList.remove('hidden');
                isMaximized = true;
            } else {
                container.classList.remove('maximized');
                document.body.style.overflow = 'auto';
                maximizeText.innerText = 'Fullscreen';
                maximizeIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/>';
                fixedCloseBtn.classList.add('hidden');
                isMaximized = false;
                document.getElementById('pastebin-content-wrapper').scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        }

        // ── Live Visitor Polling ──────────────────────────────────────────
        const PASTEBIN_SLUG = @json($pastebin->slug);
        const VISIT_URL = '{{ route("pastebin.visit", ":slug") }}'.replace(':slug', PASTEBIN_SLUG);
        const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ?? '';

        function buildVisitorItem(visitor) {
            const isMemb = visitor.type === 'member';
            const name = (isMemb ? '@' : '') + visitor.name;
            // Wrap member names with styled span using role_style if available, else role_color
            if (isMemb && visitor.user_style) {
                return visitor.user_style;
            }
            const color = visitor.role_color || '#6b7280';
            return `<span style="color:${color}">${name}</span>`;
        }

        function updateVisitorList(data) {
            const countEl = document.getElementById('visitor-count');
            const listEl = document.getElementById('visitor-list');
            if (!countEl || !listEl) return;

            countEl.textContent = data.count;

            if (data.visitors.length === 0) {
                listEl.innerHTML = '<span class="text-gray-700 italic">No active visitors</span>';
                return;
            }
            listEl.innerHTML = data.visitors.map(buildVisitorItem).join(', ');
        }

        async function heartbeat() {
            try {
                const res = await fetch(VISIT_URL, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': CSRF_TOKEN,
                    },
                });
                if (res.ok) {
                    const data = await res.json();
                    updateVisitorList(data);
                }
            } catch (e) {
                // Silently ignore network errors
            }
        }

        // Start polling every 30 seconds
        document.addEventListener('DOMContentLoaded', () => {
            heartbeat();
            setInterval(heartbeat, 30000);
        });
    </script>

</x-layouts.app>