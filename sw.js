// Service Worker for Maharati Platform
const CACHE_NAME = 'maharati-v1.0';
const STATIC_CACHE = 'maharati-static-v1.0';
const DYNAMIC_CACHE = 'maharati-dynamic-v1.0';

// Files to cache immediately
const STATIC_FILES = [
    '/',
    '/index.php',
    '/skills.php',
    '/chat.php',
    '/login.php',
    '/register.php',
    '/css/style.css',
    '/js/app.js',
    '/manifest.json',
    '/offline.html',
    // External resources
    'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css',
    'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js',
    'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css',
    'https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700;900&display=swap'
];

// Install event - cache static files
self.addEventListener('install', event => {
    console.log('Service Worker: Installing...');
    
    event.waitUntil(
        caches.open(STATIC_CACHE)
            .then(cache => {
                console.log('Service Worker: Caching static files');
                return cache.addAll(STATIC_FILES);
            })
            .catch(error => {
                console.error('Service Worker: Error caching static files', error);
            })
    );
    
    // Force activation
    self.skipWaiting();
});

// Activate event - clean up old caches
self.addEventListener('activate', event => {
    console.log('Service Worker: Activating...');
    
    event.waitUntil(
        caches.keys().then(cacheNames => {
            return Promise.all(
                cacheNames.map(cacheName => {
                    if (cacheName !== STATIC_CACHE && cacheName !== DYNAMIC_CACHE) {
                        console.log('Service Worker: Deleting old cache', cacheName);
                        return caches.delete(cacheName);
                    }
                })
            );
        })
    );
    
    // Take control of all pages
    self.clients.claim();
});

// Fetch event - serve from cache or network
self.addEventListener('fetch', event => {
    const { request } = event;
    const url = new URL(request.url);
    
    // Skip non-GET requests
    if (request.method !== 'GET') {
        return;
    }
    
    // Skip chrome-extension and other non-http requests
    if (!url.protocol.startsWith('http')) {
        return;
    }
    
    event.respondWith(
        caches.match(request)
            .then(cachedResponse => {
                // Return cached version if available
                if (cachedResponse) {
                    console.log('Service Worker: Serving from cache', request.url);
                    return cachedResponse;
                }
                
                // Fetch from network
                return fetch(request)
                    .then(networkResponse => {
                        // Check if valid response
                        if (!networkResponse || networkResponse.status !== 200 || networkResponse.type !== 'basic') {
                            return networkResponse;
                        }
                        
                        // Clone response for caching
                        const responseToCache = networkResponse.clone();
                        
                        // Cache dynamic content
                        if (shouldCache(request.url)) {
                            caches.open(DYNAMIC_CACHE)
                                .then(cache => {
                                    console.log('Service Worker: Caching dynamic content', request.url);
                                    cache.put(request, responseToCache);
                                });
                        }
                        
                        return networkResponse;
                    })
                    .catch(error => {
                        console.log('Service Worker: Network fetch failed', error);
                        
                        // Return offline page for navigation requests
                        if (request.destination === 'document') {
                            return caches.match('/offline.html');
                        }
                        
                        // Return cached version if available
                        return caches.match(request);
                    });
            })
    );
});

// Background sync for offline actions
self.addEventListener('sync', event => {
    console.log('Service Worker: Background sync', event.tag);
    
    if (event.tag === 'chat-sync') {
        event.waitUntil(syncChatMessages());
    }
    
    if (event.tag === 'progress-sync') {
        event.waitUntil(syncProgressUpdates());
    }
});

// Push notifications
self.addEventListener('push', event => {
    console.log('Service Worker: Push received');
    
    const options = {
        body: 'تحدي جديد متاح اليوم! هيا لننمي مهاراتك 💪',
        icon: '/images/icon-192x192.png',
        badge: '/images/badge-72x72.png',
        vibrate: [100, 50, 100],
        data: {
            dateOfArrival: Date.now(),
            primaryKey: '1'
        },
        tag: 'daily-challenge',
        requireInteraction: true,
        actions: [
            {
                action: 'open-challenge',
                title: 'ابدأ التحدي',
                icon: '/images/start-icon.png'
            },
            {
                action: 'remind-later',
                title: 'ذكرني لاحقاً',
                icon: '/images/remind-icon.png'
            }
        ],
        dir: 'rtl',
        lang: 'ar'
    };

    if (event.data) {
        try {
            const data = event.data.json();
            options.body = data.body || options.body;
            options.data = { ...options.data, ...data };
        } catch (e) {
            console.error('Service Worker: Error parsing push data', e);
        }
    }

    event.waitUntil(
        self.registration.showNotification('منصة مهاراتي', options)
    );
});

// Handle notification clicks
self.addEventListener('notificationclick', event => {
    console.log('Service Worker: Notification clicked', event.action);
    
    event.notification.close();

    if (event.action === 'open-challenge') {
        event.waitUntil(
            clients.openWindow('/challenges.php')
        );
    } else if (event.action === 'remind-later') {
        // Schedule reminder for later (1 hour)
        setTimeout(() => {
            self.registration.showNotification('تذكير: منصة مهاراتي', {
                body: 'لا تنس إكمال تحدي اليوم! 🎯',
                icon: '/images/icon-192x192.png',
                tag: 'reminder'
            });
        }, 3600000); // 1 hour
    } else {
        event.waitUntil(
            clients.openWindow('/')
        );
    }
});

// Helper functions
function shouldCache(url) {
    // Cache API responses and important pages
    return url.includes('/api/') || 
           url.includes('.php') || 
           url.includes('.css') || 
           url.includes('.js') ||
           url.includes('.json');
}

async function syncChatMessages() {
    try {
        // Get offline chat messages from IndexedDB
        const messages = await getOfflineChatMessages();
        
        for (const message of messages) {
            try {
                const response = await fetch('/api/chat.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(message)
                });
                
                if (response.ok) {
                    await removeOfflineChatMessage(message.id);
                }
            } catch (error) {
                console.error('Service Worker: Error syncing chat message', error);
            }
        }
    } catch (error) {
        console.error('Service Worker: Error in chat sync', error);
    }
}

async function syncProgressUpdates() {
    try {
        // Get offline progress updates from IndexedDB
        const updates = await getOfflineProgressUpdates();
        
        for (const update of updates) {
            try {
                const response = await fetch('/api/skills.php?action=update-progress', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(update)
                });
                
                if (response.ok) {
                    await removeOfflineProgressUpdate(update.id);
                }
            } catch (error) {
                console.error('Service Worker: Error syncing progress update', error);
            }
        }
    } catch (error) {
        console.error('Service Worker: Error in progress sync', error);
    }
}

// IndexedDB helpers (simplified - would need full implementation)
async function getOfflineChatMessages() {
    // Implementation would use IndexedDB to get stored messages
    return [];
}

async function removeOfflineChatMessage(id) {
    // Implementation would remove message from IndexedDB
}

async function getOfflineProgressUpdates() {
    // Implementation would use IndexedDB to get stored updates
    return [];
}

async function removeOfflineProgressUpdate(id) {
    // Implementation would remove update from IndexedDB
}

// Error handling
self.addEventListener('error', event => {
    console.error('Service Worker: Error', event.error);
});

self.addEventListener('unhandledrejection', event => {
    console.error('Service Worker: Unhandled promise rejection', event.reason);
});

console.log('Service Worker: Loaded successfully');
