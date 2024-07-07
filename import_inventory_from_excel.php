<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "sairoma";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['import_file'])) {
    $file = $_FILES['import_file']['tmp_name'];
    if (!file_exists($file)) {
        echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'File Not Found',
                    text: 'Please upload a valid Excel file.'
                }).then(function() {
                    window.location = 'inventory.php';
                });
              </script>";
        exit;
    }
    $spreadsheet = IOFactory::load($file);
    $sheet = $spreadsheet->getActiveSheet();
    $highestRow = $sheet->getHighestRow();

    for ($row = 2; $row <= $highestRow; $row++) {
        $id = $sheet->getCell('A' . $row)->getValue();
        $name = $sheet->getCell('B' . $row)->getValue();
        $hargabeli = str_replace(['Rp ', '.', ','], '', $sheet->getCell('C' . $row)->getValue());
        $stock = $sheet->getCell('D' . $row)->getValue();
        $kategori = $sheet->getCell('E' . $row)->getValue();
        $merk = $sheet->getCell('F' . $row)->getValue();

        // Check if product exists
        $check_sql = "SELECT * FROM products WHERE id='$id'";
        $check_result = $conn->query($check_sql);

        if ($check_result->num_rows > 0) {
            // Update existing product
            $sql = "UPDATE products SET name='$name', hargabeli='$hargabeli', stock='$stock', kategori='$kategori', merk='$merk' WHERE id='$id'";
        } else {
            // Insert new product
            $sql = "INSERT INTO products (id, name, hargabeli, stock, kategori, merk) VALUES ('$id', '$name', '$hargabeli', '$stock', '$kategori', '$merk')";
        }

        if ($conn->query($sql) !== TRUE) {
            echo "<script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Import Failed',
                        text: 'Error: " . $conn->error . "'
                    }).then(function() {
                        window.location = 'inventory.php';
                    });
                  </script>";
            exit;
        }
    }

    echo "<script>
            Swal.fire({
                icon: 'success',
                title: 'Import Successful',
                text: 'Data has been imported successfully.'
            }).then(function() {
                window.location = 'inventory.php';
            });
          </script>";
        echo "<script>
                Swal.fire({
                    icon: 'success',
                    title: 'Import Successful',
                    text: 'Data has been imported successfully.'
                }).then(function() {
                    window.location = 'inventory.php';
                });
              </script>";
    } else {
        echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Import Failed',
                    text: 'Error: " . $conn->error . "'
                }).then(function() {
                    window.location = 'inventory.php';
                });
              </script>";
    }
}
$conn->close();
?>
?>
