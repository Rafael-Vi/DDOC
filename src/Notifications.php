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
    <link rel="stylesheet" href="../src/css/social.css">    <link rel="shortcut icon" href="./assets/images/2.png" >
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/2.2.0/uicons-solid-rounded/css/uicons-solid-rounded.css'>
    <script src="https://cdn.tailwindcss.com"></script>     <link href="https://cdn.jsdelivr.net/npm/daisyui@4.7.2/dist/full.min.css" rel="stylesheet" type="text/css" />
    <title>Notifications</title>
</head>
<body class="h-full flex">
    <?php echoLoadScreen(); ?>    <?php
        //echoShowTheme();
    ?>
    <?php echoNav(); ?>
    <div id="notifications-div" class="bg-gray-900 fixed flex flex-col h-full w-full md:w-9/12 p-0 m-0 md:right-0">
        
        <h1 class="h-32 border text-center sm:text-start border-black w-full p-10 font-bold text-4xl shadow-md  shadow-amber-600 bg-gray-800">
        Notifications
        </h1>

        <div name="notifications-container" id="notifications-container"" class="h-full border border-black w-full p-10 overflow-auto">
        <?php getNotif(); ?>
        </div>
    </div>
    <?php echoBottomNav(); ?>
  <script>
    var themes = <?php echo json_encode($theme['finish_date']); ?>;
  </script>
  <script>
  var targetDateFromPHP = <?php echo json_encode($_SESSION['themes'][0]['finish_date']); ?>;
</script>
  <script src="../src/js/timer.js"></script>
  <script src="../src/js/deleteNotification.js"></script>
  <script src="../src/js/social.js"></script>
</body>
</html>