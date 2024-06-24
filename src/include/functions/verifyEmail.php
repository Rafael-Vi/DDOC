<?php

// Include your SQLfunctions.inc.php file
require_once '/var/www/DDOC/src/include/config.inc.php';

// Initialize an array to hold error messages
$errorMessages = [];

// Get the encrypted email and username from the GET parameters
$encryptedEmail = $_GET['email'] ?? '';
$encryptedUsername = $_GET['username'] ?? ''; // Now expecting the username to be encrypted as well

var_dump($_GET);

// Decrypt both the email and username
$email = decrypt($encryptedEmail); // Decrypt the email
$usernameFromGet = decrypt($encryptedUsername); // Decrypt the username

// Use var_dump to output the decrypted values
print($email);
print($usernameFromGet);

// Validate decrypted values
if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errorMessages[] = 'Invalid or missing email.';
    
}
if (empty($usernameFromGet)) {
    $errorMessages[] = 'Invalid or missing username.';
}

// If there are any error messages, echo them and exit the script
if (!empty($errorMessages)) {
    foreach ($errorMessages as $errorMessage) {
        echo $errorMessage . "\n";
    }
    exit; // Exit the script after displaying all error messages
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
    echo 'User not found.';
    exit;
}

// Fetch the username
$user = $result->fetch(PDO::FETCH_ASSOC);
$username = $user['user_name'];

// Check if the decrypted username from the GET parameters matches the username fetched from the database
if ($username != $usernameFromGet) {
    echo 'Mismatched username.';
    exit;
}

// Update the email_verify field to 1 for the matching user
$query = "UPDATE users SET email_verify = 1 WHERE user_name = ?";
$params = [$username];
$result = executeQuery($dbConn, $query, $params);

// Check if the update was successful
if ($result === false) {
    echo 'Failed to update email verification status.';
    exit;
}

// At this point, the user's email verification status has been updated
echo "User email verification status updated successfully.";

// Close the database connection
$dbConn = null; // This is how you close a PDO connection