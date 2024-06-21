//*DONE
//*------------------------------------------------------------------
function followCheck() {
    console.log('Follow button clicked');
    // Assuming the user ID is now part of the URL path, similar to checkPage
    var userid = "";
    var pathSegments = window.location.pathname.split('/');
    if (window.location.pathname.includes('/perfil-de-outro/')) {
        userid = pathSegments[pathSegments.length - 1];
    }
    console.log('UserID:', userid); // Debugging: Log the UserID

    $.post('/src/include/functions/SQLfunctions.inc.php', { function: 'followCheck', userid: userid }, function(response) {
        console.log('Response from server:', response); // Debugging: Log the response
        var trimmedResponse = $.trim(response);
        var followButton = $('#follow-button');
        if (trimmedResponse === 'follow') {
            followButton.text('Seguir');
        } else if (trimmedResponse === 'following') {
            followButton.text('A seguir');
        }
    }).fail(function(jqXHR, textStatus, errorThrown) {
        console.error('Error in followCheck:', textStatus, errorThrown); // Error handling
    });
}

function followCheckLoad() {
    var userid = new URLSearchParams(window.location.search).get('userid');
    $.post('/src/include/functions/SQLfunctions.inc.php', { function: 'followCheckLoad', userid: userid }, function(response) {
        console.log('Response from server:', response);
        var trimmedResponse = $.trim(response);
        var followButton = $('#follow-button');
        if (trimmedResponse === 'follow') {
            followButton.text('Seguir');
        } else if (trimmedResponse === 'following') {
            followButton.text('A seguir');
        }
        checkPage();
    });
}

function getFollowCounts(userid) {
    console.log('Getting follow counts for UserID:', userid);
    $.post('/src/include/functions/SQLfunctions.inc.php', { function: 'getFollowCounts', userid: userid }, function(response) {
        console.log('Response from server:', response);
        try {
            var data = JSON.parse(response);
            console.log('Parsed Data:', data); // Additional logging
            $('#followers-count').text('Seguidores: ' + data.followers);
            $('#following-count').text('A seguir: ' + data.following);
        } catch (e) {
            console.error('Error parsing JSON:', e);
        }
    }).fail(function(jqXHR, textStatus, errorThrown) {
        console.error('AJAX Error in getFollowCounts:', textStatus, errorThrown);
    });
}

function checkPage() {
    var userid = "";
    var pathSegments = window.location.pathname.split('/');
    // Assuming the user ID is always the last segment of the URL
    if (window.location.pathname.includes('/perfil-de-outro/')) {
        userid = pathSegments[pathSegments.length - 1];
    }
    getFollowCounts(userid);
}

$(document).ready(function() {
    checkPage();
    followCheckLoad();
});

//*------------------------------------------------------------------
//*------------------------------------------------------------------