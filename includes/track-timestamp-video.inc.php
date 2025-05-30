<?php

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    session_start();
    $data = json_decode(file_get_contents('php://input'), true);
    $video_id = (int) $data['video_id'] ?? 0;
    $timestamp = $data['timestamp'] ?? 0;

    require_once '../classes/video-tracker.classes.php';

    $tracker = new VideoTracker();

    $tracker->store($_SESSION['id'], $video_id, $timestamp);

    // if (isset($result)) {
    //     echo json_encode([$userId, $videoId, $timestampSeconds]);
    //     exit();
    // }

}

