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
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Rankings Accounts</title>
</head>
<body class="h-full flex">
  <?php echoLoadScreen(); ?>    <?php
        echoShowTheme();
    ?>
    <?php echoNav(); ?>
    <div id="Accrankings-div" class=" bg-gray-900 fixed flex flex-col h-full w-full md:w-9/12 p-0 m-0 md:right-0 overflow-auto">
    <h1 class="h-32 border text-center sm:text-start border-black w-full p-10 font-bold text-4xl shadow-md shadow-amber-600 bg-gray-800 sticky top-0">
      Account Rankings
    </h1>

    <div class="h-full w-full p-10">
      <div class="flex flex-col h-full w-full p-10">
        <div class="flex flex-col w-full p-10 sticky">
          <div class="flex justify-around pb-4 rounded-lg mb-4 border-b-8 border-amber-600">
            <div class="flex flex-col items-center mt-auto">
              <h1>----------</h1>
              <div class="bg-gray-800 rounded-lg text-center p-4 h-20 w-24 relative flex items-center justify-center m-4 sm:m-0">Second Place</div>
            </div>
            <div class="flex flex-col items-center mt-auto">
              <h1>----------</h1>
              <div class="bg-gray-800 rounded-lg text-center p-4 h-28 w-28 relative flex items-center justify-center m-4 sm:m-0">First Place</div>
            </div>
            <div class="flex flex-col items-center mt-auto">
              <h1>---------</h1>
              <div class="bg-gray-800 rounded-lg p-4 text-center h-16 w-24 relative flex items-center justify-center m-4 sm:m-0 ">Third Place</div>
            </div>
          </div>
        </div>
        <div class="overflow-y-auto h-screen flex flex-col items-center bg-gray-800 h-56">
          <div class="flex w-full text-center justify-center bg-gray-800 p-4 text-lg text-white border-b-2 border-gray-900 items-center">
            <div class="ubuntu-bold w-1/3">Rank</div>
            <div class="ubuntu-bold w-1/3">Likes</div> <!-- Likes -->
            <div class="ubuntu-bold w-1/3">Person who posted it</div> <!-- Person who posted it -->
          </div>
          <!-- Repeat this div for each post -->
          <div class="flex w-full text-center justify-center bg-gray-800 p-2 border-r-2 border-gray-900 shadow-lg mb-2 hover:bg-gray-700 transition-colors duration-200">
            <div class="text-white w-1/3">Rank</div> <!-- Rank of the post -->
            <div class="text-white w-1/3">Likes</div> <!-- Likes -->
            <div class="text-white w-1/3">Person who posted it</div> <!-- Person who posted it -->
          </div>
          <!-- End of post div -->
        </div>
      </div>
    </div>
  </div><script>
  var targetDateFromPHP = <?php echo json_encode($_SESSION['themes']['finish_date']); ?>;
</script>

  <script src="../src/js/timer.js"></script>

  <script src="../src/js/social.js"></script>
</body>
</html>