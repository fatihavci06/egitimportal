<?php

class AddWrittenBookContr extends AddWrittenBook
{

	private $photoSize;
	private $photoName;
	private $fileTmpName;
	private $name;
	private $iframe;
	private $classAdd;
	private $lesson;
	private $unit;
	private $topic;
	private $subtopic;
	private $description;

	public function __construct($name, $iframe, $photoSize, $photoName, $fileTmpName, $classAdd, $lesson, $unit, $topic, $subtopic, $description)
	{
		$this->photoSize = $photoSize;
		$this->photoName = $photoName;
		$this->fileTmpName = $fileTmpName;
		$this->name = $name;
		$this->iframe = $iframe;
		$this->classAdd = $classAdd;
		$this->lesson = $lesson;
		$this->unit = $unit;
		$this->topic = $topic;
		$this->subtopic = $subtopic;
		$this->description = $description;
	}

	public function addWrittenBookDb()
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
			$img = $imageSent->audioBookImage($this->photoName, $this->photoSize, $this->fileTmpName, $slug);
			$imgName = $img['image'];
		} else {
			$imgName = 'sesli-kitap.jpg';
		}

		$this->setWrittenBook($imgName, $slug, $this->name, $this->iframe, $this->classAdd, $this->lesson, $this->unit, $this->topic, $this->subtopic, $this->description);
	}
	public function updateWrittenBookDb($oldWrittenBook)
	{

		$nameChanged = ($this->name !== $oldWrittenBook['book_name']);
		$photoUploaded = ($this->fileTmpName != NULL);
		$imgName = $oldWrittenBook['cover_img'];
		$slug = $oldWrittenBook['slug'];

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
			$img = $imageSent->audioBookImage($this->photoName, $this->photoSize, $this->fileTmpName, $slug);
			$imgName = $img['image'];
		}

		$this->updateWrittenBook($oldWrittenBook['id'], $imgName, $slug, $this->name, $this->iframe, $this->classAdd, $this->lesson, $this->unit, $this->topic, $this->subtopic, $this->description);
	}

}
