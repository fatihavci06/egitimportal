<?php

class Message
{
    private $pdo;
    public function __construct()
    {
        require_once 'dbh.classes.php';

        $this->pdo = (new dbh())->connect();
    }
    public function send($sender_id, $receiver_id, $subject, $body)
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO messages (sender_id, receiver_id, subject, body)
            VALUES (:sender_id, :receiver_id, :subject, :body)
        ");
        return $stmt->execute([
            ':sender_id' => $sender_id,
            ':receiver_id' => $receiver_id,
            ':subject' => $subject,
            ':body' => $body
        ]);
    }

    public function getInbox($user_id)
    {
        $stmt = $this->pdo->prepare("
            SELECT 
                m.*,
                sender.name AS sender_name,
                sender.surname AS sender_surname,
                receiver.name AS receiver_name,
                receiver.surname AS receiver_surname
            FROM 
                messages m
            JOIN 
                users_lnp sender ON m.sender_id = sender.id
            JOIN 
                users_lnp receiver ON m.receiver_id = receiver.id
            WHERE 
                m.receiver_id = :user_id OR m.sender_id = :user_id
            ORDER BY 
                m.created_at DESC
        ");
        $stmt->execute([':user_id' => $user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getSent($user_id)
    {
        $stmt = $this->pdo->prepare("
            SELECT * FROM messages 
            WHERE sender_id = :user_id
            ORDER BY created_at DESC
        ");
        $stmt->execute([':user_id' => $user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getMessageById($message_id, $user_id)
    {
        $stmt = $this->pdo->prepare("
            SELECT 
                m.*, 
                sender.name AS sender_name,
                sender.surname AS sender_surname,
                sender.username AS sender_username,
                s_roles.name AS sender_role,
                receiver.name AS receiver_name,
                receiver.surname AS receiver_surname,
                receiver.username AS receiver_username,
                r_roles.name AS receiver_role
            FROM 
                messages m 
            JOIN
                users_lnp sender ON m.sender_id = sender.id 
            JOIN
                users_lnp receiver ON m.receiver_id = receiver.id
            JOIN
                userroles_lnp r_roles ON receiver.role = r_roles.id
            JOIN
                userroles_lnp s_roles ON sender.role = s_roles.id
            WHERE 
                m.id = :message_id 
                AND (m.sender_id = :user_id OR m.receiver_id = :user_id)
        ");
        $stmt->execute([
            ':message_id' => $message_id,
            ':user_id' => $user_id
        ]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function markAsRead($message_id, $user_id)
    {
        $stmt = $this->pdo->prepare("
            UPDATE messages SET is_read = 1 
            WHERE id = :message_id AND receiver_id = :user_id
        ");
        return $stmt->execute([
            ':message_id' => $message_id,
            ':user_id' => $user_id
        ]);
    }

}
