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

	public function __construct($photoSize, $photoName, $fileTmpName, $name, $classes, $lessons, $short_desc)
	{
		$this->photoSize = $photoSize;
		$this->photoName = $photoName;
		$this->fileTmpName = $fileTmpName;
		$this->name = $name;
		$this->classes = $classes;
		$this->lessons = $lessons;
		$this->short_desc = $short_desc;
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

		if($this->fileTmpName != NULL){
			$imageSent = new ImageUpload();
			$img = $imageSent->unitImage($this->photoName, $this->photoSize, $this->fileTmpName, $slug);
			$imgName = $img['image'];
		}else{
			$imgName = 'uniteDefault.jpg';
		}

		$this->setUnit($imgName, $slug, $this->name, $this->classes, $this->lessons, $this->short_desc);
	}
}
