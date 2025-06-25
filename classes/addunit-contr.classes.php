<?php

class AddUnitContr extends AddUnit
{

	private $photoSize;
	private $photoName;
	private $fileTmpName;
	private $name;
	private $classes;
	private $lessons;
	private $short_desc;
	private $start_date;
	private $end_date;
	private $unit_order;
	private $development_package_str;

	public function __construct($photoSize, $photoName, $fileTmpName, $name, $classes, $lessons, $short_desc, $start_date, $end_date, $unit_order,$development_package_str )
	{
		$this->photoSize = $photoSize;
		$this->photoName = $photoName;
		$this->fileTmpName = $fileTmpName;
		$this->name = $name;
		$this->classes = $classes;
		$this->lessons = $lessons;
		$this->short_desc = $short_desc;
		$this->start_date = $start_date;
		$this->end_date = $end_date;
		$this->unit_order = $unit_order;
		$this->development_package_str=$development_package_str;
	}

	public function addUnitDb()
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

		if ($this->fileTmpName != NULL) {
			$imageSent = new ImageUpload();
			$img = $imageSent->unitImage($this->photoName, $this->photoSize, $this->fileTmpName, $slug);
			$imgName = $img['image'];
		} else {
			$imgName = 'uniteDefault.jpg';
		}

		$this->setUnit($imgName, $slug, $this->name, $this->classes, $this->lessons, $this->short_desc, $this->start_date, $this->end_date, $this->unit_order,$this->development_package_str);
	}
}

class UpdateUnitContr extends AddUnit
{

	private $photoSize;
	private $photoName;
	private $fileTmpName;
	private $name;
	private $short_desc;
	private $start_date;
	private $end_date;
	private $unit_order;
	private $slug;
	private $development_package_id;

	public function __construct($photoSize, $photoName, $fileTmpName, $name, $short_desc, $start_date, $end_date, $unit_order, $slug,$development_package_id)
	{
		$this->photoSize = $photoSize;
		$this->photoName = $photoName;
		$this->fileTmpName = $fileTmpName;
		$this->name = $name;
		$this->short_desc = $short_desc;
		$this->start_date = $start_date;
		$this->end_date = $end_date;
		$this->unit_order = $unit_order;
		$this->slug = $slug;
		$this->development_package_id=$development_package_id;
	}

	public function updateUnitDb()
	{

		$units = new Units();
		$unitInfo = $units->getOneUnitForDetails($this->slug);

		if ($this->name != $unitInfo[0]['name']) {
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
		}else {
			$slug = $unitInfo[0]['slug'];
		}

		if ($this->fileTmpName != NULL) {
			$imageSent = new ImageUpload();
			$img = $imageSent->unitImage($this->photoName, $this->photoSize, $this->fileTmpName, $slug);
			$imgName = $img['image'];
		} else {
			$imgName = $unitInfo[0]['photo'];
		}

		$unit_id = $unitInfo[0]['id'];

		$this->updateUnit($imgName, $slug, $this->name, $this->short_desc, $this->start_date, $this->end_date, $this->unit_order, $unit_id,$this->development_package_id);
	}
}
