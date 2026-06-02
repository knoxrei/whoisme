<script>
(function () {
    'use strict';

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

            btn.disabled = true;
            btn.querySelector('.btn-label').classList.add('hidden');

            btn.querySelector('.btn-spinner').classList.remove('hidden');

            var relativeUrl = baseUrl;
            try {
                var urlObj = new URL(baseUrl);
                relativeUrl = urlObj.pathname + urlObj.search;
            } catch (e) {
            }

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
                var type = btn.getAttribute('data-type');

                if (type === 'table') {
                    var tempTable = document.createElement('table');
                    var tempBody  = document.createElement('tbody');
                    tempBody.innerHTML = data.html || '';
                    tempTable.appendChild(tempBody);
                    while (tempBody.firstElementChild) {
                        target.appendChild(tempBody.firstElementChild);
                    }
                } else {
                    var tempDiv = document.createElement('div');
                    tempDiv.innerHTML = data.html || '';
                    while (tempDiv.firstChild) {
                        target.appendChild(tempDiv.firstChild);
                    }
                }

                if (data.next_cursor) {
                    btn.setAttribute('data-cursor', data.next_cursor);
                    btn.disabled = false;
                    btn.querySelector('.btn-label').classList.remove('hidden');
                    btn.querySelector('.btn-spinner').classList.add('hidden');
                } else {
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
