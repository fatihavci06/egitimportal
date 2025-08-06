<?php

class AddGameContr extends AddGame
{

	private $photoSize;
	private $photoName;
	private $fileTmpName;
	private $name;
	private $iframe;
	private $description;
	private $classAdd;
	private $lesson;
	private $unit;
	private $topic;
	private $subtopic;

	public function __construct($name, $iframe, $description, $photoSize, $photoName, $fileTmpName, $classAdd, $lesson, $unit, $topic, $subtopic)
	{
		$this->photoSize = $photoSize;
		$this->photoName = $photoName;
		$this->fileTmpName = $fileTmpName;
		$this->name = $name;
		$this->iframe = $iframe;
		$this->description = $description;
		$this->classAdd = $classAdd;
		$this->lesson = $lesson;
		$this->unit = $unit;
		$this->topic = $topic;
		$this->subtopic = $subtopic;

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

		if ($this->fileTmpName != NULL) {
			$imageSent = new ImageUpload();
			$img = $imageSent->gameImage($this->photoName, $this->photoSize, $this->fileTmpName, $slug);
			$imgName = $img['image'];
		} else {
			$imgName = 'oyun-default.jpg';
		}

		$this->setGame($imgName, $slug, $this->name, $this->iframe, $this->description, $this->classAdd, $this->lesson, $this->unit, $this->topic, $this->subtopic);
	}


	public function updateGameDb($oldGame)
	{
		$nameChanged = ($this->name !== $oldGame['game_name']);
		$photoUploaded = ($this->fileTmpName != NULL);
		$imgName = $oldGame['cover_img'];
		$slug = $oldGame['slug'];

		if ($nameChanged) {

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
		}


		if ($photoUploaded) {
			$imageSent = new ImageUpload();
			$img = $imageSent->gameImage($this->photoName, $this->photoSize, $this->fileTmpName, $slug);
			$imgName = $img['image'];
		}


		$this->updateGame($oldGame['id'], $imgName, $slug, $this->name, $this->iframe, $this->description, $this->classAdd, $this->lesson, $this->unit, $this->topic, $this->subtopic);
	}

}
