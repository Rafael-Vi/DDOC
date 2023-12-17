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

    <div class="absolute lg:flex flex-col top-0 left-0 w-1/5 bg-white h-full border-r hidden rounded-lg shadow-xl">
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
    
    </div>
  
    <div class="bg-gray-600 fixed w-full lg:w-4/5 p-0 m-0 lg:right-0 h-full flex flex-col justify-center items-center" id="home-div">
      <h5 class="h-64 border border-black w-full p-10">
        Flex item 1
      </h5>

      <h5 class="h-full border border-black w-full p-10">
        Flex item 2
      </h5>
    </div>


  
      <div id="profile-div" class="hidden fixed flex flex-col h-full w-full lg:w-4/5 p-0 m-0 bg-gray-900 lg:right-0">
        <div id="profileInfo-div" class="flex-grow-1 b-8 relative flex flex-row  bg-gray-800 h-60 lg:h-80" >
          <?php
            getUserInfo($_SESSION['uid']); 
          ?>
        </div>
        <div id="profilePosts-div" class="hidden flex-grow-1 relative flex">

        </div>
      </div>
    
      <div id="messages-div" class="hidden bg-red-600 fixed flex flex-col h-full w-full lg:w-4/5 p-0 m-0 lg:right-0">
  <div></div>
  <div></div>
</div>

<div id="notifications-div" class="hidden bg-slate-950 fixed flex flex-col h-full w-full lg:w-4/5 p-0 m-0 lg:right-0">
  <div></div>
  <div></div>
</div>

<div id="rankings-div" class="hidden bg-blue-600 fixed flex flex-col h-full w-full lg:w-4/5 p-0 m-0 lg:right-0">
  <div></div>
  <div></div>
</div>

<div id="createPost-div" class="hidden bg-orange-600 fixed flex flex-col h-full w-full lg:w-4/5 p-0 m-0 lg:right-0">
  <div></div>
  <div></div>
</div>

<div id="settings-div" class="hidden bg-yellow-600 fixed flex flex-col h-full w-full lg:w-4/5 p-0 m-0 lg:right-0">
  <div></div>
  <div></div>
</div>


</div>

