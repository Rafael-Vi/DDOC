<?php
  include "include/config.inc.php";
  include "include/functions/checkLogin.inc.php";
  require "include/functions/checkThemeIsFinished.inc.php";
  if (checkThemeIsFinished()){
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
  <link rel="shortcut icon" href="/src/assets/images/2.png" >
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
  <title>Publicar Post</title>
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
  <div id="createPost-div" class="bg-gray-800 fixed flex flex-col h-full w-full md:w-9/12 p-0 m-0 md:right-0">
  <div class="h-32 text-center sm:text-start w-full p-10 font-bold text-4xl text-white sticky top-0 flex items-center justify-left gap-8 shadow-mdshadow-md">
<a href="javascript:history.back()" class="btn">Voltar atrás</a>
    Publicar Post
  </div>

  <?php
  global $arrConfig;
  if ($_SESSION['can_post'] == 0 || $_SESSION['can_post'] == "0") {
  ?>
<form action="../src/include/functions/validadeCreatePost.inc.php" class="flex flex-col items-center h-full m-20 overflow-auto" method="Post" enctype="multipart/form-data" style="padding-left: 20%; padding-right: 20%;">

<div class="flex justify-center w-full sm:w-3/4">
  <img src="<?php echo $arrConfig['url_assets'] . 'images/something.png'?>" alt="Thumbnail" class="rounded-sm mb-8 w-auto h-48 mx-auto object-contain max-w-[40vh]" id="profile-picture">
</div>
<label for="post-title" class="text-white font-bold text-2xl pt-4 text-left w-full">Legenda:</label>
<textarea id="post-title" name="post-title" required class="rounded-lg px-4 py-2 bg-base-200  w-full" maxlength="255"></textarea><br>
<div class="flex flex-row justify-start items-center gap-4 mt-4 mb-4 w-full"> <!-- Ensure this is flex-row and items are centered -->
  <div class="flex flex-col w-1/2"> <!-- This is already set to take half width -->
    <label for="post-type" class="text-white font-bold text-left">Tipo:</label>
    <select id="post-type" name="post-type" required class="rounded-lg px-4 py-2 bg-base-200 m-auto  w-full">
      <option value="audio">Audio</option>
      <option value="image" selected>Imagem</option>
      <option value="video">Vídeo</option>
    </select>
  </div>
  <div class="flex flex-col w-1/2"> <!-- This is also set to take half width, ensuring side by side layout -->
    <label for="file-input" class="text-white font-bold w-full text-left mt-8">Upload de Ficheiro (Musica/Imagem/Vídeo):</label>
    <input type="file" id="file-input" name="file-input" class="file-input file-input-warning max-w-xs w-full">
  </div>
</div>
<input type="text" id="post-theme" name="post-theme" disabled class="rounded-lg bg-base-200 p-2 w-full" value="Tema: <?php echo $_SESSION['themes'][0]['theme']; ?>">
<button type="submit" name="CreatePost" class="btn btn-warning font-bold py-2 px-4 rounded mt-8 mb-8 w-full hover:text-orange-500">Publicar Post</button>
</form>
  <?php
  } else {
    echo'<div class="flex flex-col  items-center justify-center h-full">
    <h2 class="text-xl sm:text-3xl font-bold bg-base-200 rounded-lg p-8 gap-3">
    Já postaste neste tema.
    <a href="/perfil" class="hover:bg-white bg-orange-500 hover:text-bl-800 text-white font-bold py-2 px-4 rounded mt-4">Vai para o teu perfil</a>
    </h2>
    </div>';
  }
  ?>
  </div>
  <?php echoBottomNav(); ?>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script>
  var targetDateFromPHP = <?php echo json_encode($_SESSION['themes'][0]['finish_date']); ?>;
</script>
  <script src="../src/js/timer.js"></script>
  <script>
  document.getElementById('file-input').addEventListener('change', function(e) {
  var file = e.target.files[0];
  var reader = new FileReader();
  reader.onloadend = function() {
    // Get the existing profile picture
    var profilePicture = document.getElementById('profile-picture');

    // Create a new element based on the file type
    var newElement;
    switch (true) {
      case file.type.startsWith('video/'):
        newElement = document.createElement('video');
        newElement.controls = true;
        break;
      case file.type.startsWith('image/'):
        newElement = document.createElement('img');
        break;
      case file.type.startsWith('audio/'):
        newElement = document.createElement('audio');
        newElement.controls = true;
        break;
      default:
        console.error('Unsupported file type: ' + file.type);
        return;
    }

    // Set the source and classes of the new element
    newElement.src = reader.result;
    newElement.className = profilePicture.className;
    newElement.id = profilePicture.id;

    // Replace the existing profile picture with the new element
    profilePicture.parentNode.replaceChild(newElement, profilePicture);
  }
  reader.readAsDataURL(file);
  });
</script>
  <script src="../src/js/social.js"></script>
  <script src="../src/js/createPost.js"></script>
  
</body>
</html>