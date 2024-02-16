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
    <title>{Other Person's Name}</title>
</head>
<body class="h-full flex">
    <?php echoNav(); ?>
    <div id="profile-div" class="fixed flex flex-col h-full w-full md:w-9/12 p-0 m-0 bg-gray-900 md:right-0">
       
       <div id="profileInfo-div" class="b-8 z-20 relative w-full flex flex-row justify-between shadow-md shadow-amber-600 bg-gray-800 h-60 md:h-80 pl-4 pr-4 sm:text-right pb-4">
         <?php
              echo '<div class="flex h-32 lg:h-64 mt-8 w-4/6">';
              echo '<div class="h-full w-full mt-0 md:mt-8 mb-4">';
             getUserInfo($_GET['userid']); 
             echo'  
             <div class="flex justify-center">
             <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded m-4">
                 Message
             </button>
           
             <button class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded m-4">
                 Follow
             </button>
         </div>';
             echo '</div>';
             echo '</div>';
         ?>  
 
       <div id="profilePosts-div" class="relative p-auto overflow-auto">
         <?php
            getPosts($_GET['userid']);
         ?>
       </div>
   </div>
  <?php echoBottomNav(); ?>
  <script src="../src/js/social.js"></script>
</body>
</html>