<?php
include_once "dateformat.classes.php";
include_once 'Mailer.php';
include_once "userslist.classes.php";
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

		$mailer = new Mailer();
		$user = new User();
		$userInfo = $user->getOneUserById($_SESSION['id']);
		$getadminEmail = $user->getlnpAdmin();
		$adminEmail = $getadminEmail[0]['email'];
		$supportNameInfo = new Support();
		$supportNameInfo = $supportNameInfo->getSupportName($this->subject);
		$mailer->sendSupportEmailToAdmin($supportNameInfo['name'], $this->title, $this->comment, $userInfo[0]['name'], $userInfo[0]['surname'], $adminEmail);

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

		$mailer = new Mailer();
		$user = new User();
		$userInfo = $user->getOneUserById($_SESSION['id']);
		if ($this->writer == $this->openBy) {
			$getadminEmail = $user->getlnpAdmin();
			$adminEmail = $getadminEmail[0]['email'];
		} else {
			$getUserEmail = $user->getOneUserById($this->openBy);
			$adminEmail = $getUserEmail[0]['email'];
		}
		$supportNameInfo = new Support();
		$supportNameInfo = $supportNameInfo->getSupportName($this->subject);
		$mailer->sendSupportResponseEmail($supportNameInfo['name'], $this->title, $this->comment, $userInfo[0]['name'], $userInfo[0]['surname'], $adminEmail);

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

		$mailer = new Mailer();
		$user = new User();
		$getadminEmail = $user->getlnpAdmin();
		$adminEmail = $getadminEmail[0]['email'];
		$supportTitleInfo = new Support();
		$supportTitleInfo = $supportTitleInfo->getSupportTitle($this->supId);
		$mailer->sendSupportCompleteEmail($supportTitleInfo['title'], $adminEmail);

		$this->setSupportSolved($this->supId);
	}
}
