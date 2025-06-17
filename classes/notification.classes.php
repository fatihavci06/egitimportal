<?php
class NotificationManager extends Dbh
{

	public function createNotification(array $data, array $targets = [])
	{

		$db = $this->connect();
		try {
			$db->beginTransaction();

			// Insert announcement
			$stmt = $db->prepare("
                INSERT INTO notifications_lnp (title, content, start_date, expire_date, created_by, slug ,target_type)
                VALUES (:title, :content, :start_date, :expire_date, :created_by, :slug, :target_type)
            ");



			$stmt->execute([
				':title' => $data['title'],
				':content' => $data['content'],
				':start_date' => $data['start_date'],
				':expire_date' => $data['expire_date'],
				':created_by' => $data['created_by'],
				':slug' => $data['slug'],
				':target_type' => $targets['type']
			]);

			$notificationId = $db->lastInsertId();

			if (!empty($targets['value'])) {
				$targetStmt = $db->prepare("
                    INSERT INTO notification_targets_lnp (notification_id, target_type, target_value)
                    VALUES (:notification_id, :target_type, :target_value)
                ");

				$targetStmt->execute([
					':notification_id' => $notificationId,
					':target_type' => $targets['type'],
					':target_value' => $targets['value']
				]);
			}

			$db->commit();

			return $notificationId;


		} catch (Exception $e) {
			$this->connect()->rollBack();
			throw $e;
		}
	}
	public function getAllNotifications()
	{
		$query = "
			SELECT 
				a.*,
				GROUP_CONCAT(
					CONCAT(at.target_type, ':', at.target_value) 
					SEPARATOR ','
				) AS targets
			FROM 
				notifications_lnp a
			LEFT JOIN 
				notification_targets_lnp at ON a.id = at.notification_id
			GROUP BY 
				a.id
			ORDER BY 
				a.start_date DESC
			";

		$stmt = $this->connect()->prepare($query);
		$stmt->execute();
		$notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);

		// Process the results
		foreach ($notifications as &$notification) {

			$notification['targets'] = $notification['targets'] ? explode(',', $notification['targets']) : [];

			$processedTargets = [];
			foreach ($notification['targets'] as $target) {
				list($type, $value) = explode(':', $target);
				$processedTargets[] = [
					'type' => $type,
					'value' => (int) $value
				];
			}
			$notification['targets'] = $processedTargets;

			$notification['is_active'] = (bool) $notification['is_active'];
		}

		return $notifications;


	}
	public function getNotification(int $id)
	{
		$stmt = $this->connect()->prepare("
            SELECT a.*, u.name as creator_name
            FROM notifications_lnp a
            LEFT JOIN users_lnp u ON a.created_by = u.id
            WHERE a.id = :id
        ");

		$stmt->execute([':id' => $id]);
		$notification = $stmt->fetch(PDO::FETCH_ASSOC);

		if ($notification) {
			// Get targets
			$targetStmt = $this->connect()->prepare("
                SELECT target_type, target_value
                FROM notification_targets_lnp
                WHERE notification_id = :id
            ");
			$targetStmt->execute([':id' => $id]);
			$notification['targets'] = $targetStmt->fetchAll(PDO::FETCH_ASSOC);
		}

		return $notification;
	}
	public function updateNotificationStatus($id, $isActive)
	{
		$db = $this->connect();


		try {
			$stmt = $db->prepare("
            UPDATE notifications_lnp
            SET is_active = :is_active
            WHERE id = :notification_id
        ");

			return $stmt->execute([
				':is_active' => $isActive,
				':notification_id' => $id
			]);
		} catch (Exception $e) {
			return false;
		}
	}
	public function updateNotificationStatusArray($ids, $isActive)
	{
		$db = $this->connect();

		if (empty($ids)) {
			return false;
		}

		// Create placeholders for the IN clause
		$placeholders = implode(',', array_fill(0, count($ids), '?'));

		try {
			$stmt = $db->prepare("
            UPDATE notifications_lnp
            SET is_active = ?
            WHERE id IN ($placeholders)
        ");

			// Merge the is_active value with the IDs
			$params = array_merge([$isActive], $ids);

			return $stmt->execute($params);
		} catch (Exception $e) {
			// Optionally log the exception or handle it
			return false;
		}
	}
	public function getNotificationBySlug($slug)
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
				notifications_lnp a
			LEFT JOIN 
				notification_targets_lnp at ON a.id = at.notification_id
			WHERE 
				a.slug = :slug
			GROUP BY 
				a.id
			LIMIT 1
		";

		$stmt = $this->connect()->prepare($query);
		$stmt->execute([':slug' => $slug]);
		$notification = $stmt->fetch(PDO::FETCH_ASSOC);

		if (!empty($notification['targets'])) {
			$targets = explode(',', $notification['targets']);
			$notification['targets'] = array_map(function ($target) {
				list($type, $value) = explode(':', $target);
				return ['target_type' => $type, 'target_value' => $value];
			}, $targets);
		} else {
			$notification['targets'] = [];
		}

		return $notification;

	}
	public function getNotificationViewers(int $id)
	{
		$stmt = $this->connect()->prepare("
				SELECT 
					u.*, 
					av.viewed_at, 
					r.name AS role_name
				FROM users_lnp u
				JOIN notification_views_lnp av ON av.user_id = u.id
				LEFT JOIN userroles_lnp r ON u.role = r.id
				WHERE av.notification_id = :id
				ORDER BY av.viewed_at DESC
			");

		$stmt->execute([':id' => $id]);
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	public function markAsViewed(int $id, int $userId)
	{
		$stmt = $this->connect()->prepare("
            INSERT IGNORE INTO notification_views_lnp (notification_id, user_id)
            VALUES (:notification_id, :user_id)
        ");

		return $stmt->execute([
			':notification_id' => $id,
			':user_id' => $userId
		]);
	}

	public function getNotificationsWithViewStatus($user_id, $role_id, $class_id)
	{
		$currentDate = date('Y-m-d H:i:s');

		$query = "
        SELECT 
            n.*,
            IF(nv.id IS NULL, 0, 1) AS is_viewed,
            nv.viewed_at
        FROM notifications_lnp n
        LEFT JOIN notification_views_lnp nv ON n.id = nv.notification_id AND nv.user_id = :user_id
        WHERE 
            n.is_active = 1
            AND n.start_date <= :current_date
            AND (n.expire_date IS NULL OR n.expire_date >= :current_date)
            AND (
                n.target_type = 'all'
                
                OR (n.target_type = 'roles' AND EXISTS (
                    SELECT 1 FROM notification_targets_lnp nt1
                    WHERE nt1.notification_id = n.id 
                    AND nt1.target_type = 'roles' 
                    AND nt1.target_value = :role_id
                ))
                
                OR (n.target_type = 'classes' AND EXISTS (
                    SELECT 1 FROM notification_targets_lnp nt2
                    WHERE nt2.notification_id = n.id 
                    AND nt2.target_type = 'classes' 
                    AND nt2.target_value = :class_id
                ))
                
                OR (n.target_type = 'lessons' AND EXISTS (
                    SELECT 1 FROM notification_targets_lnp nt3
                    JOIN lessons_lnp l ON nt3.target_value = l.id
                    WHERE nt3.notification_id = n.id 
                    AND nt3.target_type = 'lessons'
                    AND l.class_id = :class_id
                ))
                
                OR (n.target_type = 'units' AND EXISTS (
                    SELECT 1 FROM notification_targets_lnp nt4
                    JOIN units_lnp u ON nt4.target_value = u.id
                    WHERE nt4.notification_id = n.id 
                    AND nt4.target_type = 'units'
                    AND u.class_id = :class_id
                ))
                
                OR (n.target_type = 'topics' AND EXISTS (
                    SELECT 1 FROM notification_targets_lnp nt5
                    JOIN topics_lnp t ON nt5.target_value = t.id
                    WHERE nt5.notification_id = n.id 
                    AND nt5.target_type = 'topics'
                    AND t.class_id = :class_id
                ))
                
                OR (n.target_type = 'subtopics' AND EXISTS (
                    SELECT 1 FROM notification_targets_lnp nt6
                    JOIN subtopics_lnp st ON nt6.target_value = st.id
                    WHERE nt6.notification_id = n.id 
                    AND nt6.target_type = 'subtopics'
                    AND st.class_id = :class_id
                ))
                
                OR (n.target_type = 'assignments' AND EXISTS (
                    SELECT 1 FROM notification_targets_lnp nt7
                    -- JOIN assignments_lnp a ON nt7.target_value = a.id
                    WHERE nt7.notification_id = n.id 
                    AND nt7.target_type = 'assignments'
                ))
            )
        ORDER BY n.start_date DESC
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

	public function getNotificationsWithViewStatusCoord($user_id, $role_id)
	{
		$currentDate = date('Y-m-d H:i:s');

		$query = "
        SELECT 
            n.*,
            IF(nv.id IS NULL, 0, 1) AS is_viewed,
            nv.viewed_at
        FROM notifications_lnp n
        LEFT JOIN notification_views_lnp nv ON n.id = nv.notification_id AND nv.user_id = :user_id
        WHERE 
            n.is_active = 1
            AND n.start_date <= :current_date
            AND (n.expire_date IS NULL OR n.expire_date >= :current_date)
            AND (
                n.target_type = 'all'
                
                OR (n.target_type = 'roles' AND EXISTS (
                    SELECT 1 FROM notification_targets_lnp nt1
                    WHERE nt1.notification_id = n.id 
                    AND nt1.target_type = 'roles' 
                    AND nt1.target_value = :role_id
                ))
                
                OR (n.target_type = 'lessons' AND EXISTS (
                    SELECT 1 FROM notification_targets_lnp nt3
                    JOIN lessons_lnp l ON nt3.target_value = l.id
                    WHERE nt3.notification_id = n.id 
                    AND nt3.target_type = 'lessons'
                ))
                
                OR (n.target_type = 'units' AND EXISTS (
                    SELECT 1 FROM notification_targets_lnp nt4
                    JOIN units_lnp u ON nt4.target_value = u.id
                    WHERE nt4.notification_id = n.id 
                    AND nt4.target_type = 'units'
                ))
                
                OR (n.target_type = 'topics' AND EXISTS (
                    SELECT 1 FROM notification_targets_lnp nt5
                    JOIN topics_lnp t ON nt5.target_value = t.id
                    WHERE nt5.notification_id = n.id 
                    AND nt5.target_type = 'topics'
                ))
                
                OR (n.target_type = 'subtopics' AND EXISTS (
                    SELECT 1 FROM notification_targets_lnp nt6
                    JOIN subtopics_lnp st ON nt6.target_value = st.id
                    WHERE nt6.notification_id = n.id 
                    AND nt6.target_type = 'subtopics'
                ))
                
                OR (n.target_type = 'assignments' AND EXISTS (
                    SELECT 1 FROM notification_targets_lnp nt7
                    -- JOIN assignments_lnp a ON nt7.target_value = a.id
                    WHERE nt7.notification_id = n.id 
                    AND nt7.target_type = 'assignments'
                ))
            )
        ORDER BY n.start_date DESC
    ";

		$stmt = $this->connect()->prepare($query);
		$stmt->execute([
			':user_id' => $user_id,
			':current_date' => $currentDate,
			':role_id' => $role_id
		]);

		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}


	
	public function addNotificationTargets($notification_id, $targets)
	{
		$sql = "INSERT INTO notification_targets_lnp (notification_id, target_type, target_value) VALUES (?, ?, ?)";
		$stmt = $this->connect()->prepare($sql);

		foreach ($targets as $target) {
			$stmt->execute([
				$notification_id,
				$target['type'], // 'all', 'role', 'grade', 'course', etc.
				$target['value'] ?? null
			]);
		}
	}
	public function getNotificationStats($notification_id)
	{
		$sql = "SELECT 
                    n.*,
                    COUNT(DISTINCT CASE WHEN this_should_target_user THEN u.id END) as total_eligible_users,
                    COUNT(DISTINCT nr.user_id) as read_count
                FROM notifications n
                LEFT JOIN notification_reads nr ON n.id = nr.notification_id
                WHERE n.id = ?
                GROUP BY n.id";

		// Note: You'll need to implement the logic to count eligible users based on targets
		// This is a simplified version

		$stmt = $this->connect()->prepare($sql);
		$stmt->execute([$notification_id]);

		return $stmt->fetch(PDO::FETCH_ASSOC);
	}
	public function getReadUsers($notification_id)
	{
		$sql = "SELECT u.id, u.name, u.email, nr.read_at
                FROM users u
                JOIN notification_reads nr ON u.id = nr.user_id
                WHERE nr.notification_id = ?
                ORDER BY nr.read_at DESC";

		$stmt = $this->connect()->prepare($sql);
		$stmt->execute([$notification_id]);

		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}
	public function deactivateNotification($notification_id)
	{
		$sql = "UPDATE notifications SET is_active = 0 WHERE id = ?";
		$stmt = $this->connect()->prepare($sql);
		return $stmt->execute([$notification_id]);
	}


	//adds
	public function getNotificationsClass($id)
	{

		$stmt = $this->connect()->prepare('SELECT name FROM classes_lnp WHERE id = ?');

		if (!$stmt->execute(array($id))) {
			$stmt = null;
			exit();
		}

		$announcementData = $stmt->fetch(PDO::FETCH_ASSOC);

		return $announcementData['name'];


	}
	public function getNotificationsLesson($id)
	{

		$stmt = $this->connect()->prepare('SELECT name FROM lessons_lnp WHERE id = ?');

		if (!$stmt->execute(array($id))) {
			$stmt = null;
			exit();
		}

		$data = $stmt->fetch(PDO::FETCH_ASSOC);

		return $data['name'];
	}
	public function getNotificationsUnit($id)
	{

		$stmt = $this->connect()->prepare('SELECT name FROM units_lnp WHERE id = ?');

		if (!$stmt->execute(array($id))) {
			$stmt = null;
			exit();
		}

		$data = $stmt->fetch(PDO::FETCH_ASSOC);

		return $data['name'];

	}
	public function getNotificationsTopic($id)
	{

		$stmt = $this->connect()->prepare('SELECT name FROM topics_lnp WHERE id = ?');

		if (!$stmt->execute(array($id))) {
			$stmt = null;
			exit();
		}

		$data = $stmt->fetch(PDO::FETCH_ASSOC);

		return $data['name'];

	}
	public function getNotificationsSubtopic($id)
	{

		$stmt = $this->connect()->prepare('SELECT name FROM subtopics_lnp WHERE id = ?');

		if (!$stmt->execute(array($id))) {
			$stmt = null;
			exit();
		}

		$data = $stmt->fetch(PDO::FETCH_ASSOC);

		return $data['name'];

	}
	
	public function getNotificationsRole($userRole_id)
	{

		$stmt = $this->connect()->prepare('SELECT name FROM userroles_lnp WHERE id = ?');

		if (!$stmt->execute(array($userRole_id))) {
			$stmt = null;
			exit();
		}

		$announcementData = $stmt->fetch(PDO::FETCH_ASSOC);

		return $announcementData['name'];

	}


}

