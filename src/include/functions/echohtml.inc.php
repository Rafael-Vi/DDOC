<?php
    function echoProfileInfo($username, $email, $profilePic, $realName, $biography){
        global $arrConfig;
        echo '<div class="flex h-32 lg:h-64 mt-8 w-4/6 md:mr-8">';
        echo '<div class="h-full w-full mt-0 lg:mt-4 mb-4 mr-8">';
        echo '<span class="block font-bold text-3xl mt-12 text-orange-500 mb-4">@' . $username . '</span>';
        echo '<div class="font-bold">' . $realName . '</div>';
        echo '<div class="w-full h-full">' . $biography . '</div>';
        echo '</div>';
        echo '</div>';
        echo '<div class="relative mt-8 mb-8">';
        echo '<div class="absolute top-0 border-l-8 border-orange-500 border-solid rounded-lg h-full lg:ml-auto"></div>';
        echo '<img src="'.$profilePic. '" alt="Profile Picture" class="rounded-full w-32 h-32 lg:w-56 lg:h-56 mt-4 ml-8 mr-10 lg:ml-3/5 sm:mr-8 lg:mr-3/5 hover:filter hover:brightness-50 hover:opacity-75">';
        echo '<button class="float-right bg-orange-500 hover:bg-orange-700 text-white font-bold py-4 px-4 rounded-lg flex items-center justify-center h-10 md:h-16 w-32 md:w-16" onclick="openDialog()">Edit Profile</button>';
        echo '</div>';
    }


    function echoUserPosts($post) {
        global $arrConfig;
        echo '<div class="post-container" style="width: 100%; height: 0; padding-bottom: 100%; position: relative; z-10">';
        echo '<img src="'. $arrConfig['url_posts']. $post['post_type'].'/'.$post['post_url'].'" alt="Post Image" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;" class="shadow-md shadow-black hover:filter hover:brightness-20 hover:opacity-75">';
        echo '</div>';  
    }

    function echoNoPosts(){
        echo '<div class="flex justify-center items-center h-64 text-4xl">';
        echo '<span class="w-full font-bold text-center">No Posts</span>';
        echo '</div>';
    }

    function echoThumb($thumb){
        echo '<label for="profile-picture" class="text-white font-bold">What your post will look like:</label>';
        echo '<img src="https://via.placeholder.com/320x320" alt="Thumbnail" class="rounded-sm w-64 h-64 lg:w-56 lg:h-56 mt-4 mr-10 lg:ml-3/5 sm:mr-8 lg:mr-3/5" id="profile-picture">';
    }

    function echoSearch(){
 
        echo '
        <div class="" >
        
        </div>
        ';
    }

    function echoNav(){
        echo'
        <div class="flex w-3/12 flex-column z-40 h-screen" id="topNavBar">
            <div class="relative md:flex md:flex-col md:top-0 md:left-0 md:w-full md:bg-white md:h-full md:border-r hidden md:rounded-lg md:shadow-xl">
                <div class="overflow-y-auto overflow-x-hidden flex-grow">
                    <ul class="flex flex-col py-4 space-y-1">
                    <li class="px-5">
                        <div class="flex flex-row items-center h-32">
                        <img src="../src/assets/images/1.png" alt="" srcset="" class="h-full"></div>
                    </li>
                    <li class="px-4 py-2">
                        <a href="#" id="search-link" style="color: black" onclick="openSearch(event)">Search</a>
                    </li>
                    <li class="px-4 py-2">
                        <a href="./messages.php" id="messages-link" style="color: black">Messages</a>
                    </li>
                    <li class="px-4 py-2">
                        <a href="./Notifications.php" id="notifications-link" style="color: black">Notifications</a>
                    </li>
                    <li class="px-5 py-2">
                        <div class="flex flex-row items-center h-8">
                        <div class="text-sm font-light tracking-wide text-gray-500 border-b-2 border-orange-500 w-full rounded-full"></div>
                        </div>
                    </li>
                    <li class="px-4 py-2">
                        <a href="./social.php" id="home-link" style="color: black">Home</a>
                    </li>
                    <li class="px-4 py-2">
                        <a href="./rankings.php" id="Accrankings-link" style="color: black">Account Rankings</a>
                    </li>
                    <li class="px-4 py-2">
                        <a href="./rankingsPost.php" id="Postrankings-link" style="color: black">Post Rankings</a>
                    </li>
                    <li class="px-4 py-2">
                        <a href="./CreatePost.php" id="createPost-link" style="color: black">Create Post</a>
                    </li>
                    <li class="px-5 py-2">
                        <div class="flex flex-row items-center h-8">
                        <div class="text-sm font-light tracking-wide text-gray-500 border-b-2 border-orange-500 w-full rounded-lg"></div>
                        </div>
                    </li>
                    <li class="px-4 py-2">
                        <a href="./profile.php" id="profile-link" style="color: black">Profile</a>
                    </li>
                    <li class="px-4 py-2">
                        <a href="./settings.php" id="settings-link" style="color: black">Settings</a>
                    </li>
                    <li class="px-4 py-2">
                        <a href="#" id="logout-link" style="color: black">Logout</a>
                    </li>
                    </ul>
                </div>
            </div>
            <div id="search-div" class="relative w-full bg-gray-200 hidden h-full m-auto">
                <div class="flex flex-col h-full">
                <div class="mt-6 mb-2 mx-4 border-b-4 rounded border-b-orange-500">
                    <input type="text" placeholder="Search people" id="search-input" class="bg-gray-100 h-8 py-4 px-2 mb-4 rounded-md text-black font-semibold" />
                </div>
                <div class="flex-1">
                    <div class="mt-4 mx-4 h-5/6 bg-gray-300 relative p-2 rounded-md" id="search-people">
                    <?php echoSearch(); ?>
                    </div>
                </div>
                </div>
            </div>
            </div>
        ';
    }

    function echoBottomNav(){
        echo'
        <nav class="bg-white shadow-md flex items-center md:hidden justify-between absolute bottom-0 w-full">
        <ul class="flex w-full h-12 flex-row justify-between items-center">
            <li><a href="./social.php" id="home-link" class="text-md font-medium text-gray-800 hover:text-gray-700 bg-white rounded-full p-2 mr-1">Home</a></li>
            <li><a href="./rankings.php" id="rankings-link" class="text-md font-medium text-gray-800 hover:text-gray-700 bg-white rounded-full p-2">Rankings</a></li>
            <li><a href="./profile.php"  id="profile-link" class="w-1/5 text-md font-medium active text-gray-800 hover:text-gray-700 bg-yellow-500 rounded-full p-2 m-auto">Profile</a></li>
            <li><a href="./CreatePost.php" id="createPost-link" class="text-md font-medium text-gray-800 hover:text-gray-700 rounded-full p-2 mr-1">+ Post</a></li>
            <li><a href="./settings.php" id ="settings-link" class="text-md font-medium text-gray-800 hover:text-gray-700 rounded-full p-2 mr-3">Settings</a></li>
        </ul>
        </nav>
        ';
    }
?>