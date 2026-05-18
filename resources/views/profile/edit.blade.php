<x-layouts.dashboard title="Settings">
    <div class="min-h-screen text-gray-300 py-8 px-4 font-sans bg-[#050505]">
        <div class=" mx-auto">
            @if(session('success'))
                <div class="bg-green-600/10 border border-green-600/30 text-green-500 p-3 rounded-sm mb-6 text-xs font-mono">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Avatar Upload -->
                <div class="bg-[#0a0a0a] border border-red-900/30 p-5 rounded-sm">
                    <h2 class="text-sm font-black text-red-500 uppercase tracking-[0.2em] mb-5 border-b border-red-900/20 pb-2">Avatar Profile</h2>
                    
                    <div class="flex items-start gap-5">
                        <div class="w-16 h-16 overflow-hidden flex-shrink-0">
                            @if($identification->avatar_path)
                                <img src="{{ Storage::url($identification->avatar_path) }}" alt="Avatar" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-xl font-bold text-gray-600">
                                    {{ strtoupper(substr($user->username, 0, 1)) }}
                                </div>
                            @endif
                        </div>
                        <div class="flex-1">
                            <input type="file" name="avatar" id="avatar" class="w-full text-[10px] text-gray-400 file:mr-3 file:py-1 file:px-3 file:border-0 file:text-[10px] file:font-black file:uppercase file:tracking-widest file:bg-red-600/10 file:text-red-500 hover:file:bg-red-600 hover:file:text-white cursor-pointer">
                            <p class="mt-2 text-[10px] text-gray-600 font-mono">
                                Max 2MB. @if($identification->role->canHaveGifAvatar()) GIF, @endif PNG, JPG, JPEG.
                            </p>
                            @error('avatar') <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <!-- Account Info -->
                <div class="bg-[#0a0a0a] border border-red-900/30 p-5 rounded-sm space-y-5">
                    <h2 class="text-sm font-black text-red-500 uppercase tracking-[0.2em] mb-5 border-b border-red-900/20 pb-2">Account Information</h2>

                    @php
                        $maxChanges = $identification->role->allowedUsernameChanges();
                        $changesUsed = $identification->username_changes;
                        $canChangeUsername = $changesUsed < $maxChanges;
                    @endphp

                    <div>
                        <div class="flex justify-between items-center mb-1">
                            <label for="username" class="text-[10px] font-black text-gray-500 uppercase tracking-widest">Username</label>
                            <span class="text-[9px] font-mono {{ $canChangeUsername ? 'text-gray-600' : 'text-red-500' }}">
                                Changes: {{ $changesUsed }} / {{ $maxChanges > 10 ? 'Unlimited' : $maxChanges }}
                            </span>
                        </div>
                        <input type="text" name="username" id="username" value="{{ old('username', $user->username) }}" 
                            class="w-full bg-[#050505] border border-red-900/20 rounded-sm px-3 py-2 text-xs text-white focus:outline-none focus:border-red-600"
                            {{ $canChangeUsername ? '' : 'readonly' }}
                        >
                        @if(!$canChangeUsername)
                            <p class="text-[10px] text-red-500 mt-1 italic">You have reached the maximum number of username changes for your role.</p>
                        @endif
                        @error('username') <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="website" class="block text-[10px] font-black text-gray-500 uppercase tracking-widest mb-1">Website</label>
                        <input type="url" name="website" id="website" value="{{ old('website', $identification->website == 'N/A' ? '' : $identification->website) }}" placeholder="https://..."
                            class="w-full bg-[#050505] border border-red-900/20 rounded-sm px-3 py-2 text-xs text-white focus:outline-none focus:border-red-600"
                        >
                        @error('website') <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="bio" class="block text-[10px] font-black text-gray-500 uppercase tracking-widest mb-1">Bio</label>
                        <textarea name="bio" id="bio" rows="3" 
                            class="w-full bg-[#050505] border border-red-900/20 rounded-sm px-3 py-2 text-xs text-white focus:outline-none focus:border-red-600 resize-none"
                        >{{ old('bio', $identification->bio == 'N/A' ? '' : $identification->bio) }}</textarea>
                        @error('bio') <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p> @enderror
                    </div>

                  
                    @if($identification->has_custom_color_unlocked)
                        <div class="pt-4 border-t border-red-900/20 mt-4">
                            <label for="custom_color" class="block text-[10px] font-black text-gray-500 uppercase tracking-widest mb-2">Custom Username Color</label>
                            <div class="flex items-center gap-3">
                                <input type="color" name="custom_color" id="custom_color" value="{{ old('custom_color', $identification->custom_color ?? '#ffffff') }}" 
                                    class="w-8 h-8 bg-black border border-red-900/30 cursor-pointer p-0 rounded-sm"
                                >
                                <span class="text-gray-500 text-[10px] uppercase font-mono bg-[#050505] px-2 py-1 border border-red-900/20">{{ $identification->custom_color ?? '#FFFFFF' }}</span>
                            </div>
                            <p class="text-[9px] text-gray-600 mt-1 italic">Personalize how your name appears on threads.</p>
                            @error('custom_color') <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p> @enderror
                        </div>
                    @endif
                </div>

                <div class="flex justify-end pt-2">
                    <button type="submit" class="bg-red-600/10 border border-red-600/30 hover:bg-red-600 hover:text-white text-red-500 px-6 py-2.5 rounded-sm font-black text-[10px] uppercase tracking-[0.2em] active:scale-95 transition-transform">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.dashboard>
