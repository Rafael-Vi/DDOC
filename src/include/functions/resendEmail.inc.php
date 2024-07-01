<?php
require_once "../config.inc.php";

if (isset($_POST['email']) && !empty($_POST['email'])) {
    $email = preg_replace('/\s+/', '', htmlspecialchars($_POST['email']));
    if (isset($_POST['action']) && $_POST['action'] == 'email-resend') {
        verifyEmailExistsAndStatus($email);
    } elseif (isset($_POST['action']) && $_POST['action'] == 'password-alter' && isset($_POST['newPassword'])) {
        $newPassword = $_POST['newPassword'];
        changeUserPassword($email, $newPassword);
    }
}

function verifyEmailExistsAndStatus($email) {
    global $arrConfig; // Ensure the global encryption key and configuration are accessible

    $dbConn = db_connect(); // Establish database connection

    $query = "SELECT user_name, email_verify FROM users WHERE user_email = ? LIMIT 1";
    $result = executeQuery($dbConn, $query, [$email]);
    
    if ($result && $result->num_rows > 0) {
        $userData = $result->fetch_assoc();
        
        if ($userData['email_verify']) {
            $_SESSION['error'] = "Este email já foi verificado.";
        } else {
            $username = $userData['user_name'];
            $encryptedUsername = encrypt(urlencode($username));
            $encryptedEmail = encrypt(urlencode($email));
            $verificationLink = "http://gentl.store/src/include/functions/verifyEmail.php?username=" . $encryptedUsername . "&email=" . $encryptedEmail;
            
            if (sendVerificationEmail($email, "Verificaçao de Email", "Por favor, verifique seu email para efetuar login.", $verificationLink)) {
                $_SESSION['success'] = "Verifique seu email para concluir o registro.";
            } else {
                $_SESSION['error'] = "Falha ao enviar email de verificação.";
            }
        }
    } else {
        $_SESSION['error'] = "Email não encontrado.";
    }
    header("Location: /login");
}

function changeUserPassword($email, $newPassword) {
    global $arrConfig; // Access global configuration and encryption key if needed

    $dbConn = db_connect(); // Establish database connection

    // Fetch the username for the email provided
    $fetchUsernameQuery = "SELECT username FROM users WHERE user_email = ? LIMIT 1";
    $result = executeQuery($dbConn, $fetchUsernameQuery, [$email]);
    if ($result && $result->num_rows > 0) {
        $userData = $result->fetch_assoc();
        $username = $userData['username']; // Assuming the column name is 'username'

        // Encrypt the username and email for the verification link
        $encryptedUsername = encrypt($username); // This assumes you have an encrypt function
        $encryptedEmail = encrypt($email);

        // Hash the new password using PHP's password_hash function
        $hashedNewPassword = password_hash($newPassword, PASSWORD_DEFAULT);

        // Encrypt the hashed new password for the verification link
        $encryptedNewPassword = encrypt($hashedNewPassword);

        // Create a verification link
        $verificationLink = "http://gentl.store/src/include/functions/verifyChangePassword.inc.php?username=" . urlencode($encryptedUsername) . "&email=" . urlencode($encryptedEmail) . "&newPassword=" . urlencode($encryptedNewPassword);

        // Send email with the verification link
        $subject = "Password Change Request";
        $message = "Hello " . $username . ",\n\nYou have requested to change your password. Please click the link below to verify your request and update your password.\n\nVerification Link: " . $verificationLink . "\n\nIf you did not request this change, please ignore this email.";

        if (sendVerificationEmail($email, $subject, $message, $verificationLink)) { // Adjusted to include verificationLink in the call
            $_SESSION['success'] = "A verification link has been sent to your email.";
        } else {
            $_SESSION['error'] = "Failed to send email with the verification link.";
        }
    } else {
        $_SESSION['error'] = "Email not found.";
    }
    header("Location: /login");
}