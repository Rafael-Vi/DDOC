<?php
global $arrConfig;

$dbconn = $arrConfig['conn'];



if (!isset($_GET['table']) || empty($_GET['table'])) {

    header('Location: home.php');
    exit;
}
$table = $_GET['table'];

$query = "SHOW TABLES LIKE '$table'";
$result = $dbconn->query($query);


if ($result->num_rows == 0) {
    header('Location:  home.php');
    exit;
}

if ($table == 'admins' && $_SESSION['super_user'] != 1) {
    header('Location: home.php');
    exit;
}

