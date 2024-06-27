<?php
include 'config.php';

if (isset($_POST['id'])) {
    $id = $_POST['id'];

    // Delete product
    $sql = "DELETE FROM products WHERE id='$id'";
    if ($conn->query($sql) === TRUE) {
        echo "Product deleted successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();

// Redirect back to the inventory page
header("Location: inventory.php");
exit();
?>
