<?php

    function echoShowTheme(){
        foreach ($_SESSION['themes'] as $theme) {
            echo '<p>';
            echo 'Theme ID: ' . htmlspecialchars($theme['theme_id']) . '<br>';
            echo 'Theme: ' . htmlspecialchars($theme['theme']) . '<br>';
            echo 'Finish Date: ' . htmlspecialchars($theme['finish_date']) . '<br>';
            echo 'Is Finished: ' . ($theme['is_finished'] ? 'Yes' : 'No');
            echo '</p>';
        }
        echo '</div>';
    }
    function echoProfileInfo($username, $email, $profilePic, $realName, $biography){
        global $arrConfig;
        echo '<div class="block text-3xl sm:text-4xl font-bold text-amber-500">Rank: ' ."---" . '</div>';
        echo '<span class="block font-bold text-3xl mt-4 text-amber-700 mb-4">@' . $username . '</span>';
        echo '<div class="font-bold">' . $realName . '</div>';
        echo '<div class="w-full">' . $biography . '</div>';
        echo '<div class="sm:flex sm:space-x-4 relative m-auto sm:float-right sm:mt-4">';
        echo '<div id="followers-count" class="font-bold">Followers: ---</div>';
        echo '<div id="following-count" class="font-bold">Following: ---</div>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
        echo '<div class="relative mt-8 mb-8">';
        echo '<div class="absolute top-0 border-l-8 border-orange-500 border-solid rounded-lg h-full lg:ml-auto"></div>';
        echo '<img src="' . $profilePic . '" alt="Profile Picture" class="object-contain rounded-full w-32 h-32 md:w-56 md:h-56 mt-4 ml-8 mr-10 lg:ml-3/5 sm:mr-8 md:mr-3/5 hover:filter hover:brightness-50 hover:opacity-75 border-2 border-gray-600">';
    }


    function echoUserPosts($post) {
        global $arrConfig;
        echo '<div class="post-container" style="width: 100%; height: 0; padding-bottom: 100%; position: relative; z-10; overflow: hidden; background: black;">';
        echo '<a class="post-image" href="../src/posts.php?id=' . urlencode($post['post_id']) .'"><img src="'. $arrConfig['url_posts']. $post['post_type'].'/'.$post['post_url'].'" alt="Post Image" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: contain;" class="shadow-md shadow-black hover:filter hover:brightness-20 hover:opacity-75"></a>';
        echo '<button src="https://cdn-icons-png.flaticon.com/512/5400/5400852.png" class="edit-post absolute top-0 right-0 m-2 bg-orange-500 hover:bg-orange-700 text-white font-bold py-2 px-4 rounded" style="visibility: hidden;">Edit</button>';
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

    function echoNav(){
        echo'
        <div class="flex w-3/12 flex-column z-40 h-screen" id="topNavBar">
            <div class="relative md:flex md:flex-col md:top-0 md:left-0 md:w-full md:bg-white md:h-full md:border-r hidden md:rounded-lg md:shadow-xl">
                <div class="overflow-y-auto overflow-x-hidden flex-grow">
                    <ul class="flex flex-col py-4 space-y-1">
                        <li class="px-5">
                            <div class="flex flex-row items-center h-32 w-32">
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
                            <div class="text-sm font-light tracking-wide text-gray-500 border-b-2 border-purple-500 w-full rounded-full"></div>
                            </div>
                        </li>
                        <li class="px-4 py-2 text-black items-center justify-center">
                           
                            <div class="grid grid-flow-col gap-5 text-center auto-cols-max items-center justify-center">
                            <div class="flex flex-col text-black items-center justify-center">
                                <span class="countdown Ubuntu text-4xl">
                                <span id="days" style="--value:0;"></span>
                                </span>
                                Days
                            </div> 
                            <div class="flex flex-col text-black items-center justify-center">
                                <span class="countdown Ubuntu text-4xl">
                                <span id="hours" style="--value:0;"></span>
                                </span>
                                Hours
                            </div> 
                            <div class="flex flex-col text-black items-center justify-center">
                                <span class="countdown Ubuntu text-4xl">    
                                <span id="minutes" style="--value:0;"></span>
                                </span>
                                Min
                            </div> 
                            <div class="flex flex-col text-black items-center justify-center">
                                <span class="countdown Ubuntu text-4xl">
                                <span id="seconds" style="--value:0;"></span>
                                </span>
                                Sec
                            </div>
                            </div>
                            <a href="./rankingsPost.php?    theme='."{$_SESSION['themes'][0]['theme']}".'" id="themes-link" class="text-black hover:filter hover:brightness-50 hover:opacity-75">
                            <div class="theme-name mt-5 ubuntu-bold-italic text-center bg-gray-900 m-1 p-2 rounded-md text-orange-500 items-center justify-center">Theme: '."{$_SESSION['themes'][0]['theme']}".'</div>
                            </a>
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
                            <div class="text-sm font-light tracking-wide text-gray-500 border-b-2 border-purple-500 w-full rounded-lg"></div>
                            </div>
                        </li>
                        <li class="px-4 py-2">
                            <a href="./profile.php" id="profile-link" style="color: black">Profile</a>
                        </li>
                        <li class="px-4 py-2">
                            <a href="./settings.php" id="settings-link" style="color: black">Settings</a>
                        </li>
                        <li class="px-4 py-2">
                        <a href="#" id="logout-link" style="color: black" onclick="logout()">Logout</a>
                        </li>

                    </ul>
                </div>
            </div>
            <div id="search-div" class="relative w-full bg-gray-200 hidden h-full m-auto">
            
                <div class="flex flex-col h-full">
                <div class="mb-2 mx-4 border-b-4 rounded border-b-orange-500 mt-14">
                  <input type="text" placeholder="Search" id="search-input" class="bg-gray-100 h-8 py-4 px-2 mb-4 rounded-md text-black font-semibold" />
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

    function echoShowPost($post){
        global $arrConfig;
        switch($post['post_type']) {
            case 'image':
                echo'
                <!-- First row: Post image -->
                <div class="flex grow-0">
                    <img src="'. $arrConfig['url_posts']. $post['post_type'].'/'.$post['post_url'].'" alt="Post Image" class="object-contain w-full h-full">
                </div>
                ';
                echo'
                <!-- Second row: Caption, like button, like count, and ranking -->
                <div class="flex items-center justify-between py-4 bg-gray-800 rounded-b-lg border-t-4 border-t-orange-500">
                    <span class="text-white text-2xl font-bold ml-4">Caption: "'.$post['caption'].'"</span>
                    <button id="like-button" class="bg-orange-500 hover:bg-orange-700 text-white font-bold py-2 px-4 rounded" onclick="likeCheck()">Like</button>
                    <span class="text-white text-2xl font-bold" id="like-count">Likes: 123</span>
                    <span class="text-white text-2xl font-bold mr-4">Ranking: 1</span>
                </div>';
                
                break;
            case 'audio':
                echo'
                <!-- First row: Post audio -->
                <div class="flex grow-0 my-auto">
                    <audio controls class="w-full h-24">
                        <source src="'. $arrConfig['url_posts']. $post['post_type'].'/'.$post['post_url'].'" type="audio/mpeg">
                        Your browser does not support the audio tag.
                    </audio>
                </div>
                ';
                echo'
                <!-- Second row: Caption, like button, like count, and ranking -->
                <div class="flex items-center justify-between py-4 bg-gray-800 rounded-b-lg border-t-4 border-t-orange-500 my-auto">
                    <span class="text-white text-2xl font-bold ml-4">Caption: "'.$post['caption'].'"</span>
                    <button id="like-button" class="bg-orange-500 hover:bg-orange-700 text-white font-bold py-2 px-4 rounded" onclick="likeCheck()">Like</button>
                    <span class="text-white text-2xl font-bold" id="like-count">Likes: 123</span>
                    <span class="text-white text-2xl font-bold mr-4">Ranking: 1</span>
                </div>';
                break;
            case 'video':
                echo'
                <!-- First row: Post video -->
                <div class="flex grow-0">
                    <video controls class="w-full h-full">
                        <source src="'. $arrConfig['url_posts']. $post['post_type'].'/'.$post['post_url'].'" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                </div>
                ';
                echo'
                <!-- Second row: Caption, like button, like count, and ranking -->
                <div class="flex items-center justify-between py-4 bg-gray-800 rounded-b-lg border-t-4 border-t-orange-500">
                    <span class="text-white text-2xl font-bold ml-4">Caption: "'.$post['caption'].'"</span>
                    <button id="like-button" class="bg-orange-500 hover:bg-orange-700 text-white font-bold py-2 px-4 rounded" onclick="likeCheck()">Like</button>
                    <span class="text-white text-2xl font-bold" id="like-count">Likes: 123</span>
                    <span class="text-white text-2xl font-bold mr-4">Ranking: 1</span>
                </div>';
                break;
        }
    }

    function echoLoadScreen(){
        echo'<div id="loadingScreen" class="fixed inset-0 z-50 bg-white flex items-center justify-center flex-row">
        <p class=" text-4xl text-black">D</p>
        <p class=" text-4xl mx-1 text-black">D</p>
        <div class="loader border-t-4 rounded-full border-t-red-400 bg-orange-300 animate-spin
        aspect-square w-10 flex justify-center items-center text-yellow-700"></div>
        <p class="mx-1 text-4xl  text-black">C ...</p>
    </div>';
    }

    function echoSearchResults($userId, $username, $profilePic){
        echo '<a href="OProfile.php?userid=' . $userId. '" class="inline-block text-orange-500 hover:text-orange-800 transform hover:scale-105 transition-all duration-200">';
        echo '<div class="flex justify-between items-center text-white hover:text-orange-800 bg-gray-800 border-orange-500 border-4 p-2 rounded-lg m-2">';
        echo $username;
        echo '<img src="' . $profilePic . '" alt="Profile Picture" class="w-12 h-12 rounded-full border-orange-500 border-2">';
        echo '</div>';
        echo '</a>';
    }
    function echoConvo(){
        echo '<a href="messages.php?convo_id=" class="text-orange-500 hover:text-orange-800 transform hover:scale-110 transition-all duration-200 mb-4">';
        echo '<div class="flex justify-between items-center text-white hover:text-orange-800 bg-gray-800 p-2 rounded-lg m-2 transform hover:scale-105 transition-transform duration-200 relative">';
        echo '<div class="flex items-center">';
        echo '<div class="w-12 h-12 rounded-full bg-gray-500 mr-4"></div>'; // Circle for the profile picture
        echo '<div>';
        echo '<div class="font-bold">Miguel</div>'; // Name
        echo '<div class="text-sm leading-relaxed">Last message...</div>'; // Last message
        echo '</div>';
        echo '</div>';
        echo '<div class="absolute bottom-0 left-0 w-full h-1 bg-gradient-to-r from-purple-500 to-orange-500 rounded-b-lg"></div>'; // Gradient border
        echo '</div>';
        echo '</a>';
    }

    function echoNotif(){
        echo '<a href="#" class="text-orange-500 hover:text-orange-800 transform hover:scale-110 transition-all duration-200 mb-4">';
        echo '<div class="flex justify-between items-center text-white hover:text-orange-800 bg-gray-800 p-2 rounded-lg m-2 transform hover:scale-105 transition-transform duration-200 relative">';
        echo '<div class="flex items-center">';
        echo '<div class=" h-1 mr-4"></div>'; // Circle for the profile picture
        echo '<div>';
        echo '<div class="font-bold text-xl">Date of notif</div>'; // N
        echo '<div class="font-semibold text-md">Person that notificated you</div>'; // Name
        echo '<div class="text-sm leading-relaxed">Notification message</div>'; // Last message
        echo '</div>';
        echo '</div>';
        echo '<div class="ml-auto">🔔</div>'; // Notification symbol
        echo '<div class="absolute bottom-0 left-0 w-full h-1 bg-gradient-to-r from-purple-500 to-orange-500 rounded-b-lg"></div>'; // Gradient border
        echo '</div>';
        echo '</a>';
    }
    
    function echoMessages(){
        echo'<div class="chat chat-start">
        <div class="chat-image avatar">
          <div class="w-10 rounded-full">
            <img alt="Tailwind CSS chat bubble component" src="https://daisyui.com/images/stock/photo-1534528741775-53994a69daeb.jpg" />
          </div>
        </div>
        <div class="chat-header">
          Obi-Wan Kenobi
          <time class="text-xs opacity-50">12:45</time>
        </div>
        <div class="chat-bubble">You were the Chosen One!</div>

      </div>
      <div class="chat chat-end">
        <div class="chat-image avatar">
          <div class="w-10 rounded-full">
            <img alt="Tailwind CSS chat bubble component" src="https://daisyui.com/images/stock/photo-1534528741775-53994a69daeb.jpg" />
          </div>
        </div>
        <div class="chat-header">
          Anakin
          <time class="text-xs opacity-50">12:46</time>
        </div>
        <div class="chat-bubble">I hate you!</div>
      </div>';
    }

    function echoRankPosts($rank, $image, $name, $type, $likes, $poster){
        global $arrConfig;  
        echo'
        <div class="flex w-full text-center justify-center bg-gray-800 p-2 border-r-2 border-gray-900 shadow-lg mb-2 hover:bg-gray-700 transition-colors duration-200">
        <div class="text-white w-1/6">'.$rank.'</div> <!-- Rank of the post -->
        <div class="flex items-center justify-center w-1/6"><img src="'. $arrConfig['url_posts'].'/'.$type.'/'.$image.'" alt="Post Image" class="w-16 h-16"></div> <!-- Image of the post -->
        <div class="text-white w-1/6">'.$name.'</div> <!-- Name of the post -->
        <div class="text-white w-1/6">'.$type.'</div> <!-- Type -->
        <div class="text-white w-1/6">'.$likes.'</div> <!-- Likes -->
        <div class="text-white w-1/6">@'.$poster.'</div> <!-- Person who posted it -->
        </div>
        ';
    }

    function echoRankAcc($rank, $likes, $poster){
        echo '
        <div class="flex w-full text-center justify-center bg-gray-800 p-2 border-r-2 border-gray-900 shadow-lg mb-2 hover:bg-gray-700 transition-colors duration-200">
        <div class="text-white w-1/3">'.$rank.'</div> <!-- Rank of the post -->
        <div class="text-white w-1/3">'.$likes.'</div> <!-- Likes -->
        <div class="text-white w-1/3">@'.$poster.'</div> <!-- Person who posted it -->
        </div>
        ';
    }
?>