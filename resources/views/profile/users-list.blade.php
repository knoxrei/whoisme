<x-layouts.app :title="$title">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 font-mono text-gray-300">
        
        <!-- Premium UI/UX Header Section -->
        <div class="border-b border-red-950/40 pb-6 mb-12 flex flex-col md:flex-row md:items-end justify-between gap-4">
            <div>
                <h1 class="text-xl md:text-2xl font-black text-white uppercase tracking-wider" style="font-family: 'Outfit', sans-serif;">
                    Our <span class="text-red-500">Users</span>
                </h1>
                <p class="text-[10px] text-gray-500 mt-1.5 uppercase tracking-wider">
                    Secure index of captured aliases and system entities ordered by reputation score.
                </p>
            </div>
            
            <!-- Lightweight Realtime Stats Header Bar -->
            <div class="flex flex-wrap items-center gap-x-4 gap-y-2 text-[9px] font-bold text-gray-600 border-l border-red-950/30 pl-0 md:pl-4 uppercase tracking-widest">
                <div>Staff: <span class="text-red-500 font-black">{{ $staff->total() }}</span></div>
                <div class="hidden sm:block text-red-950/30">•</div>
                <div>Premium: <span class="text-yellow-500 font-black">{{ $premium->total() }}</span></div>
                <div class="hidden sm:block text-red-950/30">•</div>
                <div>Promo Nodes: <span class="text-blue-400 font-black">{{ $advertisers->total() }}</span></div>
                <div class="hidden sm:block text-red-950/30">•</div>
                <div>Members: <span class="text-gray-400 font-black">{{ $members->total() }}</span></div>
            </div>
        </div>

        <div class="space-y-16">
            
            <!-- 1. STAFF & MODERATORS TABLE -->
            <section class="space-y-4">
                <div class="flex items-center justify-between border-b border-red-950/20 pb-2">
                    <div class="text-[11px] font-black text-red-500 uppercase tracking-[0.25em] flex items-center gap-2">
                        <span class="text-red-900/50">[01]</span> SYSTEM STAFF & MODERATORS
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

            <!-- 2. PREMIUM USERS TABLE -->
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

            <!-- 3. ADVERTISERS TABLE -->
            <section class="space-y-4">
                <div class="flex items-center justify-between border-b border-red-950/20 pb-2">
                    <div class="text-[11px] font-black text-blue-400 uppercase tracking-[0.25em] flex items-center gap-2">
                        <span class="text-blue-900/50">[03]</span> PROMOTED ADVERTISERS
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

            <!-- 4. MEMBERS TABLE -->
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
