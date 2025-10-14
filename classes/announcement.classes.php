<?php


class AnnouncementManager extends Dbh
{
	public function createAnnouncement(array $data, array $targets = [])
	{

		$db = $this->connect();
		try {
			$db->beginTransaction();

			// Insert announcement
			$stmt = $db->prepare("
                INSERT INTO announcements_lnp (title, content, start_date, expire_date, created_by, slug ,target_type)
                VALUES (:title, :content, :start_date, :expire_date, :created_by, :slug, :target_type)
            ");
			$data['expire_date'] = date('Y-m-d', strtotime('+1 month'));


			$stmt->execute([
				':title' => $data['title'],
				':content' => $data['content'],
				':start_date' => $data['start_date'],
				':expire_date' => $data['expire_date'],
				':created_by' => $data['created_by'],
				':slug' => $data['slug'],
				':target_type' => $targets['type']
			]);

			$announcementId = $db->lastInsertId();


			if (!empty($targets['value'])) {
				$targetStmt = $db->prepare("
                    INSERT INTO announcement_targets_lnp (announcement_id, target_type, target_value)
                    VALUES (:announcement_id, :target_type, :target_value)
                ");

				$targetStmt->execute([
					':announcement_id' => $announcementId,
					':target_type' => $targets['type'],
					':target_value' => $targets['value']
				]);
			}

			$db->commit();

			return $data['title'];


		} catch (Exception $e) {
			$this->connect()->rollBack();
			throw $e;
		}
	}

	public function getAnnouncement(int $id)
	{
		$stmt = $this->connect()->prepare("
            SELECT a.*, u.name as creator_name
            FROM announcements_lnp a
            LEFT JOIN users_lnp u ON a.created_by = u.id
            WHERE a.id = :id
        ");

		$stmt->execute([':id' => $id]);
		$announcement = $stmt->fetch(PDO::FETCH_ASSOC);

		if ($announcement) {
			// Get targets
			$targetStmt = $this->connect()->prepare("
                SELECT target_type, target_value
                FROM announcement_targets_lnp
                WHERE announcement_id = :id
            ");
			$targetStmt->execute([':id' => $id]);
			$announcement['targets'] = $targetStmt->fetchAll(PDO::FETCH_ASSOC);
		}

		return $announcement;
	}
	public function getAnnouncementsByRoleAndClass($role, $class_id)
	{
		$currentDate = date('Y-m-d H:i:s');

		$query = "
			SELECT DISTINCT a.*
			FROM announcements_lnp a
			LEFT JOIN announcement_targets_lnp at ON a.id = at.announcement_id
			WHERE 
				a.is_active = 1
				AND a.start_date <= :current_date
				AND a.expire_date >= :current_date
				AND (
					a.target_type = 'all'
					
					OR (a.target_type = 'roles' AND at.target_type = 'roles' AND at.target_value = :role_id)
					
					OR (a.target_type = 'classes' AND at.target_type = 'classes' AND at.target_value = :class_id)
					
					OR (
						a.target_type IN ('roles', 'classes') 
						AND at.announcement_id = a.id 
						AND (
							(at.target_type = 'roles' AND at.target_value = :role_id)
							OR (at.target_type = 'classes' AND at.target_value = :class_id)
						)
					)
				)
			ORDER BY a.start_date DESC
		";

		$stmt = $this->connect()->prepare($query);
		$stmt->execute([
			':current_date' => $currentDate,
			':role_id' => $role,
			':class_id' => $class_id
		]);
		$announcements = $stmt->fetchAll(PDO::FETCH_ASSOC);
		return $announcements;
	}

	public function markAsViewed(int $announcementId, int $userId)
	{
		$stmt = $this->connect()->prepare("
            INSERT IGNORE INTO announcement_views_lnp (announcement_id, user_id)
            VALUES (:announcement_id, :user_id)
        ");

		return $stmt->execute([
			':announcement_id' => $announcementId,
			':user_id' => $userId
		]);
	}

	public function getAnnouncementViewers(int $announcementId)
	{
		$stmt = $this->connect()->prepare("
				SELECT 
					u.*, 
					av.viewed_at, 
					r.name AS role_name
				FROM users_lnp u
				JOIN announcement_views_lnp av ON av.user_id = u.id
				LEFT JOIN userroles_lnp r ON u.role = r.id
				WHERE av.announcement_id = :id
				ORDER BY av.viewed_at DESC
			");

		$stmt->execute([':id' => $announcementId]);
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}
	public function updateAnnouncementStatus($announcementId, $isActive)
	{
		$db = $this->connect();


		try {
			$stmt = $db->prepare("
            UPDATE announcements_lnp
            SET is_active = :is_active
            WHERE id = :announcement_id
        ");

			return $stmt->execute([
				':is_active' => $isActive,
				':announcement_id' => $announcementId
			]);
		} catch (Exception $e) {
			return false;
		}
	}
	public function updateAnnouncementStatusArray($announcementIds, $isActive)
	{
		$db = $this->connect();

		if (empty($announcementIds)) {
			return false;
		}

		// Create placeholders for the IN clause
		$placeholders = implode(',', array_fill(0, count($announcementIds), '?'));

		try {
			$stmt = $db->prepare("
            UPDATE announcements_lnp
            SET is_active = ?
            WHERE id IN ($placeholders)
        ");

			// Merge the is_active value with the IDs
			$params = array_merge([$isActive], $announcementIds);

			return $stmt->execute($params);
		} catch (Exception $e) {
			// Optionally log the exception or handle it
			return false;
		}
	}

	public function updateAnnouncement(int $id, array $data, array $targets = null)
	{
		try {
			$this->connect()->beginTransaction();

			// Update announcement
			$stmt = $this->connect()->prepare("
                UPDATE announcements_lnp 
                SET title = :title, content = :content, start_date = :start_date, 
                    expire_date = :expire_date, target_type = :target_type,
                    updated_at = CURRENT_TIMESTAMP
                WHERE id = :id
            ");

			$targetType = $targets === null ? 'all' :
				(count($targets) > 1 ? 'mixed' : $targets[0]['type']);

			$stmt->execute([
				':id' => $id,
				':title' => $data['title'],
				':content' => $data['content'],
				':start_date' => $data['start_date'],
				':expire_date' => $data['expire_date'],
				':target_type' => $targetType
			]);

			// Update targets if provided
			if ($targets !== null) {
				// Delete existing targets
				$deleteStmt = $this->connect()->prepare("DELETE FROM announcement_targets_lnp WHERE announcement_id = :id");
				$deleteStmt->execute([':id' => $id]);

				// Insert new targets
				if (!empty($targets)) {
					$targetStmt = $this->connect()->prepare("
                        INSERT INTO announcement_targets_lnp (announcement_id, target_type, target_value)
                        VALUES (:announcement_id, :target_type, :target_value)
                    ");

					foreach ($targets as $target) {
						foreach ($target['values'] as $value) {
							$targetStmt->execute([
								':announcement_id' => $id,
								':target_type' => $target['type'],
								':target_value' => $value
							]);
						}
					}
				}
			}

			$this->connect()->commit();
			return true;

		} catch (Exception $e) {
			$this->connect()->rollBack();
			throw $e;
		}
	}

	public function deactivateExpiredAnnouncements()
	{
		$stmt = $this->connect()->prepare("
            UPDATE announcements_lnp 
            SET is_active = 0 
            WHERE expire_date < NOW() AND is_active = 1
        ");

		$stmt->execute();
		return $stmt->rowCount();
	}
	public function getAllAnnouncements()
	{
		$query = "
			SELECT 
				a.*,
				GROUP_CONCAT(
					CONCAT(at.target_type, ':', at.target_value) 
					SEPARATOR ','
				) AS targets
			FROM 
				announcements_lnp a
			LEFT JOIN 
				announcement_targets_lnp at ON a.id = at.announcement_id
			GROUP BY 
				a.id
			ORDER BY 
				a.start_date DESC
			";

		$stmt = $this->connect()->prepare($query);
		$stmt->execute();
		$announcements = $stmt->fetchAll(PDO::FETCH_ASSOC);

		// Process the results
		foreach ($announcements as &$announcement) {

			$announcement['targets'] = $announcement['targets'] ? explode(',', $announcement['targets']) : [];

			$processedTargets = [];
			foreach ($announcement['targets'] as $target) {
				list($type, $value) = explode(':', $target);
				$processedTargets[] = [
					'type' => $type,
					'value' => (int) $value
				];
			}
			$announcement['targets'] = $processedTargets;

			$announcement['is_active'] = (bool) $announcement['is_active'];
		}

		return $announcements;


	}
	public function getAnnouncementsClass($class_id)
	{

		$stmt = $this->connect()->prepare('SELECT name FROM classes_lnp WHERE id = ?');

		if (!$stmt->execute(array($class_id))) {
			$stmt = null;
			exit();
		}

		$announcementData = $stmt->fetch(PDO::FETCH_ASSOC);

		return $announcementData['name'];

		$stmt = null;

	}
	public function getAnnouncementsRole($userRole_id)
	{

		$stmt = $this->connect()->prepare('SELECT name FROM userroles_lnp WHERE id = ?');

		if (!$stmt->execute(array($userRole_id))) {
			$stmt = null;
			exit();
		}

		$announcementData = $stmt->fetch(PDO::FETCH_ASSOC);

		return $announcementData['name'];

		$stmt = null;

	}

	public function getAnnouncementBySlug($slug)
	{

		// Query to get the announcement by slug
		$query = "
			SELECT 
				a.*,
				GROUP_CONCAT(
					CONCAT(at.target_type, ':', at.target_value) 
					SEPARATOR ','
				) AS targets
			FROM 
				announcements_lnp a
			LEFT JOIN 
				announcement_targets_lnp at ON a.id = at.announcement_id
			WHERE 
				a.slug = :slug
			GROUP BY 
				a.id
			LIMIT 1
		";

		$stmt = $this->connect()->prepare($query);
		$stmt->execute([':slug' => $slug]);
		$announcement = $stmt->fetch(PDO::FETCH_ASSOC);

		if (!empty($announcement['targets'])) {
			$targets = explode(',', $announcement['targets']);
			$announcement['targets'] = array_map(function ($target) {
				list($type, $value) = explode(':', $target);
				return ['target_type' => $type, 'target_value' => $value];
			}, $targets);
		} else {
			$announcement['targets'] = [];
		}

		return $announcement;

	}
	public function getAnnouncementsWithViewStatus($user_id, $role_id, $class_id=null)
	{
		$currentDate = date('Y-m-d H:i:s');
		$query = "
			SELECT 
				a.*,
				IF(av.id IS NULL, 0, 1) AS is_viewed,
				av.viewed_at
			FROM announcements_lnp a
			LEFT JOIN announcement_targets_lnp at ON a.id = at.announcement_id
			LEFT JOIN announcement_views_lnp av ON a.id = av.announcement_id AND av.user_id = :user_id
			WHERE 
				a.is_active = 1
				AND a.start_date <= :current_date
				
				AND (
					a.target_type = 'all'
					OR (a.target_type = 'roles' AND EXISTS (
						SELECT 1 FROM announcement_targets_lnp at1 
						WHERE at1.announcement_id = a.id AND at1.target_type = 'roles' AND at1.target_value = :role_id
					))
					OR (a.target_type = 'classes' AND EXISTS (
						SELECT 1 FROM announcement_targets_lnp at2
						WHERE at2.announcement_id = a.id AND at2.target_type = 'classes' AND at2.target_value = :class_id
					))
				)
			GROUP BY a.id
			ORDER BY a.start_date DESC
		";

		$stmt = $this->connect()->prepare($query);
		$stmt->execute([
			':user_id' => $user_id,
			':current_date' => $currentDate,
			':role_id' => $role_id,
			':class_id' => $class_id
		]);

		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	public function getAnnouncementsWithViewStatusCoord($user_id, $role_id)
	{
		$currentDate = date('Y-m-d H:i:s');
		$query = "
			SELECT 
				a.*,
				IF(av.id IS NULL, 0, 1) AS is_viewed,
				av.viewed_at
			FROM announcements_lnp a
			LEFT JOIN announcement_targets_lnp at ON a.id = at.announcement_id
			LEFT JOIN announcement_views_lnp av ON a.id = av.announcement_id AND av.user_id = :user_id
			WHERE 
				a.is_active = 1
				AND a.start_date <= :current_date
				AND a.expire_date >= :current_date
				AND (
					a.target_type = 'all'
					OR (a.target_type = 'roles' AND EXISTS (
						SELECT 1 FROM announcement_targets_lnp at1 
						WHERE at1.announcement_id = a.id AND at1.target_type = 'roles' AND at1.target_value = :role_id
					))
				)
			GROUP BY a.id
			ORDER BY a.start_date DESC
		";

		$stmt = $this->connect()->prepare($query);
		$stmt->execute([
			':user_id' => $user_id,
			':current_date' => $currentDate,
			':role_id' => $role_id
		]);

		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}
}
