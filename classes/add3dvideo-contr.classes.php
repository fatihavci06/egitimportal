<?php
include_once "dateformat.classes.php";

class AddContentContr extends AddContent
{

	private $photoSize;
	private $photoName;
	private $fileTmpName;
	private $name;
	private $classes;
	private $short_desc;
	private $video_url;

	public function __construct($name, $classes, $short_desc, $video_url, $photoSize, $photoName, $fileTmpName)
	{
		$this->photoSize = $photoSize;
		$this->photoName = $photoName;
		$this->fileTmpName = $fileTmpName;
		$this->name = $name;
		$this->classes = $classes;
		$this->short_desc = $short_desc;
		$this->video_url = $video_url;
	}


	public function addContentDb()
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
			$img = $imageSent->threeDVideoImage($this->photoName, $this->photoSize, $this->fileTmpName, $slug);
			$imgName = $img['image'];
		} else {
			$imgName = 'konuDefault.jpg';
		}


		$result = $this->setContent($imgName, $slug, $this->name, $this->classes, $this->short_desc, $this->video_url);
		return $result;

	}
}
