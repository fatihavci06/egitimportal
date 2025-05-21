<?php
require 'vendor/autoload.php'; // composer autoload dosyası

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// Yeni spreadsheet oluştur
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Başlıklar
$sheet->setCellValue('A1', 'Ad');
$sheet->setCellValue('B1', 'Soyad');
$sheet->setCellValue('C1', 'Yaş');

// Veri satırları
$sheet->setCellValue('A2', 'Ahmet');
$sheet->setCellValue('B2', 'Yılmaz');
$sheet->setCellValue('C2', 30);

$sheet->setCellValue('A3', 'Ayşe');
$sheet->setCellValue('B3', 'Demir');
$sheet->setCellValue('C3', 25);

// Excel dosyasını çıktı olarak gönder
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="export.xlsx"');
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
