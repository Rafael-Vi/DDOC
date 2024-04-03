<?php
include "include/config.inc.php";
?>

<?php
  include "include/functions/checkLogin.inc.php";
?> <?php
      require "include/functions/checkThemeIsFinished.inc.php";
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../src/css/social.css">
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/2.2.0/uicons-solid-rounded/css/uicons-solid-rounded.css'>
    <script src="https://cdn.tailwindcss.com"></script>     <link href="https://cdn.jsdelivr.net/npm/daisyui@4.7.2/dist/full.min.css" rel="stylesheet" type="text/css" />
    <title>Settings</title>
</head>
<body class="h-full flex">
    <?php echoLoadScreen(); ?>    <?php
        //echoShowTheme();
    ?>
    <?php echoNav(); ?>
    <div id="settings-div" class=" bg-gray-800 fixed flex flex-col h-full w-full md:w-9/12 p-0 m-0 md:right-0 overflow-auto">
        <h1 class="h-32 border text-center sm:text-start border-black w-full p-10 font-bold text-4xl shadow-md shadow-amber-600 bg-gray-800 sticky top-0">
        Settings
        </h1>

        <details class="w-full h-auto">
           <summary class="h-32 text-center sm:text-start w-full p-10 font-bold text-4xl bg-gray-700">
            <i class="fi fi-sr-universal-access mr-5"></i></i>Acessibility
           </summary>
            <div class="bg-gray-800 p-4 rounded-md border-b-2 pl-10">
                <h2 class="text-2xl font-bold text-center sm:text-start mb-4"> <i class="fi fi-sr-language mr-5"></i>Language</h2>
                <ul class="space-y-2">
                <button class="btn hover:text-orange-500 text-white ubuntu-medium text-lg bg-red-500">Portuguese</button>
                <button class="btn hover:text-orange-500 text-white ubuntu-medium text-lg bg-blue-800">English</button>
                </ul>
            </div>
            <div class="bg-gray-800 p-4 rounded-md border-b-2 pl-10">
                <h2 class="text-2xl font-bold text-center sm:text-start mb-4">Theme</h2>
                <ul class="space-y-2">
                <button class="btn hover:text-orange-500 text-white ubuntu-medium text-lg bg-gray-700">Dark</button>
                <button class="btn hover:text-orange-500 text-black ubuntu-medium text-lg bg-gray-200">Light</button>
                </ul>
            </div>
        </details>
        <details class="h-auto w-full">
           <summary class="h-32 text-center sm:text-start w-full p-10 font-bold text-4xl bg-gray-700">
            <i class="fi fi-sr-portrait mr-5"></i>  Account
           </summary>
            <div class="bg-gray-800 p-4 rounded-md pl-10 border-b-2">
                <h2 class="text-2xl font-bold text-center sm:text-start mb-4">Actions</h2>
                <ul class="space-y-2">
                    <li class=" btn text-lg ubuntu-medium text-red-500">Delete Account</li>
                    <li class="btn text-lg ubuntu-medium text-blue-500 " onclick="logout()">Logout</li>
                </ul>
            </div>
        </details>
    </div>
    <?php echoBottomNav(); ?>
    <script>
  var targetDateFromPHP = <?php echo json_encode($_SESSION['themes'][0]['finish_date']); ?>;
</script>  <script src="../src/js/timer.js"></script>

  <script src="../src/js/social.js"></script>
</body>
</html>