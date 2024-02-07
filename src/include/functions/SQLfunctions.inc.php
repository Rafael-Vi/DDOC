<?php

    //*SQL Commands for User Info
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

    //* SQL Commands For Posts
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
?>