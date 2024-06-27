<?php
include 'config.php';

// Clear all data from products and sales tables
$sql = "TRUNCATE TABLE products";
$conn->query($sql);

$sql = "TRUNCATE TABLE sales";
$conn->query($sql);

$conn->close();

// Redirect back to the index page
header("Location: index.php");
exit();
?>
