<?php
header('Content-Type: application/json');

// Fungsi untuk mengembalikan respons JSON
function sendJsonResponse($status, $message) {
    echo json_encode([
        'status' => $status,
        'message' => $message
    ]);
    exit;
}

// Tangkap semua error PHP
set_error_handler(function($errno, $errstr, $errfile, $errline) {
    sendJsonResponse('error', "PHP Error: $errstr in $errfile on line $errline");
});

// Log function
$log_file = 'import_log.txt';
function log_message($message) {
    global $log_file;
    file_put_contents($log_file, date('Y-m-d H:i:s') . " - " . $message . "\n", FILE_APPEND);
}

try {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "sairoma";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }

    require 'vendor/autoload.php';

    use PhpOffice\PhpSpreadsheet\IOFactory;

    if ($_SERVER['REQUEST_METHOD'] != 'POST') {
        throw new Exception("Invalid request method.");
    }

    if (!isset($_FILES['import_file'])) {
        throw new Exception("No file uploaded or invalid request.");
    }

    $file = $_FILES['import_file']['tmp_name'];
    log_message("File uploaded: " . $file);
    log_message("File type: " . $_FILES['import_file']['type']);
    log_message("File size: " . $_FILES['import_file']['size']);

    if (!file_exists($file)) {
        throw new Exception("File not found: " . $file);
    }

    $spreadsheet = IOFactory::load($file);
    log_message("Spreadsheet loaded successfully");

    $sheet = $spreadsheet->getActiveSheet();
    $highestRow = $sheet->getHighestRow();
    log_message("Highest row: " . $highestRow);

    for ($row = 2; $row <= $highestRow; $row++) {
        $id = $sheet->getCell('A' . $row)->getValue();
        $name = $sheet->getCell('B' . $row)->getValue();
        $hargabeli = str_replace(['Rp ', '.', ','], '', $sheet->getCell('C' . $row)->getValue());
        $stock = $sheet->getCell('D' . $row)->getValue();
        $kategori = $sheet->getCell('E' . $row)->getValue();
        $merk = $sheet->getCell('F' . $row)->getValue();

        log_message("Processing row $row: ID=$id, Name=$name, HargaBeli=$hargabeli, Stock=$stock, Kategori=$kategori, Merk=$merk");

        // Check if product exists
        $check_sql = $conn->prepare("SELECT * FROM products WHERE id=?");
        $check_sql->bind_param("s", $id);
        $check_sql->execute();
        $check_result = $check_sql->get_result();

        if ($check_result->num_rows > 0) {
            // Update existing product
            $sql = $conn->prepare("UPDATE products SET name=?, hargabeli=?, stock=?, kategori=?, merk=? WHERE id=?");
            $sql->bind_param("ssisss", $name, $hargabeli, $stock, $kategori, $merk, $id);
            log_message("Updating existing product: ID=$id");
        } else {
            // Insert new product
            $sql = $conn->prepare("INSERT INTO products (id, name, hargabeli, stock, kategori, merk) VALUES (?, ?, ?, ?, ?, ?)");
            $sql->bind_param("ssisss", $id, $name, $hargabeli, $stock, $kategori, $merk);
            log_message("Inserting new product: ID=$id");
        }

        if ($sql->execute() !== TRUE) {
            throw new Exception("Error executing SQL: " . $sql->error);
        }
    }

    sendJsonResponse('success', 'Data has been imported successfully.');
} catch (Exception $e) {
    log_message("Error: " . $e->getMessage());
    sendJsonResponse('error', $e->getMessage());
} finally {
    if (isset($conn)) {
        $conn->close();
    }
}
?>