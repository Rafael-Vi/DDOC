<?php
global $arrConfig;

$dbconn = $arrConfig['conn'];

$table = $_GET['table'];
$action = $_GET['action'];

if (!isset($_GET['action'])) {
    header('Location: home.php');
    exit;
}

if ($action != 'edit' && $action != 'insert') {
    header('Location: home.php');
    exit;
}

if ($action == 'edit') {
    if (!isset($_GET['id'])) {
        header('Location: home.php');
        exit;
    }
    $id = $_GET['id'];
    $query = "SELECT * FROM $table WHERE id = $id";
    $result = $dbconn->query($query);    

    if ($result->num_rows == 0) {
        header('Location: home.php');
        exit;
    }
}

if ($table == 'admins' && $action == 'insert') {
    header('Location: home.php');
    exit;
}