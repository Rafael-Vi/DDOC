<?php

date_default_timezone_set('Europe/Lisbon');

function checkThemeIsFinished() {
    getThemes(false);
    // Check if the session theme should be finished
    if (isset($_SESSION['themes']) && !empty($_SESSION['themes']) ){
        $themes = $_SESSION['themes'];
        foreach ($themes as $theme) {
            $finishDate = strtotime($theme['finish_date']);
            $currentDate = time();
            if ($currentDate > $finishDate) {
                // Update the table to set is_finished = 1
                $dbConn = db_connect();
                if ($dbConn === false) {
                    die("ERROR: Could not connect. " . mysqli_connect_error());
                }
                include "saveLastPage.inc.php";
            }
        }
        return true; // Return true if there is a theme set
    }
    else {
        header("Location: errorPages/noThemeError.php");
        return false; // Return false if there is no theme set
    }
}
