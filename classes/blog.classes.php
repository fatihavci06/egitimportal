<?php

class Blog extends Dbh
{
	public function blogPost()
	{

		$stmt = $this->connect()->prepare('SELECT * FROM psikoloji_blog_lnp ');

		if (!$stmt->execute(array())) {
			$stmt = null;
			exit();
		}

		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}
	
	public function blogPostActives()
	{

		$stmt = $this->connect()->prepare('SELECT * FROM psikoloji_blog_lnp WHERE is_active = 1');

		if (!$stmt->execute(array())) {
			$stmt = null;
			exit();
		}

		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	public function getClasses()
	{

		$stmt = $this->connect()->prepare('SELECT * FROM classes_lnp');

		if (!$stmt->execute(array())) {
			$stmt = null;
			exit();
		}

		$classData = $stmt->fetchAll(PDO::FETCH_ASSOC);

		return $classData;
	}
	
	public function permissionControl($contentId, $userId)
	{

		$stmt = $this->connect()->prepare('
        SELECT 1
        FROM users_lnp u
        WHERE u.id = ?
        AND EXISTS (
            SELECT 1
            FROM main_school_content_lnp mc
            WHERE mc.main_school_class_id = u.class_id
            AND mc.id = ?
        )
    ');

		if (!$stmt->execute([$userId, $contentId])) {
			$stmt = null;
			return false;
		}

		// Sonuç varsa true, yoksa false döndür
		return $stmt->fetch() ? true : false;
	}

	public function getBlogContent($id)
	{
		// İçerik verisini al
		$stmt = $this->connect()->prepare('SELECT * FROM psikoloji_blog_lnp WHERE id = ?');
		if (!$stmt->execute([$id])) {
			return null;
		}

		$classData = $stmt->fetch(PDO::FETCH_ASSOC);

		return $classData;
	}
}
