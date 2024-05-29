<?php
require 'config/config.inc.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GENTL. Dashboard</title>
    <link rel="shortcut icon" href="./assets/fav_icon.png" type="image/x-icon">
    <link rel="stylesheet" href="../css/tailwind.css">
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/2.3.0/uicons-bold-rounded/css/uicons-bold-rounded.css'>
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/2.3.0/uicons-solid-straight/css/uicons-solid-straight.css'>
</head>
<body class="bg-base-300 h-full min-h-screen">
<?php
if (strpos($_SERVER['REQUEST_URI'], 'login.php') === false) {
    include 'includes/nav.inc.php';
}