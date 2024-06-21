//*DONE
//*------------------------------------------------------------------
function followCheck() {
    console.log('Follow button clicked');
    var userid = new URLSearchParams(window.location.search).get('userid');
    $.post('/src/include/functions/SQLfunctions.inc.php', { function: 'followCheck', userid: userid }, function(response) {
        checkPage();
        console.log('Response from server:', response);
        var trimmedResponse = $.trim(response);
        var followButton = $('#follow-button');
        if (trimmedResponse === 'follow') {
            followButton.text('Follow');
        } else if (trimmedResponse === 'following') {
            followButton.text('Following');
        }
    });
}

function followCheckLoad() {
    var userid = new URLSearchParams(window.location.search).get('userid');
    $.post('/src/include/functions/SQLfunctions.inc.php', { function: 'followCheckLoad', userid: userid }, function(response) {
        console.log('Response from server:', response);
        var trimmedResponse = $.trim(response);
        var followButton = $('#follow-button');
        if (trimmedResponse === 'follow') {
            followButton.text('Follow');
        } else if (trimmedResponse === 'following') {
            followButton.text('Following');
        }
        checkPage();
    });
}

function getFollowCounts(userid) {
    console.log('Getting follow counts');
    $.post('/src/include/functions/SQLfunctions.inc.php', { function: 'getFollowCounts', userid: userid }, function(response) {
        console.log('Response from server:', response);
        var data = JSON.parse(response);
        $('#followers-count').text('Seguidores: ' + data.followers);
        $('#following-count').text('A seguir: ' + data.following);
    });
}

function checkPage() {
    var userid = "";
    if (window.location.pathname.endsWith('OProfile.php')) {
        userid = new URLSearchParams(window.location.search).get('userid');
    }
    getFollowCounts(userid);
}

$(document).ready(function() {
    checkPage();
    followCheckLoad();
});

//*------------------------------------------------------------------
//*------------------------------------------------------------------