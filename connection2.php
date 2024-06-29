<?php

require_once "configuration.php";

$con = mysqli_connect($config["database2"]["hostname"], $config["database2"]["username"], $config["database2"]["password"], $config["database2"]["database"]);

// Check connection
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}