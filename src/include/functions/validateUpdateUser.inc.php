<?php
   if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm'])) {
    $username = $_POST['username'];
    $realName = $_POST['realName'];
    $profilePic = $_POST['profilePic'];
    $biography = $_POST['biography'];
    updateUser($_SESSION['uid'], $username, $realName, $profilePic, $biography);
    header("Location: ../../social.php");
    exit;
   }
?>