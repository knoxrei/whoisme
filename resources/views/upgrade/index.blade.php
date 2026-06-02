<x-layouts.app :title="$title">
    <div class="pw-full max-w-4xl mx-auto">
   <div class="mb-12 border-b border-red-900/30 pb-6  py-16 text-center md:text-left">
                <h1 class="text-4xl md:text-6xl font-black text-white tracking-tighter mb-4" style="font-family: 'Outfit', sans-serif;">
                                   Elevate Your <span class="text-red-600">Experience</span>

                </h1>
                <p class="text-gray-500 font-mono text-sm uppercase tracking-widest"> Choose a premium tier to unlock exclusive features, advanced privacy controls, and a distinguished
                identity within the community</p>
            </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @foreach($roles as $role)
                <div class="relative group">
                    <div class="relative flex flex-col h-full  border border-white/5  p-8 overflow-hidden">
                        <div class="absolute top-0 right-0 p-4 opacity-5 transform translate-x-4 -translate-y-4">
                            <x-layouts.logo class="w-32 h-32" />
                        </div>

                        <div class="mb-8">
                            <h3 class="text-xs font-black uppercase tracking-[0.3em] mb-2"
                                style="color: {{ $role->color() }}">
                                {{ $role->label() }}
                            </h3>
                            <div class="flex items-baseline gap-1">
                                <span class="text-4xl font-black text-white">${{ $role->price() }}</span>
                                <span class="text-gray-500 font-bold uppercase tracking-widest text-[10px]">/ month</span>
                            </div>
                        </div>

                        <ul class="flex-1 space-y-4 mb-10">
                            <li class="flex items-center gap-3 text-sm">
                                <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                                <span class="text-gray-300 font-medium">{!! $role->preview() !!} Preview</span>
                            </li>
                            
                            <li class="flex items-center gap-3 text-sm">
                                <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                                <span class="text-gray-300 font-medium">Color: <span
                                        style="color: {{ $role->color() }}">{{ $role->label() }} Color</span></span>
                            </li>
                            <li class="flex items-center gap-3 text-sm">
                                <svg class="w-5 h-5 {{ $role->canHaveGifAvatar() ? 'text-green-500' : 'text-gray-700' }}"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="{{ $role->canHaveGifAvatar() ? 'M5 13l4 4L19 7' : 'M6 18L18 6M6 6l12 12' }}" />
                                </svg>
                                <span
                                    class="{{ $role->canHaveGifAvatar() ? 'text-gray-300' : 'text-gray-600' }} font-medium">.GIF
                                    profile picture</span>
                            </li>
                           
                            <li class="flex items-center gap-3 text-sm">
                                <svg class="w-5 h-5 {{ $role->canPrivatePastes() ? 'text-green-500' : 'text-gray-700' }}"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="{{ $role->canPrivatePastes() ? 'M5 13l4 4L19 7' : 'M6 18L18 6M6 6l12 12' }}" />
                                </svg>
                                <span
                                    class="{{ $role->canPrivatePastes() ? 'text-gray-300' : 'text-gray-600' }} font-medium">Private
                                    your own pastes</span>
                            </li>
                             <li class="flex items-center gap-3 text-sm">
                                <svg class="w-5 h-5 {{ $role->canPriorityOrderUser() ? 'text-green-500' : 'text-gray-700' }}"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="{{ $role->canPriorityOrderUser() ? 'M5 13l4 4L19 7' : 'M6 18L18 6M6 6l12 12' }}" />
                                </svg>
                                <span
                                    class="{{ $role->canPriorityOrderUser() ? 'text-gray-300' : 'text-gray-600' }} font-medium">Priority
                                    order in user list</span>
                            </li>
                            <li class="flex items-center gap-3 text-sm">
                                <svg class="w-5 h-5 {{ $role->canPasswordProtect() ? 'text-green-500' : 'text-gray-700' }}"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="{{ $role->canPasswordProtect() ? 'M5 13l4 4L19 7' : 'M6 18L18 6M6 6l12 12' }}" />
                                </svg>
                                <span
                                    class="{{ $role->canPasswordProtect() ? 'text-gray-300' : 'text-gray-600' }} font-medium">Password
                                    protected pastes</span>
                            </li>
                            <li class="flex items-center gap-3 text-sm">
                                <div class="w-5 h-5 flex items-center justify-center">
                                    <span
                                        class="text-xs font-black text-red-600">{{ $role->allowedUsernameChanges() }}</span>
                                </div>
                                <span class="text-gray-300 font-medium">Username changes</span>
                            </li>
                        </ul>
                        @auth
                        <form action="{{ route('upgrade.purchase', $role->value) }}" method="POST">
                            @csrf
                            <button type="submit"
                                class="w-full py-4 bg-[#222] text-white font-bold text-xs font-black uppercase tracking-[0.2em] rounded">
                                Purchase {{ $role->label() }}
                            </button>
                        </form>
                        @endauth
                        @guest
                         <div class="w-full  bg-[#222] text-white font-bold text-xs font-black uppercase tracking-[0.2em] rounded">
                          
                            <a href="{{ route('login') }}"
                                class="w-full block py-4 text-center text-white font-bold text-xs font-black uppercase tracking-[0.2em] rounded">
                                Purchase {{ $role->label() }}
                            </a>
                        </div>
                        @endguest
                    </div>
                </div>
            @endforeach
        </div>

    </div>
</x-layouts.app>