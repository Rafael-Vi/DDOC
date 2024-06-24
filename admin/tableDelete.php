<?php
ob_start(); // Start output buffering at the beginning of the script
require 'includes/header.inc.php';

$redirectUrl = 'index.php'; // Default redirection if table is not set

// Check if table and id are set and not empty
if (isset($_GET['table'], $_GET['id']) && !empty($_GET['table']) && !empty($_GET['id'])) {
    $table = $_GET['table'];
    $id = $_GET['id'];
    $redirectUrl = "tableView.php?table=$table"; // Set redirection to the table view

    // Check if the table is allowed to be deleted from
    if (!isset($tablePermissions[$table]) || !$tablePermissions[$table]['deletable']) {
        $_SESSION['admin_error'] = "Exclusão de registros não permitida para esta tabela.";
        header("Location: $redirectUrl");
        exit;
    } else {
        $db_conn = db_connect();

        // Validate table name against allowed tables to prevent SQL injection
        if (!array_key_exists($table, $tablePermissions)) {
            $_SESSION['admin_error'] = "Tabela inválida.";
            header("Location: $redirectUrl");
            exit;
        } else {
            // Preliminary check to see if the data exists
            $idColumnName = $table === 'posts' ? 'post_id' : "id_$table"; // Adjust ID column name based on table
            $preliminaryResult = executeQuery($db_conn, "SELECT * FROM $table WHERE $idColumnName = ?", [$id]);

            if ($preliminaryResult && $preliminaryResult->num_rows > 0) {
                // Data exists, proceed with deletion logic
                $deleteSuccess = false; // Flag to track deletion success
                switch ($table) {
                    case 'users':
                        if ($id == $_SESSION['uid']) {
                            $_SESSION['admin_error'] = "Você não pode excluir sua própria conta.";
                            header("Location: $redirectUrl");
                            exit;
                        }elseif ($id == 8) {
                            header("Location: $redirectUrl");
                            exit;
                        }
                        else{
                            $deleteSuccess = deleteUser($id, $db_conn); // Assume deleteUser returns boolean
                        }
                        break;
                    case 'theme':
                        // Additional deletion logic for theme
                        $result2 = executeQuery($db_conn, "SELECT post_id FROM posts WHERE id_theme = ?", [$id]);
                        while ($row = mysqli_fetch_assoc($result2)) {
                            deletePost($row['post_id'], $db_conn); // Assume deletePost handles its own success/failure internally
                        }
                        $deleteSuccess = true; // Assume success if we've reached this point
                        break;
                    case 'posts':
                        $deleteSuccess = deletePost($id, $db_conn); // Assume deletePost returns boolean
                        break;
                    default:
                        // Generic deletion for other tables
                        $result = executeQuery($db_conn, "DELETE FROM $table WHERE $idColumnName = ?", [$id]);
                        $deleteSuccess = $result ? true : false;
                        break;
                }

                // Set session message based on deletion success
                if ($deleteSuccess) {
                    $_SESSION['admin_success'] = "Registro excluído com sucesso.";
                } else {
                    $_SESSION['admin_error'] = "Erro na exclusão do registro.";
                }
            } else {
                // Data not found
                $_SESSION['admin_error'] = "Nenhum registro encontrado para exclusão.";
            }
        }
    }
}

header("Location: $redirectUrl");
exit;
ob_end_flush(); // End output buffering and flush output before redirecting

