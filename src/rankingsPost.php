<?php
include "include/config.inc.php";

  include "include/functions/checkLogin.inc.php";

      require "include/functions/checkThemeIsFinished.inc.php";
      require "include/functions/Development.inc.php";

     if (checkThemeIsFinished()){
    include "include/functions/saveLastPage.inc.php";
  }

require "include/functions/checkFilterVars.inc.php";

// Start the session if it's not already started
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}

// Call the checkThemeVar function
$themeId = checkThemeVar();

// If the return value is -1, redirect and exit
if ($themeId == -1) {
  header("Location: ../src/errorPages/noThemeError.php");
  exit;
}

// If the return value is null or 0, set theme_id to the session id
if ($themeId == null || $themeId == 0) {
  $themeId = $_SESSION['themes'][0]['theme_id'];
}

// Set the global theme_id variable
$GLOBALS['theme_id'] = $themeId;

// Call the checkTypeVar function
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
    <title>Rankings Posts</title>
</head>
<body class="h-full flex">
    <?php echoLoadScreen(); ?>    <?php
        //echoShowTheme();
    ?>
    <?php echoNav(); ?>
    <div id="Postrankings-div" class=" bg-gray-900 fixed flex flex-col h-full w-full md:w-9/12 p-0 m-0 md:right-0 overflow-auto">
    <div class="h-32 text-center sm:text-start w-full p-10 font-bold text-4xl text-white sticky top-0 flex items-center justify-left gap-8 backdrop-blur-md">
<a href="javascript:history.back()" class="btn">Voltar atrás</a>
      Post Rankings
    </div>

    <div class="h-full w-full">
      
    <div class="h-full w-full px-10">
      <div class="flex flex-col h-full w-full p-10">
        <div class="flex flex-col w-full mb-8 sticky">
          <div class="flex justify-around pb-4 rounded-lg border-b-8 border-amber-600">
          <?php
            global $arrConfig;
            $podium = getPodium(2, "PostRank", $GLOBALS['theme_id'], $GLOBALS['type']);?>
            <div class="flex flex-col items-center mt-auto">
                <?php if ($podium): ?>
                    <h1 class="mb-2 text-white"><?php echo $podium['NameOfThePost']; ?></h1>
                    <div class="bg-gray-800 rounded-lg text-center p-4 h-20 w-24 relative flex items-center justify-center m-4 sm:m-0">Second Place</div>
                <?php else: ?>
                    <h1 class="mb-2 text-white">No post found with this rank.</h1>
                    <div class="bg-gray-800 rounded-lg text-center p-4 h-20 w-24 relative flex items-center justify-center m-4 sm:m-0">Second Place</div>
                <?php endif; ?>
            </div>
            <?php $podium = getPodium(1, "PostRank",  $GLOBALS['theme_id'], $GLOBALS['type']); ?>
            <div class="flex flex-col items-center mt-auto">
                <?php if ($podium): ?>
                    <h1 class="mb-2 text-white"><?php echo $podium['NameOfThePost']; ?></h1>
                    <div class="bg-gray-800 rounded-lg text-center p-4 h-28 w-28 relative flex items-center justify-center m-4 sm:m-0">First Place</div>
                <?php else: ?>
                    <h1 class="mb-2 text-white">No post found with this rank.</h1>
                    <div class="bg-gray-800 rounded-lg text-center p-4 h-28 w-28 relative flex items-center justify-center m-4 sm:m-0">First Place</div>
                <?php endif; ?>
            </div>
            <?php $podium = getPodium(3, "PostRank",  $GLOBALS['theme_id'], $GLOBALS['type']); ?>
            <div class="flex flex-col items-center mt-auto">
                <?php if ($podium): ?>
                    <h1 class="mb-2 text-white"><?php echo $podium['NameOfThePost']; ?></h1>
                    <div class="bg-gray-800 rounded-lg text-center p-4 h-16 w-24 relative flex items-center justify-center m-4 sm:m-0">Third Place</div>
                <?php else: ?>
                    <h1 class="mb-2 text-white">No post found with this rank.</h1>
                    <div class="bg-gray-800 rounded-lg text-center p-4 h-16 w-24 relative flex items-center justify-center m-4 sm:m-0">Third Place</div>
                <?php endif; ?>
            </div>
          </div>

        </div>
        <div class="flex flex-row gap-4">
          <div>
              <label for="themeSelect" class="mb-2 text-white">Temas:</label>
              <select class="select select-bordered w-full max-w-xs mb-8 text-black" id="themeSelect">
                  <option disabled>Temas - - - </option>
                  <?php
                    getThemes(true);
                  ?>
              </select>
          </div>
          <!-- Falta acabar este filtro e corrigir o display ds conteúdos-->
          <div>
              <label for="typeSelect" class="mb-2 text-white">Tipo:</label>
              <select class="select select-bordered w-full max-w-xs mb-8 text-black" id="typeSelect">
                  <option disabled>Type - - -</option>
                  <?php setSelectedType($GLOBALS['type'] ?? ''); ?>
              </select>
          </div>
      </div>

        <div class="overflow-y-auto h-96 flex flex-col items-center bg-gray-800 ">
          <div class="flex w-full text-center justify-center bg-gray-800 p-4 text-lg text-white border-b-2 border-gray-900 items-center">
            <div class="ubuntu-bold w-1/6">Rank</div>
            <div class="ubuntu-bold w-1/6">Conteúdo do Post</div> <!-- Image of the post -->
            <div class="ubuntu-bold w-1/6">Nome do Post</div> <!-- Name of the post -->
            <div class="ubuntu-bold w-1/6">Tipe</div> <!-- Type -->
            <div class="ubuntu-bold w-1/6">Likes</div> <!-- Likes -->
            <div class="ubuntu-bold w-1/6">Dono do Post</div> <!-- Person who posted it -->
          </div>
          <div class="h-full overflow-y-auto w-full">

          <?php 
            getRankingPost($GLOBALS['theme_id'], $GLOBALS['type']);
          ?>
          </div>
          <!-- End of post div -->
      </div>

<script>
  var targetDateFromPHP = <?php echo json_encode($_SESSION['themes'][0]['finish_date']); ?>;
</script>
  <script src="../src/js/timer.js"></script>
  <script src="../src/js/filterTheme.js"></script>
  <script   src="../src/js/social.js"></script>
</body>
</html>