<?php
include "include/config.inc.php";

  include "include/functions/checkLogin.inc.php";

      require "include/functions/checkThemeIsFinished.inc.php";

     if (checkThemeIsFinished()){
    include "include/functions/saveLastPage.inc.php";
  }
  require "include/functions/Development.inc.php";
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../src/css/social.css">    <link rel="shortcut icon" href="./assets/images/2.png" >
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
    <div class="h-32 text-center sm:text-start w-full p-10 font-bold text-4xl text-white sticky top-0 flex items-center justify-left gap-8">
      <a href="javascript:history.back()" class="btn">Voltar atrás</a>
      Settings
    </div>


        <div class="h-auto w-full">
           <div class=" h-32 text-white text-center sm:text-start w-full p-10 font-bold text-4xl bg-gray-700">
            <i class="fi fi-sr-portrait mr-5"></i>  Account
           </div>
            <div class="bg-gray-800 p-4 rounded-md pl-10 ">
                <h2 class="text-2xl font-bold text-center sm:text-start mb-4 text-white">Actions</h2>
                <ul class="space-y-2">
                    <li class=" btn text-lg ubuntu-medium text-red-500">Delete Account</li>
                    <li class="btn text-lg ubuntu-medium text-orange-500 " onclick="logout('DDOC')">Logout</li>
                </ul>
            </div>
        </div>
    </div>
    <?php echoBottomNav(); ?>
    <script>
  var targetDateFromPHP = <?php echo json_encode($_SESSION['themes'][0]['finish_date']); ?>;
</script>  <script src="../src/js/timer.js"></script>

  <script src="../src/js/social.js"></script>
</body>
</html>
