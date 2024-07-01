<?php
require_once "../config.inc.php";

if (isset($_POST['email']) && !empty($_POST['email'])) {
    $email = preg_replace('/\s+/', '', htmlspecialchars($_POST['email']));
    verifyEmailExistsAndStatus($email);
}
function verifyEmailExistsAndStatus($email) {
    global $arrConfig; // Ensure the global encryption key and configuration are accessible

    $dbConn = db_connect(); // Establish database connection

    // Prepare the SQL query to select the user based on the provided email
    $query = "SELECT user_name, email_verify FROM users WHERE user_email = ? LIMIT 1";
    
    // Execute the query with the provided email
    $result = executeQuery($dbConn, $query, [$email]);
    
    if ($result && $result->num_rows > 0) {
        // Fetch the user data
        $userData = $result->fetch_assoc();
        
        if ($userData['email_verify']) {
            // If the user is already verified
            $_SESSION['error'] = "Este email já foi verificado.";
        } else {
            // If the user is not verified, proceed with the verification process
            $username = $userData['user_name'];
            
            // Encrypt the username and email
            $encryptedUsername = encrypt(urlencode($username));
            $encryptedEmail = encrypt(urlencode($email));

            // Generate the verification link with encrypted data
            $verificationLink = "http://gentl.store/src/include/functions/verifyEmail.php?username=" . $encryptedUsername . "&email=" . $encryptedEmail;
            
            // Send the verification email
            if (sendVerificationEmail($email, "Verificação de Email", "Por favor, verifique seu email para efetuar login.", $verificationLink)) {
                $_SESSION['success'] = "Verifique seu email para concluir o registro.";
            } else {
                $_SESSION['error'] = "Falha ao enviar email de verificação.";
            }
        }
    } else {
        // If no user is found with the provided email
        $_SESSION['error'] = "Email não encontrado.";
    }
    header("Location: /verificar-email-reenvio");
}