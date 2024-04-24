<?php
// Handle the form submission
if (isset($_POST['submit'])) {
    // Check if the login form was submitted
    if ($_POST['submit'] === 'loginSubmit') {
        // Check if the email and password are not empty
        if (isset($_POST['emailL']) && isset($_POST['passwordL']) && !empty($_POST['emailL']) && !empty($_POST['passwordL'])) {
            $email = $_POST['emailL'];
            $password = $_POST['passwordL'];

            // Call the validateLogin function
            validateLogin($email, $password);
        }
    }
    // Check if the register form was submitted
    elseif ($_POST['submit'] === 'registerSubmit') {
        // Check if the username, email, and password are not empty
        if (isset($_POST['usernameR']) && isset($_POST['emailR']) && isset($_POST['passwordR']) && !empty($_POST['usernameR']) && !empty($_POST['emailR']) && !empty($_POST['passwordR'])) {
            $username = $_POST['usernameR'];
            $email = $_POST['emailR'];
            $password = $_POST['passwordR'];

            // Call the validateRegister function
            validateRegister($username, $email, $password);
        }
    }
}

// Function definitions
function validateLogin($email, $password) {
    $dbConn = db_connect();

    // Create the SQL query
    $sql = "SELECT user_email, user_password FROM users WHERE user_email = ?";
    $sql2 = "SELECT user_id, can_post FROM users WHERE user_email = ?";

/// Prepare the SQL statement
$stmt = mysqli_prepare($dbConn, $sql);
$stmt2 = mysqli_prepare($dbConn, $sql2);

// Bind parameters
mysqli_stmt_bind_param($stmt, "s", $email);
mysqli_stmt_bind_param($stmt2, "s", $email);

// Execute the query
if (!mysqli_stmt_execute($stmt)) {
    // Handle the query error
    die("Statement failed: " . mysqli_stmt_error($stmt));
}

// Bind result variables
mysqli_stmt_bind_result($stmt, $userEmail, $userPassword);
// Store the result
mysqli_stmt_store_result($stmt);

// Handle the query result
if (mysqli_stmt_fetch($stmt)) {
    // Check if the password matches
    if (password_verify($password, $userPassword)) {
        // Redirect to another page
        // After successful login
        echo '<div class="bg-green-100 p-5 w-full sm:w-1/2 center top-10 absolute rounded-lg">';
        echo '  <div class="flex space-x-3">';
        echo '    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="flex-none fill-current text-green-500 h-4 w-4">';
        echo '      <path d="M12 0c-6.627 0-12 5.373-12 12s5.373 12 12 12 12-5.373 12-12-5.373-12-12-12zm4.597 17.954l-4.591-4.55-4.555 4.596-1.405-1.405 4.547-4.592-4.593-4.552 1.405-1.405 4.588 4.543 4.545-4.589 1.416 1.403-4.546 4.587 4.592 4.548-1.403 1.416z" />';
        echo '    </svg>';
        echo '    <div class="leading-tight flex flex-col space-y-2">';
        echo '      <div class="text-sm font-medium text-green-700">Successful</div>';
        echo '      <div class="text-sm font-small text-green-800">Login Successful!</div>';
        echo '    </div>';
        echo '  </div>';
        echo '</div>';
        // Execute the second query
        if (!mysqli_stmt_execute($stmt2)) {
            // Handle the query error
            die("Statement failed: " . mysqli_stmt_error($stmt2));
        }

        // Bind result variables
        mysqli_stmt_bind_result($stmt2, $userId, $canPost);

       // Fetch the result of the second query
       if (mysqli_stmt_fetch($stmt2)) {
        $_SESSION['uid'] = $userId;
        $_SESSION['can_post'] = $canPost;
        header("Location: social.php");
        exit;
    }
    } else {
        echo '<div class="bg-red-100 p-5 w-full sm:w-1/2 center top-10 absolute rounded-lg">';
        echo '  <div class="flex space-x-3">';
        echo '    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="flex-none fill-current text-red-500 h-4 w-4">';
        echo '      <path d="M12 0c-6.627 0-12 5.373-12 12s5.373 12 12 12 12-5.373 12-12-5.373-12-12-12zm4.597 17.954l-4.591-4.55-4.555 4.596-1.405-1.405 4.547-4.592-4.593-4.552 1.405-1.405 4.588 4.543 4.545-4.589 1.416 1.403-4.546 4.587 4.592 4.548-1.403 1.416z" />';
        echo '    </svg>';
        echo '    <div class="leading-tight flex flex-col space-y-2">';
        echo '      <div class="text-sm font-medium text-red-700">Something went wrong</div>';
        echo '      <div class="text-sm font-small text-red-800">Auth Failed!!!</div>';
        echo '    </div>';
        echo '  </div>';
        echo '</div>';
    }
} else {
    echo '<div class="bg-red-100 p-5 w-full sm:w-1/2 center top-10 absolute rounded-lg">';
    echo '  <div class="flex space-x-3">';
    echo '    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="flex-none fill-current text-red-500 h-4 w-4">';
    echo '      <path d="M12 0c-6.627 0-12 5.373-12 12s5.373 12 12 12 12-5.373 12-12-5.373-12-12-12zm4.597 17.954l-4.591-4.55-4.555 4.596-1.405-1.405 4.547-4.592-4.593-4.552 1.405-1.405 4.588 4.543 4.545-4.589 1.416 1.403-4.546 4.587 4.592 4.548-1.403 1.416z" />';
    echo '    </svg>';
    echo '    <div class="leading-tight flex flex-col space-y-2">';
    echo '      <div class="text-sm font-medium text-red-700">Something went wrong</div>';
    echo '      <div class="text-sm font-small text-red-800">Auth Failed!!!</div>';
    echo '    </div>';
    echo '  </div>';
    echo '</div>';
}

// Close the database connection
mysqli_close($dbConn);
}

