<?php
  require "include/config.inc.php";
?>

<?php
  require "include/functions/checkLogin.inc.php";
?> 

<?php
  require "include/functions/checkThemeIsFinished.inc.php";
?>


<?php
    include "include/functions/saveLastPage.inc.php";
?>
<?php 
    $post = showPost($_GET['id'], "no");
    if ($post === false) {
        header("Location: ./errorPages/noPostFound.php");
        exit();
    }
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../src/css/social.css">    <link rel="shortcut icon" href="./assets/images/2.png" >
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/2.2.0/uicons-solid-rounded/css/uicons-solid-rounded.css'>
    <script src="https://cdn.tailwindcss.com"></script>     <link href="https://cdn.jsdelivr.net/npm/daisyui@4.7.2/dist/full.min.css" rel="stylesheet" type="text/css" />
    <title><?php echo $post['caption']?></title>
</head>
<body class="h-full flex">
    <?php echoLoadScreen(); ?>   
    <?php echoNav(); ?>

    <div id="ThisPost-div" class="bg-gray-900 fixed flex flex-col h-full w-full md:w-9/12 p-0 m-0 md:right-0">
        
        <h1 class=" h-32 text-white border text-center sm:text-start border-black w-full p-10 font-bold text-4xl shadow-md  shadow-amber-600 bg-gray-800">
          Theme: <?php echo $post['theme_name'] ?>
        </h1>

              <?php 
                showPost($_GET['id'], "yes");
              ?>
    </div>
  
  <?php echoBottomNav(); ?>
<script>
  var targetDateFromPHP = <?php echo json_encode($_SESSION['themes'][0]['finish_date']); ?>;
</script>
  <script src="../src/js/timer.js"></script>
  <script src="../src/js/social.js"></script>
  <script src="../src/js/like.js"></script>
</body>
</html>