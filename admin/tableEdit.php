<?php
require 'includes/header.inc.php';

// check if table is set
if (isset($_GET['table']) && !empty($_GET['table']) && isset($_GET['id']) && !empty($_GET['id'])) {
    $table = $_GET['table'];
    $db_conn = db_connect();

    $result_table = executeQuery($db_conn, "SHOW TABLES LIKE '$table'");
    if ($result_table->num_rows === 0) {
        header('Location: index.php');
        exit;
    }

    // Check if the table is allowed to be deleted from
    if (!isset($tablePermissions[$table]) || !$tablePermissions[$table]['editable']) {
        header('Location: index.php');
        exit;
    }

    $result = executeQuery($db_conn, "SELECT * FROM $table WHERE id_$table = ?", [$_GET['id']]);    
    if ($result->num_rows === 0) {
        header('Location: tableView.php?table=' . $table);
        exit;
    }
} else {
    header('Location: index.php');
    exit;
}

$db_conn = db_connect();

// get table columns
$result = executeQuery($db_conn, "SELECT COLUMN_NAME, COLUMN_TYPE, COLUMN_COMMENT FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = '$table' AND TABLE_SCHEMA = '" . $arrConfig['connect_DB'][3] . "'");

$foreign_keys_result = executeQuery($db_conn, "SELECT COLUMN_NAME, REFERENCED_TABLE_NAME, REFERENCED_COLUMN_NAME FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE WHERE TABLE_NAME = '$table' AND TABLE_SCHEMA = '" . $arrConfig['connect_DB'][3] . "' AND REFERENCED_TABLE_NAME IS NOT NULL");
$foreign_keys = [];
while ($row = $foreign_keys_result->fetch_assoc()) {
    $foreign_keys[$row['COLUMN_NAME']] = ['table' => $row['REFERENCED_TABLE_NAME'], 'column' => $row['REFERENCED_COLUMN_NAME']];
}

$columns = [];
$column_types = [];
$column_comments = [];
while ($row = $result->fetch_assoc()) {
    $columns[] = $row['COLUMN_NAME'];
    $column_types[$row['COLUMN_NAME']] = strtoupper($row['COLUMN_TYPE']);
    $column_comments[$row['COLUMN_NAME']] = $row['COLUMN_COMMENT'];

    if (str_contains($column_types[$row['COLUMN_NAME']], 'VARCHAR') && $column_types[$row['COLUMN_NAME']] !== 'VARCHAR(7)') {
        $column_types[$row['COLUMN_NAME']] = 'VARCHAR';
    }
}

$primary_key_result = executeQuery($db_conn, "SHOW KEYS FROM $table WHERE Key_name = 'PRIMARY'");
$primary_key = $primary_key_result->fetch_assoc()['Column_name'];

$id = $_GET['id'];

