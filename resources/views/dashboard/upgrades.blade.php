<x-layouts.dashboard :title="$title" :role="$role">
    <div class="space-y-8 max-w-7xl mx-auto">
        <!-- Title & Filter Panel -->
        <div class="border border-red-900/40 bg-gradient-to-b from-red-950/10 to-[#0a0a0a] p-6 rounded-sm flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h1 class="text-xl font-black text-white tracking-tight uppercase">
                    Manage Upgrade Requests
                </h1>
                <p class="text-gray-500 text-xs font-mono mt-1">
                    System control room for Owner and Administrators to approve or reject pending role purchases.
                </p>
            </div>
            
            <!-- Filters -->
            <div class="flex flex-wrap gap-2 font-mono text-[10px]">
                <a href="{{ route('dashboard.upgrades') }}" class="px-3 py-1.5 border {{ is_null($currentStatus) ? 'border-red-600 bg-red-950/20 text-red-500 font-black' : 'border-red-900/20 text-gray-500 hover:text-white' }} uppercase tracking-widest rounded-sm">
                    All
                </a>
                <a href="{{ route('dashboard.upgrades', ['status' => 'pending']) }}" class="px-3 py-1.5 border {{ $currentStatus === 'pending' ? 'border-red-600 bg-red-950/20 text-red-500 font-black' : 'border-red-900/20 text-gray-500 hover:text-white' }} uppercase tracking-widest rounded-sm">
                    Pending
                </a>
                <a href="{{ route('dashboard.upgrades', ['status' => 'approved']) }}" class="px-3 py-1.5 border {{ $currentStatus === 'approved' ? 'border-red-600 bg-red-950/20 text-red-500 font-black' : 'border-red-900/20 text-gray-500 hover:text-white' }} uppercase tracking-widest rounded-sm">
                    Approved
                </a>
                <a href="{{ route('dashboard.upgrades', ['status' => 'rejected']) }}" class="px-3 py-1.5 border {{ $currentStatus === 'rejected' ? 'border-red-600 bg-red-950/20 text-red-500 font-black' : 'border-red-900/20 text-gray-500 hover:text-white' }} uppercase tracking-widest rounded-sm">
                    Rejected
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="p-4 bg-green-950/20 border border-green-900/30 text-green-500 text-xs font-mono font-bold rounded-sm">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="p-4 bg-red-950/20 border border-red-900/30 text-red-500 text-xs font-mono font-bold rounded-sm">
                {{ session('error') }}
            </div>
        @endif

        <!-- Upgrade Requests Table -->
        <div class="p-6 border border-red-900/20 bg-[#050505] rounded-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-left font-mono">
                    <thead>
                        <tr class="text-gray-500 text-[9px] uppercase tracking-widest border-b border-red-900/10">
                            <th class="pb-3 font-black">User / Signature</th>
                            <th class="pb-3 font-black">Current Role</th>
                            <th class="pb-3 font-black">Requested Upgrade</th>
                            <th class="pb-3 font-black">Status</th>
                            <th class="pb-3 font-black">Submitted</th>
                            <th class="pb-3 font-black text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-red-900/10">
                        @forelse($requests as $req)
                            <tr class="text-xs">
                                <td class="py-4 pr-4 align-top">
                                    <span class="text-gray-300 font-bold block">@ {{ $req->user->username ?? 'Anonymous' }}</span>
                                    <span class="text-[8px] text-gray-600 block mt-0.5">UID: {{ $req->user_id ?? 'N/A' }}</span>
                                </td>
                                <td class="py-4 pr-4 align-top">
                                    <span style="color: {{ $req->user->identification->role->color() }}" class="font-bold uppercase tracking-widest text-[9px]">
                                        {{ $req->user->identification->role->label() }}
                                    </span>
                                </td>
                                <td class="py-4 pr-4 align-top">
                                    <span style="color: {{ $req->requested_role->color() }}" class="font-black uppercase tracking-widest text-[10px]">
                                        {!! $req->requested_role->label() !!}
                                    </span>
                                </td>
                                <td class="py-4 align-top">
                                    <span class="px-1.5 py-0.5 rounded-sm text-[8px] font-black uppercase tracking-widest border 
                                        {{ $req->status === 'approved' ? 'bg-green-950/30 text-green-500 border-green-900/30' : ($req->status === 'rejected' ? 'bg-red-950/30 text-red-500 border-red-900/30' : 'bg-yellow-950/30 text-yellow-500 border-yellow-900/30') }}">
                                        {{ $req->status }}
                                    </span>
                                </td>
                                <td class="py-4 text-[10px] text-gray-500 align-top">{{ $req->created_at->diffForHumans() }}</td>
                                <td class="py-4 text-right align-top">
                                    <div class="flex justify-end gap-2">
                                        @if($req->status === 'pending')
                                            <form action="{{ route('dashboard.upgrades.approve', $req) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="text-[9px] font-black bg-green-950/20 hover:bg-green-600 hover:text-white text-green-500 px-3 py-1 border border-green-900/30 uppercase tracking-widest rounded-sm transition-colors duration-150">
                                                    Approve
                                                </button>
                                            </form>
                                            <form action="{{ route('dashboard.upgrades.reject', $req) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="text-[9px] font-black bg-red-950/20 hover:bg-red-600 hover:text-white text-red-500 px-3 py-1 border border-red-900/30 uppercase tracking-widest rounded-sm transition-colors duration-150">
                                                    Reject
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-[9px] text-gray-600 uppercase tracking-widest font-black">Logged</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-12 text-center text-xs text-gray-600 italic">
                                    No upgrade requests captured under this query state.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            {{ $requests->links() }}
        </div>
    </div>
</x-layouts.dashboard>
