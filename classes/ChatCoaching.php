<?php
class ChatCoaching
{
    private $pdo;

    public function __construct()
    {
        require_once 'dbh.classes.php';

        $this->pdo = (new dbh())->connect();
    }

    // public function getOrCreateConversation($user1_id, $user2_id)
    // {
    //     $smaller_id = min($user1_id, $user2_id);
    //     $larger_id = max($user1_id, $user2_id);

    //     $stmt = $this->pdo->prepare("
    //         SELECT id FROM conversations 
    //         WHERE user1_id = ? AND user2_id = ?
    //     ");

    //     $stmt->execute([$smaller_id, $larger_id]);
    //     $conversation = $stmt->fetch(PDO::FETCH_ASSOC);

    //     if ($conversation) {
    //         return $conversation['id'];
    //     }

    //     $stmt = $this->pdo->prepare("
    //         INSERT INTO conversations (user1_id, user2_id) 
    //         VALUES (?, ?)
    //     ");
    //     $stmt->execute([$smaller_id, $larger_id]);

    //     return $this->pdo->lastInsertId();
    // }

    public function getUserConversations($user_id): array
    {
        $sql = "
        SELECT 
            c.id,
            c.updated_at,
            CASE 
                WHEN c.user_id = :user_id THEN u2.username 
                ELSE u1.username 
            END AS other_username,
            CASE 
                WHEN c.user_id = :user_id THEN c.teacher_id 
                ELSE c.user_id 
            END AS other_user_id,
            CASE 
                WHEN c.user_id = :user_id THEN u2.name 
                ELSE u1.name 
            END AS other_name,
            CASE 
                WHEN c.user_id = :user_id THEN u2.surname 
                ELSE u1.surname 
            END AS other_surname,
            CASE 
                WHEN c.user_id = :user_id THEN u2.photo 
                ELSE u1.photo 
            END AS other_photo,
            m.message as last_message,
            m.message_type as last_message_type,
            m.file_name as last_file_name,
            (SELECT COUNT(*) FROM coaching_messages m2 
             WHERE m2.conversation_id = c.id 
             AND m2.sender_id != :user_id 
             AND m2.is_read = 0) as unread_count
        FROM coaching_guidance_requests_lnp c
        LEFT JOIN users_lnp u1 ON c.user_id = u1.id
        LEFT JOIN users_lnp u2 ON c.teacher_id = u2.id
        LEFT JOIN coaching_messages m ON c.id = m.conversation_id
        LEFT JOIN coaching_messages m3 ON c.id = m3.conversation_id AND m.created_at < m3.created_at
        WHERE (c.user_id = :user_id OR c.teacher_id = :user_id) AND m3.id IS NULL
        ORDER BY c.updated_at DESC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['user_id' => $user_id]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getMessages($conversation_id)
    {
        $stmt = $this->pdo->prepare("
            SELECT m.*, u.username as sender_name,
            u.name, u.surname, u.username, u.photo
            FROM coaching_messages m
            JOIN users_lnp u ON m.sender_id = u.id
            WHERE m.conversation_id = ?
            ORDER BY m.created_at DESC
        ");
        $stmt->execute([$conversation_id]);

        return array_reverse($stmt->fetchAll(PDO::FETCH_ASSOC));
    }
    public function sendMessage($conversation_id, $sender_id, $message)
    {
        try {
            $this->pdo->beginTransaction();

            $sql = "INSERT INTO coaching_messages (conversation_id, sender_id, message, message_type) VALUES (?, ?, ?, 'text')";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$conversation_id, $sender_id, $message]);

            $this->updateConversationTimestamp($conversation_id);

            $this->pdo->commit();
            return true;
        } catch (Exception $e) {
            $this->pdo->rollBack();
            return false;
        }
    }
    public function sendFileMessage($conversation_id, $sender_id, $message, $file_name, $file_path, $file_size, $file_type)
    {
        try {
            $this->pdo->beginTransaction();

            $sql = "INSERT INTO coaching_messages (conversation_id, sender_id, message, message_type, file_name, file_path, file_size, file_type) 
                    VALUES (?, ?, ?, 'file', ?, ?, ?, ?)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$conversation_id, $sender_id, $message, $file_name, $file_path, $file_size, $file_type]);

            $this->updateConversationTimestamp($conversation_id);

            $this->pdo->commit();
            return true;
        } catch (Exception $e) {
            $this->pdo->rollBack();
            return false;
        }
    }
    public function markMessagesAsRead($conversation_id, $user_id)
    {
        $stmt = $this->pdo->prepare("
            UPDATE coaching_messages 
            SET is_read = TRUE 
            WHERE conversation_id = ? AND sender_id != ? AND is_read = FALSE
        ");

        return $stmt->execute([$conversation_id, $user_id]);
    }
    public function hasAccessToConversation($conversation_id, $user_id)
    {
        $stmt = $this->pdo->prepare("
            SELECT id FROM coaching_guidance_requests_lnp 
            WHERE id = ? AND (user_id = ? OR teacher_id = ?)
        ");
        $stmt->execute([$conversation_id, $user_id, $user_id]);

        return $stmt->fetch() !== false;
    }
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
    public function canUserAccessFile($file_path, $user_id)
    {
        $sql = "SELECT m.id FROM coaching_messages m
                JOIN coaching_guidance_requests_lnp c ON m.conversation_id = c.id
                WHERE m.file_path = ? AND (c.user_id = ? OR c.teacher_id = ?)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$file_path, $user_id, $user_id]);

        return $stmt->fetchColumn() !== false;
    }
    public function getOriginalFileName($file_path)
    {
        $sql = "SELECT file_name FROM coaching_messages WHERE file_path = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$file_path]);

        return $stmt->fetchColumn();
    }
    private function updateConversationTimestamp($conversation_id)
    {
        $sql = "UPDATE coaching_guidance_requests_lnp SET updated_at = CURRENT_TIMESTAMP WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$conversation_id]);
    }

}
