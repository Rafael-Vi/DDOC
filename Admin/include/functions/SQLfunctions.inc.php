<?php
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
$arrConfig['dir_fotos']='C:\wamp64\www\FrontOffice_Proj\assets\images';
//$arrConfig['dir_fotos']='C:/Share/12itm124/www/BackOffice_Proj';

if (isset($_POST['submit']) && !empty($_POST['submit'])) {
    $action = $_POST['submit'];
    switch ($action) {
        case 'delete':
            echo "1";
            $data = $_POST;
            deleteCRUD($data);
            break;
        case 'edit':
            $id = $_POST['id'];
            $table = $_POST['table'];
            header("Location: ../../general.php?action=edit&table=$table&id=$id");
            break;
        case 'insert':
            $table = $_POST['table'];
            var_dump($_POST);
            header("Location: ../../general.php?action=insert&table=$table&id=$id");
            break;
        default:
            break;
    }

if ($action !== 'LoginUser' && $action !== 'RegisterUser') {
    unset($_POST);
}

}

if (isset($_POST['edit'])) {
    var_dump($_POST);
    $data = $_POST;
    $table = $_POST['table'];
    $id = $_POST['id'];
    unset($data['edit']);
    update($table, $data, 'id = ?', [$id]);
    header("Location: ../../home.php");
    unset($_POST);
    exit();
} elseif (isset($_POST['insert'])) {
    $data = $_POST;
    $table = $_POST['table'];
    create($table,$data);
    var_dump($_POST);
    unset($_POST);
    header("Location: ../../home.php");
    exit();
} else {
    unset($_POST);
}

function dataTable(){
    $table = $_GET['table'];
    if($table == 'admins'){
        $data = read($table, 'id != ?', [$_SESSION['uid']]);
    }
    else{
        $data = read($table);
    }
    if ($table != 'admins' && $table !='logs') {
        echo "<input type='submit' name='submit' value='insert'  onclick=\"confirmSubmit(event, 'insert');\"> ";
        echo "<input type='hidden' name='table' value='$table' >";
    }
    if (!empty($data)) {
        echoTableHeaders($data[0]);
        foreach ($data as $row) {
            echoTableRow($row, $table);
        }
    }
}

function editTable(){
    global $arrConfig; 
    $dbconn = $arrConfig['conn']; 
    $table = $_GET['table'];
    $id = $_GET['id'];
    $data = read($table, 'id = ?', [$id]);
    if (!empty($data)) {
        echoEditForm($data[0], $table, $dbconn);
    }
}
function insertTable($top){
    global $arrConfig; 
    $dbconn = $arrConfig['conn']; 
    $table = $_GET['table'];
    $data = read($table);
    if (!empty($data)) {
        echoInsertForm($data[0], $table , $dbconn);
    } else {
        $fields = getTableFields($table);
        if (!empty($fields)) {
            echoInsertForm($fields, $table , $dbconn);
        }
    }
}

function getTableFields($table){
    global $arrConfig; 
    $dbconn = $arrConfig['conn']; 

    $sql = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = ?";
    $stmt = $dbconn->prepare($sql);
    $stmt->bind_param('s', $table);
    $stmt->execute();
    $result = $stmt->get_result();
    $fields = array();
    while ($row = $result->fetch_assoc()) {
        $fields[] = $row['COLUMN_NAME'];
    }
    return $fields;
}

function create($table, $data) {
    global $arrConfig; 
    $dbconn = $arrConfig['conn']; 
    if (!$dbconn) return false;
    array_shift($data);
    array_pop($data);

    // Check if email already exists
    if (isset($data['email'])) {
        $email = $data['email'];
        $stmt = $dbconn->prepare("SELECT * FROM `$table` WHERE email = ?");
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            echo "A user with this email already exists.";
            return false;
        }
    }
    if (isset($data['username'])) {
        $username = $data['username'];
        $stmt = $dbconn->prepare("SELECT * FROM `$table` WHERE username = ?");
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            echo "A user with this username already exists.";
            return false;
        }
    }

    if (isset($_FILES)) {
        echo "1";
        foreach ($_FILES as $key => $file) {
            $original_name = $file['name'];
            $tmp_name = $file['tmp_name'];
            $target_file = $arrConfig['dir_fotos'].'\\'.$original_name;
            if (file_exists($target_file)) {
                echo "File with the same name already exists.";
                return false;
            }
            if (move_uploaded_file($tmp_name, $target_file)) {
                $data[$key] = "./assets/images/" . $original_name;
                echo $data[$key];
            } else {
                echo "Failed to upload file.";
                return false;
            }
        }
    } else {
        echo "File not found";
        var_dump($_FILES);
        return false;
    }
    $columns = implode(',', array_map(function($key) { return "`$key`"; }, array_keys($data)));
    $placeholders = str_repeat('?,', count($data) - 1) . '?';
    $sql = "INSERT INTO `$table` ($columns) VALUES ($placeholders)";
    $stmt = $dbconn->prepare($sql);
    if ($stmt === false) {
        echo($dbconn->error);
        return false;
    }
    $types = str_repeat('s', count($data));
    $stmt->bind_param($types, ...array_values($data));
    $stmt->execute();
    return $dbconn->insert_id;
}

