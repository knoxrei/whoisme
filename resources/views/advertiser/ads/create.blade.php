<x-layouts.dashboard title="Submit Advertisement">
    <div class="max-w-3xl mx-auto">
        <div class="mb-8 border-b border-red-900/30 pb-6">
            <h1 class="text-3xl font-black text-white tracking-tighter uppercase font-mono flex items-center gap-3">
                <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"></path></svg>
                Create <span class="text-red-600">Ad Campaign</span>
            </h1>
            <p class="text-xs text-gray-500 font-mono tracking-widest uppercase mt-2">Submit your ad for owner review.</p>
        </div>

        @if ($errors->any())
            <div class="mb-6 p-4 bg-red-950/20 border border-red-900/50 rounded-sm">
                <ul class="list-disc list-inside text-xs text-red-500 font-mono">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-[#0a0a0a] border border-white/5 rounded-sm p-6 shadow-2xl shadow-black">
            <form action="{{ route('advertiser.ads.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <div>
                    <label for="title" class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2">Campaign Title</label>
                    <input type="text" id="title" name="title" required value="{{ old('title') }}" placeholder="e.g. Summer Privacy Sale" 
                        class="w-full bg-[#111] border border-white/10 rounded-sm px-4 py-3 text-sm text-white focus:outline-none focus:border-red-500 focus:ring-1 focus:ring-red-500 transition-all font-mono">
                    <p class="text-[10px] text-gray-600 mt-1 font-mono">A descriptive title for your internal dashboard tracking.</p>
                </div>

                <div>
                    <label for="contact" class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2">Contact Info (Telegram / Tox / Email)</label>
                    <input type="text" id="contact" name="contact" required value="{{ old('contact') }}" placeholder="e.g. @username or email@proton.me" 
                        class="w-full bg-[#111] border border-white/10 rounded-sm px-4 py-3 text-sm text-white focus:outline-none focus:border-red-500 focus:ring-1 focus:ring-red-500 font-mono">
                    <p class="text-[10px] text-gray-600 mt-1 font-mono">So the admin can contact you if there's an issue with your ad.</p>
                </div>

                <div>
                    <label for="target_url" class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2">Target URL</label>
                    <input type="url" id="target_url" name="target_url" required value="{{ old('target_url') }}" placeholder="https://your-secure-site.com" 
                        class="w-full bg-[#111] border border-white/10 rounded-sm px-4 py-3 text-sm text-white focus:outline-none focus:border-red-500 focus:ring-1 focus:ring-red-500 transition-all font-mono">
                    <p class="text-[10px] text-gray-600 mt-1 font-mono">Where users will be redirected upon clicking your ad.</p>
                </div>

                <div>
                    <label class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2">Ad Creative (Banner)</label>
                    <div class="border-2 border-dashed border-white/10 rounded-sm p-8 text-center bg-[#111] hover:bg-[#151515] hover:border-red-500/50 transition-all relative group cursor-pointer" onclick="document.getElementById('image').click()">
                        <input type="file" id="image" name="image" accept="image/*" class="hidden" required onchange="previewImage(event)">
                        
                        <div id="upload-placeholder" class="flex flex-col items-center">
                            <svg class="w-10 h-10 text-gray-600 group-hover:text-red-500 transition-colors mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                            <span class="text-xs font-bold text-gray-400 font-mono">Click to upload banner</span>
                            <span class="text-[10px] text-gray-600 mt-1 font-mono">Max 5MB. Recommended size: 728x90 or 300x250</span>
                        </div>
                        
                        <div id="image-preview-container" class="hidden w-full relative">
                            <img id="image-preview" src="#" alt="Preview" class="max-h-48 mx-auto rounded-sm border border-white/10">
                            <button type="button" class="absolute top-2 right-2 bg-red-600 text-white p-1 rounded-sm opacity-0 group-hover:opacity-100 transition-opacity" onclick="event.stopPropagation(); removeImage();">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-between pt-6 border-t border-white/5 mt-8">
                    <p class="text-[10px] text-gray-500 font-mono flex items-center gap-2">
                        <svg class="w-4 h-4 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        All ads require manual review by the Owner.
                    </p>
                    <button type="submit" class="px-8 py-3 bg-red-600 hover:bg-red-700 text-white font-black text-xs uppercase tracking-widest rounded-sm transition-colors shadow-lg shadow-red-900/20 active:scale-95 flex items-center gap-2">
                        Submit Ad Request
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </button>
                </div>

            </form>
        </div>
    </div>

    <script>
        function previewImage(event) {
            const reader = new FileReader();
            reader.onload = function(){
                const output = document.getElementById('image-preview');
                output.src = reader.result;
                document.getElementById('upload-placeholder').classList.add('hidden');
                document.getElementById('image-preview-container').classList.remove('hidden');
            };
            if(event.target.files[0]) {
                reader.readAsDataURL(event.target.files[0]);
            }
        }

        function removeImage() {
            document.getElementById('image').value = '';
            document.getElementById('upload-placeholder').classList.remove('hidden');
            document.getElementById('image-preview-container').classList.add('hidden');
            document.getElementById('image-preview').src = '#';
        }
</script>
</x-layouts.dashboard>
