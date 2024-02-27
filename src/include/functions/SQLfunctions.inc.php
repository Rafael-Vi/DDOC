<?php
    //!----------------------------------------------------------------------------------------
    //!----------------------------------------------------------------------------------------
    require_once "echohtml.inc.php";
    global $arrConfig;

    if($_SERVER['HTTP_HOST'] == 'localhost') {
        error_reporting(E_ALL);
    } else {
        error_reporting(0);
    }

    // acessos FrontOffice
    $arrConfig['url_site']='http://localhost/DDOC';
    $arrConfig['dir_site'] = "C:\\wamp64\\www\\DDOC";

    // caminhos Docs e/ou fotografias
    $arrConfig['dir_posts'] = $arrConfig['dir_site'].'/upload/posts/';
    $arrConfig['url_posts'] = $arrConfig['url_site'].'/upload/posts/';
    $arrConfig['dir_users'] = $arrConfig['dir_site'].'/upload/users/';
    $arrConfig['url_users'] = $arrConfig['url_site'].'/upload/users/';
    $arrConfig['fotos_auth'] = array ('image/jpeg', 'image/jpg', 'image/png', 'image/gif');
    $arrConfig['fotos_maxUpload'] = 3000000;

    // caminhos Ficheiros
    $arrConfig['files_auth'] = array ('application/pdf');
    $arrConfig['files_maxUpload'] = 10000000;

    // número de registo de página, para situações de paginação
    $arrConfig['num_reg_pagina'] = 25;

    if (isset($_POST['function'])) {
        switch ($_POST['function']) {
            case 'getSearchStuff':
                if (isset($_POST['value'])) {
                    $value = $_POST['value'];
                    $uid = "9"; // assuming you have a session variable for the user id
                    getSearchStuff($value, $uid);
                }
                break;
            case 'followCheck':
                if (isset($_POST['userid'], $_POST['currentSessionUser'])) {
                    $userid = $_POST['userid'];
                    $currentSessionUser = $_POST['currentSessionUser'];
                    echo followCheck($userid, $currentSessionUser);
                }
            case 'followCheckLoad':
                if (isset($_POST['userid'], $_POST['currentSessionUser'])) {
                    $userid = $_POST['userid'];
                    $currentSessionUser = $_POST['currentSessionUser'];
                    echo followCheckLoad($userid, $currentSessionUser);
                }
                break;
            case 'getFollowCounts':
                if (isset($_POST['userid'])) {
                    $userid = $_POST['userid'];
                    echo json_encode(getFollowCounts($userid));
                }
                break;
            case 'likeCheck':
                if (isset($_POST['postid'], $_POST['currentSessionUser'])) {
                    $postid = $_POST['postid'];
                    $currentSessionUser = $_POST['currentSessionUser'];
                    $response = likeCheck($postid, $currentSessionUser);
                    error_log("likeCheck response: " . $response); // Add this line
                    echo $response;
                }
                break;
            case 'likeCheckLoad':
                if (isset($_POST['postid'], $_POST['currentSessionUser'])) {
                    $postid = $_POST['postid'];
                    $currentSessionUser = $_POST['currentSessionUser'];
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
        }
    }


    //! NEED TO MAKE THE CONFIG WORK
    //!----------------------------------------------------------------------------------------
    //!----------------------------------------------------------------------------------------
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
    function getPointsPost($postID){

    }
    function getPointsAcc(){

    }
    function RankingPost(){

    }
    function RankingAcc(){

    }
    function getThemes(){
    }
    function getDef($userID){

    }
    function getConvo(){
    }

//! Need to make
//!-----------------------------------------------------------------------------------------

//? Further Improve
//?-----------------------------------------------------------------------------------------

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
    function createPost($uid, $title, $type, $file) {
        global $arrConfig;
        $dbConn = db_connect();

        $fileName = $type . "-" . $file['name'] . "-" . $_SESSION['uid'];
        $fileExtension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $fileName .= "." . $fileExtension;

        // Prepare the SQL query to insert into the table using prepared statements
        $sql = "INSERT INTO posts (user_id, caption, post_type, post_url) VALUES (?, ?, ?, ?)";
        $stmt = $dbConn->prepare($sql);

        $stmt->bind_param("isss", $uid, $title, $type, $fileName); // bind parameters

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
                $profilePic = 'https://via.placeholder.com/320x320';
            }
            else{
                $profilePic = $arrConfig['url_users']. $profilePic ;
            }
            $_SESSION['imageProfile'] = $profilePic;
            $_SESSION['username'] = "@$username";
            echoProfileInfo($username, $email, $profilePic, $realName, $biography);
    
        } else {
            // Handle the query error
            echo "No user found with this ID.";
        }
    
        // Close the database connection
        mysqli_stmt_close($stmt);
        mysqli_close($dbConn);
    }
    
    function showPost($postId) {
        // Start the database connection
        $dbConn = db_connect();

        // Check connection
        if ($dbConn === false) {
            die("ERROR: Could not connect. " . mysqli_connect_error());
        }

        // Prepare the SQL query with the post_id condition using prepared statements
        $sql = "SELECT post_id, post_type, post_url, caption, created_at, updated_at FROM posts WHERE post_id = ?";
        $stmt = mysqli_prepare($dbConn, $sql);

        // Bind parameters
        mysqli_stmt_bind_param($stmt, "i", $postId);

        // Execute the query
        if(mysqli_stmt_execute($stmt) === false) {
            die("ERROR: Could not execute query: $sql. " . mysqli_error($dbConn));
        }

        // Bind result variables
        mysqli_stmt_bind_result($stmt, $post_id, $post_type, $post_url, $caption, $created_at, $updated_at);

        // Fetch the result
        if (mysqli_stmt_fetch($stmt)) {
            $post = array(
                'post_id' => $post_id,
                'post_type' => $post_type,
                'post_url' => $post_url,
                'caption' => $caption,
                'created_at' => $created_at,
                'updated_at' => $updated_at
            );
            // Display the post
            echoShowPost($post);

        } else {
            echoNoPosts();
        }

        // Close the database connection
        mysqli_stmt_close($stmt);
        mysqli_close($dbConn);
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
                $profilePic = 'https://via.placeholder.com/320x320';
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


//? Further Improve
//?-----------------------------------------------------------------------------------------


//*DONE

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

//*-----------------------------------------------------------------------------------------
?>