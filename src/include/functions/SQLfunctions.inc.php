<?php

    @session_start();
    require_once "echohtml.inc.php";
    require_once "paths.inc.php";
    require '/var/www/DDOC/vendor/autoload.php';
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

            case 'deletePost':
                if (isset($_POST['postid'])) {
                    $postID = $_POST['postid'];
                    deletePost($postID);
                    if (isset($_SESSION['success'])) {
                        echo json_encode(['success' => true, 'message' => $_SESSION['success']]);
                        unset($_SESSION['success']); // Clear the success message after use
                    } else {
                        echo json_encode(['success' => false, 'message' => $_SESSION['error']]);
                        unset($_SESSION['error']); // Clear the error message after use
                    }
                } else {
                    echo json_encode(['success' => false, 'message' => 'Post ID not provided.']);
                }
                break;
            case 'savePost':
                if (isset($_POST['postid']) && isset($_POST['postContent'])) {
                    $postID = $_POST['postid'];
                    $postContent = $_POST['postContent'];
                    $result = savePost($postID, $postContent);
                    if ($result) {
                        echo json_encode(['success' => true, 'message' => $_SESSION['success']]);
                        unset($_SESSION['success']); // Clear the success message after use
                    } else {
                        echo json_encode(['success' => false, 'message' => $_SESSION['error']]);
                        unset($_SESSION['error']); // Clear the error message after use
                    }
                } else {
                    echo json_encode(['success' => false, 'message' => 'Required fields are missing.']);
                }
                break;
            
            case 'saveReport':
                if (isset($_POST['reportId']) && isset($_POST['reportReason']) && isset($_POST['reportType'])) {
                    $reportId = $_POST['reportId'];
                    $reportReason = $_POST['reportReason'];
                    $reportType = $_POST['reportType'];
                    $result = saveReport($reportId, $reportReason, $reportType); // Assume saveReport is a function defined elsewhere
                    if ($result) {
                        echo json_encode(['success' => true, 'message' => 'Report saved successfully.']);
                    } else {
                        echo json_encode(['success' => false, 'message' => 'Failed to save report.']);
                    }
                } else {
                    echo json_encode(['success' => false, 'message' => 'Required fields are missing.']);
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
                        // Directly use $userId instead of $_SESSION['sender']
                        $response = getMessages($_SESSION['convo_id']);
                        echo $response;
                        if ($response === null) {
                            error_log('No response received from getMessages.');
                        } else {
                            error_log($response);
                        }
                    }
                break;
            case 'deleteMessage':
                if (isset($_POST['messageId'])) {
                    $messageId = $_POST['messageId'];
                    deleteMessage($messageId);
                }
                break;
            case 'deleteAllNotifications':

                    deleteAllNotifications();
    
                break;
            case 'deleteUser':
                if (isset($_SESSION['uid'])) {
                    $id = $_SESSION['uid'];
                    deleteUser($id);
                }
                break;
        }
    }

    //*AJAX HANDLING ---------------------------------------------------------------------


    //!QUERY OPTIMIZATION ----------------------------------------------------------------

        function encrypt($data, $key) {
            // Use a strong cipher method
            $method = 'AES-256-CBC';
            // Generate an initialization vector
            $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($method));
            // Encrypt the data
            $encrypted = openssl_encrypt($data, $method, $key, 0, $iv);
            // Return the encrypted data along with the iv, encoding both with Base64
            return base64_encode($encrypted . '::' . $iv);
        }

        function decrypt($data, $key) {
            $method = 'AES-256-CBC';
            // Split the encrypted data to get the encrypted data and the iv
            list($encrypted_data, $iv) = explode('::', base64_decode($data), 2);
            return openssl_decrypt($encrypted_data, $method, $key, 0, $iv);
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

        function sendVerificationEmail($toEmail, $subject, $message, $verificationLink) {
            $mail = new PHPMailer(true); // Passing `true` enables exceptions
            try {
                // Server settings
                // $mail->SMTPDebug = 2;                                 // Enable verbose debug output
                $mail->isSMTP();                                      // Set mailer to use SMTP
                $mail->Host = 'smtp.gmail.com';                     // Specify main and backup SMTP servers
                $mail->SMTPAuth = true;                               // Enable SMTP authentication
                $mail->Username = 'gameplaysrafinha0@gmail.com';                // SMTP username
                $mail->Password = 'oufh ghoa pwvd zngt';                         // SMTP password
                $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
                $mail->Port = 587;                                    // TCP port to connect to
        
                // Recipients
                $mail->setFrom('rafa.pinto.vieira@gmail.com', 'DDOC');
                $mail->addAddress($toEmail);                          // Add a recipient
        
                // Content
                $mail->isHTML(true);                                  // Set email format to HTML
                $mail->Subject = $subject;
                $mail->Body    = $message . "<br><br><a href='" . $verificationLink . "'>Clique aqui para verificar seu email</a>";
                $mail->AltBody = strip_tags($message) . "\n\nLink para verificação: " . $verificationLink;
        
                $mail->send();
                return true;
            } catch (Exception $e) {
                // Log the error or handle it as per your needs
                return false;
            }
        }
        function deleteUser($id) {
            $dbConn = db_connect(); // Assuming db_connect() is a function that returns a database connection
        
            // Start transaction
            mysqli_begin_transaction($dbConn);
        
            try {
                // Delete all notifications where receiver_id is the $id
                $query = "DELETE FROM notifications WHERE receiver_id = ?";
                $params = [$id];
                if (!executeQuery($dbConn, $query, $params)) {
                    throw new Exception("Failed to delete notifications");
                }
        
                // Delete all from follow table where follower_id or followee_id = $id
                $queries = [
                    "DELETE FROM follow WHERE follower_id = ?",
                    "DELETE FROM follow WHERE followee_id = ?"
                ];
        
                foreach ($queries as $query) {
                    if (!executeQuery($dbConn, $query, $params)) {
                        throw new Exception("Failed to delete follow records");
                    }
                }
        
                // Delete all messages where the user is either the sender or receiver
                $query = "DELETE FROM messages WHERE messenger_id = ? OR receiver_id = ?";
                $params = [$id, $id]; // Note: Adjusted to match both conditions
                if (!executeQuery($dbConn, $query, $params)) {
                    throw new Exception("Failed to delete messages");
                }
        
                // Select all posts where id_users = $id
                $query = "SELECT post_id FROM posts WHERE id_users = ?";
                $params = [$id]; 
                $result = executeQuery($dbConn, $query, $params);
                if ($result !== false) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        // For each post, call the deletePost function
                        deletePost($row['post_id'], $dbConn);
                    }
                }
        
                // NEW STEP: Delete every report where sender = $id
                $query = "DELETE FROM report WHERE sender = ?";
                if (!executeQuery($dbConn, $query, [$id])) {
                    throw new Exception("Failed to delete reports");
                }
        
                // After handling all related records, delete the user
                $query = "DELETE FROM users WHERE id_users = ?";
                if (!executeQuery($dbConn, $query, [$id])) { // Adjusted params to a direct array
                    throw new Exception("Failed to delete user");
                }
        
                // Commit transaction
                mysqli_commit($dbConn);
            } catch (Exception $e) {
                // Rollback transaction on error
                mysqli_rollback($dbConn);
                error_log($e->getMessage()); // Log error
                $_SESSION['error'] = "Ocorreu um Erro."; // Set error message in session
                return false;
            } finally {
                // Close the database connection
                mysqli_close($dbConn);
            }
        
            return true;
        }
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
        
            // Step 1: Retrieve the current profile picture's filename
            $currentPicSql = "SELECT user_profilePic FROM users WHERE id_users = ?";
            $stmt = mysqli_prepare($dbConn, $currentPicSql);
            mysqli_stmt_bind_param($stmt, "i", $uid);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $currentPic = mysqli_fetch_assoc($result)['user_profilePic'];
            mysqli_stmt_close($stmt);
        
            $updateSql = "UPDATE users SET";
            $updateFields = array();
            $params = array();
            
            // Generate a unique filename for the profile picture if a new one is uploaded
            if ($profilePic['name']) {
                $fileExtension = pathinfo($profilePic['name'], PATHINFO_EXTENSION);
                $uniqueFilename = "ProfilePic-" . uniqid() . "." . $fileExtension; // Unique filename
        
                // Step 2: Check and delete the current profile picture if different
                if ($currentPic && $uniqueFilename !== $currentPic) {
                    $currentPicPath = $arrConfig['dir_users'] . $currentPic;
                    if (file_exists($currentPicPath)) {
                        unlink($currentPicPath);
                    }
                }
        
                move_uploaded_file($profilePic['tmp_name'], $arrConfig['dir_users'].$uniqueFilename);
                $updateFields[] = " user_profilePic = ?";
                $params[] = $uniqueFilename;
            }
            
            if (!empty($username)) {
                $updateFields[] = " user_name = ?";
                $params[] = $username;
            }
            
            if (!empty($realName)) {
                $updateFields[] = " user_realName = ?";
                $params[] = $realName;
            }
            
            if (!empty($biography)) {
                $updateFields[] = " user_biography = ?";
                $params[] = $biography;
            }
        
            if (!empty($updateFields)) {
                $updateSql .= implode(",", $updateFields);
                $updateSql .= " WHERE id_users = ?";
                $params[] = $uid;
                
                $updateResult = executeQuery($dbConn, $updateSql, $params);
                
                if ($updateResult) {
                    $_SESSION['success'] = 'Perfil actualizado com sucesso';
                } else {
                    $error = mysqli_error($dbConn);
                    mySQLerror($error);
                }
            }
            mysqli_close($dbConn);
        }
      
        function getUserNotCurrent($uid){
            global $arrConfig;
        
            // Start the database connection
            $dbConn = db_connect();
        
            // Check connection
            if ($dbConn === false) {
                error_log("ERROR: Could not connect. " . mysqli_connect_error()); // Log connection error
                die("ERROR: Could not connect. " . mysqli_connect_error());
            }
        
            // Prepare the SQL query with the id_users condition using prepared statements
            $sql = "SELECT user_name, user_email, user_profilePic, user_realName, user_biography FROM users WHERE id_users = ?";
            $params = array($uid);
        
            // Execute the query
            $result = executeQuery($dbConn, $sql, $params);
        
            if (!$result) {
                error_log("ERROR: Query failed to execute for getUserNotCurrent with UID: $uid"); // Log query execution failure
            }
        
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
        
                // Prepare the SQL query to get the rank from the accountrankings view
                $sqlRank = "SELECT UserRank FROM `accountrankings` WHERE `UserName` = ?";
                $paramsRank = array($username);
        
                // Execute the query
                $resultRank = executeQuery($dbConn, $sqlRank, $paramsRank);
        
                if (!$resultRank) {
                    error_log("ERROR: Query failed to execute for getting rank with username: $username"); // Log rank query failure
                }
        
                // Fetch the rank
                if($rowRank = mysqli_fetch_assoc($resultRank)) {
                    $_SESSION['rank'] = $rowRank['UserRank'];
                } else {
                    error_log("ERROR: No rank found for username: $username"); // Log missing rank
                    header("Location: ../src/errorPages/NoUserFound.php");
                    exit;
                }
        
                // Return the user data instead of echoing it
                return array(
                    'username' => $username,
                    'email' => $email,
                    'profilePic' => $profilePic,
                    'realName' => $realName,
                    'biography' => $biography,
                    'rank' => $_SESSION['rank']
                );
            } else {
                // If user doesn't exist, clear and destroy the session
                session_unset(); // Remove all session variables
                session_destroy(); // Destroy the session
        
                // Redirect to the error page
                header("Location:../src/errorPages/NoUserFound.php");
                exit;
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
        
            // Prepare the SQL query with the id_users condition using prepared statements
            $sql = "SELECT user_name, user_email, user_profilePic, user_realName, user_biography FROM users WHERE id_users = ?";
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
                    header("Location:../src/errorPages/NoUserFound.php");
                    exit;
                }
        
                // Return the user data instead of echoing it
                return array(
                    'username' => $username,
                    'email' => $email,
                    'profilePic' => $profilePic,
                    'realName' => $realName,
                    'biography' => $biography,
                    'rank' => $_SESSION['rank']
                );
            } else {
                // If user doesn't exist, clear and destroy the session
                session_unset(); // Remove all session variables
                session_destroy(); // Destroy the session
        
                // Redirect to the error page
                header("Location:../src/errorPages/NoUserFound.php");
                exit;
            }
        
            mysqli_close($dbConn);
        }

      
        
        function updateUserPostStatus($userId , $status) {
            $dbConn = db_connect(); // Assuming db_connect() is a function that returns a database connection
        
            // Prepare the SQL query to update the table
            $sql = "UPDATE users SET can_post = ? WHERE id_users = ?";
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
            $sql = "SELECT can_post FROM users WHERE id_users = ?";
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
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
        
            // Check if the user is the owner of the post
            if (!checkIfOwnerPost($postID, $_SESSION['uid'])) {
                $_SESSION['error'] = "You are not authorized to edit this post.";
                return false; // Stop execution if the user is not the owner
            }
        
            $dbConn = db_connect();
        
            if ($dbConn === false) {
                $_SESSION['error'] = "ERROR: Could not connect. " . mysqli_connect_error();
                error_log("ERROR: Could not connect. " . mysqli_connect_error());
                return false;
            }
        
            $sql = "UPDATE posts SET caption = ? WHERE post_id = ?";
            $stmt = mysqli_prepare($dbConn, $sql);
        
            mysqli_stmt_bind_param($stmt, "si", $postContent, $postID);
        
            if(mysqli_stmt_execute($stmt) === false) {
                $_SESSION['error'] = "ERROR: Could not execute query: $sql. " . mysqli_error($dbConn);
                error_log("ERROR: Could not execute query: $sql. " . mysqli_error($dbConn));
                mysqli_stmt_close($stmt);
                mysqli_close($dbConn);
                
                return false;
            }
        
            $wasUpdated = mysqli_stmt_affected_rows($stmt) > 0;
        
            mysqli_stmt_close($stmt);
            mysqli_close($dbConn);
        
            if ($wasUpdated) {
                $_SESSION['success'] = "Post updated successfully.";
                return true;
            } else {
                $_SESSION['error'] = "No changes were made to the post.";
                return false;
            }
        }
        
        function checkIfOwnerPost($postID, $currentSessionUser) {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
        
            $dbConn = db_connect();
        
            if ($dbConn === false) {
                $_SESSION['error'] = "ERROR: Could not connect. " . mysqli_connect_error();
                error_log("ERROR: Could not connect. " . mysqli_connect_error());
                return false;
            }
        
            $sql = "SELECT id_users FROM posts WHERE post_id = ?";
            $stmt = mysqli_prepare($dbConn, $sql);
        
            if (!$stmt) {
                $_SESSION['error'] = "ERROR: Could not prepare query: $sql. " . mysqli_error($dbConn);
                error_log("ERROR: Could not connect. " . mysqli_connect_error());
                mysqli_close($dbConn);
                return false;
            }
        
            mysqli_stmt_bind_param($stmt, "i", $postID);
        
            if (!mysqli_stmt_execute($stmt)) {
                $_SESSION['error'] = "ERROR: Could not execute query: $sql. " . mysqli_error($dbConn);
                error_log("ERROR: Could not connect. " . mysqli_connect_error());
                mysqli_stmt_close($stmt);
                mysqli_close($dbConn);
                return false;
            }
        
            mysqli_stmt_bind_result($stmt, $userID);
        
            if (mysqli_stmt_fetch($stmt)) {
                $isOwner = $userID == $currentSessionUser;
            } else {
                $isOwner = false;
            }
        
            mysqli_stmt_close($stmt);
            mysqli_close($dbConn);
            
            
            error_log(print_r($currentSessionUser, true));
            return $isOwner;
        }

        function setSelectedType($selectedType) {
            $types = ['none', 'video', 'image', 'audio'];

            foreach ($types as $type) {
                $selected = ($type == $selectedType) ? 'selected' : '';
                echo '<option class="text-white" value="'.$type.'" '.$selected.'>' . ucfirst($type) . '</option>';
            }
        }

        function createPost($uid, $title, $type, $file, $theme) {
            global $arrConfig;
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
        
            $dbConn = db_connect();
            if (!$dbConn) {
                $_SESSION['error'] = 'Database connection failed';
                return;
            }
        
            if ($file['error'] > 0) {
                $_SESSION['error'] = 'File upload error: ' . $file['error'];
                return;
            }
        
            $fileName = $type . "-" . htmlspecialchars($file['name']) . "-" . $_SESSION['uid'];
            $fileExtension = pathinfo($file['name'], PATHINFO_EXTENSION);
            $fileName .= "." . $fileExtension;
        
            if (file_exists($arrConfig['dir_posts']."/$type/".$fileName)) {
                $_SESSION['error'] = 'A file with the same name already exists.';
                return;
            }
        
            if (!move_uploaded_file($file['tmp_name'], $arrConfig['dir_posts']."/$type/".$fileName)) {
                $_SESSION['error'] = 'Error moving uploaded file.';
                return;
            }
        
            $sql = "INSERT INTO posts (id_users, caption, post_type, post_url, id_theme) VALUES (?, ?, ?, ?, ?)";
            $stmt = mysqli_prepare($dbConn, $sql);
            if (!$stmt) {
                $_SESSION['error'] = "Error while uploading";
                return;
            }
        
            mysqli_stmt_bind_param($stmt, 'issss', $uid, $title, $type, $fileName, $theme);
            $result = mysqli_stmt_execute($stmt);
            if (!$result) {
                $_SESSION['error'] = "Error while uploading";
                return;
            }
        
            $_SESSION['success'] = "Post created successfully.";
            updateUserPostStatus($_SESSION['uid'], 1);
            sendNotification(null, $_SESSION['uid'], "PostCreated");
        }
  
        function showPost($postId, $show) {
            // Start the database connection
            $dbConn = db_connect();
        
            // Check connection
            if ($dbConn === false) {
                die("ERROR: Could not connect. " . mysqli_connect_error());
            }
        
            // Prepare the SQL query with the post_id condition using prepared statements
  
            $sql = "SELECT p.post_id, p.post_type, p.post_url, p.caption, p.created_at, p.updated_at, p.id_users, p.id_theme, t.theme, r.PostRank, p.Enabled, u.id_users AS creator_id, u.user_name, u.user_profilePic
            FROM posts p 
            LEFT JOIN theme t ON p.id_theme = t.id_theme 
            LEFT JOIN rankingposts r ON p.post_id = r.PostId AND p.id_theme = r.id_theme
            LEFT JOIN users u ON p.id_users = u.id_users
            WHERE p.post_id = ?";
  
            $stmt = mysqli_prepare($dbConn, $sql);
        
            // Bind parameters
            mysqli_stmt_bind_param($stmt, "i", $postId);
        
            // Execute the query
            if(mysqli_stmt_execute($stmt) === false) {
                die("ERROR: Could not execute query: $sql. " . mysqli_error($dbConn));
            }
        
            // Bind result variables
     
            mysqli_stmt_bind_result($stmt, $post_id, $post_type, $post_url, $caption, $created_at, $updated_at, $id_users, $id_theme, $theme_name, $rank, $enabled, $creator_id, $creator_name, $creator_avatar);

            // Fetch the result
            if (mysqli_stmt_fetch($stmt)) {
      
                $post = array(
                    'post_id' => $post_id,
                    'post_type' => $post_type,
                    'post_url' => $post_url,
                    'caption' => $caption,
                    'created_at' => $created_at,
                    'updated_at' => $updated_at,
                    'id_users' => $id_users,
                    'id_theme' => $id_theme,
                    'theme_name' => $theme_name,
                    'rank' => $rank,
                    'enabled' => $enabled,
                    'creator_id' => $creator_id,
                    'creator_name' => $creator_name,
                    'creator_avatar' => $creator_avatar
                );
   
                // Display the post if $show is not 'no'
                if ($show !== 'no') {
                    echoShowPost($post, array('name' => $creator_name, 'avatar_url' => $creator_avatar, 'id' => $creator_id));
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

            // Prepare the SQL query with the id_users condition using prepared statements
            $sql = "SELECT post_id, post_type, post_url, caption, created_at, updated_at FROM posts WHERE id_users = ? ORDER BY created_at DESC";
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
       

        function deletePost($postID) {
            global $arrConfig;
        
            if (session_status() === PHP_SESSION_NONE) {
            session_start();
            }
        
            // Check if the user is the owner of the post
            if (!checkIfOwnerPost($postID, $_SESSION['uid'])) {
            $_SESSION['error'] = "You are not authorized to delete this post.";
            return; // Stop execution if the user is not the owner
            }
        
            $dbConn = db_connect();
        
            if ($dbConn === false) {
            $_SESSION['error'] = "ERROR: Could not connect to the database. " . mysqli_connect_error();
            return;
            }
        
            // Start transaction
            mysqli_begin_transaction($dbConn);
        
            $query = "SELECT post_url, id_theme, post_type FROM posts WHERE post_id = ?";
            $stmt = mysqli_prepare($dbConn, $query);
            mysqli_stmt_bind_param($stmt, "i", $postID);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $file = mysqli_fetch_assoc($result);
            mysqli_stmt_close($stmt);
        
            if ($file) {
            $filePath = $arrConfig['dir_posts'] . $file['post_type'] . '/' . $file['post_url'];
            if (file_exists($filePath)) {
                unlink($filePath);
            }
            }
        
            $queries = [
            "DELETE FROM posts WHERE post_id = ?",
            "DELETE FROM likes WHERE post_id = ?",
            "DELETE FROM report WHERE post_id = ?"
            ];
        
            $success = true;
        
            foreach ($queries as $query) {
            $stmt = mysqli_prepare($dbConn, $query);
            mysqli_stmt_bind_param($stmt, "i", $postID);
            if (!mysqli_stmt_execute($stmt)) {
                $success = false;
                break;
            }
            mysqli_stmt_close($stmt);
            }
        
            if ($success) {
            if (isset($_SESSION['themes'][0]['id_theme']) && isset($file['id_theme']) && $file['id_theme'] == $_SESSION['themes'][0]['id_theme']) {
                updateUserPostStatus($_SESSION['uid'], 0);
            }
        
            // Commit transaction
            mysqli_commit($dbConn);
            $_SESSION['success'] = "Post deleted successfully.";
            } else {
            mysqli_rollback($dbConn); // Rollback changes on error
            $_SESSION['error'] = "Failed to delete post or likes associated with the post.";
            }
        
            mysqli_close($dbConn); // Ensure the database connection is closed
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
        $result = executeQuery($dbConn, "SELECT user_name FROM users WHERE id_users = ?", [$senderId]);
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
                $rankData = getPodium($receiverId, "post");
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


    function deleteAllNotifications() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    
        // Start the database connection
        $dbConn = db_connect();
    
        // Check connection
        if ($dbConn === false) {
            error_log("ERROR: Could not connect. " . mysqli_connect_error()) ;
            return; // Early return to stop execution
        }
    
        // Prepare the SQL query to delete all notifications
        $query = "DELETE FROM notifications WHERE receiver_id = ?";
        $params = array($_SESSION['uid']);
    
        // Execute the query
        if (executeQuery($dbConn, $query, $params)) {
            error_log("Deleted successfully") ;
        } else {
            error_log("Not deleted successfully") ;
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
                Sem notificações.
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

        $result = executeQuery($dbConn, "SELECT user_name, user_profilePic FROM users WHERE id_users = ?", [$convo_id]);

        if ($row = mysqli_fetch_assoc($result)) {
            return array(
                'username' => $row['user_name'],
                'profile_pic' => $row['user_profilePic']
            );
        } else {
            header("Location:../src/errorPages/NoCoversationFound.php");
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
                $result = executeQuery($dbConn, "SELECT user_name, user_profilePic FROM users WHERE id_users = ?", [$followerId]);
                while ($row = mysqli_fetch_assoc($result)) {
                    if ($echo === "echo") {
                        $lastmessage = getMessages($followerId, true); // Ensure this function returns the last message correctly
                        echoConvo($row['user_profilePic'], $row['user_name'], $followerId, $lastmessage); // Ensure last message is correctly passed and handled
                    }
                    $convoIds[] = $followerId;
                }
            }
        }
        return $convoIds;
    }


    function getMessages($convoId, $last = NULL) {
    
        global $arrConfig;
        $sender = $_SESSION['sender'];
        $dbConn = db_connect();
        if ($dbConn === false) {
            return "ERROR: Could not connect. " . mysqli_connect_error();
        }
        $currentUserId = $_SESSION['uid'];
    
        // Start building the query
        $query = "SELECT * FROM messages WHERE (messenger_id = ? AND receiver_id = ?) OR (messenger_id = ? AND receiver_id = ?)";
    
        // Parameters for the query
        $params = [$convoId, $currentUserId, $currentUserId, $convoId];
    
        // Modify ordering to DESC to ensure the latest message is fetched
        $query .= " ORDER BY DateTime DESC";
    
        // Apply LIMIT 1 if $last is not NULL (interpreted as true) to ensure only the latest message is fetched
        if ($last !== NULL) {
            $query .= " LIMIT 1";
        }
    
        // Execute the query
        $result = executeQuery($dbConn, $query, $params);
        if ($last !== NULL) {
            // Fetch and return the latest message directly
            $row = mysqli_fetch_assoc($result);
            return $row ? $row['message'] : null; // Return the message or null if no message is found
        } else {
            // If $last is NULL, echo the messages as before but in reverse order to maintain chronological order
            $messages = [];
            while ($row = mysqli_fetch_assoc($result)) {
                array_unshift($messages, $row); // Prepend to maintain order when echoing
            }
            foreach ($messages as $row) {
                echoMessages($row['message_id'], $row['message'], $sender, $row['messenger_id']);
            }
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
    
  
    function getRankingPost($id_theme, $type){
        // Create a connection to the database
        $dbConn = db_connect();
        if ($dbConn === false) {
            die("ERROR: Could not connect. " . mysqli_connect_error());
        }

      
        // Improved readability and consistency
        if ($id_theme !== null && $id_theme !== 'none' && $type !== null && $type !== 'none') {
            $sql = "SELECT * FROM rankingpoststype WHERE id_theme = ? AND PostType = ?";
            $params = array($id_theme, $type);
        } elseif ($id_theme !== null && $id_theme !== 'none') {
            $sql = "SELECT * FROM rankingposts WHERE id_theme = ?";
            $params = array($id_theme);
        } elseif ($type !== 'none' && ($id_theme === null || $id_theme === 'none')) {
            // Grouped conditions for clarity
            $sql = "SELECT * FROM rankingpoststypeall WHERE PostType = ?";
            $params = array($type);
        } else {
            $sql = "SELECT * FROM rankingpostsall";
            $params = array();
        }

        // Execute the query
        $result = executeQuery($dbConn, $sql, $params);
        if ($result === false) {
            die("ERROR: Could not execute query: $sql. " . mysqli_error($dbConn));
        }

        // Fetch all rows as an associative array
        $rows = mysqli_fetch_all($result, MYSQLI_ASSOC);
        // Echo the posts
        foreach($rows as $row) {
            echoRankPosts($row['PostRank'], $row['PostImage'], $row['NameOfThePost'], $row['PostType'], $row['Likes'], $row['PersonWhoPostedIt'], $row['PostId']);
        }

        // Close the connection
        mysqli_close($dbConn);
    }
    function RankingAcc($type = null){
        // Create a connection to the database
        $dbConn = db_connect();
        if ($dbConn === false) {
            die("ERROR: Could not connect. " . mysqli_connect_error());
        }
    
        if($type != 'none' && $type != null){
            $sql = "SELECT * FROM accountrankingstype WHERE PostType = ?";
            $params = [$type];
            $result = executeQuery($dbConn, $sql, $params);
        }
        else{
            $sql = "SELECT * FROM accountrankings";
            $result = executeQuery($dbConn, $sql);
        }
    
        if ($result === false) {
            die("ERROR: Could not execute query. " . mysqli_error($dbConn));
        }
    
        if ($result !== true) {
            // Fetch all rows as an associative array if result is not true (indicating a SELECT query was executed)
            $rows = mysqli_fetch_all($result, MYSQLI_ASSOC);
    
            // Echo the posts
            foreach($rows as $row) {
                echoRankAcc($row['UserRank'], $row['TotalLikes'], $row['UserName'], $row['UserImage'], $row['id_users']);
            }
        }
    
        // Close the connection
        mysqli_close($dbConn);
    }

    function getPodium($rank, $table, $themeId = null, $type = null) {
        // Create a connection to the database
        $dbConn = db_connect();
        if ($dbConn === false) {
            error_log("ERROR: Could not connect. " . mysqli_connect_error());
            return null; // Return or handle the error as appropriate
        }
    
        $sql = null; // Initialize $sql as null
        $params = []; // Initialize parameters array
    
        // Construct SQL query based on table and conditions
        if ($table == 'AccRank') {
            if ($type !== null && $type !== 'none') {
                $sql = "SELECT UserName, UserImage FROM accountrankingstype WHERE UserRank = ? AND PostType = ?";
                $params[] = $rank; // This line is added to correctly add the rank to the parameters array before the type
                $params[] = $type;
            }
            else{
                $sql = "SELECT UserName, UserImage FROM accountrankings WHERE UserRank = ?";
                $params[] = $rank;
            }
        } else if ($table == 'PostRank') {
            $baseSql = "SELECT NameOfThePost FROM ";
            $whereClauses = "PostRank = ?";
            $params[] = $rank;
            if ($themeId !== null && $themeId !== 'none' && $type !== null && $type !== 'none') {
                $sql = $baseSql . "rankingpoststype WHERE " . $whereClauses . " AND id_theme = ? AND PostType = ?";
                $params[] = $themeId;
                $params[] = $type;
            } else if ($themeId !== null && $themeId !== 'none' && ($type === null || $type === 'none')) {
                $sql = $baseSql . "rankingposts WHERE " . $whereClauses . " AND id_theme = ?";
                $params[] = $themeId;
            } else if (($type !== null && $type !== 'none') && ($themeId === null || $themeId === 'none')) {
                $sql = $baseSql . "rankingpoststypeall WHERE " . $whereClauses . " AND PostType = ?";
                $params[] = $type;
            } else if (($type === null || $type === 'none') && ($themeId === null || $themeId === 'none')) {
                $sql = $baseSql . "rankingpostsall WHERE " . $whereClauses;
            
        }
        } else {
            error_log("ERROR: Invalid table name.");
            return null;
        }
    
        $sql .= " LIMIT 1"; // Limit the results to 1
    
        // Prepare SQL statement
        if ($stmt = mysqli_prepare($dbConn, $sql)) {
            // Dynamically bind parameters
            mysqli_stmt_bind_param($stmt, str_repeat('s', count($params)), ...$params);
            // Execute the query
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
    
            // Fetch the data
            $row = mysqli_fetch_assoc($result);
            if ($row) {
                if ($table == 'AccRank') {
                    return ['username' => $row['UserName'], 'image' => $row['UserImage']];
                } else if ($table == 'PostRank') {
                    return ['NameOfThePost' => $row['NameOfThePost']];
                }
            }
            mysqli_stmt_close($stmt);
        } else {
            error_log("ERROR: Could not prepare query: $sql. " . mysqli_error($dbConn));
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
        $sql = "SELECT * FROM likes WHERE id_users = ? AND post_id = ?";
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
            $sql = "DELETE FROM likes WHERE id_users = ? AND post_id = ?";
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
            $sql = "INSERT INTO likes (id_users, post_id) VALUES (?, ?)";
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
            $receiverId = $postData['id_users'];
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
        $sql = "SELECT * FROM likes WHERE id_users = ? AND post_id = ?";
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
        $sql = "SELECT users.* FROM follow JOIN users ON follow.follower_id = users.id_users WHERE follow.followee_id = ?";
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
            echoSearchResults($follower['id_users'], $follower['user_name'], $follower['user_profilePic']);
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
        $sql = "SELECT users.* FROM follow JOIN users ON follow.followee_id = users.id_users WHERE follow.follower_id = ?";
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
                $followee['user_profilePic'] = $arrConfig['url_assets'] . 'images/Unknown_person.jpg';
            }
            echoSearchResults($followee['id_users'], $followee['user_name'], $followee['user_profilePic']);
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
        mysqli_stmt_bind_result($stmt, $id_theme, $theme, $finish_date, $is_finished);
    
        // Fetch the theme data
        $themes = array();
        while(mysqli_stmt_fetch($stmt)) {
            $themes[] = array(
                'id_theme' => $id_theme,
                'theme' => $theme,
                'finish_date' => $finish_date,
                'is_finished' => $is_finished
            );
        }
    
        mysqli_stmt_close($stmt);
        mysqli_close($dbConn);
    
        if (!$all) {
            $_SESSION['themes'] = $themes;
            $_SESSION['id_theme'] = $themes;
        }
        else{
        echo '<option class="text-white" value="none"' . (empty($GLOBALS['id_theme']) ? ' selected' : '') . '>None</option>';
        foreach ($themes as $theme) {
            $selected = ($theme['id_theme'] == $GLOBALS['id_theme']) ? 'selected' : '';
            echo '<option class="text-white" value="' . $theme['id_theme'] . '" ' . $selected . '>' . $theme['theme'] . '</option>';
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
        $sql = "SELECT id_users, user_name, user_profilePic FROM users WHERE id_users != ? AND user_name LIKE ? LIMIT 5";
        $params = array($uid, "%" . $value . "%");
    
        // Execute the query
        $result = executeQuery($dbConn, $sql, $params);
    
        // Fetch the user data
        while($row = mysqli_fetch_assoc($result)) {
            // Access the user data
            $userId = $row['id_users'];
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


    function saveReport($reportId, $reportReason, $reportType) {
        global $arrConfig;
        $dbConn = db_connect();
    
        // Check if any of the parameters are empty or null
        if (empty($reportId) || $reportId === "" || $reportId === null) {
            $_SESSION['error'] = 'Report ID cannot be empty';
            return false;
        }
        if (empty($reportReason) || $reportReason === "" || $reportReason === null) {
            $_SESSION['error'] = 'Report reason cannot be empty';
            return false;
        }
        if (empty($reportType) || $reportType === "" || $reportType === null) {
            $_SESSION['error'] = 'Report type cannot be empty';
            return false;
        }
    
        // Prepare the SQL statement to insert the new report
        $insertSql = "INSERT INTO report (why, R_type, sender, post_id) VALUES (?, ?, ?, ?)";
        // Assuming sender_id and post_id need to be dynamically provided, add placeholders for these parameters
        $senderId = $_SESSION['uid']; // Assuming the sender's ID is stored in the session
        $postId = $reportId; // Use the provided report ID as the post ID
    
        // Use the executeQuery function to execute the prepared statement
        $result = executeQuery($dbConn, $insertSql, [$reportReason, $reportType, $senderId, $postId]);
    
        if ($result) {
            $_SESSION['success'] = 'Report saved successfully';
            return true;
        } else {
            $error = mysqli_error($dbConn);
            $_SESSION['error'] = 'Failed to save report: ' . $error;
            // Assuming mySQLerror is a function to handle SQL errors
            mySQLerror($error);
            return false;
        }
    }
function getHome(){
        global $arrConfig;
        
    
        // Check if the session user and theme are set
        if (isset($_SESSION['uid']) && isset($_SESSION['themes'])) {

            $userId = $_SESSION['uid'];
            $themeId = $_SESSION['themes'][0]['id_theme'];

    
            // Connect to your database
            $dbConn = db_connect();
    
            $query = "SELECT p.post_id FROM posts p JOIN follow f ON p.id_users = f.followee_id WHERE f.follower_id = ? AND p.id_theme = ? ORDER BY p.created_at DESC LIMIT 10";
            $params = array($userId, $themeId);
    
            // Execute the statement
            $result = executeQuery($dbConn, $query, $params);
    
            // Fetch all rows as an associative array
            $followedPosts = mysqli_fetch_all($result, MYSQLI_ASSOC);
    
            // Adjust the SQL statement to include the current session user in the random posts
            $query = "SELECT post_id FROM posts WHERE id_theme = ? AND id_users NOT IN (SELECT followee_id FROM follow WHERE follower_id = ?) ORDER BY RAND() LIMIT 5";
            $params = array($themeId, $userId);
    
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
