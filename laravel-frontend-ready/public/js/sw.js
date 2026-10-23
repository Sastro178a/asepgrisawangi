// =====================================================================
// FILE: sw.js — Service Worker E-Aspirasi Grisawangi
// FUNGSI: Cache offline, Push Notification background, Install PWA
// =====================================================================

const CACHE_NAME = 'e-aspirasi-v1';
const ASSETS_TO_CACHE = [
  '/',
  '/index.html',
  '/admin.html',
  '/elakses.html',
  '/elpoint.html',
  '/icon.png',
  '/manifest.json'
];

// -----------------------------------------------------------------------
// 1. INSTALL — Cache semua asset utama saat SW pertama dipasang
// -----------------------------------------------------------------------
self.addEventListener('install', event => {
  event.waitUntil(
    caches.open(CACHE_NAME).then(cache => {
      console.log('[SW] Caching assets...');
      return cache.addAll(ASSETS_TO_CACHE);
    }).then(() => self.skipWaiting())
  );
});

// -----------------------------------------------------------------------
// 2. ACTIVATE — Bersihkan cache lama saat SW baru diaktifkan
// -----------------------------------------------------------------------
self.addEventListener('activate', event => {
  event.waitUntil(
    caches.keys().then(keys =>
      Promise.all(
        keys.filter(key => key !== CACHE_NAME).map(key => {
          console.log('[SW] Menghapus cache lama:', key);
          return caches.delete(key);
        })
      )
    ).then(() => self.clients.claim())
  );
});

// -----------------------------------------------------------------------
// 3. FETCH — Strategi: Network First, fallback ke Cache
//    Cocok untuk aplikasi dinamis yang butuh data terbaru
// -----------------------------------------------------------------------
self.addEventListener('fetch', event => {
  // Jangan cache request ke API backend
  if (event.request.url.includes('/api/') || 
      event.request.url.includes('supabase.co') ||
      event.request.url.includes('telegram.org') ||
      event.request.method !== 'GET') {
    return;
  }

  event.respondWith(
    fetch(event.request)
      .then(response => {
        // Simpan response baru ke cache
        if (response && response.status === 200) {
          const cloned = response.clone();
          caches.open(CACHE_NAME).then(cache => cache.put(event.request, cloned));
        }
        return response;
      })
      .catch(() => {
        // Jika offline, ambil dari cache
        return caches.match(event.request).then(cached => {
          if (cached) return cached;
          // Fallback ke index.html untuk navigasi
          if (event.request.destination === 'document') {
            return caches.match('/index.html');
          }
        });
      })
  );
});

// -----------------------------------------------------------------------
// 4. PUSH — Terima pesan push dari server & tampilkan notifikasi
// -----------------------------------------------------------------------
self.addEventListener('push', event => {
  let data = {
    title: 'E-Aspirasi Grisawangi',
    body: 'Ada pembaruan baru untuk Anda.',
    icon: '/icon.png',
    badge: '/icon.png',
    tag: 'ea-notif',
    data: { url: '/' }
  };

  // Parse data dari server jika ada
  if (event.data) {
    try {
      const parsed = event.data.json();
      data = { ...data, ...parsed };
    } catch(e) {
      data.body = event.data.text();
    }
  }

  event.waitUntil(
    self.registration.showNotification(data.title, {
      body: data.body,
      icon: data.icon || '/icon.png',
      badge: data.badge || '/icon.png',
      tag: data.tag || 'ea-notif',
      renotify: true,
      vibrate: [200, 100, 200],
      data: data.data || { url: '/' },
      actions: [
        { action: 'buka', title: '📂 Buka Aplikasi' },
        { action: 'tutup', title: '✕ Tutup' }
      ]
    })
  );
});

// -----------------------------------------------------------------------
// 5. NOTIFICATIONCLICK — Aksi saat notifikasi diklik
// -----------------------------------------------------------------------
self.addEventListener('notificationclick', event => {
  event.notification.close();

  if (event.action === 'tutup') return;

  const targetUrl = (event.notification.data && event.notification.data.url) 
    ? event.notification.data.url 
    : '/';

  event.waitUntil(
    clients.matchAll({ type: 'window', includeUncontrolled: true }).then(clientList => {
      // Kalau tab sudah terbuka, fokus ke sana
      for (const client of clientList) {
        if (client.url.includes(self.location.origin) && 'focus' in client) {
          return client.focus();
        }
      }
      // Kalau belum ada tab, buka baru
      if (clients.openWindow) return clients.openWindow(targetUrl);
    })
  );
});

// -----------------------------------------------------------------------
// 6. PUSHSUBSCRIPTIONCHANGE — Perbarui subscription jika expired
// -----------------------------------------------------------------------
self.addEventListener('pushsubscriptionchange', event => {
  event.waitUntil(
    self.registration.pushManager.subscribe({
      userVisibleOnly: true,
      applicationServerKey: self.VAPID_PUBLIC_KEY || ''
    }).then(subscription => {
      console.log('[SW] Push subscription diperbarui:', subscription);
    })
  );
});
