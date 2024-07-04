

let lastMessage = null;

function loadMessages() {
    // Extract the convo_id from the URL path
    const pathSegments = window.location.pathname.split('/');
    const convoIndex = pathSegments.findIndex(segment => segment === 'mensagens');
    let convoId = pathSegments[convoIndex + 1]; // Get convo_id from the path

    // Check if convoId is not set or empty
    if (!convoId) {
        console.log('No conversation ID set. Cannot load messages.');
        return; // Exit the function early
    }

    fetch('/src/include/functions/SQLfunctions.inc.php', {
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
            lastMessage = data;
            document.getElementById('message-container').innerHTML = data;
            var messageContainer = document.getElementById('message-container');
            messageContainer.scrollTop = messageContainer.scrollHeight;
        }
    })
    .catch(error => console.error('Error loading messages:', error));
}

function sendMessage(recipientid) {
    let message = document.getElementById('message-box').value;
    if (message === "") {
        return; // Don't send if the message is empty
    }
    document.getElementById('message-box').value = '';
    fetch('/src/include/functions/SQLfunctions.inc.php', {
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
    if (!window.confirm('Tem certeza que quer apagar esta mensagem?')) {
        return;
    }

    fetch('/src/include/functions/SQLfunctions.inc.php', {
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