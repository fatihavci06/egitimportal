<?php

if ($_SERVER['REQUEST_METHOD'] == "POST") {

    // Grabbing the data
    $name = trim($_POST["name"]) ?? '';
    $classes = trim($_POST["classes"]) ?? '';
    $lessons = trim($_POST["lessons"]) ?? '';
    $units = trim($_POST["units"]) ?? '';
    $topics = trim($_POST["topics"]) ?? '';
    $sub_topics = trim($_POST["sub_topics"]) ?? null;
    $short_desc = trim($_POST["short_desc"]) ?? '';
    $text_content = $_POST["content"] ?? '';
    $video_url = trim($_POST["video_url"]) ?? '';
    $files = $_FILES['file_path'] ?? '';
    $imageFiles = $_FILES['images'] ?? '';
    $descriptions = $_POST['descriptions'] ?? null;
    $startDate = $_POST['start_date'] ?? null;
    $endDate = $_POST['end_date'] ?? null;

    $titles = $_POST['wordWallTitles'] ?? null; // ['Başlık1', 'Başlık2', ...]
    $urls = $_POST['wordWallUrls'] ?? null;     // ['url1', 'url2', ...]

    $photoSize = $_FILES['photo']['size'];
    $photoName = $_FILES['photo']['name'];
    $fileTmpName = $_FILES['photo']['tmp_name'];

    $school_id = $_SESSION['school_id'] ?? null;
    $teacher_id = $_SESSION['teacher_id'] ?? null;

    // Instantiate AddContentContr class
    include_once "../classes/dbh.classes.php";
    include_once "../classes/addhomework.classes.php";
    include_once "../classes/addhomework-contr.classes.php";
    include_once "../classes/slug.classes.php";
    include_once "../classes/addimage.classes.php";


    $addHomework = new AddHomeworkContr($name, $classes, $lessons, $units, $topics, $sub_topics, $short_desc, $text_content, $video_url, $files, $imageFiles, $photoSize, $photoName, $fileTmpName, $descriptions, $titles, $urls, $startDate, $endDate, $school_id, $teacher_id);

    // Running error handlers and addContent
    $addHomework->addHomeworkDb();




    // Going to back to products page
    //header("location: ../kategoriler");
}
