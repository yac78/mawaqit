var MawaqitNotification = {
    getPermision: function () {
        Notification.requestPermission();
    },
    showNotification: function (title, body) {
        if (Notification.permission == 'granted') {
            navigator.serviceWorker.getRegistration().then(function (reg) {
                var options = {
                    body: body,
                    icon: '/android-chrome-192x192.png',
                    vibrate: [100, 50, 100],
                };
                reg.showNotification(title, options);
            });
        }
    }
};

MawaqitNotification.getPermision();