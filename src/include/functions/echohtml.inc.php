<?php
    function echoProfileInfo($username, $email, $profilePic, $realName, $biography){
        echo '<div class="flex h-32 lg:h-64 mt-8 w-4/6  mr-8">';
        echo '<div class="h-full w-full mt-0 lg:mt-4 mb-4 mr-8">';
        echo '<span class="block font-bold text-3xl mt-12 text-orange-500 mb-4">@' . $username . '</span>';
        echo '<div class="font-bold">' . $realName . '</div>';
        echo '<div class="w-full h-full">' . $biography . '</div>';
        echo '</div>';
        echo '</div>';
        echo '<div class="relative mt-8 mb-8">';
        echo '<div class="absolute top-0 border-l-8 border-orange-500 border-solid rounded-lg h-full lg:ml-auto"></div>';
        echo '<img src="' . $profilePic . '" alt="Profile Picture" class="rounded-full w-32 h-32 lg:w-56 lg:h-56 mt-4 ml-8 mr-10 lg:ml-3/5 sm:mr-8 lg:mr-3/5b hover:filter hover:grayscale hover:blur-sm hover:drop-shadow-sm">';
        echo '</div>';
    }


    function echoUserPosts($post) {

        echo '<div class="post-container" style="width: 100%; height: 0; padding-bottom: 100%; position: relative;">';
        echo '<img src="" alt="Post Image" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;" class="bg-black">';
        echo '</div>';  
    }

    function echoNoPosts(){
        echo '<div class="flex justify-center items-center h-full text-4xl">';
        echo '<span class="w-full font-bold text-center">No Posts</span>';
        echo '</div>';
    }
?>