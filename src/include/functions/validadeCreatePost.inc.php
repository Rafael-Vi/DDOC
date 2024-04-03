<?php
       @session_start();
       require_once "../config.inc.php";
    $title = $_POST['post-title'];
    $type = $_POST['post-type'];
    $file = $_FILES['file-input'];
    var_dump($_FILES);
    var_dump($_POST);

    createPost($_SESSION['uid'],$title,$type,$file, $_SESSION['themes'][0]['theme_id']);
    header("Location: ../../social.php");
    exit;
?>