// get table data
$result = executeQuery($db_conn, "SELECT * FROM $table WHERE id_$table = ?", [$_GET['id']]);

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $update_data = [];
    foreach ($columns as $column) {
        if (isset($_POST[$column]) || isset($_POST[$column . '_date']) && isset($_POST[$column . '_time']) || $column_types[$column] === "TINYINT(1)" ) {
            if ($column_types[$column] === 'DATETIME') {
                $date = $_POST[$column . '_date'];
                $time = $_POST[$column . '_time'];
                $datetime = date_format(date_create($date . ' ' . $time), 'Y-m-d H:i:s');
                $update_data[$column] = $datetime;
            } else if ($column_types[$column] === 'TINYINT(1)') {
                $update_data[$column] = isset($_POST[$column]) ? 1 : 0;
            } else {
                if (in_array($column, $foreign_keys) && $_POST[$column] == "") {
                    $update_data[$column] = "NULL";
                } else {
                    $update_data[$column] = $_POST[$column];
                }
            }
        }
    }
    if (!empty($update_data)) {
        $update_query = "UPDATE $table SET ";
        foreach ($update_data as $column => $value) {
            if ($value === "NULL") {
                $update_query .= "$column = NULL, ";
            } else {
                $update_query .= "$column = '$value', ";
            }
        }
        $update_query = rtrim($update_query, ', ');
        $update_query .= " WHERE id_$table = ?";
        executeQuery($db_conn, $update_query, [$_GET['id']]);
    }
    header("Location: tableView.php?table=$table");
}
?>
<div class="w-screen h-[90vh] p-2 flex items-center justify-center">
    <div class="card w-96 bg-base-100 shadow-xl">
        <form class="card-body max-h-[80vh]" action="" method="post">
            <div class="overflow-auto">
                <?php
               foreach ($columns as $column) {
                $type = $column_types[$column];
                $comment = $column_comments[$column]; // Use a different variable for the comment yes
                $value = $column == $primary_key ? $id : $data[0][$column];
                $disabled = $column == $primary_key ? ' disabled' : '';
            
                if (isset($foreign_keys[$column])) {
                    $ref_table = $foreign_keys[$column]['table'];
                    $ref_column = $foreign_keys[$column]['column'];
                    
                    $columns_result = executeQuery($db_conn, "SHOW COLUMNS FROM $ref_table");
                    while ($row = $columns_result->fetch_assoc()) {
                        if ($row['Field'] == $ref_column) {
                            break;
                        }
                    }
                    $next_column = $columns_result->fetch_assoc()['Field'];
                    $ref_result = executeQuery($db_conn, "SELECT $ref_column, $next_column FROM $ref_table");
                    echo '<div class="form-control w-full max-w-xs">
                            <label for="' . $column . '" class="label font-bold">' . $column . '</label>
                            <select id="' . $column . '" name="' . $column . '" class="select select-bordered">
                                <option value="NULL">Selecione uma opção</option>';
                    while ($row = $ref_result->fetch_assoc()) {
                        $selected = $row[$ref_column] == $data[0][$column] ? ' selected' : '';
                        echo '<option value="' . $row[$ref_column] . '"' . $selected . '>' . $row[$ref_column] . '-' . $row[$next_column] . '</option>';
                    }
                    echo '</select></div>';
                } else {
                    switch ($type) {
                    case 'VARCHAR':
                    case 'INT':
                    case 'DOUBLE':
                        echo '
                            <div class="form-control w-full max-w-xs">
                                <label for="' . $column . '" class="label font-bold">' . $column . '</label>
                                <input type="text" id="' . $column . '" name="' . $column . '" class="input input-bordered" value="' . $value . '" placeholder="Insira o valor para o campo"' . $disabled . '>
                            </div>
                        ';
                        break;
                    case 'TEXT':
                        echo '
                            <div class="form-control w-full max-w-xs">
                                <label for="' . $column . '" class="label font-bold">' . $column . '</label>
                                <textarea id="' . $column . '" name="' . $column . '" class="input input-bordered"' . $disabled . '>' . $value . '</textarea>
                            </div>
                        ';
                        break;
                    case 'TINYINT(1)':
                        $checked = $value ? ' checked' : '';
                        echo '
                            <div class="form-control w-full max-w-xs pt-6">
                                <label for="' . $column . '" class="label cursor-pointer">
                                    <span>' . $column . '</span>
                                    <input type="checkbox" id="' . $column . '" name="' . $column . '" class="checkbox"' . $checked . $disabled . '>
                                </label>
                            </div>
                        ';
                        break;
                    case 'DATETIME':
                        list($date, $time) = explode(' ', $value);
                        echo '
                            <div class="form-control w-full max-w-xs">
                                <label for="' . $column . '_date" class="label font-bold">' . $column . '</label>
                                <input type="date" id="' . $column . '_date" name="' . $column . '_date" class="input input-bordered" value="' . $date . '"' . $disabled . '>
                                <input type="time" id="' . $column . '_time" name="' . $column . '_time" class="input input-bordered" value="' . $time . '"' . $disabled . '>
                            </div>
                        ';
                        break;
                    case 'VARCHAR(7)':
                        echo '
                            <div class="form-control w-full max-w-xs">
                                <label for="' . $column . '" class="label font-bold">' . $column . '</label>
                                <input type="color" id="' . $column . '" name="' . $column . '" class="" value="' . $value . '"' . $disabled . '>
                            </div>
                        ';
                        break;
                    }
                }
            }
                ?>
            </div>
            <div class="divider"></div>
            <div class="form-control w-full max-w-xs">
                <button type="submit" class="btn btn-primary"><i class="fi fi-ss-floppy-disks"></i>Save</button>
            </div>
            <a class="link link-hover w-full max-w-xs text-center" href="tableView.php?table=<?php echo $table ?>">Voltar</a>
        </form>
    </div>
</div>
<?php
    mysqli_close($db_conn);
    require 'includes/footer.inc.php';
