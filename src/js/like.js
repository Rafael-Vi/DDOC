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

function openReport(reportId, reportName) {
    console.log('Type of reportId:', typeof reportId); // Debugging: Check the type
    console.log('Value of reportId:', reportId); // Debugging: Check the value

    document.getElementById('Post-Name').textContent = "Porque quer reportar: " + reportName;

    // Directly encode reportId without checking its type
    document.getElementById('reportId').value = btoa(reportId.toString()); // Update ID to postId

    // Show the modal
    document.getElementById('postReport').showModal(); // Update modal ID to postReport
}

function cancelReport() {
    // Clear the reason for reporting
    document.getElementById('post-reason').value = '';
    // Reset the type select to its default state
    document.getElementById('postType').selectedIndex = 0;
    // Optionally clear the reportId if needed
    document.getElementById('reportId').value = '';
    // Close the modal
    document.getElementById('postReport').close();
}
