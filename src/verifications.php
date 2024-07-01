
<?php
// Check if the 'action' variable is set and not one of the expected values
if (!isset($_GET['action']) || ($_GET['action'] != 'email-resend' && $_GET['action'] != 'password-alter')) {
    // Set header to 404 Not Found
    header("HTTP/1.0 404 Not Found");
    exit();
}
?>
<?php
include "include/config.inc.php";

    include "include/functions/saveLastPage.inc.php";
    require "include/functions/Development.inc.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="shortcut icon" href="../src/assets/images/2.png" type="image/x-icon">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../src/css/accountLC.css">
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/2.2.0/uicons-solid-rounded/css/uicons-solid-rounded.css'>
     <link href="/dist/tailwind.css" rel="stylesheet" type="text/css" />
  <style>
    .error-container {
      position: fixed;
      top: 0;
      left: 50%;
      transform: translateX(-50%);
      width: 100%;
      min-height: 60px;
      text-align: center;
      z-index: 49; /* Ensure it's above other content */
    }
  </style>
    <title>Verificações</title>
</head>
<body class="bg-gray-900">

<?php echoLoadScreen(); ?>
<?php
if(isset($_SESSION['error'])) {
    echoError($_SESSION['error']);
    unset($_SESSION['error']);
} elseif(isset($_SESSION['success'])) {
    if ($_SESSION['success'] == 'Registration successful') {
        validRegisterAl();
    } else {
        echoSuccess($_SESSION['success']);
    }
    unset($_SESSION['success']);
}
?>

<div class="">
    <?php
    if (isset($_GET['action'])) {
        switch ($_GET['action']) {
            case 'email-resend':
                ?>
                <h2 class="text-2xl font-bold justify-center flex select-none text-white">Reenviar Email de Confirmação</h2>
                <form id="emailResend" method="post" class="space-y-3" action="/src/include/functions/resendEmail.inc.php">
                    <div class="space-y-2">
                        <input type="email" id="email" name="email" placeholder="Email" required class="w-full px-4 py-2 text-black bg-gray-200 border border-gray-300 rounded-md mb-2 mt-6 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent select-none">
                        <button type="submit" name="submit" value="loginSubmit" class="w-full px-4 py-2 text-white bg-gray-800 rounded-md hover:bg-orange-700 select-none">Enviar email</button>
                    </div>
                </form>
                <?php
                break;
            case 'password-alter':
                ?>
                <h2 class="text-2xl font-bold justify-center flex select-none text-white">Alterar Senha</h2>
                <form id="passwordChange" method="post" class="space-y-3" action="/src/include/functions/resendEmail.inc.php">
                    <div class="space-y-2">
                        <input type="email" id="email" name="email" placeholder="Email" required class="w-full px-4 py-2 text-black bg-gray-200 border border-gray-300 rounded-md mb-2 mt-6 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent select-none">
                        <input type="password" id="currentPassword" name="currentPassword" placeholder="Senha Atual" class="w-full px-4 py-2 text-black bg-gray-200 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent select-none">
                        <input type="password" id="newPassword" name="newPassword" placeholder="Nova Senha" required class="w-full px-4 py-2 text-black bg-gray-200 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent select-none">
                        <button type="submit" name="submit" value="passwordChangeSubmit" class="w-full px-4 py-2 text-white bg-gray-800 rounded-md hover:bg-orange-700 select-none">Alterar Senha</button>
                    </div>
                </form>
                <?php
                break;
            default:
                // Optionally handle unknown actions
                echo "<p>Ação desconhecida.</p>";
                break;
        }
    } else {
        // If 'action' is not set, you can display a default message or form
        echo "<p>Nenhuma ação selecionada.</p>";
    }
    ?>
</div>



<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="../dist/bundle.js"></script>
<script src="../src/js/social.js"></script>
</body>
</html>