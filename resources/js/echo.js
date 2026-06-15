import Echo from 'laravel-echo';

import Pusher from 'pusher-js';
window.Pusher = Pusher;

const host = import.meta.env.VITE_REVERB_HOST;
const wsHost = (host === 'localhost' || !host) ? window.location.hostname : host;

window.Echo = new Echo({
    broadcaster: 'reverb',
    key: import.meta.env.VITE_REVERB_APP_KEY,
    wsHost: wsHost,
    wsPort: import.meta.env.VITE_REVERB_PORT ?? 80,
    wssPort: import.meta.env.VITE_REVERB_PORT ?? 443,
    forceTLS: (import.meta.env.VITE_REVERB_SCHEME ?? 'https') === 'https' || window.location.protocol === 'https:',
    enabledTransports: ['ws', 'wss'],
});
