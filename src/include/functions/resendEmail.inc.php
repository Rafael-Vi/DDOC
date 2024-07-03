userPasswordEmail($email, $newPassword);

<?php
require_once "../config.inc.php";

// Further processing based on $_POST['action']
if (isset($_POST['email']) && !empty($_POST['email'])) {
    echo "Email verificado.";
    $email = preg_replace('/\s+/', '', htmlspecialchars($_POST['email']));
    if ($_POST['action'] == 'email-resend') {
        verifyEmailExistsAndStatus($email);
        echo "Email de verificação enviado.";
    } elseif ($_POST['action'] == 'password-alter' && isset($_POST['newPassword'])) {
        $newPassword = $_POST['newPassword'];
        echo "Email de verificação de alteração de senha enviado.";
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
            echo "Email já verificado.";
            $_SESSION['error'] = "Este email já foi verificado.";
        } else {
            $username = $userData['user_name'];
            $encryptedUsername = encrypt(urlencode($username));
            $encryptedEmail = encrypt(urlencode($email));
            $verificationLink = "http://gentl.store/src/include/functions/verifyEmail.php?username=" . $encryptedUsername . "&email=" . $encryptedEmail;
            
            if (sendVerificationEmail($email, "Verificação de Email", "Por favor, verifique seu email para efetuar login.", $verificationLink)) {
                echo "Email de verificação enviado.";
                $_SESSION['success'] = "Verifique seu email para concluir o registro.";
            } else {
                echo "Falha ao enviar email de verificação.";
                $_SESSION['error'] = "Falha ao enviar email de verificação.";
            }
        }
    } else {
        echo "Email não encontrado.";
        $_SESSION['error'] = "Email não encontrado.";
    }
    header("Location: /login");
    exit(); // Ensure no further code is executed after redirect
}

function userPasswordEmail($email, $newPassword) {
    global $arrConfig;

    $dbConn = db_connect();

    $fetchUsernameQuery = "SELECT user_name FROM users WHERE user_email = ? LIMIT 1";
    $result = executeQuery($dbConn, $fetchUsernameQuery, [$email]);
    if ($result && $result->num_rows > 0) {
        $userData = $result->fetch_assoc();
        $username = $userData['user_name'];

        $encryptedUsername = encrypt(urlencode($username));
        $encryptedEmail = encrypt(urlencode($email));

        $hashedNewPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $encryptedNewPassword = encrypt($hashedNewPassword);

        $verificationLink = "http://gentl.store/src/include/functions/verifyChangePassword.inc.php?username=" . urlencode($encryptedUsername) . "&email=" . urlencode($encryptedEmail) . "&newPassword=" . urlencode($encryptedNewPassword);

        if (sendVerificationEmail($email, "Mudar Password", "Se não tiver pedido a mudança da password da sua conta não clicque neste link.", $verificationLink)) {
            echo "Email de verificação de alteração de senha enviado.";
            $_SESSION['success'] = "Um link de verificação foi enviado para o seu email.";
        } else {
            echo "Falha ao enviar email de verificação de alteração de senha.";
            $_SESSION['error'] = "Falha ao enviar email com o link de verificação.";
        }
    } else {
        echo "Email não encontrado.";
        $_SESSION['error'] = "Email não encontrado.";
    }
    header("Location: /login");
    exit();
}
