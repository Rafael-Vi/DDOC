<?php

    function echoShowTheme(){
        foreach ($_SESSION['themes'] as $theme) {
            echo '<p>';
            echo 'Theme ID: ' . htmlspecialchars($theme['id_theme']) . '<br>';
            echo 'Theme: ' . htmlspecialchars($theme['theme']) . '<br>';
            echo 'Finish Date: ' . htmlspecialchars($theme['finish_date']) . '<br>';
            echo 'Is Finished: ' . ($theme['is_finished'] ? 'Yes' : 'No');
            echo '</p>';
        }
        echo '</div>';
    }

    function echoProfileInfo($username, $email, $profilePic, $realName, $biography, $rank){
        global $arrConfig;
        // Main container with flex row to ensure alignment to the right
        echo '<div class="profile-info-container flex flex-row justify-end items-center w-full">';
    
        // Text Information in a flex column, aligned to the left of the image
        echo '<div class="text-info flex flex-col text-right mr-8">';
        echo '<div class="block text-3xl sm:text-4xl font-bold text-amber-500">Rank: #' . $rank . '</div>';
        echo '<span class="block font-bold text-3xl mt-4 text-amber-700 mb-4">@' . $username . '</span>';
        echo '<div class="font-bold text-white">' . $realName . '</div>';
        echo '<div class="text-white">' . $biography . '</div>';
        echo '<div class="flex flex-row justify-end space-x-4 gap-4 mt-4">';
        echo '<a href="#" onclick="showFollow(\'follower\'); return false;"><div id="followers-count" class="font-bold text-white hover:text-orange-500">Seguidores: ---</div></a>';
        echo '<a href="#" onclick="showFollow(\'following\'); return false;"><div id="following-count" class="font-bold text-white hover:text-orange-500">A seguir: ---</div></a>';
        echo '</div>';
        echo '</div>'; // Close text-info container
    
        // Image Container, aligned to the right of the text
        echo '<div class="flex flex-col justify-center items-center w-1/2 ml-4">';
        echo '<div class="avatar mb-4">';
        echo '<div class="w-36 h-36 rounded-full overflow-hidden">';
        echo '<img src="' . $profilePic . '" alt="Profile Picture" class="w-full h-full object-cover"/>';
        echo '</div>';
        echo '</div>';

    }
    function echoUserPosts($post) {
        global $arrConfig;
    
        echo '<div class="post-container border-2 border-white" style="width: 100%; height: 0; padding-bottom: 100%; position: relative; z-10; overflow: hidden; background: black;">';
        echo '<a class="post-image" href="../post/' . urlencode($post['post_id']) .'">';
    
        if ($post['post_type'] == 'video') {
            echo '<div style="display: flex; justify-content: center; align-items: center; height: 100%; width: 100%;">';
            echo '<video width="100%" height="100%" style="object-fit: fill; margin: auto;" class="shadow-md shadow-black hover:filter hover:brightness-20 hover:opacity-75">';
            echo '<source src="'. $arrConfig['url_posts']. $post['post_type'].'/'.$post['post_url'].'" type="video/mp4">';
            echo 'Your browser does not support the video tag.';
            echo '</video>';
            echo '</div>';
        } else if ($post['post_type'] == 'audio') {
            echo '<img src="'. $arrConfig['url_assets'].'images/audio.jpg" alt="Audio Image" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: contain;" class="shadow-md shadow-black hover:filter hover:brightness-20 hover:opacity-75">'; // Display audio.jpeg for audio type
        } else {
            echo '<img src="'. $arrConfig['url_posts']. $post['post_type'].'/'.$post['post_url'].'" alt="'.$post['caption'].'" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: contain;" class="shadow-md shadow-black hover:filter hover:brightness-20 hover:opacity-75">';
        }
    
        echo '</a>';
        echo '<button disabled class="new-button absolute top-0 right-0 m-2 bg-slate-800 text-white font-bold py-2 px-4 mr-8 rounded" style="visibility: hidden; margin-right: 80px;">'.htmlspecialchars($post['caption']).'</button>'; // New disabled button with unique class
        echo '<button src="https://cdn-icons-png.flaticon.com/512/5400/5400852.png" onclick="showMyModal(\'' . htmlspecialchars(addslashes($post['post_id']), ENT_QUOTES) . '\', \'' . htmlspecialchars(addslashes($post['caption']), ENT_QUOTES) . '\')" class="edit-post absolute top-0 right-0 m-2 bg-orange-500 hover:bg-orange-700 text-white font-bold py-2 px-4 rounded" data-id="' . htmlspecialchars(urlencode($post['post_id']), ENT_QUOTES) . '" style="visibility: hidden;">Editar</button>';
        echo '</div>';  
    }

    function echoNoPosts(){
        echo '<div class="flex justify-center items-center h-64 text-4xl">';
        echo '<span class="w-full font-bold text-center">Sem Posts</span>';
        echo '</div>';
    }
    function echoNav(){
        echo'
        <div class="flex w-3/12 flex-column z-40 h-screen" id="topNavBar">
            <div class="relative md:flex md:flex-col md:top-0 md:left-0 md:w-full md:bg-white md:h-full md:border-r hidden md:shadow-xl">
                <div class="overflow-y-auto overflow-x-hidden flex-grow">
                    <ul class="flex flex-col py-4 space-y-1">
                        <a href="/social">
                        <li class="px-5">
                            <div class="flex flex-row items-center  h-32 text-white w-32">
                            <img src="/src/assets/images/1.png" alt="" srcset="" class="h-full"></div>
                        </li>
                        </a>
                        <li class="px-4 py-2">
                        <a href="#" id="search-link" class="" style="color: black"><i class="mr-2 fi fi-sr-search"></i> Pesquisa</a>
                        </li>
                        <li class="px-4 py-2">
                            <a href="/mensagens" id="messages-link" style="color: black"><i class="mr-2 fi fi-sr-comment-alt"></i> Mensagens</a>
                        </li>
                        <li class="px-4 py-2 flex justify-between items-center">
                            <a href="/notificacoes" id="notifications-link" style="color: black">
                                <i class="mr-2 fi fi-sr-megaphone"></i> Notificações
                            </a>
                            <div id="notif-number-cleaner" class="*:rounded-full h-8 w-8 flex items-center justify-center text-sm ubuntu-bold">
                            </div>
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
                                Dias
                            </div> 
                            <div class="flex flex-col text-black items-center justify-center">
                                <span class="countdown Ubuntu text-4xl">
                                <span id="hours" style="--value:0;"></span>
                                </span>
                                Horas
                            </div> 
                            <div class="flex flex-col text-black items-center justify-center">
                                <span class="countdown Ubuntu text-4xl">    
                                <span id="minutes" style="--value:0;"></span>
                                </span>
                                Minutos
                            </div> 
                            <div class="flex flex-col text-black items-center justify-center">
                                <span class="countdown Ubuntu text-4xl">
                                <span id="seconds" style="--value:0;"></span>
                                </span>
                                Segundos
                            </div>
                            </div>
                            <a href="/ranking-posts/'."{$_SESSION['themes'][0]['id_theme']}".'/none" id="themes-link" class="text-black hover:filter hover:brightness-50 hover:opacity-75">
                            <div class="theme-name mt-5 ubuntu-bold-italic text-center bg-gray-900 m-1 p-2 rounded-md text-orange-500 items-center justify-center">Tema: '."{$_SESSION['themes'][0]['theme']}".'</div>
                            </a>
                        </li>
                        <li class="px-4 py-2">
                            <a href="/social" id="home-link" style="color: black"><i class="mr-2 fi fi-sr-home"></i> Home</a>
                        </li>
                        <li class="px-4 py-2">
                            <a href="/ranking-contas" id="Accrankings-link" style="color: black"><i class="mr-2 fi fi-sr-users-alt"></i> Ranks de Contas</a>
                        </li>
                        <li class="px-4 py-2">
                            <a href="/ranking-posts" id="Postrankings-link" style="color: black"><i class="mr-2 fi fi-sr-stats"></i> Ranks dos Posts</a>
                        </li>
                        <li class="px-4 py-2">
                            <a href="/criar-post" id="createPost-link" style="color: black"><i class="mr-2 fi fi-sr-add"></i> Publicar</a>
                        </li>
                        <li class="px-5 py-2">
                            <div class="flex flex-row items-center h-8">
                            <div class="text-sm font-light tracking-wide text-gray-500 border-b-2 border-purple-500 w-full rounded-lg"></div>
                            </div>
                        </li>
                        <li class="px-4 py-2">
                            <a href="/perfil" id="profile-link" style="color: black"><i class="mr-2 fi fi-sr-user"></i> Perfil</a>
                        </li>
                        <li class="px-4 py-2">
                            <a href="/definicoes" id="settings-link" style="color: black"><i class="mr-2 fi fi-sr-settings-sliders"></i> Definições</a>
                        </li>
                        <li class="px-4 py-2">
                        <a href="#" id="logout-link" style="color: black" onclick="logout()">Logout</a>
                        </li>

                    </ul>
                </div>
            </div>
            <div id="search-div" class=" absolute top-0 left-0 sm:relative w-full bg-gray-200 hidden h-full m-auto">
            <div class="flex flex-col h-full">
          
                    <div class="mb-2 mx-4 border-b-4 rounded border-b-orange-500 mt-8 flex">
                        <input type="text" placeholder="🔍  Pesquisar" id="search-input" class="bg-gray-100 h-8 py-4 px-2 w-full sm:w-auto mb-8 rounded-md text-black font-semibold" />
                       <button id="search-button" class="w-8 h-8 ml-2 ubuntu-bold rounded-full hover:bg-gray-800 text-black hover:text-white">x</button>
                    </div>
                    <div class="flex-1 items-center">
                        <div class="mt-4 mx-4 h-5/6 relative p-2 rounded-md" id="search-people">
                        </div>
                    </div>
                </div>
            </div>
            </div>
        ';
    }

    function displayPodium($podium, $positionName) {
        // Initialize $imageHtml with a default value (e.g., an empty string or a default image HTML code)
$imageHtml = '';
        global $arrConfig;
        if ($podium) {
            // Determine the name to display based on available data
            if (isset($podium['NameOfThePost'])) {
                $postName = $podium['NameOfThePost'];
            } elseif (isset($podium['username'])) {
                $postName = $podium['username'];
            } else {
                $postName = 'Desconhecido';
            }
    
            // Check if an image is available and set the image HTML
            $imageHtml = '';
            if (isset($podium['image']) && !empty($podium['image'])) {
                $imageHtml = "<img src=\"{$arrConfig['url_users']}/{$podium['image']}\" alt=\"User Image\" class=\"rounded-full mb-2\" style=\"width: 100px; height: 100px;\">";
            }
        } else {
            $postName = 'Nenhum';
        }
    
        // Determine the height class based on the position name
        $heightClass = $positionName === 'Primeiro Lugar' ? 'h-28 w-28' : ($positionName === 'Segundo Lugar' ? 'h-20 w-24' : 'h-16 w-24');
    
        // Truncate $postName if it is longer than 15 characters
        if (strlen($postName) > 20) {
            $postName = substr($postName, 0, 20) . "...";
        }
        // Display the podium position
        echo "<div class=\"flex flex-col items-center mt-auto\">";
        echo $imageHtml; // Display the image if available
        echo "<h1 class=\"mb-2 text-white\">$postName</h1>";
        echo "<div class=\"bg-gray-800 rounded-lg text-center p-4 $heightClass relative flex items-center justify-center m-4  text-white sm:m-0\">$positionName</div>";
        echo "</div>";
    }
    function echoBottomNav(){
        echo'
        <div class="btm-nav sm:hidden bg-white shadow-md flex items-center z-40 justify-between absolute bottom-0 w-full shadow-top">
            <div class="flex-1 text-md font-medium text-gray-800 hover:text-gray-700 bg-white rounded-full p-2 mr-1 flex items-center justify-center">
                <a href="/social" class="flex flex-col items-center justify-center">
                    <i class="fi fi-sr-home"></i>
                    <span class="btm-nav-label">Home</span>
                </a>
            </div>
            <div class="flex-1 text-md font-medium text-gray-800 hover:text-gray-700 bg-white rounded-full p-2 mr-1 flex items-center justify-center">
                <a href="#" class="flex flex-col items-center justify-center"  onclick="openSearch(event)">
                    <i class="fi fi-sr-search"></i>
                    <span class="btm-nav-label">Pesquisa</span>
                </a>
            </div>
            <div class="flex-1 text-md font-medium text-gray-800 hover:text-gray-700 bg-yellow-500 rounded-full p-2 m-auto flex items-center justify-center">
                <a href="/perfil"" class="flex flex-col items-center justify-center">
                    <i class="fi fi-sr-user"></i> 
                    <span class="btm-nav-label">Perfil</span>
                </a>
            </div>
            <div class="flex-1 text-md font-medium text-gray-800 hover:text-gray-700 bg-white rounded-full p-2 mr-1 flex items-center justify-center">
                <a href="/criar-post"" class="flex flex-col items-center justify-center">
                    <i class="fi fi-sr-add"></i>
                    <span class="btm-nav-label">Criar</span>
                </a>
            </div>
            <div class="flex-1 text-md font-medium text-gray-800 hover:text-gray-700 bg-white rounded-full p-2 mr-1 flex items-center justify-center">
                <a href="#" class="flex flex-col items-center justify-center">
                    <i class="fi fi-sr-menu-burger"></i>
                    <span class="btm-nav-label">Mais</span>
                </a>
            </div>
        </div>
        <style>
        .shadow-top::before {
            content: "";
            position: absolute;
            top: -1px; /* Adjust this value to change the height of the shadow */
            left: 0;
            right: 0;
            height: 5px; /* Adjust this value to change the height of the shadow */
            box-shadow: 0px -5px 5px rgba(0, 0, 0, 0.1); /* Adjust the color, spread and blur radius as needed */
            z-index: -1;
        }
        </style>
        ';
    }

    function echoShowPost($post, $creator){
        global $arrConfig;
        // Check if creator has an avatar, if not use a default from $arrConfig
        $avatarURL = !empty($creator['avatar_url']) ? $arrConfig['url_users'].$creator['avatar_url'] : $arrConfig['url_assets'].'images/Unknown_person.jpg';
    
        echo'<div id="post-'.$post['post_id'].'" class="h-full w-full p-10 flex flex-col relative bottom-0 overflow-auto">';
        switch($post['post_type']) {
            case 'image':
                echo'
                    <div class="flex items-center justify-between py-4 pl-8 bg-base-200 rounded-t-lg border-b-4 border-b-orange-500 my-auto">
                    <a class="hover:scale-105" href="../perfil-de-outro/'.$creator['id'].'"><div class="flex items-center">
                    <img src="'.$avatarURL.'" alt="Avatar" class="rounded-full h-10 w-10 bg-red-800">
                        <span class="text-xl font-bold ml-4">@'.$creator['name'].'</span>
                    </div>
                    </a>
                    <button onclick="openReport(\'' . $post['post_id'] . '\',\''  . $post['caption'] . '\')" class="bg-gray-800 hover:bg-orange-500 text-white font-bold py-2 px-4 rounded mr-4">Reportar</button>
                    </div>
                    <div class="flex flex-col justify-center items-center bg-base-200 h-full">
                    <img src="'. $arrConfig['url_posts']. $post['post_type'].'/'.$post['post_url'].'" alt="Post Image" class="rounded-lg w-full h-auto block mx-auto object-contain max-h-[60vh]">
                    </div>
                    <div class="flex items-center justify-between py-4 bg-base-200 rounded-b-lg border-t-4 border-t-orange-500">
                        <textarea class="text-2xl font-bold ml-4" readonly>'.$post['caption'].'</textarea>';
                if ($post['enabled'] == 0) {
                    echo '<button id="like-button-' . $post['post_id'] . '" class="bg-orange-500 hover:bg-orange-700 text-white font-bold py-2 px-4 rounded" onclick="likeCheck(' . $post['post_id'] . ')">Like</button>';
                } else {
                    echo '<button id="like-button' . $post['post_id'] . '" class="btn btn-ghost w-1/4 text-white font-bold py-2 px-4 rounded" disabled>Desativado</button>';
                }
                echo'
                    <span class="text-2xl font-bold" id="like-count-'.$post['post_id'].'">Gostos: <span class="loading loading-ring loading-lg text-warning"></span></span>
                    <span class="text-2xl font-bold mr-4">Ranking: #'.$post['rank'].'</span>
                </div>';
                break;
            case 'audio':
                echo'
 <div class="flex items-center justify-between py-4 pl-8 bg-base-200 rounded-t-lg border-b-4 border-b-orange-500 my-auto">
                    <a class="hover:scale-105" href="../perfil-de-outro/'.$creator['id'].'"><div class="flex items-center">
                    <img src="'.$avatarURL.'" alt="Avatar" class="rounded-full h-10 w-10 bg-red-800">
                        <span class="text-xl font-bold ml-4">@'.$creator['name'].'</span>
                    </div>
                    </a>
                    <button onclick="openReport(\'' . $post['post_id'] . '\',\''  . $post['caption'] . '\')" class="bg-gray-800 hover:bg-orange-500 text-white font-bold py-2 px-4 rounded mr-4">Reportar</button>
                    </div>
            <div class="flex flex-col justify-center items-center bg-base-200 h-full">
                <img src="'. $arrConfig['url_assets']. 'images/audio.jpg" alt="Audio Image" class="rounded-lg w-full h-auto block mx-auto object-contain max-h-[60vh]">
                <audio controls class="rounded-sm w-full h-auto mt-4 object-contain">
                    <source src="'. $arrConfig['url_posts']. $post['post_type'].'/'.$post['post_url'].'" type="audio/mpeg">
                    Your browser does not support the audio tag.
                </audio>
            </div>
            <div class="flex items-center justify-between py-4 bg-base-200 rounded-b-lg border-t-4 border-t-orange-500 my-auto">
                <textarea class="text-2xl font-bold ml-4" readonly>'.$post['caption'].'</textarea>';
                if ($post['enabled'] == 0) {
                    echo '<button id="like-button-' . $post['post_id'] . '" class="bg-orange-500 hover:bg-orange-700 text-white font-bold py-2 px-4 rounded" onclick="likeCheck(' . $post['post_id'] . ')">Like</button>';
                } else {
                    echo '<button id="like-button' . $post['post_id'] . '" class="btn btn-ghost w-1/4 text-white font-bold py-2 px-4 rounded" disabled>Desativado</button>';
                }
                echo'
                 <span class="text-2xl font-bold" id="like-count-'.$post['post_id'].'">Gostos: <span class="loading loading-ring loading-lg text-warning"></span></span>
                <span class="text-2xl font-bold mr-4">Ranking: #'.$post['rank'].'</span>
            </div>';
                break;
            case 'video':
                echo'
                    <div class="flex items-center justify-between py-4 pl-8 bg-base-200 rounded-t-lg border-b-4 border-b-orange-500 my-auto">
                    <a class="hover:scale-105" href="../perfil-de-outro/'.$creator['id'].'"><div class="flex items-center">
                    <img src="'.$avatarURL.'" alt="Avatar" class="rounded-full h-10 w-10 bg-red-800">
                        <span class="text-xl font-bold ml-4">@'.$creator['name'].'</span>
                    </div>
                    </a>
                     <button onclick="openReport(\'' . $post['post_id'] . '\',\''  . $post['caption'] . '\')" class="bg-gray-800 hover:bg-orange-500 text-white font-bold py-2 px-4 rounded mr-4">Reportar</button>
                    </div>
                <div class="flex grow-0">
                    <video controls class="rounded-sm w-full h-auto mt-4 mr-10 lg:ml-3/5 sm:mr-8 lg:mr-3/5 object-contain">
                        <source src="'. $arrConfig['url_posts']. $post['post_type'].'/'.$post['post_url'].'" type="video/mp4" class="rounded-sm w-full h-auto mt-2 mb-10 block mx-auto object-contain max-w-[60vh]">
                        O seu browse não suporta a tag de vídeo.
                    </video>
                </div>
                <div class="flex items-center justify-between py-4 bg-base-200 rounded-b-lg border-t-4 border-t-orange-500">
                    <textarea class="text-2xl font-bold ml-4" readonly>'.$post['caption'].'</textarea>';
                if ($post['enabled'] == 0) {
                    echo '<button id="like-button-' . $post['post_id'] . '" class="bg-orange-500 hover:bg-orange-700 text-white font-bold py-2 px-4 rounded" onclick="likeCheck(' . $post['post_id'] . ')">Like</button>';
                } else {
                    echo '<button id="like-button' . $post['post_id'] . '" class="btn btn-ghost w-1/4 text-white font-bold py-2 px-4 rounded" disabled>Desativado</button>';
                }
                echo'
                 <span class="text-2xl font-bold" id="like-count-'.$post['post_id'].'">Gostos: <span class="loading loading-ring loading-lg text-warning"></span></span>
                <span class="text-2xl font-bold mr-4">Ranking: #'.$post['rank'].'</span>
            </div>';
                break;
        }
        echo'</div>';
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
        echo '<a href="/perfil-de-outro/' . $userId. '" class="w-3/4 md:w-full text-orange-500 hover:text-orange-800 transition-all duration-200">';
        echo '<div class="flex justify-between ubuntu-medium items-center text-white bg-gray-800 hover:border-orange-500 hover: border-4 p-2 rounded-lg mb-3">';
        echo $username;
        echo '<img src="' . $profilePic . '" alt="Profile Picture" class="w-12 h-12 m-2 rounded-full">';
        echo '</div>';
        echo '</a>';
    }

    function echoConvo($profilePic, $name, $personId, $lastMessage){
        global $arrConfig;
        $profilePicUrl = empty($profilePic) ? $arrConfig['url_assets']."images/Unknown_person.jpg" : $arrConfig['url_users'].$profilePic;
    
        echo '<a href="../mensagens/'.$personId.'" class="text-orange-500 mb-8 transform hover:scale-110 transition-all duration-200 w-80">';
        echo '<div class="flex justify-between items-center text-white bg-gray-800 p-2 rounded-lg m-2 transform hover:scale-105 transition-transform duration-200 relative">';
        echo '<div class="flex items-center">';
        echo '<img src="'.$profilePicUrl.'" class="w-12 h-12 rounded-full mr-4">'; // Circle for the profile picture
        echo '<div>';
        echo '<div class="font-bold">'.$name.'</div>'; // Name
        echo '<div class="text-sm leading-relaxed">'.$lastMessage.'</div>'; // Last message
        echo '</div>';
        echo '</div>';
        echo '<div class="absolute bottom-0 left-0 w-full h-1 bg-gradient-to-r from-purple-500 to-orange-500 rounded-b-lg"></div>'; // Gradient border
        echo '</div>';
        echo '</a>';
    }


    function echoNotif($row){
        $date = $row['date_sent'];
        $message = $row['message'];
        $notificationId = $row['id'];
        echo '<a href="#" class="text-orange-500 transform hover:scale-110 transition-all duration-200 mb-4 notification-message group">';
        echo '<div class="flex justify-between items-center text-white bg-gray-800 p-2 rounded-lg m-2 transform hover:scale-105 transition-transform duration-200 relative">';
        echo '<div class="flex items-center">';
        echo '<div class=" h-1 mr-4"></div>'; // Circle for the profile picture
        echo '<div>';
        echo "<div class='font-bold text-xl'>$message</div>"; // Date of notif
        echo "<div class='text-sm leading-relaxed'>$date</div>"; // Notification message
        echo '</div>';
        echo '</div>';
        echo '<button class="delete-button opacity-0 group-hover:opacity-100 transition-opacity duration-200 bg-red-400 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="deleteNotifications(' . $notificationId . ')">&#10005;</button>'; // Delete button
        echo '<div class="absolute bottom-0 left-0 w-full h-1 bg-gradient-to-r from-purple-500 to-orange-500 rounded-b-lg"></div>'; // Gradient border
        echo '</div>';
        echo '</a>';
    }

    function echoMessages($messageID, $message, $sender, $messenger){
        global $arrConfig;
    
        if ($messenger == $_SESSION['uid']) {
            // Current user is the sender
            echo '
            <div class="chat chat-end relative group">
                <div class="chat-image avatar">
                    <div class="w-10 rounded-full">
                        <img alt="Tailwind CSS chat bubble component" src="'.$_SESSION['imageProfile'].'" />
                    </div>
                </div>
                <div class="chat-header text-white">
                    '.$_SESSION['username'].'
                </div>
                <div class="chat-bubble">'.$message.'</div>
                <button class="delete-button w-24top-0 left-0 bg-red-500 text-white px-4 py-2 rounded opacity-0 group-hover:opacity-100" onclick="deleteMessage('.$messageID.')">
                    x
                </button>
            </div>';
        } else {
            // Current user is the receiver
            // Check if sender's profile pic is empty or NULL
            $senderProfilePic = empty($sender['profile_pic']) ? "{$arrConfig['url_assets']}/images/Unknown_person.jpg" : "{$arrConfig['url_users']}{$sender['profile_pic']}";
            echo '
            <div class="chat chat-start">
                <div class="chat-image avatar">
                    <div class="w-10 rounded-full">
                        <img alt="Tailwind CSS chat bubble component" src="'.$senderProfilePic.'" />
                    </div>
                </div>
                <div class="chat-header text-white">
                    @'.$sender['username'].'
                </div>
                <div class="chat-bubble">'.$message.'</div>
            </div>';
        }
    }


    function echoRankPosts($rank, $image, $name, $type, $likes, $poster, $id){
        global $arrConfig;  
        // Pad rank and likes with a leading zero if they are less than 10
        $formattedRank = str_pad($rank, 2, "0", STR_PAD_LEFT);
        $formattedLikes = str_pad($likes, 2, "0", STR_PAD_LEFT);
    
        echo '<tr class="text-white hover:bg-gray-700"  data-id="'.$id.'" onclick="redirectToPost(this)">';
        echo '<th class="w-1/6">#'.$formattedRank.'</th>';
        echo '<td class="w-1/6">';
        if ($type == 'video') {
            echo '<div style="display: flex; justify-content: center; align-items: center; height: 100%; width: 100%;">';
            echo '<video width="100%" height="100%" style="object-fit: fill; margin: auto;" class="shadow-md shadow-black hover:filter hover:brightness-20 hover:opacity-75">';
            echo '<source src="'. $arrConfig['url_posts'].'/'.$type.'/'.$image.'" type="video/mp4">';
            echo 'Your browser does not support the video tag.';
            echo '</video>';
            echo '</div>';
        } else if ($type == 'audio') {
            echo '<img src="'. $arrConfig['url_assets'].'images/audio.jpg" alt="Audio Image" class="w-32 h-32 text-white">';
        } else {
            echo '<img src="'. $arrConfig['url_posts'].'/'.$type.'/'.$image.'" alt="Post Image" class="w-32 h-32 text-white">';
        }
        echo '</td>';
        echo '<td class="w-1/6">'.$name.'</td>';
        echo '<td class="w-1/6">'.$type.'</td>';
        echo '<td class="w-1/6">'.$formattedLikes.'</td>';
        echo '<td class="w-1/6">@'.$poster.'</td>';
        echo '</tr>';
    }
    
    function echoRankAcc($rank, $likes, $poster, $url_image, $id){
        global $arrConfig;  
        // Pad rank and likes with a leading zero if they are less than 10
        $formattedRank = str_pad($rank, 2, "0", STR_PAD_LEFT);
        $formattedLikes = str_pad($likes, 2, "0", STR_PAD_LEFT);
    
        echo '
        <tr class="hover:bg-gray-700 transition-colors duration-200" data-id="'.$id.'" onclick="redirectToPerson(this)">
            <th class="text-white">#'.$formattedRank.'</th> <!-- Corrected closing tag -->
            <td class="text-white">'.$formattedLikes.'</td>
            <td class="flex flex-row items-center justify-center pr-16 text-white">
                @'.$poster;
            
                if($url_image != null && $url_image != ""){
                    echo'
                    <div class="avatar ml-4">
                        <div class="w-20">
                            <img class="rounded-full w-10" src="'. $arrConfig['url_users'].''.$url_image.'" alt="Profile Picture">
                        </div>
                    </div>';
                }
                else{
                    echo'
                    <div class="avatar ml-4">
                        <div class="w-20">
                            <img class="rounded-full w-10" src="'. $arrConfig['url_assets'].'images/Unknown_person.jpg" alt="Profile Picture">
                        </div>
                    </div>';
                }
               
        echo'
            </td>
        </tr>
        ';
    }

