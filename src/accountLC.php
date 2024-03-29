
<?php
include "include/config.inc.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../src/css/accountLC.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Login & Register</title>
</head>
<body class="bg-gray-900">

<?php echoLoadScreen(); ?>
<div class="flip-container">
    <div class="flipper" id="flipper">
        <div class="front">
            <h2 class="text-2xl font-bold justify-center flex select-none">Login</h2>
            <form id="loginForm" method="post" class="space-y-3">
                <div class="space-y-2">
                    <input type="text" id="emailL" name="emailL" placeholder="Email/Username" required class="w-full px-4 py-2 border border-gray-300 rounded-md mb-2 mt-6 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent select-none">
                    <input type="password" id="passwordL" name="passwordL" placeholder="Password" required class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent select-none">
                    <button type="submit" name="submit" value="loginSubmit" class="w-full px-4 py-2 text-white bg-neutral-800 rounded-md hover:bg-orange-700 select-none">Login</button>
                </div>
            </form>
            <a class="flipbutton justify-center flex mt-4 select-none" id="loginButton" href="#">Create my account →</a>
        </div>
        
        <div class="back">
            <h2 class="text-2xl font-bold justify-center flex select-none">Register</h2>
            <form id="registerForm" method="post" class="space-y-3">
                <div class="space-y-2">
                    <input type="text" id="usernameR" name="usernameR" placeholder="Username" required class=" select-none w-full px-4 py-2 border border-gray-300 rounded-md mb-2 mt-6 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                    <input type="email" id="emailR" name="emailR" placeholder="Email" required class=" select-none w-full px-4 py-2 border border-gray-300 rounded-md mb-2 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                    <input type="password" id="passwordR" name="passwordR" placeholder="Password" required class=" select-none w-full px-4 py-2 border border-gray-300 mb-2 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                    <button type="submit" name="submit" value="registerSubmit" class="w-full px-4 py-2 text-white bg-neutral-800 rounded-md hover:bg-orange-700 select-none">Register</button>
                </div>
            </form>
            <a class="flipbutton justify-center flex mt-4 select-none" id="registerButton" href="#">Login to my account</a>
        </div>
    </div>
</div>



<script src="../dist/bundle.js"></script>
<script src="../src/js/social.js"></script>
</body>
</html>