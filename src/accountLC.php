
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
    <title>Login & Register</title>
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
<div class="flip-container">
    <div class="flipper" id="flipper">
        <div class="front">
            <h2 class="text-2xl font-bold justify-center flex select-none text-black">Login</h2>
            <form id="loginForm" method="post" class="space-y-3">
                <div class="space-y-2">
                    <input type="text" id="emailL" name="emailL" placeholder="Email/Username" required class="w-full px-4 py-2 text-black bg-gray-200 border border-gray-300 rounded-md mb-2 mt-6 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent select-none">
                    <input type="password" id="passwordL" name="passwordL" placeholder="Password" required class="w-full text-black bg-gray-200 px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent select-none">
                    <button type="submit" name="submit" value="loginSubmit" class="w-full px-4 py-2 text-white bg-gray-800 rounded-md hover:bg-orange-700 select-none">Login</button>
                </div>
            </form>
            <a class="flipbutton justify-center flex mt-4 select-none" id="loginButton" href="#">Create my account →</a>
        </div>
        
        <div class="back">
            <h2 class="text-2xl font-bold justify-center flex select-none text-black">Register</h2>
            <form id="registerForm" method="post" class="space-y-3">
                <div class="space-y-2">
                    <input type="text" id="usernameR" name="usernameR" placeholder="Username" required class="bg-gray-200 select-none w-full px-4 py-2 border border-gray-300 text-black rounded-md mb-2 mt-6 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                    <input type="email" id="emailR" name="emailR" placeholder="Email" required class=" select-none w-full bg-gray-200 px-4 py-2 border border-gray-300 rounded-md text-black mb-2 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                    <input type="password" id="passwordR" name="passwordR" placeholder="Password" required pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*\W).{8,}" title="Must contain at least one number, one uppercase letter, one lowercase letter, one special character, and at least 8 or more characters" class="bg-gray-200 text-black select-none w-full px-4 py-2 border border-gray-300 mb-2 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                    <button type="submit" name="submit" value="registerSubmit" class="w-full px-4 py-2 text-white bg-gray-800 rounded-md hover:bg-orange-700 select-none">Register</button>
                </div>
            </form>
            <a class="flipbutton justify-center flex mt-4 select-none" id="registerButton" href="#">Login to my account</a>
        </div>
    </div>
</div>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="../dist/bundle.js"></script>
<script src="../src/js/social.js"></script>
</body>
</html>