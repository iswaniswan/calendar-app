self.addEventListener('push', (event) => {
    console.log('service-worker.js push');
    const notification = event.data.json();

    event.waitUntil(self.registration.showNotification(notification.title, {
        body: notification.body,
        icon: 'check.jpg',
        data: {
            notifUrl: notification.url
        }
    }));
});

self.addEventListener('notificationclick', (event) => {
    console.log('service-worker.js notificationclick');
    event.waitUntil(clients.openWindow(event.notification.data.notifUrl));
});


 