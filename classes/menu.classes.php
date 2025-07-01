<?php

class Menus extends Dbh
{

	protected function getTopMenuList($i)
	{


		//$stmt = $this->connect()->prepare('SELECT menus_lnp.name AS menuName, menus_lnp.slug AS menuSlug, menus_lnp.role AS menuRole, menus_lnp.parent AS menuParent, menusparent_lnp.name AS accordionName, menusparent_lnp.accordion AS accordionNo, menusparent_lnp.classes AS accordionClasses FROM menusparent_lnp INNER JOIN menus_lnp ON menusparent_lnp.accordion = menus_lnp.parent');

		//$stmt = $this->connect()->prepare('SELECT name, slug, role, parent, accordion, classes FROM menus_lnp');
		$stmt = $this->connect()->prepare('SELECT name, classes, role, parent, accordion FROM menusparent_lnp WHERE accordion = ?');

		if (!$stmt->execute(array($i))) {
			$stmt = null;
			exit();
		}

		$menuData = $stmt->fetchAll(PDO::FETCH_ASSOC);

		return $menuData;
	}

	protected function getTopMenuNumber()
	{
		$stmt = $this->connect()->prepare('SELECT accordion FROM menusparent_lnp ORDER BY accordion DESC LIMIT 1');

		if (!$stmt->execute(array())) {
			$stmt = null;
			exit();
		}

		$menuData = $stmt->fetch(PDO::FETCH_ASSOC);

		return $menuData;
	}

	protected function getSubMenuList($i)
	{

		$stmt = $this->connect()->prepare('SELECT name, slug, role, parent, accordion, classes FROM menus_lnp WHERE parent = ?');

		if (!$stmt->execute(array($i))) {
			$stmt = null;
			exit();
		}

		$menuData = $stmt->fetchAll(PDO::FETCH_ASSOC);

		return $menuData;
	}

	protected function getIsActive($slug)
	{

		$stmt = $this->connect()->prepare('SELECT parent FROM menus_lnp WHERE slug = ?');

		if (!$stmt->execute(array($slug))) {
			$stmt = null;
			exit();
		}

		$menuData = $stmt->fetch(PDO::FETCH_ASSOC);

		return $menuData;
	}

	protected function getLessonsList()
	{

		$stmt = $this->connect()->prepare('SELECT name, slug FROM lessons_lnp ');

		if (!$stmt->execute(array())) {
			$stmt = null;
			exit();
		}

		$menuData = $stmt->fetchAll(PDO::FETCH_ASSOC);

		return $menuData;
	}

	protected function getLessonsListForSubMenu($slug)
	{

		$stmt = $this->connect()->prepare('SELECT class_id FROM lessons_lnp WHERE slug = ?');

		if (!$stmt->execute(array($slug))) {
			$stmt = null;
			exit();
		}

		$menuData = $stmt->fetch(PDO::FETCH_ASSOC);

		return $menuData;
	}

	public function getOneMenu($slug)
	{

		$stmt = $this->connect()->prepare('SELECT * FROM menus_lnp WHERE slug = ?');

		if (!$stmt->execute(array($slug))) {
			$stmt = null;
			exit();
		}

		$menuData = $stmt->fetchAll(PDO::FETCH_ASSOC);

		return $menuData;
	}

	public function getLessonTitle($slug)
	{

		$stmt = $this->connect()->prepare('SELECT * FROM lessons_lnp WHERE slug = ?');

		if (!$stmt->execute(array($slug))) {
			$stmt = null;
			exit();
		}

		$menuData = $stmt->fetchAll(PDO::FETCH_ASSOC);

		return $menuData;
	}

	public function getUnitTitle($slug)
	{

		$stmt = $this->connect()->prepare('SELECT * FROM units_lnp WHERE slug = ?');

		if (!$stmt->execute(array($slug))) {
			$stmt = null;
			exit();
		}

		$menuData = $stmt->fetchAll(PDO::FETCH_ASSOC);

		return $menuData;
	}

	public function getTopicTitle($slug)
	{

		$stmt = $this->connect()->prepare('SELECT * FROM topics_lnp WHERE slug = ?');

		if (!$stmt->execute(array($slug))) {
			$stmt = null;
			exit();
		}

		$menuData = $stmt->fetchAll(PDO::FETCH_ASSOC);

		return $menuData;
	}

	public function getGameTitle($slug)
	{

		$stmt = $this->connect()->prepare('SELECT * FROM games_lnp WHERE slug = ?');

		if (!$stmt->execute(array($slug))) {
			$stmt = null;
			exit();
		}

		$menuData = $stmt->fetchAll(PDO::FETCH_ASSOC);

		return $menuData;
	}

	public function getMenus()
	{

		$stmt = $this->connect()->prepare('SELECT * FROM menus_lnp');

		if (!$stmt->execute(array(0))) {
			$stmt = null;
			exit();
		}

		$menuData = $stmt->fetchAll(PDO::FETCH_ASSOC);

		return $menuData;
	}

	 public function getTitleNames($search_slug)
    {
        $pdo = $this->connect(); // PDO bağlantısını al

        // 1. Tüm tablo adlarını al
        $tables_query = $pdo->query("SHOW TABLES");
        if ($tables_query) {
            while ($table_row = $tables_query->fetch(PDO::FETCH_NUM)) {
                $table_name = $table_row[0];

                // Her tablo için 'slug' ve 'name' sütunlarının olup olmadığını kontrol et
                $columns_stmt = $pdo->prepare("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? AND (COLUMN_NAME = 'slug' OR COLUMN_NAME = 'name')");
                if (!$columns_stmt->execute([$table_name])) {
                    error_log("Kolon kontrol sorgusu hatası: " . implode(":", $columns_stmt->errorInfo()));
                    continue;
                }

                $found_columns = [];
                while ($col_row = $columns_stmt->fetch(PDO::FETCH_ASSOC)) {
                    $found_columns[] = $col_row['COLUMN_NAME'];
                }
                $columns_stmt = null;

                // Eğer hem 'slug' hem de 'name' sütunları varsa, tabloda arama yap
                if (in_array('slug', $found_columns) && in_array('name', $found_columns)) {
                    $sql = "SELECT name FROM `" . $table_name . "` WHERE slug = ?";
                    $stmt = $pdo->prepare($sql);

                    if ($stmt->execute([$search_slug])) {
                        $result = $stmt->fetch(PDO::FETCH_ASSOC);
                        if ($result) {
                            // Eşleşme bulundu, sadece 'name' değerini döndür
                            return $result; // Örneğin: ['name' => 'Örnek Başlık']
                        }
                    } else {
                        error_log("Tablo arama hatası: " . implode(":", $stmt->errorInfo()));
                    }
                    $stmt = null;
                }
            }
        } else {
            error_log("Tablo sorgusu hatası: " . implode(":", $pdo->errorInfo()));
        }

        // Hiçbir eşleşme bulunamazsa null döndür
        return null;
    }
}
