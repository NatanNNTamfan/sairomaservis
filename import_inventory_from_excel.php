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

$log_file = 'import_log.txt';
function log_message($message) {
    global $log_file;
    file_put_contents($log_file, date('Y-m-d H:i:s') . " - " . $message . "\n", FILE_APPEND);
}

log_message("Script started");

use PhpOffice\PhpSpreadsheet\IOFactory;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    log_message("POST request received");
    if (isset($_FILES['import_file'])) {
        log_message("File upload detected");
    $file = $_FILES['import_file']['tmp_name'];
    log_message("File uploaded: " . $file);
        if (!file_exists($file)) {
            log_message("File not found: " . $file);
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
        log_message("File not found: " . $file);
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
}
    
        try {
            $spreadsheet = IOFactory::load($file);
            log_message("Spreadsheet loaded successfully");
        } catch (Exception $e) {
            log_message("Error loading spreadsheet: " . $e->getMessage());
            echo "<script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Import Failed',
                        text: 'Error loading spreadsheet.'
                    }).then(function() {
                        window.location = 'inventory.php';
                    });
                  </script>";
            exit;
        }
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
            $check_sql = $conn->prepare("SELECT * FROM products WHERE id=?");
            $check_sql->bind_param("s", $id);
            $check_sql->execute();
            $check_result = $check_sql->get_result();

            if ($check_result->num_rows > 0) {
                // Update existing product
                $sql = $conn->prepare("UPDATE products SET name=?, hargabeli=?, stock=?, kategori=?, merk=? WHERE id=?");
                $sql->bind_param("ssisss", $name, $hargabeli, $stock, $kategori, $merk, $id);
            } else {
                // Insert new product
                $sql = $conn->prepare("INSERT INTO products (id, name, hargabeli, stock, kategori, merk) VALUES (?, ?, ?, ?, ?, ?)");
                $sql->bind_param("ssisss", $id, $name, $hargabeli, $stock, $kategori, $merk);
            }

            if ($sql->execute() !== TRUE) {
                log_message("Error executing SQL: " . $sql->error);
                echo "<script>
                        Swal.fire({
                            icon: 'error',
                            title: 'Import Failed',
                            text: 'Error: " . $sql->error . "'
                        }).then(function() {
                            window.location = 'inventory.php';
                        });
                      </script>";
                exit;
            }
        }

        log_message("Import successful");
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
        log_message("No file uploaded or invalid request");
        echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Import Failed',
                    text: 'No file uploaded or invalid request.'
                }).then(function() {
                    window.location = 'inventory.php';
                });
              </script>";
    }
}
$conn->close();
?>
