<x-layouts.app :title="$title">
    <div class="min-h-screen flex flex-col items-center justify-center px-4">
        <div class="w-full max-w-md mx-auto">
            <div class="mb-8 text-center">
                <h1 class="text-3xl font-semibold text-white tracking-tight uppercase">Reset Password</h1>
                <p class="text-gray-500 mt-2 text-sm">Verify clearance OTP key and input your new password credentials.</p>
                @if(isset($email))
                    <div class="mt-4 text-xs font-mono text-gray-500 bg-black/60 p-3.5 border border-red-950/40 rounded-sm">
                        Target Node: <span class="text-red-500 font-bold">{{ $email }}</span>
                    </div>
                @endif
            </div>

            @if(session('success'))
                <div class="mb-6 bg-green-950/20 border border-green-900/30 text-green-500 text-center py-3 px-4 font-mono text-xs rounded-sm">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 bg-red-950/20 border border-red-900/30 text-red-500 text-center py-3 px-4 font-mono text-xs rounded-sm">
                    {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('password.update') }}" method="POST" class="space-y-5">
                @csrf
                
                <div class="space-y-2">
                    <label for="code" class="text-xs font-medium text-gray-400 uppercase tracking-widest ml-1 font-mono">
                        6-Digit Clearance OTP Code
                    </label>
                    <input type="text" name="code" id="code" required maxlength="6" autofocus
                        class="w-full bg-black border border-red-950/40 rounded-sm px-4 py-3 text-white text-center font-mono font-black text-2xl tracking-[0.4em] focus:outline-none focus:ring-1 focus:ring-red-600 focus:border-red-600 transition-all placeholder:text-gray-800"
                        placeholder="000000">
                    @error('code')
                        <p class="text-red-500 text-xs mt-1 font-mono">{{ $message }}</p>
                    @enderror
                </div>

                <div class="space-y-1.5">
                    <label for="password" class="text-xs font-medium text-gray-400 uppercase tracking-wider ml-1 font-mono">New Password</label>
                    <input type="password" name="password" id="password" required
                        class="w-full bg-black border border-gray-800 rounded-md px-4 py-3 text-white focus:outline-none focus:ring-1 focus:ring-red-600 focus:border-red-600 transition-all placeholder:text-gray-600"
                        placeholder="••••••••">
                    @error('password')
                        <p class="text-red-500 text-xs mt-1 font-mono">{{ $message }}</p>
                    @enderror
                </div>

                <div class="space-y-1.5">
                    <label for="password_confirmation" class="text-xs font-medium text-gray-400 uppercase tracking-wider ml-1 font-mono">Confirm New Password</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" required
                        class="w-full bg-black border border-gray-800 rounded-md px-4 py-3 text-white focus:outline-none focus:ring-1 focus:ring-red-600 focus:border-red-600 transition-all placeholder:text-gray-600"
                        placeholder="••••••••">
                </div>

                <div class="pt-2">
                    <button type="submit"
                        class="w-full bg-red-700 hover:bg-red-800 text-white font-black text-xs uppercase tracking-widest py-3 rounded-sm transition-colors shadow-lg shadow-red-900/10">
                        Override Credentials
                    </button>
                </div>
            </form>

            <div class="mt-8 pt-6 border-t border-red-950/20 text-center">
                <a href="{{ route('login') }}" class="text-xs font-mono text-gray-600 hover:text-white transition-colors">
                    Back to login screen
                </a>
            </div>
        </div>
    </div>
</x-layouts.app>
