<?php
require 'config/config.inc.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DDOC ADMININISTRAÇÃO</title>
    <link rel="shortcut icon" href="./assets/fav_icon.png" type="image/x-icon">
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/2.2.0/uicons-solid-rounded/css/uicons-solid-rounded.css'>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.7.2/dist/full.min.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/2.3.0/uicons-bold-rounded/css/uicons-bold-rounded.css'>
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/2.3.0/uicons-solid-straight/css/uicons-solid-straight.css'>
    <style>
        .error-container {
            position: fixed;
            top: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 100%;
            min-height: 60px;
            text-align: center;
            z-index: 1000; /* Ensure it's above other content */
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

      .dataTables_filter {
    margin-bottom: 20px; /* Adds margin below the search box */
}

.dataTables_filter label {
    display: flex;
    align-items: center;
    gap: 10px; /* Adds some space between the text and the input box */
}

.dataTables_filter input {
    padding: 8px;
    border-radius: 4px;
    border: 1px solid #ccc; /* Adds padding, border-radius, and border styling to the search input */
}

.dataTables_length {
    margin-bottom: 20px; /* Adds margin below the length selection */
}

.dataTables_length label {
    display: flex;
    align-items: center;
    gap: 10px; /* Adds some space between the text and the select box */
}

.dataTables_length select {
    padding: 8px;
    border-radius: 4px;
    border: 1px solid #ccc; /* Adds padding, border-radius, and border styling to the select dropdown */
}
    </style>

    </style>
</head>
<body class="bg-base-300 h-full min-h-screen">
<?php
if (strpos($_SERVER['REQUEST_URI'], 'login.php') === false) {
    include 'includes/nav.inc.php';
}