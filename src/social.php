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

  <div class="absolute md:flex md:flex-col md:top-0 md:left-0 md:w-1/5 md:bg-white md:h-full md:border-r hidden md:rounded-lg md:shadow-xl z-40">
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
          <a href="#" id="Accrankings-link" style="color: black">Account Rankings</a>
        </li>
        <li class="px-4 py-2">
          <a href="#" id="Postrankings-link" style="color: black">Post Rankings</a>
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
    <h1 class="h-32 border text-center sm:text-start border-black w-full p-10 font-bold text-4xl shadow-md shadow-amber-600 bg-gray-800">
      Home
    </h1>

    <div class="h-full border border-black w-full p-10">
      
    </div>
  </div>

  <div id="profile-div" class="hidden fixed flex flex-col h-full w-full md:w-4/5 p-0 m-0 bg-gray-900 md:right-0">
    <div id="profileInfo-div" class="b-8 z-20 relative w-full flex flex-row shadow-md shadow-amber-600 bg-gray-800 h-60 md:h-80 pl-4 pr-4 sm:text-right pb-4" >
      <?php
        getUserInfo($_SESSION['uid']); 
      ?>
      
      <!-- Button to open the dialog -->
      <div class="flex justify-center items-center">
        <button class="bg-orange-500 hover:bg-orange-700 text-white font-bold py-2 px-4 rounded-lg hidden md:flex items-center justify-center h-16 w-16" onclick="openDialog()">
          Edit Profile
        </button>
      </div>
    </div>
    <div id="profilePosts-div" class="relative p-auto h-full">
      <?php
        getPosts($_SESSION['uid']);
      ?>
    </div>
  </div>

  
  <!-- Dialog -->
  <div id="dialog" class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-75 hidden text-gray-800 font-medium">
    <form class="bg-white p-8 rounded shadow-lg" action="include/functions/validateUpdateUser.inc.php" method="post" >
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
          <input type="file" name="profilePic" accept="image/*" class="border border-gray-300 p-2 rounded text-gray-800" />
      </div>
      <div class="flex justify-center">
      <button type="button" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="closeDialog()">Close</button>
        <button type="submit" name="confirm" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded ml-4">Update</button>
      </div>
    </form>
  </div>

  <div id="messages-div" class="hidden bg-gray-900 fixed flex flex-col h-full w-full md:w-4/5 p-0 m-0 md:right-0">
    <h1 class="h-32 border text-center sm:text-start border-black w-full p-10 font-bold text-4xl shadow-md shadow-amber-600 bg-gray-800">
      Messages
    </h1>

    <div class="h-full border border-black w-full p-10">
      
    </div>
  </div>

  <div id="notifications-div" class="hidden bg-gray-900 fixed flex flex-col h-full w-full md:w-4/5 p-0 m-0 md:right-0">
    
    <h1 class="h-32 border text-center sm:text-start border-black w-full p-10 font-bold text-4xl shadow-md  shadow-amber-600 bg-gray-800">
      Notifications
    </h1>

    <div class="h-full border border-black w-full p-10">
      
    </div>
  </div>

  <div id="Accrankings-div" class="hidden bg-gray-900 fixed flex flex-col h-full w-full md:w-4/5 p-0 m-0 md:right-0">
    <h1 class="h-32 border text-center sm:text-start border-black w-full p-10 font-bold text-4xl shadow-md shadow-amber-600 bg-gray-800">
      Account Rankings
    </h1>

    <div class="h-full border border-black w-full p-10">
      
    </div>
  </div>
  <div id="Postrankings-div" class="hidden bg-gray-900 fixed flex flex-col h-full w-full md:w-4/5 p-0 m-0 md:right-0">
    <h1 class="h-32 border text-center sm:text-start border-black w-full p-10 font-bold text-4xl shadow-md shadow-amber-600 bg-gray-800">
      Post Rankings
    </h1>

    <div class="h-full border border-black w-full p-10">
      
    </div>
  </div>

  <div id="createPost-div" class="hidden bg-gray-900 fixed flex flex-col h-full w-full md:w-4/5 p-0 m-0 md:right-0">
    <h1 class="h-32 border text-center sm:text-start border-black w-full p-10 font-bold text-4xl shadow-md shadow-amber-600 bg-gray-800">
      Create Post
    </h1>

    <form action="createPost()" class="flex flex-col items-center sm:items-start h-full ml-10">
        <label for="post-title" class="text-white font-bold text-2xl pt-8">Title:</label>
        <input type="text" id="post-title" name="post-title" required class="rounded-lg px-4 py-2 bg-gray-800 text-white mb-4"><br>
        
        <label for="post-type" class="text-white font-bold">Type:</label>
        <select id="post-type" name="post-type" required class="rounded-lg px-4 py-2 bg-gray-800 text-white mb-4">
            <option value="audio">Audio</option>
            <option value="image">Image</option>
        </select><br>

        <?php
          echoThumb('hm');
        ?>

      <label for="file-upload" class="text-white font-bold mt-8">Upload File (Music/Image):</label>
      <input type="file" id="file-upload" name="file-upload" class="rounded-lg bg-gray-800 text-white mb-4"><br>
        
      <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded mt-8">Create Post</button>
    </form>
  </div>

  <div id="settings-div" class="hidden bg-gray-900 fixed flex flex-col h-full w-full md:w-4/5 p-0 m-0 md:right-0">
    <h1 class="h-32 border text-center sm:text-start border-black w-full p-10 font-bold text-4xl shadow-md shadow-amber-600 bg-gray-800">
      Settings
    </h1>

    <div class="h-full border border-black w-full p-10">
      
    </div>
  </div>


<nav class="bg-white shadow-md flex items-center md:hidden justify-between absolute bottom-0 w-full">
  <ul class="flex w-full h-12 flex-row justify-between items-center">
    <li><a href="#" id="home-link" class="text-md font-medium text-gray-800 hover:text-gray-700 bg-white rounded-full p-2 mr-1">Home</a></li>
    <li><a href="#" id="rankings-link" class="text-md font-medium text-gray-800 hover:text-gray-700 bg-white rounded-full p-2">Rankings</a></li>
    <li><a href="#"  id="profile-link" class="w-1/5 text-md font-medium active text-gray-800 hover:text-gray-700 bg-yellow-500 rounded-full p-2 m-auto">Profile</a></li>
    <li><a href="#" id="createPost-link" class="text-md font-medium text-gray-800 hover:text-gray-700 rounded-full p-2 mr-1">+ Post</a></li>
    <li><a href="#" id ="settings-link" class="text-md font-medium text-gray-800 hover:text-gray-700 rounded-full p-2 mr-3">Settings</a></li>
  </ul>
</nav>



<script src="../src/js/social.js"></script>
</body>
</html>