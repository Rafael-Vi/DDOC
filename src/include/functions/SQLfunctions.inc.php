<?php
//!----------------------------------------------------------------------------------------
    @session_start();
    require_once "echohtml.inc.php";
    require_once "paths.inc.php";

//* AJAX HANDLING
//*-----------------------------------------------------------------------------------------
if (isset($_POST['function'])) {
    switch ($_POST['function']) {
        case 'getSearchStuff':
            if (isset($_POST['value'])) {
                $value = $_POST['value'];
                $uid = $_SESSION['uid'];
                getSearchStuff($value, $uid);
            }
            break;
        case 'followCheck':
            if (isset($_POST['userid'])) {
                $userid = $_POST['userid'];
                $currentSessionUser = $_SESSION['uid'];
                echo followCheck($userid, $currentSessionUser);
            }
        case 'followCheckLoad':
            if (isset($_POST['userid'])) {
                $userid = $_POST['userid'];
                $currentSessionUser = $_SESSION['uid'];
                echo followCheckLoad($userid, $currentSessionUser);
            }
            break;
        case 'getFollowCounts':
            if (isset($_POST['userid'])) {
                $userid = $_POST['userid'];
                if ($userid == "") {
                    if (isset($_SESSION['uid'])) {
                        $userid = $_SESSION['uid'];
                    } else {
                        die('Session variable "uid" is not set');
                    }
                }
                echo json_encode(getFollowCounts($userid));
            }
            break;
        case 'likeCheck':
            if (isset($_POST['postid'])) {
                $postid = $_POST['postid'];
                $currentSessionUser = $_SESSION['uid'];
                $response = likeCheck($postid, $currentSessionUser);
                error_log("likeCheck response: " . $response); // Add this line
                echo $response;
            }
            break;
        case 'likeCheckLoad':
            if (isset($_POST['postid'])) {
                $postid = $_POST['postid'];
                $currentSessionUser =  $_SESSION['uid'];
                $response = likeCheckLoad($postid, $currentSessionUser);
                error_log("likeCheck response: " . $response); // Add this line
                echo $response;
            }
            break;
        case 'likeCount':
            if (isset($_POST['postid'])) {
                $postid = $_POST['postid'];
                $response = likeCount($postid);
                error_log("likeCount response: " . $response); // Add this line
                echo $response;
            }
            break;
        case 'deleteNotifications':
            if (isset($_POST['id'])) {
                $id = $_POST['id'];
                deleteNotif($id);
            }
            break;
        case 'loadNotifications':
            if (isset($_SESSION['uid'])) {
                $response = getNotif();
                error_log("loadUserNotifications response: " . $response);
                echo $response;
            }
            break;
        case 'checkIfitsOwner':
            if (isset($_POST['postid'])) {
                $postID = $_POST['postid'];
                $currentSessionUser = $_SESSION['uid'];
                echo CheckIfOwnerPost($postID, $currentSessionUser);
            }
            break;
        case 'deletePost':
            if (isset($_POST['postid'])) {
                $postID = $_POST['postid'];
                deletePost($postID);
            }
            break;
        case 'savePost':
            if (isset($_POST['postid']) & isset($_POST['postContent'])) {
                $postid = $_POST['postid'];
                $postContent = $_POST['postContent'];
                savePost($postid, $postContent);
            }
            break;
    }
}
//*-----------------------------------------------------------------------------------------
//*-----------------------------------------------------------------------------------------

function db_connect() {
    global $arrConfig;

    
    $conn = mysqli_connect($arrConfig['connect_DB'][0], $arrConfig['connect_DB'][1], $arrConfig['connect_DB'][2], $arrConfig['connect_DB'][3]);

    if (!$conn) {
        die("Error connecting to MySQL Server: " . mysqli_connect_error());
    }
    return $conn;
}
//! Need to make
//!-----------------------------------------------------------------------------------------

    



    function getDef($userID){

    }

    function getConvo(){
    }
    function sendMessage(){
    }

    function getHome(){
    }

