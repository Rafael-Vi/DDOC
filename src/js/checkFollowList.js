function closeFollow(follow) {
    document.getElementById('postContent').value = '';
    var captionElement = document.getElementById('caption');
    var postIdElement = document.getElementById('postId');
    captionElement.textContent = "Caption";
    postContent.placeholder = 'Altera para outra legenda...';
    postIdElement.value = ''; // Clear the postId input

    switch (follow) {
        case 'following':
            document.getElementById('postFollowing').close();
            break;
        case 'follower':
            document.getElementById('postFollower').close();
            break;
    }
}
function showFollow(follow) {
    switch (follow) {
        case 'following':
            postFollowing.showModal();
            break;
        case 'follower':
            postFollower.showModal();
            break;
    }
}
