function deleteNotifications(notificationId) {
    fetch('../src/include/functions/SQLfunctions.inc.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: new URLSearchParams({
            function: 'deleteNotifications',
            id: notificationId 
        })
    })
    .then(response => response.text())
    .then(() => {
        document.querySelectorAll('.notification-message').forEach(el => el.remove());
        loadNotifications();
    })
    .catch(error => console.error(error));
}

function loadNotifications() {
    fetch('../src/include/functions/SQLfunctions.inc.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'function=loadNotifications',
    })
    .then(response => response.text())
    .then(data => {
        // Handle the data from the PHP file
        const notificationsContainer = document.getElementById('notifications-container');
        notificationsContainer.innerHTML = data;
    })
    .catch(error => {
        // Handle the error
        console.error('Error:', error);
    });
}