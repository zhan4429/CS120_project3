<?php
require("dbparams.inc");
    $conn = new mysqli($server, $id, $pw );
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error); //not for production
    }
    $conn->select_db($db);
?>