<?php
include "include/config.inc.php";

  include "include/functions/checkLogin.inc.php";

      require "include/functions/checkThemeIsFinished.inc.php";

     if (checkThemeIsFinished()){
    include "include/functions/saveLastPage.inc.php";
  }
  require "include/functions/Development.inc.php";


$userInfo = getUserInfo($_GET['userid']);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../src/css/social.css">    <link rel="shortcut icon" href="./assets/images/2.png" >
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/2.2.0/uicons-solid-rounded/css/uicons-solid-rounded.css'>
    <link href="/dist/tailwind.css" rel="stylesheet" type="text/css" />
    <title><?php echo $userInfo['username']; ?></title>
</head>
<body class="h-full flex">
    <?php if ($_SESSION['uid'] == $_GET['userid']) {
    header("Location: profile.php");
    exit;
    }?>
    <?php echoLoadScreen(); ?>   
    <?php echoNav(); ?>
    <div id="profile-div" class="fixed flex flex-col h-full w-full md:w-9/12 p-0 m-0 bg-gray-900 md:right-0">
       
       <div id="profileInfo-div" class="b-8 z-20 relative w-full flex flex-row justify-between  h-60 md:h-80 pl-4 pr-4 sm:text-right pb-4">
       <a href="javascript:history.back()" class="btn mt-8 hidden sm:flex">Voltar atrás</a>
          <div class="flex  h-32 text-white lg:h-64 mt-8 w-4/6">
              <div class="h-full w-full mt-0 md:mt-8 mb-4">
                <?php
                  echoProfileInfo($userInfo['username'], $userInfo['email'], $userInfo['profilePic'], $userInfo['realName'], $userInfo['biography'], $userInfo['rank']);
                  unset($userInfo);
                  echo '
                    <div class="flex justify-center">
                      <a href="./messages.php?convo_id=' . $_GET['userid'] . '" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded m-4">
                        Message
                      </a>
                      <button class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded m-4" id="follow-button" onclick="followCheck()">
                        Follow
                      </button>
                    </div>
                  ';
                  echo '</div>';
                  echo '</div>';
                ?>


            </div>
           </div>
       <div id="profilePosts-div" class="relative w-full p-auto overflow-auto bg-base-200 min-h-screen">
         <?php
            getPosts($_GET['userid']);
         ?>
       </div>
   </div>
  <?php echoBottomNav(); ?>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script>
  var targetDateFromPHP = <?php echo json_encode($_SESSION['themes'][0]['finish_date']); ?>;
</script>

  <script src="../src/js/timer.js"></script>

  <script src="../src/js/social.js"></script>
  <script src="../src/js/follow.js"></script>
</body>
</html>