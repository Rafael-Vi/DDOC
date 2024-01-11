<?php
    @session_start();
    require_once "dbConnect.inc.php";
    require_once "alerts.inc.php";
    require_once "echohtml.inc.php";
    require_once "SQLfunctions.inc.php";
    $title = $_POST['post-title'];
    $type = $_POST['post-type'];
    $file = $_FILES['file-upload'];
    var_dump($_FILES);
    var_dump($_POST);

    createPost($_SESSION['uid'],$title,$type,$file);

?>