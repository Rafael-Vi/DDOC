<?php
include "include/config.inc.php";
include "include/functions/checkLogin.inc.php";
require "include/functions/checkThemeIsFinished.inc.php";

if (checkThemeIsFinished()) {
  include "include/functions/saveLastPage.inc.php";
}

require "include/functions/Development.inc.php";

if (basename($_SERVER['PHP_SELF']) === 'profile.php') {
  $userInfo = getUserInfo($_SESSION['uid']);
} elseif (basename($_SERVER['PHP_SELF']) === 'OProfile.php') {
  $userInfo = getUserNotCurrent($_GET['userid']);
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../src/css/social.css">
 <link rel="shortcut icon" href="/src/assets/images/2.png">
  <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/2.2.0/uicons-solid-rounded/css/uicons-solid-rounded.css'>
   <link href="/dist/tailwind.css" rel="stylesheet" type="text/css" />
  <style>
    .error-container {
      position: fixed;
      top: 0;
      left: 50%;
      transform: translateX(-50%);
      width: 100%;
      min-height: 60px;
      text-align: center;
      z-index: 49; /* Ensure it's above other content */
    }
  </style>
  <title>Perfil</title>
</head>

<body class="h-full flex">
  <?php echoLoadScreen(); ?>
  <?php echoNav(); ?>
   <?php
 
  if(isset($_SESSION['error'])) {
    echo'  <div class="error-container">';
      echoError($_SESSION['error']);
      unset($_SESSION['error']);
    echo'</div>';
  } elseif(isset($_SESSION['success'])) {
      if ($_SESSION['success'] == 'Registration successful') {
        echo'  <div class="error-container">';
          validRegisterAl();
          echo'</div>';
          
      } else {
        echo'  <div class="error-container">';
          echoSuccess($_SESSION['success']);
          echo'</div>';
      }
      unset($_SESSION['success']);
  }

  ?>
  <div id="profile-div" class="fixed flex flex-col h-full w-full md:w-9/12 p-0 m-0 bg-gray-900 md:right-0">
    <div id="profileInfo-div" class="b-8 z-20 relative w-full flex flex-col justify-between h-auto md:h-80 pl-4 pr-4 pb-4">
      <a href="javascript:history.back()" class="btn fixed mt-8 ">Voltar atrás</a>
      <div class="flex flex-row justify-end lg:h-64 mt-8 w-full">
        <div class="w-full lg:w-auto">
          <?php
          echoProfileInfo($userInfo['username'], $userInfo['email'], $userInfo['profilePic'], $userInfo['realName'], $userInfo['biography'], $userInfo['rank']);
          unset($userInfo);
          echo '<button onclick="openDialog()" class="btn bg-gray-700 hover:bg-orange-500 text-white font-bold py-2 px-4 rounded-lg">Editar Perfil</button>';
          echo '</div>';
          echo '</div>'; // Close profile-info-container
          ?>
        </div>
    </div>
    <div id="profilePosts-div" class="relative p-auto overflow-y-auto bg-base-200 min-h-screen mb-8 mt-8">
      <?php
      getPosts($_SESSION['uid']);
      ?>
    </div>
  </div>
  </div>

  <dialog id="profile-dialog" class="modal">
    <form class="bg-white p-8 rounded shadow-lg" action="/src/include/functions/validateUpdateUser.inc.php" method="post" enctype="multipart/form-data">
      <h2 class="text-2xl font-extrabold mb-4 text-gray-800">Editar Perfil</h2>
      <div class="flex flex-col mb-4">
        <label class="text-lg mb-2 text-gray-800">Nome de Utilizador:</label>
        <input type="text" name="username" class="border border-gray-300 p-2 rounded text-gray-800 bg-white" minlength="4" maxlength="15" />
        <span class="invalid-feedback text-red-800">O nome de utilizador deve ter entre 4 e 15 caracteres.</span>
      </div>

      <div class="flex flex-col mb-4">
        <label class="text-lg mb-2 text-gray-800">Nome Real:</label>
        <input type="text" name="realName" class="border border-gray-300 p-2 rounded text-gray-800 bg-white" maxlength="30" />
        <span class="invalid-feedback text-red-800">O nome real não pode exceder os 30 caracteres.</span>
      </div>

      <div class="flex flex-col mb-4">
        <label class="text-lg mb-2 text-gray-800 bg-white">Biografia:</label>
        <textarea name="biography" class="border border-gray-300 p-2 rounded text-gray-800 bg-white" rows="6" maxlength="255"></textarea>
        <span class="invalid-feedback text-red-800">A biografia não pode exceder os 255 caracteres.</span>
      </div>
      <div class="flex flex-col mb-4">
        <label class="text-lg mb-2 text-gray-800">Foto de Perfil:</label>
        <input type="file" name="profilePic" class="border border-gray-300 p-2 rounded text-gray-800 bg-white" accept="image/jpeg,image/jpg,image/png,image/gif" />
      </div>
      <div class="flex justify-center">
        <button type="button" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="closeDialog()">Fechar</button>
        <button type="submit" name="confirm" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded ml-4">Atualizar</button>
      </div>
    </form>
  </dialog>

  <dialog id="postEdit" class="modal">
    <div class="modal-box">
      <form method="dialog">
        <input type="hidden" id="postId" name="postId">
        <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2 text-gray-900" onclick="cancel()">✕</button>
        <h3 class="font-bold text-lg">Editar Post</h3>
        <div class="form-control">
          <label class="label">
            <span id="caption" class="label-text">legenda</span>
          </label>
          <textarea id="postContent" class="textarea textarea-bordered w-full" placeholder="Type your post content here..."></textarea>
        </div>
        <div class="form-control mt-4 flex flex-row">
          <button class="btn flex-initial" onclick="save()">Salvar Alterações</button>
          <button class="btn btn-error ml-2 flex-initial" onclick="deletePost()">Delete de Posts</button>
          <button class="btn btn-outline ml-2 flex-initial" onclick="cancel()">Cancelar</button>
        </div>
      </form>
    </div>
  </dialog>
  <dialog id="postFollower" class="w-11/12 md:w-1/2 p-5  bg-white rounded-md">
    <div class="flex flex-col w-full h-auto">
      <!-- Header Section -->
      <div class="flex w-full h-auto justify-between items-center">
        <div class="flex w-10/12 h-auto justify-start items-center">
          <h1 class="text-gray-700 font-bold text-lg">Seguidores</h1>
        </div>
        <div onclick="closeFollow('follower')" class="flex w-8 h-8 justify-center items-center bg-gray-900 rounded-full text-gray-50">
          <button onclick="closeFollow('follower')">X</button>
        </div>
      </div>
      <!-- Body Section -->
      <div class="flex-1">
        <div class="flex flex-col items-center mt-4 mx-4 h-5/6 relative p-2 rounded-md overflow-auto" id="followers-people">
          <?php
          getFollowers($_SESSION['uid']);
          ?>
        </div>
      </div>
    </div>
  </dialog>
  <dialog id="postFollowing" class="w-11/12 md:w-1/2 p-5  bg-white rounded-md">
    <div class="flex flex-col w-full h-auto">
      <!-- Header Section -->
      <div class="flex w-full h-auto justify-between items-center">
        <div class="flex w-10/12 h-auto justify-start items-center">
          <h1 class="text-gray-700 font-bold text-lg">A seguir</h1>
        </div>
        <div onclick="closeFollow('following')" class="flex w-8 h-8 justify-center items-center bg-gray-900 rounded-full text-gray-50">
          <button onclick="closeFollow('following')">X</button>
        </div>
      </div>
      <!-- Body Section -->
      <div class="flex-1">
        <div class="flex flex-col items-center mt-4 mx-4 h-5/6 relative p-2 rounded-md overflow-auto" id="followers-people">
          <?php
          getFollowing($_SESSION['uid']);
          ?>
        </div>
      </div>
    </div>
  </dialog>
  <?php echoBottomNav(); ?>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script>
    var targetDateFromPHP = <?php echo json_encode($_SESSION['themes'][0]['finish_date']); ?>;
  </script>
  <script src="../src/js/checkFollowList.js"></script>
  <script src="../src/js/timer.js"></script>
  <script src="../src/js/social.js"></script>
  <script src="../src/js/follow.js"></script>
  <script src="../src/js/EditProfile.js"></script>
  <script src="../src/js/editPost.js"></script>
</body>

</html>