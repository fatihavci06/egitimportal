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

	public function __construct($firstName, $lastName, $username, $tckn, $gender, $birth_day, $email, $parentFirstName, $parentLastName, $classes, $pack, $address, $district, $postcode, $city, $telephone, $couponCode)
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
	}

	public function addUserDb()
	{

		$tcknControl = $this->checkTckn($this->tckn);

		if (count($tcknControl) > 0) {
			echo json_encode(["status" => "error", "message" => "Hata: Bu Türkiye Cumhuriyeti Kimlik Numarası daha önce kullanılmış!"]);
			die();
		}

		$usernameRes = $this->checkUsername($this->username);

		if (count($usernameRes) > 0) {
			echo json_encode(["status" => "error", "message" => "Hata: Bu kullanıcı daha önce kullanılmış!"]);
			die();
		}

		$emailRes = $this->checkEmail($this->email);

		if (count($emailRes) > 0) {
			echo json_encode(["status" => "error", "message" => "Hata: Bu e-posta daha önce kullanılmış!"]);
			die();
		}

		$packages = new Packages();
		$couponRes = $packages->checkCoupon($this->couponCode);
		
		if(!$couponRes){
			echo json_encode(["status" => "error", "message" => "Kupon bulunamadı!"]);
			return;
		}
		
		if ($couponRes !== false) {

			$expireDate = new DateTime($couponRes['coupon_expires']);
			$today = new DateTime();

			$expireDateFormatted = $expireDate->format('Y-m-d');
			$todayFormatted = $today->format('Y-m-d');

			if ($expireDateFormatted < $todayFormatted) {
				echo json_encode(["status" => "error", "message" => "Kuponun Süresi Dolmuş!"]);
				return;
			}

		}

		if ($couponRes['coupon_quantity'] <= 0) {
			echo json_encode(["status" => "error", "message" => "Kuponun kullanım hakkı kalmamış!"]);
			return;
		}

		// if (count($couponRes) < 0) {
		// 	echo json_encode(["status" => "error", "message" => "Hata: Bu kupon kodu daha önce kullanılmış!"]);
		// 	die();
		// }


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

		echo json_encode(["status" => "success", "message" => "Ödeme sayfasına yönlendirileceksiniz."]);

		//$this->setStudent($this->firstName, $this->lastName, $this->username, $this->tckn, $this->gender, $this->birth_day, $this->email, $this->parentFirstName, $this->parentLastName, $this->classes, $this->pack);
	}
}
