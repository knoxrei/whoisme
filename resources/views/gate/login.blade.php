<x-layouts.app :title="$title">
    <div class="min-h-screen flex flex-col items-center justify-center  px-4">

        <div class="w-full max-w-md  rounded-lg p-10 shadow-xl">
            <div class="mb-8 text-center">
                <h1 class="text-3xl font-semibold text-white tracking-tight">Login</h1>
                <p class="text-gray-500 mt-2 text-sm">Please enter your credentials to continue.</p>
                @if(!empty($legacyPlatformName))
                    <div class="mt-4 p-3 border border-red-900/30 bg-red-950/10 rounded-sm text-left">
                        <p class="text-[10px] text-gray-400 font-mono leading-relaxed">
                            Already registered on <strong class="text-red-500">{{ $legacyPlatformName }}</strong>?
                            Use the same username and password to sign in here. 
                        </p>
                    </div>
                @endif
            </div>
            @if(session('success'))
                <div
                    class="sticky top-0 z-50 bg-green-900/15 text-white text-center py-3 px-4 shadow-md font-medium text-sm">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="sticky top-0 z-50 bg-red-600/15 text-white text-center py-3 px-4 shadow-md font-medium text-sm">
                    {{ session('error') }}
                </div>
            @endif
            <form action="{{ route('login.store') }}" method="POST" class="space-y-5">
                @csrf
                <div class="space-y-1.5">
                    <label for="username"
                        class="text-xs font-medium text-gray-400 uppercase tracking-wider ml-1">Username or Email</label>
                    <input type="text" name="username" id="username" required
                        class="w-full bg-black border border-gray-800 rounded-md px-4 py-3 text-white focus:outline-none focus:ring-1 focus:ring-red-600 focus:border-red-600 transition-all placeholder:text-gray-600"
                        placeholder="Username or email address">
                </div>

                <div class="space-y-1.5">
                    <label for="password"
                        class="text-xs font-medium text-gray-400 uppercase tracking-wider ml-1">Password</label>
                    <input type="password" name="password" id="password" required
                        class="w-full bg-black border border-gray-800 rounded-md px-4 py-3 text-white focus:outline-none focus:ring-1 focus:ring-red-600 focus:border-red-600 transition-all placeholder:text-gray-600"
                        placeholder="••••••••">
                </div>

                <div class="flex items-center gap-2">
                    <input type="checkbox" name="remember" id="remember" value="1"
                        class="rounded border-gray-700 bg-black text-red-600 focus:ring-red-600">
                    <label for="remember" class="text-xs text-gray-500">Remember me</label>
                </div>

                <div class="pt-2">
                    <button type="submit"
                        class="w-full bg-red-600 hover:bg-red-700 text-white font-medium py-3 rounded-md transition-colors shadow-lg shadow-red-900/10">
                        Sign In
                    </button>
                </div>

                <div class="flex items-center justify-between pt-2">
                    <div>
                        <span class="text-gray-500 text-sm">New member?</span>
                        <a href="{{ route('register.index') }}"
                            class="text-red-500 hover:text-red-400 text-sm font-medium ml-1">Register</a>
                    </div>
                    <div>
                        <a href="{{ route('password.request') }}"
                            class="text-gray-400 hover:text-red-400 text-sm font-medium">Forgot Password?</a>
                    </div>
                </div>
            </form>
        </div>

    </div>
</x-layouts.app>