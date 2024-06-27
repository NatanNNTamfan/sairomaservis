<?php
include 'config.php';

if (isset($_POST['edit_product'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $hargajual = $_POST['hargajual'];
    $hargabeli = $_POST['hargabeli'];
    $stock = $_POST['stock'];
    $kategori = $_POST['kategori'];
    $merk = $_POST['merk'];

    // Update product
    $sql = "UPDATE products SET name='$name', hargajual='$hargajual', hargabeli='$hargabeli', stock='$stock', kategori='$kategori', merk='$merk' WHERE id='$id'";
    if ($conn->query($sql) === TRUE) {
        echo "Product updated successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();

// Redirect back to the inventory page
header("Location: inventory.php");
exit();
?>
