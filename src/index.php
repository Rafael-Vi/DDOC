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
  <link href="/dist/tailwind.css" rel="stylesheet" type="text/css" />
  <link rel="stylesheet" href="./src/css/social.css">
  <style>
    /* CSS code */
    .navbar {
      position: fixed; /* Make the navbar fixed at the top */
      top: 0; /* Position it at the top */
      left: 0; /* Align it to the left */
      width: 100%; /* Make it occupy the full width */
      z-index: 1000; /* Ensure it stays on top of other content */
      display: none;
    }

    .navbar.visible {
      display: block; /* Show the navbar when the 'visible' class is added */
    }
  </style>
</head>
<body class="bg-gray-800">

<div class="navbar bg-base-100">
  <div class="flex-none">
    <button class="btn btn-square btn-ghost">
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="inline-block w-5 h-5 stroke-current"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
    </button>
  </div>
  <div class="flex-1">
    <a class="btn btn-ghost text-xl">daisyUI</a>
  </div>
  <div class="flex-none">
    <button class="btn btn-square btn-ghost">
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="inline-block w-5 h-5 stroke-current"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z"></path></svg>
    </button>
  </div>
</div>

<section class="w-full h-screen flex flex-col sm:flex-row items-center justify-center" id="welcome-ddoc">
  <!-- First div -->
  <div class="flex flex-col w-full sm:w-1/2 p-8 items-center justify-center lg:ml-auto">
    <div class="lg:text-8xl text-white ubuntu-bold hidden xl:block"> 
       Bem vindo ao
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
      <li><a class="btn m-2 p-2 text-2xl w-full hover:scale-110 hover:border hover:border-orange-500 hover:b-2" href="#">Contactar Criador</a></li>
    </ul>
  </div>
</section>
<div class="flex justify-center items-center min-h-screen">
  <section id="functions" class="flex flex-col">

  </section>
</div>

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
<script>

  // JavaScript code
document.addEventListener("DOMContentLoaded", function() {
  const navbar = document.querySelector('.navbar');
  const functionalitiesSection = document.querySelector('#functions');

  function toggleNavbarVisibility() {
    let sectionTop = functionalitiesSection.getBoundingClientRect().top + window.scrollY;
    if (window.scrollY >= sectionTop) {
      navbar.classList.add('visible');
    } else {
      navbar.classList.remove('visible');
    }
  }

  window.addEventListener('scroll', toggleNavbarVisibility);
});
</script>
</body>
</html>
