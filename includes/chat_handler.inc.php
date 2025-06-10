<?php
session_start();

require_once '../classes/Chat.php';


if (!isset($_SESSION['id'])) {
    header("HTTP/1.0 404 Not Found");
    header("Location: 404.php");
    exit();
}

$chat = new Chat();
$user_id = $_SESSION['id'];

header('Content-Type: application/json');

$action = $_POST['action'] ?? $_GET['action'] ?? '';

switch ($action) {
    case 'get_conversations':
        $conversations = $chat->getUserConversations($user_id);
        echo json_encode(['success' => true, 'conversations' => $conversations]);
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
        $users = $chat->getAllUsers($user_id);
        echo json_encode(['success' => true, 'users' => $users]);
        break;

    default:
        echo json_encode(['error' => 'Invalid action']);
        break;
}