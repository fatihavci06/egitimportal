<?php

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    session_start();

    $file_id = (int) $_POST['file_id'];


    require_once '../classes/download-tracker.classes.php';


    $tracker = new DownloadTracker();

    $tracker->createFileVisit($_SESSION['id'], $file_id);

}

