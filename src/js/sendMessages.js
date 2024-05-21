function checkUpdates() {
    var xhr = new XMLHttpRequest();
    xhr.open('GET', '../src/include/functions/checkUpdates.inc.php', true);
    xhr.onload = function() {
        if (this.status == 200) {
            var response = JSON.parse(this.responseText);
            if (response.newMessages) {
                console.log('New messages:', response.newMessages);
                // Update the HTML with the new messages
                var messagesDiv = document.getElementById('messages-div');
                response.newMessages.forEach(function(message) {
                    var messageElement = document.createElement('p');
                    messageElement.textContent = message;
                    messagesDiv.appendChild(messageElement);
                });
            }
        }
    };
    xhr.send();
}


let lastMessage = null;

function loadMessages() {
    fetch('../src/include/functions/SQLfunctions.inc.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: new URLSearchParams({
            function: 'loadMessages',
        })
    })
    .then(response => response.text())
    .then(data => {
        // Only update if the data has changed
        if (data !== lastMessage) {
            console.log('Messages loaded:', data);
            lastMessage = data;
            document.getElementById('message-container').innerHTML = data;
        }
    })
    .catch(error => console.error(error));
}

function sendMessage(recipientid) {
    let message = document.getElementById('message-box').value;
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
            checkUpdates();
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

    var xhr = new XMLHttpRequest();
    xhr.open('POST', '../src/include/functions/deleteMessage.inc.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload = function() {
        if (this.status == 200) {
            console.log('Message deleted:', messageId);
            checkUpdates();
        }
    };
    xhr.send('function=deleteMessage&messageId=' + encodeURIComponent(messageId));
}