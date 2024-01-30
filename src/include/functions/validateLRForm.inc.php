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
        // Create the SQL query
        $sql = "SELECT user_email, user_password FROM users WHERE user_email = '$email'";
        $sql2 = "SELECT user_id FROM users WHERE user_email = '$email'";

        // Open a connection using dbConnect()
        $dbConn = db_connect();

        // Execute the query
        $result = sqlsrv_query($dbConn, $sql);
        $result2 = sqlsrv_query($dbConn, $sql2);

        // Handle the query result
        if ($result) {
            // Check if a row is returned
            if (sqlsrv_num_rows($result) > 0) {
                // Fetch the data
                $row = sqlsrv_fetch_assoc($result);
                
                // Access the user_email and password values
                $userEmail = $row['user_email'];
                $userPassword = $row['user_password'];
                
                // Check if the password matches
                if ($userPassword === $password) {
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
                    $row2 = sqlsrv_fetch_assoc($result2);
                    $_SESSION['uid'] = $row2['user_id'];
                    header("Location: social.php");
                    exit;
                } else {
                    echo '<div class="bg-red-100 p-5 w-full sm:w-1/2 center top-10 absolute rounded-lg">';
                    echo '  <div class="flex space-x-3">';
                    echo '    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="flex-none fill-current text-red-500 h-4 w-4">';
                    echo '      <path d="M12 0c-6.627 0-12 5.373-12 12s5.373 12 12 12 12-5.373 12-12-5.373-12-12-12zm4.597 17.954l-4.591-4.55-4.555 4.596-1.405-1.405 4.547-4.592-4.593-4.552 1.405-1.405 4.588 4.543 4.545-4.589 1.416 1.403-4.546 4.587 4.592 4.548-1.403 1.416z" />';
                    echo '    </svg>';
                    echo '    <div class="leading-tight flex flex-col space-y-2">';
                    echo '      <div class="text-sm font-medium text-red-700">Something went wrong</div>';
                    echo '      <div class="text-sm font-small text-red-800">Password Incorrect!</div>';
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
                echo '      <div class="text-sm font-small text-red-800">Email has yet to create an account!</div>';
                echo '    </div>';
                echo '  </div>';
                echo '</div>';
            }
        } else {
                // Handle the query error
                $error = sqlsrv_error($dbConn);
                mySQLerror($error);
        }

        // Close the database connection
        sqlsrv_close($dbConn);
}

function validateRegister($username, $email, $password) {
    // Create the SQL queries
    $emailSql = "SELECT user_email FROM users WHERE user_email = '$email'";
    $usernameSql = "SELECT user_name FROM users WHERE user_name = '$username'";
    
    // Open a connection using dbConnect()
    $dbConn = db_connect();

    // Execute the email query
    $emailResult = sqlsrv_query($dbConn, $emailSql);

    // Handle the email query result
    if ($emailResult) {
        // Check if a row is returned
        if (sqlsrv_num_rows($emailResult) > 0) {
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
        // Handle the email query error
        $error = sqlsrv_error($dbConn);
        echo '<script>alert("Email query error: ' . $error . '");</script>';
        return;
    }

    // Execute the username query
    $usernameResult = sqlsrv_query($dbConn, $usernameSql);

    // Handle the username query result
    if ($usernameResult) {
        // Check if a row is returned
        if (sqlsrv_num_rows($usernameResult) > 0) {
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
        $error = sqlsrv_error($dbConn);
        mySQLerror($error);
    }

    newUser($dbConn, $email, $username, $password) ;
}
?>