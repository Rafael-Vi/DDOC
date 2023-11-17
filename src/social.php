<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../src/css/social.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>DDOC</title>
</head>
<body class="bg-slate-900">
  <div class="flex flex-row min-h-screen z-8">
    <div class="w-2/5 lg:w-1/5 bg-slate-950 rounded-r-lg z-4 border-solid border-2 border-neutral-50 " id="leftButtons-div">
      <ul class="bg-slate-950">
        <li><a href="#" class="text-3xl block text-left py-2 px-4 text-white mb-8 ml-3 md:ml-8" style="margin-top: 60px;" id="brand-name">DDOC</a></li>
        <li><a href="#" class="text-xl block text-left py-2 px-4 text-white hover:font-bold ml-3 md:ml-8 hover:text-orange-500" id="profile-link">Profile</a></li>
        <li><a href="#" class="text-xl block text-left py-2 px-4 text-white hover:font-bold ml-3 md:ml-8 hover:text-orange-500" id="notifications-link">Notifications</a></li>
        <li><a href="#" class="text-xl block text-left py-2 px-4 text-white hover:font-bold ml-3 md:ml-8 hover:text-orange-500" id="rankings-link">Rankings</a></li>
        <li><a href="#" class="text-xl block text-left py-2 px-4 text-white hover:font-bold ml-3 md:ml-8 hover:text-orange-500" id="messages-link">Messages</a></li>
        <li><a href="#" class="text-xl block text-left py-2 px-4 text-white hover:font-bold ml-3 md:ml-8 hover:text-orange-500" id="createPost-link" style="margin-bottom: 400px;">Create Post</a></li>        
        <li>
          <a href="#" class="text-xl block text-left py-2 px-4 text-white hover:font-bold ml-3 md:text-left md:ml-8 hover:text-orange-500" id="more-link">More &#x25B2;</a>
        </li>
      </ul>

      <!-- Dropdown menu -->
      <div id="dropdown-div" class="bg-white divide-y divide-gray-100 rounded-lg shadow w-44 dark:bg-gray-700 absolute bottom-full ml-3">
        <ul class="py-2 text-sm text-gray-700 dark:text-gray-200">
          <li>
            <a href="#" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Dashboard</a>
          </li>
          <li>
            <a href="#" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Settings</a>
          </li>
          <li>
            <a href="#" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Earnings</a>
          </li>
          <li>
            <a href="#" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Sign out</a>
          </li>
        </ul>
      </div>

    </div>
  
    <div class="w-3/5 bg-slate-900 0 max-h-screen lg:w-3/5 overflow-y-auto z-auto" id="mainContent-div">

    </div>
  
    <div class="w-1/5 lg:w-1/5 bg-slate-950 hidden lg:block rounded-l-lg z-4 border-solid border-2 border-neutral-50 " id="rightButtons-div">
      <form class="flex items-center ml-8 mt-4 mr-8">   
        <label for="simple-search" class="sr-only">Search</label>
        <div class="relative ">
            <input type="text" id="simple-search" class="bg-gray-500 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-orange-500 focus:border-orange-500 block w-full ps-10 p-2.5  dark:bg-gray-500 dark:border-gray-700 dark:placeholder-neutral-50 dark:text-white dark:focus:ring-gray-500 dark:focus:border-neutral-500" placeholder="Search" required>
        </div>
        <button type="submit" class="p-2.5 ms-2 text-sm font-medium text-white bg-gray-700 rounded-lg border border-neutral-50 hover:bg-orange-800 focus:ring-4 focus:outline-none focus:ring-gray-300 dark:bg-gray-600 dark:hover:bg-orange-700 dark:focus:ring-gray-800">
            <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
            </svg>
            <span class="sr-only">Search</span>
        </button>
      </form>
    </div>
      <div id="profile-div" class="bg-slate-500 w-3/5 hidden">

        <a href="#" class="block">
          <img 
            alt="ProfilePic" 
            src="https://i.stack.imgur.com/HgkK0.png" 
            class="object-none w-36 h-36 rounded-full custom-position absolute top-20 right-16 lg:top-16 lg:right-40" />
        </a>

      </div>
      <div id="notifications-div" class="bg-slate-500 w-3/5 hidden"></div>
      <div id="rankings-div" class="bg-slate-500 w-3/5 hidden"></div>
      <div id="messages-div" class="bg-slate-500 w-3/5 hidden"></div>
      <div id="createPost-div" class="bg-slate-500 w-3/5 hidden"></div>
    </div>
  </div>

  <script src="../src/js/social.js"></script>
</body>
</html>