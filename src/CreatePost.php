<?php
include "include/config.inc.php";
?>

<?php
  include "include/functions/checkLogin.inc.php";
?> 
<?php
      require "include/functions/checkThemeIsFinished.inc.php";
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../src/css/social.css">
    <link rel="shortcut icon" href="./assets/images/2.png" >
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/2.2.0/uicons-solid-rounded/css/uicons-solid-rounded.css'>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.7.2/dist/full.min.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Publicar Post</title>
</head>
<body class="h-full flex">

    <?php echoLoadScreen(); ?>  

    <?php echoNav(); ?>
    <div id="createPost-div" class="bg-gray-900 fixed flex flex-col h-full w-full md:w-9/12 p-0 m-0 md:right-0">
    <h1 class="h-32 border text-center sm:text-start border-black w-full p-10 font-bold text-4xl shadow-md shadow-amber-600 bg-gray-800 sticky top-0">
      Publicar Post
    </h1>

    <?php
    if ($_SESSION['can_post'] == 0) {
    ?>
    <form action="include/functions/validadeCreatePost.inc.php" class="flex flex-col items-center sm:items-start h-full ml-10" method="Post" enctype="multipart/form-data">

        <div class="flex flex-col items-center">
            <img src="https://th.bing.com/th/id/R.3e77a1db6bb25f0feb27c95e05a7bc57?rik=DswMYVRRQEHbjQ&riu=http%3a%2f%2fwww.coalitionrc.com%2fwp-content%2fuploads%2f2017%2f01%2fplaceholder.jpg&ehk=AbGRPPcgHhziWn1sygs8UIL6XIb1HLfHjgPyljdQrDY%3d&risl=&pid=ImgRaw&r=00" alt="Thumbnail" class="rounded-sm w-auto h-82 lg:h-72 mt-4 mr-10 lg:ml-3/5 sm:mr-8 lg:mr-3/5 object-contain max-w-[30vh]" id="profile-picture">
        </div>
       <label for="post-title" class="text-white font-bold text-2xl pt-4">Título:</label>
        <input type="text" id="post-title" name="post-title" required class="rounded-lg px-4 py-2 bg-gray-800 text-white"><br>
        <label for="post-type" class="text-white font-bold">Tipo:</label>
        <select id="post-type" name="post-type" required class="rounded-lg px-4 py-2 bg-gray-800 text-white mb-4">
            <option value="audio">Audio</option>
            <option value="image" selected>Imagem</option>
            <option value="video">Vídeo</option>
        </select><br>
      <label for="file-upload" class="text-white font-bold">Upload de Ficheiro (Musica/Imagem/Vídeo):</label>
      <input type="file" id="file-input" name="file-input" class="rounded-lg bg-gray-800 text-white mb-4"><br>
      <input type="text" id="post-theme" name="post-theme" disabled class="rounded-lg bg-gray-800 text-white p-2" value="Tema: <?php echo $_SESSION['themes'][0]['theme']; ?>">
      <button type="submit" name="CreatePost" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded mt-8">Publicar Post</button>
    </form>
    <?php
    } else {
      echo'<div class="flex flex-col  items-center justify-center h-full">
      <h2 class="text-3xl font-bold bg-gray-800 rounded-lg p-8 text-white gap-3">
      You have already posted in this theme.
      <a href="./profile.php" class="hover:bg-white bg-orange-500 hover:text-gray-800 text-white font-bold py-2 px-4 rounded mt-4">Go to your profile</a>
      </h2>
      </div>';
    }
    ?>
  </div>
  <?php echoBottomNav(); ?>
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