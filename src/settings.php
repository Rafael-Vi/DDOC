<?php
  include "include/config.inc.php";
  include "include/functions/checkLogin.inc.php";
  require "include/functions/checkThemeIsFinished.inc.php";
  if (checkThemeIsFinished()){
    include "include/functions/saveLastPage.inc.php";
  }
  require "include/functions/Development.inc.php";
  if (basename($_SERVER['PHP_SELF']) === 'profile.php') {
    $userInfo = getUserInfo($_SESSION['uid']);
  } elseif (basename($_SERVER['PHP_SELF']) === 'OProfile.php') {
    $userInfo = getUserNotCurrent($_GET['userid']);
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../src/css/social.css">    <link rel="shortcut icon" href="./assets/images/2.png" >
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
    <title>Definições</title>
</head>
<body class="h-full flex">
    <?php echoLoadScreen(); ?>    <?php
        //echoShowTheme();
    ?>
    <?php echoNav(); ?>
     <?php
 
  if(isset($_SESSION['error'])) {
    echo'  <div class="error-container">';
      echoError($_SESSION['error']);
      unset($_SESSION['error']);
    echo'</div>';
  } elseif(isset($_SESSION['success'])) {
      if ($_SESSION['success'] == 'Registration successful') {
        echo'  <div class="error-container">';
          validRegisterAl();
          echo'</div>';
          
      } else {
        echo'  <div class="error-container">';
          echoSuccess($_SESSION['success']);
          echo'</div>';
      }
      unset($_SESSION['success']);
  }

  ?>
    <div id="settings-div" class=" bg-gray-800 fixed flex flex-col h-full w-full md:w-9/12 p-0 m-0 md:right-0 overflow-auto">
    <div class="h-32 text-center sm:text-start w-full p-10 font-bold text-4xl text-white sticky top-0 flex items-center justify-left gap-8">
      <a href="javascript:history.back()" class="btn">Voltar atrás</a>
      Definições
    </div>


        <div class="h-auto w-full">
           <div class=" h-32 text-white text-center sm:text-start w-full p-10 font-bold text-4xl bg-gray-700">
            <i class="fi fi-sr-portrait mr-5"></i>  Conta
           </div>
            <div class="bg-gray-800 p-4 rounded-md pl-10 ">
                <h2 class="text-2xl font-bold text-center sm:text-start mb-4 text-white">Ações</h2>
                <ul class="space-y-2">
                    <li class=" btn text-lg ubuntu-medium text-red-500" id="apagar-conta">Apagar Conta</li>
                    <li class="btn text-lg ubuntu-medium text-orange-500 " id="logout-button" onclick="logout()">Logout</li>
                </ul>
            </div>
        </div>
    </div>
    <?php echoBottomNav(); ?>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script>
  var targetDateFromPHP = <?php echo json_encode($_SESSION['themes'][0]['finish_date']); ?>;
</script>  <script src="../src/js/timer.js"></script>
<script src="../src/js/accountdelete.js"></script>
  <script src="../src/js/social.js"></script>
</body>
</html>
