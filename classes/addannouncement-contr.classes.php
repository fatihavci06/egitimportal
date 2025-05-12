<?php

class AddAnnouncementContr extends AddAnnouncement
{

	private $name;
	private $toAll;
	private $roles;
	private $classes;
	private $notification;

	public function __construct($name, $roles, $toAll, $classes, $notification)
	{
		$this->name = $name;
		$this->toAll = $toAll;
		$this->roles = $roles;
		$this->classes = $classes;
		$this->notification = $notification;
	}

	public function addAnnouncementDb()
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

		$this->setAnnouncement($this->name, $this->toAll, $this->roles, $this->classes, $this->notification, $slug);
	}
}

class AddNotificationContr extends AddNotification
{

	private $name;
	private $toAll;
	private $roles;
	private $classes;
	private $notification;

	public function __construct($name, $roles, $toAll, $classes, $notification)
	{
		$this->name = $name;
		$this->toAll = $toAll;
		$this->roles = $roles;
		$this->classes = $classes;
		$this->notification = $notification;
	}

	public function addNotificationDb()
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

		$this->setNotification($this->name, $this->toAll, $this->roles, $this->classes, $this->notification, $slug);
	}
}