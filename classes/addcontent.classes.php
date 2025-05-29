<?php

if (session_status() == PHP_SESSION_NONE) {
    // Oturum henüz başlatılmamışsa başlat
    session_start();
}

class AddContent extends Dbh
{

	protected function setContent($imgName, $slug, $name, $classes, $lessons, $units, $short_desc, $topics, $sub_topics, $content, $video_url, $file_urls, $imageFiles, $descriptions, $titles, $urls)
	{
		$pdo = $this->connect();

		$pdo->beginTransaction(); // İşlemleri başlat

		try {

			$stmt = $pdo->prepare('INSERT INTO school_content_lnp SET slug=?, title=?, summary=?, class_id=?, lesson_id=?, unit_id=?, topic_id=?, subtopic_id=?, cover_img=?, text_content=?');

			if (!$stmt->execute([$slug, $name, $short_desc, $classes, $lessons, $units, $topics, $sub_topics, $imgName, $content])) {
				$stmt = null;
				//header("location: ../admin.php?error=stmtfailed");
				exit();
			}

			$mainId = $pdo->lastInsertId();

			if (!empty($video_url)) {

				$stmtFile = $pdo->prepare("INSERT INTO school_content_videos_url SET school_content_id=?, video_url=?");


				if (!$stmtFile->execute([$mainId, $video_url])) {
					$stmtFile = null;
					//header("location: ../admin.php?error=stmtfailed");
					exit();
				}
			}

			// Eğer dosya yüklendiyse ayrı tabloya kaydet
			if (!empty($file_urls)) {

				//$descriptions = $_POST['descriptions'] ?? null;


				foreach ($file_urls as $index => $url) {
					$description = isset($descriptions[$index]) ? $descriptions[$index] : '';
					$stmtFile = $pdo->prepare("INSERT INTO school_content_files_lnp (school_content_id, file_path, description) VALUES (:school_content_id, :file_path, :description)");
					$stmtFile->execute([
						':school_content_id' => $mainId,
						':file_path' => $url,
						':description' => $description
					]);
				}
			}

			if (isset($titles) && isset($urls)) {

				/* $titles = $_POST['wordWallTitles'] ?? null; // ['Başlık1', 'Başlık2', ...]
            $urls = $_POST['wordWallUrls'] ?? null;     // ['url1', 'url2', ...] */

				// Temel güvenlik filtresi
				$titles = array_map('strip_tags', $titles);
				$urls = array_map('strip_tags', $urls);

				// Kayıt işlemi (örneğin veritabanına veya dosyaya yazabilirsiniz)
				for ($i = 0; $i < count($titles); $i++) {
					$title = trim($titles[$i]);
					$url = trim($urls[$i]);

					if ($title !== '' && $url !== '') {
						$stmtFile = $pdo->prepare("INSERT INTO school_content_wordwall_lnp (school_content_id, wordwall_title,wordwall_url) VALUES (:school_content_id, :wordwall_title ,:wordwall_url)");
						$stmtFile->execute([
							':school_content_id' => $mainId,
							':wordwall_title' => $title,
							':wordwall_url' => $url
						]);
					}
				}
			}
			

			$pdo->commit(); // Tüm işlemler başarılıysa commit et
			
			echo json_encode(["status" => "success", "message" => $name]);
			$pdo = null;
		} catch (\Exception $e) {
			$pdo->rollback(); // Bir hata oluşursa tüm işlemleri geri al
			echo json_encode(["status" => "error", "message" => "Bir hata oluştu"]);
			$pdo = null; // PDO bağlantısını kapat
		} finally {
			$pdo = null;
		}
	}


