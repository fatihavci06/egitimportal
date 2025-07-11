<?php
header('Content-Type: application/json');
session_start();

require_once "../classes/dbh.classes.php";
require_once "../classes/updatecontent.classes.php";
$contentObj = new GetContent();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $itemId = $input['id'] ?? null;


    if ($itemId) {
        $itemModel = $contentObj->getAllContentDetailsById($itemId);

    }

    if (!$itemModel) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'ID gerekli'
        ]);
        exit;
    }

    try {
        $success = $contentObj->updateApprovementContent($itemModel['id'], true);

        if ($success) {
            echo json_encode([
                'success' => true,
                'message' => 'İşlem başarıyla tamamlandı',
                'new_status' => 'Onaylandı',
                'data' => [
                    'id' => $itemModel['id'],
                    'updated_at' => date('Y-m-d H:i:s')
                ]
            ]);
        } else {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => 'İşlem sırasında bir hata oluştu'
            ]);
        }

    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => 'Sunucu hatası: ' . $e->getMessage()
        ]);
    }
} else {
    http_response_code(405);
    echo json_encode([
        'success' => false,
        'message' => 'Sadece POST istekleri kabul edilir'
    ]);
}
