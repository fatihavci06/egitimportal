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

	public function __construct($photoSize, $photoName, $fileTmpName, $name, $classes, $lessons, $units, $short_desc)
	{
		$this->photoSize = $photoSize;
		$this->photoName = $photoName;
		$this->fileTmpName = $fileTmpName;
		$this->name = $name;
		$this->classes = $classes;
		$this->lessons = $lessons;
		$this->units = $units;
		$this->short_desc = $short_desc;
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

		$this->setTopic($imgName, $slug, $this->name, $this->classes, $this->lessons, $this->units, $this->short_desc);
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
	private $content;
	private $video_url;
	private $units;
	private $topics;
	private $chooseType;
	private $cozumlusoru;
	private $cozumlu_cevap_a;
	private $cozumlu_cevap_b;
	private $cozumlu_cevap_c;
	private $cozumlu_cevap_d;
	private $cozumlu_testcevap;
	private $solution;
	private $testsoru;
	private $cevap_a;
	private $cevap_b;
	private $cevap_c;
	private $cevap_d;
	private $testcevap;
	private $test;
	private $question;

	public function __construct($photoSize, $photoName, $fileTmpName, $name, $classes, $lessons, $units, $topics, $short_desc, $content, $video_url, $chooseType, $cozumlusoru, $cozumlu_cevap_a, $cozumlu_cevap_b, $cozumlu_cevap_c, $cozumlu_cevap_d, $cozumlu_testcevap, $solution, $testsoru, $cevap_a, $cevap_b, $cevap_c, $cevap_d, $testcevap, $test, $question)
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
		$this->video_url = $video_url;
		$this->chooseType = $chooseType;
		$this->cozumlusoru = $cozumlusoru;
		$this->cozumlu_cevap_a = $cozumlu_cevap_a;
		$this->cozumlu_cevap_b = $cozumlu_cevap_b;
		$this->cozumlu_cevap_c = $cozumlu_cevap_c;
		$this->cozumlu_cevap_d = $cozumlu_cevap_d;
		$this->cozumlu_testcevap = $cozumlu_testcevap;
		$this->solution = $solution;
		$this->testsoru = $testsoru;
		$this->cevap_a = $cevap_a;
		$this->cevap_b = $cevap_b;
		$this->cevap_c = $cevap_c;
		$this->cevap_d = $cevap_d;
		$this->testcevap = $testcevap;
		$this->test = $test;
		$this->question = $question;
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

		$this->setSubTopic($imgName, $slug, $this->name, $this->classes, $this->lessons, $this->units, $this->topics, $this->short_desc, $this->content, $this->video_url, $this->test, $this->question);

		if ($this->chooseType == "test") {
			$testSorular = implode(":/;", $this->testsoru);

			$joint_answers = array_map(function ($a, $b, $c, $d) {
				return "$a*-*$b*-*$c*-*$d";
			}, $this->cevap_a, $this->cevap_b, $this->cevap_c, $this->cevap_d);

			$joint_answers = implode(":/;", $joint_answers);

			$testcevap = implode(":/;", $this->testcevap);

			$last_day = "";

			$this->setTest($testSorular, $joint_answers, $testcevap, $slug, $this->name, $last_day);
		}

		if ($this->chooseType == "question") {
			$cozumlusoru = implode(":/;", $this->cozumlusoru);

			$joint_answers = array_map(function ($a, $b, $c, $d) {
				return "$a*-*$b*-*$c*-*$d";
			}, $this->cozumlu_cevap_a, $this->cozumlu_cevap_b, $this->cozumlu_cevap_c, $this->cozumlu_cevap_d);

			$joint_answers = implode(":/;", $joint_answers);

			$cozumlu_testcevap = implode(":/;", $this->cozumlu_testcevap);

			$solution = implode(":/;", $this->solution);

			$this->setS_questions($cozumlusoru, $joint_answers, $cozumlu_testcevap, $solution, $slug, $this->name);
		}
	}
}

class AddTestContr extends AddSubTopic
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
