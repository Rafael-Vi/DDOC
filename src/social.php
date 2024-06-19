<?php
include "include/config.inc.php";
include "include/functions/checkLogin.inc.php";
require "include/functions/checkThemeIsFinished.inc.php";
if (checkThemeIsFinished()) {
  include "include/functions/saveLastPage.inc.php";
}
require "include/functions/Development.inc.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../src/css/social.css">
  <link rel="shortcut icon" href="./assets/images/2.png">
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
      z-index: 49;
      /* Ensure it's above other content */
    }
  </style>
  <title>DDOC</title>
</head>

<body class="h-full flex">
  <?php echoLoadScreen(); ?>
  <?php echoNav(); ?>
  <?php
  if (isset($_SESSION['error'])) {
    echo '<div class="error-container">';
    echoError($_SESSION['error']);
    unset($_SESSION['error']);
    echo '</div>';
  } elseif (isset($_SESSION['success'])) {
    if ($_SESSION['success'] == 'Registration successful') {
      echo '<div class="error-container">';
      validRegisterAl();
      echo '</div>';
    } else {
      echo '<div class="error-container">';
      echoSuccess($_SESSION['success']);
      echo '</div>';
    }
    unset($_SESSION['success']);
  }
  ?>
  <div class="bg-gray-900 fixed w-full md:w-9/12 p-0 m-0 md:right-0 h-full flex flex-col justify-center items-center" id="home-div"></div>
    <div class="h-32 text-center sm:text-start w-full p-10 font-bold text-4xl text-white sticky top-0 flex items-center justify-left gap-8 shadow-mdbackdrop-blur-md">
      <a href="javascript:history.back()" class="btn">Voltar atrás</a>
      Home
    </div>
    <div class="h-full border-black w-full px-10 overflow-auto">
      <?php
      getHome();
      ?>
    </div>
    <dialog id="postReport" class="modal">
      <div class="modal-box">
        <form method="dialog">
          <input type="hidden" id="postId" name="postId">
          <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2 text-gray-900" onclick="cancel()">✕</button>
          <h3 class="font-bold text-lg">Report</h3>
          <input type="text" class="hidden" disabled val="" id="reportId">
          <div class="form-control">
            <label class="label"></label>
              <span id="Post-Name" class="label-text">Porque quer reportar: Nome do Post</span>
            </label>
            <input type="text" value="" id="post-reason" class="input input-bordered w-full text-gray-900" placeholder="Insira o motivo de report">
          </div>
          <div class="form-control">
            <label class="label">
              <span class="label-text">Tipo</span>
            </label>
            <select id="postType" class="select select-bordered w-full text-gray-900">
              <option disabled selected>Escolha o tipo</option>
              <option value="type1">Conteúdo Inapropriado</option>
              <option value="type2">Não tem a ver com o tema</option>
              <option value="type3">Plágio</option>
            </select>
          </div>
          <div class="form-control mt-4 flex flex-row"></div>
            <button class="btn flex-initial btn-warning" onclick="saveReport()">Report</button>
            <button class="btn btn-outline ml-2 flex-initial btn-error" onclick="cancelReport()">Cancelar</button>
          </div>
        </form>
      </div>
    </dialog>
  </div>
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
