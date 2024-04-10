var postContainers = document.getElementsByClassName('post-container');

for (var i = 0; i < postContainers.length; i++) {
    postContainers[i].addEventListener('mouseover', function() {
        var editButton = this.querySelector('.edit-post');
        if (editButton) {
            editButton.style.visibility = 'visible';
        }
    });
    postContainers[i].addEventListener('mouseout', function() {
        var editButton = this.querySelector('.edit-post');
        if (editButton) {
            editButton.style.visibility = 'hidden';
        }
    });
}



function cancel() {
    document.getElementById('postContent').value = '';
    document.getElementById('postEdit').close();
}

function showModal(postContent) {
    console.log('Getting post content');
    var xhr = new XMLHttpRequest();
    xhr.open('POST', '../src/include/functions/SQLfunctions.inc.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload = function() {
        console.log('entered');
        try {
            if (this.status == 200) {
                console.log('Response from server:', this.responseText);
                // Parse the response
                var response = this.responseText;
                // Update the post content on the modal
                document.getElementById('postContent').value = response.trim();
                document.getElementById('postEdit').showModal();
            }
        } catch (error) {
            console.error('Error parsing server response:', error);
            console.log('Server response:', this.responseText);
        }
    };
    xhr.send('function=getEditPost&postid=' + encodeURIComponent(postContent))
}