</div>
        <section id="bottom-navigation" class="lg:hidden block fixed inset-x-0 bottom-0 z-10 bg-white shadow">
          <div id="tabs" class="flex justify-between items-center mt">
            <a href="#" class="w-full text-black focus:text-orange-500 hover:text-orange-500 justify-center inline-block text-center pt-2 pb-1">
              <svg width="25" height="25" viewBox="0 0 42 42" class="inline-block mb-1">
                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                    <path d="M21.0847458,3.38674884 C17.8305085,7.08474576 17.8305085,10.7827427 21.0847458,14.4807396 C24.3389831,18.1787365 24.3389831,22.5701079 21.0847458,27.6548536 L21.0847458,42 L8.06779661,41.3066256 L6,38.5331279 L6,26.2681048 L6,17.2542373 L8.88135593,12.4006163 L21.0847458,2 L21.0847458,3.38674884 Z" fill="currentColor" fill-opacity="0.1"></path>
                    <path d="M11,8 L33,8 L11,8 Z M39,17 L39,36 C39,39.3137085 36.3137085,42 33,42 L11,42 C7.6862915,42 5,39.3137085 5,36 L5,17 L7,17 L7,36 C7,38.209139 8.790861,40 11,40 L33,40 C35.209139,40 37,38.209139 37,36 L37,17 L39,17 Z" fill="currentColor"></path>
                    <path d="M22,27 C25.3137085,27 28,29.6862915 28,33 L28,41 L16,41 L16,33 C16,29.6862915 18.6862915,27 22,27 Z" stroke="currentColor" stroke-width="2" fill="currentColor" fill-opacity="0.1"></path>
                    <rect fill="currentColor" transform="translate(32.000000, 11.313708) scale(-1, 1) rotate(-45.000000) translate(-32.000000, -11.313708) " x="17" y="10.3137085" width="30" height="2" rx="1"></rect>
                    <rect fill="currentColor" transform="translate(12.000000, 11.313708) rotate(-45.000000) translate(-12.000000, -11.313708) " x="-3" y="10.3137085" width="30" height="2" rx="1"></rect>
                </g>
              </svg>
            </a>
            <a href="#" class="w-full text-black focus:text-orange-500 hover:text-orange-500 justify-center inline-block text-center pt-2 pb-1">
              <svg width="25" height="25" viewBox="0 0 42 42" class="inline-block mb-1">
                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                  <path d="M14.7118754,20.0876892 L8.03575361,20.0876892 C5.82661462,20.0876892 4.03575361,18.2968282 4.03575361,16.0876892 L4.03575361,12.031922 C4.03575361,8.1480343 6.79157254,4.90780265 10.4544842,4.15995321 C8.87553278,8.5612583 8.1226025,14.3600511 10.9452499,15.5413938 C13.710306,16.6986332 14.5947501,18.3118357 14.7118754,20.0876892 Z M14.2420017,23.8186831 C13.515543,27.1052019 12.7414284,30.2811559 18.0438552,31.7330419 L18.0438552,33.4450645 C18.0438552,35.6542035 16.2529942,37.4450645 14.0438552,37.4450645 L9.90612103,37.4450645 C6.14196811,37.4450645 3.09051926,34.3936157 3.09051926,30.6294627 L3.09051926,27.813861 C3.09051926,25.604722 4.88138026,23.813861 7.09051926,23.813861 L14.0438552,23.813861 C14.1102948,23.813861 14.1763561,23.8154808 14.2420017,23.8186831 Z M20.7553776,32.160536 C23.9336213,32.1190063 23.9061943,29.4103976 33.8698747,31.1666916 C34.7935223,31.3295026 35.9925894,31.0627305 37.3154077,30.4407183 C37.09778,34.8980343 33.4149547,38.4450645 28.9036761,38.4450645 C24.9909035,38.4450645 21.701346,35.7767637 20.7553776,32.160536 Z" fill="currentColor" opacity="0.1"></path>
                  <g transform="translate(2.000000, 3.000000)">
                      <path d="M8.5,1 C4.35786438,1 1,4.35786438 1,8.5 L1,13 C1,14.6568542 2.34314575,16 4,16 L13,16 C14.6568542,16 16,14.6568542 16,13 L16,4 C16,2.34314575 14.6568542,1 13,1 L8.5,1 Z" stroke="currentColor" stroke-width="2"></path>
                      <path d="M4,20 C2.34314575,20 1,21.3431458 1,23 L1,27.5 C1,31.6421356 4.35786438,35 8.5,35 L13,35 C14.6568542,35 16,33.6568542 16,32 L16,23 C16,21.3431458 14.6568542,20 13,20 L4,20 Z" stroke="currentColor" stroke-width="2"></path>
                      <path d="M23,1 C21.3431458,1 20,2.34314575 20,4 L20,13 C20,14.6568542 21.3431458,16 23,16 L32,16 C33.6568542,16 35,14.6568542 35,13 L35,8.5 C35,4.35786438 31.6421356,1 27.5,1 L23,1 Z" stroke="currentColor" stroke-width="2"></path>
                      <path d="M34.5825451,33.4769886 L38.3146092,33.4322291 C38.8602707,33.4256848 39.3079219,33.8627257 39.3144662,34.4083873 C39.3145136,34.4123369 39.3145372,34.4162868 39.3145372,34.4202367 L39.3145372,34.432158 C39.3145372,34.9797651 38.8740974,35.425519 38.3265296,35.4320861 L34.5944655,35.4768456 C34.048804,35.4833899 33.6011528,35.046349 33.5946085,34.5006874 C33.5945611,34.4967378 33.5945375,34.4927879 33.5945375,34.488838 L33.5945375,34.4769167 C33.5945375,33.9293096 34.0349773,33.4835557 34.5825451,33.4769886 Z" fill="currentColor" transform="translate(36.454537, 34.454537) rotate(-315.000000) translate(-36.454537, -34.454537) "></path>
                      <circle stroke="currentColor" stroke-width="2" cx="27.5" cy="27.5" r="7.5"></circle>
                  </g>
                </g>
              </svg>
            </a>
            <a href="#" class="w-full text-black focus:text-orange-500 hover:text-orange-500 justify-center inline-block text-center pt-2 pb-1">
              <svg width="25" height="25" viewBox="0 0 42 42" class="inline-block mb-1">
                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                    <path d="M20.5890101,0.254646884 C12.8696785,5.50211755 8.0025785,14.258415 14.1941217,18.8708225 C23.16683,25.5550669 13.3362326,40.2698884 33.1021758,38.4149164 C29.6814884,40.8311956 25.5065164,42.2507054 21,42.2507054 C9.40202025,42.2507054 0,32.8486852 0,21.2507054 C0,9.79003409 9.18071714,0.473634138 20.5890101,0.254646884 Z" fill="currentColor" opacity="0.1"></path>
                    <path d="M25.9500282,20.3643496 L22.4308312,38.2677802 C22.3775703,38.5387376 22.1147395,38.7152155 21.8437821,38.6619546 C21.6570955,38.6252584 21.507413,38.4857901 21.4576354,38.3021581 L16.5951895,20.3643496 L20.099732,4.44663907 C20.1385204,4.27046145 20.2692032,4.12883813 20.4417012,4.07604096 C20.7057521,3.99522179 20.9853245,4.14376046 21.0661436,4.40781135 L25.9500282,20.3643496 Z M21.3022963,22.2852638 C22.4068658,22.2852638 23.3022963,21.3898333 23.3022963,20.2852638 C23.3022963,19.1806943 22.4068658,18.2852638 21.3022963,18.2852638 C20.1977268,18.2852638 19.3022963,19.1806943 19.3022963,20.2852638 C19.3022963,21.3898333 20.1977268,22.2852638 21.3022963,22.2852638 Z" fill="currentColor" transform="translate(21.272609, 20.629524) rotate(-315.000000) translate(-21.272609, -20.629524) "></path>
                    <circle stroke="currentColor" stroke-width="2" cx="21" cy="21" r="20"></circle>
                  </g>
                </svg>
            </a>
            <a href="#" class="w-full text-black focus:text-orange-500 hover:text-orange-500 justify-center inline-block text-center pt-2 pb-1">
              <svg width="25" height="25" viewBox="0 0 42 42" class="inline-block mb-1">
                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                  <path d="M14.7118754,20.0876892 L8.03575361,20.0876892 C5.82661462,20.0876892 4.03575361,18.2968282 4.03575361,16.0876892 L4.03575361,12.031922 C4.03575361,8.1480343 6.79157254,4.90780265 10.4544842,4.15995321 C8.87553278,8.5612583 8.1226025,14.3600511 10.9452499,15.5413938 C13.710306,16.6986332 14.5947501,18.3118357 14.7118754,20.0876892 Z M14.2420017,23.8186831 C13.515543,27.1052019 12.7414284,30.2811559 18.0438552,31.7330419 L18.0438552,33.4450645 C18.0438552,35.6542035 16.2529942,37.4450645 14.0438552,37.4450645 L9.90612103,37.4450645 C6.14196811,37.4450645 3.09051926,34.3936157 3.09051926,30.6294627 L3.09051926,27.813861 C3.09051926,25.604722 4.88138026,23.813861 7.09051926,23.813861 L14.0438552,23.813861 C14.1102948,23.813861 14.1763561,23.8154808 14.2420017,23.8186831 Z M20.7553776,32.160536 C23.9336213,32.1190063 23.9061943,29.4103976 33.8698747,31.1666916 C34.7935223,31.3295026 35.9925894,31.0627305 37.3154077,30.4407183 C37.09778,34.8980343 33.4149547,38.4450645 28.9036761,38.4450645 C24.9909035,38.4450645 21.701346,35.7767637 20.7553776,32.160536 Z" fill="currentColor" opacity="0.1"></path>
                  <g transform="translate(2.000000, 3.000000)">
                      <path d="M8.5,1 C4.35786438,1 1,4.35786438 1,8.5 L1,13 C1,14.6568542 2.34314575,16 4,16 L13,16 C14.6568542,16 16,14.6568542 16,13 L16,4 C16,2.34314575 14.6568542,1 13,1 L8.5,1 Z" stroke="currentColor" stroke-width="2"></path>
                      <path d="M4,20 C2.34314575,20 1,21.3431458 1,23 L1,27.5 C1,31.6421356 4.35786438,35 8.5,35 L13,35 C14.6568542,35 16,33.6568542 16,32 L16,23 C16,21.3431458 14.6568542,20 13,20 L4,20 Z" stroke="currentColor" stroke-width="2"></path>
                      <path d="M23,1 C21.3431458,1 20,2.34314575 20,4 L20,13 C20,14.6568542 21.3431458,16 23,16 L32,16 C33.6568542,16 35,14.6568542 35,13 L35,8.5 C35,4.35786438 31.6421356,1 27.5,1 L23,1 Z" stroke="currentColor" stroke-width="2"></path>
                      <path d="M34.5825451,33.4769886 L38.3146092,33.4322291 C38.8602707,33.4256848 39.3079219,33.8627257 39.3144662,34.4083873 C39.3145136,34.4123369 39.3145372,34.4162868 39.3145372,34.4202367 L39.3145372,34.432158 C39.3145372,34.9797651 38.8740974,35.425519 38.3265296,35.4320861 L34.5944655,35.4768456 C34.048804,35.4833899 33.6011528,35.046349 33.5946085,34.5006874 C33.5945611,34.4967378 33.5945375,34.4927879 33.5945375,34.488838 L33.5945375,34.4769167 C33.5945375,33.9293096 34.0349773,33.4835557 34.5825451,33.4769886 Z" fill="currentColor" transform="translate(36.454537, 34.454537) rotate(-315.000000) translate(-36.454537, -34.454537) "></path>
                      <circle stroke="currentColor" stroke-width="2" cx="27.5" cy="27.5" r="7.5"></circle>
                  </g>
                </g>
              </svg>
            </a>
            <a href="#" class="w-full text-black focus:text-orange-500 hover:text-orange-500 justify-center text-center pt-2 pb-1 flex items-center">
              <svg width="25" height="25" viewBox="0 0 42 42" class="inline-block mb-1">
                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                  <g transform="translate(2.000000, 3.000000)">
                    <?php
                      echo '<img src="' . $_SESSION['imageProfile'] . '" alt="Profile Picture" class="rounded-full w-12 h-3/4 hover:border-4 hover:border-orange-500">';
                    ?>
                    <circle stroke="currentColor" stroke-width="2" cx="27.5" cy="27.5" r="7.5"></circle>
                  </g>
                </g>
              </svg>
            </a>
          </div>
        </section>
  <script src="../src/js/social.js"></script>
</body>
</html>