const filesToCache = [
  "public/js/canvasjs.js",
  "public/js/dashboard.js",
  "public/js/font-awesome.js",
  "public/js/form.js",
  "public/js/jquery.min.js",
  "public/js/popping.js",
  "public/js/Main.js",
  "public/js/sweetalert2.js",
  "public/css/all.min.css",
  "public/css/dashboard_style.css",
  "public/css/style.css",
  "public/css/util.style.css",
  "public/img/background.png",
  "public/img/hero-bg.jpg",
  "public/webfonts/*",
];

const staticCacheName = "pages-cache-v1";

self.addEventListener("install", (event) => {
  console.log("Attempting to install service worker and cache static assets");
  event.waitUntil(
    caches.open(staticCacheName).then((cache) => {
      return cache.addAll(filesToCache);
    })
  );
});

self.addEventListener("fetch", (event) => {
  console.log("Fetch event for ", event.request.url);
  event.respondWith(
    caches
      .match(event.request)
      .then((response) => {
        if (response) {
          console.log("Found ", event.request.url, " in cache");
          return response;
        }
        console.log("Network request for ", event.request.url);
        return fetch(event.request).then((response) => {
          // TODO 5 - Respond with custom 404 page
          return caches.open(staticCacheName).then((cache) => {
            cache.put(event.request.url, response.clone());
            return response;
          });
        });
      })
      .catch((error) => {
        // TODO 6 - Respond with custom offline page
      })
  );
});

self.addEventListener("activate", (event) => {
  console.log("Activating new service worker...");

  const cacheAllowlist = [staticCacheName];

  event.waitUntil(
    caches.keys().then((cacheNames) => {
      return Promise.all(
        cacheNames.map((cacheName) => {
          if (cacheAllowlist.indexOf(cacheName) === -1) {
            return caches.delete(cacheName);
          }
        })
      );
    })
  );
});

// self.addEventListener('activate', event => {
//     console.log('Activating new service worker...');

//     const cacheAllowlist = [staticCacheName];

//     event.waitUntil(
//         caches.keys().then(cacheNames => {
//             return Promise.all(
//                 cacheNames.map(cacheName => {
//                     if (cacheAllowlist.indexOf(cacheName) === -1) {
//                         return caches.delete(cacheName);
//                     }
//                 })
//             );
//         })
//     );
// });
