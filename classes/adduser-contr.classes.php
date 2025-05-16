<?php

session_start();

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

	public function __construct($firstName, $lastName, $username, $tckn, $gender, $birth_day, $email, $parentFirstName, $parentLastName, $classes, $pack, $address, $district, $postcode, $city, $telephone)
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

		echo json_encode(["status" => "success", "message" => "Ödeme sayfasına yönlendirileceksiniz."]);

		//$this->setStudent($this->firstName, $this->lastName, $this->username, $this->tckn, $this->gender, $this->birth_day, $this->email, $this->parentFirstName, $this->parentLastName, $this->classes, $this->pack);
	}
}
