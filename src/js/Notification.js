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

function deleteAllNotifications() {
    if (confirm('Are you sure you want to delete all notifications?')) {
        $.ajax({
            url: '../src/include/functions/SQLfunctions.inc.php',
            type: 'POST',
            data: {
                function: 'deleteAllNotifications'
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
}

let lastData = null;

function loadNotifications() {
    $.ajax({
        url: '../src/include/functions/SQLfunctions.inc.php',
        type: 'POST',
        data: { function: 'loadNotifications' },
        success: function(response) {
            // Assuming the response is a JSON string that needs to be parsed
            const data = JSON.parse(response);
            // Check if the count of notifications is more than 0
            if (data.count > 0 && data.html !== lastData) {
                const $notificationsContainer = $('#notifications-container');
                // Update the notifications container with the HTML from the response
                $notificationsContainer.html(data.html);
                lastData = data.html;
                // Show the delete all notifications button
                $('#delete-all-notif').show();
            } else {
                // Hide the delete all notifications button if there are no notifications
                $('#delete-all-notif').hide();
            }
        },
        error: function(error) {
            console.error('Error:', error);
        }
    });
}

setInterval(loadNotifications, 500);

setInterval(loadNotifications, 500);