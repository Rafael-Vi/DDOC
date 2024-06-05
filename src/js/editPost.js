var postContainers = document.getElementsByClassName('post-container');

for (var i = 0; i < postContainers.length; i++) {
    postContainers[i].addEventListener('mouseover', function() {
        var editButton = this.querySelector('.edit-post');
        var captionLabel = this.querySelector('.caption-label'); // Select the label
        var newButton = this.querySelector('.new-button'); // Select the new button
        if (editButton) {
            editButton.style.visibility = 'visible';
        }
        if (captionLabel) {
            captionLabel.style.visibility = 'visible'; // Show the label
        }
        if (newButton) {
            newButton.style.visibility = 'visible'; // Show the new button
        }
    });
    postContainers[i].addEventListener('mouseout', function() {
        var editButton = this.querySelector('.edit-post');
        var captionLabel = this.querySelector('.caption-label'); // Select the label
        var newButton = this.querySelector('.new-button'); // Select the new button
        if (editButton) {
            editButton.style.visibility = 'hidden';
        }
        if (captionLabel) {
            captionLabel.style.visibility = 'hidden'; // Hide the label
        }
        if (newButton) {
            newButton.style.visibility = 'hidden'; // Hide the new button
        }
    });
}

function cancel() {
    document.getElementById('postContent').value = '';
    var captionElement = document.getElementById('caption');
    var postIdElement = document.getElementById('postId');
    captionElement.textContent = "Caption";
    postContent.placeholder = 'Altera para outra legenda...';
    postIdElement.value = ''; // Clear the postId input
    document.getElementById('postEdit').close();
}
function showModal(postid, caption) {
    var postEdit = document.getElementById('postEdit');
    var postContent = document.getElementById('postContent');
    var captionElement = document.getElementById('caption');
    var postIdElement = document.getElementById('postId');
    captionElement.textContent = caption;
    postContent.placeholder = 'Altera "' + caption + '" para outra legenda...';
    postIdElement.value = btoa(postid);
    postEdit.showModal();
}

function checkIfOwner(postid, callback) {
    var xhr = new XMLHttpRequest();
    xhr.open('POST', '../src/include/functions/SQLfunctions.inc.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload = function() {
        try {
            if (this.status == 200) {
                var response = JSON.parse(this.responseText);
                if (!response.isOwner) {
                    console.error('User is not the owner of the post');
                    console.log('userID:', response.userID);
                }
                callback(response.isOwner);
            }
        } catch (error) {
            console.error('Error parsing server response:', error);
            console.log('Server response:', this.responseText);
        }
    };
    xhr.send('function=checkIfitsOwner&postid=' + encodeURIComponent(postid));
}


function deletePost() {
    var postIdElement = document.getElementById('postId');
    var postid = atob(postIdElement.value);
    var userConfirmed = window.confirm('Are you sure you want to delete this post?');
    if (!userConfirmed) {
        return;
    }
    checkIfOwner(postid, function(isOwner) {
        if (isOwner) {
            // Delete the post
            var xhr = new XMLHttpRequest();
            xhr.open('POST', '../src/include/functions/SQLfunctions.inc.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onload = function() {
                try {
                    if (this.status == 200) {
                        var response = JSON.parse(this.responseText);
                        if (response.success) {
                            // Post was deleted successfully
                            console.log('Post was deleted successfully');
                        } else {
                            // There was an error deleting the post
                            console.error('There was an error deleting the post');
                        }
                    }
                } catch (error) {
                    console.log('Server response:', this.responseText);
                }
            };
            xhr.send('function=deletePost&postid=' + encodeURIComponent(postid));
            if (response.success) {
                console.log('Post was deleted successfully');
                loadPosts();
            } else {
                console.error('There was an error deleting the post');
            }
        } else {
            console.error('User is not the owner of the post');
        }
    });
}

function loadPosts() {
    location.reload();
}

function save() {
    var postIdElement = document.getElementById('postId');
    var postid = atob(postIdElement.value);
    var textarea = document.getElementById('postContent');
    var postContent = textarea.value;

    var confirmation = confirm('Are you sure you want to save this post?');
    if (!confirmation) {
        return;
    }

    checkIfOwner(postid, function(isOwner) {
        if (isOwner) {
            var xhr = new XMLHttpRequest();
            xhr.open('POST', '../src/include/functions/SQLfunctions.inc.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onload = function() {
                try {
                    if (this.status == 200) {
                        var response = JSON.parse(this.responseText);
                        if (response.success) {
                            console.log('Post saved successfully');
                            loadPosts();
                        } else {
                            console.error('Error saving post:', response.error);
                        }
                    }
                } catch (error) {
                    console.error('Error parsing server response:', error);
                    console.log('Server response:', this.responseText);
                }
            };
            xhr.send('function=savePost&postid=' + encodeURIComponent(postid) + '&postContent=' + encodeURIComponent(postContent));
        } else {
            console.error('User is not the owner of the post');
        }
    });
}


