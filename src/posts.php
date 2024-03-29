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
    <script src="https://cdn.tailwindcss.com"></script>     <link href="https://cdn.jsdelivr.net/npm/daisyui@4.7.2/dist/full.min.css" rel="stylesheet" type="text/css" />
    <title><?php echo $_GET['id'];?></title>
</head>
<body class="h-full flex">
    <?php echoLoadScreen(); ?>   
    <?php echoNav(); ?>

    <div id="ThisPost-div" class="bg-gray-900 fixed flex flex-col h-full w-full md:w-9/12 p-0 m-0 md:right-0">
        
        <h1 class="h-32 border text-center sm:text-start border-black w-full p-10 font-bold text-4xl shadow-md  shadow-amber-600 bg-gray-800">
            <?php echo"nome desta Cena"?>
        </h1>

        <div class="h-full border border-black w-full p-10">
            <div class="h-full border border-black w-full p-10 flex flex-col relative bottom-0 overflow-auto">
                <?php 
                    showPost($_GET['id']);
                ?>
            </div>
        </div>
    </div>
  
  <?php echoBottomNav(); ?>
<script>
    var currentPost = <?php echo json_encode($_GET['id']); ?>;
</script><script>
  var targetDateFromPHP = <?php echo json_encode($_SESSION['themes'][0]['finish_date']); ?>;
</script>
  <script src="../src/js/timer.js"></script>

  <script src="../src/js/social.js"></script>
  <script src="../src/js/EditProfile.js"></script>
  <script src="../src/js/openPosts.js"></script>
  <script src="../src/js/like.js"></script>
</body>
</html>