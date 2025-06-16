<?php
class Chat
{
    private $pdo;

    public function __construct()
    {
        require_once 'dbh.classes.php';

        $this->pdo = (new dbh())->connect();
    }

    /**
     * Get or create a conversation between two users
     */
    public function getOrCreateConversation($user1_id, $user2_id)
    {
        $smaller_id = min($user1_id, $user2_id);
        $larger_id = max($user1_id, $user2_id);

        $stmt = $this->pdo->prepare("
            SELECT id FROM conversations 
            WHERE user1_id = ? AND user2_id = ?
        ");

        $stmt->execute([$smaller_id, $larger_id]);
        $conversation = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($conversation) {
            return $conversation['id'];
        }

        $stmt = $this->pdo->prepare("
            INSERT INTO conversations (user1_id, user2_id) 
            VALUES (?, ?)
        ");
        $stmt->execute([$smaller_id, $larger_id]);

        return $this->pdo->lastInsertId();
    }

    /**
     * Send a message
     */
    public function sendMessage($conversation_id, $sender_id, $message)
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO messages (conversation_id, sender_id, message) 
            VALUES (?, ?, ?)
        ");

        $result = $stmt->execute([$conversation_id, $sender_id, $message]);

        if ($result) {
            $update_stmt = $this->pdo->prepare("
                UPDATE conversations SET updated_at = CURRENT_TIMESTAMP 
                WHERE id = ?
            ");
            $update_stmt->execute([$conversation_id]);
        }

        return $result;
    }

    /**
     * Get messages for a conversation
     */
    public function getMessages($conversation_id)
    {
        $stmt = $this->pdo->prepare("
            SELECT m.*, u.username as sender_name
            FROM messages m
            JOIN users_lnp u ON m.sender_id = u.id
            WHERE m.conversation_id = ?
            ORDER BY m.created_at DESC
        ");
        $stmt->execute([$conversation_id]);

        return array_reverse($stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    /**
     * Get user's conversations list
     */
    public function getUserConversations($user_id)
    {
        $stmt = $this->pdo->prepare("
            SELECT 
                c.id,
                c.updated_at,
                CASE 
                    WHEN c.user1_id = ? THEN u2.username 
                    ELSE u1.username 
                END as other_user_name,
                CASE 
                    WHEN c.user1_id = ? THEN c.user2_id 
                    ELSE c.user1_id 
                END as other_user_id,
                u1.name,
                u2.name,
                u1.surname,
                u2.surname,
                u1.photo,
                u2.photo,
                (SELECT message FROM messages WHERE conversation_id = c.id ORDER BY created_at DESC LIMIT 1) as last_message,
                (SELECT COUNT(*) FROM messages WHERE conversation_id = c.id AND sender_id != ? AND is_read = FALSE) as unread_count
            FROM conversations c
            JOIN users_lnp u1 ON c.user1_id = u1.id
            JOIN users_lnp u2 ON c.user2_id = u2.id
            WHERE c.user1_id = ? OR c.user2_id = ?
            ORDER BY c.updated_at DESC
        ");
        $stmt->execute([$user_id, $user_id, $user_id, $user_id, $user_id]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Mark messages as read
     */
    public function markMessagesAsRead($conversation_id, $user_id)
    {
        $stmt = $this->pdo->prepare("
            UPDATE messages 
            SET is_read = TRUE 
            WHERE conversation_id = ? AND sender_id != ? AND is_read = FALSE
        ");

        return $stmt->execute([$conversation_id, $user_id]);
    }

    /**
     * Get all users for starting new conversations
     */
    public function getAllUsers($current_user_id)
    {
        $stmt = $this->pdo->prepare("
            SELECT id, username 
            FROM users_lnp 
            WHERE id != ? 
            ORDER BY username
        ");
        $stmt->execute([$current_user_id]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Check if user has access to conversation
     */
    public function hasAccessToConversation($conversation_id, $user_id)
    {
        $stmt = $this->pdo->prepare("
            SELECT id FROM conversations 
            WHERE id = ? AND (user1_id = ? OR user2_id = ?)
        ");
        $stmt->execute([$conversation_id, $user_id, $user_id]);

        return $stmt->fetch() !== false;
    }
}




// class Message
// {
//     private $pdo;
//     public function __construct()
//     {
//         require_once 'dbh.classes.php';

//         $this->pdo = (new dbh())->connect();
//     }
//     public function send($sender_id, $receiver_id, $subject, $body)
//     {
//         $stmt = $this->pdo->prepare("
//             INSERT INTO messages (sender_id, receiver_id, subject, body)
//             VALUES (:sender_id, :receiver_id, :subject, :body)
//         ");
//         return $stmt->execute([
//             ':sender_id' => $sender_id,
//             ':receiver_id' => $receiver_id,
//             ':subject' => $subject,
//             ':body' => $body
//         ]);
//     }

//     public function getInbox($user_id)
//     {
//         $stmt = $this->pdo->prepare("
//             SELECT 
//                 m.*,
//                 sender.name AS sender_name,
//                 sender.surname AS sender_surname,
//                 receiver.name AS receiver_name,
//                 receiver.surname AS receiver_surname
//             FROM 
//                 messages m
//             JOIN 
//                 users_lnp sender ON m.sender_id = sender.id
//             JOIN 
//                 users_lnp receiver ON m.receiver_id = receiver.id
//             WHERE 
//                 m.receiver_id = :user_id OR m.sender_id = :user_id
//             ORDER BY 
//                 m.created_at DESC
//         ");
//         $stmt->execute([':user_id' => $user_id]);
//         return $stmt->fetchAll(PDO::FETCH_ASSOC);
//     }

//     public function getSent($user_id)
//     {
//         $stmt = $this->pdo->prepare("
//             SELECT * FROM messages 
//             WHERE sender_id = :user_id
//             ORDER BY created_at DESC
//         ");
//         $stmt->execute([':user_id' => $user_id]);
//         return $stmt->fetchAll(PDO::FETCH_ASSOC);
//     }

//     public function getMessageById($message_id, $user_id)
//     {
//         $stmt = $this->pdo->prepare("
//             SELECT 
//                 m.*, 
//                 sender.name AS sender_name,
//                 sender.surname AS sender_surname,
//                 sender.username AS sender_username,
//                 s_roles.name AS sender_role,
//                 receiver.name AS receiver_name,
//                 receiver.surname AS receiver_surname,
//                 receiver.username AS receiver_username,
//                 r_roles.name AS receiver_role
//             FROM 
//                 messages m 
//             JOIN
//                 users_lnp sender ON m.sender_id = sender.id 
//             JOIN
//                 users_lnp receiver ON m.receiver_id = receiver.id
//             JOIN
//                 userroles_lnp r_roles ON receiver.role = r_roles.id
//             JOIN
//                 userroles_lnp s_roles ON sender.role = s_roles.id
//             WHERE 
//                 m.id = :message_id 
//                 AND (m.sender_id = :user_id OR m.receiver_id = :user_id)
//         ");
//         $stmt->execute([
//             ':message_id' => $message_id,
//             ':user_id' => $user_id
//         ]);
//         return $stmt->fetch(PDO::FETCH_ASSOC);
//     }

//     public function markAsRead($message_id, $user_id)
//     {
//         $stmt = $this->pdo->prepare("
//             UPDATE messages SET is_read = 1 
//             WHERE id = :message_id AND receiver_id = :user_id
//         ");
//         return $stmt->execute([
//             ':message_id' => $message_id,
//             ':user_id' => $user_id
//         ]);
//     }

// }
