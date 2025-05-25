<?php

include_once 'createpassword.classes.php';
include_once 'adduser.classes.php';
include_once 'Mailer.php';

class AddSchoolContr extends AddSchool
{

	private $name;
	private $address;
	private $district;
	private $postcode;
	private $city;
	private $email;
	private $telephone;
	private $schoolAdminName;
	private $schoolAdminSurname;
	private $schoolAdminEmail;
	private $schoolAdminTelephone;
	private $schoolCoordinatorName;
	private $schoolCoordinatorSurname;
	private $schoolCoordinatorEmail;
	private $schoolCoordinatorTelephone;

	public function __construct($name, $address, $district, $postcode, $city, $email, $telephone, $schoolAdminName, $schoolAdminSurname, $schoolAdminEmail, $schoolAdminTelephone, $schoolCoordinatorName, $schoolCoordinatorSurname, $schoolCoordinatorEmail, $schoolCoordinatorTelephone)
	{
		$this->name = $name;
		$this->address = $address;
		$this->district = $district;
		$this->postcode = $postcode;
		$this->city = $city;
		$this->email = $email;
		$this->telephone = $telephone;
		$this->schoolAdminName = $schoolAdminName;
		$this->schoolAdminSurname = $schoolAdminSurname;
		$this->schoolAdminEmail = $schoolAdminEmail;
		$this->schoolAdminTelephone = $schoolAdminTelephone;
		$this->schoolCoordinatorName = $schoolCoordinatorName;
		$this->schoolCoordinatorSurname = $schoolCoordinatorSurname;
		$this->schoolCoordinatorEmail = $schoolCoordinatorEmail;
		$this->schoolCoordinatorTelephone = $schoolCoordinatorTelephone;
	}

	public function addSchoolDb()
	{
		$slugName = new Slug($this->name);
		$slug = $slugName->slugify($this->name);

		$slugRes = $this->checkSlug($slug);

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
		}

		$emailRes = $this->checkEmail($this->email);

		if (count($emailRes) > 0) {
			echo json_encode(["status" => "error", "message" => "Hata: Bu e-posta daha önce kullanılmış!"]);
			die();
		}

		if (!empty($this->schoolAdminEmail)) {

			$emailRes2 = $this->checkEmail($this->schoolAdminEmail);

			if (count($emailRes2) > 0) {
				echo json_encode(["status" => "error", "message" => "Hata: Bu e-posta daha önce kullanılmış!"]);
				die();
			}
		}

		if (!empty($this->schoolCoordinatorEmail)) {
			$emailRes3 = $this->checkEmail($this->schoolCoordinatorEmail);

			if (count($emailRes3) > 0) {
				echo json_encode(["status" => "error", "message" => "Hata: Bu e-posta daha önce kullanılmış!"]);
				die();
			}
		}

		$this->setSchool($slug, $this->name, $this->address, $this->district, $this->postcode, $this->city, $this->email, $this->telephone);

		$usersInfo = new AddUser();

		if ($this->schoolAdminName != "" and $this->schoolAdminSurname != "" and $this->schoolAdminEmail != "" and $this->schoolAdminTelephone != "") {
			$createPassword = new CreatePassword();
			$schoolAdminPassword = $createPassword->gucluSifreUret(15);
			$schoolAdminPasswordHash = password_hash($schoolAdminPassword, PASSWORD_DEFAULT);

			$slugAdminName = new Slug($this->schoolAdminName);
			$slugAdminName = $slugAdminName->slugify($this->schoolAdminName);

			$slugAdminSurname = new Slug($this->schoolAdminSurname);
			$slugAdminSurname = $slugAdminSurname->slugify($this->schoolAdminSurname);

			$slugAdmin = $slugAdminName . "-" . $slugAdminSurname;
			$slugAdminRes = $usersInfo->checkUsername($slugAdmin);
			if (count($slugAdminRes) > 0) {
				$ech = end($slugAdminRes);

				$output = substr($ech['username'], -1, strrpos($ech['username'], '-'));

				if (!is_numeric($output)) {
					$output = 1;
				} else {
					$output = $output + 1;
				}

				$slugAdmin = $slugAdmin . "-" . $output;
			} else {
				$slugAdmin = $slugAdmin;
			}

			$this->setSchoolAdmin($this->schoolAdminName, $this->schoolAdminSurname, $slugAdmin, $this->schoolAdminEmail, $this->schoolAdminTelephone, $schoolAdminPasswordHash);

			$mailer = new Mailer();
			$mailer->sendSchoolAdminEmail($this->schoolAdminName, $this->schoolAdminSurname, $this->schoolAdminEmail, $schoolAdminPassword, $slugAdmin, $this->name);
		}

		if ($this->schoolCoordinatorName != "" and $this->schoolCoordinatorSurname != "" and $this->schoolCoordinatorEmail != "" and $this->schoolCoordinatorTelephone != "") {
			$createPassword2 = new CreatePassword();
			$schoolCoordinatorPassword = $createPassword2->gucluSifreUret(15);
			$schoolCoordinatorPasswordHash = password_hash($schoolCoordinatorPassword, PASSWORD_DEFAULT);

			$slugCoordinatorName = new Slug($this->schoolCoordinatorName);
			$slugCoordinatorName = $slugCoordinatorName->slugify($this->schoolCoordinatorName);

			$slugCoordinatorSurname = new Slug($this->schoolCoordinatorSurname);
			$slugCoordinatorSurname = $slugCoordinatorSurname->slugify($this->schoolCoordinatorSurname);

			$slugCoordinator = $slugCoordinatorName . "-" . $slugCoordinatorSurname;
			$slugCoordinatorRes = $usersInfo->checkUsername($slugCoordinator);
			if (count($slugCoordinatorRes) > 0) {
				$ech = end($slugCoordinatorRes);

				$output = substr($ech['username'], -1, strrpos($ech['username'], '-'));

				if (!is_numeric($output)) {
					$output = 1;
				} else {
					$output = $output + 1;
				}

				$slugCoordinator = $slugCoordinator . "-" . $output;
			} else {
				$slugCoordinator = $slugCoordinator;
			}

			$this->setSchoolCoordinator($this->schoolCoordinatorName, $this->schoolCoordinatorSurname, $slugCoordinator, $this->schoolCoordinatorEmail, $this->schoolCoordinatorTelephone, $schoolCoordinatorPasswordHash);
			$mailer = new Mailer();
			$mailer->sendSchoolCoordinatorEmail($this->schoolCoordinatorName, $this->schoolCoordinatorSurname, $this->schoolCoordinatorEmail, $schoolCoordinatorPassword, $slugCoordinator, $this->name);
		}

		echo json_encode(["status" => "success", "message" => $this->name]);
	}
}
