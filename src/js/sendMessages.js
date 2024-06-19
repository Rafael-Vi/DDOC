

let lastMessage = null;
function loadMessages() {
    // Assuming convoId is stored in a global variable or session storage
    let convoId = sessionStorage.getItem('convo_id'); // Example: Retrieving from session storage

    // Check if convoId is not set or empty
    if (!convoId) {
        console.log('No conversation ID set. Cannot load messages.');
        return; // Exit the function early
    }

    fetch('../src/include/functions/SQLfunctions.inc.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: new URLSearchParams({
            function: 'loadMessages',
            convoId: convoId, // Include the convoId in the request
        })
    })
    .then(response => response.text())
    .then(data => {
        // Only update if the data has changed
        if (data !== lastMessage) {
            console.log('Messages loaded:', data);
            lastMessage = data;
            document.getElementById('message-container').innerHTML = data;
            var messageContainer = document.getElementById('message-container');
            messageContainer.scrollTop = messageContainer.scrollHeight;
        }
    })
    .catch(error => console.error(error));
}

function sendMessage(recipientid) {
    let message = document.getElementById('message-box').value;
    if (message === "") {
        return; // Don't send if the message is empty
    }
    document.getElementById('message-box').value = '';
    fetch('../src/include/functions/SQLfunctions.inc.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: new URLSearchParams({
            function: 'sendMessage',
            message: message,
            recipient: recipientid
        })
    })
    .then(response => response.text())
    .then(data => {
        // Only update if the data has changed
        if (data !== lastMessage) {
            console.log('Message sent:', message);
            loadMessages();
        }
    })
    .catch(error => console.error(error));
}

setInterval(loadMessages, 500);

function deleteMessage(messageId) {
    console.log('Delete message:', messageId)
    if (!window.confirm('Are you sure you want to delete this message? This action cannot be undone.')) {
        return;
    }

    fetch('../src/include/functions/SQLfunctions.inc.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: new URLSearchParams({
            function: 'deleteMessage',
            messageId: messageId
        })
    })
    .then(response => response.text())
    .then(data => {
        console.log('Message deleted:', messageId);
        checkUpdates();
    })
    .catch(error => console.error(error));
}