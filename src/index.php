<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <!--Page Title-->
    <title>DDOC</title>

    <!--Meta Keywords and Description-->
    <meta name="keywords" content="">
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no"/>

    <!--Favicon-->
    <link rel="shortcut icon" href="./assets/images/2.png" >
    <script src="https://cdn.tailwindcss.com"></script>  
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/2.2.0/uicons-solid-rounded/css/uicons-solid-rounded.css'>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.7.2/dist/full.min.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="./src/css/social.css">
</head>
<body class="bg-gray-800">

<section class="w-full h-screen flex flex-col sm:flex-row items-center justify-center" id="welcome-ddoc">
    <!-- First div -->
    <div class="flex flex-col w-full sm:w-1/2 p-8 items-center justify-center lg:ml-auto">
        <div class="lg:text-8xl text-white ubuntu-bold hidden lg:block"> 
            WELCOME TO
        </div>
        <div class="flex items-center justify-center lg:justify-right flex-row float-right">
            <p class="text-8xl text-white ubuntu-bold">D</p>
            <p class="text-8xl mx-1 text-white ubuntu-bold">D</p>
            <div class="loader border-t-8 rounded-full border-t-red-400 bg-orange-300 animate-spin aspect-square w-24 flex justify-center items-center text-yellow-700"></div>
            <p class="mx-1 my-8 text-8xl text-white ubuntu-bold">C</p>
        </div>
    </div>

    <!-- Second div -->
    <div class="w-full sm:w-1/2 p-8 flex items-center justify-center">
        <ul class="text-white w-42">
            <li><a class="btn m-2 p-2 text-2xl w-full hover:scale-110 hover:border hover:border-orange-500 hover:b-2" href="./accountLC.php">Entra...</a></li>
            <li><a class="btn m-2 p-2 text-2xl w-full hover:scale-110 hover:border hover:border-orange-500 hover:b-2" href="#functions">Funcionalidades</a></li>
            <li><a class="btn m-2 p-2 text-2xl w-full hover:scale-110 hover:border hover:border-orange-500 hover:b-2" href="#">Manual</a></li>
            <li><a class="btn m-2 p-2 text-2xl w-full hover:scale-110 hover:border hover:border-orange-500 hover:b-2" href="#">Contactar Creador</a></li>
        </ul>
    </div>
</section>

<section  class="w-full h-screen flex flex-col sm:flex-row items-center justify-center">

<div class="hero min-h-screen bg-base-200">
  <div class="hero-content text-center">
    <div class="max-w-md">
      <h1 class="text-5xl font-bold mb-2">Agora que sabes no que te vais meter</h1>
      <a href="./accountLC.php" class="bg-orange-500 rounded-md hover:bg-gray-600 text-white hover:text-orange-500 w-full h-24 mt-8 text-3xl ubuntu-bold">Entra</a>
    </div>
  </div>
</div>

</section>
    
<script src="../dist/bundle.js"></script>
</body>
</html>
