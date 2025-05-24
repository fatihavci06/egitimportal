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
require_once "../classes/games.classes.php";

$game_manager= new Games();

try {
    if (isset($_POST['id'])) {
        $id = (int) $_POST['id'];

        $current_game = $game_manager->getOneGameById($id);

        if (!$current_game) {
            echo json_encode(['status' => 'error', 'message' => 'Game not found']);
            exit;
        }

        $new_status = !$current_game['is_active'] ? 1 : 0;

        $game_manager->updateGameStatus($current_game['id'], $new_status);

        echo json_encode(['status' => 'success', 'message' => 'Game status updated']);
        exit;

    } elseif (!empty($_POST['ids']) && is_array($_POST['ids'])) {
        $ids = array_filter($_POST['ids'], 'is_numeric');

        if (empty($ids)) {
            echo json_encode(['status' => 'error', 'message' => 'Invalid IDs']);
            exit;
        }

        $game_manager->updateGameStatusArray($ids, 0);

        echo json_encode(['status' => 'success', 'message' => 'Bulk status update successful']);
        exit;

    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid request parameters']);
        exit;
    }

} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'Server error']);
    exit;
}
