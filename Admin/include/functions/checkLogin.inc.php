<?php

$currentScript = basename($_SERVER['PHP_SELF']);
$queryString = $_SERVER['QUERY_STRING'];

if (!isset($_SESSION['uid']) || empty($_SESSION['uid']) || 
    ($_SESSION['super_user'] == 0 && ($currentScript == 'register.php' || ($currentScript == 'data.php' && $queryString == 'table=admins')))) {
    header('Location: ./index.php');
    exit();
}