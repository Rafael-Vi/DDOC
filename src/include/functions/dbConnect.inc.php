<?php

function db_connect() {
    try {
        $conn = new PDO("sqlsrv:server = tcp:ddoc-lets-run.database.windows.net,1433; Database = DDOC", "RafaelAdmin", "Rafa1402");
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    catch (PDOException $e) {
        print("Error connecting to SQL Server.");
        die(print_r($e));
    }
    
    // SQL Server Extension Sample Code:
    $connectionInfo = array("UID" => "RafaelAdmin", "pwd" => "Rafa1402", "Database" => "DDOC", "LoginTimeout" => 30, "Encrypt" => 1, "TrustServerCertificate" => 0);
    $serverName = "tcp:ddoc-lets-run.database.windows.net,1433";
    $conn = sqlsrv_connect($serverName, $connectionInfo);
    return $conn;
}


?>