function validateRegister($username, $email, $password) {
// Create the SQL queries
$emailSql = "SELECT user_email FROM users WHERE user_email = ?";
$usernameSql = "SELECT user_name FROM users WHERE user_name = ?";

// Open a connection using dbConnect()
$dbConn = db_connect();

// Prepare and execute the email query
$emailStmt = mysqli_prepare($dbConn, $emailSql);
mysqli_stmt_bind_param($emailStmt, 's', $email);
mysqli_stmt_execute($emailStmt);
$emailResult = mysqli_stmt_get_result($emailStmt);

// Prepare and execute the username query
$usernameStmt = mysqli_prepare($dbConn, $usernameSql);
mysqli_stmt_bind_param($usernameStmt, 's', $username);
mysqli_stmt_execute($usernameStmt);
$usernameResult = mysqli_stmt_get_result($usernameStmt);

    // Handle the email query result
    if ($emailResult) {
        // Check if a row is returned
        if (mysqli_num_rows($emailResult) > 0) {
            // Email already exists, show alert
            echo '<div class="bg-red-100 p-5 w-full sm:w-1/2 center top-10 absolute rounded-lg">';
            echo '  <div class="flex space-x-3">';
            echo '    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="flex-none fill-current text-red-500 h-4 w-4">';
            echo '      <path d="M12 0c-6.627 0-12 5.373-12 12s5.373 12 12 12 12-5.373 12-12-5.373-12-12-12zm4.597 17.954l-4.591-4.55-4.555 4.596-1.405-1.405 4.547-4.592-4.593-4.552 1.405-1.405 4.588 4.543 4.545-4.589 1.416 1.403-4.546 4.587 4.592 4.548-1.403 1.416z" />';
            echo '    </svg>';
            echo '    <div class="leading-tight flex flex-col space-y-2">';
            echo '      <div class="text-sm font-medium text-red-700">Something went wrong</div>';
            echo '      <div class="text-sm font-small text-red-800">Email Already Registred!</div>';
            echo '    </div>';
            echo '  </div>';
            echo '</div>';
            return;
        }
    } else {
        $error = mysqli_error($dbConn);
        mySQLerror($error);
    }


    // Handle the username query result
    if ($usernameResult) {
        // Check if a row is returned
        if (mysqli_num_rows($usernameResult) > 0) {
            // Username already exists, show alert
            echo '<div class="bg-red-100 p-5 w-full sm:w-1/2 center top-10 absolute rounded-lg">';
            echo '  <div class="flex space-x-3">';
            echo '    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="flex-none fill-current text-red-500 h-4 w-4">';
            echo '      <path d="M12 0c-6.627 0-12 5.373-12 12s5.373 12 12 12 12-5.373 12-12-5.373-12-12-12zm4.597 17.954l-4.591-4.55-4.555 4.596-1.405-1.405 4.547-4.592-4.593-4.552 1.405-1.405 4.588 4.543 4.545-4.589 1.416 1.403-4.546 4.587 4.592 4.548-1.403 1.416z" />';
            echo '    </svg>';
            echo '    <div class="leading-tight flex flex-col space-y-2">';
            echo '      <div class="text-sm font-medium text-red-700">Something went wrong</div>';
            echo '      <div class="text-sm font-small text-red-800">Username taken!</div>';
            echo '    </div>';
            echo '  </div>';
            echo '</div>';
            return;
        }
    } else {
        // Handle the username query error
        $error = mysqli_error($dbConn);
        mySQLerror($error);
    }

    newUser($dbConn, $email, $username, $password) ;
}
?>