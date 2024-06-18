<?php
require 'includes/header.inc.php';

// Define allowed tables and their deletable status
$tablePermissions = [
    'users' => ['deletable' => true],
    'theme' => ['deletable' => true],
    'posts' => ['deletable' => true],
    // Add more tables as needed
];

// check if table and id are set
if (isset($_GET['table'], $_GET['id']) && !empty($_GET['table']) && !empty($_GET['id'])) {
    $table = $_GET['table'];
    $id = $_GET['id'];

    // Check if the table is allowed to be deleted from
    if (!isset($tablePermissions[$table]) || !$tablePermissions[$table]['deletable']) {
        header('Location: index.php');
        exit;
    }

    $db_conn = db_connect();

    // Validate table name against allowed tables to prevent SQL injection
    if (!array_key_exists($table, $tablePermissions)) {
        header('Location: index.php');
        exit;
    }


    if ($result && $result->num_rows > 0) {
        // Data exists and can be deleted
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
                    echo "Failed to delete post.";
                    exit;
                }
                break;
            // Add more tables as needed
            default:
                // Proceed with deletion if the table exists and deletion is permitted
                $result = executeQuery($db_conn, "SELECT * FROM $table WHERE id_$table = ?", [$id]);
                break;
        }
        // Execute deletion query
        $result = executeQuery($db_conn, "DELETE FROM $table WHERE id_$table = ?", [$id]);
        header('Location: tableView.php?table=' . $table);
        exit;
    } else {
        // Data not found
        echo "Data not found.";
    }
} else {
    header('Location: index.php');
    exit;
}

function deletePost($id, $dbConn) { // Include $dbConn as a parameter
    global $arrConfig;

    $query = "SELECT post_url, id_theme, post_type FROM posts WHERE post_id = ?";
    $stmt = mysqli_prepare($dbConn, $query);
    mysqli_stmt_bind_param($stmt, "i", $id);
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
        "DELETE FROM likes WHERE post_id = ?",
    ];

    foreach ($queries as $query) {
        $stmt = mysqli_prepare($dbConn, $query);
        mysqli_stmt_bind_param($stmt, "i", $id);
        if (!mysqli_stmt_execute($stmt)) {
            mysqli_stmt_close($stmt);
            return false;
        }
        mysqli_stmt_close($stmt);
    }

    return true;
}

mysqli_close($db_conn);