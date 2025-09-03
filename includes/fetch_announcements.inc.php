<?php
session_start(); 

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit(0);
}

include_once "../classes/dateformat.classes.php";
include_once "../classes/dbh.classes.php";
include_once '../classes/announcement.classes.php';

try {
    if (!isset($_SESSION['id']) || !isset($_SESSION['role']) || !isset($_SESSION['class_id'])) {
        throw new Exception('User not authenticated');
    }

    $user_id = $_SESSION['id'];
    $role_id = $_SESSION['role'];
    $class_id = $_SESSION['class_id'];

    $lastAnnouncementId = isset($_GET['last_id']) ? (int) $_GET['last_id'] : 0;
    $lastTimestamp = isset($_GET['last_timestamp']) ? $_GET['last_timestamp'] : null;

    $announcementManager = new AnnouncementManager();

    $allAnnouncements = $announcementManager->getAnnouncementsWithViewStatus($user_id, $role_id, $class_id);

    $newAnnouncements = [];

    if (!empty($allAnnouncements)) {
        foreach ($allAnnouncements as $announcement) {
            $isNewer = false;

            if ($lastTimestamp) {
                $isNewer = strtotime($announcement['start_date']) > strtotime($lastTimestamp);
            } else {
                $isNewer = count($newAnnouncements) < 10;
            }

            if ($isNewer) {
                $newAnnouncements[] = $announcement;
            }
        }
    }

    $timeDifference = new DateFormat();
    $now = new DateTime();

    $response = [
        'success' => true,
        'announcements' => [],
        'has_new' => !empty($newAnnouncements),
        'last_id' => $lastAnnouncementId,
        'total_count' => count($allAnnouncements),
        'new_count' => count($newAnnouncements)
    ];

    if (!empty($newAnnouncements)) {
        foreach ($newAnnouncements as $announcement) {
            $timeDiff = '';
            ob_start();
            $timeDifference->timeDifference($now, $announcement['start_date']);
            $timeDiff = ob_get_clean();

            $response['announcements'][] = [
                'id' => $announcement['id'],
                'title' => $announcement['title'],
                'slug' => $announcement['slug'],
                'is_viewed' => (bool) $announcement['is_viewed'],
                'start_date' => $announcement['start_date'],
                'time_diff' => $timeDiff,
                'viewed_at' => $announcement['viewed_at'] ?? null
            ];

            if ($announcement['id'] > $response['last_id']) {
                $response['last_id'] = $announcement['id'];
            }
        }
    }

    echo json_encode($response);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Internal server error',
        'message' => $e->getMessage()
    ]);
}
?>