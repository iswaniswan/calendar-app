function initNotification() {
    Notification.requestPermission().then(perm => {
        if (perm !== 'granted') {
            $('#notification-wrapper').fadeIn("slow");
        } else {
            registerServiceWorker();
        }
    })
}

function showNotification(title, message) {
    const notification = new Notification(title, {
        body: message,
        icon: "your-icon.png", // Optional: provide a custom icon path
    });
}

function getCookie(name) {
    const cookies = document.cookie.split("; ");
    for (const cookie of cookies) {
        const [cookieName, cookieValue] = cookie.split("=");
        if (cookieName === name) {
            return decodeURIComponent(cookieValue);
        }
    }
    return null;
}

function isPushNotificationServiceEnable() {
    let isEnable = true;

    const isPushNotificationServiceEnable = getCookie("isPushNotificationServiceEnable");

    if (isPushNotificationServiceEnable && isPushNotificationServiceEnable === 'true') {
        console.log('welcome back!');        
    } else {
        isEnable = false;
    }

    if (isEnable === false) {        
        // show modal enable push notification service
        $('#modal').modal('show');
    }
}

function registerServiceWorker() {
    if ('serviceWorker' in navigator) {
        // check cookies if has enable push notification service
        isPushNotificationServiceEnable();

        navigator.serviceWorker.register('service-worker.js').then(function(registration) {
            console.log('Service Worker registered with scope:', registration.scope);
            }).catch((error) => {
                console.log('request permission error ', error)
            })
    } else {
        console.log('service worker is not supported');
    }
}

// @publicKey @see params.php
function enableNotif() {
    Notification.requestPermission().then(permission => {
        if (permission === 'granted') {
            // Subscribe to push notifications
            navigator.serviceWorker.ready.then(swRegistration => {
                swRegistration.pushManager.subscribe({
                    userVisibleOnly: true,
                    applicationServerKey: 'BGrrulv_FvjsVfnWR7Qs1xNj9SHWmPBDg2fAx8RuVED-s3he5mS_gZwowm2MmnZRX3SRT8dqA1IN1yUuI9Y7u3c'
                }).then(subscription => {
                    // Send subscription object to server
                    console.log('subscription : ', subscription);
                    sendSubscriptionToServer(subscription);
                }).catch(error => {
                    console.error('Error subscribing to push notifications:', error);
                });
            });
        }
    });

    $('#modal').modal('hide');

    //set cookie for 1 year
    const expirationDate = new Date();
    expirationDate.setDate(expirationDate.getDate() + 365); // Add days to the current date
    const expires = "expires=" + expirationDate.toUTCString();
    document.cookie = "isPushNotificationServiceEnable=true;" + expires + "; path=/";
}

function sendSubscriptionToServer(subscription) {
    $.ajax({
        url: 'index.php?r=schedule/save-subscription',
        method: 'POST',
        contentType: 'application/json',
        data: JSON.stringify(subscription),
        success: function(response) {
            console.log('Subscription saved on server:', response);
        },
        error: function(xhr, status, error) {
            console.error('Error saving subscription on server:', error);
        }
    });
}
    
$(document).ready(function() {    
    
    $('#btn-accept-notification-sevice').on('click', function() {
        enableNotif();
    })

    initNotification();
});