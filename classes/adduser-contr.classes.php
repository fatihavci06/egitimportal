<?php

session_start();

include_once 'packages.classes.php';

class AddUserContr extends AddUser
{
	private $firstName;
	private $lastName;
	private $username;
	private $tckn;
	private $gender;
	private $birth_day;
	private $email;
	private $parentFirstName;
	private $parentLastName;
	private $classes;
	private $pack;
	private $address;
	private $district;
	private $postcode;
	private $city;
	private $telephone;
	private $couponCode;
	private $payment_type;
	private $isinstallment;

	public function __construct($firstName, $lastName, $username, $tckn, $gender, $birth_day, $email, $parentFirstName, $parentLastName, $classes, $pack, $address, $district, $postcode, $city, $telephone, $couponCode, $payment_type, $isinstallment)
	{
		$this->firstName = $firstName;
		$this->lastName = $lastName;
		$this->username = $username;
		$this->tckn = $tckn;
		$this->gender = $gender;
		$this->birth_day = $birth_day;
		$this->email = $email;
		$this->parentFirstName = $parentFirstName;
		$this->parentLastName = $parentLastName;
		$this->classes = $classes;
		$this->pack = $pack;
		$this->parentFirstName = $parentFirstName;
		$this->parentLastName = $parentLastName;
		$this->classes = $classes;
		$this->address = $address;
		$this->district = $district;
		$this->postcode = $postcode;
		$this->city = $city;
		$this->telephone = $telephone;
		$this->couponCode = $couponCode;
		$this->payment_type = $payment_type;
		$this->isinstallment = $isinstallment;
	}

	public function addUserDb()
	{


		$usernameRes = $this->checkUsername($this->username);

		if (count($usernameRes) > 0) {
			echo json_encode(["status" => "error", "message" => "Hata: Bu kullanıcı <br>daha önce kullanılmış!"]);
			die();
		}

		$tcknControl = $this->checkTckn($this->tckn);

		if (count($tcknControl) > 0) {
			echo json_encode(["status" => "error", "message" => "Hata: Bu Türkiye Cumhuriyeti Kimlik Numarası <br>daha önce kullanılmış!"]);
			die();
		}

		$emailRes = $this->checkEmail($this->email);

		if (count($emailRes) > 0) {
			echo json_encode(["status" => "error", "message" => "Hata: Bu e-posta <br>daha önce kullanılmış!"]);
			die();
		}

		$telephoneRes = $this->checkTelephone($this->telephone);

		if (count($telephoneRes) > 0) {
			echo json_encode(["status" => "error", "message" => "Hata: Bu telefon numarası <br>daha önce kullanılmış!"]);
			die();
		}

		$packages = new Packages();

		$couponRes = $packages->checkCoupon($this->couponCode);

		$packDetails = $packages->getPackagePrice($this->pack);

		$packUse = 0;

		if ($this->couponCode) {

			if ($couponRes['coupon_quantity'] == $couponRes['used_coupon_count']) {
				echo json_encode(["status" => "error", "message" => "Kuponun kullanım hakkı kalmamış!"]);
				return;
			}

			$expireDate = new DateTime($couponRes['coupon_expires']);
			$today = new DateTime();

			$expireDateFormatted = $expireDate->format('Y-m-d');
			$todayFormatted = $today->format('Y-m-d');

			if ($expireDateFormatted < $todayFormatted) {
				echo json_encode(["status" => "error", "message" => "Kuponun Süresi Dolmuş!"]);
				return;
			}

			$packUse = 1;

			//$packages->updateUsedCuponCount($this->couponCode);
		}

		$installmentNo = $packDetails[0]['max_installment'];

		$creditCashDiscount = $packDetails[0]['discount'];

		$_SESSION['firstName'] = $this->firstName;
		$_SESSION['lastName'] = $this->lastName;
		$_SESSION['username'] = $this->username;
		$_SESSION['tckn'] = $this->tckn;
		$_SESSION['gender'] = $this->gender;
		$_SESSION['birth_day'] = $this->birth_day;
		$_SESSION['email'] = $this->email;
		$_SESSION['parentFirstName'] = $this->parentFirstName;
		$_SESSION['parentLastName'] = $this->parentLastName;
		$_SESSION['classes'] = $this->classes;
		$_SESSION['pack'] = $this->pack;
		$_SESSION['address'] = $this->address;
		$_SESSION['district'] = $this->district;
		$_SESSION['postcode'] = $this->postcode;
		$_SESSION['city'] = $this->city;
		$_SESSION['telephone'] = $this->telephone;
		$_SESSION['couponCode'] = $this->couponCode;
		$_SESSION['isinstallment'] = $installmentNo;
		$_SESSION['creditCash'] = $creditCashDiscount;
		$_SESSION['packUse'] = $packUse;

		if ($this->payment_type == 2) {
			echo json_encode(["status" => "success", "message" => "Ödeme sayfasına yönlendirileceksiniz.", "type" => "credit_card"]);
		} else {
			echo json_encode(["status" => "success", "message" => "Havale bilgisi sayfasına yönlendirileceksiniz.", "type" => "bank_transfer"]);
		}
		//$this->setStudent($this->firstName, $this->lastName, $this->username, $this->tckn, $this->gender, $this->birth_day, $this->email, $this->parentFirstName, $this->parentLastName, $this->classes, $this->pack);
	}
}
