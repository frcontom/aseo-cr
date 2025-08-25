self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open('biometric-cache').then((cache) => {
            return cache.addAll([
                '/biometric',
                '/script.js',
                '/logo.png',
                '/icon-512x512.png'
            ]);
        })
    );
});

self.addEventListener('fetch', (event) => {
    event.respondWith(
        fetch(event.request).catch((error) => {
            console.error('La petición falló:', error);
            return new Response('No hay conexión a la red', {
                status: 503,
                statusText: 'Service Unavailable',
            });
        })
    );
});
