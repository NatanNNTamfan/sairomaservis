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

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;

$search = isset($_GET['search']) ? $_GET['search'] : '';
$search = str_replace(' ', '', $search);

$sql = "SELECT * FROM products WHERE REPLACE(name, ' ', '') LIKE '%$search%' OR REPLACE(kategori, ' ', '') LIKE '%$search%' OR REPLACE(merk, ' ', '') LIKE '%$search%'";
$result = $conn->query($sql);

$spreadsheet = new Spreadsheet();
$spreadsheet->setActiveSheetIndex(0);
$sheet = $spreadsheet->getActiveSheet();

$sheet->setCellValue('A1', 'ID');
$sheet->setCellValue('B1', 'Name');
$sheet->setCellValue('C1', 'Harga Beli');
$sheet->setCellValue('D1', 'Stock');
$sheet->setCellValue('E1', 'Kategori');
$sheet->setCellValue('F1', 'Merk');

$sheet->getStyle('A1:F1')->getFont()->setBold(true);
$sheet->getStyle('A1:F1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
$sheet->getStyle('A1:F1')->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

$rowNumber = 2;
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $sheet->setCellValue('A' . $rowNumber, $row["id"]);
        $sheet->setCellValue('B' . $rowNumber, $row["name"]);
        $sheet->setCellValue('C' . $rowNumber, 'Rp ' . number_format($row["hargabeli"], 0, ',', '.'));
        $sheet->setCellValue('D' . $rowNumber, $row["stock"]);
        $sheet->setCellValue('E' . $rowNumber, $row["kategori"]);
        $sheet->setCellValue('F' . $rowNumber, $row["merk"]);
        $rowNumber++;
    }
}

foreach (range('A', 'F') as $columnID) {
    $sheet->getColumnDimension($columnID)->setAutoSize(true);
}

$sheet->getStyle('A2:F' . ($rowNumber - 1))->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="inventory_report.xls"');
header('Cache-Control: max-age=0');

$writer = new Xls($spreadsheet);
$writer->save('php://output');
exit;
?>
