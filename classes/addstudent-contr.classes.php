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
	private $password;

	public function __construct($photoSize, $photoName, $fileTmpName, $name, $surname, $username, $gender, $birthdate, $email, $telephone, $school, $classes, $password)
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
		$this->password = $password;
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

		if($this->fileTmpName != NULL){
			$imageSent = new ImageUpload();
			$img = $imageSent->profileImage($this->photoName, $this->photoSize, $this->fileTmpName, $slug);
			$imgName = $img['image'];
		}else{
			if($this->gender == "Kız"){
				$imgName = 'kiz.jpg';
			}else{
				$imgName = 'erkek.jpg';
			}
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

		$this->setStudent($imgName, $this->name, $this->surname, $this->username, $this->gender, $this->birthdate, $this->email, $this->telephone, $this->school, $this->classes, $this->password);
	}
}
