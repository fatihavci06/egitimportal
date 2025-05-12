<?php

include_once "dateformat.classes.php";

class AddWeeklyContr extends AddWeekly 
{

	private $photoSize;
	private $photoName;
	private $fileTmpName;
	private $name;
	private $classes;
	private $lessons;
	private $units;
	private $short_desc;
	private $tarihi;

	public function __construct($photoSize, $photoName, $fileTmpName, $name, $classes, $lessons, $units, $short_desc, $tarihi)
	{
		$this->photoSize = $photoSize;
		$this->photoName = $photoName;
		$this->fileTmpName = $fileTmpName;
		$this->name = $name;
		$this->classes = $classes;
		$this->lessons = $lessons;
		$this->units = $units;
		$this->short_desc = $short_desc;
		$this->tarihi = $tarihi;
	}

	public function addWeeklyDb()
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
			$img = $imageSent->weeklyImage($this->photoName, $this->photoSize, $this->fileTmpName, $slug);
			$imgName = $img['image'];
		} else {
			$imgName = 'konuDefault.jpg';
		}

		$dates = explode(" - ", $this->tarihi);

		$dateFormat = new DateFormat();

		$dbDateStart =  $dateFormat->forDB($dates[0]);
		$dbDateEnd =  $dateFormat->forDB($dates[1]);

		$this->setWeekly($imgName, $slug, $this->name, $this->classes, $this->lessons, $this->units, $this->short_desc, $dbDateStart, $dbDateEnd);

	}
}
