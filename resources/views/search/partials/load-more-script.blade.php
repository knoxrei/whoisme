<script>
(function () {
    'use strict';

    /**
     * Generic Load More handler.
     * Reads data-* from the button, fetches next page via AJAX,
     * appends HTML into the target container, and updates the cursor.
     */
    function initLoadMore() {
        var btn = document.getElementById('load-more-btn');
        var wrap = document.getElementById('load-more-wrap');
        if (!btn || !wrap) return;

        btn.addEventListener('click', function () {
            var cursor     = btn.getAttribute('data-cursor');
            var baseUrl    = btn.getAttribute('data-url');
            var targetId   = btn.getAttribute('data-target');
            var extraParams = btn.getAttribute('data-extra-params') || '';
            var target     = document.getElementById(targetId);
            if (!cursor || !target) return;

            // Show spinner, disable button
            btn.disabled = true;
            btn.querySelector('.btn-label').classList.add('hidden');
            
            btn.querySelector('.btn-spinner').classList.remove('hidden');

            // Ensure we use a relative path to support both Tor (.onion) and HTTPS clearnet without scheme/host mismatch
            var relativeUrl = baseUrl;
            try {
                var urlObj = new URL(baseUrl);
                relativeUrl = urlObj.pathname + urlObj.search;
            } catch (e) {
                // Keep baseUrl as is if URL constructor fails (e.g. it's already a relative path)
            }

            // Build URL with cursor + any extra query params
            var url = relativeUrl + (relativeUrl.indexOf('?') === -1 ? '?' : '&') + 'cursor=' + encodeURIComponent(cursor) + '&ajax=1';
            if (extraParams) {
                url += '&' + extraParams;
            }

            fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                }
            })
            .then(function (res) {
                if (!res.ok) throw new Error('Network error: ' + res.status);
                return res.json();
            })
            .then(function (data) {
                // Append fetched rows to container
                var type = btn.getAttribute('data-type');

                if (type === 'table') {
                    // MUST use a real <table><tbody> wrapper so the browser
                    // correctly parses <tr> elements — a plain <div> strips them.
                    var tempTable = document.createElement('table');
                    var tempBody  = document.createElement('tbody');
                    tempBody.innerHTML = data.html || '';
                    tempTable.appendChild(tempBody);
                    while (tempBody.firstElementChild) {
                        target.appendChild(tempBody.firstElementChild);
                    }
                } else {
                    // For div/section targets: append block children
                    var tempDiv = document.createElement('div');
                    tempDiv.innerHTML = data.html || '';
                    while (tempDiv.firstChild) {
                        target.appendChild(tempDiv.firstChild);
                    }
                }

                // Update cursor or hide button if no more records
                if (data.next_cursor) {
                    btn.setAttribute('data-cursor', data.next_cursor);
                    btn.disabled = false;
                    btn.querySelector('.btn-label').classList.remove('hidden');
                    btn.querySelector('.btn-spinner').classList.add('hidden');
                } else {
                    // No more pages: show "End of stream" and hide button
                    wrap.innerHTML =
                        '<p class="text-[10px] text-gray-600 uppercase tracking-widest font-bold font-mono">' +
                        '— End of Stream —' +
                        '</p>';
                }
            })
            .catch(function (err) {
                console.error('Load more failed:', err);
                btn.disabled = false;
                btn.querySelector('.btn-label').classList.remove('hidden');
                btn.querySelector('.btn-spinner').classList.add('hidden');
                btn.querySelector('.btn-label').textContent = 'Retry >>';
            });
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initLoadMore);
    } else {
        initLoadMore();
    }
})();
</script>
