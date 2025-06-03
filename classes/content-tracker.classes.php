<?php
class ContentTracker
{

    private $db;
    private $table = 'content_visits';
    public function __construct()
    {
        require_once 'dbh.classes.php';

        $this->db = (new dbh())->connect();
    }

    public function createContentVisit($userId, $contentId)
    {
        try {
            $sql = "INSERT INTO content_visits (user_id, content_id) VALUES (?, ?)";
            $stmt = $this->db->prepare($sql);
            if ($stmt->execute([$userId, $contentId])) {
                return $this->db->lastInsertId();
            }
            return false;
        } catch (Exception $e) {
            return false;
        }
    }

    public function getAllContentOfUnitById($unitId){
        try {
            $sql = "
            SELECT * FROM school_content_lnp scl
            LEFT JOIN school_content_videos_url v ON scl.unit_id = v.id;
            WHERE unit_id = ? 
            ";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$unitId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return [];
        }
    }

    public function getById($id)
    {
        try {
            $sql = "SELECT * FROM content_visits WHERE id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return false;
        }
    }

    public function getByUserAndContent($userId, $contentId)
    {
        try {
            $sql = "SELECT * FROM content_visits WHERE user_id = ? AND content_id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$userId, $contentId]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return false;
        }
    }

    public function getByUser($userId)
    {
        try {
            $sql = "SELECT * FROM content_visits WHERE user_id = ? ORDER BY event_time DESC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$userId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return [];
        }
    }

    public function getByContent($contentId)
    {
        try {
            $sql = "SELECT * FROM content_visits WHERE content_id = ? ORDER BY event_time DESC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$contentId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return [];
        }
    }

}