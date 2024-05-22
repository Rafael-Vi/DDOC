<?php

// Include your SQLfunctions.inc.php file
require_once './SQLfunctions.inc.php';

// Get the email and token from the GET parameters
$email = $_GET['email']?? '';
$idFromGet = $_GET['id']?? ''; // Assuming 'id' is the parameter holding the user ID

// Check if both email and id are provided
if (!$email ||!$idFromGet) {
    die('Email or ID not provided.');
}

// Connect to the database
$dbConn = db_connect();

// Prepare the query to fetch the user ID based on the email
$query = "SELECT user_id FROM users WHERE email =?";
$params = [$email];
$result = executeQuery($dbConn, $query, $params);

// Check if the user exists
if ($result === false) {
    die('User not found.');
}

// Fetch the user ID
$user = $result->fetch(PDO::FETCH_ASSOC);
$userID = $user['user_id'];

// Check if the ID from the GET parameters matches the user ID fetched from the database
if ($userID!= $idFromGet) {
    die('Mismatched user ID.');
}

// Update the email_verify field to 1 for the matching user
$query = "UPDATE users SET email_verify = 1 WHERE user_id =?";
$params = [$userID];
$result = executeQuery($dbConn, $query, $params);

// Check if the update was successful
if ($result === false) {
    die('Failed to update email verification status.');
}

// At this point, the user's email verification status has been updated
echo "User email verification status updated successfully.";

// Close the database connection
mysqli_close($dbConn);
?>
