<?php
@session_start();
global $arrConfig;
$arrConfig['pagesWhiteList'] = array('login.php', 'register.php', 'forgot-password.php', 'reset-password.php');
$arrConfig['connect_DB'] = array('localhost', 'root', 'ddocPassNice', 'ddoc');

if (!isset($_SESSION['admin_id']) && (!in_array(basename($_SERVER['PHP_SELF']), $arrConfig['pagesWhiteList']))) {
    header('Location: login.php');
    exit;
}


require 'db.inc.php';

// Define an associative array to specify permissions for each table
$tablePermissions = [
    'users' => [
        'editable' => true,
        'deletable' => true,
        'addable' => true
    ],
    'products' => [
        'editable' => true,
        'deletable' => true,
        'addable' => true
    ],
    'orders' => [
        'editable' => false, // Example: Orders can be displayed but not edited
        'deletable' => false,
        'addable' => false
    ],
    // Add more tables as needed
];