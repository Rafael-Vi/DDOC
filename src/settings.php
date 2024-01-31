<?php
include "include/config.inc.php";
?>

<?php
  include "include/functions/checkLogin.inc.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../src/css/social.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Settings</title>
</head>
<body class="h-full flex">
    <?php echoNav(); ?>
    <div id="settings-div" class=" bg-gray-900 fixed flex flex-col h-full w-full md:w-9/12 p-0 m-0 md:right-0 overflow-auto overflow-auto">
        <h1 class="h-32 border text-center sm:text-start border-black w-full p-10 font-bold text-4xl shadow-md shadow-amber-600 bg-gray-800 sticky top-0">
        Settings
        </h1>

        <div class="h-full w-full">
            <h1 class="h-32 text-center sm:text-start w-full p-10 font-bold text-4xl rounded-md bg-gray-700">
                    Stuff
            </h1>
            <div class="bg-gray-800 p-4 rounded-md">
                <h2 class="text-2xl font-bold text-center sm:text-start mb-4">Smaller Stuff</h2>
                <ul class="space-y-2">
                    <li class="text-lg text-gray-300">Item 1</li>
                    <li class="text-lg text-gray-300">Item 2</li>
                    <li class="text-lg text-gray-300">Item 3</li>
                    <!-- Add as many items as you need -->
                </ul>
            </div>
        </div>
        <div class="h-full w-full">
            <h1 class="h-32 text-center sm:text-start w-full p-10 font-bold text-4xl rounded-md bg-gray-700">
                    Stuff
            </h1>
            <div class="bg-gray-800 p-4 rounded-md">
                <h2 class="text-2xl font-bold text-center sm:text-start mb-4">Smaller Stuff</h2>
                <ul class="space-y-2">
                    <li class="text-lg text-gray-300">Item 1</li>
                    <li class="text-lg text-gray-300">Item 2</li>
                    <li class="text-lg text-gray-300">Item 3</li>
                    <!-- Add as many items as you need -->
                </ul>
            </div>
        </div>
        <div class="h-full w-full">
            <h1 class="h-32 text-center sm:text-start w-full p-10 font-bold text-4xl rounded-md bg-gray-700">
                    Stuff
            </h1>
            <div class="bg-gray-800 p-4 rounded-md">
                <h2 class="text-2xl font-bold text-center sm:text-start mb-4">Smaller Stuff</h2>
                <ul class="space-y-2">
                    <li class="text-lg text-gray-300">Item 1</li>
                    <li class="text-lg text-gray-300">Item 2</li>
                    <li class="text-lg text-gray-300">Item 3</li>
                    <!-- Add as many items as you need -->
                </ul>
            </div>
        </div>
    </div>
    <?php echoBottomNav(); ?>
    <script src="../src/js/social.js"></script>
</body>
</html>