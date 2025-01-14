<?php
$dbhost = "localhost";
$dbuser = "root";
$dbpass = "TB1234avt";
$dbname = "ziekMeld";

$con = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

if (!$con) {
    die("Failed to Connect: " . mysqli_connect_error());
}

