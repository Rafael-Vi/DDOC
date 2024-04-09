//*DONE
//*------------------------------------------------------------------
function followCheck() {
    console.log('Follow button clicked');
    var xhr = new XMLHttpRequest();
    var urlParams = new URLSearchParams(window.location.search);
    var userid = urlParams.get('userid'); // get the userid from the URL
    xhr.open('POST', '../src/include/functions/SQLfunctions.inc.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload = function() {
        if (this.status == 200) {
            console.log('Response from server:', this.responseText);
            console.log('Trimmed response:', this.responseText.trim());
            // Change the text of the follow button based on the response
            var followButton = document.getElementById('follow-button');
            if (this.responseText.trim() === 'follow') {
                followButton.textContent = 'Follow';
            } else if (this.responseText.trim() === 'following') {
                followButton.textContent = 'Following';
            }
            checkPage();
        }
    };
    xhr.send('function=followCheck&userid=' + encodeURIComponent(userid));
};

function followCheckLoad() {
    console.log('Follow button clicked');
    var xhr = new XMLHttpRequest();
    var urlParams = new URLSearchParams(window.location.search);
    var userid = urlParams.get('userid'); // get the userid from the URL
    xhr.open('POST', '../src/include/functions/SQLfunctions.inc.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload = function() {
        if (this.status == 200) {
            console.log('Response from server:', this.responseText);
            console.log('Trimmed response:', this.responseText.trim());
            // Change the text of the follow button based on the response
            var followButton = document.getElementById('follow-button');
            if (this.responseText.trim() === 'follow') {
                followButton.textContent = 'Follow';
            } else if (this.responseText.trim() === 'following') {
                followButton.textContent = 'Following';
            }
            checkPage();
        }
    };
    xhr.send('function=followCheckLoad&userid=' + encodeURIComponent(userid));
};

function getFollowCounts(userid) {
    console.log('Getting follow counts');
    var xhr = new XMLHttpRequest();
    var urlParams = new URLSearchParams(window.location.search);
    xhr.open('POST', '../src/include/functions/SQLfunctions.inc.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload = function() {
        console.log('entered');
        if (this.status == 200) {
            console.log('Response from server:', this.responseText);
            // Parse the response
            var response = JSON.parse(this.responseText);
            // Update the followers and following counts on the page
            document.getElementById('followers-count').textContent = 'Seguidores: ' + response.followers;
            document.getElementById('following-count').textContent = 'A seguir: ' + response.following;
        }
    };
    xhr.send('function=getFollowCounts&userid=' + encodeURIComponent(userid));
};

function checkPage(){
    var urlParams = new URLSearchParams(window.location.search);
    var userid = "";
    if (window.location.pathname.endsWith('OProfile.php')) {
        userid = urlParams.get('userid'); // get the userid from the URL
    } 
    getFollowCounts(userid);
}

window.addEventListener('load', function() {
    checkPage();
    followCheckLoad();
});

//*------------------------------------------------------------------
//*------------------------------------------------------------------