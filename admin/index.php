<?php
    require 'includes/header.inc.php';
?>
<div class="w-screen h-full flex flex-wrap items-start gap-2 py-6 px-2 justify-center">
    <?php
    $db_conn = db_connect();

    // Get all table names in the database
    $result = executeQuery($db_conn, "SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = ? AND TABLE_TYPE = 'BASE TABLE'", [$arrConfig['connect_DB'][3]]);

    while ($row = $result->fetch_row()) {
        $table = $row[0];

        // Only proceed if the table is mentioned in $tablePermissions and display is set to true
        if (isset($tablePermissions[$table])) {
            // Get comment for the current table
            $commentResult = executeQuery($db_conn, "SELECT TABLE_COMMENT FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ?", [$arrConfig['connect_DB'][3], $table]);

            $table_comment = '';
            if ($commentRow = $commentResult->fetch_assoc()) {
                $table_comment = $commentRow['TABLE_COMMENT'];
            }

            echo '
                <div class="card w-96 bg-base-100">
                    <div class="card-body">
                        <h2 class="card-title">Tabela ' . $table . '</h2>
                        <p>' . htmlspecialchars($table_comment) . '</p>
                        <div class="card-actions justify-end">
                            <a href="tableView.php?table=' . $table . '" class="btn btn-warning">
                                <i class="fi fi-br-blog-pencil"></i>
                                Edit
                            </a>
                        </div>
                    </div>
                </div>
            ';
        }
    }

    mysqli_close($db_conn);
    ?>
</div>