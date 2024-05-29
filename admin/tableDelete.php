<?php
require 'includes/header.inc.php';

// check if table and id are set
if (isset($_GET['table']) && !empty($_GET['table']) && isset($_GET['id']) && !empty($_GET['id'])) {
    $table = $_GET['table'];
    $db_conn = db_connect();

    $result_table = executeQuery($db_conn, "SHOW TABLES LIKE '$table'");
    if ($result_table->num_rows === 0) {
        header('Location: index.php');
        exit;
    }
} else {
    header('Location: index.php');
    exit;
}

$db_conn = db_connect();

// delete data from table
$result = executeQuery($db_conn, "SELECT * FROM $table WHERE id_$table = ?", [$id]);

if ($result->num_rows > 0) {
    // data deleted successfully
    $result = executeQuery($db_conn, "DELETE FROM $table WHERE id_$table = ?", [$id]);
    header('Location: tableView.php?table=' . $table);
} else {
    // data not deleted
    echo "Data not deleted.";
}

mysqli_close($db_conn);