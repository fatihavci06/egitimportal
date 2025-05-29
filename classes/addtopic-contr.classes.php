<?php
include_once "dateformat.classes.php";

class AddTopicContr extends AddTopic
{

	private $photoSize;
	private $photoName;
	private $fileTmpName;
	private $name;
	private $classes;
	private $lessons;
	private $short_desc;
	private $units;
	private $start_date;
	private $end_date;
	private $order;

	public function __construct($photoSize, $photoName, $fileTmpName, $name, $classes, $lessons, $units, $short_desc, $start_date, $end_date, $order)
	{
		$this->photoSize = $photoSize;
		$this->photoName = $photoName;
		$this->fileTmpName = $fileTmpName;
		$this->name = $name;
		$this->classes = $classes;
		$this->lessons = $lessons;
		$this->units = $units;
		$this->short_desc = $short_desc;
		$this->start_date = $start_date;
		$this->end_date = $end_date;
		$this->order = $order;
	}

	public function addTopicDb()
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
			$img = $imageSent->topicImage($this->photoName, $this->photoSize, $this->fileTmpName, $slug);
			$imgName = $img['image'];
		} else {
			$imgName = 'konuDefault.jpg';
		}

		$this->setTopic($imgName, $slug, $this->name, $this->classes, $this->lessons, $this->units, $this->short_desc, $this->start_date, $this->end_date, $this->order);
	}
}

class UpdateTopicContr extends UpdateTopic
{

	private $photoSize;
	private $photoName;
	private $fileTmpName;
	private $name;
	private $short_desc;
	private $start_date;
	private $end_date;
	private $order;
	private $slug;

	public function __construct($photoSize, $photoName, $fileTmpName, $name, $short_desc, $start_date, $end_date, $order, $slug)
	{
		$this->photoSize = $photoSize;
		$this->photoName = $photoName;
		$this->fileTmpName = $fileTmpName;
		$this->name = $name;
		$this->short_desc = $short_desc;
		$this->start_date = $start_date;
		$this->end_date = $end_date;
		$this->order = $order;
		$this->slug = $slug;
	}

	public function updateTopicDb()
	{

		$topics = new Topics();
		$topicInfo = $topics->getOneTopicDetailsAdmin($this->slug);

		if ($this->name != $topicInfo[0]['name']) {
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
		} else {
			$slug = $topicInfo[0]['slug'];
		}


		if ($this->fileTmpName != NULL) {
			$imageSent = new ImageUpload();
			$img = $imageSent->topicImage($this->photoName, $this->photoSize, $this->fileTmpName, $slug);
			$imgName = $img['image'];
		} else {
			$imgName = $topicInfo[0]['image'];
		}

		$topic_id = $topicInfo[0]['id'];

		$this->updateTopic($imgName, $slug, $this->name, $this->short_desc, $this->start_date, $this->end_date, $this->order, $topic_id);
	}
}

class AddSubTopicContr extends AddSubTopic
{

	private $photoSize;
	private $photoName;
	private $fileTmpName;
	private $name;
	private $classes;
	private $lessons;
	private $short_desc;
	private $units;
	private $topics;
	private $start_date;
	private $end_date;
	private $order;

	public function __construct($photoSize, $photoName, $fileTmpName, $name, $classes, $lessons, $units, $topics, $short_desc, $start_date, $end_date, $order)
	{
		$this->photoSize = $photoSize;
		$this->photoName = $photoName;
		$this->fileTmpName = $fileTmpName;
		$this->name = $name;
		$this->classes = $classes;
		$this->lessons = $lessons;
		$this->units = $units;
		$this->topics = $topics;
		$this->short_desc = $short_desc;
		$this->start_date = $start_date;
		$this->end_date = $end_date;
		$this->order = $order;
	}

	public function addSubTopicDb()
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
			$img = $imageSent->topicImage($this->photoName, $this->photoSize, $this->fileTmpName, $slug);
			$imgName = $img['image'];
		} else {
			$imgName = 'konuDefault.jpg';
		}

		$this->setSubTopic($imgName, $slug, $this->name, $this->classes, $this->lessons, $this->units, $this->topics, $this->short_desc, $this->start_date, $this->end_date, $this->order);
	}
}
class UpdateSubTopicContr extends UpdateSubTopic
{

