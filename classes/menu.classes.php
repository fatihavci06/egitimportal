<?php

class Menus extends Dbh {

	protected function getTopMenuList($i){

	
		//$stmt = $this->connect()->prepare('SELECT menus_lnp.name AS menuName, menus_lnp.slug AS menuSlug, menus_lnp.role AS menuRole, menus_lnp.parent AS menuParent, menusparent_lnp.name AS accordionName, menusparent_lnp.accordion AS accordionNo, menusparent_lnp.classes AS accordionClasses FROM menusparent_lnp INNER JOIN menus_lnp ON menusparent_lnp.accordion = menus_lnp.parent');

		//$stmt = $this->connect()->prepare('SELECT name, slug, role, parent, accordion, classes FROM menus_lnp');
		$stmt = $this->connect()->prepare('SELECT name, classes, role, parent, accordion FROM menusparent_lnp WHERE accordion = ?');

		if(!$stmt->execute(array($i))){
			$stmt = null;
			exit();
		}

        $menuData = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $menuData;
		
	}

	protected function getTopMenuNumber(){
		$stmt = $this->connect()->prepare('SELECT accordion FROM menusparent_lnp ORDER BY accordion DESC LIMIT 1');

		if(!$stmt->execute(array())){
			$stmt = null;
			exit();
		}

        $menuData = $stmt->fetch(PDO::FETCH_ASSOC);

        return $menuData;
	}

	protected function getSubMenuList($i){

		$stmt = $this->connect()->prepare('SELECT name, slug, role, parent, accordion, classes FROM menus_lnp WHERE parent = ?');

		if(!$stmt->execute(array($i))){
			$stmt = null;
			exit();
		}

        $menuData = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $menuData;
		
	}

	protected function getIsActive($slug){

		$stmt = $this->connect()->prepare('SELECT parent FROM menus_lnp WHERE slug = ?');

		if(!$stmt->execute(array($slug))){
			$stmt = null;
			exit();
		}

        $menuData = $stmt->fetch(PDO::FETCH_ASSOC);

        return $menuData;
		
	}

	protected function getLessonsList(){

		$stmt = $this->connect()->prepare('SELECT name, slug FROM lessons_lnp ');

		if(!$stmt->execute(array())){
			$stmt = null;
			exit();
		}

        $menuData = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $menuData;
		
	}

	protected function getLessonsListForSubMenu($slug){

		$stmt = $this->connect()->prepare('SELECT class_id FROM lessons_lnp WHERE slug = ?');

		if(!$stmt->execute(array($slug))){
			$stmt = null;
			exit();
		}

        $menuData = $stmt->fetch(PDO::FETCH_ASSOC);

        return $menuData;
		
	}

	public function getOneMenu($slug){

		$stmt = $this->connect()->prepare('SELECT * FROM menus_lnp WHERE slug = ?');

		if(!$stmt->execute(array($slug))){
			$stmt = null;
			exit();
		}

        $menuData = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $menuData;
		
	}

	public function getLessonTitle($slug){

		$stmt = $this->connect()->prepare('SELECT * FROM lessons_lnp WHERE slug = ?');

		if(!$stmt->execute(array($slug))){
			$stmt = null;
			exit();
		}

        $menuData = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $menuData;
	}

	public function getUnitTitle($slug){

		$stmt = $this->connect()->prepare('SELECT * FROM units_lnp WHERE slug = ?');

		if(!$stmt->execute(array($slug))){
			$stmt = null;
			exit();
		}

        $menuData = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $menuData;
	}

	public function getTopicTitle($slug){

		$stmt = $this->connect()->prepare('SELECT * FROM topics_lnp WHERE slug = ?');

		if(!$stmt->execute(array($slug))){
			$stmt = null;
			exit();
		}

        $menuData = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $menuData;
	}

	public function getGameTitle($slug){

		$stmt = $this->connect()->prepare('SELECT * FROM games_lnp WHERE slug = ?');

		if(!$stmt->execute(array($slug))){
			$stmt = null;
			exit();
		}

        $menuData = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $menuData;
	}

	public function getMenus(){

		$stmt = $this->connect()->prepare('SELECT * FROM menus_lnp');

		if(!$stmt->execute(array(0))){
			$stmt = null;
			exit();
		}

        $menuData = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $menuData;
		
	}


}



