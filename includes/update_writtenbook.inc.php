<?php

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    // Instantiate AddwrittenBookContr class
    include_once "../classes/dbh.classes.php";
    include_once "../classes/addwritten-book.classes.php";
    include_once "../classes/addwritten-book-contr.classes.php";
    include_once "../classes/slug.classes.php";
    include_once "../classes/addimage.classes.php";

    include_once "../classes/written-book.classes.php";

    $old_slug = $_POST["old_slug"];

    $writtenBookDB = new writtenBook();

    $oldwrittenBook = $writtenBookDB->getOnewrittenBook($old_slug);

    // Grabbing the data
    $name = $_POST["name"] ?? $oldwrittenBook['book_name'];
    $iframe = $_POST["iframe"] ?? $oldwrittenBook['book_url'];
    $description = $_POST["description"] ?? $oldwrittenBook['description'];
    $photoSize = $_FILES['photo']['size'];
    $photoName = $_FILES['photo']['name'];
    $fileTmpName = $_FILES['photo']['tmp_name'];
    $classes = isset($_POST['classes']) ? (int) $_POST['classes'] : $oldwrittenBook['class_id'];
    $lessons = isset($_POST['lessons']) ? (int) $_POST['lessons'] : $oldwrittenBook['lesson_id'];
    $units = isset($_POST['units']) ? (int) $_POST['units'] : $oldwrittenBook['unit_id'];
    $topics = isset($_POST['topics']) ? (int) $_POST['topics'] : $oldwrittenBook['topic_id'];
    $subtopics = isset($_POST['subtopics']) ? (int) $_POST['subtopics'] : $oldwrittenBook['subtopic_id'];



    $addwrittenBook = new AddwrittenBookContr($name, $iframe, $photoSize, $photoName, $fileTmpName, $classes, $lessons, $units, $topics, $subtopics, $description);


    // echo json_encode([$name, $iframe, $classes, $lessons, $units, $topics, $subtopics]);



    $addwrittenBook->updatewrittenBookDb($oldwrittenBook);






    // Going to back to products page
    //header("location: ../kategoriler");
}
