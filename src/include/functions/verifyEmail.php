<?php

// Include your SQLfunctions.inc.php file
require_once '/var/www/DDOC/src/include/config.inc.php';

// Initialize an array to hold error messages
$errorMessages = [];
$operationStatus = 'error'; // Initialize operation status

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
if ($result === false) {
    echo 'Failed to update email verification status.';
    $operationStatus = 'error'; // Update operation status to error
    exit;
} else {
    $operationStatus = 'success'; // Update operation status to success
}

// Fetch the username
$user = $result->fetch_assoc(); // Adjusted for MySQLi
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

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/social.css">    
    <link rel="stylesheet" href="../css/errorPages.css">    
    <link rel="shortcut icon" href="../assets/images/2.png" >
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/2.2.0/uicons-solid-rounded/css/uicons-solid-rounded.css'>
    <script src="https://cdn.tailwindcss.com"></script> <link href="https://cdn.jsdelivr.net/npm/daisyui@4.7.2/dist/full.min.css" rel="stylesheet" type="text/css" />
    <title>Validar email</title>
</head>
<body class="bg-gray-800">
<div class="fixed inset-0 flex-row">
    <div class="fixed inset-0 z-50 bg-gray-800 flex items-center justify-center flex-col">
      <div class="flex items-center justify-center flex-row">
        <p class=" text-7xl text-white ubuntu-bold">D</p>
        <p class=" text-7xl mx-1 text-white ubuntu-bold">D</p>
        <div class="loader  border-t-8 rounded-full border-t-red-400 bg-orange-300 animate-spin
        aspect-square w-24 flex justify-center items-center text-yellow-700 shadow-sm shadow-black"></div>
        <p class="mx-1 my-8 text-7xl  text-white ubuntu-bold">C</p>
      </div>
      <?php if ($operationStatus == 'success'): ?>
        <h1 class="ubuntu-bold text-2xl sm:text-4xl text-white p-2 rounded">Email validado com sucesso</h1>
        <h1 class="ubuntu-bold text-xl sm:text-4xl text-white p-2 rounded">Pode fechar esta página</h1>
      <?php else: ?>
        <h1 class="ubuntu-bold text-2xl sm:text-4xl text-white p-2 rounded">Algum erro ocorreu!!</h1>
      <?php endif; ?>

</div>
</body>
</html>