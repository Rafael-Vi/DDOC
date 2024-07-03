<?php
// Include your SQLfunctions.inc.php file
require_once '/var/www/DDOC/src/include/config.inc.php';

$encryptedUsername = $_GET['username'] ?? '';
$encryptedEmail = $_GET['email'] ?? '';
$encryptedNewPassword = $_GET['newPassword'] ?? '';

// Descriptografe os parâmetros para obter os valores originais
$decryptedUsername = decrypt($encryptedUsername); // Use sua função de descriptografia existente
$decryptedEmail = decrypt($encryptedEmail);
$decryptedNewPassword = decrypt($encryptedNewPassword);
// Agora, use a função verifyChangePassword com os valores descriptografados
verifyChangePassword($decryptedUsername, $decryptedEmail, $decryptedNewPassword);

function verifyChangePassword($username, $email, $newPassword) {
    global $arrConfig; // Access global configuration and encryption key if needed

    $dbConn = db_connect(); // Establish database connection
~~

    // Verify if the username and email belong to the same account
    $verifyAccountQuery = "SELECT * FROM users WHERE user_name = ? AND user_email = ? LIMIT 1";
    $result = executeQuery($dbConn, $verifyAccountQuery, [$username, $email]);
    if ($result && $result->num_rows > 0) {
        // Account verified, proceed with updating the password
        updatePassword($dbConn, $username, $newPassword); // Use the decrypted (hashed) new password
        $_SESSION['success'] = "Sua senha foi atualizada com sucesso.";
    } else {
        // Account verification failed
        $_SESSION['error'] = "Falha na verificação da conta. As informações fornecidas não correspondem aos nossos registros.";
    }
    header("Location: /login");
}

function updatePassword($dbConn, $username, $newPassword) {

    // Atualize a senha do usuário no banco de dados
    $updatePasswordQuery = "UPDATE users SET user_password = ? WHERE user_name = ?";
    $result = executeQuery($dbConn, $updatePasswordQuery, [$newPassword, $username]);
    if ($result) {
        // Atualização de senha bem-sucedida
        return true;
    } else {
        // Falha na atualização da senha
        $_SESSION['error'] = "Falha ao atualizar a senha.";
        return false;
    }
}