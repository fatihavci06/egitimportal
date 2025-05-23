<?php
session_start();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
    exit;
}

// Check if the user has the correct role (e.g., role 1 or 3)
$allowed_roles = [1, 3];
if (!isset($_SESSION['role']) || !in_array((int)$_SESSION['role'], $allowed_roles)) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit;
}

require_once "../classes/dbh.classes.php";
require_once "../classes/announcement.classes.php";

$announce_manager = new AnnouncementManager();

try {
    if (isset($_POST['id'])) {
        $id = (int) $_POST['id'];

        $current_announce = $announce_manager->getAnnouncement($id);

        if (!$current_announce) {
            echo json_encode(['status' => 'error', 'message' => 'Announcement not found']);
            exit;
        }

        $new_status = !$current_announce['is_active'] ? 1 : 0;

        $announce_manager->updateAnnouncementStatus($current_announce['id'], $new_status);

        echo json_encode(['status' => 'success', 'message' => 'Announcement status updated']);
        exit;

    } elseif (!empty($_POST['ids']) && is_array($_POST['ids'])) {
        $ids = array_filter($_POST['ids'], 'is_numeric');

        if (empty($ids)) {
            echo json_encode(['status' => 'error', 'message' => 'Invalid IDs']);
            exit;
        }

        $announce_manager->updateAnnouncementStatusArray($ids, 0);

        echo json_encode(['status' => 'success', 'message' => 'Bulk status update successful']);
        exit;

    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid request parameters']);
        exit;
    }

} catch (Exception $e) {
    // Log the actual error if needed
    error_log("Announcement status update error: " . $e->getMessage());
    echo json_encode(['status' => 'error', 'message' => 'Server error']);
    exit;
}
