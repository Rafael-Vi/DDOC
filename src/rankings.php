<?php
include "include/config.inc.php";

include "include/functions/checkLogin.inc.php";

require "include/functions/checkThemeIsFinished.inc.php";

if (checkThemeIsFinished()) {
  include "include/functions/saveLastPage.inc.php";
  require "include/functions/Development.inc.php";
}

require "include/functions/checkFilterVars.inc.php";

// Start the session if it's not already started
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}

if (basename($_SERVER['PHP_SELF']) === 'profile.php') {
  $userInfo = getUserInfo($_SESSION['uid']);
} elseif (basename($_SERVER['PHP_SELF']) === 'OProfile.php') {
  $userInfo = getUserNotCurrent($_GET['userid']);
}

// Call the checkTypeVarAdmin function
$type = checkTypeVar();

// If the return value is null, redirect and exit
if ($type == null || $type == 0) {
  $type = "none";
}

// Set the global type variable
$GLOBALS['type'] = $type;
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
   <link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.tailwindcss.css">
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

  .dataTables_wrapper .dataTables_paginate .paginate_button.current,
      .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
        background-color: #ee7000;
        border-color: #007bff;
        color: #fff;
      }

      .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
        background-color: #f8f9fa;
        border-color: #dee2e6;
        color: #6c757d;
      }

      .dataTables_wrapper .dataTables_paginate {
        margin-top: 25px;
        position: absolute;
        left: 0;
        margin-left: 30px;  
        z-index: 1;
      }

      .paginate_button {
        background-color: #fff;
        border: 1px solid #dee2e6;
        color: #6c757d;
        margin: 0 5px;
        padding: 5px 10px;
        cursor: pointer;
      }
    </style>
  <title>Ranking de Contas</title>
  <style>
    #podium-container * {
      z-index: 1;
    }
  </style>
</head>

<body class="h-full flex">
  <?php echoLoadScreen(); ?>
  <?php
  //echoShowTheme();
  ?>
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
  <div id="Accrankings-div" class=" bg-gray-900 fixed flex flex-col h-full w-full md:w-9/12 p-0 m-0 md:right-0 overflow-auto">
    <div class=" z-8 h-32 text-center sm:text-start w-full  bg-gray-800 p-10 font-bold text-4xl text-white sticky top-0 flex items-center justify-left gap-8 backdrop-blur-md">
      <a href="javascript:history.back()" class="btn">Voltar atrás</a>
      Ranking de Contas
    </div>


    <div class="flex flex-col h-full w-full pt-6 pb-8 px-10">
      <div class="flex flex-col w-full px-10 sticky">
        <div class="flex justify-around pb-4 rounded-lg mb-4 border-b-8 border-amber-600 mt-8" id="podium-container">
          <?php

          // Display Second Place
          $podiumSecond = getPodium(2, "AccRank", null, $GLOBALS['type']);
          displayPodium($podiumSecond, "Segundo Lugar");

          // Display First Place
          $podiumFirst = getPodium(1, "AccRank", null, $GLOBALS['type']);
          displayPodium($podiumFirst, "Primeiro Lugar");

          // Display Third Place
          $podiumThird = getPodium(3, "AccRank", null, $GLOBALS['type']);
          displayPodium($podiumThird, "Terceiro Lugar");
          ?>
        </div>
      </div>
      <div class="flex flex-row gap-4">
        <div>
          <label for="typeSelect" class="mb-2 text-white">Tipo:</label>
          <select class="select select-bordered w-full max-w-xs mb-8 " id="typeSelect">
            <option disabled class="">Type - - -</option>
            <?php setSelectedType($GLOBALS['type'] ?? ''); ?>
          </select>
        </div>
      </div>
      <div class="h-96">
        <table  id="tableRanking"  class="table-fixed table-pin-rows table-pin-cols table-lg w-full text-center bg-gray-800 p-4 text-lg text-white border-b-2 border-gray-900">
          <thead>
            <tr class="text-lg text-white border-b-2 border-gray-900 items-center">
              <th class="ubuntu-bold w-2/6 hover">
                Rank
              </th>
              <th class="ubuntu-bold w-2/6 hover">Likes</th>
              <th class="ubuntu-bold w-2/6 hover">Conta</th>
            </tr>
          </thead>
          <tbody class="overflow-auto h-32">
            <?php
              RankingAcc($GLOBALS['type']);
            ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.js"></script>
  <script>
    var targetDateFromPHP = <?php echo json_encode($_SESSION['themes'][0]['finish_date']); ?>;
  </script>
  <script src="../src/js/timer.js"></script>
  <script src="../src/js/filterTheme.js"></script>
  <script src="../src/js/social.js"></script>
  <script>
    function redirectToPerson(element) {
        var profileId = element.getAttribute("data-id");
        window.location.href = "../perfil-de-outro/" + profileId;
    }

    $(document).ready(function() {
      $('#tableRanking').DataTable(
        {
          "scrollX": false,
          "paging": true,
          "pageLength": 4,
          "ordering": true,
          "info": false,
          "lengthChange": false,
          "searching": false,
        }
      );
    });
    </script>


</body>

</html>