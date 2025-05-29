<?php
class VideoTracker
{
    private $db;

    public function __construct()
    {
        require_once 'dbh.classes.php';

        $this->db = (new dbh())->connect();
    }

    public function store($userId, $videoId, $timestampSeconds)
    {
        try {
            $stmt = $this->db->prepare("
            INSERT INTO video_timestamp_lnp (user_id, video_id, timestamp_in_seconds) 
                VALUES (?, ?, ?)
            ON DUPLICATE KEY UPDATE 
                timestamp_in_seconds = VALUES(timestamp_in_seconds),
                event_time = CURRENT_TIMESTAMP;
            ");

            $stmt->execute([$userId, $videoId, $timestampSeconds]);

            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            return false;
        }
    }

    public function getWatchEvents($userId, $videoId)
    {
        try {
            $stmt = $this->db->prepare("
                SELECT id, timestamp_in_seconds, event_time
                FROM video_timestamp_lnp
                WHERE user_id = ? AND video_id = ?
                ORDER BY event_time DESC
 
            ");

            $stmt->execute([$userId, $videoId]);

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error fetching watch timestamp: " . $e->getMessage());
            return false;
        }
    }

    public function getWatchProgress($userId, $videoId)
    {
        try {
            $stmt = $this->db->prepare("
                SELECT *
                FROM video_timestamp_lnp
                WHERE user_id = ? AND video_id = ?
            ");
            $stmt->execute([$userId, $videoId]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function getVideoStatistics($videoId)
    {
        try {
            $stmt = $this->db->prepare("
                SELECT 
                    COUNT(DISTINCT user_id) as unique_viewers,
                    COUNT(*) as total_views,
                    AVG(timestamp_in_seconds) as avg_watched_seconds,
                    MAX(timestamp_in_seconds) as max_watched_seconds
                FROM video_timestamp_lnp
                WHERE video_id = ?
            ");

            $stmt->execute([$videoId]);

            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error fetching video statistics: " . $e->getMessage());
            return false;
        }
    }
}
