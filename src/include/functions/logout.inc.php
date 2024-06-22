<?php
    @session_start();
    require_once "../config.inc.php";    
    session_unset();
    session_destroy();
    header("Location: /login");
    exit;
