<?php

require_once "config.php";

$conn = mysqli_connect($config["database"]["hostname"], $config["database"]["username"], $config["database"]["password"], $config["database"]["database"]);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}