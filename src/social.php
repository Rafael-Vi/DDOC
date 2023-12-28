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
    <title>DDOC</title>
    
</head>
<body class="h-full flex">

  <div class="absolute md:flex md:flex-col md:top-0 md:left-0 md:w-1/5 md:bg-white md:h-full md:border-r hidden md:rounded-lg md:shadow-xl">
    <div class="overflow-y-auto overflow-x-hidden flex-grow">
      <ul class="flex flex-col py-4 space-y-1">
        <li class="px-5">
          <div class="flex flex-row items-center h-32">
            <img src="../src/assets/images/1.png" alt="" srcset="" class="h-full"></div>
          </li>
        <li class="px-4 py-2">
          <a href="#" id="messages-link" style="color: black">Messages</a>
        </li>
        <li class="px-4 py-2">
          <a href="#" id="notifications-link" style="color: black">Notifications</a>
        </li>
        <li class="px-4 py-2">
          <a href="#" id="search-link" style="color: black">Search</a>
        </li>
        <li class="px-5 py-2">
          <div class="flex flex-row items-center h-8">
            <div class="text-sm font-light tracking-wide text-gray-500 border-b-2 border-orange-500 w-full rounded-full"></div>
          </div>
        </li>
        <li class="px-4 py-2">
          <a href="#" id="home-link" style="color: black">Home</a>
        </li>
        <li class="px-4 py-2">
          <a href="#" id="rankings-link" style="color: black">Rankings</a>
        </li>
        <li class="px-4 py-2">
          <a href="#" id="createPost-link" style="color: black">Create Post</a>
        </li>
        <li class="px-5 py-2">
          <div class="flex flex-row items-center h-8">
            <div class="text-sm font-light tracking-wide text-gray-500 border-b-2 border-orange-500 w-full rounded-lg"></div>
          </div>
        </li>
        <li class="px-4 py-2">
          <a href="#" id="profile-link" style="color: black">Profile</a>
        </li>
        <li class="px-4 py-2">
          <a href="#" id="settings-link" style="color: black">Settings</a>
        </li>
        <li class="px-4 py-2">
          <a href="#" id="logout-link" style="color: black">Logout</a>
        </li>
      </ul>
    </div>
  </div>

  <div class="bg-gray-900 fixed w-full md:w-4/5 p-0 m-0 md:right-0 h-full flex flex-col justify-center items-center" id="home-div">
    <h1 class="h-32 border border-black w-full p-10 font-bold text-4xl shadow-md shadow-amber-600 bg-gray-800">
      Home
    </h1>

    <div class="h-full border border-black w-full p-10">
      
    </div>
  </div>

  <div id="profile-div" class="hidden fixed flex flex-col h-full w-full md:w-4/5 p-0 m-0 bg-gray-900 md:right-0">
    <div id="profileInfo-div" class="b-8 relative w-full flex flex-row shadow-md shadow-amber-600 bg-gray-800 h-60 md:h-80 pl-4 pr-4 sm:text-right pb-4" >
      <?php
        getUserInfo($_SESSION['uid']); 
      ?>
    </div>
    <div id="profilePosts-div" class="relative p-auto h-full">
      <?php
        getPosts($_SESSION['uid']);
      ?>
    </div>
  </div>

  <div id="messages-div" class="hidden bg-gray-900 fixed flex flex-col h-full w-full md:w-4/5 p-0 m-0 md:right-0">
    <h1 class="h-32 border border-black w-full p-10 font-bold text-4xl shadow-md shadow-amber-600 bg-gray-800">
      Messages
    </h1>

    <div class="h-full border border-black w-full p-10">
      
    </div>
  </div>

  <div id="notifications-div" class="hidden bg-gray-900 fixed flex flex-col h-full w-full md:w-4/5 p-0 m-0 md:right-0">
    
    <h1 class="h-32 border border-black w-full p-10 font-bold text-4xl shadow-md shadow-amber-600 bg-gray-800">
      Messages
    </h1>

    <div class="h-full border border-black w-full p-10">
      
    </div>
  </div>

  <div id="rankings-div" class="hidden bg-gray-900 fixed flex flex-col h-full w-full md:w-4/5 p-0 m-0 md:right-0">
    <h1 class="h-32 border border-black w-full p-10 font-bold text-4xl shadow-md shadow-amber-600 bg-gray-800">
      Rankings
    </h1>

    <div class="h-full border border-black w-full p-10">
      
    </div>
  </div>

  <div id="createPost-div" class="hidden bg-gray-900 fixed flex flex-col h-full w-full md:w-4/5 p-0 m-0 md:right-0">

  </div>

  <div id="settings-div" class="hidden bg-gray-900 fixed flex flex-col h-full w-full md:w-4/5 p-0 m-0 md:right-0">
    <h1 class="h-32 border border-black w-full p-10 font-bold text-4xl shadow-md shadow-amber-600 bg-gray-800">
      Settings
    </h1>

    <div class="h-full border border-black w-full p-10">
      
    </div>
  </div>


<nav class="bg-white shadow-md flex items-center justify-between md:hidden absolute bottom-0 w-full">
  <ul class="flex w-full h-12 flex-row justify-between items-center">
    <li><a href="#" id="home-link" class="text-md font-medium text-gray-800 hover:text-gray-700 bg-white rounded-full p-2 mr-3">Home</a></li>
    <li><a href="#" id="rankings-link" class="text-md font-medium text-gray-800 hover:text-gray-700 bg-white rounded-full p-2 mr-3">Rankings</a></li>
    <li><a href="#"  id="profile-link" class="w-1/5 text-md font-medium active text-gray-800 hover:text-gray-700 bg-yellow-500 rounded-full p-2">Profile</a></li>
    <li><a href="#" class="text-md font-medium text-gray-800 hover:text-gray-700 rounded-full p-2 mr-3">Hist</a></li>
    <li><a href="#" class="text-md font-medium text-gray-800 hover:text-gray-700 rounded-full p-2 mr-3">adas</a></li>
  </ul>
</nav>



  <script src="../src/js/social.js"></script>
</body>
</html>