<x-layouts.app :title="$title">
    <div class="min-h-screen text-white overflow-hidden">
        <form action="{{ route('pastebin.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="flex flex-col lg:flex-row min-h-screen relative">
                <div id="main-content"
                    class="flex-1 flex flex-col p-4 lg:p-8 bg-[#050505] transition-all duration-300 relative">
                    <button type="button" id="toggle-sidebar"
                        class="hidden lg:flex absolute right-0 top-1/2 -translate-y-1/2 translate-x-1/2 z-50 items-center justify-center w-13 h-12 bg-red-600 border border-red-500 rounded-full hover:bg-red-700 transition-all shadow-lg shadow-red-900/40 group">
                        <svg id="toggle-icon" class="w-4 h-4 text-white transition-transform duration-300" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M15 19l-7-7 7-7">
                            </path>
                        </svg>
                    </button>

                    <div class="mb-6 flex items-center justify-between">
                        <div>
                            <h1 class="text-2xl font-bold tracking-tight">Create New Paste</h1>
                            <p class="text-gray-500 text-sm">Write or paste your content below.
                                <span
                                    class="text-xs font-semibold text-gray-500 border border-gray-800 rounded-md px-2 py-1">Markdown
                                    Supported</span>
                            </p>

                        </div>
                        <div class="lg:hidden">
                            <button type="submit"
                                class="bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded-md font-bold transition-all text-sm">
                                Publish
                            </button>
                        </div>
                    </div>

                    <div class="flex-1 flex flex-col">
                        <label for="content" class="sr-only">Content</label>
                        <textarea name="content" id="content" placeholder="Paste your content here..." required
                            class="flex-1 w-full bg-[#0a0a0a] border border-gray-800 rounded-lg p-6 font-mono text-sm focus:outline-none focus:border-red-600 transition-colors resize-none shadow-inner"
                            style="min-height: 70vh;">{{ old('content') }}</textarea>
                    </div>
                </div>

                <aside id="sidebar"
                    class="w-full lg:w-96 bg-[#0a0a0a] border-l border-gray-900 p-6 lg:p-8 space-y-8 overflow-y-auto transition-all duration-300 transform translate-x-0 relative">
                    <div class="space-y-6">
                        <div class="space-y-2">
                            <label for="title"
                                class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Title</label>
                            <input type="text" name="title" id="title" placeholder="A brief title"
                                value="{{ old('title') }}" required
                                class="w-full bg-black border border-gray-800 rounded-md px-4 py-3 text-sm focus:outline-none focus:border-red-600 transition-colors">
                        </div>

                        <div class="space-y-2">
                            <label for="description"
                                class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Description <span class="text-[10px] font-normal text-red-500 normal-case">(Optional - Helps in finding it on search engines)</span></label>
                            <textarea name="description" id="description" rows="3" placeholder="Describe your paste..."
                                class="w-full bg-black border border-gray-800 rounded-md px-4 py-3 text-sm focus:outline-none focus:border-red-600 transition-colors resize-none">{{ old('description') }}</textarea>
                        </div>

                        <div class="space-y-4 pt-4 border-t border-gray-900">
                            <div class="space-y-2">
                                <label for="cover_path"
                                    class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Cover
                                    Image <span class=" text-xs font-normal text-red-500">(Optional)</span></label>
                                <div class="relative group">
                                    <input type="file" name="cover_path" id="cover_path"
                                        class="w-full p-2 bg-black border border-gray-800 rounded-md px-4 py-2.5 text-xs file:mr-4 file:py-1 file:px-3 file:rounded file:border-0 file:text-xs file:font-semibold file:bg-red-600 file:text-white hover:file:bg-red-700 cursor-pointer">
                                </div>
                            </div>

                            <div class="space-y-2">
                                <label for="image"
                                    class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Gallery Images
                                    (Max 5) <span class=" text-xs font-normal text-red-500">(Optional)</span></label>
                                <input type="file" id="image" name="image[]" multiple
                                    class="w-full bg-black border border-gray-800 rounded-md px-4 py-2.5 text-xs file:mr-4 file:py-1 file:px-3 file:rounded file:border-0 file:text-xs file:font-semibold file:bg-gray-800 file:text-white hover:file:bg-gray-700 cursor-pointer">
                                <p class="text-[10px] text-gray-600 italic">Hold Ctrl/Cmd to select multiple files.</p>
                            </div>

                            <div class="space-y-2 pt-2">
                                <div class="flex items-center justify-between p-3.5 bg-red-950/10 border border-red-900/30 rounded-md">
                                    <div class="space-y-0.5">
                                        <label for="is_self_destruct" class="text-xs font-black text-red-500 uppercase tracking-widest cursor-pointer select-none">
                                            Burn After Reading
                                        </label>
                                        <p class="text-[9px] text-gray-500 font-mono">
                                            Destruct automatically on first view.
                                        </p>
                                    </div>
                                    <div class="relative flex items-center">
                                        <input type="checkbox" name="is_self_destruct" id="is_self_destruct" value="1"
                                            class="w-4 h-4 rounded border-gray-800 text-red-600 focus:ring-red-600 focus:ring-offset-black bg-black">
                                    </div>
                                </div>
                            </div>

                            @if(auth()->check() && auth()->user()->canUsePremiumFeatures())
                            <div class="space-y-4 pt-4 border-t border-gray-900">
                                <div class="space-y-2">
                                    
                                    <label for="visibility" class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Visibility</label>
                                    <select name="visibility" id="visibility"
                                        class="w-full bg-black border border-gray-800 rounded-md px-4 py-3 text-sm focus:outline-none focus:border-red-600 transition-colors appearance-none">
                                        <option value="public" {{ old('visibility') == 'public' ? 'selected' : '' }}>Public</option>
                                        <option value="private" {{ old('visibility') == 'private' ? 'selected' : '' }}>Private</option>
                                    </select>
                                </div>

                                <div class="space-y-2">
                                    <label for="password" class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Password Protection <span class="text-[10px] font-normal text-gray-600">(Optional)</span></label>
                                    <input type="password" name="password" id="password" placeholder="Min. 8 characters"
                                        class="w-full bg-black border border-gray-800 rounded-md px-4 py-3 text-sm focus:outline-none focus:border-red-600 transition-colors">
                                </div>
                            </div>
                            @endif
                        </div>

                        <div class="pt-6">
                            <button type="submit"
                                class="w-full bg-red-600 hover:bg-red-700 text-white py-4 rounded-md font-bold transition-all shadow-lg shadow-red-900/20 active:scale-[0.98]">
                                Publish Now
                            </button>
                        </div>

                        <div class="pt-6 border-t border-gray-900 space-y-4">
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-red-500" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M20.53 11H3.47C3.21 11 3 11.21 3 11.47v1.06c0 .26.21.47.47.47h17.06c.26 0 .47-.21.47-.47v-1.06c0-.26-.21-.47-.47-.47zM3.47 6h17.06C20.79 6 21 6.21 21 6.47v1.06c0 .26-.21.47-.47.47H3.47C3.21 8 3 7.79 3 7.53V6.47C3 6.21 3.21 6 3.47 6zm0 10h17.06c.26 0 .47.21.47.47v1.06c0 .26-.21.47-.47.47H3.47c-.26 0-.47-.21-.47-.47v-1.06c0-.26.21-.47.47-.47z" />
                                </svg>
                                <span class="text-xs font-semibold text-gray-500 uppercase tracking-widest">Markdown
                                    Supported</span>
                            </div>
                            <div class="grid grid-cols-2 gap-x-4 gap-y-2 text-[10px] text-gray-600 font-mono">
                                <div class="flex justify-between border-b border-gray-900/50 pb-1">
                                    <span>**bold**</span>
                                    <span class="text-gray-400">Bold</span>
                                </div>
                                <div class="flex justify-between border-b border-gray-900/50 pb-1">
                                    <span>*italic*</span>
                                    <span class="text-gray-400">Italic</span>
                                </div>
                                <div class="flex justify-between border-b border-gray-900/50 pb-1">
                                    <span># H1</span>
                                    <span class="text-gray-400">Header</span>
                                </div>
                                <div class="flex justify-between border-b border-gray-900/50 pb-1">
                                    <span>[link](url)</span>
                                    <span class="text-gray-400">Link</span>
                                </div>
                                <div class="flex justify-between border-b border-gray-900/50 pb-1">
                                    <span>- list</span>
                                    <span class="text-gray-400">Bullet</span>
                                </div>
                                <div class="flex justify-between border-b border-gray-900/50 pb-1">
                                    <span>`code`</span>
                                    <span class="text-gray-400">Code</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </aside>
            </div>
        </form>
    </div>

    <script>
        document.getElementById('image').addEventListener('change', function () {
            if (this.files.length > 5) {
                alert("You can only upload a maximum of 5 images");
                this.value = '';
            }
        });

        const toggleBtn = document.getElementById('toggle-sidebar');
        const sidebar = document.getElementById('sidebar');
        const mainContent = document.getElementById('main-content');
        const toggleIcon = document.getElementById('toggle-icon');
        let isCollapsed = false;

        toggleBtn.addEventListener('click', () => {
            isCollapsed = !isCollapsed;

            if (isCollapsed) {
                sidebar.style.width = '0px';
                sidebar.style.padding = '0px';
                sidebar.style.overflow = 'hidden';
                sidebar.style.borderLeft = 'none';
                toggleIcon.style.transform = 'rotate(180deg)';
                mainContent.classList.add('max-w-full');
            } else {
                sidebar.style.width = '';
                sidebar.style.padding = '';
                sidebar.style.overflow = 'auto';
                sidebar.style.borderLeft = '';
                toggleIcon.style.transform = 'rotate(0deg)';
                mainContent.classList.remove('max-w-full');
            }
        });
</script>
</x-layouts.app>