<?php

require_once "configuration.php";

$conn = mysqli_connect($config["database1"]["hostname"], $config["database1"]["username"], $config["database1"]["password"], $config["database1"]["database"]);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}