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
        'editable' => false,
        'deletable' => true,
        'addable' => true
    ],
    'theme' => [
        'editable' => true,
        'deletable' => true,
        'addable' => true
    ],
    'database_status' => [
        'editable' => true,
        'deletable' => false,
        'addable' => true
    ],
    'logs' => [
        'editable' => false,
        'deletable' => false,
        'addable' => false
    ],
    'report' => [
        'editable' => false,
        'deletable' => false,
        'addable' => false
    ],
    'posts' => [
        'editable' => false,
        'deletable' => true,
        'addable' => false
    ],
    // Add more tables as needed
];