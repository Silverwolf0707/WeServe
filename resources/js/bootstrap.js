// resources/js/bootstrap.js
import axios from 'axios';
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

// Make sure to use 'pusher' as broadcaster
window.Echo = new Echo({
    broadcaster: 'pusher',
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
    forceTLS: true,
    encrypted: true,
    wsHost: import.meta.env.VITE_PUSHER_HOST,
    wsPort: import.meta.env.VITE_PUSHER_PORT || 443,
    wssPort: import.meta.env.VITE_PUSHER_PORT || 443,
    disableStats: true,
    enabledTransports: ['ws', 'wss']
});

window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';