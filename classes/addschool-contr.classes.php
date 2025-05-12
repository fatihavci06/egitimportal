<?php

class AddSchoolContr extends AddSchool
{

	private $name;
	private $address;
	private $district;
	private $postcode;
	private $city;
	private $email;
	private $telephone;

	public function __construct($name, $address, $district, $postcode, $city, $email, $telephone)
	{
		$this->name = $name;
		$this->address = $address;
		$this->district = $district;
		$this->postcode = $postcode;
		$this->city = $city;
		$this->email = $email;
		$this->telephone = $telephone;
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

		$this->setSchool($slug, $this->name, $this->address, $this->district, $this->postcode, $this->city, $this->email, $this->telephone);
	}
}
