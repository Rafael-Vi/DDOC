<?php
    @session_start();
    error_reporting(E_ALL ^ E_NOTICE);

    require_once "functions/paths.inc.php";
    require_once "functions/alerts.inc.php";
    require_once "functions/echohtml.inc.php";
    require_once "functions/SQLfunctions.inc.php";
    require_once "functions/validateLRForm.inc.php";
    getThemes(false);
    if(isset($_SESSION['uid'])){
        getCanPostStatus($_SESSION['uid']);
    }

    if(basename($_SERVER['PHP_SELF']) !== 'messages.php'){
        unset($_SESSION['sender']);
        unset($_SESSION['convo_id']);
    }

