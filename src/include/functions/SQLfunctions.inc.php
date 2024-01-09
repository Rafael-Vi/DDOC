<?php
    
    //*SQL Commands for User Info
    function newUser($dbConn, $email, $username, $password){
                // Insert into the table
        $insertSql = "INSERT INTO users (user_email, user_name, user_password) VALUES ('$email', '$username', '$password')";
        $insertResult = mysqli_query($dbConn, $insertSql);

        if ($insertResult) {
            validRegisterAl();
        } else {
            // Handle the insert error
            $error = mysqli_error($dbConn);
            mySQLerror($error);
        }

        // Close the database connection
        mysqli_close($dbConn);
    }
    function updateUser($uid, $username, $realName, $profilePic, $biography) {
        $dbConn = db_connect();
        
        // Construct the update query based on the provided values
        $updateSql = "UPDATE users SET";
        $updateFields = array();
    
        if (!empty($username)) {
            $updateFields[] = " user_name = '$username'";
        }
    
        if (!empty($realName)) {
            $updateFields[] = " user_realName = '$realName'";
        }
    
        if (!empty($profilePic)) {
            $updateFields[] = " user_profilePic = '$profilePic'";
        }
    
        if (!empty($biography)) {
            $updateFields[] = " user_biography = '$biography'";
        }
    
        // Check if any fields need to be updated
        if (!empty($updateFields)) {
            $updateSql .= implode(",", $updateFields);
            $updateSql .= " WHERE user_id = '$uid'";
    
            $updateResult = mysqli_query($dbConn, $updateSql);
    
            if ($updateResult) {
                // Handle the update success
                updateSuccess();    
            } else {
                // Handle the update error
                $error = mysqli_error($dbConn);
                mySQLerror($error);
            }
        }
    
        // Close the database connection
        mysqli_close($dbConn);
    }

    //* SQL Commands For Posts
    function createPost(){
    }

    function updatePost(){
    }

    function deletePost($postID) {
        // Start the database connection
        $dbConn = db_connect();
    
        // Prepare the SQL query to delete the post based on post_id
        $sql = "DELETE FROM posts WHERE post_id = '$postID';";
    
        // Execute the query
        $result = mysqli_query($dbConn, $sql);
    
        // Check if the deletion was successful
        if ($result) {
            echo "Post with ID $postID deleted successfully.";
        } else {
            echo "Failed to delete post with ID $postID.";
        }
    }
    function getPosts($uid) {
        // Start the database connection
        $dbConn = db_connect();
    
        // Prepare the SQL query with the user_id condition
        $sql = "SELECT post_id, post_type, post_url, caption, created_at, updated_at FROM posts WHERE user_id = '$uid';";
    
        // Execute the query
        $result = mysqli_query($dbConn, $sql);
    
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
        mysqli_close($dbConn);
    }

    function getUserInfo($uid){

        // Start the database connection
        $dbConn = db_connect();

        // Prepare the SQL query with the user_id condition
        $sql = "SELECT user_name, user_email, user_profilePic, user_realName, user_biography FROM users WHERE user_id = '$uid';";

        // Execute the query
        $result = mysqli_query($dbConn, $sql);

        // Check if the query was successful
        if ($result) {
            // Fetch the user data
            $userData = mysqli_fetch_assoc($result);
            
            // Access the user data
            $username = $userData['user_name'];
            $email = $userData['user_email'];
            $profilePic = $userData['user_profilePic'];
            $realName = $userData['user_realName'];
            $biography = $userData['user_biography'];
            if (!$profilePic) {
                $profilePic = 'https://via.placeholder.com/320x320';
            }
            $_SESSION['imageProfile'] = $profilePic;
            $_SESSION['username'] = "@$username";
            echoProfileInfo($username, $email, $profilePic, $realName, $biography);

        } else {
            // Handle the query error
            $error = mysqli_error($dbConn);
            mySQLerror($error);
        }

        // Close the database connection
        mysqli_close($dbConn);
    }
?>