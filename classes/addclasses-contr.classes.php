<?php

class AddClassesContr extends AddClasses
{

	private $name;
	private $endDate;
	private $startDate;
	private $table;

	public function __construct($name,$table='classes_lnp',$startDate=null,$endDate=null)
	{
		$this->name = $name;
		$this->startDate=$startDate;
		$this->endDate=$endDate;
		$this->table = $table;
	}

	public function addClassDb()
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
		
		$this->setClass($slug, $this->name,$this->table,$this->startDate,$this->endDate);
	}
}
