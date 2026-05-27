const CACHE_NAME = 'apss-cache-v1';
const ASSETS_TO_CACHE = [
    '/',
    '/favicon.ico',
    '/bs/css/bootstrap.min.css',
    '/bs/font/bootstrap-icons.min.css',
    '/bs/js/bootstrap.bundle.min.js',
    '/images/logosmk.png',
    '/images/logosmk_transparent.png',
    '/images/school_bg.jpeg'
];

// Install Service Worker
self.addEventListener('install', event => {
    event.waitUntil(
        caches.open(CACHE_NAME).then(cache => {
            console.log('[Service Worker] Caching app shell and static assets');
            return cache.addAll(ASSETS_TO_CACHE);
        })
    );
    self.skipWaiting();
});

// Activate Service Worker
self.addEventListener('activate', event => {
    event.waitUntil(
        caches.keys().then(cacheNames => {
            return Promise.all(
                cacheNames.map(cache => {
                    if (cache !== CACHE_NAME) {
                        console.log('[Service Worker] Clearing old cache');
                        return caches.delete(cache);
                    }
                })
            );
        })
    );
    self.clients.claim();
});

// Fetch resources
self.addEventListener('fetch', event => {
    // Only cache GET requests
    if (event.request.method !== 'GET') return;

    // Avoid caching external resources or chrome extensions
    const url = new URL(event.request.url);
    if (!url.origin.startsWith(self.location.origin)) return;

    // Avoid caching Laravel routes that involve forms/actions
    if (url.pathname.includes('/logout') || url.pathname.includes('/import') || url.pathname.includes('/export')) return;

    // Network-first strategy for routes, fallback to cache
    event.respondWith(
        fetch(event.request)
            .then(response => {
                // If response is valid, cache it
                if (response && response.status === 200 && response.type === 'basic') {
                    const responseClone = response.clone();
                    caches.open(CACHE_NAME).then(cache => {
                        cache.put(event.request, responseClone);
                    });
                }
                return response;
            })
            .catch(() => {
                // Network failed, try cache
                return caches.match(event.request).then(cachedResponse => {
                    if (cachedResponse) {
                        return cachedResponse;
                    }
                    if (event.request.mode === 'navigate') {
                        return caches.match('/');
                    }
                });
            })
    );
});
