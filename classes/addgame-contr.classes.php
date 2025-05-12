<?php

class AddGameContr extends AddGame
{

	private $photoSize;
	private $photoName;
	private $fileTmpName;
	private $name;
	private $iframe;
	private $classAdd;

	public function __construct($name, $iframe, $photoSize, $photoName, $fileTmpName, $classAdd)
	{
		$this->photoSize = $photoSize;
		$this->photoName = $photoName;
		$this->fileTmpName = $fileTmpName;
		$this->name = $name;
		$this->iframe = $iframe;
		$this->classAdd = $classAdd;
	}

	public function addGameDb()
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
			$img = $imageSent->gameImage($this->photoName, $this->photoSize, $this->fileTmpName, $slug);
			$imgName = $img['image'];
		}else{
			$imgName = 'gameDefault.jpg';
		}

		$this->setGame($imgName, $slug, $this->name, $this->iframe, $this->classAdd);
	}
}
