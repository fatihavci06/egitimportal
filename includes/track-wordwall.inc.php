<?php

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    session_start();

    $wordwall_id = (int) $_POST['wordwall_id'];


    require_once '../classes/wordwall-tracker.classes.php';


    $tracker = new WordwallTracker();

    $tracker->createWordwallVisit($_SESSION['id'], $wordwall_id);

}

