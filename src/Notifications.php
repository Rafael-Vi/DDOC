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
      <link href="/dist/tailwind.css" rel="stylesheet" type="text/css" />
    <title>Notifications</title>
</head>
<body class="h-full flex">
    <?php echoLoadScreen(); ?>    <?php
        //echoShowTheme();
    ?>
    <?php echoNav(); ?>
    <div id="notifications-div" class="bg-gray-900 fixed flex flex-col h-full w-full md:w-9/12 p-0 m-0 md:right-0">
        
      <div class="h-32 text-center sm:text-start w-full p-10 font-bold text-4xl text-white sticky top-0 flex items-center justify-left gap-8 backdrop-blur-md">
  <a href="javascript:history.back()" class="btn">Voltar atrás</a>
        Notifications
      </div>

        <div name="notifications-container" id="notifications-container" class="h-full w-full px-10 overflow-auto">
          <div class="mt-8 mb-8 float-right" id="delete-all-notif">
            <button class="btn btn-danger" onclick="deleteAllNotifications()">Delete All Notifications</button>
          </div>
        <?php setNotifRead()?>
        <?php getNotif(); ?>
        </div>
    </div>
    <?php echoBottomNav(); ?>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script>
    var themes = <?php echo json_encode($_SESSION['themes'][0]['finish_date']); ?>;
  </script>
  <script>
  var targetDateFromPHP = <?php echo json_encode($_SESSION['themes'][0]['finish_date']); ?>;
</script>
  <script src="../src/js/timer.js"></script>
  <script src="../src/js/Notification.js"></script>
  <script src="../src/js/social.js"></script>
</body>
</html>