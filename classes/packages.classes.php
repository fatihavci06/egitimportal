<?php

class Packages extends Dbh {

	public function getPackage($class)
	{
		$stmt = $this->connect()->prepare('SELECT id, name, image FROM packages_lnp WHERE class_id = ? ');

			if (!$stmt->execute(array($class))) {
				$stmt = null;
				exit();
			}

		$classData = $stmt->fetchAll(PDO::FETCH_ASSOC);

		return $classData;

		$stmt = null;
	}

	public function getPackagePrice($id)
	{
		$stmt = $this->connect()->prepare('SELECT monthly_fee, subscription_period FROM packages_lnp WHERE id = ? ');

			if (!$stmt->execute(array($id))) {
				$stmt = null;
				exit();
			}

		$classData = $stmt->fetchAll(PDO::FETCH_ASSOC);

		return $classData;

		$stmt = null;
	}


}

class PackagesForAdmin extends Dbh {

	public function getAllPackages()
	{

		$stmt = $this->connect()->prepare('SELECT packages_lnp.id, packages_lnp.name, packages_lnp.monthly_fee, packages_lnp.subscription_period, packages_lnp.discount, classes_lnp.name AS className FROM packages_lnp INNER JOIN classes_lnp ON packages_lnp.class_id = classes_lnp.id ORDER BY packages_lnp.id ASC'); 

			if (!$stmt->execute(array())) {
				$stmt = null;
				exit();
			}

		$getData = $stmt->fetchAll(PDO::FETCH_ASSOC);

		return $getData;

		$stmt = null;
	}

	public function getOnePackage($id)
	{
		
		$stmt = $this->connect()->prepare('SELECT packages_lnp.id, packages_lnp.name, packages_lnp.monthly_fee, packages_lnp.subscription_period, packages_lnp.discount, classes_lnp.name AS className FROM packages_lnp INNER JOIN classes_lnp ON packages_lnp.class_id = classes_lnp.id WHERE packages_lnp.id = ?'); 

			if (!$stmt->execute(array($id))) {
				$stmt = null;
				exit();
			}

		$getData = $stmt->fetchAll(PDO::FETCH_ASSOC);

		return $getData;

		$stmt = null;
	}

	public function getPackageUsers($id)
	{
		
		$stmt = $this->connect()->prepare('SELECT id, name, surname, photo, subscribed_end FROM users_lnp WHERE package_id = ?'); 

			if (!$stmt->execute(array($id))) {
				$stmt = null;
				exit();
			}

		$getData = $stmt->fetchAll(PDO::FETCH_ASSOC);

		return $getData;

		$stmt = null;
	}

	public function getPackagePrice($id)
	{
		$stmt = $this->connect()->prepare('SELECT monthly_fee, subscription_period FROM packages_lnp WHERE id = ? ');

			if (!$stmt->execute(array($id))) {
				$stmt = null;
				exit();
			}

		$getData = $stmt->fetchAll(PDO::FETCH_ASSOC);

		return $getData;

		$stmt = null;
	}


}

class UpdatePackage extends Dbh {

	/* public function updatePackage($id, $name, $monthly_fee, $subscription_period, $discount)
	{
		$stmt = $this->connect()->prepare('UPDATE packages_lnp SET name = ?, monthly_fee = ?, subscription_period = ?, discount = ? WHERE id = ?');

			if (!$stmt->execute(array($name, $monthly_fee, $subscription_period, $discount, $id))) {
				$stmt = null;
				exit();
			}

		$stmt = null;
	} */

	protected function updatePackage($photo, $name, $surname, $username, $gender, $birthdate, $email, $telephone, $school, $classes, $password){
		$stmt = $this->connect()->prepare('INSERT INTO users_lnp SET photo = ?, name = ?, surname = ?, username = ?, gender = ?, birth_date = ?, email = ?, telephone = ?, school_id = ?, class_id = ?, password=?, role=?, active = ?');

		if(!$stmt->execute([$photo, $name, $surname, $username, $gender, $birthdate, $email, $telephone, $school, $classes, $password, "2", "1"])){
			$stmt = null;
			//header("location: ../admin.php?error=stmtfailed");
			exit();
		}
		echo json_encode(["status" => "success", "message" => $name . ' adlı paket güncellenmiştir.']);
		$stmt = null;
	}

}

