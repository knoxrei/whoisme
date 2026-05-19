<x-layouts.app :title="$title">
    <div class="min-h-screen flex flex-col items-center justify-center px-4">
        <div class="w-full max-w-md mx-auto ">
            <div class="mb-8 text-center">
                <h1 class="text-3xl font-semibold text-white tracking-tight uppercase">Verify Email</h1>
                <p class="text-gray-500 mt-2 text-sm">Please verify your email address to establish connection.</p>
                <div class="mt-4 text-xs font-mono text-gray-500 bg-black/60 p-3.5 border border-red-950/40 rounded-sm">
                    Target: <span class="text-red-500 font-bold">{{ $pending['email'] }}</span>
                </div>
            </div>

            <!-- Verification System Announcement Banner -->
            <div class="mb-6 bg-red-950/15 border border-red-900/20 text-gray-400 p-4 rounded-sm font-mono text-[10px] leading-relaxed text-left">
                <span class="text-red-500 font-black uppercase tracking-wider block mb-1">SYSTEM NOTICE:</span>
                Please allow up to <span class="text-white font-bold">1 minute</span> for the encryption key to route to your terminal. If the transmission is not received within 1 minute, please verify your <span class="text-white font-bold">Spam / Junk Inbox folder</span>.
            </div>

            @if(session('success'))
                <div class="mb-6 bg-green-950/20 border border-green-900/30 text-green-500 text-center py-3 px-4 font-mono text-xs rounded-sm">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('warning'))
                <div class="mb-6 bg-yellow-950/20 border border-yellow-900/30 text-yellow-500 text-center py-3 px-4 font-mono text-xs rounded-sm">
                    {{ session('warning') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 bg-red-950/20 border border-red-900/30 text-red-500 text-center py-3 px-4 font-mono text-xs rounded-sm">
                    {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('verify.registration.post') }}" method="POST" class="space-y-6">
                @csrf
                <div class="space-y-2">
                    <label for="code" class="text-xs font-medium text-gray-400 uppercase tracking-widest ml-1 font-mono">
                        6-Digit Security OTP Key
                    </label>
                    <input type="text" name="code" id="code" required maxlength="6" autofocus
                        class="w-full bg-black border border-red-950/40 rounded-sm px-4 py-3 text-white text-center font-mono font-black text-2xl tracking-[0.4em] focus:outline-none focus:ring-1 focus:ring-red-600 focus:border-red-600 transition-all placeholder:text-gray-800"
                        placeholder="000000">
                    @error('code')
                        <p class="text-red-500 text-xs mt-1 font-mono">{{ $message }}</p>
                    @enderror
                </div>

                <div class="pt-2">
                    <button type="submit"
                        class="w-full bg-red-700 hover:bg-red-800 text-white font-black text-xs uppercase tracking-widest py-3 rounded-sm transition-colors shadow-lg shadow-red-900/10">
                        Authorize Node
                    </button>
                </div>
            </form>

            <div class="mt-8 pt-6 border-t border-red-950/20 flex flex-col items-center justify-between gap-4">
                <form action="{{ route('verify.registration.resend') }}" method="POST" class="w-full">
                    @csrf
                    <button type="submit" class="w-full text-center text-xs font-mono text-gray-400 hover:text-red-500 transition-colors">
                        Did not receive key? Resend Code
                    </button>
                </form>

                <a href="{{ route('login') }}" class="text-xs font-mono text-gray-600 hover:text-white transition-colors">
                    Back to login screen
                </a>
            </div>
        </div>
    </div>
</x-layouts.app>
