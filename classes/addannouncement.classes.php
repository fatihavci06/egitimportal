<?php

session_start();

class AddAnnouncement extends Dbh
{

	protected function setAnnouncement($name, $toAll, $roles, $classes, $announcement, $slug)
	{
		$stmt = $this->connect()->prepare('INSERT INTO announcements_lnp SET name = ?, content = ?, toAll = ?, role_id = ?, class_id = ?, slug=?, addBy=?');

		if (!$stmt->execute([$name, $announcement, $toAll, $roles, $classes, $slug, $_SESSION['id']])) {
			$stmt = null;
			//header("location: ../admin.php?error=stmtfailed");
			exit();
		}
		echo json_encode(["status" => "success", "message" => $name]);
		$stmt = null;
	}


	public function checkSlug($slug)
	{
		$stmt = $this->connect()->prepare('SELECT slug FROM topics_lnp WHERE slug LIKE ? OR slug = ? ORDER BY slug ASC');

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

class AddNotification extends Dbh
{

	protected function setNotification($name, $toAll, $roles, $classes, $notification, $slug)
	{
		$stmt = $this->connect()->prepare('INSERT INTO notifications_lnp SET name = ?, content = ?, toAll = ?, role_id = ?, class_id = ?, slug=?, addBy=?');

		if (!$stmt->execute([$name, $notification, $toAll, $roles, $classes, $slug, $_SESSION['id']])) {
			$stmt = null;
			//header("location: ../admin.php?error=stmtfailed");
			exit();
		}
		echo json_encode(["status" => "success", "message" => $name]);
		$stmt = null;
	}


	public function checkSlug($slug)
	{
		$stmt = $this->connect()->prepare('SELECT slug FROM topics_lnp WHERE slug LIKE ? OR slug = ? ORDER BY slug ASC');

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