function read($table, $where = '', $params = []) {
    global $arrConfig; 
    $dbconn = $arrConfig['conn']; 
    if (!$dbconn) return false;
    $sql = "SELECT * FROM $table";
    if ($where) {
        $sql .= " WHERE $where";
    }
    $stmt = $dbconn->prepare($sql);
    if ($stmt === false) {
        echo($dbconn->error);
        return false;
    }
    if (!empty($params)) {
        $types = str_repeat('s', count($params));
        $stmt->bind_param($types, ...$params);
    }
    $stmt->execute();
    $result = $stmt->get_result();
    $rows = $result->fetch_all(MYSQLI_ASSOC);
    return $rows;
}

function update($table, $data, $where, $whereParams) {
    global $arrConfig; 
    $dbconn = $arrConfig['conn']; 
    if (!$dbconn) return false;
    unset($data['table']);
    $set = '';
    $params = [];

    if (isset($_FILES)) {
        echo "1";
        foreach ($_FILES as $key => $file) {
            $original_name = $file['name'];
            $tmp_name = $file['tmp_name'];
            $target_file = $arrConfig['dir_fotos'].'\\'.$original_name;
            if (file_exists($target_file)) {
                echo "File with the same name already exists.";
                return false;
            }
            if (move_uploaded_file($tmp_name, $target_file)) {
                $data[$key] = "./assets/images/" . $original_name;
                echo $data[$key];
            } else {
                echo "Failed to upload file.";
                return false;
            }
        }
    } else {
        echo "File not found";
        var_dump($_FILES);
        return false;
    }

    foreach ($data as $key => $value) {
        $set .= "$key = ?, ";
        $params[] = $value;
    }
    $set = rtrim($set, ', ');
    $sql = "UPDATE $table SET $set WHERE $where";
    $stmt = $dbconn->prepare($sql);
    if ($stmt === false) {
        echo "Error: " . $dbconn->error;
        return false;
    }
    $params = array_merge($params, $whereParams);
    $stmt->bind_param(str_repeat('s', count($params)), ...$params);
    $stmt->execute();
    return $dbconn->affected_rows;
}


function deleteCRUD($data) {
    echo "2";
    global $arrConfig; 
    $dbconn = $arrConfig['conn']; 
    if (!$dbconn) echo "false";
    if (!isset($data['table']) || !isset($data['where'])) echo "false";
    $table = '';
    switch ($data['table']) {
        case 'users':
            $table = 'users';

            $relatedTables = ['testimonials', 'photos', 'logs'];

            $sql = "SELECT id, photo_url FROM photos WHERE user_id = ?";
            $stmt = $dbconn->prepare($sql);
            $stmt->bind_param('i', $data['id']);
            $stmt->execute();
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
                $filename = str_replace('./assets/images/', '', $row['photo_url']);
                echo $filename;
                unlink($arrConfig['dir_fotos']."\\".$filename);
                echo "4";
            }
            
            foreach ($relatedTables as $relatedTable) {
                $sql = "DELETE FROM $relatedTable WHERE user_id = ?";
                $stmt = $dbconn->prepare($sql);
                $stmt->bind_param('i', $data['id']);
                $stmt->execute();
            }

            break;

        case 'testimonials':
            $table = 'testimonials';
            break;

        case 'requirements':
            $table = 'requirements';
            break;

        case 'photos':
            $table = 'photos';
            break;

        case 'genres':
            $table = 'genres';

            $sql = "SELECT * FROM contests WHERE contest_genre_id = ?";
            $stmt = $dbconn->prepare($sql);
            $stmt->bind_param('i', $data['id']);
            $stmt->execute();
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
                $dataNew = ['table' => 'contests', 'id' => $row['id'], 'contest_url' => $row['contest_url']];
                deleteCRUD($dataNew);
            }

            break;

        case 'contests':
            $table = 'contests';

            $relatedTables = ['photos', 'requirements'];

            $sql = "SELECT id, photo_url FROM photos WHERE contest_id = ?";
            $stmt = $dbconn->prepare($sql);
            $stmt->bind_param('i', $data['id']);
            $stmt->execute();
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
                $filename = str_replace('./assets/images/', '', $row['photo_url']);
                echo $filename;
                unlink($arrConfig['dir_fotos']."\\".$filename);
                echo "4";
            }
            
            foreach ($relatedTables as $relatedTable) {
                $sql = "DELETE FROM $relatedTable WHERE contest_id = ?";
                $stmt = $dbconn->prepare($sql);
                $stmt->bind_param('i', $data['id']);
                $stmt->execute();
            }

            break;

        default:
            return false;
    }
    foreach ($data as $key => $value) {
        if (strpos($key, '_url') !== false ) {
            $filename = str_replace('./assets/images/', '', $value);
            echo $filename;
            unlink($arrConfig['dir_fotos']."\\".$filename);
        }
    }
    $where = "id=".$data['id'];
    $sql = "DELETE FROM $table WHERE $where";
    $stmt = $dbconn->prepare($sql);
    $stmt->execute();

    var_dump($data);
    $affected_rows = $dbconn->affected_rows;
    header("Location: ../../data.php?table=$table");
    exit();
}