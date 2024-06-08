function deleteNotifications(notificationId) {
    $.ajax({
        url: '../src/include/functions/SQLfunctions.inc.php',
        type: 'POST',
        data: {
            function: 'deleteNotifications',
            id: notificationId
        },
        success: function() {
            $('.notification-message').remove();
            loadNotifications();
        },
        error: function(error) {
            console.error(error);
        }
    });
}

let lastData = null;

function loadNotifications() {
    $.ajax({
        url: '../src/include/functions/SQLfunctions.inc.php',
        type: 'POST',
        data: { function: 'loadNotifications' },
        success: function(data) {
            // Only update if the data has changed
            if (data !== lastData) {
                const $notificationsContainer = $('#notifications-container');
                $notificationsContainer.html(data);
                lastData = data;
            }
        },
        error: function(error) {
            console.error('Error:', error);
        }
    });
}

setInterval(loadNotifications, 500);