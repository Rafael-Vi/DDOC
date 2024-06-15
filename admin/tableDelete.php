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

    // Proceed with deletion if the table exists and deletion is permitted
    $result = executeQuery($db_conn, "SELECT * FROM $table WHERE id_$table = ?", [$id]);

    if ($result->num_rows > 0) {
        // Data exists and can be deleted
        $result = executeQuery($db_conn, "DELETE FROM $table WHERE id_$table = ?", [$id]);
        header('Location: tableView.php?table=' . $table);
    } else {
        // Data not found
        echo "Data not found.";
    }
} else {
    header('Location: index.php');
    exit;
}

mysqli_close($db_conn);