//! Need to make
//!-----------------------------------------------------------------------------------------

    function savePost($postID, $postContent) {
        // Start the database connection
        $dbConn = db_connect();

        // Check connection
        if ($dbConn === false) {
            die("ERROR: Could not connect. " . mysqli_connect_error());
        }

        // Prepare the SQL query with the post_id condition using prepared statements
        $sql = "UPDATE posts SET caption = ? WHERE post_id = ?";
        $stmt = mysqli_prepare($dbConn, $sql);

        // Bind parameters
        mysqli_stmt_bind_param($stmt, "si", $postContent, $postID);

        // Execute the query
        if(mysqli_stmt_execute($stmt) === false) {
            die("ERROR: Could not execute query: $sql. " . mysqli_error($dbConn));
        }

        // Check if the post was updated
        $wasUpdated = mysqli_stmt_affected_rows($stmt) > 0;

        // Close the database connection
        mysqli_stmt_close($stmt);
        mysqli_close($dbConn);

        // Return a JSON object
        echo json_encode(array('success' => $wasUpdated, 'postID' => $postID));
    }

    function checkIfOwnerPost($postID, $currentSessionUser) {
    // Start the database connection
    $dbConn = db_connect();

    // Check connection
    if ($dbConn === false) {
        die("ERROR: Could not connect. " . mysqli_connect_error());
    }

    // Prepare the SQL query with the post_id condition using prepared statements
    $sql = "SELECT user_id FROM posts WHERE post_id = ?";
    $stmt = mysqli_prepare($dbConn, $sql);

    // Bind parameters
    mysqli_stmt_bind_param($stmt, "i", $postID);

    // Execute the query
    if(mysqli_stmt_execute($stmt) === false) {
        die("ERROR: Could not execute query: $sql. " . mysqli_error($dbConn));
    }

    // Bind result variables
    mysqli_stmt_bind_result($stmt, $userID);


    // Fetch the result
    if (mysqli_stmt_fetch($stmt)) {
        // Check if the current session user is the owner of the post
        $isOwner = $userID == $currentSessionUser;
    } else {
        $userID = null;
        $isOwner = false;
    }

    // Close the database connection
    mysqli_stmt_close($stmt);
    mysqli_close($dbConn);

    // Return a JSON object

    echo json_encode(array('isOwner' => $isOwner, 'userID' => $userID, 'postID' => $postID));


    }

    function sendNotification($receiverId, $senderId, $type) {
        global $arrConfig;
        $dbConn = db_connect(); 
        if ($dbConn === false) {
            return "ERROR: Could not connect. " . mysqli_connect_error();
        }

        // Fetch the username based on the senderId
        $sql = "SELECT user_name FROM users WHERE user_id = ?";
        $stmt = mysqli_prepare($dbConn, $sql);
        if ($stmt === false) {
            return "ERROR: Could not prepare query: $sql. " . mysqli_error($dbConn);
        }
        mysqli_stmt_bind_param($stmt, "i", $senderId);
        if (mysqli_stmt_execute($stmt) === false) {
            return "ERROR: Could not execute query: $sql. " . mysqli_error($dbConn);
        }
        mysqli_stmt_bind_result($stmt, $senderUsername);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);

        $message = '';
        switch ($type) {
            case 'PostCreated':
                $message = 'A new post has been created by ' . $senderUsername;
                // Get the followers of the user who created the post
                $sql = "SELECT follower_id FROM follow WHERE followee_id = ?";
                $stmt = mysqli_prepare($dbConn, $sql);
                if ($stmt === false) {
                    return "ERROR: Could not prepare query: $sql. " . mysqli_error($dbConn);
                }
                mysqli_stmt_bind_param($stmt, "i", $senderId);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
                // Insert a notification for each follower
                $sql = "INSERT INTO notifications (message, date_sent, receiver_id) VALUES (?, NOW(), ?)";
                $stmt = mysqli_prepare($dbConn, $sql);
                if ($stmt === false) {
                    return "ERROR: Could not prepare query: $sql. " . mysqli_error($dbConn);
                }
                while ($row = mysqli_fetch_assoc($result)) {
                    mysqli_stmt_bind_param($stmt, "si", $message, $row['follower_id']);
                    mysqli_stmt_execute($stmt);
                }
                break;
            case 'PostLiked':
                $message = 'User ' . $senderUsername . ' liked your post';
                 // Prepare the SQL query
        $sql = "INSERT INTO notifications (message, date_sent, receiver_id) VALUES (?, NOW(), ?)";
        $stmt = mysqli_prepare($dbConn, $sql);
        if ($stmt === false) {
        return "ERROR: Could not prepare query: $sql. " . mysqli_error($dbConn);
        }

        // Bind parameters
        mysqli_stmt_bind_param($stmt, "si", $message, $receiverId);

        // Execute the query
        if(mysqli_stmt_execute($stmt) === false) {
        return "ERROR: Could not execute query: $sql. " . mysqli_error($dbConn);
        }
                break;
            case 'UserFollowed':
                $message = 'User ' . $senderUsername . ' started following you';
                 // Prepare the SQL query
        $sql = "INSERT INTO notifications (message, date_sent, receiver_id) VALUES (?, NOW(), ?)";
        $stmt = mysqli_prepare($dbConn, $sql);
        if ($stmt === false) {
        return "ERROR: Could not prepare query: $sql. " . mysqli_error($dbConn);
        }

        // Bind parameters
        mysqli_stmt_bind_param($stmt, "si", $message, $receiverId);

        // Execute the query
        if(mysqli_stmt_execute($stmt) === false) {
        return "ERROR: Could not execute query: $sql. " . mysqli_error($dbConn);
        }
                break;
            case 'YourRank':
                $rankData = getPodium($receiverId);
                if ($rankData !== null) {
                    $message = $rankData['username'] . ', your current rank is ' . $rankData['rank'];
                }
                 // Prepare the SQL query
        $sql = "INSERT INTO notifications (message, date_sent, receiver_id) VALUES (?, NOW(), ?)";
        $stmt = mysqli_prepare($dbConn, $sql);
        if ($stmt === false) {
        return "ERROR: Could not prepare query: $sql. " . mysqli_error($dbConn);
        }

        // Bind parameters
        mysqli_stmt_bind_param($stmt, "si", $message, $receiverId);

        // Execute the query
        if(mysqli_stmt_execute($stmt) === false) {
        return "ERROR: Could not execute query: $sql. " . mysqli_error($dbConn);
        }
                break;
            default:
                return "ERROR: Invalid notification type.";
        }
       

        mysqli_stmt_close($stmt);
        mysqli_close($dbConn);

        return true;
    }
    function getRankingPost($theme_id, $type){
        // Create a connection to the database
        $dbConn = db_connect();
        if ($dbConn === false) {
            die("ERROR: Could not connect. " . mysqli_connect_error());
        }
    
        // Define the SQL query
        if (($theme_id && $type) && $theme_id != 'none' && $type != 'none') {
            $sql = "SELECT * FROM rankingpoststype WHERE theme_id = ? AND TYPE = ?";
            $stmt = mysqli_prepare($dbConn, $sql);
            mysqli_stmt_bind_param($stmt, 'is', $theme_id, $type);
        } elseif ($theme_id && $theme_id != 'none') {
            $sql = "SELECT * FROM rankingposts WHERE theme_id = ?";
            $stmt = mysqli_prepare($dbConn, $sql);
            mysqli_stmt_bind_param($stmt, 'i', $theme_id);
        } elseif ($type && $type != 'none') {
            $sql = "SELECT * FROM rankingpostsotype WHERE TYPE = ?";
            $stmt = mysqli_prepare($dbConn, $sql);
            mysqli_stmt_bind_param($stmt, 's', $type);
        } else {
            $sql = "SELECT * FROM rankingpostsall";
            $stmt = mysqli_prepare($dbConn, $sql);
        }
    
        // Execute the query
        if (mysqli_stmt_execute($stmt) === false) {
            die("ERROR: Could not execute query: $sql. " . mysqli_error($dbConn));
        }
    
        // Get the result set
        $result = mysqli_stmt_get_result($stmt);
    
        // Fetch all rows as an associative array
        $rows = mysqli_fetch_all($result, MYSQLI_ASSOC);
        // Echo the posts
        foreach($rows as $row) {
            echoRankPosts($row['PostRank'], $row['PostImage'], $row['NameOfThePost'], $row['TYPE'], $row['Likes'], $row['PersonWhoPostedIt']);
        }
    
        // Close the statement and the connection
        mysqli_stmt_close($stmt);
        mysqli_close($dbConn);
    }
    function setSelectedType($selectedType) {
        $types = ['none', 'video', 'image', 'audio'];

        foreach ($types as $type) {
            $selected = ($type == $selectedType) ? 'selected' : '';
            echo "<option value='$type' $selected>" . ucfirst($type) . "</option>";
        }
    }
    function newUser($dbConn, $email, $username, $password){
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Prepare the SQL query to insert into the table using prepared statements
        $insertSql = "INSERT INTO users (user_email, user_name, user_password) VALUES (?, ?, ?)";
        $stmt = $dbConn->prepare($insertSql);

        // Bind parameters
        $stmt->bind_param("sss", $email, $username, $hashedPassword);

        // Execute the query
        $insertResult = $stmt->execute();

        if ($insertResult) {
            validRegisterAl();
        } else {
            // Handle the insert error
            echo "Error: " . $stmt->error;
        }
        // Close the statement and the database connection
        mysqli_stmt_close($stmt);
        mysqli_close($dbConn);
    }
    function updateUser($uid, $username, $realName, $profilePic, $biography) {
        global $arrConfig;
        $dbConn = db_connect();
        
        // Construct the update query based on the provided values
        $updateSql = "UPDATE users SET";
        $updateFields = array();
        $profilePicName = "ProfilePic-" . $profilePic['name'] . "-" . $_SESSION['uid'];
        $fileExtension = pathinfo($profilePic['name'], PATHINFO_EXTENSION);
        $profilePicName .= "." . $fileExtension;
        
        if (!empty($username)) {
            $updateFields[] = " user_name = ?";
        }
        
        if (!empty($realName)) {
            $updateFields[] = " user_realName = ?";
        }
        
        if (!empty($profilePic)) {
            var_dump($profilePic);
            move_uploaded_file($profilePic['tmp_name'], $arrConfig['dir_users'].$profilePicName);
            $updateFields[] = " user_profilePic = ?";
        }
        
        if (!empty($biography)) {
            $updateFields[] = " user_biography = ?";
        }
        
        // Check if any fields need to be updated
        if (!empty($updateFields)) {
            $updateSql .= implode(",", $updateFields);
            $updateSql .= " WHERE user_id = ?";
        
            $stmt = mysqli_prepare($dbConn, $updateSql);
            mysqli_stmt_bind_param($stmt, "ssssi", $username, $realName, $profilePicName, $biography, $uid);
        
            $updateResult = mysqli_stmt_execute($stmt);
        
            if ($updateResult) {
                // Handle the update success
                updateSuccess();    
            } else {
                // Handle the update error
                $error = mysqli_stmt_error($stmt);
                mySQLerror($error);
            }
        
            mysqli_stmt_close($stmt);
        }
        
        // Close the database connection
        mysqli_close($dbConn);
    }
    function updateUserPostStatus($userId) {
        $dbConn = db_connect(); // Assuming db_connect() is a function that returns a database connection
        // Prepare the SQL query to update the table
        $sql = "UPDATE users SET can_post = 1 WHERE user_id = ?";
    
        // Prepare the statement
        $stmt = $dbConn->prepare($sql);
    
        // Bind the parameter
        $stmt->bind_param("i", $userId);
    
        // Execute the query
        if ($stmt->execute()) {
            // Handle the successful update
            echo "User can now post.";
        } else {
            // Handle the update error
            echo "Error updating user: " . $stmt->error;
        }
    
        // Close the statement and the database connection
        $stmt->close();
        mysqli_close($dbConn);
    }
    function getCanPostStatus($userId) {
        $dbConn = db_connect(); 

        // Prepare the SQL query to select the can_post value
        $sql = "SELECT can_post FROM users WHERE user_id = ?";

        // Prepare the statement
        $stmt = $dbConn->prepare($sql);

        // Bind the parameter
        $stmt->bind_param("i", $userId);

        // Execute the query
        $stmt->execute();

        // Bind the result to a variable
        $stmt->bind_result($canPost);

        // Fetch the result
        $stmt->fetch();

        // Close the statement and the database connection
        $stmt->close();
        mysqli_close($dbConn);
        $_SESSION['can_post'] = $canPost;
    }
    function createPost($uid, $title, $type, $file, $theme) {
    global $arrConfig;
    $dbConn = db_connect();

    $fileName = $type . "-" . $file['name'] . "-" . $_SESSION['uid'];
    $fileExtension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $fileName .= "." . $fileExtension;

    // Get the MIME type of the file
    $fileType = mime_content_type($file['tmp_name']);

    // Define the expected MIME types for each extension
    $expectedMimeTypes = [
        'mp3' => 'audio/mpeg',
        'jpeg' => 'image/jpeg',
        'jpg' => 'image/jpeg',
        'png' => 'image/png',
        'gif' => 'image/gif',
        'mp4' => 'video/mp4',
        // Add more extensions and MIME types as needed
    ];

    // Check if the extension is known and the MIME type matches the expected MIME type
    if (!isset($expectedMimeTypes[$fileExtension]) || $fileType !== $expectedMimeTypes[$fileExtension]) {
        die('File type and extension do not match.');
    }
    
        // Prepare the SQL query to insert into the table using prepared statements
        $sql = "INSERT INTO posts (user_id, caption, post_type, post_url, theme_id) VALUES (?, ?, ?, ?, ?)";
        $stmt = $dbConn->prepare($sql);
    
        $stmt->bind_param("isssi", $uid, $title, $type, $fileName, $theme); // bind parameters
    
        // Move the uploaded file
        if (!move_uploaded_file($file['tmp_name'],  $arrConfig['dir_posts']."/$type/".$fileName)) {
            die('Error uploading file - check destination is writeable. '.$type.'');
        }
    
        // Execute the query
        if($stmt->execute() === false) {
            die("Error: " . $stmt->error);
        }
    
        if ($stmt) {
            // Handle the successful post creation
            echo "Post created successfully.";
            //updateUserPostStatus($_SESSION['uid']);
            sendNotification(null, $_SESSION['uid'], "PostCreated");
        } else {
            // Handle the post creation error
            die("Error: " . $stmt->error);
        }
    
        if ($file['error'] > 0) {
            die('File upload error: ' . $file['error']);
        }
    }
    function showPost($postId, $show) {
        // Start the database connection
        $dbConn = db_connect();
    
        // Check connection
        if ($dbConn === false) {
            die("ERROR: Could not connect. " . mysqli_connect_error());
        }
    
        // Prepare the SQL query with the post_id condition using prepared statements
        $sql = "SELECT p.post_id, p.post_type, p.post_url, p.caption, p.created_at, p.updated_at, p.user_id, p.theme_id, t.theme, r.PostRank
        FROM posts p 
        LEFT JOIN theme t ON p.theme_id = t.theme_id 
        LEFT JOIN rankingposts r ON p.post_id = r.PostId AND p.theme_id = r.theme_id
        WHERE p.post_id = ?";
        $stmt = mysqli_prepare($dbConn, $sql);
    
        // Bind parameters
        mysqli_stmt_bind_param($stmt, "i", $postId);
    
        // Execute the query
        if(mysqli_stmt_execute($stmt) === false) {
            die("ERROR: Could not execute query: $sql. " . mysqli_error($dbConn));
        }
    
        // Bind result variables
        mysqli_stmt_bind_result($stmt, $post_id, $post_type, $post_url, $caption, $created_at, $updated_at, $user_id, $theme_id, $theme_name, $rank);
    
        // Fetch the result
        if (mysqli_stmt_fetch($stmt)) {
            $post = array(
                'post_id' => $post_id,
                'post_type' => $post_type,
                'post_url' => $post_url,
                'caption' => $caption,
                'created_at' => $created_at,
                'updated_at' => $updated_at,
                'user_id' => $user_id,
                'theme_id' => $theme_id,
                'theme_name' => $theme_name,
                'rank' => $rank
            );
            // Display the post if $show is not 'no'
            if ($show !== 'no') {
                echoShowPost($post);
            }
    
        } else {
            return false;
        }
    
        // Close the database connection
        mysqli_stmt_close($stmt);
        mysqli_close($dbConn);
        return $post;
    }
    function getSearchStuff($value,$uid){
        global $arrConfig;
        $dbConn = db_connect(); 
        if ($dbConn === false) {
            die("ERROR: Could not connect. " . mysqli_connect_error());
        }
        $sql = "SELECT user_id, user_name, user_profilePic FROM users WHERE user_id != ? AND user_name LIKE ?";
        $stmt = mysqli_prepare($dbConn, $sql);
        
        // Bind parameters
        // Add percent signs around the value to define wildcards before and after the pattern
        $value = "%" . $value . "%";
        mysqli_stmt_bind_param($stmt, "is", $uid, $value);
        
        // Execute the query
        if(mysqli_stmt_execute($stmt) === false) {
            die("ERROR: Could not execute query: $sql. " . mysqli_error($dbConn));
        }
        
        // Bind result variables
        mysqli_stmt_bind_result($stmt, $userId, $username, $profilePic);
        
        // Fetch the user data
        while(mysqli_stmt_fetch($stmt)) {
            // Access the user data
            if (!$profilePic) {
                $profilePic = 'https://th.bing.com/th/id/R.3e77a1db6bb25f0feb27c95e05a7bc57?rik=DswMYVRRQEHbjQ&riu=http%3a%2f%2fwww.coalitionrc.com%2fwp-content%2fuploads%2f2017%2f01%2fplaceholder.jpg&ehk=AbGRPPcgHhziWn1sygs8UIL6XIb1HLfHjgPyljdQrDY%3d&risl=&pid=ImgRaw&r=00';
            }
            else{
                if ($arrConfig !== null && isset($arrConfig['url_users'])) {
                    $profilePic = $arrConfig['url_users']. $profilePic;
                }
            }
            echoSearchResults($userId, $username, $profilePic);
        }
        
        // Close the database connection
        mysqli_stmt_close($stmt);
        mysqli_close($dbConn);

    }
    function getThemes($all){
        global $arrConfig;
    
        $dbConn = db_connect();
        if ($dbConn === false) {
            die("ERROR: Could not connect. " . mysqli_connect_error());
        }
    
        if ($all) {
            $sql = "SELECT * FROM theme";
        } else {
            $sql = "SELECT * FROM theme WHERE is_finished = 0";
        }
        $stmt = mysqli_prepare($dbConn, $sql);
    
        // Execute the query
        if(mysqli_stmt_execute($stmt) === false) {
            die("ERROR: Could not execute query: $sql. " . mysqli_error($dbConn));
        }
    
        // Bind result variables
        mysqli_stmt_bind_result($stmt, $theme_id, $theme, $finish_date, $is_finished);
    
        // Fetch the theme data
        $themes = array();
        while(mysqli_stmt_fetch($stmt)) {
            $themes[] = array(
                'theme_id' => $theme_id,
                'theme' => $theme,
                'finish_date' => $finish_date,
                'is_finished' => $is_finished
            );
        }
    
        mysqli_stmt_close($stmt);
        mysqli_close($dbConn);
    
        if (!$all) {
            $_SESSION['themes'] = $themes;
            $_SESSION['theme_id'] = $themes;
        }
        else{
        echo '<option value="none"' . (empty($GLOBALS['theme_id']) ? ' selected' : '') . '>None</option>';
        foreach ($themes as $theme) {
            $selected = ($theme['theme_id'] == $GLOBALS['theme_id']) ? 'selected' : '';
            echo '<option value="' . $theme['theme_id'] . '" ' . $selected . '>' . $theme['theme'] . '</option>';
        }
        }

    }
    function getPosts($uid) {
    // Start the database connection
        $dbConn = db_connect();

        // Check connection
        if ($dbConn === false) {
            die("ERROR: Could not connect. " . mysqli_connect_error());
        }

        // Prepare the SQL query with the user_id condition using prepared statements
        $sql = "SELECT post_id, post_type, post_url, caption, created_at, updated_at FROM posts WHERE user_id = ?";
        $stmt = mysqli_prepare($dbConn, $sql);

        // Bind parameters
        mysqli_stmt_bind_param($stmt, "i", $uid);

        // Execute the query
        if(mysqli_stmt_execute($stmt) === false) {
            die("ERROR: Could not execute query: $sql. " . mysqli_error($dbConn));
        }

        // Bind result variables
        mysqli_stmt_bind_result($stmt, $post_id, $post_type, $post_url, $caption, $created_at, $updated_at);

        // Create an array to hold the post variables
        $posts = array();

        // Fetch each row of the result set and store it in the $posts array
        while (mysqli_stmt_fetch($stmt)) {
            $posts[] = array(
                'post_id' => $post_id,
                'post_type' => $post_type,
                'post_url' => $post_url,
                'caption' => $caption,
                'created_at' => $created_at,
                'updated_at' => $updated_at
            );
        }

        // Check if there are no results
        if (empty($posts)) {
            echoNoPosts();
        } else {
            // Loop through each post and echo the variables
            echo '<div id="post-destroyer" style="display: grid; grid-template-columns: repeat(3, 1fr);">';
            foreach ($posts as $post) {
                echoUserPosts($post);
            }
            echo '</div>';
        }

        // Close the database connection
        mysqli_stmt_close($stmt);
        mysqli_close($dbConn);
    }
    function updatePost(){
    }
    function deletePost($postID) {
    // Start the database connection
    $dbConn = db_connect();

    // Check connection
    if ($dbConn === false) {
        die("ERROR: Could not connect. " . mysqli_connect_error());
    }

    // Prepare the SQL query to delete the post based on post_id using prepared statements
    $stmt = $dbConn->prepare("DELETE FROM posts WHERE post_id = ?");

    // Bind parameters
    $stmt->bind_param("i", $postID);

    // Execute the query
    if ($stmt->execute()) {
        echo "Post with ID $postID deleted successfully.";
    } else {
        echo "Failed to delete post with ID $postID.";
    }

    // Close statement and connection
    mysqli_stmt_close($stmt);
    mysqli_close($dbConn);
    }
