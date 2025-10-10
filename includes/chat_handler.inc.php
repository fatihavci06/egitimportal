<?php
session_start();

require_once '../classes/Chat.php';
require_once '../classes/dbh.classes.php';
require_once '../classes/user.classes.php';

if (!isset($_SESSION['id'])) {
    header("HTTP/1.0 404 Not Found");
    header("Location: 404.php");
    exit();
}

$chat = new Chat();
$user_id = $_SESSION['id'];
$user_role_id = $_SESSION['role'];

header('Content-Type: application/json');

$action = $_POST['action'] ?? $_GET['action'] ?? '';

switch ($action) {
    case 'get_conversations':
        $conversations = $chat->getUserConversations($user_id);
        echo json_encode(['success' => true, 'conversations' => $conversations]);
        break;

    case 'get_child_conversations':

        if ($user_role_id == 5) {
            $parent = (new User)->getUserById($user_id);
            $conversations = $chat->getUserConversationsOfChild($parent['child_id']);
        }
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

    case 'get_child_messages':
        $conversation_id = intval($_GET['conversation_id'] ?? 0);
        if ($user_role_id != 5) {
            echo json_encode(['error' => 'Invalid conversation']);
            break;
        }
        $parent = (new User)->getUserById($user_id);

        if (!$conversation_id || !$chat->hasAccessToConversation($conversation_id, $parent['child_id'])) {
            echo json_encode(['error' => 'Invalid conversation']);
            break;
        }

        $messages = $chat->getMessages($conversation_id);
        // $chat->markMessagesAsRead($conversation_id, $user_id);

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
    case 'get_user_info':
        $target_user_id = intval($_GET['user_id'] ?? 0);

        if (!$target_user_id) {
            echo json_encode(['success' => false, 'error' => 'Geçersiz Kullanıcı ID']);
            break;
        }

        $user = (new User)->getUserById($target_user_id);

        if ($user) {
            echo json_encode(['success' => true, 'user' => [
                'id' => $user['id'],
                'name' => $user['name'],
                'surname' => $user['surname'],
                'photo' => $user['photo'] ?? 'default.jpg' // Eğer fotoğraf yoksa 'default.jpg' kullan.
            ]]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Kullanıcı bulunamadı.']);
        }
        break;


    default:
        echo json_encode(['error' => 'Invalid action']);
        break;
}
