<?php
class WordwallTracker
{

    private $db;
    public function __construct()
    {
        require_once 'dbh.classes.php';

        $this->db = (new dbh())->connect();
    }


    public function createWordwallVisit($userId, $wordwallId)
    {
        try {
            $sql = "INSERT INTO wordwall_views (user_id, wordwall_id) VALUES (?, ?)";
            $stmt = $this->db->prepare($sql);
            if ($stmt->execute([$userId, $wordwallId])) {
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
            $sql = "SELECT * FROM wordwall_views WHERE id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return false;
        }
    }

    public function getByUserAndWordwall($userId, $wordwallId)
    {
        try {
            $sql = "SELECT * FROM wordwall_views WHERE user_id = ? AND wordwall_id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$userId, $wordwallId]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return false;
        }
    }

    public function getByUser($userId)
    {
        try {
            $sql = "SELECT * FROM wordwall_views WHERE user_id = ? ORDER BY event_time DESC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$userId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return [];
        }
    }

    public function getByWordwall($wordwallId)
    {
        try {
            $sql = "SELECT * FROM wordwall_views WHERE wordwall_id = ? ORDER BY event_time DESC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$wordwallId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return [];
        }
    }


}