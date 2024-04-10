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
    }
}
//*-----------------------------------------------------------------------------------------
//*-----------------------------------------------------------------------------------------

function db_connect() {
    $conn = mysqli_connect("localhost", "root", "", "ddoc");

    if (!$conn) {
        die("Error connecting to MySQL Server: " . mysqli_connect_error());
    }
    return $conn;
}
//! Need to make
//!-----------------------------------------------------------------------------------------
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
    
    function getDef($userID){

    }

    function getConvo(){
    }
    function sendMessage(){
    }

//! Need to make
//!-----------------------------------------------------------------------------------------

//? Further Improve
//?-----------------------------------------------------------------------------------------

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
                break;
            case 'PostLiked':
                $message = 'User ' . $senderUsername . ' liked your post';
                break;
            case 'UserFollowed':
                $message = 'User ' . $senderUsername . ' started following you';
                break;
            case 'YourRank':
                $rankData = getPodium($receiverId);
                if ($rankData !== null) {
                    $message = $rankData['username'] . ', your current rank is ' . $rankData['rank'];
                }
                break;
            default:
                return "ERROR: Invalid notification type.";
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

        mysqli_stmt_close($stmt);
        mysqli_close($dbConn);

        return true;
    }
    function getRankingPost($theme_id){
        // Create a connection to the database
        $dbConn = db_connect();
        if ($dbConn === false) {
            die("ERROR: Could not connect. " . mysqli_connect_error());
        }
    
    
        // Define the SQL query
        $sql = "SELECT * FROM rankingposts where theme_id = ?";

        // Prepare the SQL statement
        $stmt = mysqli_prepare($dbConn, $sql);

        // Bind the theme_id parameter to the SQL statement
        mysqli_stmt_bind_param($stmt, 'i', $theme_id);

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
    function createPost($uid, $title, $type, $file, $theme) {
        global $arrConfig;
        $dbConn = db_connect();
    
        $fileName = $type . "-" . $file['name'] . "-" . $_SESSION['uid'];
        $fileExtension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $fileName .= "." . $fileExtension;
    
        // Prepare the SQL query to insert into the table using prepared statements
        $sql = "INSERT INTO posts (user_id, caption, post_type, post_url, theme_id) VALUES (?, ?, ?, ?, ?)";
        $stmt = $dbConn->prepare($sql);
    
        $stmt->bind_param("isssi", $uid, $title, $type, $fileName, $theme); // bind parameters
    
        move_uploaded_file($file['tmp_name'],  $arrConfig['dir_posts']."/$type/".$fileName);
    
        // Execute the query
        if($stmt->execute() === false) {
            die("Error: " . $stmt->error);
        }
    
        if ($stmt) {
            // Handle the successful post creation
            echo "Post created successfully.";
        } else {
            // Handle the post creation error
            die("Error: " . $stmt->error);
        }
    
        mysqli_stmt_close($stmt);
        mysqli_close($dbConn);
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
            echo '<div style="display: grid; grid-template-columns: repeat(3, 1fr);">';
            foreach ($posts as $post) {
                echoUserPosts($post);
            }
            echo '</div>';
        }

        // Close the database connection
        mysqli_stmt_close($stmt);
        mysqli_close($dbConn);
    }

    function showPost($postId, $show) {
        // Start the database connection
        $dbConn = db_connect();
    
        // Check connection
        if ($dbConn === false) {
            die("ERROR: Could not connect. " . mysqli_connect_error());
        }
    
        // Prepare the SQL query with the post_id condition using prepared statements
        $sql = "SELECT p.post_id, p.post_type, p.post_url, p.caption, p.created_at, p.updated_at, p.user_id, p.theme_id, t.theme 
                FROM posts p 
                LEFT JOIN theme t ON p.theme_id = t.theme_id 
                WHERE p.post_id = ?";
        $stmt = mysqli_prepare($dbConn, $sql);
    
        // Bind parameters
        mysqli_stmt_bind_param($stmt, "i", $postId);
    
        // Execute the query
        if(mysqli_stmt_execute($stmt) === false) {
            die("ERROR: Could not execute query: $sql. " . mysqli_error($dbConn));
        }
    
        // Bind result variables
        mysqli_stmt_bind_result($stmt, $post_id, $post_type, $post_url, $caption, $created_at, $updated_at, $user_id, $theme_id, $theme_name);
    
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
                'theme_name' => $theme_name
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
        }
        else{

        foreach ($themes as $theme) {
            $selected = ($theme['theme_id'] == $GLOBALS['theme_id']) ? 'selected' : '';
            echo '<option value="' . $theme['theme_id'] . '" ' . $selected . '>' . $theme['theme'] . '</option>';
        }
        }

    }
//? Further Improve
//?-----------------------------------------------------------------------------------------


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
        echo '<h1 class="ubuntu-bold">No notifications</h1>';
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
function RankingAcc(){
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
function getPodium($rank, $table, $themeId = null){
    // Create a connection to the database
    $dbConn = db_connect();
    if ($dbConn === false) {
        die("ERROR: Could not connect. " . mysqli_connect_error());
    }

    // Define the SQL query based on the table
    if ($table == 'AccRank') {
        $sql = "SELECT UserName, UserImage FROM accountrankings WHERE UserRank = ? LIMIT 1";
    } else if ($table == 'PostRank') {
        $sql = "SELECT NameOfThePost FROM rankingposts WHERE PostRank = ? AND theme_id = ? LIMIT 1";
    } else {
        die("ERROR: Invalid table name.");
    }

    // Prepare the SQL statement
    $stmt = mysqli_prepare($dbConn, $sql);

    // Bind parameters
    if ($table == 'AccRank') {
        mysqli_stmt_bind_param($stmt, "i", $rank);
    } else if ($table == 'PostRank') {
        mysqli_stmt_bind_param($stmt, "ii", $rank, $themeId);
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
        return "follow";
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

        return "following";
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
//*-----------------------------------------------------------------------------------------