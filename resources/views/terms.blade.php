<x-layouts.app title="Terms of Service">
    <div class="min-h-[90vh] bg-[#050505] text-gray-300 py-16 px-6 font-sans">
        <main class="w-full max-w-4xl mx-auto">
            <div class="mb-12 border-b border-red-900/30 pb-6">
                <h1 class="text-4xl md:text-6xl font-black text-white tracking-tighter mb-4" style="font-family: 'Outfit', sans-serif;">
                    Terms of <span class="text-red-600" style="text-shadow: 0 0 25px rgba(220, 38, 38, 0.4);">Service</span>
                </h1>
                <p class="text-gray-500 font-mono text-sm uppercase tracking-widest">Read Carefully. No Exceptions.</p>
            </div>

            <div class="space-y-10 font-mono text-sm">
                
                <div class="bg-red-950/10 border border-red-900/30 p-6 rounded-sm">
                    <h3 class="text-red-500 font-black uppercase tracking-widest mb-3 text-lg">1. Absolute Freedom of Information</h3>
                    <p class="text-gray-400 leading-relaxed">
                        By accessing this service, you acknowledge that <strong>we have absolutely no rules</strong> regarding the submission of pastebins, sharing of information, or the practice of "doxxing". We turn a blind eye to all content uploaded to our servers. We do not moderate, censor, or remove information based on moral, ethical, or legal requests.
                    </p>
                </div>

                <div class="bg-[#0a0a0a] border border-white/5 p-6 rounded-sm">
                    <h3 class="text-white font-black uppercase tracking-widest mb-3 text-lg">2. Zero Accountability & Blind Eye Policy</h3>
                    <p class="text-gray-400 leading-relaxed">
                        We provide the infrastructure; you provide the data. We close our eyes to what is happening on the platform. If you find your personal information here, do not expect us to take it down. We are a neutral conduit of data and take zero responsibility for the consequences of the information shared.
                    </p>
                </div>

                <div class="bg-[#0a0a0a] border border-white/5 p-6 rounded-sm">
                    <h3 class="text-white font-black uppercase tracking-widest mb-3 text-lg">3. Transparent Reporting</h3>
                    <p class="text-gray-400 leading-relaxed">
                        While we do not take action on reports to remove content, any reports or complaints submitted to our administration will be treated with full transparency. <strong class="text-red-400">All reports will be made public and transparent.</strong> By sending us a report, you forfeit any expectation of privacy regarding your communication with us.
                    </p>
                </div>

                <div class="bg-[#0a0a0a] border border-white/5 p-6 rounded-sm">
                    <h3 class="text-white font-black uppercase tracking-widest mb-3 text-lg">4. User Autonomy</h3>
                    <p class="text-gray-400 leading-relaxed">
                        You are entirely responsible for your own OPSEC (Operational Security). If you choose to use our platform, you do so at your own risk. We do not protect you, and we do not protect your targets.
                    </p>
                </div>

                <div class="mt-12 text-center">
                    <p class="text-xs text-gray-600 uppercase tracking-widest font-black">
                        Last Updated: {{ date('Y-m-d') }} // END OF TERMS
                    </p>
                </div>

            </div>
        </main>
    </div>
</x-layouts.app>
