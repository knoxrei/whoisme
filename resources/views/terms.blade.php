<x-layouts.app title="Terms of Service">
    <x-static-page title="Terms of" highlight="Service" subtitle="Read carefully. No exceptions.">
        <div class="space-y-6 text-sm leading-relaxed">
            <section class="border border-red-900/30 bg-black p-5 rounded-sm">
                <h2 class="text-red-500 font-semibold mb-2">1. Absolute freedom of information</h2>
                <p class="text-gray-400">
                    By accessing this service, you acknowledge that <strong class="text-gray-300">we have absolutely no rules</strong> regarding the submission of pastebins, sharing of information, or the practice of "doxxing". We turn a blind eye to all content uploaded to our servers. We do not moderate, censor, or remove information based on moral, ethical, or legal requests.
                </p>
            </section>

            <section class="border border-red-950/40 bg-black p-5 rounded-sm">
                <h2 class="text-white font-semibold mb-2">2. Zero accountability & blind eye policy</h2>
                <p class="text-gray-400">
                    We provide the infrastructure; you provide the data. We close our eyes to what is happening on the platform. If you find your personal information here, do not expect us to take it down. We are a neutral conduit of data and take zero responsibility for the consequences of the information shared.
                </p>
            </section>

            <section class="border border-red-950/40 bg-black p-5 rounded-sm">
                <h2 class="text-white font-semibold mb-2">3. Transparent reporting</h2>
                <p class="text-gray-400">
                    While we do not take action on reports to remove content, any reports or complaints submitted to our administration will be treated with full transparency. <strong class="text-red-400">All reports will be made public and transparent.</strong> By sending us a report, you forfeit any expectation of privacy regarding your communication with us.
                </p>
            </section>

            <section class="border border-red-950/40 bg-black p-5 rounded-sm">
                <h2 class="text-white font-semibold mb-2">4. User autonomy</h2>
                <p class="text-gray-400">
                    You are entirely responsible for your own OPSEC. If you choose to use our platform, you do so at your own risk. We do not protect you, and we do not protect your targets.
                </p>
            </section>

            <p class="text-xs text-gray-600 text-center pt-4">
                Last updated: {{ date('Y-m-d') }}
            </p>
        </div>

        <x-internal-ads class="mt-10" />
    </x-static-page>
</x-layouts.app>
