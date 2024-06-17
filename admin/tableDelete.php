<?php
require 'includes/header.inc.php';

// check if table and id are set
if (isset($_GET['table']) && !empty($_GET['table']) && isset($_GET['id']) && !empty($_GET['id'])) {
    $table = $_GET['table'];
    $id = $_GET['id']; // Ensure $id is captured from $_GET

    // Check if the table is allowed to be deleted from
    if (!isset($tablePermissions[$table]) || !$tablePermissions[$table]['deletable']) {
        header('Location: index.php');
        exit;
    }

    $db_conn = db_connect();

    $result_table = executeQuery($db_conn, "SHOW TABLES LIKE ?", [$table]);
    if ($result_table->num_rows === 0) {
        header('Location: index.php');
        exit;
    }



    if ($result->num_rows > 0) {
        // Data exists and can be deleted
        $result = executeQuery($db_conn, "DELETE FROM $table WHERE id_$table = ?", [$id]);
        header('Location: tableView.php?table=' . $table);
    } else {
        // Data not found
        echo "Data not found.";
    }

    switch ($table) {
        case 'users':
            //deleteUser($id);
            break;
        case 'theme':
                // Proceed with deletion if the table exists and deletion is permitted
                $result1 = executeQuery($db_conn, "SELECT * FROM $table WHERE id_$table = ?", [$id]);
                if ($result1 && $result1->num_rows > 0) {
                    // Data exists and can be deleted
                    $result2 = executeQuery($db_conn, "SELECT * FROM posts WHERE id_theme = ?", [$id]);
                    $result = executeQuery($db_conn, "DELETE FROM $table WHERE id_$table = ?", [$id]);
                    while ($row = mysqli_fetch_assoc($result2)) {
                        deletePost($row['post_id']);
                        $result3 = executeQuery($db_conn, "DELETE FROM likes WHERE post_id = ?", [$row['post_id']]);
                    }
                    header('Location: tableView.php?table=' . $table);
                    exit;
                } else {
                    // Data not found
                    echo "Data not found.";
                }
                break;
        case 'posts':
            if(deletePost($id) === false) {
                echo "Failed to delete post.";
            } else {
                header('Location: tableView.php?table=' . $table);
            }
            break;
        // Add more tables as needed
        default:
            header('Location: index.php');
            exit;
    }
    function deletePost($id) {
        global $arrConfig;
        $dbConn = db_connect();
    
        if ($dbConn === false) {
            $error = "ERROR: Could not connect. " . mysqli_connect_error();
            error_log($error);
            return json_encode(['success' => false, 'error' => $error]);
        }
    
        $query = "SELECT post_url, id_theme, post_type FROM posts WHERE post_id = ?";
        $stmt = mysqli_prepare($dbConn, $query);
        mysqli_stmt_bind_param($stmt, "i", $postID);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $file = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);
    
        if ($file) {
            $filePath = $arrConfig['dir_posts'] . $file['post_type'] . '/' . $file['post_url'];
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }
    
        $queries = [
            "DELETE FROM posts WHERE post_id = ?",
            "DELETE FROM likes WHERE post_id = ?"
        ];
    
        foreach ($queries as $query) {
            $stmt = mysqli_prepare($dbConn, $query);
            mysqli_stmt_bind_param($stmt, "i", $postID);
            if (!mysqli_stmt_execute($stmt)) {
                $error = "Failed to execute query: $query";
                error_log($error);
                mysqli_stmt_close($stmt);
                mysqli_close($dbConn);
                return json_encode(['success' => false, 'error' => $error]);
            }
            mysqli_stmt_close($stmt);
        }
    
        if (isset($_SESSION['themes'][0]['id_theme']) && isset($file['id_theme']) && $file['id_theme'] == $_SESSION['themes'][0]['id_theme']) {
            updateUserPostStatus($_SESSION['uid'], 0);
        }
    
        mysqli_close($dbConn);
        echo json_encode(['success' => true]);
    }
} else {
    header('Location: index.php');
    exit;
}

mysqli_close($db_conn);