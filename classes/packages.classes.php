<?php

class Packages extends Dbh
{

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

	public function checkCoupon($couponCode)
	{
		$stmt = $this->connect()->prepare('SELECT coupon_code, discount_type, discount_value, coupon_expires, coupon_quantity FROM coupon_lnp WHERE coupon_code = ? ');

		if (!$stmt->execute(array($couponCode))) {
			$stmt = null;
			exit();
		}

		$coupon = $stmt->fetch(PDO::FETCH_ASSOC);

		$stmt = null;
		return $coupon;
	}
}