//*DONE
function deleteNotif($notifID) {
    // Start the database connection
    $dbConn = db_connect();

    // Check connection
    if ($dbConn === false) {
        die("ERROR: Could not connect. " . mysqli_connect_error());
    }

    // Prepare the SQL query to delete the notification based on notification_id using prepared statements
    $stmt = $dbConn->prepare("DELETE FROM notifications WHERE id = ?");
    
    // Bind parameters
    $stmt->bind_param("i", $notifID);

    // Execute the query
    if ($stmt->execute()) {
        echo "Notification with ID $notifID deleted successfully.";
    } else {
        echo "Failed to delete notification with ID $notifID.";
    }

    // Close statement and connection
    mysqli_stmt_close($stmt);
    mysqli_close($dbConn);

}
function getNotif(){
    $receiverId = $_SESSION['uid'];

    global $arrConfig;
    $dbConn = db_connect(); 
    if ($dbConn === false) {
        return "ERROR: Could not connect. " . mysqli_connect_error();
    }

    // Prepare the SQL query
    $sql = "SELECT * FROM notifications WHERE receiver_id = ?";
    $stmt = mysqli_prepare($dbConn, $sql);
    if ($stmt === false) {
        return "ERROR: Could not prepare query: $sql. " . mysqli_error($dbConn);
    }

    // Bind parameters
    mysqli_stmt_bind_param($stmt, "i", $receiverId);

    // Execute the query
    if(mysqli_stmt_execute($stmt) === false) {
        return "ERROR: Could not execute query: $sql. " . mysqli_error($dbConn);
    }

    // Bind result variables
    $result = mysqli_stmt_get_result($stmt);
    // Fetch all notifications and echo them
    if(mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            echoNotif($row);
        }
    } else {
        echo'<div class="flex flex-col  items-center justify-center h-full">
        <h2 class="text-3xl font-bold bg-gray-800 rounded-lg p-8 text-white">
        No notifications found.
        </h2>
        </div>';
    }

    mysqli_stmt_close($stmt);
    mysqli_close($dbConn);
}
function getUserInfo($uid){
    global $arrConfig;
    
    // Start the database connection
    $dbConn = db_connect();

    // Check connection
    if ($dbConn === false) {
        die("ERROR: Could not connect. " . mysqli_connect_error());
    }

    // Prepare the SQL query with the user_id condition using prepared statements
    $sql = "SELECT user_name, user_email, user_profilePic, user_realName, user_biography FROM users WHERE user_id = ?";
    $stmt = mysqli_prepare($dbConn, $sql);

    // Bind parameters
    mysqli_stmt_bind_param($stmt, "i", $uid);

    // Execute the query
    if(mysqli_stmt_execute($stmt) === false) {
        die("ERROR: Could not execute query: $sql. " . mysqli_error($dbConn));
    }

    // Bind result variables
    mysqli_stmt_bind_result($stmt, $username, $email, $profilePic, $realName, $biography);

    // Fetch the user data
    if(mysqli_stmt_fetch($stmt)) {
        // Access the user data
        if (!$profilePic) {
            $profilePic = 'https://th.bing.com/th/id/R.3e77a1db6bb25f0feb27c95e05a7bc57?rik=DswMYVRRQEHbjQ&riu=http%3a%2f%2fwww.coalitionrc.com%2fwp-content%2fuploads%2f2017%2f01%2fplaceholder.jpg&ehk=AbGRPPcgHhziWn1sygs8UIL6XIb1HLfHjgPyljdQrDY%3d&risl=&pid=ImgRaw&r=00';
        }
        else{
            $profilePic = $arrConfig['url_users']. $profilePic ;
        }
        $_SESSION['imageProfile'] = $profilePic;
        $_SESSION['username'] = "@$username";
        // Close the database connection
        mysqli_stmt_close($stmt);
        // Prepare the SQL query to get the rank from the accountrankings view
        $sqlRank = "SELECT UserRank FROM `accountrankings` WHERE `UserName` = ?";
        $stmtRank = mysqli_prepare($dbConn, $sqlRank);

        // Bind parameters
        mysqli_stmt_bind_param($stmtRank, "s", $username);

        // Execute the query
        if(mysqli_stmt_execute($stmtRank) === false) {
            die("ERROR: Could not execute query: $sqlRank. " . mysqli_error($dbConn));
        }

        // Bind result variables
        mysqli_stmt_bind_result($stmtRank, $rank);

        // Fetch the rank
        if(mysqli_stmt_fetch($stmtRank)) {
            $_SESSION['rank'] = $rank;
        } else {
            echo "No rank found for this user.";
        }
        
         mysqli_stmt_close($stmtRank);
        echoProfileInfo($username, $email, $profilePic, $realName, $biography, $rank);


        // Close the statement


    } else {
        // Handle the query error
        echo "No user found with this ID.";
    }

    mysqli_close($dbConn);

    
}
function likeCheck($postid, $currentSessionUser){
    // Start the database connection
    $dbConn = db_connect();

    if ($dbConn === false) {
        die("ERROR: Could not connect. " . mysqli_connect_error());
    }

    // Prepare the SQL query to check if the current user has liked the post with the given id
    $sql = "SELECT * FROM likes WHERE user_id = ? AND post_id = ?";
    $stmt = mysqli_prepare($dbConn, $sql);

    // Check if the statement was prepared successfully
    if ($stmt === false) {
        die("ERROR: Could not prepare query: $sql. " . mysqli_error($dbConn));
    }

    // Bind parameters
    mysqli_stmt_bind_param($stmt, "ii", $currentSessionUser, $postid);

    // Execute the query
    if(mysqli_stmt_execute($stmt) === false) {
        die("ERROR: Could not execute query: $sql. " . mysqli_error($dbConn));
    }

    // Store the result
    mysqli_stmt_store_result($stmt);

    // If the post is not liked by the user, insert a like
    if (mysqli_stmt_num_rows($stmt) > 0) {
        $sql = "DELETE FROM likes WHERE user_id = ? AND post_id = ?";
        $stmt = mysqli_prepare($dbConn, $sql);
        if ($stmt === false) {
            die("ERROR: Could not prepare query: $sql. " . mysqli_error($dbConn));
        }
    
        mysqli_stmt_bind_param($stmt, "ii", $currentSessionUser, $postid);
    
        if(mysqli_stmt_execute($stmt) === false) {
            die("ERROR: Could not execute query: $sql. " . mysqli_error($dbConn));
        }

        mysqli_stmt_close($stmt);
        mysqli_close($dbConn);
        return "like";
    } else {
        $sql = "INSERT INTO likes (user_id, post_id) VALUES (?, ?)";
        $stmt = mysqli_prepare($dbConn, $sql);
        if ($stmt === false) {
            die("ERROR: Could not prepare query: $sql. " . mysqli_error($dbConn));
        }
    
        mysqli_stmt_bind_param($stmt, "ii", $currentSessionUser, $postid);
    
        if(mysqli_stmt_execute($stmt) === false) {
            die("ERROR: Could not execute query: $sql. " . mysqli_error($dbConn));
        }

        mysqli_stmt_close($stmt);
        mysqli_close($dbConn);
        $postData = showPost($postid, 'no');
        $receiverId = $postData['user_id'];
        sendNotification($receiverId, $currentSessionUser, "PostLiked");
        return "liked";
    }
}

