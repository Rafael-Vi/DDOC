<?php

if (isset($_POST['submit'])) {

    if ($_POST['submit'] === 'loginSubmit') {

        if (isset($_POST['emailL']) && isset($_POST['passwordL']) && !empty($_POST['emailL']) && !empty($_POST['passwordL'])) {
            $email = strtolower(preg_replace('/\s+/', '', htmlspecialchars($_POST['emailL'])));
            $password = htmlspecialchars($_POST['passwordL']);

            validateLogin($email, $password);
        }
    }

    elseif ($_POST['submit'] === 'registerSubmit') {

        if (isset($_POST['usernameR']) && isset($_POST['emailR']) && isset($_POST['passwordR']) && !empty($_POST['usernameR']) && !empty($_POST['emailR']) && !empty($_POST['passwordR'])) {
            $username = preg_replace('/\s+/', '', htmlspecialchars($_POST['usernameR']));
            $email = strtolower(trim(htmlspecialchars($_POST['emailR'])));
            $password = htmlspecialchars($_POST['passwordR']);
            validateRegister($username, $email, $password);
        }

    }
}

function validateLogin($email, $password) {
    global $arrConfig;

    // Check if the email is valid
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // If the email is not valid, try using the username instead
        $email = preg_replace('/@/', '', $email); // Remove the @ symbol to treat it as a username
    }

    $dbConn = db_connect();

    $sql = "SELECT user_email, user_password FROM users WHERE user_email =?";
    $sql2 = "SELECT user_id, can_post, user_name, user_profilePic FROM users WHERE user_email =? OR user_name =?"; // Modified SQL query

    $result = executeQuery($dbConn, $sql, [$email]);
    $result2 = executeQuery($dbConn, $sql2, [$email, $email]); // Pass both email and username to the query

    $user = mysqli_fetch_assoc($result);
    $userDetails = mysqli_fetch_assoc($result2);

    if ($user) {
        if (password_verify($password, $user['user_password'])) {
            if ($userDetails) {
                $_SESSION['uid'] = $userDetails['user_id'];
                $_SESSION['can_post'] = $userDetails['can_post'];
                $_SESSION['imageProfile'] = $arrConfig['url_users'].$userDetails['user_profilePic'];
                $_SESSION['username'] = '@'.$userDetails['user_name'];
                header("Location: social.php");
                exit;
            }
        } else {
            $_SESSION['error'] = "Auth falhou!";
        }
    } else {
        $_SESSION['error'] = "Auth falhou!";
    }

    mysqli_close($dbConn);
}

function validateRegister($username, $email, $password) {

    $emailSql = "SELECT user_email FROM users WHERE user_email = ?";
    $usernameSql = "SELECT user_name FROM users WHERE user_name = ?";

    $dbConn = db_connect();
    $emailResult = executeQuery($dbConn, $emailSql, [$email]);
    $usernameResult = executeQuery($dbConn, $usernameSql, [$username]);

    if ($emailResult) {
        if (mysqli_num_rows($emailResult) > 0) {
            $_SESSION['error'] = "Email já registrado";
            return;
        }
    } else {
        $error = mysqli_error($dbConn);
        $_SESSION['error'] = $error;
    }
    if ($usernameResult) {
        if (mysqli_num_rows($usernameResult) > 0) {
            $_SESSION['error'] = "Username já registrado";
            return;
        }
    } else {
        $error = mysqli_error($dbConn);
        $_SESSION['error'] = $error;
    }

    newUser($dbConn, $email, $username, $password);
}