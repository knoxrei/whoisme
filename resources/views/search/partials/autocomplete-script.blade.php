<script>
(function () {
    var inputId  = @json($inputId ?? 'search-input');
    var boxId    = @json($boxId ?? 'autocomplete-box');
    var listId   = @json($listId ?? 'autocomplete-list');
    var suggestUrl = '{{ route("search.suggest") }}';
    try {
        var urlObj = new URL(suggestUrl);
        suggestUrl = urlObj.pathname + urlObj.search;
    } catch (e) {
    }

    var input = document.getElementById(inputId);
    var box   = document.getElementById(boxId);
    var list  = document.getElementById(listId);
    if (!input || !box || !list) return;

    var timer = null;

    function close() {
        box.classList.add('hidden');
        list.innerHTML = '';
    }

    function escapeHtml(s) {
        return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
    }

    function render(items) {
        list.innerHTML = '';
        if (!items.length) { close(); return; }

        items.forEach(function (item) {
            var li = document.createElement('li');
            li.innerHTML =
                '<a href="/pastebin/' + encodeURIComponent(item.slug) + '"' +
                '   class="flex items-center justify-between px-4 py-2.5 hover:bg-red-950/10 transition-colors group">' +
                '  <span class="text-xs text-gray-300 group-hover:text-white font-bold truncate max-w-xs">' + escapeHtml(item.title) + '</span>' +
                '  <span class="text-[9px] text-gray-600 font-mono ml-3 shrink-0 uppercase">@' + escapeHtml(item.author_name) + '</span>' +
                '</a>';
            li.querySelector('a').addEventListener('mousedown', function(e){ e.preventDefault(); });
            li.querySelector('a').addEventListener('click', close);
            list.appendChild(li);
        });
        box.classList.remove('hidden');
    }

    function fetch_suggest(q) {
        fetch(suggestUrl + '?q=' + encodeURIComponent(q), {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(function(r){ return r.json(); })
        .then(render)
        .catch(close);
    }

    input.addEventListener('input', function () {
        var q = input.value.trim();
        clearTimeout(timer);
        if (q.length < 2) { close(); return; }
        timer = setTimeout(function(){ fetch_suggest(q); }, 200);
    });

    input.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') { close(); return; }
        if (e.key === 'ArrowDown' || e.key === 'ArrowUp') {
            e.preventDefault();
            var items = list.querySelectorAll('a');
            if (!items.length) return;
            var focused = list.querySelector('a:focus');
            var idx = Array.from(items).indexOf(focused);
            idx = e.key === 'ArrowDown' ? (idx + 1) % items.length : (idx - 1 + items.length) % items.length;
            items[idx].focus();
        }
    });

    document.addEventListener('click', function(e) {
        if (!box.contains(e.target) && e.target !== input) close();
    });

    input.addEventListener('focus', function() {
        if (input.value.trim().length >= 2 && list.children.length) box.classList.remove('hidden');
    });
})();
</script>