function likeCheckLoad($postid, $currentSessionUser){
    // Start the database connection
    $dbConn = db_connect();

    if ($dbConn === false) {
        die("ERROR: Could not connect. " . mysqli_connect_error());
    }

    // Prepare the SQL query to check if the current user has liked the post with the given id
    $sql = "SELECT * FROM likes WHERE user_id = ? AND post_id = ?";
    $stmt = mysqli_prepare($dbConn, $sql);

    // Check if the statement was prepared successfully
    if ($stmt === false) {
        die("ERROR: Could not prepare query: $sql. " . mysqli_error($dbConn));
    }

    // Bind parameters
    mysqli_stmt_bind_param($stmt, "ii", $currentSessionUser, $postid);

    // Execute the query
    if(mysqli_stmt_execute($stmt) === false) {
        die("ERROR: Could not execute query: $sql. " . mysqli_error($dbConn));
    }

    // Store the result
    mysqli_stmt_store_result($stmt);

    // If the post is liked by the user, return "liked", else return "like"
    if (mysqli_stmt_num_rows($stmt) > 0) {
        mysqli_stmt_close($stmt);
        mysqli_close($dbConn);
        return "liked";
    } else {
        mysqli_stmt_close($stmt);
        mysqli_close($dbConn);
        return "like";
    }
}
function RankingAcc($type = null){
    // Create a connection to the database
    $dbConn = db_connect();
    if ($dbConn === false) {
        die("ERROR: Could not connect. " . mysqli_connect_error());
    }

    // Define the SQL query
    $sql = "SELECT * FROM accountrankings";

    // Prepare the SQL statement
    $stmt = mysqli_prepare($dbConn, $sql);

    // Execute the query
    if (mysqli_stmt_execute($stmt) === false) {
        die("ERROR: Could not execute query: $sql. " . mysqli_error($dbConn));
    }

    // Get the result set
    $result = mysqli_stmt_get_result($stmt);

    // Fetch all rows as an associative array
    $rows = mysqli_fetch_all($result, MYSQLI_ASSOC);

    // Close the statement and the connection
    mysqli_stmt_close($stmt);
    mysqli_close($dbConn);

    // Echo the posts
    foreach($rows as $row) {
        echoRankAcc($row['UserRank'], $row['TotalLikes'], $row['UserName'] , $row['UserImage']);
    }
}
function getPodium($rank, $table, $themeId = null, $type = null){
    // Create a connection to the database
    $dbConn = db_connect();
    if ($dbConn === false) {
        die("ERROR: Could not connect. " . mysqli_connect_error());
    }

    // Define the SQL query based on the table
    if ($table == 'AccRank') {
        if ($type == 'none' || $type == null) {
            $sql = "SELECT UserName, UserImage FROM accountrankings WHERE UserRank = ? LIMIT 1";
        } else {
            $sql = "SELECT UserName, UserImage FROM accountrankingstype WHERE UserRank = ? AND PostType = ? LIMIT 1";
        }
    } else if ($table == 'PostRank') {
        $sql = "SELECT NameOfThePost FROM rankingposts WHERE PostRank = ? AND theme_id = ? AND TYPE = ? LIMIT 1";
    } else {
        die("ERROR: Invalid table name.");
    }

    // Prepare the SQL statement
    $stmt = mysqli_prepare($dbConn, $sql);

    // Bind parameters
    if ($table == 'AccRank') {
        if ($type == 'none' || $type == null) {
            mysqli_stmt_bind_param($stmt, "i", $rank);
        } else {
            mysqli_stmt_bind_param($stmt, "is", $rank, $type);
        }
    } else if ($table == 'PostRank') {
    mysqli_stmt_bind_param($stmt, "iis", $rank, $themeId, $type);
    }

    // Execute the query
    if (mysqli_stmt_execute($stmt) === false) {
        die("ERROR: Could not execute query: $sql. " . mysqli_error($dbConn));
    }

    // Bind result variables and fetch the data based on the table
    if ($table == 'AccRank') {
        mysqli_stmt_bind_result($stmt, $userName, $userImage);
        if(mysqli_stmt_fetch($stmt)) {
            // Return the user data
            return array('username' => $userName, 'image' => $userImage);
        }
    } else if ($table == 'PostRank') {
        mysqli_stmt_bind_result($stmt, $postName);
        if(mysqli_stmt_fetch($stmt)) {
            // Return the post name
            return array('NameOfThePost' => $postName);
        }
    }

    return null;
}
function likeCount($postid){
    $dbConn = db_connect();
    if ($dbConn === false) {
        error_log("ERROR: Could not connect. " . mysqli_connect_error());
        return "ERROR: Could not connect. " . mysqli_connect_error();
    }

    $sql = "SELECT COUNT(*) FROM likes WHERE post_id = ?";
    $stmt = mysqli_prepare($dbConn, $sql);

    if ($stmt === false) {
        error_log("ERROR: Could not prepare query: $sql. " . mysqli_error($dbConn));
        return "ERROR: Could not prepare query: $sql. " . mysqli_error($dbConn);
    }

    mysqli_stmt_bind_param($stmt, "i", $postid);

    if(mysqli_stmt_execute($stmt) === false) {
        error_log("ERROR: Could not execute query: $sql. " . mysqli_error($dbConn));
        return "ERROR: Could not execute query: $sql. " . mysqli_error($dbConn);
    }
    mysqli_stmt_bind_result($stmt, $likeCount);
    mysqli_stmt_fetch($stmt);

    mysqli_stmt_close($stmt);
    mysqli_close($dbConn);

    return $likeCount;
}

