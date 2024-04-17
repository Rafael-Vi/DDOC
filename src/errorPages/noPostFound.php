<?php
include "../include/config.inc.php";
?>

<?php
  include "../include/functions/checkLogin.inc.php";
?> 

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/social.css">    
    <link rel="stylesheet" href="../css/errorPages.css">    
    <link rel="shortcut icon" href="../assets/images/2.png" >
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/2.2.0/uicons-solid-rounded/css/uicons-solid-rounded.css'>
    <script src="https://cdn.tailwindcss.com"></script>     <link href="https://cdn.jsdelivr.net/npm/daisyui@4.7.2/dist/full.min.css" rel="stylesheet" type="text/css" />
    <title>Post Doesn't Exist!!!</title>
</head>
<body class="bg-gray-900">
<div class="fixed inset-0 flex-row">
    <div class="fixed inset-0 z-50 bg-gray-900 flex items-center justify-center flex-col">
      <div class="flex items-center justify-center flex-row">
        <p class=" text-7xl text-white ubuntu-bold">D</p>
        <p class=" text-7xl mx-1 text-white ubuntu-bold">D</p>
        <div class="loader  border-t-8 rounded-full border-t-red-400 bg-orange-300 animate-spin
        aspect-square w-24 flex justify-center items-center text-yellow-700"></div>
        <p class="mx-1 my-8 text-7xl  text-white ubuntu-bold">C</p>
      </div>
      <h1 class="ubuntu-bold text-4xl text-white p-2 rounded">That post doesn't exist</h1>
      <a href="<?php echo $_SESSION['last_page']  ?>" class="btn">Go Back</a>
    </div>
</div>
</body>
</html>