<?php
require 'includes/header.inc.php';

// check if table and id are set
if (isset($_GET['table'], $_GET['id']) && !empty($_GET['table']) && !empty($_GET['id'])) {
    $table = $_GET['table'];
    $id = $_GET['id'];

    // Check if the table is allowed to be deleted from
    if (!isset($tablePermissions[$table]) || !$tablePermissions[$table]['deletable']) {
        $_SESSION['admin_error'] = "Exclusão de registros não permitida para esta tabela.";
        header('Location: index.php');
        exit;
    }

    $db_conn = db_connect();

    // Validate table name against allowed tables to prevent SQL injection
    if (!array_key_exists($table, $tablePermissions)) {
        header('Location: index.php');
        exit;
    }
    
    // Preliminary check to see if the data exists
    $idColumnName = $table === 'posts' ? 'post_id' : "id_$table"; // Adjust ID column name based on table
    $preliminaryResult = executeQuery($db_conn, "SELECT * FROM $table WHERE $idColumnName = ?", [$id]);
    
    if ($preliminaryResult && $preliminaryResult->num_rows > 0) {
        // Data exists, proceed with deletion logic
        switch ($table) {
            case 'users':
                // Custom deletion logic for users
                $result2 = executeQuery($db_conn, "SELECT post_id FROM posts WHERE id_users = ?", [$id]);
                while ($row = mysqli_fetch_assoc($result2)) {
                    deletePost($row['post_id'], $db_conn); // Pass $db_conn as an argument
                }
                break;
            case 'theme':
                // Additional deletion logic for theme
                $result2 = executeQuery($db_conn, "SELECT post_id FROM posts WHERE id_theme = ?", [$id]);
                while ($row = mysqli_fetch_assoc($result2)) {
                    deletePost($row['post_id'], $db_conn); // Pass $db_conn as an argument
                }
                break;
            case 'posts':
                if (deletePost($id, $db_conn) === false) { // Pass $db_conn as an argument
                    $_SESSION['admin_error'] = "Erro na exclusão do registro.";
                    header('Location: tableView.php?table=' . $table);
                    exit;
                }
                $_SESSION['admin_success'] = "Registro excluído com sucesso.";
                break;
            // Add more tables as needed
            default:
                // Proceed with deletion if the table exists and deletion is permitted
                $result = executeQuery($db_conn, "SELECT * FROM $table WHERE id_$table = ?", [$id]);
                break;
        }
        // Execute deletion query
        $result = executeQuery($db_conn, "DELETE FROM $table WHERE $idColumnName = ?", [$id]);
        if ($result) {
            $_SESSION['admin_success'] = "Registro excluído com sucesso.";
        } else {
            $_SESSION['admin_error'] = "Erro na exclusão do registro.";
        }
        header('Location: tableView.php?table=' . $table);
        exit;
    } else {
        // Data not found
        $_SESSION['admin_error'] = "Nenhum registro encontrado para exclusão.";
        header('Location: tableView.php?table=' . $table);
        exit;
    }
} else {
    header('Location: index.php');
    exit;
}

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
        error_log($e->getMessage()); // Log error or handle it as per your requirement
        return false;
    }
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
        echo "Error updating user: " . mysqli_error($dbConn);
    }

    // Close the database connection
    mysqli_close($dbConn);
}