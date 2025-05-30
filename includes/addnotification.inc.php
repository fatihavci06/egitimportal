<?php
// addannouncement.inc.php

header('Content-Type: application/json');



if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
	http_response_code(405);
	echo json_encode(['success' => false, 'message' => 'Method not allowed']);
	exit;
}

include_once "../classes/dbh.classes.php";
include_once "../classes/addnotification.classes.php";
include_once "../classes/addnotification-contr.classes.php";
include_once "../classes/slug.classes.php";


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
	$notificationtData = [
		'title' => trim($input['title']),
		'content' => trim($input['content']),
		'start_date' => $input['start_date'],
		'expire_date' => $input['expire_date'],
		'created_by' => (int) $_SESSION['id']
	];

	if (strlen($notificationtData['title']) > 255) {
		http_response_code(400);
		echo json_encode([
			'success' => false,
			'message' => 'Title cannot exceed 255 characters'
		]);
		exit;
	}

	// Validate dates
	$startDate = DateTime::createFromFormat('Y-m-d', $notificationtData['start_date']);
	$expireDate = DateTime::createFromFormat('Y-m-d', $notificationtData['expire_date']);

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

	if (!isset($input['target'])) {
		http_response_code(400);
		echo json_encode([
			'success' => false,
			'message' => 'Missing required fields'
		]);
		exit;
	}

	$target = $input['target'];

	switch ($target) {
		case 'all':
			$targets["type"] = 'all';
			break;

		case 'classes':

			if (isset($input['subtopics'])) {
				$targets["type"] = 'subtopics';
				$targets["value"] = (int) $input['subtopics'];
				break;

			}


			if (isset($input['topics'])) {
				$targets["type"] = 'topics';
				$targets["value"] = (int) $input['topics'];
				break;


			}
			if (isset($input['units'])) {
				$targets["type"] = 'units';
				$targets["value"] = (int) $input['units'];
				break;


			}

			if (isset($input['lessons'])) {
				$targets["type"] = 'lessons';
				$targets["value"] = (int) $input['lessons'];
				break;


			}

			if (isset($input['classes'])) {
				$targets["type"] = 'classes';
				$targets["value"] = (int) $input['classes'];
				break;


			}

		case 'roles':
			if (!isset($input['roles'])) {
				echo json_encode([
					'success' => false,
					'message' => 'Invalid target format. Each target must have type and values'
				]);
				exit;
			}
			$targets["type"] = 'roles';
			$targets["value"] = (int) $input['roles'];
			break;

		default:
			echo json_encode([
				'success' => false,
				'message' => 'Invalid target format. Each target must have type and values'
			]);
			exit;
			break;

	}



	$announcement = new AddNotificationContr($notificationtData, $targets);




	$announcement->addNotificationDb();



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

