@unless(request()->routeIs('welcome', 'search.index'))
<script>
(function () {
    const trackUrl = @json(route('visitors.root.track', [], false));
    const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    if (!trackUrl || !csrf) return;

    function ping() {
        fetch(trackUrl, {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrf,
            },
            credentials: 'same-origin',
        }).catch(function () {});
    }

    document.addEventListener('DOMContentLoaded', function () {
        ping();
        setInterval(ping, 60000);
    });
})();
</script>
@endunless
