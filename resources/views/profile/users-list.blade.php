<x-layouts.app :title="$title">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 font-mono text-gray-300">
        
        <div class="border-b border-red-950/40 pb-6 mb-8 flex flex-col md:flex-row md:items-end justify-between gap-4">
         

            <form method="GET" action="{{ route('profile.users-list') }}" class="flex flex-wrap items-center gap-2 font-mono text-[10px] w-full md:w-auto">
                <input type="text" name="q" value="{{ $search ?? '' }}" placeholder="Search username or email..."
                    class="flex-1 md:w-56 bg-[#0a0a0a] border border-red-950/40 focus:border-red-600 rounded-sm px-3 py-2 text-white text-xs focus:outline-none">
                <button type="submit" class="px-4 py-2 bg-red-950/30 border border-red-900/40 hover:border-red-600 text-red-500 hover:text-white uppercase tracking-widest rounded-sm font-black transition-colors">
                    Search
                </button>
                @if(!empty($search))
                    <a href="{{ route('profile.users-list') }}" class="px-3 py-2 border border-red-900/40 text-gray-500 hover:text-white uppercase tracking-widest rounded-sm">
                        Reset
                    </a>
                @endif
            </form>
        </div>

        @if(!empty($search))
            <div class="mb-8 p-3 border border-red-900/30 bg-red-950/10 rounded-sm text-[10px] font-mono text-gray-400">
                Results for <strong class="text-red-500">"{{ $search }}"</strong>
                — <span class="text-white font-black">{{ number_format($totalResults) }}</span> match(es) across all categories.
            </div>
        @endif

        <div class="flex flex-wrap items-center gap-x-4 gap-y-2 text-[9px] font-bold text-gray-600 border-b border-red-950/20 pb-6 mb-8 uppercase tracking-widest">
            <div>Staff: <span class="text-red-500 font-black">{{ $staff->total() }}</span></div>
            <div class="hidden sm:block text-red-950/30">•</div>
            <div>Premium: <span class="text-yellow-500 font-black">{{ $premium->total() }}</span></div>
            <div class="hidden sm:block text-red-950/30">•</div>
            <div>Promo Nodes: <span class="text-blue-400 font-black">{{ $advertisers->total() }}</span></div>
            <div class="hidden sm:block text-red-950/30">•</div>
            <div>Members: <span class="text-gray-400 font-black">{{ $members->total() }}</span></div>
        </div>

        <div class="space-y-16">
            
            <section class="space-y-4">
                <div class="flex items-center justify-between border-b border-red-950/20 pb-2">
                    <div class="text-[11px] font-black text-red-500 uppercase tracking-[0.25em] flex items-center gap-2">
                        <span class="text-red-900/50">[01]</span> STAFF & MODERATORS
                    </div>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="border-b border-red-950/20 text-gray-600 font-bold uppercase tracking-wider text-[9px]">
                                <th class="py-3 w-16 text-center">Avatar</th>
                                <th class="py-3 pl-2">Alias / Identity</th>
                                <th class="py-3 text-center">Reputation</th>
                                <th class="py-3">Clearance role</th>
                                <th class="py-3 text-right">Database Enrolled</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-red-950/5">
                            @forelse($staff as $u)
                                <tr class="hover:bg-red-950/5 transition-all duration-150 group">
                                    <td class="py-3 text-center align-middle">
                                        <div class="inline-block relative">
                                            <img src="{{ $u->avatar_url }}" class="w-7 h-7 object-cover " alt="">
                                        </div>
                                    </td>
                                    <td class="py-3 pl-2 align-middle font-bold">
                                        <a href="{{ route('profile.show', $u->username) }}" class="text-white hover:text-red-500 transition-colors block leading-tight">
                                            {!! $u->display_style !!}
                                        </a>
                                        <span class="text-[8px] text-gray-600 font-mono tracking-tighter">NODE://{{ $u->id }}</span>
                                    </td>
                                    <td class="py-3 text-center align-middle font-mono font-black text-red-500 text-xs">
                                        {{ $u->identification->reputation ?? 0 }}
                                    </td>
                                    <td class="py-3 align-middle">
                                        <span style="color: {{ $u->identification->role->color() }}; border-color: {{ $u->identification->role->color() }}30" 
                                              class="px-2 py-0.5 border bg-black/40 rounded-sm text-[8px] font-black uppercase tracking-widest">
                                            {{ $u->identification->role->label() }}
                                        </span>
                                    </td>
                                    <td class="py-3 text-right align-middle text-gray-500 text-[10px]">
                                        {{ $u->created_at->format('d M Y') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-8 text-center text-gray-600 italic">No staff operators indexed in active memory.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($staff->hasPages())
                    <div class="mt-4">{{ $staff->links() }}</div>
                @endif
            </section>

            <section class="space-y-4">
                <div class="flex items-center justify-between border-b border-red-950/20 pb-2">
                    <div class="text-[11px] font-black text-yellow-500 uppercase tracking-[0.25em] flex items-center gap-2">
                        <span class="text-yellow-900/50">[02]</span> PREMIUM ENTITIES
                    </div>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="border-b border-red-950/20 text-gray-600 font-bold uppercase tracking-wider text-[9px]">
                                <th class="py-3 w-16 text-center">Avatar</th>
                                <th class="py-3 pl-2">Alias / Identity</th>
                                <th class="py-3 text-center">Reputation</th>
                                <th class="py-3">Clearance role</th>
                                <th class="py-3 text-right">Database Enrolled</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-red-950/5">
                            @forelse($premium as $u)
                                <tr class="hover:bg-yellow-950/5 transition-all duration-150 group">
                                    <td class="py-3 text-center align-middle">
                                        <img src="{{ $u->avatar_url }}" class="w-7 h-7 object-cover " alt="">
                                    </td>
                                    <td class="py-3 pl-2 align-middle font-bold">
                                        <a href="{{ route('profile.show', $u->username) }}" class="text-white hover:text-yellow-500 transition-colors block leading-tight">
                                            {!! $u->display_style !!}
                                        </a>
                                        <span class="text-[8px] text-gray-600 font-mono tracking-tighter">NODE://{{ $u->id }}</span>
                                    </td>
                                    <td class="py-3 text-center align-middle font-mono font-black text-yellow-500 text-xs">
                                        {{ $u->identification->reputation ?? 0 }}
                                    </td>
                                    <td class="py-3 align-middle">
                                        <span style="color: {{ $u->identification->role->color() }}; border-color: {{ $u->identification->role->color() }}30" 
                                              class="px-2 py-0.5 border bg-black/40 rounded-sm text-[8px] font-black uppercase tracking-widest">
                                            {{ $u->identification->role->label() }}
                                        </span>
                                    </td>
                                    <td class="py-3 text-right align-middle text-gray-500 text-[10px]">
                                        {{ $u->created_at->format('d M Y') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-8 text-center text-gray-600 italic">No premium entities indexed in active memory.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($premium->hasPages())
                    <div class="mt-4">{{ $premium->links() }}</div>
                @endif
            </section>

            <section class="space-y-4">
                <div class="flex items-center justify-between border-b border-red-950/20 pb-2">
                    <div class="text-[11px] font-black text-blue-400 uppercase tracking-[0.25em] flex items-center gap-2">
                        <span class="text-blue-900/50">[03]</span>  ADVERTISERS
                    </div>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="border-b border-red-950/20 text-gray-600 font-bold uppercase tracking-wider text-[9px]">
                                <th class="py-3 w-16 text-center">Avatar</th>
                                <th class="py-3 pl-2">Alias / Identity</th>
                                <th class="py-3 text-center">Reputation</th>
                                <th class="py-3">Clearance role</th>
                                <th class="py-3 text-right">Database Enrolled</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-red-950/5">
                            @forelse($advertisers as $u)
                                <tr class="hover:bg-blue-950/5 transition-all duration-150 group">
                                    <td class="py-3 text-center align-middle">
                                        <img src="{{ $u->avatar_url }}" class="w-7 h-7 " alt="">
                                    </td>
                                    <td class="py-3 pl-2 align-middle font-bold">
                                        <a href="{{ route('profile.show', $u->username) }}" class="text-white hover:text-blue-400 transition-colors block leading-tight">
                                            {!! $u->display_style !!}
                                        </a>
                                        <span class="text-[8px] text-gray-600 font-mono tracking-tighter">NODE://{{ $u->id }}</span>
                                    </td>
                                    <td class="py-3 text-center align-middle font-mono font-black text-blue-400 text-xs">
                                        {{ $u->identification->reputation ?? 0 }}
                                    </td>
                                    <td class="py-3 align-middle">
                                        <span class="px-2 py-0.5 border border-blue-900/30 bg-black/40 rounded-sm text-[8px] font-black uppercase tracking-widest text-blue-400">
                                            ADVERTISER
                                        </span>
                                    </td>
                                    <td class="py-3 text-right align-middle text-gray-500 text-[10px]">
                                        {{ $u->created_at->format('d M Y') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-8 text-center text-gray-600 italic">No advertisers indexed in active memory.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($advertisers->hasPages())
                    <div class="mt-4">{{ $advertisers->links() }}</div>
                @endif
            </section>

            <section class="space-y-4">
                <div class="flex items-center justify-between border-b border-red-950/20 pb-2">
                    <div class="text-[11px] font-black text-gray-400 uppercase tracking-[0.25em] flex items-center gap-2">
                        <span class="text-gray-600">[04]</span> GENERAL MEMBERS
                    </div>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="border-b border-red-950/20 text-gray-600 font-bold uppercase tracking-wider text-[9px]">
                                <th class="py-3 w-16 text-center">Avatar</th>
                                <th class="py-3 pl-2">Alias / Identity</th>
                                <th class="py-3 text-center">Reputation</th>
                                <th class="py-3">Clearance role</th>
                                <th class="py-3 text-right">Database Enrolled</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-red-950/5">
                            @forelse($members as $u)
                                <tr class="hover:bg-white/[0.02] transition-all duration-150 group">
                                    <td class="py-3 text-center align-middle">
                                        <img src="{{ $u->avatar_url }}" class="w-7 h-7 object-cover " alt="">
                                    </td>
                                    <td class="py-3 pl-2 align-middle font-bold">
                                        <a href="{{ route('profile.show', $u->username) }}" class="text-gray-300 hover:text-white transition-colors block leading-tight">
                                            {{ $u->username }}
                                        </a>
                                        <span class="text-[8px] text-gray-600 font-mono tracking-tighter">NODE://{{ $u->id }}</span>
                                    </td>
                                    <td class="py-3 text-center align-middle font-mono font-black text-gray-400 text-xs">
                                        {{ $u->identification->reputation ?? 0 }}
                                    </td>
                                    <td class="py-3 align-middle">
                                        <span class="px-2 py-0.5 border border-white/5 bg-black/40 rounded-sm text-[8px] font-bold uppercase tracking-widest text-gray-500">
                                            MEMBER
                                        </span>
                                    </td>
                                    <td class="py-3 text-right align-middle text-gray-500 text-[10px]">
                                        {{ $u->created_at->format('d M Y') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-8 text-center text-gray-600 italic">No active standard nodes indexed.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($members->hasPages())
                    <div class="mt-4">{{ $members->links() }}</div>
                @endif
            </section>

        </div>

    </div>
</x-layouts.app>
