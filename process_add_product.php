<?php
include 'config.php';

if (isset($_POST['name']) && isset($_POST['hargabeli']) && isset($_POST['stock']) && isset($_POST['kategori']) && isset($_POST['merk'])) {
    $name = $_POST['merk'] . ' ' . $_POST['name'] . ' ' . $_POST['kategori'];
    $hargabeli = str_replace(['Rp ', '.'], '', $_POST['hargabeli']);
    $stock = $_POST['stock'];
    $kategori = $_POST['kategori'];
    $merk = $_POST['merk'];

    // Check for duplicate product
    $check_sql = "SELECT * FROM products WHERE name='$name' AND kategori='$kategori' AND merk='$merk'";
    $check_result = $conn->query($check_sql);

    if ($check_result->num_rows > 0) {
        echo "<div class='alert alert-warning'>Product already exists</div>";
    } else {
        $sql = "INSERT INTO products (name, hargabeli, stock, kategori, merk) VALUES ('$name', '$hargabeli', '$stock', '$kategori', '$merk')";
        if ($conn->query($sql) === TRUE) {
            echo "<div class='alert alert-success'>New product added successfully</div>";
        } else {
            echo "<div class='alert alert-danger'>Error: " . $conn->error . "</div>";
        }
    }
} else {
    echo "<div class='alert alert-warning'>All fields are required</div>";
}

$conn->close();
header("Location: inventory.php");
exit();
?>
