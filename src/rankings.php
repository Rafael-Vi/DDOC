<?php
include "include/config.inc.php";

  include "include/functions/checkLogin.inc.php";

      require "include/functions/checkThemeIsFinished.inc.php";

     if (checkThemeIsFinished()){
    include "include/functions/saveLastPage.inc.php";
    require "include/functions/Development.inc.php";
  }

require "include/functions/checkFilterVars.inc.php";

// Start the session if it's not already started
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}
// Call the checkTypeVarAdmin function
$type = checkTypeVar();

// If the return value is null, redirect and exit
if ($type == null || $type == 0) {
  $type = "none";
}

// Set the global type variable
$GLOBALS['type'] = $type;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../src/css/social.css">    <link rel="shortcut icon" href="./assets/images/2.png" >
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
    <div class="h-32 text-center sm:text-start w-full p-10 font-bold text-4xl text-white sticky top-0 flex items-center justify-left gap-8 backdrop-blur-md">
<a href="javascript:history.back()" class="btn">Voltar atrás</a>
      Account Rankings
    </div>


    <div class="flex flex-col h-full w-full pt-6 pb-8 px-10">
        <div class="flex flex-col w-full px-10 sticky">
            <div class="flex justify-around pb-4 rounded-lg mb-4 border-b-8 border-amber-600">
                <?php
                global $arrConfig;
                 $podium = getPodium(2, "AccRank",null, $GLOBALS['type']); ?>
                <div class="flex flex-col items-center mt-auto">
                    <?php if ($podium): ?>
                        <img src="<?php echo $arrConfig['url_users'] . $podium['image']; ?>" class="rounded-full w-20 h-20 mb-2">
                        <h1 class="mb-2 text-white">@<?php echo $podium['username']; ?></h1>
                        <div class="bg-gray-800 text-white rounded-lg text-center p-4 h-24 w-28 relative flex items-center justify-center m-4 sm:m-0">Second Place</div>
                    <?php else: ?>
                        <h1 class="mb-2 text-white">No user found with this rank.</h1>
                        <div class="bg-gray-800 text-white rounded-lg text-center p-4 h-24 w-28 relative flex items-center justify-center m-4 sm:m-0">Second Place</div>
                    <?php endif; ?>
                </div>
                <?php $podium = getPodium(1, "AccRank",null, $GLOBALS['type']); ?>
                <div class="flex flex-col items-center mt-auto">
                    <?php if ($podium): ?>
                        <img src="<?php echo $arrConfig['url_users'] . $podium['image']; ?>" class="rounded-full w-20 h-20 mb-2">
                        <h1 class="mb-2 text-white">@<?php echo $podium['username']; ?></h1>
                        <div class="bg-gray-800 text-white rounded-lg text-center p-4 h-28 w-28 relative flex items-center justify-center m-4 sm:m-0">First Place</div>
                    <?php else: ?>
                        <h1 class="mb-2 text-white">No user found with this rank.</h1>
                        <div class="bg-gray-800 text-white rounded-lg text-center p-4 h-28 w-28 relative flex items-center justify-center m-4 sm:m-0">First Place</div>
                    <?php endif; ?>
                </div>
                <?php $podium = getPodium(3, "AccRank", null, $GLOBALS['type']); ?>
                <div class="flex flex-col items-center mt-auto">
                    <?php if ($podium): ?>
                        <img src="<?php echo $arrConfig['url_users'] . $podium['image']; ?>" class="rounded-full w-20 h-20 mb-2">
                        <h1 class="mb-2 text-white">@<?php echo $podium['username']; ?></h1>
                        <div class="bg-gray-800 text-white rounded-lg text-center p-4 h-20 w-28 relative flex items-center justify-center m-4 sm:m-0">Third Place</div>
                    <?php else: ?>
                        <h1 class="mb-2 text-white">No user found with this rank.</h1>
                        <div class="bg-gray-800 text-white rounded-lg text-center p-4 h-20 w-28 relative flex items-center justify-center m-4 sm:m-0">Third Place</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="flex flex-row gap-4">
          <div>
              <label for="typeSelect" class="mb-2 text-white">Tipo:</label>
              <select class="select select-bordered w-full max-w-xs mb-8 text-white" id="typeSelect">
                  <option disabled class="text-white">Type - - -</option>
                  <?php setSelectedType($GLOBALS['type'] ?? ''); ?>
              </select>
          </div>
      </div>
        <div class="overflow-y-auto h-96 flex flex-col items-center bg-gray-800">
            <div class="flex w-full text-center justify-center bg-gray-800 p-4 text-lg text-white border-b-2 border-gray-900 items-center">
                <div class="ubuntu-bold w-2/6"> <button id="invertButton" class="bg-orange-500 hover:bg-orange-700 text-white font-bold py-1 px-2 rounded mr-1"> <i class="fi-sr-apps-sort fi"></i></button>Rank</div>
                <div class="ubuntu-bold w-2/6">Likes</div> <!-- Likes -->
                <div class="ubuntu-bold w-2/6">Account</div> <!-- Person who posted it -->
            </div>
            <div class="h-full overflow-y-auto w-full flex flex-col m-auto" id=tableRanking>
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
    <script>
    document.getElementById('invertButton').addEventListener('click', function() {
        document.getElementById('tableRanking').classList.toggle('flex-col-reverse');
    });
    </script>
  <script src="../src/js/timer.js"></script>
  <script src="../src/js/filterTheme.js"></script>
  <script src="../src/js/social.js"></script>
</body>
</html>