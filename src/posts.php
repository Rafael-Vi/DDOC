<?php
  require "include/config.inc.php";

  require "include/functions/checkLogin.inc.php";

  require "include/functions/checkThemeIsFinished.inc.php";

     if (checkThemeIsFinished()){
    include "include/functions/saveLastPage.inc.php";
  }

    $post = showPost($_GET['id'], "no");
    if ($post === false) {
        header("Location: /erro/sem-Post");
        exit();
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
    <link rel="stylesheet" href="../src/css/social.css">    <link rel="shortcut icon" href="/src/assets/images/2.png" >
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
    <title><?php echo $post['caption']?></title>
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

    <div id="ThisPost-div" class="bg-gray-900 fixed flex flex-col h-full w-full md:w-9/12 p-0 m-0 md:right-0">
    <div class="h-32 text-center sm:text-start w-full p-10 font-bold text-4xl text-white sticky top-0 flex items-center justify-left gap-8 shadow-mdbackdrop-blur-md">
<a href="javascript:history.back()" class="btn">Voltar atrás</a>
      Tema: <?php echo $post['theme_name'] ?>
    </div>
              <?php 
                showPost($_GET['id'], "yes");
              ?>
    </div>
    <dialog id="postReport" class="modal bg-base-100">
  <div class="modal-box">
  <form method="dialog">
      <input type="hidden" id="postId" name="postId">
      <button type="button" class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2 " onclick="cancel()">✕</button>
      <h3 class="font-bold text-lg ">Reportar</h3>
      <input type="text" class="hidden" disabled val="" id="reportId">
      <div class="form-control mt-4">
        <label class="label">
          <span id="Post-Name" class="label-text ">Porque quer reportar: Nome do Post</span>
        </label>
        <input type="text" value="" id="post-reason" class="input input-bordered w-full  bg-base-200" placeholder="Insira o motivo de report" required>
      </div>
      <div class="form-control mt-4">
        <label class="label">
          <span class="label-text ">Tipo</span>
        </label>
        <select id="postType" class="select select-bordered w-full  bg-base-200" required>
          <option disabled selected>Escolha o tipo</option>
          <option value="type1">Conteúdo Inapropriado</option>
          <option value="type2">Não tem a ver com o tema</option>
          <option value="type3">Plágio</option>
        </select>
      </div>
      <div class="form-control mt-4 flex flex-row gap-2">
        <button type="button" class="btn flex-initial text-orange-500" onclick="saveReport()">Reportar</button>
        <button type="button" class="btn btn-outline ml-2 flex-initial btn-error text-red-500" onclick="cancelReport()">Cancelar</button>
      </div>
</form>
  </div>
</dialog>
  <?php echoBottomNav(); ?>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script>
  var targetDateFromPHP = <?php echo json_encode($_SESSION['themes'][0]['finish_date']); ?>;
</script>
  <script src="../src/js/timer.js"></script>
  <script src="../src/js/social.js"></script>
  <script src="../src/js/like.js"></script>
</body>
</html>