	public function checkSlug($slug)
	{
		$stmt = $this->connect()->prepare('SELECT slug FROM school_content_lnp WHERE slug LIKE ? OR slug = ? ORDER BY slug ASC');

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

class GetContent extends Dbh
{

	public function getContentInfoByIds($topicId, $unitId, $lessonId, $classId)
	{
		$stmt = $this->connect()->prepare('SELECT * FROM school_content_lnp WHERE topic_id = ? AND unit_id = ? AND lesson_id = ? AND class_id = ? AND subtopic_id = ?');

		if (!$stmt->execute([$topicId, $unitId, $lessonId, $classId, 0])) {
			$stmt = null;
			exit();
		}

		$contentData = $stmt->fetchAll(PDO::FETCH_ASSOC);

		return $contentData;

		$stmt = null;

	}

	public function getContentInfoByIdsUnderSubTopic($subTopicId, $topicId, $unitId, $lessonId, $classId)
	{
		$stmt = $this->connect()->prepare('SELECT * FROM school_content_lnp WHERE subtopic_id = ? AND unit_id = ? AND lesson_id = ? AND class_id = ? AND topic_id = ?');

		if (!$stmt->execute([$subTopicId, $unitId, $lessonId, $classId, $topicId])) {
			$stmt = null;
			exit();
		}

		$contentData = $stmt->fetchAll(PDO::FETCH_ASSOC);

		return $contentData;

		$stmt = null;

	}

	protected function setContent($imgName, $slug, $name, $classes, $lessons, $units, $short_desc, $topics, $sub_topics, $content, $video_url, $file_urls, $imageFiles, $descriptions, $titles, $urls)
	{
		$pdo = $this->connect();

		$pdo->beginTransaction(); // İşlemleri başlat

		try {

			$stmt = $pdo->prepare('INSERT INTO school_content_lnp SET slug=?, title=?, summary=?, class_id=?, lesson_id=?, unit_id=?, topic_id=?, subtopic_id=?, cover_img=?, text_content=?');

			if (!$stmt->execute([$slug, $name, $short_desc, $classes, $lessons, $units, $topics, $sub_topics, $imgName, $content])) {
				$stmt = null;
				//header("location: ../admin.php?error=stmtfailed");
				exit();
			}

			$mainId = $pdo->lastInsertId();

			if (!empty($video_url)) {

				$stmtFile = $pdo->prepare("INSERT INTO school_content_videos_url SET school_content_id=?, video_url=?");


				if (!$stmtFile->execute([$mainId, $video_url])) {
					$stmtFile = null;
					//header("location: ../admin.php?error=stmtfailed");
					exit();
				}
			}

			// Eğer dosya yüklendiyse ayrı tabloya kaydet
			if (!empty($file_urls)) {

				//$descriptions = $_POST['descriptions'] ?? null;


				foreach ($file_urls as $index => $url) {
					$description = isset($descriptions[$index]) ? $descriptions[$index] : '';
					$stmtFile = $pdo->prepare("INSERT INTO school_content_files_lnp (school_content_id, file_path, description) VALUES (:school_content_id, :file_path, :description)");
					$stmtFile->execute([
						':school_content_id' => $mainId,
						':file_path' => $url,
						':description' => $description
					]);
				}
			}

			if (isset($titles) && isset($urls)) {

				/* $titles = $_POST['wordWallTitles'] ?? null; // ['Başlık1', 'Başlık2', ...]
            $urls = $_POST['wordWallUrls'] ?? null;     // ['url1', 'url2', ...] */

				// Temel güvenlik filtresi
				$titles = array_map('strip_tags', $titles);
				$urls = array_map('strip_tags', $urls);

				// Kayıt işlemi (örneğin veritabanına veya dosyaya yazabilirsiniz)
				for ($i = 0; $i < count($titles); $i++) {
					$title = trim($titles[$i]);
					$url = trim($urls[$i]);

					if ($title !== '' && $url !== '') {
						$stmtFile = $pdo->prepare("INSERT INTO school_content_wordwall_lnp (school_content_id, wordwall_title,wordwall_url) VALUES (:school_content_id, :wordwall_title ,:wordwall_url)");
						$stmtFile->execute([
							':school_content_id' => $mainId,
							':wordwall_title' => $title,
							':wordwall_url' => $url
						]);
					}
				}
			}
			

			$pdo->commit(); // Tüm işlemler başarılıysa commit et
			
			echo json_encode(["status" => "success", "message" => $name]);
			$pdo = null;
		} catch (\Exception $e) {
			$pdo->rollback(); // Bir hata oluşursa tüm işlemleri geri al
			echo json_encode(["status" => "error", "message" => "Bir hata oluştu"]);
			$pdo = null; // PDO bağlantısını kapat
		} finally {
			$pdo = null;
		}
	}


	public function checkSlug($slug)
	{
		$stmt = $this->connect()->prepare('SELECT slug FROM school_content_lnp WHERE slug LIKE ? OR slug = ? ORDER BY slug ASC');

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