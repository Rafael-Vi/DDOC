<?php
require 'includes/header.inc.php';

// check if table is set
if (isset($_GET['table']) && !empty($_GET['table'])) {
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


// get table columns
$result = executeQuery($db_conn, "SELECT COLUMN_NAME, COLUMN_TYPE FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = '$table' AND TABLE_SCHEMA = '" . $arrConfig['connect_DB'][3] . "'");

$columns = [];
$column_types = [];
while ($row = $result->fetch_assoc()) {
    $columns[] = $row['COLUMN_NAME'];
    $column_types[$row['COLUMN_NAME']] = strtoupper($row['COLUMN_TYPE']);

    if (str_contains($column_types[$row['COLUMN_NAME']], 'VARCHAR') && $column_types[$row['COLUMN_NAME']] !== 'VARCHAR(7)') {
        $column_types[$row['COLUMN_NAME']] = 'VARCHAR';
    }
}

// get table data
$result = executeQuery($db_conn, "SELECT * FROM $table");

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

mysqli_close($db_conn);
?>
<div class="w-screen p-2 flex items-center justify-center">
    <div class="card w-[95%] min-h-[90vh] bg-base-100 shadow-xl relative">
        <div class="card-body max-h-[80vh]">
            <div class="overflow-x-auto">
                <table class="table">
                    <!-- head -->
                    <thead>
                        <tr>
                            <?php
                            foreach ($columns as $column) {
                                echo '<th>' . $column . '</th>';
                            }
                            ?>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($data as $row) {
                            echo '<tr>';
                            foreach ($columns as $column) {
                                echo '<td>' . $row[$column] . '</td>';
                            }
                            echo '
                                <td class="flex justify-end gap-2 m-auto">
                                    <a href="tableEdit.php?table=' . $table . '&id=' . $row['id_' . $table] . '" class="btn btn-sm">
                                        <i class="fi fi-br-blog-pencil"></i>
                                    </a>
                                    <a href="tableDelete.php?table=' . $table . '&id=' . $row['id_' . $table] . '" class="btn btn-sm" onclick="return confirm(\'Are you sure you want to delete this item?\');">
                                        <i class="fi fi-br-trash"></i>
                                    </a>
                                </td>
                            ';
                            echo '</tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            <div class="absolute right-10 bottom-10">
                <a href="tableAdd.php?table=<?php echo $table ?>" class="btn btn-lg btn-circle">
                    <i class="fi fi-br-plus mt-1"></i>
                </a>
            </div>
        </div>
    </div>
</div>
<?php
    require 'includes/footer.inc.php';