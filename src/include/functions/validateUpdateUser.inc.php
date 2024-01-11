<?php
      @session_start();
      require_once "../config.inc.php";

      if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm'])) {
      $username = $_POST['username'];
      $realName = $_POST['realName'];
      $biography = $_POST['biography'];
      $profilePic = $_FILES['profilePic'];
      var_dump($_POST);
      updateUser($_SESSION['uid'], $username, $realName, $profilePic, $biography);
      header("Location: ../../social.php");
      exit;
      }

?>