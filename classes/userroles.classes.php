<?php

class Roles extends Dbh {

	protected function getRolesList(){

		$stmt = $this->connect()->prepare('SELECT id, name, slug FROM userroles_lnp');

		if(!$stmt->execute(array())){
			$stmt = null;
			exit();
		}

        $roleData = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $roleData;
		$stmt = null;
		
	}

	protected function getRolesUserNumber($roleId){

		$stmt = $this->connect()->prepare('SELECT COUNT(*) FROM users_lnp WHERE role = ?');

		if(!$stmt->execute(array($roleId))){
			$stmt = null;
			exit();
		}

        $roleData = $stmt->fetchColumn();

        return $roleData;
		$stmt = null;
		
	}

	protected function getRolesDetail(){

		$stmt = $this->connect()->prepare('SELECT id, name, slug, role FROM menusparent_lnp');

		if(!$stmt->execute(array())){
			$stmt = null;
			exit();
		}

        $roleData = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $roleData;
		$stmt = null;
		
	}

	protected function getOneRole($roleSlug){

		$stmt = $this->connect()->prepare('SELECT id, name, slug FROM userroles_lnp WHERE slug = ?');

		if(!$stmt->execute(array($roleSlug))){
			$stmt = null;
			exit();
		}

        $roleData = $stmt->fetch(PDO::FETCH_ASSOC);

        return $roleData;
		$stmt = null;
		
	}

	protected function getRoleUsers($roleId){

		$stmt = $this->connect()->prepare('SELECT id, name, photo, surname, email, created_at FROM users_lnp WHERE role = ?');

		if(!$stmt->execute(array($roleId))){
			$stmt = null;
			exit();
		}

        $roleData = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $roleData;
		$stmt = null;
		
	}

	protected function updateRoles($id, $newRoles){

		$stmt = $this->connect()->prepare('UPDATE menusparent_lnp SET role = ? WHERE id = ?');

		if(!$stmt->execute(array($newRoles, $id))){
			$stmt = null;
			exit();
		}

		$stmt = null;
		
	}


}



