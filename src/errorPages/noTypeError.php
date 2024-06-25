
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php require '/src/errorPages/errorPages-header.php';?>
    <title>Sem tipo disponível!!!</title>
</head>
<body class="bg-gray-800">
<div class="fixed inset-0 flex-row">
    <div class="fixed inset-0 z-50 bg-gray-800 flex items-center justify-center flex-col">
      <div class="flex items-center justify-center flex-row">
        <p class=" text-7xl text-white ubuntu-bold">D</p>
        <p class=" text-7xl mx-1 text-white ubuntu-bold">D</p>
        <div class="loader  border-t-8 rounded-full border-t-red-400 bg-orange-300 animate-spin
        aspect-square w-24 flex justify-center items-center text-yellow-700"></div>
        <p class="mx-1 my-8 text-7xl  text-white ubuntu-bold">C</p>
      </div>
      <h1 class="ubuntu-bold text-2xl sm:text-4xl text-white p-2 rounded">Desculpa, esse tipo não está disponível</h1>
<a href="javascript:history.back()" class="btn  w-3/4 sm:w-auto">Voltar atrás</a>
    </div>
</div>
</body>
</html>