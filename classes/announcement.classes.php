<?php

class Announcement extends Dbh {

	protected function getAnnouncementsList(){

		$stmt = $this->connect()->prepare('SELECT id, name, slug, content, toAll, role_id, class_id, created_at FROM announcements_lnp');

		if(!$stmt->execute(array())){
			$stmt = null;
			exit();
		}

        $announcementData = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $announcementData;

		$stmt = null;
		
	}

	protected function getAnnouncementsForStudentList($role, $class_id){

		$stmt = $this->connect()->prepare('SELECT id, name, slug, content, toAll, role_id, class_id, created_at FROM announcements_lnp WHERE class_id = ? OR role_id = ? OR toAll = ?');

		if(!$stmt->execute(array($class_id, $role, 1))){
			$stmt = null;
			exit();
		}

        $announcementData = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $announcementData;

		$stmt = null;
		
	}

	protected function getAnnouncementsClass($class_id){

		$stmt = $this->connect()->prepare('SELECT name FROM classes_lnp WHERE id = ?');

		if(!$stmt->execute(array($class_id))){
			$stmt = null;
			exit();
		}

        $announcementData = $stmt->fetch(PDO::FETCH_ASSOC);

        return $announcementData['name'];

		$stmt = null;
		
	}

	protected function getAnnouncementsRole($userRole_id){

		$stmt = $this->connect()->prepare('SELECT name FROM userroles_lnp WHERE id = ?');

		if(!$stmt->execute(array($userRole_id))){
			$stmt = null;
			exit();
		}

        $announcementData = $stmt->fetch(PDO::FETCH_ASSOC);

        return $announcementData['name'];

		$stmt = null;
		
	}

	public function getOneAnnouncement($slug){

		$stmt = $this->connect()->prepare('SELECT * FROM schools_lnp WHERE slug = ?');

		if(!$stmt->execute(array($slug))){
			$stmt = null;
			exit();
		}

        $announcementData = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $announcementData;

		$stmt = null;
		
	}

	public function getAnnouncements(){

		$stmt = $this->connect()->prepare('SELECT * FROM schools_lnp');

		if(!$stmt->execute(array(0))){
			$stmt = null;
			exit();
		}

        $announcementData = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $announcementData;

		$stmt = null;
		
	}


}

class AnnouncementForUsers extends Dbh {

	public function getAnnouncementsForUsersList($role, $class, $id){
	
		$stmt = $this->connect()->prepare('SELECT id, name, slug, content, toAll, role_id, class_id, created_at FROM announcements_lnp WHERE (toAll = ? OR role_id = ? OR class_id = ?) AND addBy != ?');
	
		if(!$stmt->execute(array(1, $role, $class, $id))){
			$stmt = null;
			exit();
		}
	
		$announcementData = $stmt->fetchAll(PDO::FETCH_ASSOC);
	
		return $announcementData;
	
		$stmt = null;
		
	}
	
	protected function getAnnouncementsForUsersClass($class_id){
	
		$stmt = $this->connect()->prepare('SELECT name FROM classes_lnp WHERE id = ?');
	
		if(!$stmt->execute(array($class_id))){
			$stmt = null;
			exit();
		}
	
		$announcementData = $stmt->fetch(PDO::FETCH_ASSOC);
	
		return $announcementData['name'];
	
		$stmt = null;
		
	}
	
	protected function getAnnouncementsForUsersRole($userRole_id){
	
		$stmt = $this->connect()->prepare('SELECT name FROM userroles_lnp WHERE id = ?');
	
		if(!$stmt->execute(array($userRole_id))){
			$stmt = null;
			exit();
		}
	
		$announcementData = $stmt->fetch(PDO::FETCH_ASSOC);
	
		return $announcementData['name'];
	
		$stmt = null;
		
	}
	
	public function getOneAnnouncementForUsers($slug){
	
		$stmt = $this->connect()->prepare('SELECT * FROM schools_lnp WHERE slug = ?');
	
		if(!$stmt->execute(array($slug))){
			$stmt = null;
			exit();
		}
	
		$announcementData = $stmt->fetchAll(PDO::FETCH_ASSOC);
	
		return $announcementData;
	
		$stmt = null;
		
	}
	
	public function getAnnouncementsForUsers(){
	
		$stmt = $this->connect()->prepare('SELECT * FROM schools_lnp');
	
		if(!$stmt->execute(array(0))){
			$stmt = null;
			exit();
		}
	
		$announcementData = $stmt->fetchAll(PDO::FETCH_ASSOC);
	
		return $announcementData;
	
		$stmt = null;
		
	}
	
	
}

