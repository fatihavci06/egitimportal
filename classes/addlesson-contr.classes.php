<?php

class AddLessonContr extends AddLesson
{

	private $name;
	private $classes;
	private $package_type;

	public function __construct($name, $classes,$package_type)
	{
		$this->name = $name;
		$this->classes = $classes;
		$this->package_type = $package_type;
	}

	public function addLessonDb()
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

		$this->setLesson($slug, $this->name, $this->classes,$this->package_type);
	}
}
