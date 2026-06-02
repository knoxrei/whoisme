<x-layouts.dashboard title="Settings">
    <div class="min-h-screen text-gray-300 py-8 px-4 font-sans bg-[#050505]">
        <div class=" mx-auto">
            @if(session('success'))
                <div class="bg-green-600/10 border border-green-600/30 text-green-500 p-3 rounded-sm mb-6 text-xs font-mono">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('warning'))
                <div class="bg-yellow-600/10 border border-yellow-600/30 text-yellow-400 p-3 rounded-sm mb-6 text-xs font-mono">
                    {{ session('warning') }}
                </div>
            @endif
            @if(session('error'))
                <div class="bg-red-600/10 border border-red-600/30 text-red-500 p-3 rounded-sm mb-6 text-xs font-mono">
                    {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')

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

            @php
                $pendingEmailVerif = session('pending_email_verification');
                $isVerified = !is_null($user->email_verified_at);
                $hasEmail   = !empty($user->email);
            @endphp
            <div class="bg-[#0a0a0a] border border-red-900/30 p-5 rounded-sm mt-6">
                <h2 class="text-sm font-black text-red-500 uppercase tracking-[0.2em] mb-5 border-b border-red-900/20 pb-2">Email & Verification</h2>

                <div class="mb-5 flex items-center gap-3">
                    @if($hasEmail)
                        <div class="flex-1 bg-[#050505] border border-red-900/20 rounded-sm px-3 py-2 text-xs font-mono text-gray-300">
                            {{ $user->email }}
                        </div>
                        @if($isVerified)
                            <span class="flex items-center gap-1.5 text-[10px] font-black text-blue-400 border border-blue-500/30 bg-blue-500/10 px-3 py-2 rounded-sm whitespace-nowrap">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Verified
                            </span>
                        @else
                            <span class="text-[10px] font-black text-yellow-500 border border-yellow-500/30 bg-yellow-500/10 px-3 py-2 rounded-sm whitespace-nowrap">
                                Not Verified
                            </span>
                        @endif
                    @else
                        <p class="text-[11px] text-gray-600 italic">No email address linked to your account.</p>
                    @endif
                </div>

                @if($pendingEmailVerif && $pendingEmailVerif['user_id'] === auth()->id())
                    <div class="mb-5 p-4 bg-blue-950/20 border border-blue-700/30 rounded-sm">
                        <p class="text-[11px] text-blue-400 font-mono mb-3">
                            A 6-digit code was sent to <strong>{{ $pendingEmailVerif['email'] }}</strong>. Enter it below to verify.
                        </p>
                        <form action="{{ route('profile.verify.email') }}" method="POST" class="flex items-center gap-3">
                            @csrf
                            <input type="text" name="code" maxlength="6" placeholder="000000"
                                class="w-36 bg-[#050505] border border-blue-700/40 rounded-sm px-3 py-2 text-sm font-mono text-white text-center tracking-[0.4em] focus:outline-none focus:border-blue-500"
                                autocomplete="off">
                            <button type="submit" class="bg-blue-600/10 border border-blue-600/30 hover:bg-blue-600 hover:text-white text-blue-400 px-5 py-2 rounded-sm font-black text-[10px] uppercase tracking-widest transition-all active:scale-95">
                                Confirm Code
                            </button>
                        </form>
                        @error('code') <p class="text-red-500 text-[10px] mt-2">{{ $message }}</p> @enderror
                    </div>
                @endif

                @if(!$isVerified)
                    <form action="{{ route('profile.send.verify.email') }}" method="POST" class="flex items-end gap-3">
                        @csrf
                        @if(!$hasEmail)
                            <div class="flex-1">
                                <label for="verify_email" class="block text-[10px] font-black text-gray-500 uppercase tracking-widest mb-1">Add Email Address</label>
                                <input type="email" name="email" id="verify_email" placeholder="email@example.com"
                                    class="w-full bg-[#050505] border border-red-900/20 rounded-sm px-3 py-2 text-xs text-white focus:outline-none focus:border-red-600">
                                @error('email') <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p> @enderror
                            </div>
                        @else
                            <input type="hidden" name="email" value="{{ $user->email }}">
                            <p class="text-[10px] text-gray-600 italic flex-1">A code will be sent to <span class="text-gray-400 font-mono">{{ $user->email }}</span></p>
                        @endif
                        <button type="submit" class="flex items-center gap-2 bg-blue-600/10 border border-blue-600/30 hover:bg-blue-600 hover:text-white text-blue-400 px-5 py-2.5 rounded-sm font-black text-[10px] uppercase tracking-widest transition-all active:scale-95 whitespace-nowrap">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            {{ $hasEmail ? 'Send Verification Email' : 'Add & Verify Email' }}
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</x-layouts.dashboard>
