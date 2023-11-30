<?php
    if(isset($_SESSION['uid'])) {
        // Variable 'session' with 'uid' value exists
        $uid = $_SESSION['uid'];
    
        if(empty($uid)) {
            // 'uid' value is empty
            session_unset();
            session_destroy();
            header("Location: accountLC.php");
            exit;
        }
        else {

        }
    } else {
        session_unset();
        session_destroy();
        header("Location: accountLC.php");
        exit;
    }

?>