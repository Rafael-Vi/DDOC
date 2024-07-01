<?php
include "../config.inc.php";

$encryptedUsername = $_GET['username'] ?? '';
$encryptedEmail = $_GET['email'] ?? '';
$encryptedNewPassword = $_GET['newPassword'] ?? '';

// Descriptografe os parâmetros para obter os valores originais
$decryptedUsername = decrypt($encryptedUsername); // Use sua função de descriptografia existente
$decryptedEmail = decrypt($encryptedEmail);
// Agora, use a função verifyChangePassword com os valores descriptografados
verifyChangePassword($decryptedUsername, $decryptedEmail, $encryptedNewPassword );

function verifyChangePassword($username, $email, $newPassword) {
    global $arrConfig; // Access global configuration and encryption key if needed

    $dbConn = db_connect(); // Establish database connection

    // Decrypt the username and email
    $decryptedUsername = decrypt($username);
    $decryptedEmail = decrypt($email);
    // Decrypt the new password to get the hashed password back
    $decryptedNewPassword = decrypt($newPassword);

    // Verify if the username and email belong to the same account
    $verifyAccountQuery = "SELECT * FROM users WHERE user_name = ? AND user_email = ? LIMIT 1";
    $result = executeQuery($dbConn, $verifyAccountQuery, [$decryptedUsername, $decryptedEmail]);
    if ($result && $result->num_rows > 0) {
        // Account verified, proceed with updating the password
        updatePassword($dbConn, $decryptedUsername, $decryptedNewPassword); // Use the decrypted (hashed) new password
        $_SESSION['success'] = "Sua senha foi atualizada com sucesso.";
    } else {
        // Account verification failed
        $_SESSION['error'] = "Falha na verificação da conta. As informações fornecidas não correspondem aos nossos registros.";
    }
    header("Location: /login");
}

function updatePassword($dbConn, $username, $newPassword) {
    // Hash a nova senha
    $hashedNewPassword = $newPassword;

    // Atualize a senha do usuário no banco de dados
    $updatePasswordQuery = "UPDATE users SET user_password = ? WHERE user_name = ?";
    $result = executeQuery($dbConn, $updatePasswordQuery, [$hashedNewPassword, $username]);
    if ($result) {
        // Atualização de senha bem-sucedida
        return true;
    } else {
        // Falha na atualização da senha
        $_SESSION['error'] = "Falha ao atualizar a senha.";
        return false;
    }
}