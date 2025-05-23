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


try {
	// $input = json_decode(file_get_contents('php://input'), true);

	if (!$input) {
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



	// if (isset($input['targets']) && is_array($input['targets'])) {
	// 	foreach ($input['targets'] as $target) {
	// 		if (!isset($target['type']) || !isset($target['values'])) {
	// 			http_response_code(400);

	// 		}

	// 		if (!in_array($target['type'], ['role', 'class'])) {
	// 			http_response_code(400);
	// 			echo json_encode([
	// 				'success' => false,
	// 				'message' => 'Invalid target type. Must be "role" or "class"'
	// 			]);
	// 			exit;
	// 		}

	// 		// Ensure values is an array
	// 		if (!is_array($target['values'])) {
	// 			$target['values'] = [$target['values']];
	// 		}

	// 		// Validate role values
	// 		if ($target['type'] === 'role') {
	// 			$validRoles = ['student', 'teacher', 'admin'];
	// 			foreach ($target['values'] as $role) {
	// 				if (!in_array($role, $validRoles)) {
	// 					http_response_code(400);
	// 					echo json_encode([
	// 						'success' => false,
	// 						'message' => 'Invalid role: ' . $role . '. Valid roles: ' . implode(', ', $validRoles)
	// 					]);
	// 					exit;
	// 				}
	// 			}
	// 		}

	// 		$targets[] = $target;
	// 	}
	// }

	// // Handle single target format (backward compatibility)
	// if (isset($input['target_type']) && isset($input['target_values'])) {
	// 	$targetValues = is_array($input['target_values']) ? $input['target_values'] : [$input['target_values']];
	// 	$targets = [
	// 		[
	// 			'type' => $input['target_type'],
	// 			'values' => $targetValues
	// 		]
	// 	];
	// }



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

