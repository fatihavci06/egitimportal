<?php

header('Content-Type: application/json');
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
	http_response_code(405);
	echo json_encode(['status' => false, 'message' => 'Method not allowed']);
	exit;
}

include_once "../classes/dbh.classes.php";
include_once "../classes/todayword.php";

$todayWordObj = new TodayWord();

try {
	$input = $_POST;

	$requiredFields = ['word', 'body'];
	$missingFields = [];

	foreach ($requiredFields as $field) {
		if (!isset($input[$field]) || empty(trim($input[$field]))) {
			$missingFields[] = $field;
		}
	}

	if (!empty($missingFields)) {
		http_response_code(400);
		echo json_encode([
			'status' => false,
			'message' => 'Gerekli alanlar eksik'
		]);
		exit;
	}

	// Prepare sanitized data
	$word = trim($input['word']);
	$body = trim($input['body']);
	$show_date = isset($input['show_date']) && trim($input['show_date']) !== '' ? trim($input['show_date']) : null;
	$class_id = isset($input['classes']) && trim($input['classes']) !== '' ? (int) $input['classes'] : null;
	$school_id = isset($_SESSION['school_id']) ? (int) $_SESSION['school_id'] : 0;

	// Validations
	if (strlen(string: $word) > 1000) {
		http_response_code(400);
		echo json_encode([
			'status' => false,
			'message' => 'Kelime 1000 karakteri aşamaz'
		]);
		exit;
	}
	if ($show_date != null) {
		if (!DateTime::createFromFormat('Y-m-d', $show_date)) {
			http_response_code(400);
			echo json_encode([
				'status' => false,
				'message' => 'Geçersiz tarih biçimi.'
			]);
			exit;
		}

	}

	// Save to database
	$insertedId = $todayWordObj->createTodaysWord($word, $body, $school_id, $class_id, $show_date);

	echo json_encode([
		'status' => 'success',
		'message' => 'Günün kelimesi başarıyla eklendi',
		'id' => $insertedId
	]);

} catch (PDOException $e) {
	http_response_code(500);
	echo json_encode([
		'status' => false,
		'message' => 'Sunucu hatası'
	]);
} catch (Exception $e) {
	http_response_code(500);
	echo json_encode([
		'status' => false,
		'message' => 'Sunucu hatası'
	]);
}
