<?php

session_start();

class AddAnnouncement extends Dbh
{


	protected function setAnnouncement($data, $targets)
	{


		require_once "announcement.classes.php";


		$announcementManager = new AnnouncementManager();
		// Create the announcement

		$announcement = $announcementManager->createAnnouncement($data, $targets);


		if ($announcement) {

			// $createdAnnouncement = $announcementManager->getAnnouncement($announcement);

			http_response_code(201);
			echo json_encode(["status" => "success", "message" => $announcement]);
			exit();
		} else {
			http_response_code(500);
			echo json_encode(["status" => false, "message" => "Error"]);
			exit();
		}
	}

	// protected function setAnnouncement($name, $toAll, $roles, $classes, $announcement, $slug)
	// {
	// 	$stmt = $this->connect()->prepare('INSERT INTO announcements_lnp SET name = ?, content = ?, toAll = ?, role_id = ?, class_id = ?, slug=?, addBy=?, dueDate=?');

	// 	if (!$stmt->execute([$name, $announcement, $toAll, $roles, $classes, $slug, $_SESSION['id'],"0000-00-00"])) {
	// 		$stmt = null;
	// 		//header("location: ../admin.php?error=stmtfailed");
	// 		exit();
	// 	}
	// 	echo json_encode($_POST);
	// 	die();
	// 	echo json_encode(["status" => "success", "message" => $name]);
	// 	$stmt = null;
	// }


	public function checkSlug($slug)
	{
		$stmt = $this->connect()->prepare('SELECT slug FROM announcements_lnp WHERE slug LIKE ? OR slug = ? ORDER BY slug ASC');

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
