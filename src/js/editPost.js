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

function showModal(postid) {

 document.getElementById('postEdit').showModal();
}


/*

function save(postid, postContent) {
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
function deletePost(postid) {
    checkIfOwner(postid, function(isOwner) {
        if (isOwner) {
            // Delete the post
            // ...
        } else {
            console.error('User is not the owner of the post');
        }
    });
}

function checkIfOwner(postid, callback) {
    var xhr = new XMLHttpRequest();
    xhr.open('POST', '../src/include/functions/SQLfunctions.inc.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload = function() {
        try {
            if (this.status == 200) {
                var response = JSON.parse(this.responseText);
                callback(response.isOwner);
            }
        } catch (error) {
            console.error('Error parsing server response:', error);
            console.log('Server response:', this.responseText);
        }
    };
    xhr.send('function=checkIfitsOwner&postid=' + encodeURIComponent(postid));
}

*/