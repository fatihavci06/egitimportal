<?php
require 'vendor/autoload.php';
include_once "classes/dbh.classes.php";
include_once "classes/classes.classes.php";

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

try {
    if (!isset($_POST['start_date']) || empty($_POST['start_date'])) {
        throw new Exception('Başlangıç tarihi parametresi eksik.');
    }
    if (!isset($_POST['stop_date']) || empty($_POST['stop_date'])) {
        throw new Exception('Bitiş tarihi parametresi eksik.');
    }

    $startDate = $_POST['start_date'] . ' 00:00:00';
    $stopDate = $_POST['stop_date'] . ' 23:59:59';

    $pdo = new Dbh();
    $sql = "SELECT 
        ROW_NUMBER() OVER (ORDER BY u.subscribed_end DESC) AS no,
        CONCAT(u.name, ' ', u.surname) AS ogrenci_adi_soyadi,
        u.identity_id AS ogrenci_tc,
        CONCAT(parent.name, ' ', parent.surname) AS veli_adi_soyadi,
        parent.identity_id AS veli_tc,
        CONCAT(u.address, ' ', u.district, ' / ', u.city) AS adres,
        CONCAT(CAST(ROUND(pp.pay_amount, 2) AS DECIMAL(10,2)), ' ₺') as miktar,
        CONCAT(pkg.name, ' ', cls.name, ' paketi için ödeme') AS aciklama
    FROM users_lnp u
    LEFT JOIN users_lnp parent ON u.parent_id = parent.id
    LEFT JOIN package_payments_lnp pp ON pp.user_id = u.id
    LEFT JOIN packages_lnp pkg ON pkg.id = pp.pack_id
    LEFT JOIN classes_lnp cls ON cls.id = pkg.class_id
    WHERE u.role = 2 
      AND pp.created_at BETWEEN :start_date AND :stop_date
    ORDER BY u.subscribed_end DESC";

    $stmt = $pdo->connect()->prepare($sql);
    $stmt->execute([
        'start_date' => $startDate,
        'stop_date' => $stopDate
    ]);

    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!$results) {
        throw new Exception('Veri bulunamadı.');
    }

    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->setTitle('Fatura');
    // Sayfa başlığı: FATURA LİSTESİ
    $sheet->setCellValue('A1', 'FATURA LİSTESİ');
    $sheet->mergeCells('A1:H1');
    $sheet->getStyle('A1')->applyFromArray([
        'font' => [
            'bold' => true,
            'size' => 14,
        ],
        'alignment' => [
            'horizontal' => Alignment::HORIZONTAL_CENTER,
            'vertical' => Alignment::VERTICAL_CENTER,
        ],
    ]);

    // Başlıklar
    $sheet->fromArray([
        ['NO', 'ÖĞRENCİ ADI SOYADI', 'ÖĞRENCİ T.C.', 'VELİ ADI SOYADI', 'VELİ  T.C.', 'ADRES', 'MİKTAR', 'AÇIKLAMA']
    ], null, 'A2');

    $sheet->getStyle('A2:H2')->applyFromArray([
        'font' => [
            'bold' => true,
        ],
        'fill' => [
            'fillType' => Fill::FILL_NONE,
        ],
    ]);

    // Renkleri tanımla (örnek olarak, dilediğin gibi değiştirebilirsin)

    $columnColors = [
        'B' => 'FFF866', // ÖĞRENCİ ADI SOYADI
        'C' => 'F8CBAD', // ÖĞRENCİ T.C.
        'D' => 'FFF866', // VELİ ADI SOYADI
        'E' => 'F8CBAD', // VELİ T.C.
        'F' => 'FFEDDA', // ADRES
        'G' => 'D9EAD3', // MİKTAR
        'H' => 'D9EAD3'  // AÇIKLAMA
    ];

    $rowIndex = 3;
    foreach ($results as $row) {
        $sheet->setCellValue("A{$rowIndex}", $row['no']);
        $sheet->setCellValue("B{$rowIndex}", $row['ogrenci_adi_soyadi']);
        $sheet->setCellValue("C{$rowIndex}", $row['ogrenci_tc']);
        $sheet->setCellValue("D{$rowIndex}", $row['veli_adi_soyadi']);
        $sheet->setCellValue("E{$rowIndex}", $row['veli_tc']);
        $sheet->setCellValue("F{$rowIndex}", $row['adres']);
        $sheet->setCellValue("G{$rowIndex}", $row['miktar']);
        $sheet->setCellValue("H{$rowIndex}", $row['aciklama']);

        // NO sütunu için arka plan yok (şeffaf)
        $sheet->getStyle("A{$rowIndex}")->getFill()->setFillType(Fill::FILL_NONE);

        // Diğer sütunlar için renk uygula
        foreach ($columnColors as $column => $color) {
            $sheet->getStyle("{$column}{$rowIndex}")->getFill()
                ->setFillType(Fill::FILL_SOLID)
                ->getStartColor()->setRGB($color);
        }

        $rowIndex++;
    }
    $lastRow = $rowIndex - 1;

    // Kalın çizgiler uygula
    $sheet->getStyle("A2:H{$lastRow}")->applyFromArray([
        'borders' => [
            'allBorders' => [
                'borderStyle' => Border::BORDER_THIN,
                'color' => ['argb' => 'FF000000'],
            ],
        ],
    ]);
    // Otomatik sütun genişlikleri
    foreach (range('A', 'H') as $col) {
        $sheet->getColumnDimension($col)->setAutoSize(true);
    }

    // Dosya çıktısı
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="fatura_listesi.xlsx"');
    header('Cache-Control: max-age=0');

    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');
    exit;
} catch (Exception $e) {
    http_response_code(400);
    echo "Hata: " . $e->getMessage();
    exit;
}
