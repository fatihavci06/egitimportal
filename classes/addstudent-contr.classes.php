<?php

class AddStudentContr extends AddStudent
{

	private $photoSize;
	private $photoName;
	private $fileTmpName;
	private $name;
	private $surname;
	private $username;
	private $gender;
	private $birthdate;
	private $email;
	private $telephone;
	private $school;
	private $classes;
	private $parentFirstName;
	private $parentLastName;
	private $address;
	private $district;
	private $postcode;
	private $city;
	private $tckn;
	private $pack;

	public function __construct($photoSize, $photoName, $fileTmpName, $name, $surname, $username, $gender, $birthdate, $email, $telephone, $school, $classes, $parentFirstName, $parentLastName, $address, $district, $postcode, $city, $tckn, $pack)
	{
		$this->photoSize = $photoSize;
		$this->photoName = $photoName;
		$this->fileTmpName = $fileTmpName;
		$this->name = $name;
		$this->surname = $surname;
		$this->username = $username;
		$this->gender = $gender;
		$this->birthdate = $birthdate;
		$this->email = $email;
		$this->telephone = $telephone;
		$this->school = $school;
		$this->classes = $classes;
		$this->parentFirstName = $parentFirstName;
		$this->parentLastName = $parentLastName;
		$this->address = $address;
		$this->district = $district;
		$this->postcode = $postcode;
		$this->city = $city;
		$this->tckn = $tckn;
		$this->pack = $pack;
	}

	public function addStudentDb()
	{

		$slugName = new Slug($this->surname);
		$slug = $slugName->slugify($this->surname);

		/*$slugRes = $this->checkSlug($slug);

		if (count($slugRes) > 0) {
			$ech = end($slugRes);

			$output = substr($ech['slug'], -1, strrpos($ech['slug'], '-'));

			if (!is_numeric($output)) {
				$output = 1;
			} else {
				$output = $output + 1;
			}

			$slug = $slug . "-" . $output;
		} else {
			$slug = $slug;
		}*/

		if ($this->fileTmpName != NULL) {
			$imageSent = new ImageUpload();
			$img = $imageSent->profileImage($this->photoName, $this->photoSize, $this->fileTmpName, $slug);
			$imgName = $img['image'];
		} else {
			if ($this->gender == "Kız") {
				$imgName = 'kiz.jpg';
			} else {
				$imgName = 'erkek.jpg';
			}
		}

		$tcknControl = $this->checkTckn($this->tckn);

		if (count($tcknControl) > 0) {
			echo json_encode(["status" => "error", "message" => "Hata: Bu Türkiye Cumhuriyeti Kimlik Numarası <br>daha önce kullanılmış!"]);
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

		$telephoneRes = $this->checkTelephone($this->telephone);

		if (count($telephoneRes) > 0) {
			echo json_encode(["status" => "error", "message" => "Hata: Bu telefon numarası <br>daha önce kullanılmış!"]);
			die();
		}

		$package = new Packages();

		$packInfo = $package->getPackagePrice(htmlspecialchars(trim($this->pack)));

		foreach ($packInfo as $key => $value) {
			$period = $value['subscription_period'];
		}

		$suAn = new DateTime();

		$bitis = $suAn->modify('+' . $period . ' month');

		$nowTime = date('Y-m-d H:i:s');

		$endTime = $bitis->format('Y-m-d H:i:s');

		$genratedPassword = new CreatePassword();
		$passwordStudent = $genratedPassword->gucluSifreUret(15);
		$passwordStudentHash = password_hash($passwordStudent, PASSWORD_DEFAULT);

		$passwordParent = $genratedPassword->gucluSifreUret(15);
		$passwordParentHash = password_hash($passwordParent, PASSWORD_DEFAULT);

		$parentUsername = $this->username . "-veli";

		$this->setStudent($imgName, $this->name, $this->surname, $this->username, $this->gender, $this->birthdate, $this->email, $this->telephone, $this->school, $this->classes, $this->parentFirstName, $this->parentLastName, $this->address, $this->district, $this->postcode, $this->city, $passwordStudentHash, $passwordParentHash, $parentUsername, $this->tckn, $nowTime, $endTime, $this->pack);

		$mailer = new Mailer();
		$mailer->sendLoginInfoEmail($this->parentFirstName, $this->parentLastName, $this->email, $passwordStudent, $passwordParent, $parentUsername, $this->username);
	}
}
