<?php

session_start();

class AddNotification extends Dbh
{

	protected function setNotification($data, $targets)
	{

		require_once "notification.classes.php";


		$notificationManager = new NotificationManager();

		$notificationId = $notificationManager->createNotification($data, $targets);


		if ($notificationId) {
			http_response_code(201);
			echo json_encode(["status" => "success", "message" => $notificationId]);
			exit();
		} else {
			http_response_code(500);
			echo json_encode(["status" => false, "message" => "Error"]);
			exit();
		}
	}


	public function checkSlug($slug)
	{
		$stmt = $this->connect()->prepare('SELECT slug FROM notifications_lnp WHERE slug LIKE ? OR slug = ? ORDER BY slug ASC');

		if (!$stmt->execute([$slug . '-%', $slug])) {
			$stmt = null;
			header("location: ../admin.php?error=stmtfailed");
			exit();
		}
		$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
		$stmt = null;

		return $result;
	}
}