function deletePost($id, $dbConn) {
    global $arrConfig;

    // Start transaction
    mysqli_begin_transaction($dbConn);

    try {
        // Fetch post details
        $query = "SELECT post_url, id_theme, post_type, id_users FROM posts WHERE post_id = ?";
        $stmt = mysqli_prepare($dbConn, $query);
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $file = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);

        if ($file) {
            // Delete the file associated with the post
            $filePath = $arrConfig['dir_posts'] . $file['post_type'] . '/' . $file['post_url'];
            if (file_exists($filePath)) {
                unlink($filePath);
            }

            // Check the theme's is_finished status
            $query = "SELECT is_finished FROM theme WHERE id_theme = ?";
            $stmt = mysqli_prepare($dbConn, $query);
            mysqli_stmt_bind_param($stmt, "i", $file['id_theme']);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $theme = mysqli_fetch_assoc($result);
            mysqli_stmt_close($stmt);

            if ($theme && $theme['is_finished'] == 0) {
                // Update usercanpost if the theme is not finished
                updateUserPostStatus($file['id_users'], 0); 
            }
        }

        // Delete the post and likes
        $queries = [
            "DELETE FROM posts WHERE post_id = ?",
            "DELETE FROM likes WHERE post_id = ?",
            "DELETE FROM report WHERE post_id = ?"
        ];

        foreach ($queries as $query) {
            $stmt = mysqli_prepare($dbConn, $query);
            mysqli_stmt_bind_param($stmt, "i", $id);
            if (!mysqli_stmt_execute($stmt)) {
                throw new Exception("Failed to delete post or likes");
            }
            mysqli_stmt_close($stmt);
        }

        // Commit transaction
        mysqli_commit($dbConn);
        return true;
    } catch (Exception $e) {
        // Rollback transaction on error
        mysqli_rollback($dbConn);
        error_log($e->getMessage()); // Log error
        $_SESSION['error'] = "An error occurred. Please try again."; // Set error message in session
        return false;
    }
}

function deleteUser($id) {
    $dbConn = db_connect(); // Assuming db_connect() is a function that returns a database connection

    // Start transaction
    mysqli_begin_transaction($dbConn);

    try {
        // Delete all notifications where receiver_id is the $id
        $query = "DELETE FROM notifications WHERE receiver_id = ?";
        $params = [$id];
        if (!executeQuery($dbConn, $query, $params)) {
            throw new Exception("Failed to delete notifications");
        }

        // Delete all from follow table where follower_id or followee_id = $id
        $queries = [
            "DELETE FROM follow WHERE follower_id = ?",
            "DELETE FROM follow WHERE followee_id = ?"
        ];

        foreach ($queries as $query) {
            if (!executeQuery($dbConn, $query, $params)) {
                throw new Exception("Failed to delete follow records");
            }
        }

        // Delete all messages where the user is either the sender or receiver
        $query = "DELETE FROM messages WHERE messenger_id = ? OR receiver_id = ?";
        $params = [$id, $id]; // Note: Adjusted to match both conditions
        if (!executeQuery($dbConn, $query, $params)) {
            throw new Exception("Failed to delete messages");
        }

        // Select all posts where id_users = $id
        $query = "SELECT post_id FROM posts WHERE id_users = ?";
        $params = [$id]; 
        $result = executeQuery($dbConn, $query, $params);
        if ($result !== false) {
            while ($row = mysqli_fetch_assoc($result)) {
                // For each post, call the deletePost function
                deletePost($row['post_id'], $dbConn);
            }
        }

        // NEW STEP: Delete every report where sender = $id
        $query = "DELETE FROM report WHERE sender = ?";
        if (!executeQuery($dbConn, $query, [$id])) {
            throw new Exception("Failed to delete reports");
        }

        // After handling all related records, delete the user
        $query = "DELETE FROM users WHERE id_users = ?";
        if (!executeQuery($dbConn, $query, [$id])) { // Adjusted params to a direct array
            throw new Exception("Failed to delete user");
        }

        // Commit transaction
        mysqli_commit($dbConn);
    } catch (Exception $e) {
        // Rollback transaction on error
        mysqli_rollback($dbConn);
        error_log($e->getMessage()); // Log error
        $_SESSION['error'] = "An error occurred. Please try again."; // Set error message in session
        return false;
    } finally {
        // Close the database connection
        mysqli_close($dbConn);
    }

    return true;
}

function updateUserPostStatus($userId , $status) {
    $dbConn = db_connect(); // Assuming db_connect() is a function that returns a database connection

    // Prepare the SQL query to update the table
    $sql = "UPDATE users SET can_post = ? WHERE id_users = ?";
    $params = array($status,$userId);

    // Execute the query
    $result = executeQuery($dbConn, $sql, $params);

    if ($result) {
        $_SESSION['can_post'] = $status;
    } else {
        // Handle the update error
        error_log("Error updating user: " . mysqli_error($dbConn)); // Log error
        $_SESSION['error'] = "An error occurred updating user post status. Please try again."; // Set error message in session
    }

    // Close the database connection
    mysqli_close($dbConn);
}