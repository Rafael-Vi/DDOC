<?php
       @session_start();
       require_once "../config.inc.php";
    $title = $_POST['post-title'];
    $type = $_POST['post-type'];
    $file = $_FILES['file-upload'];
    var_dump($_FILES);
    var_dump($_POST);

    createPost($_SESSION['uid'],$title,$type,$file);
    header("Location: ../../social.php");
    exit;
?>