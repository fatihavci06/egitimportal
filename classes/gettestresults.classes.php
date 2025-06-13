<?php

class TestsResult extends Dbh
{
    public function getUnitTestResults($unitId, $classId, $userId)
    {
        $stmt = $this->connect()->prepare('SELECT * FROM user_grades_lnp WHERE unit_id = ? AND class_id = ? AND topic_id = 0 AND subtopic_id = 0 AND user_id = ?');

        if (!$stmt->execute(array($unitId, $classId, $userId))) {
            $stmt = null;
            exit();
        }

        $testResultsData = $stmt->fetch(PDO::FETCH_ASSOC);

        return $testResultsData;

        $stmt = null;
    }

    
    public function getTopicTestResults($unitId, $classId, $topicId, $userId)
    {
        $stmt = $this->connect()->prepare('SELECT * FROM user_grades_lnp WHERE unit_id = ? AND class_id = ? AND topic_id = ? AND subtopic_id = 0 AND user_id = ?');

        if (!$stmt->execute(array($unitId, $classId, $topicId, $userId))) {
            $stmt = null;
            exit();
        }

        $testResultsData = $stmt->fetch(PDO::FETCH_ASSOC);

        return $testResultsData;

        $stmt = null;
    }


    public function getSubTopicTestResults($unitId, $classId, $topicId, $subtopicId, $userId)
    {
        $stmt = $this->connect()->prepare('SELECT * FROM user_grades_lnp WHERE unit_id = ? AND class_id = ? AND topic_id = ? AND subtopic_id = ? AND user_id = ?');

        if (!$stmt->execute(array($unitId, $classId, $topicId, $subtopicId, $userId))) {
            $stmt = null;
            exit();
        }

        $testResultsData = $stmt->fetch(PDO::FETCH_ASSOC);

        return $testResultsData;

        $stmt = null;
    }
}
