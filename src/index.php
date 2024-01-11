<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../src/css/index.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Welcome...</title>
</head>
<body>

    <header class="text-gray-600 body-font bg-gray-100 h-36">
        <div class="container mx-auto flex flex-wrap p-5 flex-col md:flex-row items-center">
          <a class="flex title-font font-medium items-center text-gray-900 mb-4 md:mb-0">
            <img src="./assets/images/1.png" alt="DDOC" id="DDOCSymbol" class="w-24">
          </a>
          <nav class="inline-flex items-center md:ml-auto flex-wrap text-base justify-center text-center">
            <a class="inline-flex items-center mr-5 rounded-lg p-2 hover:text-gray-900  hover:bg-gray-200" href="#functionalitiesDivider">The functionalities</a>
            <a class="inline-flex items-center mr-5 rounded-lg p-2 hover:text-gray-900  hover:bg-gray-200">Second Link</a> 
            <a class="inline-flex items-center mr-5 rounded-lg p-2 hover:text-gray-900  hover:bg-gray-200">Third Link</a>
            <a class="inline-flex items-center mr-5 rounded-lg p-2 text-orange-600 hover:bg-gray-200" href="../src/accountLC.php">Login & Register</a>
          </nav>
          <button class="inline-flex items-center bg-gray-100 border-0 py-1 px-3 focus:outline-none hover:bg-gray-200 rounded text-base mt-4 md:mt-0">
            Contact the Creator
          </button>
        </div>
        
    </header>

      <section class="text-gray-600 body-font">
        <div class="container mx-auto flex px-5 py-24 md:flex-row flex-col items-center">
          <div class="lg:max-w-lg lg:w-full md:w-1/2 w-5/6 mb-10 md:mb-0">
            <img class="object-cover object-center rounded" alt="hero" src="https://dummyimage.com/720x600">
          </div>
          <div class="lg:flex-grow md:w-1/2 lg:pl-24 md:pl-16 flex flex-col md:items-start md:text-left items-center text-center">
            <h1 class="title-font sm:text-4xl text-3xl mb-4 font-medium text-gray-900">Hi there,
              <br class="hidden lg:inline-block">Welcome to DDOC.
            </h1>
           <p class=" lg:inline-block  text-xl font-medium">The Social Network that helps you with what to post</p>
          </div>
        </div>
      </section>

      <span class="relative flex justify-center">
        <div
          class="absolute inset-x-0 top-1/2 h-px -translate-y-1/2 bg-transparent bg-gradient-to-r from-transparent via-orange-500 to-transparent opacity-75"
        ></div>
        <span class="relative z-10 bg-white px-6 textl-xl font-bold text-orange-500" id="functionalitiesDivider">FUNCTIONALITIES</span>
      </span>
      <div class="grid grid-cols-4 gap-4 lg:gap-8 mt-8 mb-8 justify-items-center">
      <a class="card" id="card1" href="#OwnAccountSec">
        <p class="heading">Own Account</p>
      </a>
      <a class="card" id="card2" href="#PromptsSec">
        <p class="heading">The Prompts</p>
      </a>
      <a class="card "id="card1" href="#CreationPostsSec">
        <p class="heading">Creation of Posts</p>
      </a>
      <a class="card" id="card2" href="#RankingsSec">
        <p class="heading">Rankings</p>
      </a>
      <a class="card" id="card2">
        <p class="heading">Popular this month</p>
      </a>
      <a class="card" id="card1">
        <p class="heading">Popular this month</p>
      </a>
      <a class="card" id="card2">
        <p class="heading">Popular this month</p>
      </a>
      <a class="card" id="card1">
        <p class="heading">Popular this month</p>
      </a>
    </div>
    <section class="text-gray-600 body-font" id="OwnAccountSec">
        <div class="container mx-auto flex px-5 py-24 md:flex-row flex-col items-center">
          <div class="lg:flex-grow md:w-1/2 lg:pl-24 md:pl-16 flex flex-col md:items-start md:text-left items-center text-center">
            <h1 class="title-font sm:text-4xl text-3xl mb-4 font-medium text-gray-900">Hi there,
              <br class="hidden lg:inline-block">Own account.
            </h1>
           <p class=" lg:inline-block  text-xl font-medium">The Social Network that helps you with what to post</p>
          </div>
          <div class="lg:max-w-lg lg:w-full md:w-1/2 w-5/6 mb-10 md:mb-0">
            <img class="object-cover object-center rounded" alt="hero" src="https://dummyimage.com/720x600">
          </div>
        </div>
    </section>

    <section class="text-gray-600 body-font" id="PromptsSec">
        <div class="container mx-auto flex px-5 py-24 md:flex-row flex-col items-center">
          <div class="lg:max-w-lg lg:w-full md:w-1/2 w-5/6 mb-10 md:mb-0">
            <img class="object-cover object-center rounded" alt="hero" src="https://dummyimage.com/720x600">
          </div>
          <div class="lg:flex-grow md:w-1/2 lg:pl-24 md:pl-16 flex flex-col md:items-start md:text-left items-center text-center">
            <h1 class="title-font sm:text-4xl text-3xl mb-4 font-medium text-gray-900">Hi there,
              <br class="hidden lg:inline-block">The Prompts.
            </h1>
           <p class=" lg:inline-block  text-xl font-medium">The Social Network that helps you with what to post</p>
          </div>
        </div>
      </section>

      <section class="text-gray-600 body-font" id="CreationPostsSec">
        <div class="container mx-auto flex px-5 py-24 md:flex-row flex-col items-center">
          <div class="lg:flex-grow md:w-1/2 lg:pl-24 md:pl-16 flex flex-col md:items-start md:text-left items-center text-center">
            <h1 class="title-font sm:text-4xl text-3xl mb-4 font-medium text-gray-900">Hi there,
              <br class="hidden lg:inline-block">Creation of Posts.
            </h1>
           <p class=" lg:inline-block  text-xl font-medium">The Social Network that helps you with what to post</p>
          </div>
          <div class="lg:max-w-lg lg:w-full md:w-1/2 w-5/6 mb-10 md:mb-0">
            <img class="object-cover object-center rounded" alt="hero" src="https://dummyimage.com/720x600">
          </div>
        </div>
      </section>

      <section class="text-gray-600 body-font" id="RankingsSec">
        <div class="container mx-auto flex px-5 py-24 md:flex-row flex-col items-center">
          <div class="lg:max-w-lg lg:w-full md:w-1/2 w-5/6 mb-10 md:mb-0">
            <img class="object-cover object-center rounded" alt="hero" src="https://dummyimage.com/720x600">
          </div>
          <div class="lg:flex-grow md:w-1/2 lg:pl-24 md:pl-16 flex flex-col md:items-start md:text-left items-center text-center">
            <h1 class="title-font sm:text-4xl text-3xl mb-4 font-medium text-gray-900">Hi there,
              <br class="hidden lg:inline-block">Rankings.
            </h1>
           <p class=" lg:inline-block  text-xl font-medium">The Social Network that helps you with what to post</p>
          </div>
        </div>
      </section>
    <span class="relative flex justify-center">
        <div
          class="absolute inset-x-0 top-1/2 h-px -translate-y-1/2 bg-transparent bg-gradient-to-r from-transparent via-orange-500 to-transparent opacity-75"
        ></div>
        <span class="relative z-10 bg-white px-6 textl-xl font-bold text-orange-500" id="">Stuff</span>
      </span>

<script src="/dist/bundle.js"></script>
</body>
</html>