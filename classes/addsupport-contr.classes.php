<?php
include_once "dateformat.classes.php";
session_start();

class AddSupportContr extends AddSupport
{

	private $photoSize;
	private $photoName;
	private $fileTmpName;
	private $subject;
	private $title;
	private $comment;

	public function __construct($subject, $title, $comment, $photoSize, $photoName, $fileTmpName)
	{
		$this->photoSize = $photoSize;
		$this->photoName = $photoName;
		$this->fileTmpName = $fileTmpName;
		$this->subject = $subject;
		$this->title = $title;
		$this->comment = $comment;
	}

	public function addSupportDb()
	{
		$slugName = new Slug($this->title);
		$slug = $slugName->slugify($this->title);

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
			$img = $imageSent->supportImage($this->photoName, $this->photoSize, $this->fileTmpName, $slug);
			$imgName = $img['image'];
		} else {
			$imgName = NULL;
		}


		$this->setSupport($imgName, $slug, $this->subject, $this->title, $this->comment, $_SESSION['id']);
	}
}

class AddSupportResponseContr extends AddSupport
{

	private $writer;
	private $supId;
	private $comment;
	private $openBy;
	private $title;
	private $subject;

	public function __construct($writer, $supId, $comment, $openBy, $title, $subject)
	{
		$this->writer = $writer;
		$this->supId = $supId;
		$this->comment = $comment;
		$this->openBy = $openBy;
		$this->title = $title;
		$this->subject = $subject;
	}

	public function addSupportDb()
	{
		$this->setSupportResponse($this->writer, $this->supId, $this->comment, $this->openBy, $this->title, $this->subject);
	}
}

class AddSupportSolvedContr extends AddSupport
{

	private $supId;

	public function __construct($supId)
	{
		$this->supId = $supId;
	}

	public function addSupportDb()
	{
		$this->setSupportSolved($this->supId);
	}
}