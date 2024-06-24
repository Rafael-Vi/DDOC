<?php

// Include your SQLfunctions.inc.php file
require_once '/var/www/DDOC/src/include/config.inc.php';

global $EncKey; // Ensure the global encryption key is accessible

// Get the encrypted email and username from the GET parameters
$encryptedEmail = $_GET['email'] ?? '';
$encryptedUsername = $_GET['username'] ?? ''; // Now expecting the username to be encrypted as well


// Decrypt both the email and username
$email = decrypt($encryptedEmail, $EncKey); // Decrypt the email
$usernameFromGet = decrypt($encryptedUsername, $EncKey); // Decrypt the username

// Validate decrypted values
if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    die('Invalid or missing email.');
}
if (empty($usernameFromGet)) {
    die('Invalid or missing username.');
}

// The rest of your code follows...
// Connect to the database
$dbConn = db_connect();

// Prepare the query to fetch the username based on the email
$query = "SELECT user_name FROM users WHERE user_email = ?";
$params = [$email];
$result = executeQuery($dbConn, $query, $params);

// Check if the user exists
if ($result === false || $result->num_rows == 0) {
    die('User not found.');
}

// Fetch the username
$user = $result->fetch(PDO::FETCH_ASSOC);
$username = $user['user_name'];

// Check if the decrypted username from the GET parameters matches the username fetched from the database
if ($username != $usernameFromGet) {
    die('Mismatched username.');
}

// Update the email_verify field to 1 for the matching user
$query = "UPDATE users SET email_verify = 1 WHERE user_name = ?";
$params = [$username];
$result = executeQuery($dbConn, $query, $params);

// Check if the update was successful
if ($result === false) {
    die('Failed to update email verification status.');
}

// At this point, the user's email verification status has been updated
echo "User email verification status updated successfully.";

// Close the database connection
$dbConn = null; // This is how you close a PDO connection
