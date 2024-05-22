<?php

    @session_start();
    require_once "echohtml.inc.php";
    require_once "paths.inc.php";
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;
    
    //*AJAX HANDLING ---------------------------------------------------------------------

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
                    echo $response;
                }
                break;
            case 'loadNotificationsNavBar':
                if (isset($_SESSION['uid'])) {
                    $response = getNotif(false);
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

            case 'sendMessage':
                if (isset($_POST['message']) && isset($_POST['recipient'])) {
                    $message = $_POST['message'];
                    $recipient = $_POST['recipient'];
                    sendMessage($recipient, $message);
                }
                break;
            case 'loadMessages':
                if (isset($_SESSION['uid'])) {
                    $response = getMessages($_SESSION['sender'], $_SESSION['convo_id']);
                    echo $response;
                    error_log($response);
                }
                break;
            case 'deleteMessage':
                if (isset($_POST['messageId'])) {
                    $messageId = $_POST['messageId'];
                    deleteMessage($messageId);
                }
                break;


        }
    }

    //*AJAX HANDLING ---------------------------------------------------------------------


    //!QUERY OPTIMIZATION ----------------------------------------------------------------


    function sendVerificationEmail($to, $subject, $message, $link) {
        $mail = new PHPMailer(true);
    
        try {
            //Server settings
            $mail->isSMTP();                                      
            $mail->Host = 'smtp.eu.mailgun.org';  
            $mail->SMTPAuth = true;                               
            $mail->Username = 'brad@gentl.store';                 
            $mail->Password = getenv('MAIL_PASSWORD');                           
            $mail->SMTPSecure = 'tls';                            
            $mail->Port = 587;                                    
    
            //Recipients
            $mail->setFrom('rafa.pinto.vieira@gmail.com', 'DDOC');
            $mail->addAddress($to);     
    
            //Content
            $mail->isHTML(true);                                  
            $mail->Subject = $subject;
            $body = "<html><body>";
            $body.= "<p>Hello,</p>";
            $body.= "<p>Please click the link below to verify your email:</p>";
            $body.= "<a href=\"$link\">Verify Email</a></p>";
            $body.= "</body></html>";
            $mail->Body    = $body;
    
            $mail->send();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }


        function executeQuery($dbConn, $query, $params = null) {
            $stmt = mysqli_prepare($dbConn, $query);
        
            if ($params !== null && !empty($params)) {
                $types = '';
                foreach ($params as $param) {
                    if (is_int($param)) {
                        $types .= 'i';
                    } elseif (is_bool($param)) {
                        $types .= 'i'; // booleans are treated as integers
                    } elseif (is_string($param)) {
                        $types .= 's';
                    } else {
                        $types .= 'b'; // for blob and unknown types
                    }
                }
        
                if (!empty($types)) {
                    mysqli_stmt_bind_param($stmt, $types, ...$params);
                }
            }
        
            $executed = mysqli_stmt_execute($stmt);
            if ($executed) {
                $result = mysqli_stmt_get_result($stmt);
                return $result !== false ? $result : true;
            } else {
                return false;
            }
        }

        function db_connect() {
            global $arrConfig;

            $conn = mysqli_connect($arrConfig['connect_DB'][0], $arrConfig['connect_DB'][1], $arrConfig['connect_DB'][2], $arrConfig['connect_DB'][3]);

            if (!$conn) {
                die("Error connecting to MySQL Server: " . mysqli_connect_error());
            }
            return $conn;
        }

    //!QUERY OPTIMIZATION ----------------------------------------------------------------


    //TODO FUNCTIONS ---------------------------------------------------------------------



    //TODO FUNCTIONS --------------------------------------------------------------------


    //? USER RELATED ------------------------------------------------------------------------


    
        function newUser($dbConn, $email, $username, $password){
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
            $insertSql = "INSERT INTO users (user_email, user_name, user_password) VALUES (?, ?, ?)";
        
            $insertResult = executeQuery($dbConn, $insertSql, [$email, $username, $hashedPassword]);
        
            if ($insertResult) {

            $_SESSION['success'] = 'Registration successful';

            } else {
                $_SESSION['error'] = "Error: " . mysqli_error($dbConn) . 
                                    " Error code: " . mysqli_errno($dbConn) . 
                                    " SQLSTATE: " . mysqli_sqlstate($dbConn);
            }
        
            mysqli_close($dbConn);
        }

        function updateUser($uid, $username, $realName, $profilePic, $biography) {
            global $arrConfig;
            $dbConn = db_connect();
            $updateSql = "UPDATE users SET";
            $updateFields = array();
            $params = array();
            
            $fileExtension = pathinfo($profilePic['name'], PATHINFO_EXTENSION);
            $profilePicName .= "." . $fileExtension;
            
            if (!empty($username)) {
                $updateFields[] = " user_name = ?";
                $params[] = $username;
            }
            
            if (!empty($realName)) {
                $updateFields[] = " user_realName = ?";
                $params[] = $realName;
            }
            
            if (!empty($profilePic) && !empty($profilePic['name'])) {
                $profilePicName = "ProfilePic-" . $profilePic['name'] . "-" . $_SESSION['uid'];
                move_uploaded_file($profilePic['tmp_name'], $arrConfig['dir_users'].$profilePicName);
                $updateFields[] = " user_profilePic = ?";
                $params[] = $profilePicName;
            }
            
            if (!empty($biography)) {
                $updateFields[] = " user_biography = ?";
                $params[] = $biography;
            }

            if (!empty($updateFields)) {
                $updateSql .= implode(",", $updateFields);
                $updateSql .= " WHERE user_id = ?";
                $params[] = $uid;
            
                $updateResult = executeQuery($dbConn, $updateSql, $params);
            
                if ($updateResult) {

                    echoSuccess("User updated successfully.");   
                } else {

                    $error = mysqli_error($dbConn);
                    mySQLerror($error);
                }
            }
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
            $params = array($uid);
        
            // Execute the query
            $result = executeQuery($dbConn, $sql, $params);
        
            // Fetch the user data
            if($row = mysqli_fetch_assoc($result)) {
                // Access the user data
                $username = $row['user_name'];
                $email = $row['user_email'];
                $profilePic = $row['user_profilePic'];
                $realName = $row['user_realName'];
                $biography = $row['user_biography'];
        
                if (!$profilePic) {
                    $profilePic = $arrConfig['url_assets']. "/images/Unknown_person.jpg";
                }
                else{
                    $profilePic = $arrConfig['url_users']. $profilePic ;
                }
                $_SESSION['imageProfile'] = $profilePic;
                $_SESSION['username'] = "@$username";
        
                // Prepare the SQL query to get the rank from the accountrankings view
                $sqlRank = "SELECT UserRank FROM `accountrankings` WHERE `UserName` = ?";
                $paramsRank = array($username);
        
                // Execute the query
                $resultRank = executeQuery($dbConn, $sqlRank, $paramsRank);
        
                // Fetch the rank
                if($rowRank = mysqli_fetch_assoc($resultRank)) {
                    $_SESSION['rank'] = $rowRank['UserRank'];
                } else {
                    header("Location:../../errorPages/NoUserFound.php");
                    exit;
                }
                
                echoProfileInfo($username, $email, $profilePic, $realName, $biography, $_SESSION['rank']);
            } else {
                // Handle the query error
                header("../../errorPages/NoUserFound.php");
                exit;
            }
        
            mysqli_close($dbConn);
        }
        
        function updateUserPostStatus($userId , $status) {
            $dbConn = db_connect(); // Assuming db_connect() is a function that returns a database connection
        
            // Prepare the SQL query to update the table
            $sql = "UPDATE users SET can_post = ? WHERE user_id = ?";
            $params = array($status,$userId);
        
            // Execute the query
            $result = executeQuery($dbConn, $sql, $params);
        
            if ($result) {
                $_SESSION['can_post'] = $status;
            } else {
                // Handle the update error
                echo "Error updating user: " . mysqli_error($dbConn);
            }
        
            // Close the database connection
            mysqli_close($dbConn);
        }
        
        function getCanPostStatus($userId) {
            $dbConn = db_connect(); // Assuming db_connect() is a function that returns a database connection
        
            // Prepare the SQL query to select the can_post value
            $sql = "SELECT can_post FROM users WHERE user_id = ?";
            $params = array($userId);
        
            // Execute the query
            $result = executeQuery($dbConn, $sql, $params);
        
            // Check if the query was successful
            if($result === false) {
                error_log("Error: " . mysqli_error($dbConn));
                return;
            }
        
            // Fetch the result
            if($row = mysqli_fetch_assoc($result)) {
                $_SESSION['can_post'] = $row['can_post'];
            } else {
                error_log("Error: No user found with ID " . $userId);
            }
        
            // Close the database connection
            mysqli_close($dbConn);
        }

        
    //? USER RELATED ------------------------------------------------------------------------


    //*POST RELATED ------------------------------------------------------------------------
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
        
            // Check if a file with the same name already exists
            if (file_exists($arrConfig['dir_posts']."/$type/".$fileName)) {
                die('A file with the same name already exists.');
            }
        
            // Prepare the SQL query to insert into the table using prepared statements
            $sql = "INSERT INTO posts (user_id, caption, post_type, post_url, theme_id) VALUES (?, ?, ?, ?, ?)";
            
            // Execute the query
            // Execute the query
            $result = executeQuery($dbConn, $sql, [$uid, $title, $type, $fileName, $theme]);

            if($result === false) {
                error_log("Error: " . mysqli_error($dbConn));
            }

            // Move the uploaded file
            if (!move_uploaded_file($file['tmp_name'],  $arrConfig['dir_posts']."/$type/".$fileName)) {
                die('Error uploading file - check destination is writeable. '.$type.'');
            }
        
            if ($result) {
                // Handle the successful post creation
                echo "Post created successfully.";
                updateUserPostStatus($_SESSION['uid'], 1);
                sendNotification(null, $_SESSION['uid'], "PostCreated");
            } else {
                // Handle the post creation error
                error_log("Error: " . mysqli_error($dbConn));
            }
        
            if ($file['error'] > 0) {
                error_log('File upload error: ' . $file['error']);
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
            $sql = "SELECT p.post_id, p.post_type, p.post_url, p.caption, p.created_at, p.updated_at, p.user_id, p.theme_id, t.theme, r.PostRank, p.Enabled, u.user_name, u.user_profilePic
            FROM posts p 
            LEFT JOIN theme t ON p.theme_id = t.theme_id 
            LEFT JOIN rankingposts r ON p.post_id = r.PostId AND p.theme_id = r.theme_id
            LEFT JOIN users u ON p.user_id = u.user_id
            WHERE p.post_id = ?";
            $stmt = mysqli_prepare($dbConn, $sql);
        
            // Bind parameters
            mysqli_stmt_bind_param($stmt, "i", $postId);
        
            // Execute the query
            if(mysqli_stmt_execute($stmt) === false) {
                die("ERROR: Could not execute query: $sql. " . mysqli_error($dbConn));
            }
        
            // Bind result variables
            mysqli_stmt_bind_result($stmt, $post_id, $post_type, $post_url, $caption, $created_at, $updated_at, $user_id, $theme_id, $theme_name, $rank, $enabled, $creator_name, $creator_avatar);
        
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
                    'rank' => $rank,
                    'enabled' => $enabled,
                    'creator_name' => $creator_name,
                    'creator_avatar' => $creator_avatar
                );
                // Display the post if $show is not 'no'
                if ($show !== 'no') {
                    echoShowPost($post, array('name' => $creator_name, 'avatar_url' => $creator_avatar));
                }
        
            } else {
                return false;
            }
        
            // Close the database connection
            mysqli_stmt_close($stmt);
            mysqli_close($dbConn);
            return $post;
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
        
            // Delete the post
            $query = "DELETE FROM posts WHERE post_id = ?";
            $params = [$postID];
            if (!executeQuery($dbConn, $query, $params)) {
                echo "Failed to delete post with ID $postID.";
                return;
            }
        
            // Delete all likes associated with the post
            $query = "DELETE FROM likes WHERE post_id = ?";
            if (!executeQuery($dbConn, $query, $params)) {
                echo "Failed to delete likes for post with ID $postID.";
                return;
            }
    
            // Update user status
            updateUserPostStatus($_SESSION['uid'], 0);
            // Close connection
            mysqli_close($dbConn);
        }
        


    //*POST RELATED ------------------------------------------------------------------------

    //?NOTIFICATION RELATED ----------------------------------------------------------------

    function sendNotification($receiverId, $senderId, $type) {
        global $arrConfig;
        $dbConn = db_connect(); 
        if ($dbConn === false) {
            return "ERROR: Could not connect. " . mysqli_connect_error();
        }
    
        // Fetch the username based on the senderId
        $result = executeQuery($dbConn, "SELECT user_name FROM users WHERE user_id = ?", [$senderId]);
        $senderUsername = mysqli_fetch_assoc($result)['user_name'];
    
        $message = '';
        switch ($type) {
            case 'PostCreated':
                $message = 'A new post has been created by ' . $senderUsername;
                $result = executeQuery($dbConn, "SELECT follower_id FROM follow WHERE followee_id = ?", [$senderId]);
                while ($row = mysqli_fetch_assoc($result)) {
                    executeQuery($dbConn, "INSERT INTO notifications (message, date_sent, receiver_id) VALUES (?, NOW(), ?)", [$message, $row['follower_id']]);
                }
                break;
            case 'PostLiked':
                $message = 'User ' . $senderUsername . ' liked your post';
                executeQuery($dbConn, "INSERT INTO notifications (message, date_sent, receiver_id) VALUES (?, NOW(), ?)", [$message, $receiverId]);
                break;
            case 'UserFollowed':
                $message = 'User ' . $senderUsername . ' started following you';
                executeQuery($dbConn, "INSERT INTO notifications (message, date_sent, receiver_id) VALUES (?, NOW(), ?)", [$message, $receiverId]);
                break;
            case 'YourRank':
                $rankData = getPodium($receiverId);
                if ($rankData !== null) {
                    $message = $rankData['username'] . ', your current rank is ' . $rankData['rank'];
                }
                executeQuery($dbConn, "INSERT INTO notifications (message, date_sent, receiver_id) VALUES (?, NOW(), ?)", [$message, $receiverId]);
                break;
            case 'MessageReceived':
                $message = 'You have received a new message from ' . $senderUsername;
                executeQuery($dbConn, "INSERT INTO notifications (message, date_sent, receiver_id) VALUES (?, NOW(), ?)", [$message, $receiverId]);
                break;
            default:
                return "ERROR: Invalid notification type.";
        }
    
        mysqli_close($dbConn);
    
        return true;
    }
    function deleteNotif($notifID) {
        // Start the database connection
        $dbConn = db_connect();
    
        // Check connection
        if ($dbConn === false) {
            die("ERROR: Could not connect. " . mysqli_connect_error());
        }
    
        // Prepare the SQL query to delete the notification based on notification_id
        $query = "DELETE FROM notifications WHERE id = ?";
        $params = [$notifID];
    
        // Execute the query
        if (executeQuery($dbConn, $query, $params)) {
            echo "Notification with ID $notifID deleted successfully.";
        } else {
            echo "Failed to delete notification with ID $notifID.";
        }
    
        // Close connection
        mysqli_close($dbConn);
    }

    function getNotif($echoNotif = true) {
        $receiverId = $_SESSION['uid'];
    
        global $arrConfig;
        $dbConn = db_connect(); 
        if ($dbConn === false) {
            return "ERROR: Could not connect. " . mysqli_connect_error();
        }
    
        // Prepare the SQL query
        $query = "SELECT * FROM notifications WHERE receiver_id = ? ORDER BY date_sent DESC";
        $params = [$receiverId];
    
        // Execute the query
        $result = executeQuery($dbConn, $query, $params);
        if ($result === false) {
            return "ERROR: Could not execute query: $query. " . mysqli_error($dbConn);
        }
    
        // Fetch all notifications
        $notifications = mysqli_fetch_all($result, MYSQLI_ASSOC);
    
        // If echoNotif is true, echo the notifications
        if ($echoNotif) {
            if (count($notifications) > 0) {
                foreach ($notifications as $notification) {
                    echoNotif($notification);
                }
            } else {
                echo'<div class="flex flex-col  items-center justify-center h-full">
                <h2 class="text-3xl font-bold bg-gray-800 rounded-lg p-8 text-white">
                No notifications found.
                </h2>
                </div>';
            }
        } else {
            $unreadCount = 0;
            foreach ($notifications as $notification) {
                if ($notification['is_read'] == 0) {
                    $unreadCount++;
                }
            }
            return $unreadCount;
        }
    
        // Close connection
        mysqli_close($dbConn);
    }
    
    function setNotifRead() {
        $receiverId = $_SESSION['uid'];
    
        global $arrConfig;
        $dbConn = db_connect();
        if ($dbConn === false) {
            return "ERROR: Could not connect. " . mysqli_connect_error();
        }
    
        $query = "UPDATE notifications SET is_read = 1 WHERE receiver_id = ?";
        $params = [$receiverId];
    
        // Execute the query
        if(executeQuery($dbConn, $query, $params) === false) {
            return "ERROR: Could not execute query: $query. " . mysqli_error($dbConn);
        }
    
        // Close connection
        mysqli_close($dbConn);
    }

    //?NOTIFICATION RELATED ---------------------------------------------------------------


    //*CONVO RELATED ----------------------------------------------------------------------


    function getUserDetails($convo_id) {
        global $arrConfig;
        $dbConn = db_connect(); 
        if ($dbConn === false) {
            return "ERROR: Could not connect. " . mysqli_connect_error();
        }

        $result = executeQuery($dbConn, "SELECT user_name, user_profilePic FROM users WHERE user_id = ?", [$convo_id]);

        if ($row = mysqli_fetch_assoc($result)) {
            return array(
                'username' => $row['user_name'],
                'profile_pic' => $row['user_profilePic']
            );
        } else {
            header("Location:../../errorPages/NoCoversationFound.php");
            exit;
        }
    }

    function getConvo($echo = NULL) {
        global $arrConfig;
        $dbConn = db_connect(); 
        if ($dbConn === false) {
            return "ERROR: Could not connect. " . mysqli_connect_error();
        }

        $userId1 = $_SESSION['uid']; // The ID of the first user

        $result = executeQuery($dbConn, "SELECT followee_id FROM follow WHERE follower_id = ?", [$userId1]);
        
        $userId2 = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $userId2[] = $row['followee_id'];
        }

        $convoIds = [];
        foreach ($userId2 as $followerId) {
            $result = executeQuery($dbConn, "SELECT * FROM follow WHERE follower_id = ? AND followee_id = ?", [$followerId, $_SESSION['uid']]);
            if (mysqli_num_rows($result) > 0) {
                $result = executeQuery($dbConn, "SELECT user_name, user_profilePic FROM users WHERE user_id = ?", [$followerId]);
                while ($row = mysqli_fetch_assoc($result)) {
                    if ($echo === "echo") {
                        echoConvo($row['user_profilePic'], $row['user_name'], $followerId);
                    }
                    $convoIds[] = $followerId;
                }
            }
        }
        return $convoIds;
    }


    function getMessages($sender,$convoId) {
        global $arrConfig;
        $dbConn = db_connect();
        if ($dbConn === false) {
            return "ERROR: Could not connect. " . mysqli_connect_error();
        }
        $currentUserId = $_SESSION['uid'];
    
        $result = executeQuery($dbConn, "SELECT * FROM messages WHERE (messenger_id = ? AND receiver_id = ?) OR (messenger_id = ? AND receiver_id = ?) ORDER BY DateTime ASC", [$convoId, $currentUserId, $currentUserId, $convoId]);
        while ($row = mysqli_fetch_assoc($result)) {
            echoMessages($row['message_id'], $row['message'], $sender, $row['messenger_id']);
        }
    }



    function sendMessage($receiver, $message){
        global $arrConfig;
        $dbConn = db_connect();
        if ($dbConn === false) {
            return "ERROR: Could not connect. " . mysqli_connect_error();
        }
        $currentUserId = $_SESSION['uid'];

        $result = executeQuery($dbConn, "INSERT INTO messages (messenger_id, receiver_id, message) VALUES (?, ?, ?)", [$currentUserId, $receiver, $message]);
        sendNotification($receiver, $currentUserId, "MessageReceived");
    
        if ($result === false) {
            // Handle error - inform the user that the message could not be sent
        } else {
            // Message sent successfully
        }
    }

    function deleteMessage($messageID){
        global $arrConfig;
        $dbConn = db_connect();
        if ($dbConn === false) {
            return "ERROR: Could not connect. " . mysqli_connect_error();
        }
    
        $result = executeQuery($dbConn, "DELETE FROM messages WHERE message_id = ?", [$messageID]);
    
        if ($result === false) {
            // Handle error - inform the user that the message could not be deleted
        } else {
            // Message deleted successfully
        }
    }
    //*CONVO RELATED ---------------------------------------------------------------------


    //?RANKING RELATED -------------------------------------------------------------------

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

    //?RANKING RELATED ------------------------------------------------------------------


    //*LIKE RELATED ---------------------------------------------------------------------

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

    //*LIKE RELATED --------------------------------------------------------------------


    //?FOLLOW RELATED ------------------------------------------------------------------

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

    //?FOLLOW RELATED ------------------------------------------------------------------


    //*MISCELLANEOUS -------------------------------------------------------------------

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

    function getSearchStuff($value,$uid){
        global $arrConfig;
        $dbConn = db_connect(); 
        if ($dbConn === false) {
            die("ERROR: Could not connect. " . mysqli_connect_error());
        }
    
        // Prepare the SQL query to select the user data
        $sql = "SELECT user_id, user_name, user_profilePic FROM users WHERE user_id != ? AND user_name LIKE ? LIMIT 5";
        $params = array($uid, "%" . $value . "%");
    
        // Execute the query
        $result = executeQuery($dbConn, $sql, $params);
    
        // Fetch the user data
        while($row = mysqli_fetch_assoc($result)) {
            // Access the user data
            $userId = $row['user_id'];
            $username = $row['user_name'];
            $profilePic = $row['user_profilePic'];
    
            if (!$profilePic) {
                $profilePic = $arrConfig['url_assets'].'images/Unknown_person.jpg'; 
            } else {
                if ($arrConfig !== null && isset($arrConfig['url_users']) && $arrConfig['url_users'] !== "") {
                    $profilePic = $arrConfig['url_users']. $profilePic;
                } else {
                    $profilePic = $arrConfig['url_assets'].'images/Unknown_person.jpg';
                }    
            }
            echoSearchResults($userId, $username, $profilePic);

        }
    
        // Close the database connection
        mysqli_close($dbConn);
    }
    
    function getHome(){
        global $arrConfig;
        
    
        // Check if the session user and theme are set
        if (isset($_SESSION['uid']) && isset($_SESSION['themes'])) {

            $userId = $_SESSION['uid'];
            $themeId = $_SESSION['themes'][0]['theme_id'];

    
            // Connect to your database
            $dbConn = db_connect();
    
            $query = "SELECT p.post_id FROM posts p JOIN follow f ON p.user_id = f.followee_id WHERE f.follower_id = ? AND p.theme_id = ? ORDER BY p.created_at DESC LIMIT 10";
            $params = array($userId, $themeId);
    
            // Execute the statement
            $result = executeQuery($dbConn, $query, $params);
    
            // Fetch all rows as an associative array
            $followedPosts = mysqli_fetch_all($result, MYSQLI_ASSOC);
    
            // Prepare the SQL statement to get other posts randomly
            $query = "SELECT post_id FROM posts WHERE theme_id = ? AND user_id NOT IN (SELECT followee_id FROM follow WHERE follower_id = ?) AND user_id != ? ORDER BY created_at DESC LIMIT 5";
            $params = array($themeId, $userId, $userId);
    
            // Execute the statement
            $result = executeQuery($dbConn, $query, $params);
    
            // Fetch all rows as an associative array
            $otherPosts = mysqli_fetch_all($result, MYSQLI_ASSOC);
    
            // Merge the two arrays
            $posts = array_merge($followedPosts, $otherPosts);
    
            // Close the connection
            mysqli_close($dbConn);
    
            // Check if there are any posts
            if (empty($posts)) {
                echo '<div class="flex flex-col  items-center justify-center h-full">
                        <h2 class="text-3xl font-bold bg-gray-800 rounded-lg p-8 text-white">
                            Nenhum post foi criado ainda.
                        </h2>
                      </div>';
            } else {
                // Use showPost() for each post
                foreach ($posts as $post) {
                    showPost($post['post_id'], "yes");
                }
            }
        }
    }

    //*MISCELLANEOUS -------------------------------------------------------------------
