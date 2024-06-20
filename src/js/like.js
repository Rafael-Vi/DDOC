function likeCheck(postid) {
    var xhr = new XMLHttpRequest();
    xhr.open('POST', '../src/include/functions/SQLfunctions.inc.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload = function() {
        var response = this.responseText;
        if (this.status == 200) {
            // Change the text of the like button based on the response
            var likeButton = document.getElementById('like-button-'+postid);
            if (this.responseText.trim() === 'like') {
                likeButton.textContent = 'Gosto';
            } else if (this.responseText.trim() === 'liked') {
                likeButton.textContent = 'Gostou';
            }
        }
        getLikeCounts(postid);
    };
    xhr.send('function=likeCheck&postid=' + encodeURIComponent(postid));
};

function likeCheckOnLoad(postid) {
    var xhr = new XMLHttpRequest();
    xhr.open('POST', '../src/include/functions/SQLfunctions.inc.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload = function() {
        var response = this.responseText;
        if (this.status == 200) {
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
    var xhr = new XMLHttpRequest();
    xhr.open('POST', '../src/include/functions/SQLfunctions.inc.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload = function() {
        try {
            if (this.status == 200) {
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
        // Get the post id from the element id
        const postId = post.id.split('-')[1];
        // Get the like counts for this post
        likeCheckOnLoad(postId);
        getLikeCounts(postId);
    });
});

function openReport(reportId, reportName) {
    document.getElementById('Post-Name').textContent = "Porque quer reportar: '" + reportName + "'?";
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

function saveReport() {
    var reportIdElement = document.getElementById('reportId');
    var reportId = atob(reportIdElement.value); // Decode the report ID
    var reportReasonRaw = $('#post-reason').val(); // Get the raw reason for the report without trimming
    var reportReason = reportReasonRaw.trim(); // Trim whitespace for validation
    var reportType = $('#postType').val(); // Get the selected report type

    // Check if the trimmed report reason is empty or if the report type is not selected
    if (reportReason === "" || reportType === "") {
        alert('Please fill in all required fields.');
        return; // Exit the function if any field is empty
    }

    var confirmation = confirm('Are you sure you want to submit this report?');
    if (!confirmation) {
        return; // Exit the function if the user cancels
    }

    // Proceed with AJAX request to submit the report using the raw, untrimmed report reason
    $.ajax({
        type: 'POST',
        url: '../src/include/functions/SQLfunctions.inc.php',
        data: {
            function: 'saveReport', // Specify the function to call on the server
            reportId: reportId, // Pass the decoded report ID
            reportReason: reportReasonRaw, // Pass the raw, untrimmed reason for the report
            reportType: reportType // Pass the report type
        },
        success: function(response) {
            try {
                var data = JSON.parse(response);
                if (data.success) {
                    console.log('Report submitted successfully');
                    location.reload(); // Reload the page to reflect changes
                } else {
                    console.error('Error submitting report:', data.error);
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