<?php
include_once "dateformat.classes.php";

class AddHomeworkContr extends AddHomework
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
    private $text_content;
    private $video_url;
    private $sub_topics;
    private $imageFiles;
    private $files;
    private $descriptions;
    private $titles;
    private $urls;
    private $start_date;
    private $end_date;

    public function __construct($name, $classes, $lessons, $units, $topics, $sub_topics, $short_desc, $text_content, $video_url, $files, $imageFiles, $photoSize, $photoName, $fileTmpName, $descriptions, $titles, $urls, $start_date, $end_date)
    {
        $this->photoSize = $photoSize;
        $this->photoName = $photoName;
        $this->fileTmpName = $fileTmpName;
        $this->name = $name;
        $this->classes = $classes;
        $this->lessons = $lessons;
        $this->units = $units;
        $this->short_desc = $short_desc;
        $this->topics = $topics;
        $this->text_content = $text_content;
        $this->video_url = $video_url;
        $this->sub_topics = $sub_topics;
        $this->imageFiles = $imageFiles;
        $this->files = $files;
        $this->descriptions = $descriptions;
        $this->titles = $titles;
        $this->urls = $urls;
        $this->start_date = $start_date;
        $this->end_date = $end_date;
    }

    public function addHomeworkDb()
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
            $img = $imageSent->homeworkImage($this->photoName, $this->photoSize, $this->fileTmpName, $slug);
            $imgName = $img['image'];
        } else {
            $imgName = 'konuDefault.jpg';
        }

        $file_urls = [];

        if (isset($this->files)) {
            $uploadDir = __DIR__ . '/../uploads/homeworks/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            foreach ($this->files['tmp_name'] as $index => $tmpName) {

                if ($this->files['error'][$index] === UPLOAD_ERR_OK) {
                    $fileName = basename($this->files['name'][$index]);
                    $targetFile = $uploadDir . $fileName;

                    if (move_uploaded_file($tmpName, $targetFile)) {
                        $file_urls[] = 'uploads/homeworks/' . $fileName;
                    } else {
                        echo json_encode(['status' => 'error', 'message' => 'Dosya yüklenemedi: ' . $fileName]);
                        exit;
                    }
                }
            }
        }


        $this->setHomeworkContent($imgName, $slug, $this->name, $this->classes, $this->lessons, $this->units, $this->short_desc, $this->topics, $this->sub_topics, $this->text_content, $this->video_url, $file_urls, $this->imageFiles, $this->descriptions, $this->titles, $this->urls, $this->start_date, $this->end_date);
    }
}