function followCheck($userid, $currentSessionUser){
    global $arrConfig;
    // Start the database connection
    $dbConn = db_connect();

    if ($dbConn === false) {
        die("ERROR: Could not connect. " . mysqli_connect_error());
    }

    // Prepare the SQL query to check if the current user is already following the other user
    $sql = "SELECT * FROM follow WHERE follower_id = ? AND followee_id = ?";
    $stmt = mysqli_prepare($dbConn, $sql);

    // Check if the statement was prepared successfully
    if ($stmt === false) {
        die("ERROR: Could not prepare query: $sql. " . mysqli_error($dbConn));
    }

    // Bind parameters
    mysqli_stmt_bind_param($stmt, "ii", $currentSessionUser, $userid);

    // Execute the query
    if(mysqli_stmt_execute($stmt) === false) {
        die("ERROR: Could not execute query: $sql. " . mysqli_error($dbConn));
    }

    // Store the result
    mysqli_stmt_store_result($stmt);

    // Check if the current user is already following the other user
    if(mysqli_stmt_num_rows($stmt) > 0) {
        // The current user is already following the other user
        // Prepare the SQL query to delete the follow record
        $sql = "DELETE FROM follow WHERE follower_id = ? AND followee_id = ?";
        $stmt = mysqli_prepare($dbConn, $sql);

        // Bind parameters
        mysqli_stmt_bind_param($stmt, "ii", $currentSessionUser, $userid);

        // Execute the query
        if(mysqli_stmt_execute($stmt) === false) {
            die("ERROR: Could not execute query: $sql. " . mysqli_error($dbConn));
        }

        mysqli_stmt_close($stmt);
        mysqli_close($dbConn);
    } else {
        // The current user is not following the other user, so insert a new row into the follows table
        $sql = "INSERT INTO follow (follower_id, followee_id) VALUES (?, ?)";
        $stmt = mysqli_prepare($dbConn, $sql);

        // Bind parameters
        mysqli_stmt_bind_param($stmt, "ii", $currentSessionUser, $userid);

        // Execute the query
        if(mysqli_stmt_execute($stmt) === false) {
            die("ERROR: Could not execute query: $sql. " . mysqli_error($dbConn));
        }

        // Close the database connection
        mysqli_stmt_close($stmt);
        mysqli_close($dbConn);

        sendNotification($userid, $currentSessionUser, "UserFollowed");

    }
}
function followCheckLoad($userid, $currentSessionUser){
global $arrConfig;
// Start the database connection
$dbConn = db_connect();

if ($dbConn === false) {
    die("ERROR: Could not connect. " . mysqli_connect_error());
}

// Prepare the SQL query to check if the current user is already following the other user
$sql = "SELECT * FROM follow WHERE follower_id = ? AND followee_id = ?";
$stmt = mysqli_prepare($dbConn, $sql);

// Check if the statement was prepared successfully
if ($stmt === false) {
    die("ERROR: Could not prepare query: $sql. " . mysqli_error($dbConn));
}

// Bind parameters
mysqli_stmt_bind_param($stmt, "ii", $currentSessionUser, $userid);

// Execute the query
if(mysqli_stmt_execute($stmt) === false) {
    die("ERROR: Could not execute query: $sql. " . mysqli_error($dbConn));
}

// Store the result
mysqli_stmt_store_result($stmt);

// Check if the current user is already following the other user
if(mysqli_stmt_num_rows($stmt) > 0) {
    mysqli_stmt_close($stmt);
    mysqli_close($dbConn);
    return "following";
} else {
    mysqli_stmt_close($stmt);
    mysqli_close($dbConn);
    return "follow";
}
}
function getFollowCounts($userid){
    // Start the database connection
    $dbConn = db_connect();

    if ($dbConn === false) {
        die("ERROR: Could not connect. " . mysqli_connect_error());
    }

    // Prepare the SQL query to get the number of followers
    $sql = "SELECT COUNT(*) FROM follow WHERE followee_id = ?";
    $stmt = mysqli_prepare($dbConn, $sql);

    // Check if the statement was prepared successfully
    if ($stmt === false) {
        die("ERROR: Could not prepare query: $sql. " . mysqli_error($dbConn));
    }

    // Bind parameters
    mysqli_stmt_bind_param($stmt, "i", $userid);

    // Execute the query
    if(mysqli_stmt_execute($stmt) === false) {
        die("ERROR: Could not execute query: $sql. " . mysqli_error($dbConn));
    }

    // Store the result
    mysqli_stmt_bind_result($stmt, $followersCount);

    // Fetch the result
    mysqli_stmt_fetch($stmt);

    // Close the statement
    mysqli_stmt_close($stmt);

    // Prepare the SQL query to get the number of following
    $sql = "SELECT COUNT(*) FROM follow WHERE follower_id = ?";
    $stmt = mysqli_prepare($dbConn, $sql);

    // Bind parameters
    mysqli_stmt_bind_param($stmt, "i", $userid);

    // Execute the query
    if(mysqli_stmt_execute($stmt) === false) {
        die("ERROR: Could not execute query: $sql. " . mysqli_error($dbConn));
    }

    // Store the result
    mysqli_stmt_bind_result($stmt, $followingCount);

    // Fetch the result
    mysqli_stmt_fetch($stmt);

    // Close the database connection
    mysqli_stmt_close($stmt);
    mysqli_close($dbConn);

    // Return the followers and following counts
    return array('followers' => $followersCount, 'following' => $followingCount);
}

