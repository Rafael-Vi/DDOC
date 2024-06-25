<?php

// Include your SQLfunctions.inc.php file
require_once '/var/www/DDOC/src/include/config.inc.php';

// Initialize an array to hold error messages
$errorMessages = [];

// Get the encrypted email and username from the GET parameters
$encryptedEmail = $_GET['email'];
$encryptedUsername = $_GET['username']; // Now expecting the username to be encrypted as well

// Attempt to decrypt both the email and username
try {
    $email = decrypt($encryptedEmail); // Decrypt the email
    $usernameFromGet = decrypt($encryptedUsername); // Decrypt the username
} catch (Exception $e) {
    // Log decryption errors
    error_log("Decryption error: " . $e->getMessage());
    $errorMessages[] = 'Error decrypting email or username.';
}

echo $email;
echo $usernameFromGet;

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

// Only print the decrypted values if there are no errors
print($email);
print($usernameFromGet);

// Connect to the database
try {
    $dbConn = db_connect();
} catch (Exception $e) {
    error_log("Database connection error: " . $e->getMessage());
    echo "Failed to connect to the database.";
    exit;
}

// Prepare the query to fetch the username based on the email
$query = "SELECT user_name FROM users WHERE user_email = ?";
$params = [$email];
try {
    $result = executeQuery($dbConn, $query, $params);
} catch (Exception $e) {
    error_log("Query execution error: " . $e->getMessage());
    echo "Failed to execute query.";
    exit;
}

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
try {
    $result = executeQuery($dbConn, $query, $params);
} catch (Exception $e) {
    error_log("Update query execution error: " . $e->getMessage());
    echo "Failed to update email verification status.";
    exit;
}

// Check if the update was successful
if ($result === false) {
    echo 'Failed to update email verification status.';
    exit;
}

echo "User email verification status updated successfully.";

// Close the database connection
$dbConn = null;