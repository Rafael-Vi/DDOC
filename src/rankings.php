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
    <title>Rankings Accounts</title>
</head>
<body class="h-full flex">
  <?php echoLoadScreen(); ?>    <?php
        //echoShowTheme();
    ?>
    <?php echoNav(); ?>
    <div id="Accrankings-div" class=" bg-gray-900 fixed flex flex-col h-full w-full md:w-9/12 p-0 m-0 md:right-0 overflow-auto">
    <h1 class="h-32 border text-center sm:text-start border-black w-full p-10 font-bold text-4xl shadow-md shadow-amber-600 bg-gray-800 sticky top-0">
      Account Rankings
    </h1>


    <div class="flex flex-col h-full w-full pt-6 pb-8 px-10">
        <div class="flex flex-col w-full p-10 sticky">
            <div class="flex justify-around pb-4 rounded-lg mb-4 border-b-8 border-amber-600">
                <?php
                global $arrConfig;
                 $podium = getPodium(2); ?>
                <div class="flex flex-col items-center mt-auto">
                    <?php if ($podium): ?>
                        <img src="<?php echo $arrConfig['url_users'] . $podium['image']; ?>" class="rounded-full w-20 h-20 mb-2">
                        <h1 class="mb-2">@<?php echo $podium['username']; ?></h1>
                        <div class="bg-gray-800 rounded-lg text-center p-4 h-24 w-28 relative flex items-center justify-center m-4 sm:m-0">Second</div>
                    <?php else: ?>
                        <h1 class="mb-2">No user found with this rank.</h1>
                    <?php endif; ?>
                </div>
                <?php $podium = getPodium(1); ?>
                <div class="flex flex-col items-center mt-auto">
                    <?php if ($podium): ?>
                        <img src="<?php echo $arrConfig['url_users'] . $podium['image']; ?>" class="rounded-full w-20 h-20 mb-2">
                        <h1 class="mb-2">@<?php echo $podium['username']; ?></h1>
                        <div class="bg-gray-800 rounded-lg text-center p-4 h-28 w-28 relative flex items-center justify-center m-4 sm:m-0">First Place</div>
                    <?php else: ?>
                        <h1 class="mb-2">No user found with this rank.</h1>
                    <?php endif; ?>
                </div>
                <?php $podium = getPodium(3); ?>
                <div class="flex flex-col items-center mt-auto">
                    <?php if ($podium): ?>
                        <img src="<?php echo $arrConfig['url_users'] . $podium['image']; ?>" class="rounded-full w-20 h-20 mb-2">
                        <h1 class="mb-2">@<?php echo $podium['username']; ?></h1>
                        <div class="bg-gray-800 rounded-lg text-center p-4 h-20 w-28 relative flex items-center justify-center m-4 sm:m-0">Third</div>
                    <?php else: ?>
                        <h1 class="mb-2">No user found with this rank.</h1>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="overflow-y-auto h-96 flex flex-col items-center bg-gray-800">
            <div class="flex w-full text-center justify-center bg-gray-800 p-4 text-lg text-white border-b-2 border-gray-900 items-center">
                <div class="ubuntu-bold w-2/6">Rank</div>
                <div class="ubuntu-bold w-2/6">Likes</div> <!-- Likes -->
                <div class="ubuntu-bold w-2/6">Account</div> <!-- Person who posted it -->
            </div>
            <div class="h-full overflow-y-auto w-full">
                <?php 
                    RankingAcc();
                ?>
            </div>
            <!-- End of post div -->
        </div>
    </div>
<script>
  var targetDateFromPHP = <?php echo json_encode($_SESSION['themes'][0]['finish_date']); ?>;
</script>
  <script src="../src/js/timer.js"></script>

  <script src="../src/js/social.js"></script>
</body>
</html>