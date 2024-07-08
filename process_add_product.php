<?php
include 'config.php';

header('Content-Type: application/json');

header('Content-Type: application/json');

try {
    if (!empty($_POST['name']) && !empty($_POST['hargabeli']) && !empty($_POST['stock']) && !empty($_POST['kategori']) && !empty($_POST['merk'])) {
        $name = $_POST['name'];
        $hargabeli = str_replace(['Rp ', '.'], '', $_POST['hargabeli']);
        $stock = $_POST['stock'];
        $kategori = $_POST['kategori'];
        $merk = $_POST['merk'];

        // Check for duplicate product
        $check_sql = "SELECT * FROM products WHERE name=? AND kategori=? AND merk=?";
        $stmt = $conn->prepare($check_sql);
        $stmt->bind_param("sss", $name, $kategori, $merk);
        $stmt->execute();
        $check_result = $stmt->get_result();

        if ($check_result->num_rows > 0) {
            echo json_encode([
                'icon' => 'warning',
                'title' => 'Warning',
                'text' => 'Product already exists'
            ]);
        } else {
            $sql = "INSERT INTO products (name, hargabeli, stock, kategori, merk) VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssiss", $name, $hargabeli, $stock, $kategori, $merk);
            if ($stmt->execute()) {
                echo json_encode([
                    'icon' => 'success',
                    'title' => 'Success',
                    'text' => 'New product added successfully'
                ]);
            } else {
                echo json_encode([
                    'icon' => 'error',
                    'title' => 'Error',
                    'text' => 'Error: ' . $stmt->error
                ]);
            }
        }
        $stmt->close();
    } else {
        echo json_encode([
            'icon' => 'warning',
            'title' => 'Warning',
            'text' => 'All fields are required'
        ]);
    }

    $conn->close();
} catch (Exception $e) {
    echo json_encode([
        'icon' => 'error',
        'title' => 'Error',
        'text' => 'Unexpected error occurred: ' . $e->getMessage()
    ]);
}
exit();
?>
