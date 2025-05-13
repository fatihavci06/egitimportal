<?php
include "../classes/dbh.classes.php";
include "../classes/addclasses.classes.php";
include "../classes/addclasses-contr.classes.php";
include "../classes/slug.classes.php";

$service = $_GET['service'] ?? 'update';

$id = $_POST['id'] ?? null;
$description = $_POST['description'] ?? null;



try {
    $db = new Dbh();
    $pdo = $db->connect();
    

    if ($service === 'delete' && $id) {
		
		$sql = 'DELETE FROM mainschool_content_file_lnp WHERE id = ?';
    
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id]);
    
        // Etkilenen satır sayısını kontrol et
        if ($stmt->rowCount() > 0) {
            echo json_encode(['status' => 'success', 'message' => 'Silme başarılı!']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Silme işlemi başarısız!']);
        }
	
		
	
		echo json_encode(['status' => 'success', 'message' => 'Güncelleme başarılı!']);
	}
    if ($service === 'update' && $id) {
        $sql = 'UPDATE mainschool_content_file_lnp SET description = ? WHERE id = ?';
    
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$description, $id]);
    
        // Etkilenen satır sayısını kontrol et
        if ($stmt->rowCount() > 0) {
            echo json_encode(['status' => 'success', 'message' => 'Güncelleme başarılı!']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Güncelleme işlemi başarısız!']);
        }

        echo json_encode(['status' => 'success', 'message' => 'Ekleme başarılı!']);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
