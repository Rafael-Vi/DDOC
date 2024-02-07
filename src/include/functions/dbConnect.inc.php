<?php

function db_connect() {
    $conn = mysqli_connect("localhost", "root", "", "ddoc");

    if (!$conn) {
        die("Error connecting to MySQL Server: " . mysqli_connect_error());
    }

    return $conn;
}

?>