<?php
session_start(); // Start session to access user data

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit(0);
}

include_once "../classes/dateformat.classes.php";
include_once "../classes/dbh.classes.php";
include_once '../classes/notification.classes.php';

try {
    if (!isset($_SESSION['id']) || !isset($_SESSION['role']) || !isset($_SESSION['class_id'])) {
        throw new Exception('User not authenticated');
    }

    $user_id = $_SESSION['id'];
    $role_id = $_SESSION['role'];
    $class_id = $_SESSION['class_id'];

    $lastNotificationId = isset($_GET['last_id']) ? (int) $_GET['last_id'] : 0;
    $lastTimestamp = isset($_GET['last_timestamp']) ? $_GET['last_timestamp'] : null;

    $notificationManager = new NotificationManager();

    $allNotifications = $notificationManager->getNotificationsWithViewStatus($user_id, $role_id, $class_id);

    $newNotifications = [];

    if (!empty($allNotifications)) {
        foreach ($allNotifications as $notification) {
            $isNewer = false;

            if ($lastTimestamp) {
                $isNewer = strtotime($notification['start_date']) > strtotime($lastTimestamp);
            } else {
                $isNewer = count($newNotifications) < 10;
            }

            if ($isNewer) {
                $newNotifications[] = $notification;
            }
        }
    }

    $timeDifference = new DateFormat();
    $now = new DateTime();

    $response = [
        'success' => true,
        'notifications' => [],
        'has_new' => !empty($newNotifications),
        'last_id' => $lastNotificationId,
        'total_count' => count($allNotifications),
        'new_count' => count($newNotifications)
    ];

    if (!empty($newNotifications)) {
        foreach ($newNotifications as $notification) {
            $timeDiff = '';
            ob_start();
            $timeDifference->timeDifference($now, $notification['start_date']);
            $timeDiff = ob_get_clean();

            $response['notifications'][] = [
                'id' => $notification['id'],
                'title' => $notification['title'],
                'slug' => $notification['slug'],
                'is_viewed' => (bool) $notification['is_viewed'],
                'start_date' => $notification['start_date'],
                'time_diff' => $timeDiff,
                'viewed_at' => $notification['viewed_at'] ?? null
            ];

            if ($notification['id'] > $response['last_id']) {
                $response['last_id'] = $notification['id'];
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