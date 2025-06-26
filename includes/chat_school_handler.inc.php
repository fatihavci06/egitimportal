<?php
session_start();

require_once '../classes/ChatSchool.php';


if (!isset($_SESSION['id'])) {
    header("HTTP/1.0 404 Not Found");
    header("Location: 404.php");
    exit();
}

$chat = new ChatSchool();
$user_id = $_SESSION['id'];

header('Content-Type: application/json');

$action = $_POST['action'] ?? $_GET['action'] ?? '';

switch ($action) {
    case 'get_conversations':
        $conversations = $chat->getUserConversations($user_id);
        echo json_encode(['success' => true, 'conversations' => $conversations]);
        break;

    case 'get_all_conversations':
        if (($_SESSION['role'] == 1)) {
            $conversations = $chat->getConversationsForAdmin();
            echo json_encode(['success' => true, 'conversations' => $conversations]);
        }elseif($_SESSION['role'] == 3){
            $conversations = $chat->getConversationsForAdminBySchool($_SESSION['school_id']);
            echo json_encode(['success' => true, 'conversations' => $conversations]);
        }
        break;

    case 'get_messages':
        $conversation_id = intval($_GET['conversation_id'] ?? 0);

        if (!$conversation_id || !$chat->hasAccessToConversation($conversation_id, $user_id)) {
            echo json_encode(['error' => 'Invalid conversation']);
            break;
        }

        $messages = $chat->getMessages($conversation_id);
        $chat->markMessagesAsRead($conversation_id, $user_id);

        echo json_encode(['success' => true, 'messages' => $messages]);
        break;


    case 'get_all_messages':
        if (($_SESSION['role'] == 1) || ($_SESSION['role'] == 3)) {

            $conversation_id = intval($_GET['conversation_id'] ?? 0);
            // || !$chat->hasAccessToConversation($conversation_id, $user_id)
            if (!$conversation_id) {
                echo json_encode(['error' => 'Invalid conversation']);
                break;
            }

            $messages = $chat->getMessages($conversation_id);
            // $chat->markMessagesAsRead($conversation_id, $user_id);

            echo json_encode(['success' => true, 'messages' => $messages]);
        }
        break;

    case 'send_message':
        $conversation_id = intval($_POST['conversation_id'] ?? 0);
        $message = trim($_POST['message'] ?? '');

        if (!$conversation_id || empty($message)) {
            echo json_encode(['error' => 'Missing conversation ID or message']);
            break;
        }

        if (!$chat->hasAccessToConversation($conversation_id, $user_id)) {
            echo json_encode(['error' => 'Access denied']);
            break;
        }

        $result = $chat->sendMessage($conversation_id, $user_id, $message);

        if ($result) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['error' => 'Failed to send message']);
        }
        break;

    case 'send_file':
        $conversation_id = intval($_POST['conversation_id'] ?? 0);
        $message = trim($_POST['message'] ?? '');

        if (!$conversation_id) {
            echo json_encode(['error' => 'Missing conversation ID']);
            break;
        }

        if (!$chat->hasAccessToConversation($conversation_id, $user_id)) {
            echo json_encode(['error' => 'Access denied']);
            break;
        }

        if (!isset($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
            echo json_encode(['error' => 'No file uploaded or upload error']);
            break;
        }

        $file = $_FILES['file'];

        $maxFileSize = 10 * 1024 * 1024; // 10MB
        if ($file['size'] > $maxFileSize) {
            echo json_encode(['error' => 'File size exceeds 10MB limit']);
            break;
        }

        $allowedTypes = [
            'image/jpeg',
            'image/jpg',
            'image/png',
            'image/gif',
            'image/webp',
            'application/pdf',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'text/plain',
            'application/zip',
            'application/x-rar-compressed'
        ];

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        if (!in_array($mimeType, $allowedTypes)) {
            echo json_encode(['error' => 'File type not allowed']);
            break;
        }

        $uploadDir = __DIR__ . '/../uploads/school_chat_files/';
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $fileExtension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $fileName = $file['name'];
        $uniqueFileName = time() . '_' . uniqid() . '.' . $fileExtension;
        $filePath = $uploadDir . $uniqueFileName;

        if (move_uploaded_file($file['tmp_name'], $filePath)) {
            $result = $chat->sendFileMessage($conversation_id, $user_id, $message, $fileName, $uniqueFileName, $file['size'], $mimeType);

            if ($result) {
                echo json_encode(['success' => true]);
            } else {
                unlink($filePath);
                echo json_encode(['error' => 'Failed to save file message']);
            }
        } else {
            echo json_encode(['error' => 'Failed to upload file']);
        }
        break;
    case 'start_conversation':
        $other_user_id = intval($_POST['other_user_id'] ?? 0);


        if (!$other_user_id || $other_user_id == $user_id) {
            echo json_encode(['error' => 'Invalid user ID']);
            break;
        }

        $conversation_id = $chat->getOrCreateConversation($user_id, $other_user_id);

        echo json_encode(['success' => true, 'conversation_id' => $conversation_id]);
        break;

    case 'get_users':
        if ($_SESSION['role'] == 2) {
            $users = $chat->getAllTeachers($_SESSION['school_id'], $_SESSION['class_id']);
            echo json_encode(['success' => true, 'users' => $users]);
            break;
        } elseif ($_SESSION['role'] == 4) {
            // $users = $chat->getAllStudents($_SESSION['shcool_id'],$_SESSION['class_id']);
            // echo json_encode(['success' => true, 'users' => $users]);
            // break;
            echo json_encode(['error' => 'Invalid action']);
            break;

        }

    default:
        echo json_encode(['error' => 'Invalid action']);
        break;
}