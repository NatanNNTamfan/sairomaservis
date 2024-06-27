<?php
include 'config.php';

if (isset($_POST['id']) && isset($_POST['add_stock'])) {
    $id = $_POST['id'];
    $add_stock = $_POST['add_stock'];

    // Update stock
    $sql = "UPDATE products SET stock = stock + $add_stock WHERE id='$id'";
    if ($conn->query($sql) === TRUE) {
        echo "Stock updated successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();

// Redirect back to the inventory page
header("Location: inventory.php");
exit();
?>
