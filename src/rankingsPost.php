<?php
include "include/config.inc.php";
?>

<?php
  include "include/functions/checkLogin.inc.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../src/css/social.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Rankings Posts</title>
</head>
<body class="h-full flex">
    <?php echoNav(); ?>
    <div id="Postrankings-div" class=" bg-gray-900 fixed flex flex-col h-full w-full md:w-9/12 p-0 m-0 md:right-0 overflow-auto">
    <h1 class="h-32 border text-center sm:text-start border-black w-full p-10 font-bold text-4xl shadow-md shadow-amber-600 bg-gray-800 sticky top-0">
      Post Rankings
    </h1>

    <div class="h-full w-full">
      
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
              <div class="bg-gray-800 rounded-lg p-4 text-center h-16 w-24 relative flex items-center justify-center m-4 sm:m-0">Third Place</div>
            </div>
          </div>
        </div>

        <!-- Second row divided into two columns -->
        <div class="flex flex-row flex-grow">
          <div class="flex-2 bg-gray-800 p-4 rounded-l-lg border-r-2 border-orange-500">
            Their Place
          </div>

          <div class="flex-1 bg-gray-800 p-4 rounded-r-lg">
            thumbnail, title, and points and type
          </div>
        </div>
      </div>
    </div>
    </div>
  </div>
  <script src="../src/js/social.js"></script>
</body>
</html>