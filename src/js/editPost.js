$(document).ready(function() {
    $('.post-container').hover(
        function() { // Mouseover
            $(this).find('.edit-post, .caption-label, .new-button').css('visibility', 'visible');
        }, 
        function() { // Mouseout
            $(this).find('.edit-post, .caption-label, .new-button').css('visibility', 'hidden');
        }
    );

    function cancel() {
        $('#postContent').val('');
        $('#caption').text("Caption");
        $('#postContent').attr('placeholder', 'Altera para outra legenda...');
        $('#postId').val('');
        $('#postEdit').modal('hide'); // Assuming Bootstrap modal or similar
    }

    function showModal(postid, caption) {
        $('#caption').text(caption);
        $('#postContent').attr('placeholder', `Altera "${caption}" para outra legenda...`);
        $('#postId').val(btoa(postid));
        $('#postEdit').modal('show'); // Assuming Bootstrap modal or similar
    }

    function checkIfOwner(postid, callback) {
        $.ajax({
            type: 'POST',
            url: '../src/include/functions/SQLfunctions.inc.php',
            data: {function: 'checkIfitsOwner', postid: postid},
            success: function(response) {
                try {
                    var data = JSON.parse(response);
                    if (!data.isOwner) {
                        console.error('User is not the owner of the post');
                        console.log('userID:', data.userID);
                    }
                    callback(data.isOwner);
                } catch (error) {
                    console.error('Error parsing server response:', error);
                    console.log('Server response:', response);
                }
            },
            error: function(xhr, status, error) {
                console.error('Error in AJAX request:', error);
            }
        });
    }

    function deletePost() {
        var postid = atob($('#postId').val());
        var userConfirmed = window.confirm('Are you sure you want to delete this post?');
        if (!userConfirmed) {
            return;
        }
        checkIfOwner(postid, function(isOwner) {
            if (isOwner) {
                $.ajax({
                    type: 'POST',
                    url: '../src/include/functions/SQLfunctions.inc.php',
                    data: {function: 'deletePost', postid: postid},
                    success: function(response) {
                        try {
                            var data = JSON.parse(response);
                            if (data.success) {
                                console.log('Post was deleted successfully');
                                location.reload(); // Reload the page to update the list of posts
                            } else {
                                console.error('There was an error deleting the post');
                            }
                        } catch (error) {
                            console.error('Error parsing server response:', error);
                            console.log('Server response:', response);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error in AJAX request:', error);
                    }
                });
            } else {
                console.error('User is not the owner of the post');
            }
        });
    }

    function save() {
        var postid = atob($('#postId').val());
        var postContent = $('#postContent').val();
        var confirmation = confirm('Are you sure you want to save this post?');
        if (!confirmation) {
            return;
        }
        checkIfOwner(postid, function(isOwner) {
            if (isOwner) {
                $.ajax({
                    type: 'POST',
                    url: '../src/include/functions/SQLfunctions.inc.php',
                    data: {function: 'savePost', postid: postid, postContent: postContent},
                    success: function(response) {
                        try {
                            var data = JSON.parse(response);
                            if (data.success) {
                                console.log('Post saved successfully');
                                location.reload(); // Reload the page to update the list of posts
                            } else {
                                console.error('Error saving post:', data.error);
                            }
                        } catch (error) {
                            console.error('Error parsing server response:', error);
                            console.log('Server response:', response);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error in AJAX request:', error);
                    }
                });
            } else {
                console.error('User is not the owner of the post');
            }
        });
    }
});