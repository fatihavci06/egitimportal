<?php

class UserRoleUpdateContr extends Roles
{

	private $role_id;
	private $roleCheck;

	public function __construct($role_id, $roleCheck)
	{
		$this->role_id = $role_id;
		$this->roleCheck = $roleCheck;
	}

	public function updateUserRoleDb()
	{

		$allRoles = $this->getRolesDetail();

		$roleIds = array_column($allRoles, 'id');

		$difference = array_diff($roleIds, $this->roleCheck);

		$same = array_intersect($roleIds, $this->roleCheck);

		foreach ($allRoles as $key => $valueRole) {

			$rolesId = explode(",", $valueRole['role']);

			foreach ($same as $key => $value) {
				if ($valueRole['id'] == $value) {

					if (in_array($this->role_id, $rolesId)) {
					} else {
						$newRole = $valueRole['role'] . ',' . $this->role_id;
						$this->updateRoles($value, $newRole);
					}
				}
			}
			
			foreach ($difference as $key => $valueDiff) {
				if ($valueRole['id'] == $valueDiff) {
					if (in_array($this->role_id, $rolesId)) {

						$remove = $this->role_id;

						$rolesIdNew = array_filter($rolesId, function ($id) use ($remove) {
							return $id !== $remove;
						});

						$newRole = implode(",", $rolesIdNew);

						$this->updateRoles($valueDiff, $newRole);
					} else {
					}
				}
			}

			/*foreach ($this->roleCheck as $key => $value) {

				if ($valueRole['id'] == $value) {

					if (in_array($this->role_id, $rolesId)) {
					} else {
						$newRole = $valueRole['role'] . ',' . $this->role_id;
						$this->updateRoles($value, $newRole);
					}
				}
			}*/
			/*if (in_array($this->role_id, $rolesId)) {

				$remove = $this->role_id;

				$rolesIdNew = array_filter($rolesId, function ($id) use ($remove) {
					return $id !== $remove;
				});

				$newRole = implode(",", $rolesIdNew);

				$this->updateRoles($valueRole['id'], $newRole);
			} else {

			}*/
		}
		echo json_encode(["status" => "success", "message" => "Başarılı"]);
	}
}
