function likeCheck(postid) {
    console.log('Like button clicked');
    var xhr = new XMLHttpRequest();
    xhr.open('POST', '../src/include/functions/SQLfunctions.inc.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload = function() {
        var response = this.responseText;
        if (this.status == 200) {
            console.log('Response from server:', this.responseText);
            console.log('Trimmed response:', this.responseText.trim());
            // Change the text of the like button based on the response
            var likeButton = document.getElementById('like-button-'+postid);
            if (this.responseText.trim() === 'like') {
                likeButton.textContent = 'Gosto';
            } else if (this.responseText.trim() === 'liked') {
                likeButton.textContent = 'Gostado';
            }
        }
        getLikeCounts(postid);
    };
    xhr.send('function=likeCheck&postid=' + encodeURIComponent(postid));
};
function likeCheckOnLoad(postid) {
    console.log('Page loaded');
    var xhr = new XMLHttpRequest();
    xhr.open('POST', '../src/include/functions/SQLfunctions.inc.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload = function() {
        var response = this.responseText;
        if (this.status == 200) {
            console.log('Response from server:', this.responseText);
            console.log('Trimmed response:', this.responseText.trim());
            // Change the text of the like button based on the response
            var likeButton = document.getElementById('like-button-'+postid);
            if (this.responseText.trim() === 'like') {
                likeButton.textContent = 'Gosto';
            } else if (this.responseText.trim() === 'liked') {
                likeButton.textContent = 'Gostado';
            }
        }
    };
    xhr.send('function=likeCheckLoad&postid=' + encodeURIComponent(postid));
};
function getLikeCounts(postid) {
    console.log('Getting like counts');
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
                // Update the likes count on the page
                document.getElementById('like-count-'+postid).textContent = 'Gostos: ' + response.trim();
            }
        } catch (error) {
            console.error('Error parsing server response:', error);
            console.log('Server response:', this.responseText);
        }
    };
    xhr.send('function=likeCount&postid=' + encodeURIComponent(postid));
};
window.addEventListener('load', function() {

    // Get all post elements
    const posts = document.querySelectorAll('[id^="post-"]');
    // Loop over each post
    posts.forEach(post => {
        console.log('Post:', post);
        // Get the post id from the element id
        const postId = post.id.split('-')[1];
        // Get the like counts for this post
        likeCheckOnLoad(postId);
        getLikeCounts(postId);
    });
});