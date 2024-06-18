<?php
include "include/config.inc.php";
include "include/functions/checkLogin.inc.php";
require "include/functions/checkThemeIsFinished.inc.php";
if (checkThemeIsFinished()){
    include "include/functions/saveLastPage.inc.php";
}

require "include/functions/Development.inc.php";

// Reset session variables if no specific convo_id is provided
if (!isset($_GET['convo_id']) || $_GET['convo_id'] == "") {
    unset($_SESSION['sender']);
    unset($_SESSION['convo_id']);
} else {
    // Existing logic to handle specific convo_id
    $convoIds = getConvo();
    if (in_array($_GET['convo_id'], $convoIds)) {
        $userDetails = getUserDetails($_GET['convo_id']);
        
        // Simplified sender assignment
        $_SESSION['sender'] = [
            'username' => $userDetails['username'],
            'profile_pic' => $userDetails['profile_pic']
        ];
        $_SESSION['convo_id'] = $_GET['convo_id'];
    } else {
        header("Location: messages.php");
        exit; // Ensure no further output is sent
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../src/css/social.css">    <link rel="shortcut icon" href="./assets/images/2.png" >
    <script src="https://cdn.tailwindcss.com"></script>  
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/2.2.0/uicons-solid-rounded/css/uicons-solid-rounded.css'>
   <link href="/dist/tailwind.css" rel="stylesheet" type="text/css" />
  <style>
    .message-container {
      position: fixed;
      top: 0;
      left: 50%;
      transform: translateX(-50%);
      width: 100%;
      text-align: center;
      z-index: 1000; /* Ensure it's above other content */
    }
  </style>
    <title>Messages</title> 
</head>
<body class="h-full flex">
    <?php echoLoadScreen(); ?>    <?php
        //echoShowTheme();
    ?>
    <?php ob_start(); 

 ?>
<div class="message-container">
  <?php
  if(isset($_SESSION['error'])) {
      echoError($_SESSION['error']);
      unset($_SESSION['error']);
  } elseif(isset($_SESSION['success'])) {
      if ($_SESSION['success'] == 'Registration successful') {
          validRegisterAl();
      } else {
          echoSuccess($_SESSION['success']);
      }
      unset($_SESSION['success']);
  }
  ?>
</div>
    <div id="messages-div" class="bg-gray-900 fixed flex flex-col h-full w-full md:w-9/12 p-0 m-0 md:right-0">
        <h1 class=" h-32 text-center sm:text-start w-full p-10 font-bold text-4xl text-white backdrop-blur-md">
            <?php
            if (isset($_GET['convo_id']) && !empty($_GET['convo_id'])){
                echo '@'.$_SESSION['sender']['username'].'';
            }else{
                echo "Mensagens";
            }
            ?>   
        </h1>

        <div class="h-full w-full  overflow-auto hide-scrollbar ">
        <?php 
        
        if (isset($_GET['convo_id']) && $_GET['convo_id'] != "") {

            // Check if the provided convo_id is valid
            if (in_array($_GET['convo_id'], $convoIds)) {
                echo' <div class="h-full w-full overflow-auto hide-scrollbar px-2 sm:px-10" id="message-container">';

                getMessages($_SESSION['sender'],$_GET['convo_id']);
                echo'</div>';

                echo '
                    <div class="absolute w-full bottom-16 md:bottom-0 right-0 p-4 md:p-6 bg-gray-800 text-white">
                        <div class="flex justify-center h-10">
                            <input id="message-box" type="text" class="rounded-l-lg w-full p-4 border-t mr-0 border-b border-l text-gray-800 border-gray-200 bg-white" placeholder="Mensagem" onkeydown="if(event.key === \'Enter\') sendMessage(\'' . $_GET['convo_id'] . '\')">
                            <button onclick="sendMessage(\'' . $_GET['convo_id'] . '\')" class="flex items-center justify-center px-8 rounded-r-lg bg-orange-500 hover:bg-gray-700 hover:text-orange-500 text-gray-800 font-bold p-4 uppercase border-t border-b border-r"><i class="fi-sr-paper-plane fi" id="message-button"></i></button>
                        </div>
                    </div>
                ';
            } else {
                header("Location: messages.php");
                exit; // Ensure no further output is sent
            }
        } else {
            getConvo("echo");
        }

        
        ?>
       
        </div>
        <div class=" h-32"></div>
    </div>

    <?php echoNav(); echoBottomNav(); 
    ob_end_flush();?>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    </script><script>
    var targetDateFromPHP = <?php echo json_encode($_SESSION['themes'][0]['finish_date']); ?>;
    </script>

    
<script>
    // Scroll to the bottom of the message container
    window.onload = function() {
        var messageContainer = document.getElementById('message-container');
        messageContainer.scrollTop = messageContainer.scrollHeight;
    }
</script>
  <script src="../src/js/timer.js"></script>
<script src="../src/js/sendMessages.js"></script>
  <script src="../src/js/social.js"></script>
</body>
</html>