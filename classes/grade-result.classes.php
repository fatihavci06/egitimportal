<?php
class GradeResult
{

    private $db;
    public function __construct()
    {
        require_once 'dbh.classes.php';

        $this->db = (new dbh())->connect();
    }
    public function getHighestGradeOverall($schoolId)
    {
        
        try {
            $sql = "SELECT AVG(score) as average_score,
            u.*,
            c.name AS className,
            s.name AS schoolName 
            FROM user_grades_lnp
            LEFT JOIN users_lnp u ON user_grades_lnp.user_id = u.id
            LEFT JOIN classes_lnp c ON u.class_id = c.id 
            LEFT JOIN schools_lnp s ON u.school_id = s.id
            WHERE u.school_id = :school_id 
            AND unit_id != 0
            GROUP BY user_grades_lnp.user_id
            ORDER BY average_score DESC
            LIMIT 5
            ";

            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':school_id', $schoolId, PDO::PARAM_INT);
            $stmt->execute();

            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $result;

        } catch (PDOException $e) {
            return null;
        }
    }
    public function getGradeOverall($userId)
    {
        try {
            $sql = "SELECT AVG(score) as average_score 
                    FROM user_grades_lnp
            
                    WHERE user_id = :user_id 
                    AND unit_id != 0
                    
                    ";

            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $stmt->execute();

            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            return ($result['average_score'] !== null) ? $this->getFirstThreeDecimalDigits($result['average_score']) : null;

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
                AND lesson_id != 0
                AND unit_id != 0
                ";

            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $stmt->bindParam(':lesson_id', $lessonId, PDO::PARAM_INT);
            $stmt->execute();

            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            return ($result['average_score'] !== null) ? $this->getFirstThreeDecimalDigits($result['average_score']) : null;

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
                AND unit_id != 0
                ";

            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $stmt->bindParam(':unit_id', $unitId, PDO::PARAM_INT);
            $stmt->execute();

            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            return (($result['average_score'] !== null)) ? $this->getFirstThreeDecimalDigits($result['average_score']) : null;

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
                AND topic_id != 0
                AND unit_id != 0
                ";

            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $stmt->bindParam(':topic_id', $topicId, PDO::PARAM_INT);
            $stmt->execute();

            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            return ($result['average_score'] !== null) ? $this->getFirstThreeDecimalDigits($result['average_score']) : null;

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
                    AND subtopic_id != 0
                    AND unit_id != 0
                    ";

            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $stmt->bindParam(':subtopic_id', $subtopicId, PDO::PARAM_INT);
            $stmt->execute();

            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            return ($result['average_score'] !== null) ? $this->getFirstThreeDecimalDigits($result['average_score']) : null;

        } catch (PDOException $e) {
            return null;
        }
    }
    function getFirstThreeDecimalDigits($number)
    {

        $truncated = (int) ($number * 100);
        return $truncated / 100;
    }
}
