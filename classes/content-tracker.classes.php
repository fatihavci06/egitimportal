<?php
class ContentTracker
{

    private $db;
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

    // public function getAllContentOfUnitById($unitId)
    // {
    //     try {
    //         $sql = "
    //         SELECT * FROM school_content_lnp scl
    //         LEFT JOIN school_content_videos_url v ON scl.unit_id = v.id;
    //         LEFT JOIN  v ON v.id = v.id;
    //         WHERE unit_id = ? 
    //         ";
    //         $stmt = $this->db->prepare($sql);
    //         $stmt->execute([$unitId]);
    //         return $stmt->fetchAll(PDO::FETCH_ASSOC);
    //     } catch (Exception $e) {
    //         return null;
    //     }
    // }

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
            return null;
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
            return null;
        }
    }
    public function getSchoolContentAnalyticsOverall($user_id)
    {
        $sql = "
            SELECT 
                sc.id as content_id,
                sc.slug,
                sc.title,
                sc.summary,
                sc.school_id,
                sc.teacher_id,
                sc.class_id,
                sc.lesson_id,
                sc.unit_id,
                sc.topic_id,
                sc.subtopic_id,
                sc.cover_img,
                sc.text_content,
                sc.order_no,
                sc.active,
                
                CASE WHEN cv.user_id IS NOT NULL THEN 1 ELSE 0 END as content_visited,
                
                
                COUNT(DISTINCT scv.id) as total_videos,
                
                SUM(
                    CASE 
                        WHEN vd.duration > 0 AND vt.max_timestamp >= (vd.duration * 0.9) THEN 1 
                        ELSE 0 
                    END
                ) as completed_videos,
                
                COUNT(DISTINCT scf.id) as total_files,
                
                SUM(CASE WHEN fd.user_id IS NOT NULL THEN 1 ELSE 0 END) as downloaded_files,
                
                COUNT(DISTINCT scw.id) as total_wordwalls,
                
                SUM(CASE WHEN wv.user_id IS NOT NULL THEN 1 ELSE 0 END) as viewed_wordwalls

            FROM school_content_lnp sc

            LEFT JOIN content_visits cv ON sc.id = cv.content_id AND cv.user_id = ?

            LEFT JOIN school_content_videos_url scv ON sc.id = scv.school_content_id
            LEFT JOIN video_durations vd ON scv.id = vd.video_id
            LEFT JOIN video_timestamp_lnp vt ON scv.id = vt.video_id AND vt.user_id = ?

            LEFT JOIN school_content_files_lnp scf ON sc.id = scf.school_content_id
            LEFT JOIN file_downloads fd ON scf.id = fd.file_id AND fd.user_id = ?

            LEFT JOIN school_content_wordwall_lnp scw ON sc.id = scw.school_content_id
            LEFT JOIN wordwall_views wv ON scw.id = wv.wordwall_id AND wv.user_id = ?

            WHERE sc.active = 1

            GROUP BY 
                sc.id, sc.slug, sc.title, sc.summary, sc.school_id, sc.teacher_id, 
                sc.class_id, sc.lesson_id, sc.unit_id, sc.topic_id, sc.subtopic_id, 
                sc.cover_img, sc.text_content, sc.order_no, sc.active, cv.user_id

            ORDER BY sc.order_no ASC, sc.id ASC
        ";

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$user_id, $user_id, $user_id, $user_id]);
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $sums = [];
            $percentage = 0;
            if (empty($results)) {
                return null;

            }

            $sums = [
                'content_visits' => 0,
                'total_content_visits' => 0,
            ];

            foreach ($results as $item) {
                if (($item['total_wordwalls'] == 0) && ($item['total_files'] == 0) && ($item['total_videos'] == 0)) {
                    $sums['total_content_visits'] += 1;
                    $sums['content_visits'] += $item['content_visited'];

                }

                foreach ($item as $key => $value) {
                    if (is_numeric($value)) {
                        $sums[$key] = ($sums[$key] ?? 0) + (int) $value;
                    }
                }
            }
            if (!empty($sums)) {
                $total_points = $sums['total_wordwalls'] + $sums['total_files'] + $sums['total_videos'] + $sums['total_content_visits'];
                $result_points = $sums['viewed_wordwalls'] + $sums['downloaded_files'] + $sums['completed_videos'] + $sums['content_visits'];
                if ($total_points > 0) {
                    $percentage = $this->getFirstThreeDecimalDigits(($result_points / $total_points) * 100);
                }

            }
            return $percentage;

        } catch (PDOException $e) {
            return null;
        }
    }
    public function getSchoolContentAnalyticsByLessonId($user_id, $class_id, $lesson_id)
    {
        $sql = "
            SELECT 
                sc.id as content_id,
                sc.slug,
                sc.title,
                sc.summary,
                sc.school_id,
                sc.teacher_id,
                sc.class_id,
                sc.lesson_id,
                sc.unit_id,
                sc.topic_id,
                sc.subtopic_id,
                sc.cover_img,
                sc.text_content,
                sc.order_no,
                sc.active,
                
                CASE WHEN cv.user_id IS NOT NULL THEN 1 ELSE 0 END as content_visited,
                
                
                COUNT(DISTINCT scv.id) as total_videos,
                
                SUM(
                    CASE 
                        WHEN vd.duration > 0 AND vt.max_timestamp >= (vd.duration * 0.9) THEN 1 
                        ELSE 0 
                    END
                ) as completed_videos,
                
                COUNT(DISTINCT scf.id) as total_files,
                
                SUM(CASE WHEN fd.user_id IS NOT NULL THEN 1 ELSE 0 END) as downloaded_files,
                
                COUNT(DISTINCT scw.id) as total_wordwalls,
                
                SUM(CASE WHEN wv.user_id IS NOT NULL THEN 1 ELSE 0 END) as viewed_wordwalls

            FROM school_content_lnp sc

            LEFT JOIN content_visits cv ON sc.id = cv.content_id AND cv.user_id = ?

            LEFT JOIN school_content_videos_url scv ON sc.id = scv.school_content_id
            LEFT JOIN video_durations vd ON scv.id = vd.video_id
            LEFT JOIN video_timestamp_lnp vt ON scv.id = vt.video_id AND vt.user_id = ?

            LEFT JOIN school_content_files_lnp scf ON sc.id = scf.school_content_id
            LEFT JOIN file_downloads fd ON scf.id = fd.file_id AND fd.user_id = ?

            LEFT JOIN school_content_wordwall_lnp scw ON sc.id = scw.school_content_id
            LEFT JOIN wordwall_views wv ON scw.id = wv.wordwall_id AND wv.user_id = ?

            WHERE sc.class_id = ? AND sc.lesson_id = ? 
            AND sc.active = 1

            GROUP BY 
                sc.id, sc.slug, sc.title, sc.summary, sc.school_id, sc.teacher_id, 
                sc.class_id, sc.lesson_id, sc.unit_id, sc.topic_id, sc.subtopic_id, 
                sc.cover_img, sc.text_content, sc.order_no, sc.active, cv.user_id

            ORDER BY sc.order_no ASC, sc.id ASC
        ";


        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$user_id, $user_id, $user_id, $user_id, $class_id, $lesson_id]);
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $sums = [];
            $percentage = 0;
            if (empty($results)) {
                return null;

            }

            $sums = [
                'content_visits' => 0,
                'total_content_visits' => 0,
            ];

            foreach ($results as $item) {
                if (($item['total_wordwalls'] == 0) && ($item['total_files'] == 0) && ($item['total_videos'] == 0)) {
                    $sums['total_content_visits'] += 1;
                    $sums['content_visits'] += $item['content_visited'];

                }

                foreach ($item as $key => $value) {
                    if (is_numeric($value)) {
                        $sums[$key] = ($sums[$key] ?? 0) + (int) $value;
                    }
                }
            }
            if (!empty($sums)) {
                $total_points = $sums['total_wordwalls'] + $sums['total_files'] + $sums['total_videos'] + $sums['total_content_visits'];
                $result_points = $sums['viewed_wordwalls'] + $sums['downloaded_files'] + $sums['completed_videos'] + $sums['content_visits'];
                if ($total_points > 0) {
                    $percentage = $this->getFirstThreeDecimalDigits(($result_points / $total_points) * 100);
                }

            }
            return $percentage;

        } catch (PDOException $e) {
            return null;
        }
    }
    public function getSchoolContentAnalyticsByUnitId($user_id, $class_id, $lesson_id, $unit_id)
    {
        $sql = "
            SELECT 
                sc.id as content_id,
                sc.slug,
                sc.title,
                sc.summary,
                sc.school_id,
                sc.teacher_id,
                sc.class_id,
                sc.lesson_id,
                sc.unit_id,
                sc.topic_id,
                sc.subtopic_id,
                sc.cover_img,
                sc.text_content,
                sc.order_no,
                sc.active,
                
                CASE WHEN cv.user_id IS NOT NULL THEN 1 ELSE 0 END as content_visited,
                
                
                COUNT(DISTINCT scv.id) as total_videos,
                
                SUM(
                    CASE 
                        WHEN vd.duration > 0 AND vt.max_timestamp >= (vd.duration * 0.9) THEN 1 
                        ELSE 0 
                    END
                ) as completed_videos,
                
                COUNT(DISTINCT scf.id) as total_files,
                
                SUM(CASE WHEN fd.user_id IS NOT NULL THEN 1 ELSE 0 END) as downloaded_files,
                
                COUNT(DISTINCT scw.id) as total_wordwalls,
                
                SUM(CASE WHEN wv.user_id IS NOT NULL THEN 1 ELSE 0 END) as viewed_wordwalls

            FROM school_content_lnp sc

            LEFT JOIN content_visits cv ON sc.id = cv.content_id AND cv.user_id = ?

            LEFT JOIN school_content_videos_url scv ON sc.id = scv.school_content_id
            LEFT JOIN video_durations vd ON scv.id = vd.video_id
            LEFT JOIN video_timestamp_lnp vt ON scv.id = vt.video_id AND vt.user_id = ?

            LEFT JOIN school_content_files_lnp scf ON sc.id = scf.school_content_id
            LEFT JOIN file_downloads fd ON scf.id = fd.file_id AND fd.user_id = ?

            LEFT JOIN school_content_wordwall_lnp scw ON sc.id = scw.school_content_id
            LEFT JOIN wordwall_views wv ON scw.id = wv.wordwall_id AND wv.user_id = ?

            WHERE sc.class_id = ? AND sc.lesson_id = ? AND sc.unit_id = ?
            AND sc.active = 1

            GROUP BY 
                sc.id, sc.slug, sc.title, sc.summary, sc.school_id, sc.teacher_id, 
                sc.class_id, sc.lesson_id, sc.unit_id, sc.topic_id, sc.subtopic_id, 
                sc.cover_img, sc.text_content, sc.order_no, sc.active, cv.user_id

            ORDER BY sc.order_no ASC, sc.id ASC
        ";

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$user_id, $user_id, $user_id, $user_id, $class_id, $lesson_id, $unit_id]);
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $sums = [];
            $percentage = 0;
            if (empty($results)) {
                return null;

            }
            $sums = [
                'content_visits' => 0,
                'total_content_visits' => 0,
            ];

            foreach ($results as $item) {
                if (($item['total_wordwalls'] == 0) && ($item['total_files'] == 0) && ($item['total_videos'] == 0)) {
                    $sums['total_content_visits'] += 1;
                    $sums['content_visits'] += $item['content_visited'];

                }

                foreach ($item as $key => $value) {
                    if (is_numeric($value)) {
                        $sums[$key] = ($sums[$key] ?? 0) + (int) $value;
                    }
                }
            }
            if (!empty($sums)) {
                $total_points = $sums['total_wordwalls'] + $sums['total_files'] + $sums['total_videos'] + $sums['total_content_visits'];
                $result_points = $sums['viewed_wordwalls'] + $sums['downloaded_files'] + $sums['completed_videos'] + $sums['content_visits'];
                if ($total_points > 0) {
                    $percentage = $this->getFirstThreeDecimalDigits(($result_points / $total_points) * 100);
                }

            }
            return $percentage;

        } catch (PDOException $e) {
            return null;
        }
    }
    public function getSchoolContentAnalyticsByTopicId($user_id, $class_id, $lesson_id, $unit_id, $topic_id)
    {
        $sql = "
            SELECT 
                sc.id as content_id,
                sc.slug,
                sc.title,
                sc.summary,
                sc.school_id,
                sc.teacher_id,
                sc.class_id,
                sc.lesson_id,
                sc.unit_id,
                sc.topic_id,
                sc.subtopic_id,
                sc.cover_img,
                sc.text_content,
                sc.order_no,
                sc.active,
                
                CASE WHEN cv.user_id IS NOT NULL THEN 1 ELSE 0 END as content_visited,
                
                
                COUNT(DISTINCT scv.id) as total_videos,
                
                SUM(
                    CASE 
                        WHEN vd.duration > 0 AND vt.max_timestamp >= (vd.duration * 0.9) THEN 1 
                        ELSE 0 
                    END
                ) as completed_videos,
                
                COUNT(DISTINCT scf.id) as total_files,
                
                SUM(CASE WHEN fd.user_id IS NOT NULL THEN 1 ELSE 0 END) as downloaded_files,
                
                COUNT(DISTINCT scw.id) as total_wordwalls,
                
                SUM(CASE WHEN wv.user_id IS NOT NULL THEN 1 ELSE 0 END) as viewed_wordwalls

            FROM school_content_lnp sc

            LEFT JOIN content_visits cv ON sc.id = cv.content_id AND cv.user_id = ?

            LEFT JOIN school_content_videos_url scv ON sc.id = scv.school_content_id
            LEFT JOIN video_durations vd ON scv.id = vd.video_id
            LEFT JOIN video_timestamp_lnp vt ON scv.id = vt.video_id AND vt.user_id = ?

            LEFT JOIN school_content_files_lnp scf ON sc.id = scf.school_content_id
            LEFT JOIN file_downloads fd ON scf.id = fd.file_id AND fd.user_id = ?

            LEFT JOIN school_content_wordwall_lnp scw ON sc.id = scw.school_content_id
            LEFT JOIN wordwall_views wv ON scw.id = wv.wordwall_id AND wv.user_id = ?

            WHERE sc.class_id = ? AND sc.lesson_id = ? AND sc.unit_id = ? AND sc.topic_id = ?
            AND sc.active = 1

            GROUP BY 
                sc.id, sc.slug, sc.title, sc.summary, sc.school_id, sc.teacher_id, 
                sc.class_id, sc.lesson_id, sc.unit_id, sc.topic_id, sc.subtopic_id, 
                sc.cover_img, sc.text_content, sc.order_no, sc.active, cv.user_id

            ORDER BY sc.order_no ASC, sc.id ASC
        ";

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$user_id, $user_id, $user_id, $user_id, $class_id, $lesson_id, $unit_id, $topic_id]);
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $sums = [];
            $percentage = 0;
            if (empty($results)) {
                return null;

            }

            $sums = [
                'content_visits' => 0,
                'total_content_visits' => 0,
            ];

            foreach ($results as $item) {
                if (($item['total_wordwalls'] == 0) && ($item['total_files'] == 0) && ($item['total_videos'] == 0)) {
                    $sums['total_content_visits'] += 1;
                    $sums['content_visits'] += $item['content_visited'];

                }

                foreach ($item as $key => $value) {
                    if (is_numeric($value)) {
                        $sums[$key] = ($sums[$key] ?? 0) + (int) $value;
                    }
                }
            }
            if (!empty($sums)) {
                $total_points = $sums['total_wordwalls'] + $sums['total_files'] + $sums['total_videos'] + $sums['total_content_visits'];
                $result_points = $sums['viewed_wordwalls'] + $sums['downloaded_files'] + $sums['completed_videos'] + $sums['content_visits'];
                if ($total_points > 0) {
                    $percentage = $this->getFirstThreeDecimalDigits(($result_points / $total_points) * 100);
                }

            }
            return $percentage;

        } catch (PDOException $e) {
            return null;
        }
    }
    public function getSchoolContentAnalyticsBySubtopicId($user_id, $class_id, $lesson_id, $unit_id, $topic_id, $subtopic_id)
    {
        $sql = "
            SELECT 
                sc.id as content_id,
                sc.slug,
                sc.title,
                sc.summary,
                sc.school_id,
                sc.teacher_id,
                sc.class_id,
                sc.lesson_id,
                sc.unit_id,
                sc.topic_id,
                sc.subtopic_id,
                sc.cover_img,
                sc.text_content,
                sc.order_no,
                sc.active,
                
                CASE WHEN cv.user_id IS NOT NULL THEN 1 ELSE 0 END as content_visited,
                
                
                COUNT(DISTINCT scv.id) as total_videos,
                
                SUM(
                    CASE 
                        WHEN vd.duration > 0 AND vt.max_timestamp >= (vd.duration * 0.9) THEN 1 
                        ELSE 0 
                    END
                ) as completed_videos,
                
                COUNT(DISTINCT scf.id) as total_files,
                
                SUM(CASE WHEN fd.user_id IS NOT NULL THEN 1 ELSE 0 END) as downloaded_files,
                
                COUNT(DISTINCT scw.id) as total_wordwalls,
                
                SUM(CASE WHEN wv.user_id IS NOT NULL THEN 1 ELSE 0 END) as viewed_wordwalls

            FROM school_content_lnp sc

            LEFT JOIN content_visits cv ON sc.id = cv.content_id AND cv.user_id = ?

            LEFT JOIN school_content_videos_url scv ON sc.id = scv.school_content_id
            LEFT JOIN video_durations vd ON scv.id = vd.video_id
            LEFT JOIN video_timestamp_lnp vt ON scv.id = vt.video_id AND vt.user_id = ?

            LEFT JOIN school_content_files_lnp scf ON sc.id = scf.school_content_id
            LEFT JOIN file_downloads fd ON scf.id = fd.file_id AND fd.user_id = ?

            LEFT JOIN school_content_wordwall_lnp scw ON sc.id = scw.school_content_id
            LEFT JOIN wordwall_views wv ON scw.id = wv.wordwall_id AND wv.user_id = ?

            WHERE sc.class_id = ? AND sc.lesson_id = ? AND sc.unit_id = ? AND sc.topic_id = ? AND sc.subtopic_id = ?
            AND sc.active = 1

            GROUP BY 
                sc.id, sc.slug, sc.title, sc.summary, sc.school_id, sc.teacher_id, 
                sc.class_id, sc.lesson_id, sc.unit_id, sc.topic_id, sc.subtopic_id, 
                sc.cover_img, sc.text_content, sc.order_no, sc.active, cv.user_id

            ORDER BY sc.order_no ASC, sc.id ASC
        ";

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$user_id, $user_id, $user_id, $user_id, $class_id, $lesson_id, $unit_id, $topic_id, $subtopic_id]);
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $sums = [];
            $percentage = 0;
            if (empty($results)) {
                return null;

            }

            $sums = [
                'content_visits' => 0,
                'total_content_visits' => 0,
            ];

            foreach ($results as $item) {
                if (($item['total_wordwalls'] == 0) && ($item['total_files'] == 0) && ($item['total_videos'] == 0)) {
                    $sums['total_content_visits'] += 1;
                    $sums['content_visits'] += $item['content_visited'];

                }

                foreach ($item as $key => $value) {
                    if (is_numeric($value)) {
                        $sums[$key] = ($sums[$key] ?? 0) + (int) $value;
                    }
                }
            }
            if (!empty($sums)) {
                $total_points = $sums['total_wordwalls'] + $sums['total_files'] + $sums['total_videos'] + $sums['total_content_visits'];
                $result_points = $sums['viewed_wordwalls'] + $sums['downloaded_files'] + $sums['completed_videos'] + $sums['content_visits'];
                if ($total_points > 0) {
                    $percentage = $this->getFirstThreeDecimalDigits(($result_points / $total_points) * 100);
                }

            }
            return $percentage;

        } catch (PDOException $e) {
            return null;
        }
    }
    function getFirstThreeDecimalDigits($number)
    {

        $truncated = (int) ($number * 100);
        return $truncated / 100;
    }

    public function getSchoolContentAnalyticsListByUserId($user_id, $class_id)
    {
        $sql = "
            SELECT 
                sc.id as content_id,
                sc.slug,
                sc.title,
                sc.summary,
                sc.school_id,
                sc.teacher_id,
                sc.class_id,
                sc.lesson_id,
                sc.unit_id,
                sc.topic_id,
                sc.subtopic_id,
                sc.cover_img,
                sc.text_content,
                sc.order_no,
                sc.active,
                
                CASE WHEN cv.user_id IS NOT NULL THEN 1 ELSE 0 END as content_visited,
                
                
                COUNT(DISTINCT scv.id) as total_videos,
                
                SUM(
                    CASE 
                        WHEN vd.duration > 0 AND vt.max_timestamp >= (vd.duration * 0.9) THEN 1 
                        ELSE 0 
                    END
                ) as completed_videos,
                
                COUNT(DISTINCT scf.id) as total_files,
                
                SUM(CASE WHEN fd.user_id IS NOT NULL THEN 1 ELSE 0 END) as downloaded_files,
                
                COUNT(DISTINCT scw.id) as total_wordwalls,

                ts.name AS topic_name,
                sts.name AS subtopic_name,
                
                SUM(CASE WHEN wv.user_id IS NOT NULL THEN 1 ELSE 0 END) as viewed_wordwalls

            FROM school_content_lnp sc

            LEFT JOIN content_visits cv ON sc.id = cv.content_id AND cv.user_id = :user_id

            LEFT JOIN school_content_videos_url scv ON sc.id = scv.school_content_id
            LEFT JOIN video_durations vd ON scv.id = vd.video_id
            LEFT JOIN video_timestamp_lnp vt ON scv.id = vt.video_id AND vt.user_id = :user_id

            LEFT JOIN school_content_files_lnp scf ON sc.id = scf.school_content_id
            LEFT JOIN file_downloads fd ON scf.id = fd.file_id AND fd.user_id = :user_id

            LEFT JOIN school_content_wordwall_lnp scw ON sc.id = scw.school_content_id
            LEFT JOIN wordwall_views wv ON scw.id = wv.wordwall_id AND wv.user_id = :user_id

            LEFT JOIN topics_lnp ts ON sc.topic_id = ts.id AND sc.lesson_id = ts.lesson_id 
            LEFT JOIN subtopics_lnp sts ON sc.subtopic_id = sts.id AND sc.lesson_id = sts.lesson_id 
            WHERE sc.active = 1 AND sc.class_id = :class_id

            GROUP BY 
                sc.id, sc.slug, sc.title, sc.summary, sc.school_id, sc.teacher_id, 
                sc.class_id, sc.lesson_id, sc.unit_id, sc.topic_id, sc.subtopic_id, 
                sc.cover_img, sc.text_content, sc.order_no, sc.active, cv.user_id, ts.name, sts.name

            ORDER BY sc.order_no ASC, sc.id ASC
        ";

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['user_id' => $user_id, 'class_id' => $class_id]);
            $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($items as $key => $item) {

            }
            return $items;

        } catch (PDOException $e) {
            return null;
        }
    }



}