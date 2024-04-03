<?php
function echoTableHeaders($row) {
    echo "<tr>";
    foreach ($row as $column => $value) {
        echo "<th>$column</th>";
    }
    echo "<th>Actions</th>";
    echo "</tr>";
}

function echoTableRow($row, $tableName) {
    global $arrConfig;

    echo "<tr>";
    foreach ($row as $column => $value) {
        echo "<td>";

        echo "$value    ";
        if (strpos($column, '_url') !== false) {
            $value = str_replace("./assets/images/", $arrConfig['url_fotos']."\\", $value);
            echo "<img src='$value' style='width: 50px; height: 50px;'> ";
        }
        echo "</td>";
    }
    echo "<td>";
    echo "<form class=\"myForm\" method='POST' action='./include/functions/SQLfunctions.inc.php'>";
    foreach ($row as $column => $value) {
        echo "<input type='hidden' name='$column' value='$value'>";
    }
    echo "<input type='hidden' name='table' value='$tableName' >";
    if ($tableName != 'logs') {
        echo "<input type='submit' name='submit' value='edit'  onclick=\"confirmSubmit(event, 'edit');\"> ";
        echo "<input type='submit' name='submit' value='delete' onclick=\"confirmSubmit(event, 'delete');\">";
    }
    echo "</form>";
    echo "</td>";
    echo "</tr>";
}

function echoEditForm($data, $table, $conn) {
    $nameFields = [
        'user_id' => 'username',
        'contest_genre_id' => 'genre_name',
        'contest_id' => 'contest_name'
    ];

    $tableNames = [
        'user_id' => 'users',
        'contest_genre_id' => 'genres',
        'contest_id' => 'contests'
    ];

    echo '<input type="hidden" name="table" value="'.$table.'">';
    $first = true;
    foreach ($data as $key => $value) {
        echo '<div class="form-group" enctype="multipart/form-data">';
        echo '<label for="'.$key.'">'.$key.'</label>';
        if (strpos($key, '_url') !== false) {
            echo '<input type="file" class="form-control" id="'.$key.'" name="'.$key.'" required>';
        } elseif (in_array($key, array_keys($nameFields))) {
            echo '<select class="form-control" id="'.$key.'" name="'.$key.'">';
            $query = "SELECT id, " . $nameFields[$key] . " FROM " . $tableNames[$key];
            $result = mysqli_query($conn, $query);
            if ($result === false) {
                die('Error: ' . mysqli_error($conn));
            }
            while ($row = mysqli_fetch_assoc($result)) {
                echo '<option value="'.$row['id'].'"'.($row['id'] == $value ? ' selected' : '').'>'.$row[$nameFields[$key]].'</option>';
            }
            echo '</select>';
        } elseif ($key == 'super_user' || $key == 'should_have' || $key == 'finished' || $key == 'showing') {
            echo '<select class="form-control" id="'.$key.'" name="'.$key.'">';
            echo '<option value="0"'.($value == 0 ? ' selected' : '').'>No</option>';
            echo '<option value="1"'.($value == 1 ? ' selected' : '').'>Yes</option>';
            echo '</select>';
            
        }
        elseif($key == 'Rank'){
            echo '<input type="number" class="form-control" id="'.$key.'" name="'.$key.'" min="1" required>';
        } elseif($key == 'Deadline'){
            $today = date("Y-m-d");
            echo '<input type="date" class="form-control" id="'.$key.'" name="'.$key.'" value="'.$value.'" min="'.$today.'"'.($first ? ' readonly' : '').' required>';          
        }
        else{
            echo '<input type="text" class="form-control" id="'.$key.'" name="'.$key.'" value="'.$value.'"'.($first ? ' readonly' : '').' required>';
            $first = false;
        }
        echo '</div>';
    }
}

function echoInsertForm($data, $table, $conn) {
    $nameFields = [
        'user_id' => 'username',
        'contest_genre_id' => 'genre_name',
        'contest_id' => 'contest_name'
    ];

    echo '<input type="hidden" name="table" value="'.$table.'">';
    $isFieldNames = is_string(reset($data));
    $counter = 0;
    foreach ($data as $key => $value) {
        $field = $isFieldNames ? $value : $key;
        echo '<div class="form-group">';
        if ($counter == 0) {
            echo '<label for="'.$field.'" style="display: none;">'.$field.'</label>';
            echo '<input type="hidden" id="'.$field.'" name="'.$field.'">';
        } else {
            echo '<label for="'.$field.'">'.$field.'</label>';
            if (strpos($field, '_url') !== false) {
                echo '<input type="file" class="form-control" id="'.$field.'" name="'.$field.'">';
            } elseif (in_array($field, array_keys($nameFields))) {
                echo '<select class="form-control" id="'.$field.'" name="'.$field.'">';
                $tableName = $field === 'contest_genre_id' ? 'genres' : str_replace('_id', 's', $field);
                $query = "SELECT id, " . $nameFields[$field] . " FROM " . $tableName;
                $result = mysqli_query($conn, $query);
                if (!$result) {
                    die(mysqli_error($conn));
                }
                $first = true;
                while ($row = mysqli_fetch_assoc($result)) {
                    if ($first) {
                        $first = false;
                        continue;
                    }
                    echo '<option value="'.$row['id'].'">'.$row[$nameFields[$field]].'</option>';
                }
                echo '</select>';
            } elseif ($key == 'super_user' || $key == 'should_have' || $key == 'finished' || $key == 'showing') {
                echo '<select class="form-control" id="'.$key.'" name="'.$key.'">';
                echo '<option value="0"'.($value == 0 ? ' selected' : '').'>No</option>';
                echo '<option value="1"'.($value == 1 ? ' selected' : '').'>Yes</option>';
                echo '</select>';
            } 
            elseif($key == 'Rank'){
                echo '<input type="number" class="form-control" id="'.$key.'" name="'.$key.'" min="1" required>';
            }// In echoInsertForm function
            elseif($key == 'Deadline'){
                $today = date("Y-m-d");
                echo '<input type="date" class="form-control" id="'.$key.'" name="'.$key.'" value="'.$value.'" min="'.$today.'"'.($first ? ' readonly' : '').' required>';          
            } else {
                echo '<input type="text" class="form-control" id="'.$field.'" name="'.$field.'" required>';
            }
        }
        echo '</div>';
        $counter++;
    }
}