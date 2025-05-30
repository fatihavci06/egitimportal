<?php

class AddSupport extends Dbh
{

	protected function setSupport($imgName, $slug, $subject, $title, $comment, $userID)
	{
		$stmt = $this->connect()->prepare('INSERT INTO support_center_lnp SET slug = ?, subject = ?, title = ?, comment = ?, image = ?, writer = ?, openedBy = ?');

		if (!$stmt->execute([$slug, $subject, $title, $comment, $imgName, $userID, $userID])) {
			$stmt = null;
			//header("location: ../admin.php?error=stmtfailed");
			exit();
		}
		echo json_encode(["status" => "success", "message" => "Destek talebi gönderilmiştir!"]);
		$stmt = null;
	}

	protected function setSupportResponse($writer, $supId, $comment, $openBy, $title, $subject)
	{
		$stmt = $this->connect()->prepare('INSERT INTO support_center_lnp SET slug = ?, writer = ?, openedBy = ?, comment = ?, title=?, subject=?');

		if (!$stmt->execute([$supId, $writer, $openBy, $comment, $title, $subject])) {
			$stmt = null;
			//header("location: ../admin.php?error=stmtfailed");
			exit();
		}
		echo json_encode(["status" => "success", "message" => "Gönderildi!"]);
		$stmt = null;
	}

	protected function setSupportSolved($supId)
	{
		$stmt = $this->connect()->prepare('UPDATE support_center_lnp SET completed=? WHERE slug = ?');

		if (!$stmt->execute([1, $supId])) {
			$stmt = null;
			//header("location: ../admin.php?error=stmtfailed");
			exit();
		}
		echo json_encode(["status" => "success", "message" => "Destek çözümlendi!"]);
		$stmt = null;
	}


