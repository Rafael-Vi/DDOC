<?php
    
    //*SQL Commands for User Info
    function newUser($dbConn, $email, $username, $password){
                // Insert into the table
        $insertSql = "INSERT INTO users (user_email, user_name, user_password) VALUES ('$email', '$username', '$password')";
        $insertResult = mysqli_query($dbConn, $insertSql);

        if ($insertResult) {
            // Registration successful, redirect to another page
            echo '<div class="bg-green-100 p-5 w-full sm:w-1/2 center top-10 absolute rounded-lg">';
            echo '  <div class="flex space-x-3">';
            echo '    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="flex-none fill-current text-green-500 h-4 w-4">';
            echo '      <path d="M12 0c-6.627 0-12 5.373-12 12s5.373 12 12 12 12-5.373 12-12-5.373-12-12-12zm4.597 17.954l-4.591-4.55-4.555 4.596-1.405-1.405 4.547-4.592-4.593-4.552 1.405-1.405 4.588 4.543 4.545-4.589 1.416 1.403-4.546 4.587 4.592 4.548-1.403 1.416z" />';
            echo '    </svg>';
            echo '    <div class="leading-tight flex flex-col space-y-2">';
            echo '      <div class="text-sm font-medium text-green-700">Successful</div>';
            echo '      <div class="text-sm font-small text-green-800">User Registered with success!</div>';
            echo '      <div class="text-sm font-small text-green-800">Login to enter the app</div>';
            echo '    </div>';
            echo '  </div>';
            echo '</div>';
        } else {
            // Handle the insert error
            $error = mysqli_error($dbConn);
            echo '<div class="bg-red-100 p-5 w-full sm:w-1/2 center top-10 absolute rounded-lg">';
            echo '  <div class="flex space-x-3">';
            echo '    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="flex-none fill-current text-red-500 h-4 w-4">';
            echo '      <path d="M12 0c-6.627 0-12 5.373-12 12s5.373 12 12 12 12-5.373 12-12-5.373-12-12-12zm4.597 17.954l-4.591-4.55-4.555 4.596-1.405-1.405 4.547-4.592-4.593-4.552 1.405-1.405 4.588 4.543 4.545-4.589 1.416 1.403-4.546 4.587 4.592 4.548-1.403 1.416z" />';
            echo '    </svg>';
            echo '    <div class="leading-tight flex flex-col space-y-2">';
            echo '      <div class="text-sm font-medium text-red-700">Something went wrong</div>';
            echo '      <div class="text-sm font-small text-red-800">'.$error.'</div>';
            echo '    </div>';
            echo '  </div>';
            echo '</div>';
        }

        // Close the database connection
        mysqli_close($dbConn);
    }
    function updateUser(){
    }

    //* SQL Commands For Posts
    function createPost(){
    }

    function updatePost(){
    }

    function deletePost(){
    }

    function getUserAndName(){
    }

    function getUserInfo($uid){

        // Start the database connection
        $dbConn = db_connect();

        // Prepare the SQL query with the user_id condition
        $sql = "SELECT user_name, user_email, user_profilePic, user_realName, user_biography FROM users WHERE user_id = '$uid';";

        // Execute the query
        $result = mysqli_query($dbConn, $sql);

        // Check if the query was successful
        if ($result) {
            // Fetch the user data
            $userData = mysqli_fetch_assoc($result);
            
            // Access the user data
            $username = $userData['user_name'];
            $email = $userData['user_email'];
            $profilePic = $userData['user_profilePic'];
            // Create the data URI
            $dataUri = 'data:image;base64,' . base64_encode($profilePic);
            $realName = $userData['user_realName'];
            $biography = $userData['user_biography'];
            $_SESSION['imageProfile'] = $dataUri;

            echo '<div class="flex flex-col h-32 lg:h-64 mt-8 w-4/6 float-none lg:float-right mr-8">';
            echo '<div class="h-full w-full mt-0 lg:mt-4 mb-4 float-right mr-8 text-right">';
            echo '<span class="block font-bold text-3xl mt-12 text-orange-500 lg:float-right mb-4">@' . $username . '</span>';
            echo '<div class="font-bold">' . $realName . '</div>';
            echo '<div class="w-full h-full">' . $biography . '</div>';
            echo '</div>';
            echo '</div>';
            echo '<div class="relative mt-8 mb-8 flex-grow-1">';
            echo '<div class="absolute top-0 border-l-8 border-orange-500 border-solid rounded-lg h-full lg:ml-auto"></div>';
            echo '<img src="' . $dataUri . '" alt="Profile Picture" class="rounded-full w-32 h-32 lg:w-56 lg:h-56 mt-4 ml-8 mr-10 lg:ml-3/5 sm:mr-8 lg:mr-3/5">';
            echo '</div>';

        } else {
            // Handle the query error
            $error = mysqli_error($dbConn);
            echo "Error: $error";
        }

        // Close the database connection
        mysqli_close($dbConn);
    }
?>