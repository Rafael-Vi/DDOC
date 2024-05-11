<?php
include "include/config.inc.php";
?>

<?php
  include "include/functions/checkLogin.inc.php";
?> 
<?php
      require "include/functions/checkThemeIsFinished.inc.php";
?>

<?php
    include "include/functions/saveLastPage.inc.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../src/css/social.css">    <link rel="shortcut icon" href="./assets/images/2.png" >
    <script src="https://cdn.tailwindcss.com"></script>  
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/2.2.0/uicons-solid-rounded/css/uicons-solid-rounded.css'>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.7.2/dist/full.min.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Messages</title> 
</head>
<body class="h-full flex">
    <?php echoLoadScreen(); ?>    <?php
        //echoShowTheme();
    ?>
    <?php echoNav(); ?>
    <div id="messages-div" class="bg-gray-900 fixed flex flex-col h-full w-full md:w-9/12 p-0 m-0 md:right-0">
        <h1 class=" h-32 text-white text-white border text-center sm:text-start border-black w-full p-10 font-bold text-4xl shadow-md shadow-amber-600 bg-gray-800 sticky top-0">
        <?php
         if (isset($_GET['convo_id'])){
            echo "Conversa com utilizador";
         }else{
            echo "Mensagens";
         }
        ?>   
        </h1>

        <div class="h-full border border-black w-full p-10 overflow-auto hide-scrollbar ">
        <?php 

        
                if (isset($_GET['convo_id'])) {
                    // Define a random message ID, message, date, and sender
                    $messageID = rand(1, 100);
                    $message = 'This is a random message';
                    $date = date('Y-m-d H:i:s');
                    $sender = array(
                        'username' => 'RandomUser',
                        'profile_pic' => 'https://daisyui.com/images/stock/photo-1534528741775-53994a69daeb.jpg'
                    );

                    // Call the function
                    echoMessages($messageID, $message, $date, $sender);

                    
                    echo'
                    <div class="fixed w-full md:w-9/12  bottom-16 md:bottom-0 right-0 p-4 md:p-6 bg-gray-800 text-white">
                    <form class="flex justify-center h-10">
                        <input type="text" class="rounded-l-lg w-full p-4 border-t mr-0 border-b border-l text-gray-800 border-gray-200 bg-white" placeholder="Mensagem">
                        <button class="flex items-center justify-center px-8 rounded-r-lg bg-orange-500 hover:bg-gray-700 hover:text-orange-500 text-gray-800 font-bold p-4 uppercase border-t border-b border-r"><i class="fi-sr-paper-plane fi"></i></button>
                    </form>
                </div>
                    ';
                } else {
                    echoConvo();
                    echoConvo();
                    echoConvo();
                }
        ?>
       
        </div>
    </div>

    <?php echoBottomNav();?>
    </script><script>
    var targetDateFromPHP = <?php echo json_encode($_SESSION['themes'][0]['finish_date']); ?>;
    </script>
  <script src="../src/js/timer.js"></script>
<script src="../src/js/sendMessages.js"></script>
  <script src="../src/js/social.js"></script>
</body>
</html>