	protected function checkSlug($slug)
	{
		$stmt = $this->connect()->prepare('SELECT slug FROM support_center_lnp WHERE slug LIKE ? OR slug = ? ORDER BY slug ASC');

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

class Support extends Dbh
{

	public function getSupport($userID)
	{
		//$stmt = $this->connect()->prepare('SELECT support_center_lnp.id, support_center_lnp.slug, support_center_lnp.subject, support_center_lnp.title, support_center_lnp.comment, support_center_lnp.image, support_center_lnp.writer, MAX(support_center_lnp.created_at) AS created_at, users_lnp.name AS userName, users_lnp.surname AS userSurname FROM support_center_lnp INNER JOIN users_lnp ON support_center_lnp.writer = users_lnp.id WHERE support_center_lnp.openedBy = ? GROUP BY support_center_lnp.slug ORDER BY support_center_lnp.created_at DESC');

		$stmt = $this->connect()->prepare('SELECT
			dt.slug, 
		    dt.subject, 
		    dt.title, 
		    dt.comment, 
		    dt.image,
		    dt.writer, 
		    dt.openedBy, 
		    dt.completed, 
		    dt.created_at AS created_at, 
		    dt.updated_at AS updated_at,
			u.name AS userName,
			u.surname AS userSurname,
			scs.name AS subjectName
			FROM
			support_center_lnp dt
			INNER JOIN
			(SELECT slug, MAX(created_at) AS son_olusturma FROM support_center_lnp GROUP BY slug) AS son_talepler
			ON dt.slug = son_talepler.slug AND dt.created_at = son_talepler.son_olusturma
			INNER JOIN
			users_lnp u ON dt.writer = u.id
			INNER JOIN
			support_center_subjects_lnp scs ON scs.id = dt.subject
			 WHERE dt.openedBy =? AND dt.completed =?');

		if (!$stmt->execute([$userID, 0])) {
			$stmt = null;
			//header("location: ../admin.php?error=stmtfailed");
			exit();
		}

		$supportData = $stmt->fetchAll(PDO::FETCH_ASSOC);

		return $supportData;

		$stmt = null;
	}


	public function getSupportAdmin()
	{
		//$stmt = $this->connect()->prepare('SELECT support_center_lnp.id, support_center_lnp.slug, support_center_lnp.subject, support_center_lnp.title, support_center_lnp.comment, support_center_lnp.image, support_center_lnp.writer, MAX(support_center_lnp.created_at) AS created_at, users_lnp.name AS userName, users_lnp.surname AS userSurname FROM support_center_lnp INNER JOIN users_lnp ON support_center_lnp.writer = users_lnp.id WHERE support_center_lnp.openedBy = ? GROUP BY support_center_lnp.slug ORDER BY support_center_lnp.created_at DESC');

		$stmt = $this->connect()->prepare('SELECT
			dt.slug, 
		    dt.subject, 
		    dt.title, 
		    dt.comment, 
		    dt.image,
		    dt.writer, 
		    dt.openedBy, 
		    dt.completed, 
		    dt.created_at AS created_at, 
		    dt.updated_at AS updated_at,
			u.name AS userName,
			u.surname AS userSurname,
			scs.name AS subjectName 
			FROM support_center_lnp dt
			INNER JOIN
			(SELECT slug, MAX(created_at) AS son_olusturma FROM support_center_lnp GROUP BY slug) AS son_talepler
			ON dt.slug = son_talepler.slug AND dt.created_at = son_talepler.son_olusturma
			INNER JOIN
			users_lnp u ON dt.writer = u.id 
			INNER JOIN
			support_center_subjects_lnp scs ON dt.subject = scs.id 
			WHERE dt.completed =?');

		if (!$stmt->execute([0])) {
			$stmt = null;
			//header("location: ../admin.php?error=stmtfailed");
			exit();
		}

		$supportData = $stmt->fetchAll(PDO::FETCH_ASSOC);

		return $supportData;

		$stmt = null;
	}

	public function getSupportSolved($userID)
	{
		//$stmt = $this->connect()->prepare('SELECT support_center_lnp.id, support_center_lnp.slug, support_center_lnp.subject, support_center_lnp.title, support_center_lnp.comment, support_center_lnp.image, support_center_lnp.writer, MAX(support_center_lnp.created_at) AS created_at, users_lnp.name AS userName, users_lnp.surname AS userSurname FROM support_center_lnp INNER JOIN users_lnp ON support_center_lnp.writer = users_lnp.id WHERE support_center_lnp.openedBy = ? GROUP BY support_center_lnp.slug ORDER BY support_center_lnp.created_at DESC');

		$stmt = $this->connect()->prepare('SELECT
		    dt.slug, 
		    dt.subject, 
		    dt.title, 
		    dt.comment, 
		    dt.image,
		    dt.writer, 
		    dt.openedBy, 
		    dt.completed, 
		    dt.created_at AS created_at, 
		    dt.updated_at AS updated_at, ,
			u.name AS userName,
			u.surname AS userSurname,
			scs.name AS subjectName,
			scs.id AS subjectId,
			FROM
			support_center_lnp dt
			INNER JOIN
			(SELECT slug, MAX(created_at) AS son_olusturma FROM support_center_lnp GROUP BY slug) AS son_talepler
			ON dt.slug = son_talepler.slug AND dt.created_at = son_talepler.son_olusturma
			INNER JOIN
			users_lnp u ON dt.writer = u.id 
			INNER JOIN
			support_center_subjects_lnp scs ON scs.id = dt.subject
			WHERE dt.openedBy =? AND dt.completed =?');

		if (!$stmt->execute([$userID, 1])) {
			$stmt = null;
			//header("location: ../admin.php?error=stmtfailed");
			exit();
		}

		$supportData = $stmt->fetchAll(PDO::FETCH_ASSOC);

		return $supportData;

		$stmt = null;
	}

	public function getSupportSolvedAdmin()
	{
		//$stmt = $this->connect()->prepare('SELECT support_center_lnp.id, support_center_lnp.slug, support_center_lnp.subject, support_center_lnp.title, support_center_lnp.comment, support_center_lnp.image, support_center_lnp.writer, MAX(support_center_lnp.created_at) AS created_at, users_lnp.name AS userName, users_lnp.surname AS userSurname FROM support_center_lnp INNER JOIN users_lnp ON support_center_lnp.writer = users_lnp.id WHERE support_center_lnp.openedBy = ? GROUP BY support_center_lnp.slug ORDER BY support_center_lnp.created_at DESC');

		$stmt = $this->connect()->prepare('SELECT
			dt.slug, 
		    dt.subject, 
		    dt.title, 
		    dt.comment, 
		    dt.image,
		    dt.writer, 
		    dt.openedBy, 
		    dt.completed, 
		    dt.created_at AS created_at, 
		    dt.updated_at AS updated_at,
			u.name AS userName,
			u.surname AS userSurname,
			scs.name AS subjectName,
			scs.id AS subjectId,
			FROM
			support_center_lnp dt
			INNER JOIN
			(SELECT slug, MAX(created_at) AS son_olusturma FROM support_center_lnp GROUP BY slug) AS son_talepler
			ON dt.slug = son_talepler.slug AND dt.created_at = son_talepler.son_olusturma
			INNER JOIN
			users_lnp u ON dt.writer = u.id 
			INNER JOIN
			support_center_subjects_lnp scs ON scs.id = dt.subject
			WHERE dt.completed =?');

		if (!$stmt->execute([1])) {
			$stmt = null;
			//header("location: ../admin.php?error=stmtfailed");
			exit();
		}

		$supportData = $stmt->fetchAll(PDO::FETCH_ASSOC);

		return $supportData;

		$stmt = null;
	}

	public function getSupportDetail($userID, $supportID)
	{
		$stmt = $this->connect()->prepare('SELECT support_center_lnp.id, 
		support_center_lnp.slug, 
		support_center_lnp.subject, 
		support_center_lnp.title, 
		support_center_lnp.comment, 
		support_center_lnp.image,
		support_center_lnp.writer, 
		support_center_lnp.openedBy, 
		support_center_lnp.completed, 
		support_center_lnp.created_at AS created_at, 
		support_center_lnp.updated_at AS updated_at, 
		scs.name AS subjectName,
		scs.id AS subjectId,
		users_lnp.name AS userName, 
		users_lnp.surname AS userSurname, 
		users_lnp.photo AS photo 
		FROM support_center_lnp INNER JOIN users_lnp 
		ON support_center_lnp.writer = users_lnp.id 
		INNER JOIN support_center_subjects_lnp scs ON scs.id = support_center_lnp.subject
		WHERE support_center_lnp.openedBy = ? AND support_center_lnp.slug = ? ORDER BY support_center_lnp.created_at DESC');

		/*$stmt = $this->connect()->prepare('SELECT
			dt.*,
			u.name AS userName,
			u.surname AS userSurname
			FROM
			support_center_lnp dt
			INNER JOIN
			(SELECT slug, MAX(created_at) AS son_olusturma FROM support_center_lnp GROUP BY slug) AS son_talepler
			ON dt.slug = son_talepler.slug AND dt.created_at = son_talepler.son_olusturma
			INNER JOIN
			users_lnp u ON dt.writer = u.id WHERE dt.openedBy =? AND dt.completed =? AND dt.id = ? AND dt.response = ? ORDER BY id DESC');*/

		if (!$stmt->execute([$userID, $supportID])) {
			$stmt = null;
			//header("location: ../admin.php?error=stmtfailed");
			exit();
		}

		$supportData = $stmt->fetchAll(PDO::FETCH_ASSOC);

		return $supportData;

		$stmt = null;
	}

	public function getSupportAdminDetail($supportID)
	{
		$stmt = $this->connect()->prepare('SELECT support_center_lnp.id, 
		support_center_lnp.slug, 
		support_center_lnp.subject, 
		support_center_lnp.title, 
		support_center_lnp.comment, 
		support_center_lnp.image, 
		support_center_lnp.writer, 
		support_center_lnp.openedBy, 
		support_center_lnp.completed, 
		support_center_lnp.created_at AS created_at, 
		support_center_lnp.updated_at AS updated_at, 
		scs.name AS subjectName,
		scs.id AS subjectId,
		users_lnp.name AS userName, 
		users_lnp.surname AS userSurname, 
		users_lnp.photo AS photo 
		FROM support_center_lnp INNER JOIN users_lnp 
		ON support_center_lnp.writer = users_lnp.id 
	INNER JOIN
			support_center_subjects_lnp scs ON scs.id = support_center_lnp.subject	
		WHERE support_center_lnp.slug = ? 
		ORDER BY support_center_lnp.created_at DESC');

		/*$stmt = $this->connect()->prepare('SELECT
			dt.*,
			u.name AS userName,
			u.surname AS userSurname
			FROM
			support_center_lnp dt
			INNER JOIN
			(SELECT slug, MAX(created_at) AS son_olusturma FROM support_center_lnp GROUP BY slug) AS son_talepler
			ON dt.slug = son_talepler.slug AND dt.created_at = son_talepler.son_olusturma
			INNER JOIN
			users_lnp u ON dt.writer = u.id WHERE dt.openedBy =? AND dt.completed =? AND dt.id = ? AND dt.response = ? ORDER BY id DESC');*/

		if (!$stmt->execute([$supportID])) {
			$stmt = null;
			//header("location: ../admin.php?error=stmtfailed");
			exit();
		}

		$supportData = $stmt->fetchAll(PDO::FETCH_ASSOC);

		return $supportData;

		$stmt = null;
	}
}
