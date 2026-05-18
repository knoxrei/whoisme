<x-layouts.app :title="$title">
    <div class="min-h-screen flex items-center justify-center p-4">
        <div class="max-w-md w-full  relative overflow-hidden group">
            
            <div class="relative z-10 text-center space-y-8">
                <div class="w-20 h-20  rounded-2xl flex items-center justify-center mx-auto shadow-inner">
                    <svg class="w-10 h-10 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                </div>
                
                <div class="space-y-2">
                    <h1 class="text-3xl font-black uppercase  tracking-tighter text-white">Protected Pastebin</h1>
                    <p class="text-gray-500 text-sm font-medium">This paste is encrypted. Enter the decryption key to view the content.</p>
                </div>

                <form action="{{ route('pastebin.unlock', $pastebin->slug) }}" method="POST" class="space-y-4">
                    @csrf
                    <input type="password" name="password" placeholder="DECRYPTION KEY" required
                        class="w-full bg-black border border-gray-800 rounded-2xl px-6 py-4 text-center text-sm font-mono tracking-widest focus:outline-none focus:border-red-600 transition-colors shadow-inner">
                    
                    @if($errors->has('password'))
                        <p class="text-red-500 text-[10px] font-bold uppercase tracking-widest">{{ $errors->first('password') }}</p>
                    @endif

                    <button type="submit" class="w-full bg-red-600  text-white py-4 font-black uppercase text-sm tracking-widest ">
                        Decrypt Content
                    </button>
                </form>

                <div class="pt-4">
                    <a href="{{ route('pastebin.create') }}" class="text-[10px] font-bold text-gray-700 uppercase tracking-widest hover:text-red-500 transition-colors">
                        ← Back to safety
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
