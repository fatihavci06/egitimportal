<?php

class AddAudioBookContr extends AddAudioBook
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

	public function __construct($name, $iframe, $photoSize, $photoName, $fileTmpName, $classAdd, $lesson, $unit, $topic, $subtopic)
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
	}

	public function addAudioBookDb()
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
			$img = $imageSent->audioBookImage($this->photoName, $this->photoSize, $this->fileTmpName, $slug);
			$imgName = $img['image'];
		}else{
			$imgName = 'sesli-kitap.jpg';
		}
	
		$this->setAudioBook($imgName, $slug, $this->name, $this->iframe, $this->classAdd, $this->lesson,$this->unit, $this->topic, $this->subtopic);
	}
}
