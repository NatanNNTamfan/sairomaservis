<?php
include 'config.php';
require 'PHPExcel.php';

$start_date = isset($_GET['start_date']) && !empty($_GET['start_date']) ? $_GET['start_date'] : '1970-01-01';
$end_date = isset($_GET['end_date']) && !empty($_GET['end_date']) ? $_GET['end_date'] : date('Y-m-d');
$start_time = isset($_GET['start_time']) && !empty($_GET['start_time']) ? $_GET['start_time'] : '00:00:00';
$end_time = isset($_GET['end_time']) && !empty($_GET['end_time']) ? $_GET['end_time'] : '23:59:59';
$search = isset($_GET['search']) ? $_GET['search'] : '';
$search = str_replace(' ', '', $search);
$status = isset($_GET['status']) ? $_GET['status'] : 'all';
$status_condition = $status !== 'all' ? "AND s.status = '$status'" : '';

$sql = "SELECT s.id, c.name as customer_name, s.description, s.status, s.cost, s.created_at, s.updated_at, 
               GROUP_CONCAT(p.name SEPARATOR ', ') as used_products,
               (s.cost - IFNULL(SUM(p.hargabeli), 0)) as profit
        FROM services s
        JOIN customers c ON s.customer_id = c.id
        LEFT JOIN service_products sp ON s.id = sp.service_id
        LEFT JOIN products p ON sp.product_id = p.id
        WHERE (s.created_at BETWEEN '$start_date $start_time' AND '$end_date $end_time')
        AND (REPLACE(c.name, ' ', '') LIKE '%$search%' OR REPLACE(s.description, ' ', '') LIKE '%$search%')
        $status_condition
        GROUP BY s.id
        ORDER BY s.created_at DESC";
$result = $conn->query($sql);

$objPHPExcel = new PHPExcel();
$objPHPExcel->setActiveSheetIndex(0);
$sheet = $objPHPExcel->getActiveSheet();

$sheet->setCellValue('A1', 'Service ID');
$sheet->setCellValue('B1', 'Customer Name');
$sheet->setCellValue('C1', 'Description');
$sheet->setCellValue('D1', 'Status');
$sheet->setCellValue('E1', 'Cost');
$sheet->setCellValue('F1', 'Used Products');
$sheet->setCellValue('G1', 'Created At');
$sheet->setCellValue('H1', 'Updated At');
$sheet->setCellValue('I1', 'Profit');

$rowNumber = 2;
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $sheet->setCellValue('A' . $rowNumber, $row["id"]);
        $sheet->setCellValue('B' . $rowNumber, $row["customer_name"]);
        $sheet->setCellValue('C' . $rowNumber, $row["description"]);
        $sheet->setCellValue('D' . $rowNumber, $row["status"]);
        $sheet->setCellValue('E' . $rowNumber, 'Rp ' . number_format($row["cost"], 0, ',', '.'));
        $sheet->setCellValue('F' . $rowNumber, $row["used_products"]);
        $sheet->setCellValue('G' . $rowNumber, $row["created_at"]);
        $sheet->setCellValue('H' . $rowNumber, $row["updated_at"]);
        $sheet->setCellValue('I' . $rowNumber, 'Rp ' . number_format($row["profit"], 0, ',', '.'));
        $rowNumber++;
    }
}

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="service_report.xls"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');
exit;
