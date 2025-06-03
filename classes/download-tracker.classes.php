<?php
class DownloadTracker
{

    private $db;
    public function __construct()
    {
        require_once 'dbh.classes.php';

        $this->db = (new dbh())->connect();
    }


    public function createFileVisit($userId, $fileId)
    {
        try {
            $sql = "INSERT INTO file_downloads (user_id, file_id) VALUES (?, ?)";
            $stmt = $this->db->prepare($sql);
            if ($stmt->execute([$userId, $fileId])) {
                return $this->db->lastInsertId();
            }
            return false;
        } catch (Exception $e) {
            return false;
        }
    }

    public function getById($id)
    {
        try {
            $sql = "SELECT * FROM file_downloads WHERE id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return false;
        }
    }

    public function getByUserAndFile($userId, $fileId)
    {
        try {
            $sql = "SELECT * FROM file_downloads WHERE user_id = ? AND file_id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$userId, $fileId]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return false;
        }
    }

    public function getByUser($userId)
    {
        try {
            $sql = "SELECT * FROM file_downloads WHERE user_id = ? ORDER BY event_time DESC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$userId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return [];
        }
    }

    public function getByFile($fileId)
    {
        try {
            $sql = "SELECT * FROM file_downloads WHERE file_id = ? ORDER BY event_time DESC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$fileId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return [];
        }
    }


}