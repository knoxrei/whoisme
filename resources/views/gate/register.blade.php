<x-layouts.app :title="$title">
    <div class="min-h-screen flex flex-col items-center justify-center bg-[#050505] px-4 py-12">

        <div class="w-full max-w-md  rounded-lg p-10 shadow-xl">
            <div class="mb-8 text-center">
                <h1 class="text-3xl font-semibold text-white tracking-tight">Register</h1>
                <p class="text-gray-500 mt-2 text-sm">Create your account to start sharing.</p>
            </div>

            <form action="{{ route('register.store') }}" method="POST" class="space-y-5">
                @csrf
                <div class="space-y-1.5">
                    <label for="username"
                        class="text-xs font-medium text-gray-400 uppercase tracking-wider ml-1">Username</label>
                    <input type="text" name="username" id="username" required
                        class="w-full bg-black border border-gray-800 rounded-md px-4 py-3 text-white focus:outline-none focus:ring-1 focus:ring-red-600 focus:border-red-600 transition-all placeholder:text-gray-600"
                        placeholder="Choose a username">
                        <span class="text-[10px] text-red-500">{{ $errors->first('username') }}</span>
                </div>

                <div class="space-y-1.5">
                    <label for="email" class="text-xs font-medium text-gray-400 uppercase tracking-wider ml-1">Email
                        Address</label>
                    <input type="email" name="email" id="email" required
                        class="w-full bg-black border border-gray-800 rounded-md px-4 py-3 text-white focus:outline-none focus:ring-1 focus:ring-red-600 focus:border-red-600 transition-all placeholder:text-gray-600"
                        placeholder="email@example.com">
                    <span class="text-[10px] text-red-500">{{ $errors->first('email') }}</span>
                </div>

                <div class="space-y-1.5">
                    <label for="password"
                        class="text-xs font-medium text-gray-400 uppercase tracking-wider ml-1">Password</label>
                    <input type="password" name="password" id="password" required minlength="8"
                        class="w-full bg-black border border-gray-800 rounded-md px-4 py-3 text-white focus:outline-none focus:ring-1 focus:ring-red-600 focus:border-red-600 transition-all placeholder:text-gray-600"
                        placeholder="At least 8 characters">
                    <span class="text-[10px] text-red-500">{{ $errors->first('password') }}</span>
                </div>

                <div class="pt-2">
                    <button type="submit"
                        class="w-full bg-red-600 hover:bg-red-700 text-white font-medium py-3 rounded-md transition-colors shadow-lg shadow-red-900/10">
                        Create Account
                    </button>
                </div>

                <div class="text-center pt-2">
                    <span class="text-gray-500 text-sm">Already have an account?</span>
                    <a href="{{ route('login') }}"
                        class="text-red-500 hover:text-red-400 text-sm font-medium ml-1">Login here</a>
                </div>
            </form>
        </div>


    </div>
</x-layouts.app>