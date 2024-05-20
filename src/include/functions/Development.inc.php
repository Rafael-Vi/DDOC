<?php
$dbConn = db_connect();

// Prepare the SQL query to check the development status
$sql = "SELECT * FROM database_status";
// Define params as an empty array
$params = array();


// Execute the query
$result = executeQuery($dbConn, $sql, $params);

// Fetch the first row from the result
$row = mysqli_fetch_assoc($result);

// Get the status from the row
$status = $row['status'];

// Use a switch case on the status
switch ($status) {
    case 1:
        // If status is 1, do nothing
        break;
    case 0:
        header("Location: /errorPages/maintenance.php");
        exit();
    default:
        // Optional: handle other cases
        break;
}

// Close the database connection
mysqli_close($dbConn);