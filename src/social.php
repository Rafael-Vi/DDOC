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
          <a href="#" id="search-link" style="color: black">
            Search
          </a>
        </li>
        <li class="px-4 py-2">
          <a href="#" id="messages-link" style="color: black">Messages</a>
        </li>
        <li class="px-4 py-2">
          <a href="#" id="notifications-link" style="color: black">Notifications</a>
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
      <div class="flex justify-start md:justify-center items-end">
        <button class="bg-orange-500 hover:bg-orange-700 text-white font-bold py-4 px-4 rounded-lg flex items-center justify-center h-10 md:h-16 w-32 md:w-16" onclick="openDialog()">
          Edit Profile
        </button>
      </div>
    </div>
    <div id="profilePosts-div" class="relative p-auto overflow-y-auto">
      <?php
        getPosts($_SESSION['uid']);
      ?>
    </div>
  </div>

  
  <!-- Dialog -->
  <div id="dialog" class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-75 hidden text-gray-800 font-medium">
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

  <div id="search-div" class="hidden bg-gray-900 fixed flex flex-col h-full w-full md:w-4/5 p-0 m-0 md:right-0">
    <div class="h-32 border text-center sm:text-start border-black w-full p-10 font-bold text-4xl shadow-md shadow-amber-600 bg-gray-800" >
      <form class="flex items-center justify-center h-full" method="post" action="">
        <div class="rounded-lg bg-gray-200">
          <div class="flex text-gray-900">
            <button class="flex w-16 items-center justify-center rounded-tl-lg rounded-bl-lg border-r border-gray-900 bg-gray-600 p-5" id="search-confirm" type="submit">
              <svg viewBox="0 0 20 20" aria-hidden="true" class="pointer-events-none absolute w-8 fill-gray-900 transition">
                <path d="M16.72 17.78a.75.75 0 1 0 1.06-1.06l-1.06 1.06ZM9 14.5A5.5 5.5 0 0 1 3.5 9H2a7 7 0 0 0 7 7v-1.5ZM3.5 9A5.5 5.5 0 0 1 9 3.5V2a7 7 0 0 0-7 7h1.5ZM9 3.5A5.5 5.5 0 0 1 14.5 9H16a7 7 0 0 0-7-7v1.5Zm3.89 10.45 3.83 3.83 1.06-1.06-3.83-3.83-1.06 1.06ZM14.5 9a5.48 5.48 0 0 1-1.61 3.89l1.06 1.06A6.98 6.98 0 0 0 16 9h-1.5Zm-1.61 3.89A5.48 5.48 0 0 1 9 14.5V16a6.98 6.98 0 0 0 4.95-2.05l-1.06-1.06Z"></path>
              </svg>
            </button>
            <input type="text" class="w-full p-6 text-2xl font-semibold outline-0 border border-gray-700 rounded-br-md rounded-tr-md" placeholder="Search" id="search-input" pattern=".*\S+.*" title="Please fill out this field" required>
          </div>
        </div>
      </form>
    </div>
    <div class="h-full border border-black w-full p-10" id="search-results">

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

    <form action="include/functions/validadeCreatePost.inc.php" class="flex flex-col items-center sm:items-start h-full ml-10" method="Post" enctype="multipart/form-data">
        <label for="post-title" class="text-white font-bold text-2xl pt-8">Title:</label>
        <input type="text" id="post-title" name="post-title" required class="rounded-lg px-4 py-2 bg-gray-800 text-white mb-4"><br>
        
        <label for="post-type" class="text-white font-bold">Type:</label>
        <select id="post-type" name="post-type" required class="rounded-lg px-4 py-2 bg-gray-800 text-white mb-4">
            <option value="audio">Audio</option>
            <option value="image">Image</option>
            <option value="video">Video</option>
        </select><br>

        <?php
          echoThumb('hm');
        ?>

      <label for="file-upload" class="text-white font-bold mt-8">Upload File (Music/Image):</label>
      <input type="file" id="file-upload" name="file-upload" class="rounded-lg bg-gray-800 text-white mb-4"><br>
        
      <button type="submit" name="CreatePost" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded mt-8">Create Post</button>
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