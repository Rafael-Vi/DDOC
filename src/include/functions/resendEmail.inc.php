<?php
require_once "../config.inc.php";

// Further processing based on $_POST['action']
if (isset($_POST['email']) && !empty($_POST['email'])) {
    echo "Email verified.";
    $email = preg_replace('/\s+/', '', htmlspecialchars($_POST['email']));
    if ($_POST['action'] == 'email-resend') {
        verifyEmailExistsAndStatus($email);
        echo "Email verification sent.";
    } elseif ($_POST['action'] == 'password-alter' && isset($_POST['newPassword'])) {
        $newPassword = $_POST['newPassword'];
        userPasswordEmail($email, $newPassword);
        echo "Password change verification email sent.";
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
            echo "Email already verified.";
            $_SESSION['error'] = "Este email já foi verificado.";
        } else {
            $username = $userData['user_name'];
            $encryptedUsername = encrypt(urlencode($username));
            $encryptedEmail = encrypt(urlencode($email));
            $verificationLink = "http://gentl.store/src/include/functions/verifyEmail.php?username=" . $encryptedUsername . "&email=" . $encryptedEmail;
            
            if (sendVerificationEmail($email, "Verificaçao de Email", "Por favor, verifique seu email para efetuar login.", $verificationLink)) {
                echo "Verification email sent.";
                $_SESSION['success'] = "Verifique seu email para concluir o registro.";
            } else {
                echo "Failed to send verification email.";
                $_SESSION['error'] = "Falha ao enviar email de verificação.";
            }
        }
    } else {
        echo "Email not found.";
        $_SESSION['error'] = "Email não encontrado.";
    }
    header("Location: /login");
    exit(); // Ensure no further code is executed after redirect
}

function userPasswordEmail($email, $newPassword) {
    global $arrConfig; // Access global configuration and encryption key if needed

    $dbConn = db_connect(); // Establish database connection

    $fetchUsernameQuery = "SELECT username FROM users WHERE user_email = ? LIMIT 1";
    $result = executeQuery($dbConn, $fetchUsernameQuery, [$email]);
    if ($result && $result->num_rows > 0) {
        $userData = $result->fetch_assoc();
        $username = $userData['username'];

        $encryptedUsername = encrypt($username);
        $encryptedEmail = encrypt($email);

        $hashedNewPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $encryptedNewPassword = encrypt($hashedNewPassword);

        $verificationLink = "http://gentl.store/src/include/functions/verifyChangePassword.inc.php?username=" . urlencode($encryptedUsername) . "&email=" . urlencode($encryptedEmail) . "&newPassword=" . urlencode($encryptedNewPassword);

        if (sendVerificationEmail($email, "Mudar Password", "Se não tiver pedido a mudança da pssword da sua conta não clicque neste link.", $verificationLink)) {
            echo "Password change verification email sent.";
            $_SESSION['success'] = "A verification link has been sent to your email.";
        } else {
            echo "Failed to send password change verification email.";
            $_SESSION['error'] = "Failed to send email with the verification link.";
        }
    } else {
        echo "Email not found.";
        $_SESSION['error'] = "Email not found.";
    }
    header("Location: /login");
    exit(); // Ensure no further code is executed after redirect
}
