<?php
include "include/config.inc.php";
?>

<?php
  include "include/functions/checkLogin.inc.php";
?> <?php
      require "include/functions/checkThemeIsFinished.inc.php";
?>

<?php
     if (checkThemeIsFinished()){
    include "include/functions/saveLastPage.inc.php";
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../src/css/social.css">    <link rel="shortcut icon" href="./assets/images/2.png" >
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/2.2.0/uicons-solid-rounded/css/uicons-solid-rounded.css'>
    <script src="https://cdn.tailwindcss.com"></script>     <link href="https://cdn.jsdelivr.net/npm/daisyui@4.7.2/dist/full.min.css" rel="stylesheet" type="text/css" />
    <title>{Other Person's Name}</title>
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
       <a href="javascript:history.back()" class="btn mt-8">Go Back</a>
          <div class="flex  h-32 text-white lg:h-64 mt-8 w-4/6">
              <div class="h-full w-full mt-0 md:mt-8 mb-4">
                <?php
                    getUserInfo($_GET['userid']); 
                ?>  

             <div class="flex justify-center">


                      <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded m-4" >
                          Message
                      </button>
                    
                      <button class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded m-4" id="follow-button" onclick="followCheck()">
                          Follow
                      </button>


            </div>
            </div>
           </div>
       <div id="profilePosts-div" class="relative p-auto overflow-auto bg-gray-800">
         <?php
            getPosts($_GET['userid']);
         ?>
       </div>
   </div>
  <?php echoBottomNav(); ?>
  <script>
  var targetDateFromPHP = <?php echo json_encode($_SESSION['themes'][0]['finish_date']); ?>;
</script>

  <script src="../src/js/timer.js"></script>

  <script src="../src/js/social.js"></script>
  <script src="../src/js/follow.js"></script>
</body>
</html>