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
        <?php
            //getUserInfo($_SESSION['uid']); 
        ?>  

      <div id="profileInfo-div" class="b-8 z-20 relative w-full flex flex-row justify-between shadow-md shadow-amber-600 bg-gray-800 h-60 md:h-80 pl-4 pr-4 sm:text-right pb-4">
      <div class="flex h-32 lg:h-64 mt-8 w-4/6 md:mr-8">
        <div class="h-full w-full mt-0 lg:mt-4 mb-4 mr-8">
          <div class="flex sm:hidden text-3xl sm:text-4xl font-bold text-amber-500">Rank: {rank}</div>
          <span class="block font-bold text-3xl mt-4 text-amber-700 mb-4">@{username}</span>
          <div class="font-bold">{realName}</div>
          <div class="w-full h-full">{biography}</div>
        </div>
        </div>
        <div class="relative mt-8 mb-8">
          <div class="absolute top-0 border-l-8 border-orange-500 border-solid rounded-lg h-full lg:ml-auto"></div>
          <img src="{profilePic}" alt="Profile Picture" class="rounded-full w-32 h-32 md:w-56 md:h-56 mt-4 ml-8 mr-10 lg:ml-3/5 sm:mr-8 lg:mr-3/5 hover:filter hover:brightness-50 hover:opacity-75 border-2 border-gray-600">
          <button class="float-right bg-orange-500 hover:bg-orange-700 text-white font-bold py-4 px-4 rounded-lg flex items-center justify-center h-10 md:h-16 w-32 md:w-16" onclick="openDialog()">Edit Profile</button>
        </div>
        <div class="hidden sm:flex mt-2 text-4xl font-bold text-amber-600">Rank: {rank}</div>
        </div>
      <div id="profilePosts-div" class="relative p-auto overflow-auto">
      </div>
  </div>

  <?php
        //getPosts($_SESSION['uid']);
      ?>
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
</body>
</html>