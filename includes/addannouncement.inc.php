<?php
// addannouncement.inc.php

header('Content-Type: application/json');


if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
	http_response_code(405);
	echo json_encode(['success' => false, 'message' => 'Method not allowed']);
	exit;
}

include_once "../classes/dbh.classes.php";
include_once "../classes/addannouncement.classes.php";
include_once "../classes/addannouncement-contr.classes.php";
include_once "../classes/slug.classes.php";
require_once "../classes/announcement.classes.php";
$announcementManager = new AnnouncementManager();

try {
	if (!isset($input)) {
		$input = $_POST;
	}
	$requiredFields = ['title', 'content', 'start_date', 'expire_date'];
	$missingFields = [];

	foreach ($requiredFields as $field) {
		if (!isset($input[$field]) || empty(trim($input[$field]))) {
			$missingFields[] = $field;
		}
	}

	if (!empty($missingFields)) {
		http_response_code(400);
		echo json_encode([
			'success' => false,
			'message' => 'Missing required fields: ' . implode(', ', $missingFields)
		]);
		exit;
	}
	
	$announcementData = [
		'title' => trim($input['title']),
		'content' => trim($input['content']),
		'start_date' => $input['start_date'],
		'expire_date' => $input['expire_date'],
		'created_by' => (int) $_SESSION['id']
	];
	

	if (strlen($announcementData['title']) > 255) {
		http_response_code(400);
		echo json_encode([
			'success' => false,
			'message' => 'Title cannot exceed 255 characters'
		]);
		exit;
	}

	$inputDate = new DateTime($input['start_date']);
	$today = new DateTime(date('Y-m-d'));

	if ($inputDate < $today) {
		
		$announcementData['start_date'] = date('Y-m-d H:i:s');
	}

	// Validate dates
$startDate = DateTime::createFromFormat('Y-m-d', $announcementData['start_date']);
	$expireDate = DateTime::createFromFormat('Y-m-d', $announcementData['expire_date']);

	if (!$startDate) {
		
		http_response_code(400);
		echo json_encode([
			'success' => false,
			'message' => 'Invalid start date format. Use Y-m-d H:i:s'
		]);
		exit;
	}

	if (!$expireDate) {
		http_response_code(400);
		echo json_encode([
			'success' => false,
			'message' => 'Invalid expire date format. Use Y-m-d H:i:s'
		]);
		exit;
	}

	if ($expireDate <= $startDate) {
		http_response_code(400);
		echo json_encode([
			'success' => false,
			'message' => 'Expire date must be after start date'
		]);
		exit;
	}
	$targets = [];

	if (isset($input['target'])) {
		if ($input['target'] == "all") {
			$targets["type"] = "all";
		} elseif ($input["target"] == "classes") {
			$targets["type"] = "classes";
			if (isset($input['target'])) {
				$targets["value"] = (int) $input["classes"];
			} else {
				echo json_encode([
					'success' => false,
					'message' => 'Invalid target format. Each target must have type and values'
				]);
				exit;
			}
		} elseif ($input["target"] == "roles") {
			$targets["type"] = "roles";
			if (isset($input['target'])) {
				$targets["value"] = (int) $input["roles"];
			} else {
				echo json_encode([
					'success' => false,
					'message' => 'Invalid target format. Each target must have type and values'
				]);
				exit;
			}

		}
	}

	$announcement = new AddAnnouncementContr($announcementData, $targets);


	$announcement->addAnnouncementDb();



} catch (PDOException $e) {
	http_response_code(500);
	echo json_encode([
		'success' => false,
		'message' => 'An error occurred' 
	]);

} catch (Exception $e) {
	http_response_code(500);
	echo json_encode([
		'success' => false,
		'message' => 'An error occurred' 
	]);
}

