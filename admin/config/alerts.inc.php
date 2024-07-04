<?php
function validRegisterAl(){
    // Registration successful, redirect to another page
    echo '<div class="bg-green-100 p-5 w-1/2 absolute top-0 mt-8 left-1/2 transform -translate-x-1/2 rounded-lg">';
    echo '  <div class="flex space-x-3">';
    // Add an ID to the SVG tag
    echo '    <svg id="close-error" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="flex-none fill-current text-green-500 h-4 w-4">';
    echo '      <path d="M12 0c-6.627 0-12 5.373-12 12s5.373 12 12 12 12-5.373 12-12-5.373-12-12-12zm4.597 17.954l-4.591-4.55-4.555 4.596-1.405-1.405 4.547-4.592-4.593-4.552 1.405-1.405 4.588 4.543 4.545-4.589 1.416 1.403-4.546 4.587 4.592 4.548-1.403 1.416z" />';
    echo '    </svg>';
    echo '    <div class="leading-tight flex flex-col space-y-2">';
    echo '      <div class="text-sm font-medium text-green-700">Sucesso</div>';
    echo '      <div class="text-sm font-small text-green-800">Utilizador registrado com sucesso!</div>';
    echo '      <div class="text-sm font-small text-green-800">Login to enter the app</div>';
    echo '    </div>';
    echo '  </div>';
    echo '</div>';
}
function mySQLerror($error){
    echo '<div class="bg-red-100 p-5 w-1/2 absolute top-0 mt-8 left-1/2 transform -translate-x-1/2 rounded-lg">';
    echo '  <div class="flex space-x-3">';
    echo '    <svg id="close-error" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="flex-none fill-current text-red-500 h-4 w-4">';
    echo '      <path d="M12 0c-6.627 0-12 5.373-12 12s5.373 12 12 12 12-5.373 12-12-5.373-12-12-12zm4.597 17.954l-4.591-4.55-4.555 4.596-1.405-1.405 4.547-4.592-4.593-4.552 1.405-1.405 4.588 4.543 4.545-4.589 1.416 1.403-4.546 4.587 4.592 4.548-1.403 1.416z" />';
    echo '    </svg>';
    echo '    <div class="leading-tight flex flex-col space-y-2">';
    echo '      <div class="text-sm font-medium text-red-700">Algum erro aconteceu</div>';
    echo '      <div class="text-sm font-small text-red-800">'.print_r($error, true).'</div>';
    echo '    </div>';
    echo '  </div>';
    echo '</div>';
}

function echoError($error){
    echo '<div class="bg-red-100 p-5 w-1/2 absolute top-0 mt-8 left-1/2 transform -translate-x-1/2 rounded-lg">';
    echo '  <div class="flex space-x-3">';
    echo '    <svg id="close-error" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="flex-none fill-current text-red-500 h-4 w-4">';
    echo '      <path d="M12 0c-6.627 0-12 5.373-12 12s5.373 12 12 12 12-5.373 12-12-5.373-12-12-12zm4.597 17.954l-4.591-4.55-4.555 4.596-1.405-1.405 4.547-4.592-4.593-4.552 1.405-1.405 4.588 4.543 4.545-4.589 1.416 1.403-4.546 4.587 4.592 4.548-1.403 1.416z" />';
    echo '    </svg>';
    echo '    <div class="leading-tight flex flex-col space-y-2">';
    echo '      <div class="text-sm font-medium text-red-700">Algo deu errado</div>';
    echo '      <div class="text-sm font-small text-red-800">'.$error.'</div>';
    echo '    </div>';
    echo '  </div>';
    echo '</div>';
}

function echoSuccess($success){
    echo '<div class="bg-green-100 p-5 w-1/2 absolute mt-8 top-0 left-1/2 transform -translate-x-1/2 rounded-lg">';
    echo '  <div class="flex space-x-3">';
    echo '    <svg id="close-error" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="flex-none fill-current text-green-500 h-4 w-4">';
    echo '      <path d="M12 0c-6.627 0-12 5.373-12 12s5.373 12 12 12 12-5.373 12-12-5.373-12-12-12zm4.597 17.954l-4.591-4.55-4.555 4.596-1.405-1.405 4.547-4.592-4.593-4.552 1.405-1.405 4.588 4.543 4.545-4.589 1.416 1.403-4.546 4.587 4.592 4.548-1.403 1.416z" />';
    echo '    </svg>';
    echo '    <div class="leading-tight flex flex-col space-y-2">';
    echo '      <div class="text-sm font-medium text-green-700">Sucesso</div>';
    echo '      <div class="text-sm font-small text-green-800">'.$success.'</div>';
    echo '    </div>';
    echo '  </div>';
    echo '</div>';
}