function getFollowers($userid){
    global $arrConfig;
    // Start the database connection
    $dbConn = db_connect();

    if ($dbConn === false) {
        die("ERROR: Could not connect. " . mysqli_connect_error());
    }

    // Prepare the SQL query to get the followers
    $sql = "SELECT users.* FROM follow JOIN users ON follow.follower_id = users.user_id WHERE follow.followee_id = ?";
    $stmt = mysqli_prepare($dbConn, $sql);

    // Check if the statement was prepared successfully
    if ($stmt === false) {
        die("ERROR: Could not prepare query: $sql. " . mysqli_error($dbConn));
    }

    // Bind parameters
    mysqli_stmt_bind_param($stmt, "i", $userid);

    // Execute the query
    if(mysqli_stmt_execute($stmt) === false) {
        die("ERROR: Could not execute query: $sql. " . mysqli_error($dbConn));
    }

    // Get the result
    $result = mysqli_stmt_get_result($stmt);

    $followers = [];
    // Fetch the result
    while($row = mysqli_fetch_assoc($result)) {
        $followers[] = $row;
    }

    // Close the database connection
    mysqli_stmt_close($stmt);
    mysqli_close($dbConn);

    // Loop through the followers and echo their information
    foreach ($followers as $follower) {
        if ($follower['user_profilePic'] !== null) {
            $follower['user_profilePic'] = $arrConfig['url_users'] . $follower['user_profilePic'];
        }
        echoSearchResults($follower['user_id'], $follower['user_name'], $follower['user_profilePic']);
    }
}

