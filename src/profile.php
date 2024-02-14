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
    <title>Profile</title>
</head>
<body class="h-full flex">
    <?php echoLoadScreen(); ?>
    <?php echoNav(); ?>
    <div id="profile-div" class="fixed flex flex-col h-full w-full md:w-9/12 p-0 m-0 bg-gray-900 md:right-0">
       
      <div id="profileInfo-div" class="b-8 z-20 relative w-full flex flex-row justify-between shadow-md shadow-amber-600 bg-gray-800 h-60 md:h-80 pl-4 pr-4 sm:text-right pb-4">
      <?php
            getUserInfo($_SESSION['uid']); 
        ?>  

      <div id="profilePosts-div" class="relative p-auto overflow-auto">
        <?php
          getPosts($_SESSION['uid']);
        ?>
      </div>
  </div>

    
  <!-- Dialog -->
  <div id="dialog" class="hidden fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-75  text-gray-800 font-medium">
    <form class="bg-white p-8 rounded shadow-lg" action="include/functions/validateUpdateUser.inc.php" method="post" enctype="multipart/form-data" >
      <h2 class="text-2xl font-extrabold mb-4 text-gray-800">Edit Profile</h2>
      <div class="flex flex-col mb-4">
        <label class="text-lg mb-2 text-gray-800">Username:</label>
        <input type="text" name="username" class="border border-gray-300 p-2 rounded text-gray-800" required minlength="4" maxlength="15" />
        <span class="invalid-feedback text-red-800">Username must be between 4 and 15 characters long.</span>
      </div>

      <div class="flex flex-col mb-4">
        <label class="text-lg mb-2 text-gray-800">Real Name:</label>
        <input type="text" name="realName" class="border border-gray-300 p-2 rounded text-gray-800" required maxlength="30" />
        <span class="invalid-feedback text-red-800">Real name cannot exceed 30 characters.</span>
      </div>

      <div class="flex flex-col mb-4">
        <label class="text-lg mb-2 text-gray-800">Biography:</label>
        <textarea name="biography" class="border border-gray-300 p-2 rounded text-gray-800" rows="6" required maxlength="255"></textarea>
        <span class="invalid-feedback text-red-800">Biography cannot exceed 255 characters.</span>
      </div>
      <div class="flex flex-col mb-4">
          <label class="text-lg mb-2 text-gray-800">Profile Pic:</label>
          <input type="file" name="profilePic" class="border border-gray-300 p-2 rounded text-gray-800" accept="image/jpeg,image/jpg,image/png,image/gif"/>
      </div>
      <div class="flex justify-center">
      <button type="button" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="closeDialog()">Close</button>
        <button type="submit" name="confirm" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded ml-4">Update</button>
      </div>
    </form>
  </div>
  <?php echoBottomNav(); ?>
  <script src="../src/js/social.js"></script>
  <script src="../src/js/EditProfile.js"></script>
  <script src="../src/js/openPosts.js"></script>
</body>
</html>