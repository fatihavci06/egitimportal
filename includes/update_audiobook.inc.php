<?php

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    // Instantiate AddAudioBookContr class
    include_once "../classes/dbh.classes.php";
    include_once "../classes/addaudio-book.classes.php";
    include_once "../classes/addaudio-book-contr.classes.php";
    include_once "../classes/slug.classes.php";
    include_once "../classes/addimage.classes.php";

    include_once "../classes/audio-book.classes.php";

    $old_slug = $_POST["old_slug"];

    $audioBookDB = new AudioBooks();

    $oldAudioBook = $audioBookDB->getOneAudioBook($old_slug);

    // Grabbing the data
    $name = $_POST["name"] ?? $oldAudioBook['book_name'];
    $iframe = $_POST["iframe"] ?? $oldAudioBook['book_url'];
    $photoSize = $_FILES['photo']['size'];
    $photoName = $_FILES['photo']['name'];
    $fileTmpName = $_FILES['photo']['tmp_name'];
    $classes = isset($_POST['classes']) ? (int) $_POST['classes'] : $oldAudioBook['class_id'];
    $lessons = isset($_POST['lessons']) ? (int) $_POST['lessons'] : $oldAudioBook['lesson_id'];
    $units = isset($_POST['units']) ? (int) $_POST['units'] : $oldAudioBook['unit_id'];
    $topics = isset($_POST['topics']) ? (int) $_POST['topics'] : $oldAudioBook['topic_id'];
    $subtopics = isset($_POST['subtopics']) ? (int) $_POST['subtopics'] : $oldAudioBook['subtopic_id'];



    $addAudioBook = new AddAudioBookContr($name, $iframe, $photoSize, $photoName, $fileTmpName, $classes, $lessons, $units, $topics, $subtopics);


    // echo json_encode([$name, $iframe, $classes, $lessons, $units, $topics, $subtopics]);



    $addAudioBook->updateAudioBookDb($oldAudioBook['id']);






    // Going to back to products page
    //header("location: ../kategoriler");
}
