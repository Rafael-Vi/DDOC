// Corrected version of the like and report functionalities with improvements

function likeCheck(postid) {
    console.log('Like button clicked');
    var xhr = new XMLHttpRequest();
    xhr.open('POST', '/src/include/functions/SQLfunctions.inc.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload = function() {
        if (this.status == 200) {
            var likeButton = document.getElementById('like-button-'+postid);
            if (this.responseText.trim() === 'like') {
                likeButton.textContent = 'Gosto';
            } else if (this.responseText.trim() === 'liked') {
                likeButton.textContent = 'Gostou';
            }
            getLikeCounts(postid);
        }
    };
    xhr.send('function=likeCheck&postid=' + encodeURIComponent(postid));
};

function likeCheckOnLoad(postid) {
    console.log('Page loaded');
    var xhr = new XMLHttpRequest();
    xhr.open('POST', '/src/include/functions/SQLfunctions.inc.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload = function() {
        if (this.status == 200) {
            var likeButton = document.getElementById('like-button-'+postid);
            if (this.responseText.trim() === 'like') {
                likeButton.textContent = 'Gosto';
            } else if (this.responseText.trim() === 'liked') {
                likeButton.textContent = 'Gostou';
            }
        }
    };
    xhr.send('function=likeCheckLoad&postid=' + encodeURIComponent(postid));
};

function getLikeCounts(postid) {
    console.log('Getting like counts');
    var xhr = new XMLHttpRequest();
    xhr.open('POST', '/src/include/functions/SQLfunctions.inc.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload = function() {
        if (this.status == 200) {
            console.log('Response from server:', this.responseText);
            document.getElementById('like-count-'+postid).textContent = 'Gostos: ' + this.responseText.trim();
        }
    };
    xhr.send('function=likeCount&postid=' + encodeURIComponent(postid));
};

window.addEventListener('load', function() {
    const posts = document.querySelectorAll('[id^="post-"]');
    posts.forEach(post => {
        const postId = post.id.split('-')[1];
        likeCheckOnLoad(postId);
        getLikeCounts(postId);
    });
});

function openReport(reportId, reportName) {
    console.log('Opening report for:', reportName);
    document.getElementById('Post-Name').textContent = "Porque quer reportar: '" + reportName + "'?";
    document.getElementById('reportId').value = btoa(reportId.toString());
    document.getElementById('postReport').showModal();
}

function cancelReport() {
    document.getElementById('post-reason').value = '';
    document.getElementById('postType').selectedIndex = 0;
    document.getElementById('reportId').value = '';
    document.getElementById('postReport').close();
}

function saveReport() {
    var reportId = atob(document.getElementById('reportId').value);
    var reportReason = $('#post-reason').val().trim();
    var reportType = $('#postType').val();

    if (reportReason === "" || reportType === "") {
        alert('Please fill in all required fields.');
        return;
    }

    if (!confirm('Are you sure you want to submit this report?')) {
        return;
    }

    $.ajax({
        type: 'POST',
        url: '/src/include/functions/SQLfunctions.inc.php',
        data: {
            function: 'saveReport',
            reportId: reportId,
            reportReason: reportReason,
            reportType: reportType
        },
        success: function(response) {
            try {
                var data = JSON.parse(response);
                if (data.success) {
                    console.log('Report submitted successfully');
                    location.reload();
                } else {
                    console.error('Error submitting report:', data.error);
                }
            } catch (error) {
                console.error('Error parsing server response:', error);
            }
        },
        error: function(xhr, status, error) {
            console.error('Error in AJAX request:', error);
        }
    });
};