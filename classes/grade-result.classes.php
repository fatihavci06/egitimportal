<?php
class GradeResult
{

    private $db;
    public function __construct()
    {
        require_once 'dbh.classes.php';

        $this->db = (new dbh())->connect();
    }
        public function getGradeOverall($userId)
    {
        try {
            $sql = "SELECT AVG(score) as average_score 
                FROM user_grades_lnp 
                WHERE user_id = :user_id";

            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $stmt->execute();

            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            return $result['average_score'] !== null ? (float) $result['average_score'] : null;

        } catch (PDOException $e) {
            return null;
        }
    }
    public function getGradeByLessonId($userId, $lessonId)
    {
        try {
            $sql = "SELECT AVG(score) as average_score 
                FROM user_grades_lnp 
                WHERE user_id = :user_id 
                AND lesson_id = :lesson_id 
                AND lesson_id IS NOT NULL 
                AND lesson_id != 0";

            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $stmt->bindParam(':lesson_id', $lessonId, PDO::PARAM_INT);
            $stmt->execute();

            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            return $result['average_score'] !== null ? (float) $result['average_score'] : null;

        } catch (PDOException $e) {
            return null;
        }
    }

    public function getGradeByUnitId($userId, $unitId)
    {
        try {
            $sql = "SELECT AVG(score) as average_score 
                FROM user_grades_lnp 
                WHERE user_id = :user_id 
                AND unit_id = :unit_id 
                AND unit_id IS NOT NULL 
                AND unit_id != 0";

            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $stmt->bindParam(':unit_id', $unitId, PDO::PARAM_INT);
            $stmt->execute();

            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            return $result['average_score'] !== null ? (float) $result['average_score'] : null;

        } catch (PDOException $e) {
            return null;
        }
    }

    public function getGradeByTopicId($userId, $topicId)
    {
        try {
            $sql = "SELECT AVG(score) as average_score 
                FROM user_grades_lnp 
                WHERE user_id = :user_id 
                AND topic_id = :topic_id 
                AND topic_id IS NOT NULL 
                AND topic_id != 0";

            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $stmt->bindParam(':topic_id', $topicId, PDO::PARAM_INT);
            $stmt->execute();

            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            return $result['average_score'] !== null ? (float) $result['average_score'] : null;

        } catch (PDOException $e) {
            return null;
        }
    }

    public function getGradeBySubtopicId($userId, $subtopicId)
    {
        try {
            $sql = "SELECT AVG(score) as average_score 
                FROM user_grades_lnp 
                WHERE user_id = :user_id 
                AND subtopic_id = :subtopic_id 
                AND subtopic_id IS NOT NULL 
                AND subtopic_id != 0";

            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $stmt->bindParam(':subtopic_id', $subtopicId, PDO::PARAM_INT);
            $stmt->execute();

            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            return $result['average_score'] !== null ? (float) $result['average_score'] : null;

        } catch (PDOException $e) {
            return null;
        }
    }
}
