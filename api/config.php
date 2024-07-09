<?php

$servername = "sql209.infinityfree.com"; // Host Name
$username = "if0_36860907"; // MySQL User Name
$password = "Your_vPanel_Password"; // MySQL Password
$dbname = "if0_36860907_sairoma"; // MySQL DB Name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
