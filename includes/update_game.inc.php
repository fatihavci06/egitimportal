<?php

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    // Instantiate AddAudioBookContr class
    include_once "../classes/dbh.classes.php";
    include_once "../classes/addgame.classes.php";
    include_once "../classes/addgame-contr.classes.php";
    include_once "../classes/slug.classes.php";
    include_once "../classes/addimage.classes.php";

    include_once "../classes/games.classes.php";

    $old_slug = $_POST["old_slug"];

    $gameDB = new Games();

    $oldGame = $gameDB->getOneGame($old_slug);

    // Grabbing the data
    $name = $_POST["name"] ?? $oldGame['game_name'];
    $iframe = $_POST["iframe"] ?? $oldGame['game_url'];
    $description = $_POST["description"] ?? $oldGame['description'];
    $photoSize = $_FILES['photo']['size'];
    $photoName = $_FILES['photo']['name'];
    $fileTmpName = $_FILES['photo']['tmp_name'];
    $classes = isset($_POST['classes']) ? (int) $_POST['classes'] : $oldGame['class_id'];
    $lessons = isset($_POST['lessons']) ? (int) $_POST['lessons'] : $oldGame['lesson_id'];
    $units = isset($_POST['units']) ? (int) $_POST['units'] : $oldGame['unit_id'];
    $topics = isset($_POST['topics']) ? (int) $_POST['topics'] : $oldGame['topic_id'];
    $subtopics = isset($_POST['subtopics']) ? (int) $_POST['subtopics'] : $oldGame['subtopic_id'];



    $addAudioBook = new AddGameContr($name, $iframe, $description, $photoSize, $photoName, $fileTmpName, $classes, $lessons, $units, $topics, $subtopics);


    // echo json_encode([$name, $iframe, $classes, $lessons, $units, $topics, $subtopics]);



    $addAudioBook->updateGameDb($oldGame);






    // Going to back to products page
    //header("location: ../kategoriler");
}
