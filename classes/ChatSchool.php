<?php
class ChatSchool
{
    private $pdo;

    public function __construct()
    {
        require_once 'dbh.classes.php';

        $this->pdo = (new dbh())->connect();
    }

    public function getOrCreateConversation($student_id, $teacher_id)
    {

        $stmt = $this->pdo->prepare("
            SELECT id FROM school_conversations 
            WHERE student_id = ? AND teacher_id = ?
        ");

        $stmt->execute([$student_id, $teacher_id]);
        $conversation = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($conversation) {
            return $conversation['id'];
        }

        $stmt = $this->pdo->prepare("
            INSERT INTO school_conversations (student_id, teacher_id) 
            VALUES (?, ?)
        ");
        $stmt->execute([$student_id, $teacher_id]);

        return $this->pdo->lastInsertId();
    }

    public function getUserConversations($user_id): array
    {
        $sql = "
        SELECT 
            c.id,
            c.updated_at,
            CASE 
                WHEN c.student_id = :user_id THEN u2.username 
                ELSE u1.username 
            END AS other_username,
            CASE 
                WHEN c.student_id = :user_id THEN c.teacher_id 
                ELSE c.student_id 
            END AS other_user_id,
            CASE 
                WHEN c.student_id = :user_id THEN u2.name 
                ELSE u1.name 
            END AS other_name,
            CASE 
                WHEN c.student_id = :user_id THEN u2.surname 
                ELSE u1.surname 
            END AS other_surname,
            CASE 
                WHEN c.student_id = :user_id THEN u2.photo 
                ELSE u1.photo 
            END AS other_photo,
            lessons_lnp.name AS lessonName,

            m.message as last_message,
            m.message_type as last_message_type,
            m.file_name as last_file_name,
            (SELECT COUNT(*) FROM school_messages m2 
             WHERE m2.conversation_id = c.id 
             AND m2.sender_id != :user_id 
             AND m2.is_read = 0) as unread_count
        FROM school_conversations c
        LEFT JOIN users_lnp u1 ON c.student_id = u1.id
        LEFT JOIN users_lnp u2 ON c.teacher_id = u2.id
        INNER JOIN lessons_lnp ON u2.lesson_id = lessons_lnp.id 
        LEFT JOIN school_messages m ON c.id = m.conversation_id
        LEFT JOIN school_messages m3 ON c.id = m3.conversation_id AND m.created_at < m3.created_at
        WHERE (c.student_id = :user_id OR c.teacher_id = :user_id) AND m3.id IS NULL 
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
            FROM school_messages m
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

            $sql = "INSERT INTO school_messages (conversation_id, sender_id, message, message_type) VALUES (?, ?, ?, 'text')";
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

            $sql = "INSERT INTO school_messages (conversation_id, sender_id, message, message_type, file_name, file_path, file_size, file_type) 
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
            UPDATE school_messages 
            SET is_read = TRUE 
            WHERE conversation_id = ? AND sender_id != ? AND is_read = FALSE
        ");

        return $stmt->execute([$conversation_id, $user_id]);
    }
    public function hasAccessToConversation($conversation_id, $user_id)
    {
        $stmt = $this->pdo->prepare("
            SELECT id FROM school_conversations 
            WHERE id = ? AND (student_id = ? OR teacher_id = ?)
        ");
        $stmt->execute([$conversation_id, $user_id, $user_id]);

        return $stmt->fetch() !== false;
    }
    public function getAllTeachers($school_id, $class_id)
    {
        $stmt = $this->pdo->prepare("
            SELECT 
                users_lnp.id,
                users_lnp.name,
                users_lnp.surname,
                users_lnp.username,
                users_lnp.email,
                users_lnp.telephone,
                users_lnp.photo,
                userroles_lnp.name AS roleName,
                lessons_lnp.name AS lessonName   
            FROM users_lnp
            INNER JOIN userroles_lnp ON users_lnp.role = userroles_lnp.id 
            INNER JOIN classes_lnp ON users_lnp.class_id = classes_lnp.id 
            INNER JOIN lessons_lnp ON users_lnp.lesson_id = lessons_lnp.id 
            WHERE users_lnp.school_id = ? AND users_lnp.class_id = ? AND users_lnp.role = 4
            ORDER BY users_lnp.username
        ");
        $stmt->execute([$school_id, $class_id]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getAllStudents($school_id, $class_id)
    {
        $stmt = $this->pdo->prepare("
            SELECT 
            users_lnp.id,
            users_lnp.name,
            users_lnp.surname,
            users_lnp.username,
            users_lnp.email,
            users_lnp.telephone,
            users_lnp.photo,
            userroles_lnp.name AS roleName  
            FROM users_lnp
            INNER JOIN userroles_lnp ON users_lnp.role = userroles_lnp.id 
            INNER JOIN classes_lnp ON users_lnp.role = userroles_lnp.id 
            WHERE school_id = ? AND class_id = ? AND role = 2
            ORDER BY username
        ");
        $stmt->execute([$school_id, $class_id]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function canUserAccessFile($file_path, $user_id)
    {
        $sql = "SELECT m.id FROM school_messages m
                JOIN school_conversations c ON m.conversation_id = c.id
                WHERE m.file_path = ? AND (c.user_id = ? OR c.teacher_id = ?)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$file_path, $user_id, $user_id]);

        return $stmt->fetchColumn() !== false;
    }
    public function getOriginalFileName($file_path)
    {
        $sql = "SELECT file_name FROM school_messages WHERE file_path = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$file_path]);

        return $stmt->fetchColumn();
    }
    private function updateConversationTimestamp($conversation_id)
    {
        $sql = "UPDATE school_conversations SET updated_at = CURRENT_TIMESTAMP WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$conversation_id]);
    }

    public function getConversationsForAdmin(): array
    {
        $sql = "
        SELECT 
            c.id,
            c.updated_at,
            
            u1.student_id AS student_id,            
            u1.username AS student_username,
            u1.name AS student_name,
            u1.surname AS student_surname,
            u1.photo AS student_photo,
            
            u2.teacher_id AS teacher_id,
            u2.username AS teacher_username,
            u2.name AS teacher_name,
            u2.surname AS teacher_surname,
            u2.photo AS teacher_photo,

            classes_lnp.name AS className,
            lessons_lnp.name AS lessonName   

        FROM school_conversations c
        LEFT JOIN users_lnp u1 ON c.student_id = u1.id
        LEFT JOIN users_lnp u2 ON c.teacher_id = u2.id
        LEFT JOIN school_messages m ON c.id = m.conversation_id
        LEFT JOIN classes_lnp ON u2.class_id = classes_lnp.id 
        LEFT JOIN lessons_lnp ON u2.lesson_id = lessons_lnp.id 
        LEFT JOIN school_messages m3 ON c.id = m3.conversation_id AND m.created_at < m3.created_at
        WHERE m3.id IS NULL 
        ORDER BY c.updated_at DESC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getConversationsForAdminBySchool($school_id): array
    {
        $sql = "
        SELECT 
            c.id,
            c.updated_at,
            
            u1.student_id AS student_id,            
            u1.username AS student_username,
            u1.name AS student_name,
            u1.surname AS student_surname,
            u1.photo AS student_photo,
            
            u2.teacher_id AS teacher_id,
            u2.username AS teacher_username,
            u2.name AS teacher_name,
            u2.surname AS teacher_surname,
            u2.photo AS teacher_photo,

            classes_lnp.name AS className,
            lessons_lnp.name AS lessonName 
        FROM school_conversations c
        LEFT JOIN users_lnp u1 ON c.student_id = u1.id
        LEFT JOIN users_lnp u2 ON c.teacher_id = u2.id
        LEFT JOIN school_messages m ON c.id = m.conversation_id
        LEFT JOIN school_messages m3 ON c.id = m3.conversation_id AND m.created_at < m3.created_at
        LEFT JOIN classes_lnp ON u2.class_id = classes_lnp.id 
        LEFT JOIN lessons_lnp ON u2.lesson_id = lessons_lnp.id 
        WHERE (u1.school_id = :school_id OR u2.school_id = :school_id) AND m3.id IS NULL 
        ORDER BY c.updated_at DESC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['school_id' => $school_id]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
