self.addEventListener("install", (event) => {
  console.log("[Service Worker] Installed");
  self.skipWaiting();
});

self.addEventListener("activate", (event) => {
  console.log("[Service Worker] Activated");
  event.waitUntil(self.clients.claim());
});

self.addEventListener("push", (event) => {
  const data = event.data.json();
  console.log("[Service Worker] Push received with data:", data); // 🐞 Debugging

  const options = {
    body: data.body,
    icon: data.icon || "",
    badge: data.badge || "",
    image: data.image || "",
  };

  event.waitUntil(self.registration.showNotification(data.title, options));
});
