<?php

    //*SQL Commands for User Info
    function newUser($dbConn, $email, $username, $password){
        // Prepare the SQL query to insert into the table using prepared statements
        $insertSql = "INSERT INTO users (user_email, user_name, user_password) VALUES (?, ?, ?)";
        $stmt = mysqli_prepare($dbConn, $insertSql);
        mysqli_stmt_bind_param($stmt, "sss", $email, $username, $password);
        
        // Execute the query
        $insertResult = mysqli_stmt_execute($stmt);
        
        if ($insertResult) {
            validRegisterAl();
        } else {
            // Handle the insert error
            $error = mysqli_stmt_error($stmt);
            mySQLerror($error);
        }
        
        // Close the prepared statement
        mysqli_stmt_close($stmt);
        
        // Close the database connection
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
        $stmt = mysqli_prepare($dbConn, $sql);
        mysqli_stmt_bind_param($stmt, "isss", $uid, $title, $type, $fileName);
        move_uploaded_file($file['tmp_name'],  $arrConfig['dir_posts']."/$type/".$fileName);

        // Execute the query
        $result = mysqli_stmt_execute($stmt);
    
        if ($result) {
            // Handle the successful post creation
            echo "Post created successfully.";
        } else {
            // Handle the post creation error
            $error = mysqli_stmt_error($stmt);
            mySQLerror($error);
        }
    
        // Close the prepared statement
        mysqli_stmt_close($stmt);
    
        // Close the database connection
        mysqli_close($dbConn);
    }
    

    function updatePost(){
    }

    function deletePost($postID) {
        // Start the database connection
        $dbConn = db_connect();
            
        // Prepare the SQL query to delete the post based on post_id using prepared statements
        $sql = "DELETE FROM posts WHERE post_id = ?";
        $stmt = mysqli_prepare($dbConn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $postID);

        // Execute the query
        $result = mysqli_stmt_execute($stmt);

        // Check if the deletion was successful
        if ($result) {
            echo "Post with ID $postID deleted successfully.";
        } else {
            echo "Failed to delete post with ID $postID.";
        }
        // Close the database connection
        mysqli_stmt_close($stmt);
        mysqli_close($dbConn);
    }
    function getPosts($uid) {
        // Start the database connection
        $dbConn = db_connect();
        
        // Prepare the SQL query with the user_id condition using prepared statements
        $sql = "SELECT post_id, post_type, post_url, caption, created_at, updated_at FROM posts WHERE user_id = ?";
        $stmt = mysqli_prepare($dbConn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $uid);
        
        // Execute the query
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        // Check if there are no results
        if (mysqli_num_rows($result) == 0) {
            echoNoPosts();
        } else {
            // Create an array to hold the post variables
            $posts = array();
            
            // Fetch each row of the result set and store it in the $posts array
            while ($row = mysqli_fetch_assoc($result)) {
                $posts[] = $row;
            }
            
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
    
        // Prepare the SQL query with the user_id condition using prepared statements
        $sql = "SELECT user_name, user_email, user_profilePic, user_realName, user_biography FROM users WHERE user_id = ?";
        $stmt = mysqli_prepare($dbConn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $uid);
        
        // Execute the query
        $result = mysqli_stmt_execute($stmt);
    
        // Check if the query was successful
        if ($result) {
            // Fetch the user data
            $userData = mysqli_stmt_get_result($stmt);
            $userData = mysqli_fetch_assoc($userData);
            
            // Access the user data
            $username = $userData['user_name'];
            $email = $userData['user_email'];
            $profilePic = $userData['user_profilePic'];
            $realName = $userData['user_realName'];
            $biography = $userData['user_biography'];
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
            $error = mysqli_stmt_error($stmt);
            mySQLerror($error);
        }
    
        // Close the database connection
        mysqli_stmt_close($stmt);
        mysqli_close($dbConn);
    }
?>