class Notification extends Dbh {

	protected function getNotificationsList(){
	
		$stmt = $this->connect()->prepare('SELECT id, name, slug, content, toAll, role_id, class_id, created_at FROM notifications_lnp');
	
		if(!$stmt->execute(array())){
			$stmt = null;
			exit();
		}
	
		$announcementData = $stmt->fetchAll(PDO::FETCH_ASSOC);
	
		return $announcementData;
	
		$stmt = null;
		
	}
	
	protected function getNotificationsClass($class_id){
	
		$stmt = $this->connect()->prepare('SELECT name FROM classes_lnp WHERE id = ?');
	
		if(!$stmt->execute(array($class_id))){
			$stmt = null;
			exit();
		}
	
		$announcementData = $stmt->fetch(PDO::FETCH_ASSOC);
	
		return $announcementData['name'];
	
		$stmt = null;
		
	}
	
	protected function getNotificationsRole($userRole_id){
	
		$stmt = $this->connect()->prepare('SELECT name FROM userroles_lnp WHERE id = ?');
	
		if(!$stmt->execute(array($userRole_id))){
			$stmt = null;
			exit();
		}
	
		$announcementData = $stmt->fetch(PDO::FETCH_ASSOC);
	
		return $announcementData['name'];
	
		$stmt = null;
		
	}
	
	public function getOneNotification($slug){
	
		$stmt = $this->connect()->prepare('SELECT * FROM schools_lnp WHERE slug = ?');
	
		if(!$stmt->execute(array($slug))){
			$stmt = null;
			exit();
		}
	
		$announcementData = $stmt->fetchAll(PDO::FETCH_ASSOC);
	
		return $announcementData;
	
		$stmt = null;
		
	}
	
	public function getNotifications(){
	
		$stmt = $this->connect()->prepare('SELECT * FROM schools_lnp');
	
		if(!$stmt->execute(array(0))){
			$stmt = null;
			exit();
		}
	
		$announcementData = $stmt->fetchAll(PDO::FETCH_ASSOC);
	
		return $announcementData;
	
		$stmt = null;
		
	}
	
	
}

class NotificationForUsers extends Dbh {

	public function getNotificationsForUsersList($role, $class, $id){
	
		$stmt = $this->connect()->prepare('SELECT id, name, slug, content, toAll, role_id, class_id, created_at FROM notifications_lnp WHERE (toAll = ? OR role_id = ? OR class_id = ?) AND addBy != ?');
	
		if(!$stmt->execute(array(1, $role, $class, $id))){
			$stmt = null;
			exit();
		}
	
		$announcementData = $stmt->fetchAll(PDO::FETCH_ASSOC);
	
		return $announcementData;
	
		$stmt = null;
		
	}
	
	protected function getNotificationsForUsersClass($class_id){
	
		$stmt = $this->connect()->prepare('SELECT name FROM classes_lnp WHERE id = ?');
	
		if(!$stmt->execute(array($class_id))){
			$stmt = null;
			exit();
		}
	
		$announcementData = $stmt->fetch(PDO::FETCH_ASSOC);
	
		return $announcementData['name'];
	
		$stmt = null;
		
	}
	
	protected function getNotificationsForUsersRole($userRole_id){
	
		$stmt = $this->connect()->prepare('SELECT name FROM userroles_lnp WHERE id = ?');
	
		if(!$stmt->execute(array($userRole_id))){
			$stmt = null;
			exit();
		}
	
		$announcementData = $stmt->fetch(PDO::FETCH_ASSOC);
	
		return $announcementData['name'];
	
		$stmt = null;
		
	}
	
	public function getOneNotificationForUsers($slug){
	
		$stmt = $this->connect()->prepare('SELECT * FROM schools_lnp WHERE slug = ?');
	
		if(!$stmt->execute(array($slug))){
			$stmt = null;
			exit();
		}
	
		$announcementData = $stmt->fetchAll(PDO::FETCH_ASSOC);
	
		return $announcementData;
	
		$stmt = null;
		
	}
	
	public function getNotificationsForUsers(){
	
		$stmt = $this->connect()->prepare('SELECT * FROM schools_lnp');
	
		if(!$stmt->execute(array(0))){
			$stmt = null;
			exit();
		}
	
		$announcementData = $stmt->fetchAll(PDO::FETCH_ASSOC);
	
		return $announcementData;
	
		$stmt = null;
		
	}
	
	
	}