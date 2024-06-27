<?php
include 'config.php';

// Clear all data from products and sales tables
$sql = "DELETE FROM products";
$conn->query($sql);

$sql = "DELETE FROM sales";
$conn->query($sql);

$conn->close();

// Redirect back to the index page
header("Location: index.php");
exit();
?>
