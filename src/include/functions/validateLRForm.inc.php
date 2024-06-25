<?php

if (isset($_POST['submit'])) {

    if ($_POST['submit'] === 'loginSubmit') {

        if (isset($_POST['emailL']) && isset($_POST['passwordL']) && !empty($_POST['emailL']) && !empty($_POST['passwordL'])) {
            $email = preg_replace('/\s+/', '', htmlspecialchars($_POST['emailL']));
            $password = htmlspecialchars($_POST['passwordL']);

            validarLogin($email, $password);
        }
    }

    elseif ($_POST['submit'] === 'registerSubmit') {

        if (isset($_POST['usernameR']) && isset($_POST['emailR']) && isset($_POST['passwordR']) && !empty($_POST['usernameR']) && !empty($_POST['emailR']) && !empty($_POST['passwordR'])) {
            $username = preg_replace('/\s+/', '', htmlspecialchars($_POST['usernameR']));
            $email = strtolower(trim(htmlspecialchars($_POST['emailR'])));
            $password = htmlspecialchars($_POST['passwordR']);
            validarRegistro($username, $email, $password);
        }

    }
}


function validarLogin($email, $password) {
    global $arrConfig;

    // Verificar se o email é válido
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Se o email não for válido, tentar usar o nome de usuário em vez disso
        $email = preg_replace('/@/', '', $email); // Remover o símbolo @ para tratá-lo como um nome de usuário
        error_log($email); // Registrar o email modificado para fins de depuração
    }

    $dbConn = db_connect();

    // Consulta SQL ajustada para selecionar também a senha para verificação
    $sql = "SELECT id_users, can_post, user_name, user_profilePic, user_password FROM users WHERE user_email =? OR user_name =?";

    $result = executeQuery($dbConn, $sql, [$email, $email]); // Passar tanto o email quanto o nome de usuário para a consulta

    $userDetails = mysqli_fetch_assoc($result);

    if ($userDetails) {
        // Verificar a senha
        if (password_verify($password, $userDetails['user_password'])) {
            // A senha está correta, prosseguir com o login
            $_SESSION['uid'] = $userDetails['id_users'];
            $_SESSION['can_post'] = $userDetails['can_post'];
            $_SESSION['imageProfile'] = $arrConfig['url_users'].$userDetails['user_profilePic'];
            $_SESSION['username'] = '@'.$userDetails['user_name'];
            header("Location: social");
            exit;
        } else {
            // A senha está incorreta
            $_SESSION['error'] = "Autenticação falhou!";
        }
    } else {
        $_SESSION['error'] = "Utilizador não encontrado!";
    }

    mysqli_close($dbConn);
}

function validarRegistro($username, $email, $password) {
    $emailSql = "SELECT user_email FROM users WHERE user_email =?";
    $usernameSql = "SELECT user_name FROM users WHERE user_name =?";

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

    // Supondo que newUser() adiciona o novo usuário ao banco de dados
    newUser($dbConn, $email, $username, $password);


    global $EncKey; // Ensure the global encryption key is accessible
    
    // Encrypt the username and email
    $encryptedUsername = encrypt(urlencode($username), $EncKey);

    $encryptedEmail = encrypt(urlencode($email), $EncKey);

    // Generate the verification link with encrypted data
    $verificationLink = "http://gentl.store/src/include/functions/verifyEmail.php?username=" . $encryptedUsername . "&email=" . $encryptedEmail;
    
    // Send the verification email
    if (sendVerificationEmail($email, "Verificacão de Email", "Por favor, verifique seu email para efetuar login.", $verificationLink)) {
        $_SESSION['success'] = "Verifique seu email para concluir o registro.";
    } else {
        $_SESSION['error'] = "Falha ao enviar email de verificação.";

}
}