	private $photoSize;
	private $photoName;
	private $fileTmpName;
	private $name;
	private $short_desc;
	private $start_date;
	private $end_date;
	private $order;
	private $slug;

	public function __construct($photoSize, $photoName, $fileTmpName, $name, $short_desc, $start_date, $end_date, $order, $slug)
	{
		$this->photoSize = $photoSize;
		$this->photoName = $photoName;
		$this->fileTmpName = $fileTmpName;
		$this->name = $name;
		$this->short_desc = $short_desc;
		$this->start_date = $start_date;
		$this->end_date = $end_date;
		$this->order = $order;
		$this->slug = $slug;
	}

	public function updateSubTopicDb()
	{

		$subTopics = new SubTopics();
		$subTopicInfo = $subTopics->getOneSubTopicDetailsAdmin($this->slug);

		if ($this->name != $subTopicInfo[0]['name']) {

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
		} else {
			$slug = $subTopicInfo[0]['slug'];
		}

		if ($this->fileTmpName != NULL) {
			$imageSent = new ImageUpload();
			$img = $imageSent->topicImage($this->photoName, $this->photoSize, $this->fileTmpName, $slug);
			$imgName = $img['image'];
		} else {
			$imgName = $subTopicInfo[0]['image'];
		}

		$subTopic_id = $subTopicInfo[0]['id'];

		$this->updateSubTopic($imgName, $slug, $this->name, $this->short_desc, $this->start_date, $this->end_date, $this->order, $subTopic_id);
	}
}

/* class AddTestContr extends AddSubTopic
{

	private $photoSize;
	private $photoName;
	private $fileTmpName;
	private $name;
	private $classes;
	private $lessons;
	private $short_desc;
	private $content;
	private $video_url;
	private $units;
	private $topics;
	private $testsoru;
	private $cevap_a;
	private $cevap_b;
	private $cevap_c;
	private $cevap_d;
	private $testcevap;
	private $test;
	private $question;
	private $last_day;

	public function __construct($photoSize, $photoName, $fileTmpName, $name, $classes, $lessons, $units, $topics, $short_desc, $testsoru, $cevap_a, $cevap_b, $cevap_c, $cevap_d, $testcevap, $test, $content, $question, $video_url, $last_day)
	{
		$this->photoSize = $photoSize;
		$this->photoName = $photoName;
		$this->fileTmpName = $fileTmpName;
		$this->name = $name;
		$this->classes = $classes;
		$this->lessons = $lessons;
		$this->units = $units;
		$this->topics = $topics;
		$this->short_desc = $short_desc;
		$this->content = $content;
		$this->testsoru = $testsoru;
		$this->cevap_a = $cevap_a;
		$this->cevap_b = $cevap_b;
		$this->cevap_c = $cevap_c;
		$this->cevap_d = $cevap_d;
		$this->testcevap = $testcevap;
		$this->test = $test;
		$this->question = $question;
		$this->video_url = $video_url;
		$this->last_day = $last_day;
	}

	public function addTestDb()
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
			$img = $imageSent->topicImage($this->photoName, $this->photoSize, $this->fileTmpName, $slug);
			$imgName = $img['image'];
		} else {
			$imgName = 'konuDefault.jpg';
		}

		$this->setSubTopic($imgName, $slug, $this->name, $this->classes, $this->lessons, $this->units, $this->topics, $this->short_desc, $this->content, $this->video_url, $this->test, $this->question);

		$testSorular = implode(":/;", $this->testsoru);

		$joint_answers = array_map(function ($a, $b, $c, $d) {
			return "$a*-*$b*-*$c*-*$d";
		}, $this->cevap_a, $this->cevap_b, $this->cevap_c, $this->cevap_d);

		$joint_answers = implode(":/;", $joint_answers);

		$testcevap = implode(":/;", $this->testcevap);

		$dateFormat = new DateFormat();
		$dbDate =  $dateFormat->forDB($this->last_day);

		$this->setTest($testSorular, $joint_answers, $testcevap, $slug, $this->name, $dbDate);
	}
}
 */