function getFollowing($userid){
    global $arrConfig;
    // Start the database connection
    $dbConn = db_connect();

    if ($dbConn === false) {
        die("ERROR: Could not connect. " . mysqli_connect_error());
    }

    // Prepare the SQL query to get the following
    $sql = "SELECT users.* FROM follow JOIN users ON follow.followee_id = users.user_id WHERE follow.follower_id = ?";
    $stmt = mysqli_prepare($dbConn, $sql);

    // Check if the statement was prepared successfully
    if ($stmt === false) {
        die("ERROR: Could not prepare query: $sql. " . mysqli_error($dbConn));
    }

    // Bind parameters
    mysqli_stmt_bind_param($stmt, "i", $userid);

    // Execute the query
    if(mysqli_stmt_execute($stmt) === false) {
        die("ERROR: Could not execute query: $sql. " . mysqli_error($dbConn));
    }

    // Get the result
    $result = mysqli_stmt_get_result($stmt);

    $following = [];
    // Fetch the result
    while($row = mysqli_fetch_assoc($result)) {
        $following[] = $row;
    }

    // Close the database connection
    mysqli_stmt_close($stmt);
    mysqli_close($dbConn);

    // Return the following
    // Loop through the following and echo their information
    foreach ($following as $followee) {
        if ($followee['user_profilePic'] !== null) {
            $followee['user_profilePic'] = $arrConfig['url_users'] . $followee['user_profilePic'];
        }
        else{
            $followee['user_profilePic'] = 'https://th.bing.com/th/id/R.3e77a1db6bb25f0feb27c95e05a7bc57?rik=DswMYVRRQEHbjQ&riu=http%3a%2f%2fwww.coalitionrc.com%2fwp-content%2fuploads%2f2017%2f01%2fplaceholder.jpg&ehk=AbGRPPcgHhziWn1sygs8UIL6XIb1HLfHjgPyljdQrDY%3d&risl=&pid=ImgRaw&r=00';
        }
        echoSearchResults($followee['user_id'], $followee['user_name'], $followee['user_profilePic']);
    }
}

//*-----------------------------------------------------------------------------------------