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
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.7.2/dist/full.min.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Create Post</title>
</head>
<body class="h-full flex">

    <?php echoLoadScreen(); ?>  

    <?php echoNav(); ?>
    <div id="createPost-div" class="bg-gray-900 fixed flex flex-col h-full w-full md:w-9/12 p-0 m-0 md:right-0">
    <h1 class="h-32 border text-center sm:text-start border-black w-full p-10 font-bold text-4xl shadow-md shadow-amber-600 bg-gray-800 sticky top-0">
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
      <input type="text" id="post-theme" name="post-theme" disabled class="rounded-lg bg-gray-800 text-white">Theme: <?php echo $_SESSION['themes'][0]['theme']; ?><br>
      <button type="submit" name="CreatePost" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded mt-8">Create Post</button>
    </form>
  </div>
  <?php echoBottomNav(); ?>
<script>
  var targetDateFromPHP = <?php echo json_encode($_SESSION['themes'][0]['finish_date']); ?>;
</script>
  <script src="../src/js/timer.js"></script>

  <script src="../src/js/social.js"></script>
  <script src="../src/js/createPost.js"></script>
  
</body>
</html>