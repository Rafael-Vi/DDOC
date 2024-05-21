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
</head>
<body class="bg-gray-800">

<section class="w-full h-screen flex flex-col sm:flex-row items-center justify-center" id="welcome-ddoc">
    <!-- First div -->
    <div class="flex flex-col w-1/2 p-8 flex items-center justify-center lg:ml-auto">
        <div class="lg:text-8xl text-white ubuntu-bold hidden lg:block"> 
            WELCOME TO
        </div>
        <div class="flex items-center justify-center lg:justify-right flex-row float right">
            <p class="text-8xl text-white ubuntu-bold">D</p>
            <p class="text-8xl mx-1 text-white ubuntu-bold">D</p>
            <div class="loader border-t-8 rounded-full border-t-red-400 bg-orange-300 animate-spin aspect-square w-24 flex justify-center items-center text-yellow-700"></div>
            <p class="mx-1 my-8 text-8xl text-white ubuntu-bold">C</p>
        </div>
    </div>

    <!-- Second div -->
    <div class="w-1/2 p-8 flex items-start justify-start">
        <ul class="text-white w-42">
            <li><a class="btn m-2 p-2 text-2xl w-full hover:scale-110 hover:border hover:border-orange-500 hover:b-2" href="./accountLC.php">Entra...</a></li>
            <li><a class="btn m-2 p-2 text-2xl w-full hover:scale-110 hover:border hover:border-orange-500 hover:b-2" href="#functions">Funcionalidades</a></li>
            <li><a class="btn m-2 p-2 text-2xl w-full hover:scale-110 hover:border hover:border-orange-500 hover:b-2" href="#">Manual</a></li>
            <li><a class="btn m-2 p-2 text-2xl w-full hover:scale-110 hover:border hover:border-orange-500 hover:b-2" href="#">Contactar Creador</a></li>
        </ul>
    </div>
</section>

<section class="w-full h-screen flex flex-col sm:flex-row items-center justify-center" id="functions">

<ul class="timeline timeline-vertical">
  <li>
    <div class="timeline-start timeline-box">Temas Diários</div>
    <div class="timeline-middle">
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" /></svg>
    </div>
    <hr/>
  </li>
  <li>
    <hr/>
    <div class="timeline-start timeline-box">Ranking temático</div>
    <div class="timeline-middle">
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" /></svg>
    </div>
    <hr/>
  </li>
  <li>
    <hr/>
    <div class="timeline-start timeline-box">Ranks de Contas</div>
    <div class="timeline-middle">
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" /></svg>
    </div>
    <hr/>
  </li>
  <li>
    <hr/>
    <div class="timeline-start timeline-box">Mensagens</div>
    <div class="timeline-middle">
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" /></svg>
    </div>
    <hr/>
  </li>
</ul>
</section>


<section  class="w-full h-screen flex flex-col sm:flex-row items-center justify-center" id="functions">

<div class="hero min-h-screen bg-base-200">
  <div class="hero-content text-center">
    <div class="max-w-md">
      <h1 class="text-5xl font-bold">Hello there</h1>
      <p class="py-6">Provident cupiditate voluptatem et in. Quaerat fugiat ut assumenda excepturi exercitationem quasi. In deleniti eaque aut repudiandae et a id nisi.</p>
      <button class="btn btn-primary">Get Started</button>
    </div>
  </div>
</div>

</section>
    
<script src="../dist/bundle.js"></script>
</body>
</html>
