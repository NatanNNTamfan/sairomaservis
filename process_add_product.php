<?php
include 'config.php';

header('Content-Type: application/json');

try {

try {

try {

header('Content-Type: application/json');

if (!empty($_POST['name']) && !empty($_POST['hargabeli']) && !empty($_POST['stock']) && !empty($_POST['kategori']) && !empty($_POST['merk'])) {
    $name = $_POST['merk'] . ' ' . $_POST['name'] . ' ' . $_POST['kategori'];
    $hargabeli = str_replace(['Rp ', '.'], '', $_POST['hargabeli']);
    $stock = $_POST['stock'];
    $kategori = $_POST['kategori'];
    $merk = $_POST['merk'];

    // Check for duplicate product
    $check_sql = "SELECT * FROM products WHERE name='$name' AND kategori='$kategori' AND merk='$merk'";
    $check_result = $conn->query($check_sql);

    if ($check_result->num_rows > 0) {
        echo json_encode([
            'icon' => 'warning',
            'title' => 'Warning',
            'text' => 'Product already exists'
        ]);
    } else {
        $sql = "INSERT INTO products (name, hargabeli, stock, kategori, merk) VALUES ('$name', '$hargabeli', '$stock', '$kategori', '$merk')";
        if ($conn->query($sql) === TRUE) {
            echo json_encode([
                'icon' => 'success',
                'title' => 'Success',
                'text' => 'New product added successfully'
            ]);
        } else {
            echo json_encode([
                'icon' => 'error',
                'title' => 'Error',
                'text' => 'Error: ' . $conn->error
            ]);
        }
    }
} else {
    echo json_encode([
        'icon' => 'warning',
        'title' => 'Warning',
        'text' => 'All fields are required'
    ]);
}

$conn->close();
exit();
} catch (Exception $e) {
    echo json_encode([
        'icon' => 'error',
        'title' => 'Error',
        'text' => 'Unexpected error occurred: ' . $e->getMessage()
    ]);
}
exit();
