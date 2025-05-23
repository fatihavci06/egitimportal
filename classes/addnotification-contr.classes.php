<?php

class AddNotificationContr extends AddNotification
{

	private $data = [];
	private $targets = [];

	public function __construct($notitficationtData, $targets)
	{
		$this->data = $notitficationtData;

		$this->targets = $targets;
	}

	public function addNotificationDb()
	{

		$slugName = new Slug($this->data['title']);
		$this->data['slug'] = $slugName->slugify($this->data['title']);

		$slugRes = $this->checkSlug($this->data['slug']);
		

		
		if (count($slugRes) > 0) {
			$ech = end($slugRes);

			$output = substr($ech['slug'], -1, strrpos($ech['slug'], '-'));

			if (!is_numeric($output)) {
				$output = 1;
			} else {
				$output = $output + 1;
			}

			$this->data['slug'] = $this->data['slug'] . "-" . $output;
		} else {
			$this->slug = $this->data['slug'];
		}

		$this->setNotification($this->data, $this->targets);
	}


}