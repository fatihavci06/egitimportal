<?php

class Packages extends Dbh
{

	public function getPackage($class)
	{

		/* if($class == 10 OR $class == 11 OR $class == 12){
			$class = 2;
		} */

		$stmt = $this->connect()->prepare('SELECT id, name, image FROM packages_lnp WHERE class_id = ? AND status = ? AND pack_type IS ?');

		if (!$stmt->execute(array($class, 1, NULL))) {
			$stmt = null;
			exit();
		}

		$classData = $stmt->fetchAll(PDO::FETCH_ASSOC);

		return $classData;

		$stmt = null;
	}

	public function getPackagePrice($id)
	{
		$stmt = $this->connect()->prepare('SELECT name, monthly_fee, subscription_period, discount, max_installment FROM packages_lnp WHERE id = ? AND status = ?');

		if (!$stmt->execute(array($id, 1))) {
			$stmt = null;
			exit();
		}

		$classData = $stmt->fetchAll(PDO::FETCH_ASSOC);

		return $classData;

		$stmt = null;
	}

	public function getTransferDiscount()
	{
		$stmt = $this->connect()->prepare('SELECT discount_rate FROM settings_lnp WHERE school_id = ?');

		if (!$stmt->execute(array(1))) {
			$stmt = null;
			exit();
		}

		$classData = $stmt->fetch(PDO::FETCH_ASSOC);

		return $classData;

		$stmt = null;
	}

	public function getVat()
	{
		$stmt = $this->connect()->prepare('SELECT tax_rate FROM settings_lnp WHERE school_id = ?');

		if (!$stmt->execute(array(1))) {
			$stmt = null;
			exit();
		}

		$getData = $stmt->fetch(PDO::FETCH_ASSOC);

		return $getData;

		$stmt = null;
	}

	public function checkCoupon($couponCode)
	{
		$stmt = $this->connect()->prepare('SELECT coupon_code, discount_type, discount_value, coupon_expires, coupon_quantity, used_coupon_count FROM coupon_lnp WHERE coupon_code = :coupon_code');

		if (!$stmt->execute([':coupon_code' => $couponCode])) {
			$stmt = null;
			exit();
		}

		$coupon = $stmt->fetch(PDO::FETCH_ASSOC);

		$stmt = null;

		return $coupon;
	}

	public function updateUsedCuponCount($couponCode)
	{
		$stmt = $this->connect()->prepare('UPDATE coupon_lnp SET used_coupon_count = used_coupon_count + 1 WHERE coupon_code = :coupon_code');

		$update = $stmt->execute([':coupon_code' => $couponCode]);
		$stmt = null;
		return $update;
	}
}

class PackagesForAdmin extends Dbh {

	public function getAllPackages()
	{

		$stmt = $this->connect()->prepare('SELECT packages_lnp.id,packages_lnp.status, packages_lnp.name, packages_lnp.monthly_fee, packages_lnp.subscription_period, packages_lnp.discount, classes_lnp.name AS className, classes_lnp.slug AS classSlug FROM packages_lnp INNER JOIN classes_lnp ON packages_lnp.class_id = classes_lnp.id WHERE packages_lnp.pack_type IS ? ORDER BY packages_lnp.id DESC'); 

			if (!$stmt->execute(array(NULL))) {
				$stmt = null;
				exit();
			}

		$getData = $stmt->fetchAll(PDO::FETCH_ASSOC);

		return $getData;

		$stmt = null;
	}

	public function getAllSchoolarshipPackages()
	{

		$stmt = $this->connect()->prepare('SELECT packages_lnp.id,packages_lnp.status, packages_lnp.name, packages_lnp.monthly_fee, packages_lnp.subscription_period, packages_lnp.discount, classes_lnp.name AS className FROM packages_lnp INNER JOIN classes_lnp ON packages_lnp.class_id = classes_lnp.id WHERE packages_lnp.pack_type = ? ORDER BY packages_lnp.id DESC'); 

			if (!$stmt->execute(array(2))) {
				$stmt = null;
				exit();
			}

		$getData = $stmt->fetchAll(PDO::FETCH_ASSOC);

		return $getData;

		$stmt = null;
	}

	public function getOnePackage($id)
	{
		
		$stmt = $this->connect()->prepare('SELECT packages_lnp.id,packages_lnp.class_id, packages_lnp.name, packages_lnp.monthly_fee, packages_lnp.subscription_period, packages_lnp.discount, classes_lnp.name AS className FROM packages_lnp INNER JOIN classes_lnp ON packages_lnp.class_id = classes_lnp.id WHERE packages_lnp.id = ?'); 

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

	public function getMostUsedPackage(){
		$stmt = $this->connect()->prepare('SELECT packages_lnp.id, packages_lnp.name, COUNT(package_payments_lnp.pack_id) AS user_count, classes_lnp.name AS className FROM packages_lnp LEFT JOIN package_payments_lnp ON packages_lnp.id = package_payments_lnp.pack_id LEFT JOIN classes_lnp ON packages_lnp.class_id = classes_lnp.id GROUP BY packages_lnp.id ORDER BY user_count DESC LIMIT 5');

			if (!$stmt->execute(array())) {
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

