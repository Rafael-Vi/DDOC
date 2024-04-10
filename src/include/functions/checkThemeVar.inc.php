<?php
function checkThemeVar() {
    // Check if a theme variable exists in the GET array
    if (isset($_GET['theme']) && !empty($_GET['theme'])) {
        $themeId = $_GET['theme'];

        // Connect to the database
        $dbConn = db_connect();
        if ($dbConn === false) {
            die("ERROR: Could not connect. " . mysqli_connect_error());
        }

        // Prepare a query to check if the theme exists in the database
        $sql = "SELECT * FROM theme WHERE theme_id = ?";
        $stmt = mysqli_prepare($dbConn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $themeId);

        // Execute the query
        if (mysqli_stmt_execute($stmt) === false) {
            die("ERROR: Could not execute query: $sql. " . mysqli_error($dbConn));
        }

        // Get the result of the query
        $result = mysqli_stmt_get_result($stmt);

        // If the theme does not exist in the database, redirect to themeNotExist.php
        if (mysqli_num_rows($result) == 0) {
            header("Location: ../src/errorPages/noThemeError.php");
            exit;
        }

        mysqli_stmt_close($stmt);
        mysqli_close($dbConn);

        // Return the theme id
        return $themeId;
    } 
}
?>