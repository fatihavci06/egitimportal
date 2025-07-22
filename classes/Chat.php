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
            SELECT m.*, 
            u.username as sender_name,
            u.name, u.surname, u.username, u.photo
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
    public function getUserConversations($user_id): array
    {
        $sql = "
        SELECT 
            c.id,
            c.updated_at,

            CASE 
                WHEN c.user1_id = :user_id THEN us2.name 
                ELSE us1.name 
            END AS userSchoolName,
            
            CASE 
                WHEN c.user1_id = :user_id THEN uc2.name 
                ELSE uc1.name 
            END AS userClassName,

            CASE 
                WHEN c.user1_id = :user_id THEN u4.name 
                ELSE u3.name 
            END AS childName,
            
            CASE 
                WHEN c.user1_id = :user_id THEN u4.surname 
                ELSE u3.surname 
            END AS childSurname,
            CASE 
                WHEN c.user1_id = :user_id THEN u4.username 
                ELSE u3.username 
            END AS childUsername,
            CASE 
                WHEN c.user1_id = :user_id THEN u4.id 
                ELSE u3.id 
            END AS childId,

            CASE 
                WHEN c.user1_id = :user_id THEN s2.name 
                ELSE s1.name 
            END AS childSchoolName,
            
            CASE 
                WHEN c.user1_id = :user_id THEN c2.name 
                ELSE c1.name 
            END AS childClassName,


            CASE 
                WHEN c.user1_id = :user_id THEN u2.username 
                ELSE u1.username 
            END AS other_username,
            CASE 
                WHEN c.user1_id = :user_id THEN c.user2_id 
                ELSE c.user1_id 
            END AS other_user_id,
            CASE 
                WHEN c.user1_id = :user_id THEN u2.name 
                ELSE u1.name 
            END AS other_name,
            CASE 
                WHEN c.user1_id = :user_id THEN u2.surname 
                ELSE u1.surname 
            END AS other_surname,
            CASE 
                WHEN c.user1_id = :user_id THEN u2.photo 
                ELSE u1.photo 
            END AS other_photo,
            (
                SELECT message 
                FROM messages 
                WHERE conversation_id = c.id 
                ORDER BY created_at DESC 
                LIMIT 1
            ) AS last_message,
            (
                SELECT COUNT(*) 
                FROM messages 
                WHERE conversation_id = c.id 
                AND sender_id != :user_id 
                AND is_read = FALSE
            ) AS unread_count
        FROM conversations c
        JOIN users_lnp u1 ON c.user1_id = u1.id
        JOIN users_lnp u2 ON c.user2_id = u2.id
        
        LEFT JOIN schools_lnp us1 ON u1.school_id = us1.id
        LEFT JOIN schools_lnp us2 ON u2.school_id = us2.id

        LEFT JOIN classes_lnp uc1 ON u1.class_id = uc1.id
        LEFT JOIN classes_lnp uc2 ON u2.class_id = uc2.id

        LEFT JOIN users_lnp u3 ON u1.child_id = u3.id
        LEFT JOIN users_lnp u4 ON u2.child_id = u4.id

        LEFT JOIN schools_lnp s1 ON u3.school_id = s1.id
        LEFT JOIN schools_lnp s2 ON u4.school_id = s2.id

        LEFT JOIN classes_lnp c1 ON u3.class_id = c1.id
        LEFT JOIN classes_lnp c2 ON u4.class_id = c2.id

        WHERE c.user1_id = :user_id OR c.user2_id = :user_id
        ORDER BY c.updated_at DESC
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['user_id' => $user_id]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function getUserConversationsOfChild($user_id): array
    {
        $sql = "
        SELECT 
            c.id,
            c.updated_at,

            CASE 
                WHEN c.user1_id = :user_id THEN us2.name 
                ELSE us1.name 
            END AS userSchoolName,
            
            CASE 
                WHEN c.user1_id = :user_id THEN uc2.name 
                ELSE uc1.name 
            END AS userClassName,

            CASE 
                WHEN c.user1_id = :user_id THEN u4.name 
                ELSE u3.name 
            END AS childName,
            
            CASE 
                WHEN c.user1_id = :user_id THEN u4.surname 
                ELSE u3.surname 
            END AS childSurname,
            CASE 
                WHEN c.user1_id = :user_id THEN u4.username 
                ELSE u3.username 
            END AS childUsername,
            CASE 
                WHEN c.user1_id = :user_id THEN u4.id 
                ELSE u3.id 
            END AS childId,

            CASE 
                WHEN c.user1_id = :user_id THEN s2.name 
                ELSE s1.name 
            END AS childSchoolName,
            
            CASE 
                WHEN c.user1_id = :user_id THEN c2.name 
                ELSE c1.name 
            END AS childClassName,


            CASE 
                WHEN c.user1_id = :user_id THEN u2.username 
                ELSE u1.username 
            END AS other_username,
            CASE 
                WHEN c.user1_id = :user_id THEN c.user2_id 
                ELSE c.user1_id 
            END AS other_user_id,
            CASE 
                WHEN c.user1_id = :user_id THEN u2.name 
                ELSE u1.name 
            END AS other_name,
            CASE 
                WHEN c.user1_id = :user_id THEN u2.surname 
                ELSE u1.surname 
            END AS other_surname,
            CASE 
                WHEN c.user1_id = :user_id THEN u2.photo 
                ELSE u1.photo 
            END AS other_photo,

            CASE 
                WHEN c.user1_id = :user_id THEN u1.username 
                ELSE u2.username 
            END AS child_username,
            CASE 
                WHEN c.user1_id = :user_id THEN c.user1_id 
                ELSE c.user2_id 
            END AS child_user_id,
            CASE 
                WHEN c.user1_id = :user_id THEN u1.name 
                ELSE u2.name 
            END AS child_name,
            CASE 
                WHEN c.user1_id = :user_id THEN u1.surname 
                ELSE u2.surname 
            END AS child_surname,
            CASE 
            WHEN c.user1_id = :user_id THEN u1.photo 
                ELSE u2.photo 
            END AS child_photo,


            (
                SELECT message 
                FROM messages 
                WHERE conversation_id = c.id 
                ORDER BY created_at DESC 
                LIMIT 1
            ) AS last_message,
            (
                SELECT COUNT(*) 
                FROM messages 
                WHERE conversation_id = c.id 
                AND sender_id != :user_id 
                AND is_read = FALSE
            ) AS unread_count
        FROM conversations c
        JOIN users_lnp u1 ON c.user1_id = u1.id
        JOIN users_lnp u2 ON c.user2_id = u2.id
        
        LEFT JOIN schools_lnp us1 ON u1.school_id = us1.id
        LEFT JOIN schools_lnp us2 ON u2.school_id = us2.id

        LEFT JOIN classes_lnp uc1 ON u1.class_id = uc1.id
        LEFT JOIN classes_lnp uc2 ON u2.class_id = uc2.id

        LEFT JOIN users_lnp u3 ON u1.child_id = u3.id
        LEFT JOIN users_lnp u4 ON u2.child_id = u4.id

        LEFT JOIN schools_lnp s1 ON u3.school_id = s1.id
        LEFT JOIN schools_lnp s2 ON u4.school_id = s2.id

        LEFT JOIN classes_lnp c1 ON u3.class_id = c1.id
        LEFT JOIN classes_lnp c2 ON u4.class_id = c2.id

        
        WHERE c.user1_id = :user_id OR c.user2_id = :user_id
        ORDER BY c.updated_at DESC
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['user_id' => $user_id]);

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
