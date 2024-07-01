<?php

$encryptedUsername = $_GET['username'] ?? '';
$encryptedEmail = $_GET['email'] ?? '';
$encryptedNewPassword = $_GET['newPassword'] ?? '';

// Descriptografe os parâmetros para obter os valores originais
$decryptedUsername = decrypt($encryptedUsername); // Use sua função de descriptografia existente
$decryptedEmail = decrypt($encryptedEmail);
// Agora, use a função verifyChangePassword com os valores descriptografados
verifyChangePassword($decryptedUsername, $decryptedEmail, $encryptedNewPassword );

function verifyChangePassword($username, $email, $newPassword) {
    global $arrConfig; // Acesse a configuração global e a chave de criptografia, se necessário

    $dbConn = db_connect(); // Estabeleça a conexão com o banco de dados

    // Descriptografe o nome de usuário e o email
    $decryptedUsername = decrypt($username); // Supondo que você tenha uma função de descriptografia
    $decryptedEmail = decrypt($email);

    // Verifique se o nome de usuário e o email pertencem à mesma conta
    $verifyAccountQuery = "SELECT * FROM users WHERE user_name = ? AND user_email = ? LIMIT 1";
    $result = executeQuery($dbConn, $verifyAccountQuery, [$decryptedUsername, $decryptedEmail]);
    if ($result && $result->num_rows > 0) {
        // Conta verificada, prossiga com a atualização da senha
        updatePassword($dbConn, $decryptedUsername, $newPassword);
        $_SESSION['success'] = "Sua senha foi atualizada com sucesso.";
    } else {
        // Falha na verificação da conta
        $_SESSION['error'] = "Falha na verificação da conta. As informações fornecidas não correspondem aos nossos registros.";
    }
    header("Location: /login");
}

function updatePassword($dbConn, $username, $newPassword) {
    // Hash a nova senha
    $hashedNewPassword = password_hash($newPassword, PASSWORD_DEFAULT);

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