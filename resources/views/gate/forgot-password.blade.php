<x-layouts.app :title="$title">
    <div class="min-h-screen flex flex-col items-center justify-center px-4">
        <div class="w-full max-w-md rounded-lg p-10 shadow-xl">
            <div class="mb-8 text-center">
                <h1 class="text-3xl font-semibold text-white tracking-tight">Forgot Password</h1>
                <p class="text-gray-500 mt-2 text-sm">Enter your username or email address to request a reset code.</p>
            </div>

            @if(session('success'))
                <div class="sticky top-0 z-50 bg-green-900/15 text-white text-center py-3 px-4 shadow-md font-medium text-sm mb-5">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="sticky top-0 z-50 bg-red-600/15 text-white text-center py-3 px-4 shadow-md font-medium text-sm mb-5">
                    {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('password.email') }}" method="POST" class="space-y-5">
                @csrf
                <div class="space-y-1.5">
                    <label for="identity" class="text-xs font-medium text-gray-400 uppercase tracking-wider ml-1">Username or Email</label>
                    <input type="text" name="identity" id="identity" required
                        class="w-full bg-black border border-gray-800 rounded-md px-4 py-3 text-white focus:outline-none focus:ring-1 focus:ring-red-600 focus:border-red-600 transition-all placeholder:text-gray-600 font-mono text-sm"
                        placeholder="username or email@doxme.com">
                </div>

                <div class="pt-2">
                    <button type="submit"
                        class="w-full bg-red-600 hover:bg-red-700 text-white font-medium py-3 rounded-md transition-colors shadow-lg shadow-red-900/10 text-sm uppercase tracking-widest font-bold">
                        Request Reset Code
                    </button>
                </div>

                <div class="text-center pt-2">
                    <a href="{{ route('login') }}" class="text-gray-400 hover:text-red-500 text-sm font-medium transition-colors">
                        ← Back to Sign In
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
