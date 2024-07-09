<?php
// Matikan tampilan error, aktifkan log error
ini_set('display_errors', 1);
ini_set('log_errors', 1);
error_reporting(E_ALL);
ini_set('error_log', 'import_error.log');

// Mulai output buffering
ob_start();

// Set header JSON
header('Content-Type: application/json');

// Fungsi untuk mengirim respons JSON
function sendJsonResponse($status, $message) {
    ob_end_clean(); // Bersihkan output buffer
    echo json_encode([
        'status' => $status,
        'message' => $message
    ]);
    exit;
}

// Fungsi untuk logging
function logMessage($message) {
    file_put_contents('import_log.txt', date('Y-m-d H:i:s') . " - " . $message . "\n", FILE_APPEND);
}

// Tangkap semua error PHP
set_error_handler(function($errno, $errstr, $errfile, $errline) {
    logMessage("PHP Error: $errstr in $errfile on line $errline");
    sendJsonResponse('error', "An error occurred during import. Please check the log for details.");
});

    // Load library PHPSpreadsheet
    require 'vendor/autoload.php';

    use PhpOffice\PhpSpreadsheet\IOFactory;

try {
    // Log input untuk debugging
    logMessage("POST data: " . print_r($_POST, true));
    logMessage("FILES data: " . print_r($_FILES, true));

    // Periksa apakah file telah diunggah
    if (!isset($_FILES['import_file']) || $_FILES['import_file']['error'] !== UPLOAD_ERR_OK) {
        throw new Exception("No file uploaded or upload error occurred.");
    }

    // Konfigurasi database
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "sairoma";

    // Buat koneksi
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Periksa koneksi
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }

    $inputFileName = $_FILES['import_file']['tmp_name'];
    $spreadsheet = IOFactory::load($inputFileName);
    $worksheet = $spreadsheet->getActiveSheet();
    $highestRow = $worksheet->getHighestRow();

    // Mulai transaksi database
    $conn->begin_transaction();

    for ($row = 2; $row <= $highestRow; $row++) {
        $id = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
        $name = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
        $hargabeli = str_replace(['Rp ', '.', ','], '', $worksheet->getCellByColumnAndRow(3, $row)->getValue());
        $stock = $worksheet->getCellByColumnAndRow(4, $row)->getValue();
        $kategori = $worksheet->getCellByColumnAndRow(5, $row)->getValue();
        $merk = $worksheet->getCellByColumnAndRow(6, $row)->getValue();

        // Periksa apakah produk sudah ada
        $stmt = $conn->prepare("SELECT id FROM products WHERE id = ?");
        $stmt->bind_param("s", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Update produk yang ada
            $stmt = $conn->prepare("UPDATE products SET name = ?, hargabeli = ?, stock = ?, kategori = ?, merk = ? WHERE id = ?");
            $stmt->bind_param("ssisss", $name, $hargabeli, $stock, $kategori, $merk, $id);
        } else {
            // Tambah produk baru
            $stmt = $conn->prepare("INSERT INTO products (id, name, hargabeli, stock, kategori, merk) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssiss", $id, $name, $hargabeli, $stock, $kategori, $merk);
        }

        if (!$stmt->execute()) {
            throw new Exception("Error executing statement: " . $stmt->error);
        }

        $stmt->close();
    }

    // Commit transaksi
    $conn->commit();

    // Tutup koneksi
    $conn->close();

    sendJsonResponse('success', 'Data has been imported successfully.');
} catch (Exception $e) {
    // Rollback transaksi jika ada error
    if (isset($conn) && $conn->ping()) {
        $conn->rollback();
        $conn->close();
    }

    logMessage("Error: " . $e->getMessage());
    sendJsonResponse('error', $e->getMessage());
}

// Jika kode sampai di sini, berarti ada yang salah
$unexpectedOutput = ob_get_clean();
if (!empty($unexpectedOutput)) {
    logMessage("Unexpected output: " . $unexpectedOutput);
}
sendJsonResponse('error', 'An unexpected error occurred');
?>