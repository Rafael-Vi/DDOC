<?php
function db_connect()
{
    global $arrConfig;

    $conn = mysqli_connect($arrConfig['connect_DB'][0], $arrConfig['connect_DB'][1], $arrConfig['connect_DB'][2], $arrConfig['connect_DB'][3]);

    if (!$conn) {
        die("Error connecting to MySQL Server: " . mysqli_connect_error());
    }
    return $conn;
}
function executeQuery($dbConn, $query, $params = null)
{
    $stmt = mysqli_prepare($dbConn, $query);

    if ($params !== null && !empty($params)) {
        $types = '';
        foreach ($params as $param) {
            if (is_int($param)) {
                $types .= 'i';
            } elseif (is_bool($param)) {
                $types .= 'i'; // booleans are treated as integers
            } elseif (is_string($param)) {
                $types .= 's';
            } else {
                $types .= 'b'; // for blob and unknown types
            }
        }

        if (!empty($types)) {
            mysqli_stmt_bind_param($stmt, $types, ...$params);
        }
    }

    $executed = mysqli_stmt_execute($stmt);
    if ($executed) {
        $result = mysqli_stmt_get_result($stmt);
        return $result !== false ? $result : true;
    } else {
        return false;
    }
}