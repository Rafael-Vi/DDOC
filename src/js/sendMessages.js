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
function sendMessage(message, recipientid) {
    var xhr = new XMLHttpRequest();
    xhr.open('POST', '../src/include/functions/sendMessage.inc.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload = function() {
        if (this.status == 200) {
            console.log('Message sent:', message);
            checkUpdates();
        }
    };
    xhr.send('function=sendMessage&message=' + encodeURIComponent(message) + '&recipient=' + encodeURIComponent(recipientid));
}

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