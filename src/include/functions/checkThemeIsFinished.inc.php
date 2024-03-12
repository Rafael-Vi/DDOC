<?php


date_default_timezone_set('Europe/Lisbon');

// Check if the session theme should be finished
if (isset($_SESSION['themes'])) {
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

            $themeId = $theme['theme_id'];
            $sql = "UPDATE theme SET is_finished = 1 WHERE theme_id = ?";
            $stmt = mysqli_prepare($dbConn, $sql);
            mysqli_stmt_bind_param($stmt, "i", $themeId);

            // Execute the query
            if (mysqli_stmt_execute($stmt) === false) {
                die("ERROR: Could not execute query: $sql. " . mysqli_error($dbConn));
            }

            // Update the posts table to set Enabled = 1 for posts with the same theme_id
            $sqlPosts = "UPDATE posts SET Enabled = 1 WHERE theme_id = ?";
            $stmtPosts = mysqli_prepare($dbConn, $sqlPosts);
            mysqli_stmt_bind_param($stmtPosts, "i", $themeId);

            // Execute the query
            if (mysqli_stmt_execute($stmtPosts) === false) {
                die("ERROR: Could not execute query: $sqlPosts. " . mysqli_error($dbConn));
            }

            mysqli_stmt_close($stmt);
            mysqli_stmt_close($stmtPosts);
            mysqli_close($dbConn);
        }
    }
}
?>
