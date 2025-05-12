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



