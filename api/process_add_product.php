<?php
// Enable error reporting for debugging (comment out in production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'config.php'; 
include 'navbar.html';

header('Content-Type: application/json'); 

try {
    if (
        !empty($_POST['name']) && 
        !empty($_POST['hargabeli']) && 
        !empty($_POST['stock']) && 
        !empty($_POST['kategori']) && 
        !empty($_POST['merk'])
    ) {
        $name = $conn->real_escape_string($_POST['name']); 
        $hargabeli = str_replace(['Rp ', '.'], '', $_POST['hargabeli']);
        $stock = (int)$_POST['stock']; 
        $kategori = $conn->real_escape_string($_POST['kategori']); 
        $merk = $conn->real_escape_string($_POST['merk']); 

        // Check for duplicate product
        $check_sql = "SELECT * FROM products WHERE name=? AND kategori=? AND merk=?";
        $stmt = $conn->prepare($check_sql);
        $stmt->bind_param("sss", $name, $kategori, $merk);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            echo json_encode([
                'icon' => 'warning',
                'title' => 'Warning',
                'text' => 'Product already exists'
            ]);
        } else {
            $stmt->close();

            $sql = "INSERT INTO products (name, hargabeli, stock, kategori, merk) VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("siiss", $name, $hargabeli, $stock, $kategori, $merk);

            if ($stmt->execute()) {
                echo json_encode([
                    'icon' => 'success',
                    'title' => 'Success',
                    'text' => 'New product added successfully'
                ]);
            } else {
                throw new Exception($stmt->error);
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
} catch (Exception $e) {
    // Log the error for debugging
    error_log('Error in process_add_product.php: ' . $e->getMessage());

    // Send a JSON error response
    echo json_encode([
        'icon' => 'error',
        'title' => 'Error',
        'text' => 'An error occurred while processing your request.' 
    ]);
} finally {
    // Close the connection
    if (isset($conn)) {
        $conn->close();
    }
}

exit(); 
?>