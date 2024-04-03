<?php
@session_start();

global $arrConfig;
if($_SERVER['HTTP_HOST'] == 'web.colgaia.local') {
    $arrConfig['servername'] = 'localhost';
    $arrConfig['username'] = '12itm124';
    $arrConfig['password'] = '12itm124654b57cf2e691';
    $arrConfig['dbname'] = '12itm124_frontoffice_proj';
    
} elseif($_SERVER['HTTP_HOST'] == 'localhost') {

    $arrConfig['servername'] = 'localhost';
    $arrConfig['username'] = 'root';
    $arrConfig['password'] = '';
    $arrConfig['dbname'] = '12itm124_frontoffice_proj';
}


$arrConfig['conn'] =  new mysqli($arrConfig['servername'], $arrConfig['username'], $arrConfig['password'], $arrConfig['dbname']);


if (isset($_POST['submit'])) {

    if ($_POST['submit'] === 'LoginUser') {

        if (!empty($_POST['usernameL']) && !empty($_POST['passwordL'])) {
            $username = $_POST['usernameL'];
            $password = $_POST['passwordL'];

            validateLogin($username, $password);
        }
    }

    elseif ($_POST['submit'] === 'RegisterUser') {

        if (!empty($_POST['usernameR']) && !empty($_POST['emailR']) && !empty($_POST['passwordR'])) {
            $username = $_POST['usernameR'];
            $email = $_POST['emailR'];
            $password = $_POST['passwordR'];
            validateRegister($username, $email, $password);
        }
    }
}

// Function definitions
function validateLogin($username, $password) {
    echo "4";
    global $arrConfig;

        $sql = "SELECT username, password, super_user FROM admins WHERE username = '$username'";
        $sql2 = "SELECT id FROM admins WHERE username = '$username'";


        $dbConn = $arrConfig['conn'];
        var_dump($dbConn);

        $result = mysqli_query($dbConn, $sql);
        $result2 = mysqli_query($dbConn, $sql2);


        if ($result) {

            if (mysqli_num_rows($result) > 0) {

                $row = mysqli_fetch_assoc($result);
                

                $username = $row['username'];
                $userPassword = $row['password'];
                $userSuperUser = $row['super_user'];
                

                if ($userPassword === $password) {

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
                   
                    $row2 = mysqli_fetch_assoc($result2);
                    $_SESSION['uid'] = $row2['id'];
                    echo $_SESSION['uid'] ;
                    $_SESSION['username'] = $username;
                    echo $_SESSION['username'];
                    $_SESSION['super_user'] = $userSuperUser;
                    echo $_SESSION['super_user'];
                    header("Location: ../../home.php");
                    exit();
                } else {
                    echo "<script>alert('Auth Failed!');</script>";
                    header("Location: ../../index.php");
                    exit();
                }
            } else {
                echo "<script>alert('No user!');</script>";
                header("Location: ../../index.php");
                exit();
            }
        } else {
                echo "Error: " . mysqli_error($dbConn);  
        }

        mysqli_close($dbConn);
}

@session_start();

global $arrConfig;
$arrConfig['servername'] = 'localhost';
$arrConfig['username'] = 'root';
$arrConfig['password'] = '';
$arrConfig['dbname'] = '12itm124_frontoffice_proj';
$arrConfig['conn'] =  new mysqli($arrConfig['servername'], $arrConfig['username'], $arrConfig['password'], $arrConfig['dbname']);


if (isset($_POST['submit'])) {

    if ($_POST['submit'] === 'LoginUser') {

        if (!empty($_POST['usernameL']) && !empty($_POST['passwordL'])) {
            $username = $_POST['usernameL'];
            $password = $_POST['passwordL'];

            validateLogin($username, $password);
        }
    }

    elseif ($_POST['submit'] === 'RegisterUser') {

        if (!empty($_POST['usernameR'])&& !empty($_POST['passwordR'])) {
            $username = $_POST['usernameR'];
            $password = $_POST['passwordR'];
            validateRegister($username, $password);
        }
    }
}

// Function definitions
function validateRegister($username, $password) {
    global $arrConfig;

    $sqlCheck = "SELECT username FROM admins WHERE username = '$username'";


    $dbConn = $arrConfig['conn'];


    $resultCheck = mysqli_query($dbConn, $sqlCheck);

    if (mysqli_num_rows($resultCheck) > 0) {
        echo "<script>alert('Username already exists!');</script>";
        return;
    }

 
    $sql = "INSERT INTO admins (username, password) VALUES ('$username', '$password')";

    // Execute the query
    $result = mysqli_query($dbConn, $sql);

    // Handle the query result
    if ($result) {
        echo "<script>alert('Registration Successful!');</script>";
        header("Location: ./home.php");
        exit();
    } else {
        echo "Error: " . mysqli_error($dbConn);
    }

    mysqli_close($dbConn);
}
