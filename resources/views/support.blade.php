<x-layouts.app title="Support Our Infrastructure">
    <x-static-page title="Support" highlight="DoxMe" subtitle="Help keep the servers running anonymously.">
        <div class="border border-red-900/30 bg-black p-5 rounded-sm mb-8 text-sm text-gray-400 leading-relaxed">
            <p class="mb-4">
                Maintaining robust hosting and DDoS protection has a cost. Anonymous contributions help pay for infrastructure and development. If you value this platform, consider sending a contribution to one of the addresses below.
            </p>
            <p class="text-red-500 text-xs font-medium">We do not track donors. All contributions are final.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach([
                ['label' => 'Bitcoin (BTC)', 'key' => 'btc_address'],
                ['label' => 'Ethereum (ETH)', 'key' => 'eth_address'],
                ['label' => 'USDT (ERC-20)', 'key' => 'usdt_address'],
                ['label' => 'Solana (SOL)', 'key' => 'sol_address'],
            ] as $wallet)
                <div class="border border-red-950/40 bg-black p-4 rounded-sm">
                    <h3 class="text-white text-sm font-medium mb-3">{{ $wallet['label'] }}</h3>
                    <div class="bg-black border border-red-950/30 p-3 rounded-sm font-mono text-xs text-gray-400 break-all select-all">
                        {{ config('support.' . $wallet['key']) }}
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-10 pt-6 border-t border-red-950/40 text-center text-sm">
            <p class="text-gray-500 mb-2">Direct inquiries</p>
            <a href="mailto:{{ config('support.email') }}" class="text-gray-300 hover:text-red-500 transition-colors font-mono">
                {{ config('support.email') }}
            </a>
        </div>

        <x-internal-ads class="mt-10" />
    </x-static-page>
</x-layouts.app>
