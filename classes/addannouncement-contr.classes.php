<?php

class AddAnnouncementContr extends AddAnnouncement
{
	private $data=[];
	private $targets=[];
	private $slug; 
	public function __construct($announcementData, $targets)
	{
		$this->data = $announcementData;

		$this->targets = $targets;
	}

	public function addAnnouncementDb()
	{
		$slugName = new Slug($this->data['title']);
		$this->data['slug'] = $slugName->slugify($this->data['title']);

		$slugRes = $this->checkSlug($this->data['slug'] );
		
		$this->data['slug'] = $slugName->makeUniqueSlug($this->data['slug'], $slugRes);

		$this->setAnnouncement($this->data, $this->targets) ;
	}
}
