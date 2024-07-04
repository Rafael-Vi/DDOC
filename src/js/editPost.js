function cancel() {
    $('#postContent').val('');
    $('#caption').text("Caption");
    $('#postContent').attr('placeholder', 'Altera para outra legenda...');
    $('#postId').val('');
    document.getElementById('postEdit').close();
}

function showMyModal(postid, caption) {
    console.log('Type of postid:', typeof postid); // Debugging: Check the type
    console.log('Value of postid:', postid); // Debugging: Check the value

    document.getElementById('caption').textContent = caption;
    document.getElementById('postContent').setAttribute('placeholder', `Altera "${caption}" para outra legenda...`);
    console.log('postid:', postid);

    // Ensure postid is a string or number before encoding
    if (typeof postid === 'string' || typeof postid === 'number') {
        document.getElementById('postId').value = btoa(postid.toString());
    } else {
        console.error('Invalid postid type:', typeof postid);
        // Handle the error appropriately
    }

    document.getElementById('postEdit').showModal();
}



function deletePost() {
    var postIdElement = document.getElementById('postId');
    var postid = atob(postIdElement.value);
    console.log('postid:', postid);
    var userConfirmed = window.confirm('Tem a certeza que quer apagar este post?');
    if (!userConfirmed) {
        return;
    }
    // Directly proceed with AJAX request without checking if owner
    $.ajax({
        type: 'POST',
        url: '/src/include/functions/SQLfunctions.inc.php',
        data: {function: 'deletePost', postid: postid},
        dataType: 'json', // Automatically parse JSON response
        success: function(data, textStatus, xhr) {
            // No need to check Content-Type or parse JSON manually
            if (data.success) {
                console.log('Post was deleted successfully');
                location.reload(); // Reload the page after successful deletion
            } else {
                console.error('There was an error deleting the post');
            }
        },
        error: function(xhr, status, error) {
            console.error('AJAX error:', status, error);
        }
    });
}

function save() {
    var postIdElement = document.getElementById('postId');
    var postid = atob(postIdElement.value);
    var postContent = $('#postContent').val();
    var confirmation = confirm('Tem a certeza que quer salvar?');
    if (!confirmation) {
        return;
    }
    // Directly proceed with AJAX request without checking if owner
    $.ajax({
        type: 'POST',
        url: '/src/include/functions/SQLfunctions.inc.php',
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
}
$(document).ready(function() {
    $(document).on('mouseenter', '.post-container', function() {
        $(this).find('.edit-post, .caption-label, .new-button').css('visibility', 'visible');
    }).on('mouseleave', '.post-container', function() {
        $(this).find('.edit-post, .caption-label, .new-button').css('visibility', 'hidden');
    });

    $(document).on('click', '.edit-post', function() {
        var onclickAttr = $(this).attr('onclick');
        var match = onclickAttr.match(/showMyModal\(\'(?:.*?)\', \'(.*?)\'/);
        if (match && match[1]) {
            var caption = match[1]; // Extracted caption from the onclick attribute
            showMyModal(postId, caption); // Call function with postId and caption
        } else {
            console.error('Caption extraction failed');
        }
    });
});