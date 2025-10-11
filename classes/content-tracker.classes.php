<?php
class ContentTracker
{

    private $db;
    public function __construct()
    {
        require_once 'dbh.classes.php';

        $this->db = (new dbh())->connect();
    }

    public function getSubscriptionState($schoolId)
    {
        try {
            $db = $this->db;

            // Define SQL queries
            $weeklySql = "SELECT WEEK(subscribed_at) as week, COUNT(*) as count 
            FROM users_lnp 
            WHERE YEAR(subscribed_at) = YEAR(NOW()) AND school_id = :school_id AND role = 2 AND active = 1
            GROUP BY WEEK(subscribed_at)";

            $monthlySql = "SELECT MONTH(subscribed_at) as month, COUNT(*) as count 
            FROM users_lnp 
            WHERE YEAR(subscribed_at) = YEAR(NOW()) AND school_id = :school_id AND role = 2 AND active = 1
            GROUP BY MONTH(subscribed_at)";

            $yearlySql = "SELECT YEAR(subscribed_at) as year, COUNT(*) as count 
            FROM users_lnp 
            WHERE school_id = :school_id AND role = 2 AND active = 1
            GROUP BY YEAR(subscribed_at)";

            // Execute weekly query
            $stmt = $db->prepare($weeklySql);
            $stmt->execute([':school_id' => $schoolId]);
            $weeklyData = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Execute monthly query
            $stmt = $db->prepare($monthlySql);
            $stmt->execute([':school_id' => $schoolId]);
            $monthlyData = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Execute yearly query
            $stmt = $db->prepare($yearlySql);
            $stmt->execute([':school_id' => $schoolId]);
            $yearlyData = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Combine results
            $subscriptionStats = [
                'weekly' => $weeklyData,
                'monthly' => $monthlyData,
                'yearly' => $yearlyData,
            ];

            return $subscriptionStats;
        } catch (PDOException $e) {
            return [
                'error' => true,
                'message' => 'Failed to retrieve subscription stats.'
            ];
        }
    }

    public function getExamsWithStudentId($userId)
    {

        try {
            $sql = "
                SELECT *
                FROM user_grades_lnp
                WHERE user_id = :user_id 
                ";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['user_id' => $userId]);
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        } catch (Exception $e) {
            return false;
        }
    }
    public function getExamsByScore($schoolId)
    {
        try {
            $sql = "
            (SELECT AVG(score) as total, t.test_title, c.name AS className, l.name AS lessonName
            FROM user_grades_lnp ug 
            LEFT JOIN tests_lnp t ON ug.test_id = t.id
            LEFT JOIN classes_lnp c ON t.class_id = c.id
            LEFT JOIN lessons_lnp l ON t.lesson_id = l.id
            WHERE t.school_id = :school_id
            GROUP BY ug.test_id
            ORDER BY total DESC
            LIMIT 5)
            
            UNION ALL

            (SELECT AVG(score) as total, t.test_title, c.name AS className, l.name AS lessonName
            FROM user_grades_lnp ug 
            LEFT JOIN tests_lnp t ON ug.test_id = t.id
            LEFT JOIN classes_lnp c ON t.class_id = c.id
            LEFT JOIN lessons_lnp l ON t.lesson_id = l.id
            WHERE t.school_id = :school_id
            GROUP BY ug.test_id
            ORDER BY total ASC
            LIMIT 5)
        ";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['school_id' => $schoolId]);
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // En yüksek ve en düşük skorları ayır
            $highestScores = array_slice($result, 0, 5);
            $lowestScores = array_slice($result, 5, 5);

            return [
                'highest' => $highestScores,
                'lowest' => $lowestScores
            ];
        } catch (Exception $e) {
            // Hata durumunda boş veri döndür
            return [
                'highest' => [],
                'lowest' => []
            ];
        }
    }
    public function getSchoolStats($schoolId)
    {
        try {
            $sql = "
            SELECT 
                (SELECT COUNT(*) FROM classes_lnp WHERE school_id = :school_id) AS totalClasses,
                (SELECT COUNT(*) FROM lessons_lnp WHERE school_id = :school_id) AS totalLessons,
                (SELECT COUNT(*) FROM units_lnp WHERE school_id = :school_id) AS totalUnits,
                (SELECT COUNT(*) FROM topics_lnp WHERE school_id = :school_id) AS totalTopics,
                (SELECT COUNT(*) FROM subtopics_lnp WHERE school_id = :school_id) AS totalSubtopics,
                (SELECT COUNT(*) FROM games_lnp WHERE school_id = :school_id) AS totalGames,
                (SELECT COUNT(*) FROM audio_book_lnp WHERE school_id = :school_id) AS totalBooks,
                (SELECT COUNT(*) FROM school_content_lnp WHERE school_id = :school_id) AS totalContents
        ";

            $stmt = $this->db->prepare($sql);
            $stmt->execute(['school_id' => $schoolId]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result;
        } catch (Exception $e) {
            return false;
        }
    }

    public function getExamsWithHighestScore($schoolId)
    {

        try {
            $sql = "
                SELECT AVG(score) as total,
                t.test_title,
                c.name AS className,
                l.name AS lessonName
                FROM user_grades_lnp ug 
                LEFT JOIN tests_lnp t ON ug.test_id = t.id
                LEFT JOIN classes_lnp c ON t.class_id = c.id
                LEFT JOIN lessons_lnp l ON t.lesson_id = l.id
                WHERE t.school_id = :school_id
                GROUP BY ug.test_id
                ORDER BY total DESC
                LIMIT 5
                ";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['school_id' => $schoolId]);
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        } catch (Exception $e) {
            return false;
        }
    }
    public function getExamsWithLowestScore($schoolId)
    {

        try {
            $sql = "
                SELECT AVG(score) as total,
                t.test_title,
                c.name AS className,
                l.name AS lessonName
                FROM user_grades_lnp ug 
                LEFT JOIN tests_lnp t ON ug.test_id = t.id
                LEFT JOIN classes_lnp c ON t.class_id = c.id
                LEFT JOIN lessons_lnp l ON t.lesson_id = l.id
                WHERE t.school_id = :school_id
                GROUP BY ug.test_id
                ORDER BY total ASC
                LIMIT 5
                ";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['school_id' => $schoolId]);
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        } catch (Exception $e) {
            return false;
        }
    }

    public function getClassesBySchool($schoolId)
    {
        try {
            $sql = "SELECT 
            COUNT(*) AS total
            FROM classes_lnp
            WHERE classes_lnp.school_id = :school_id
            ";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['school_id' => $schoolId]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result;
        } catch (Exception $e) {
            return false;
        }
    }

    public function getLessonsBySchool($schoolId)
    {
        try {
            $sql = "SELECT 
            COUNT(*) AS total
            FROM lessons_lnp
            WHERE lessons_lnp.school_id = :school_id
            ";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['school_id' => $schoolId]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result;
        } catch (Exception $e) {
            return false;
        }
    }
    public function getUnitsBySchool($schoolId)
    {
        try {
            $sql = "SELECT 
            COUNT(*) AS total
            FROM units_lnp
            WHERE units_lnp.school_id = :school_id
            ";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['school_id' => $schoolId]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result;
        } catch (Exception $e) {
            return false;
        }
    }
    public function getTopicsBySchool($schoolId)
    {
        try {
            $sql = "SELECT 
            COUNT(*) AS total
            FROM topics_lnp
            WHERE topics_lnp.school_id = :school_id
            ";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['school_id' => $schoolId]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result;
        } catch (Exception $e) {
            return false;
        }
    }
    public function getSubtopicsBySchool($schoolId)
    {
        try {
            $sql = "SELECT 
            COUNT(*) AS total
            FROM subtopics_lnp
            WHERE subtopics_lnp.school_id = :school_id
            ";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['school_id' => $schoolId]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result;
        } catch (Exception $e) {
            return false;
        }
    }
    public function getBooksBySchool($schoolId)
    {
        try {
            $sql = "SELECT 
            COUNT(*) AS total
            FROM audio_book_lnp
            WHERE audio_book_lnp.school_id = :school_id
            ";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['school_id' => $schoolId]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result;
        } catch (Exception $e) {
            return false;
        }
    }
    public function getContentsBySchool($schoolId)
    {
        try {
            $sql = "SELECT 
            COUNT(*) AS total
            FROM school_content_lnp
            WHERE school_content_lnp.school_id = :school_id
            ";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['school_id' => $schoolId]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result;
        } catch (Exception $e) {
            return false;
        }
    }
    public function getGamesBySchool($schoolId)
    {
        try {
            $sql = "SELECT 
            COUNT(*) AS total
            FROM games_lnp
            WHERE games_lnp.school_id = :school_id
            ";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['school_id' => $schoolId]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result;
        } catch (Exception $e) {
            return false;
        }
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
    public function getTimeSpentByStudents($schoolId)
    {
        $sql = "
            SELECT 
            SUM(ts.timeSpent) AS totalTime,
            u.*,
            s.name AS schoolName,
            c.name AS className
            FROM timespend_lnp ts

            LEFT JOIN users_lnp u ON ts.user_id = u.id
            LEFT JOIN schools_lnp s ON u.school_id = s.id
            LEFT JOIN classes_lnp c ON u.class_id = c.id

            WHERE u.school_id = :school_id AND (u.role = 2 OR u.role = 10002)
            GROUP BY ts.user_id 
            ORDER BY totalTime DESC
            LIMIT 5
        ";

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['school_id' => $schoolId]);
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $results;
        } catch (PDOException $e) {
            return null;
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

    public function getHighestAnalyticsOverall($schoolId)
    {
        $sql = "
        SELECT *
        FROM (
            SELECT 
                u.id AS student_id,
                u.name AS student_name,
                c.name AS className,
                c.slug AS classSlug,
                u.active AS userActive,
                s.name AS schoolName,
                
                -- Toplam içerik sayısı
                COUNT(DISTINCT sc.id) AS total_content_items,
                
                -- Videolar
                COUNT(DISTINCT scv.id) AS total_videos,
                SUM(CASE WHEN vd.duration > 0 AND vt.max_timestamp >= (vd.duration * 0.9) THEN 1 ELSE 0 END) AS completed_videos,
                
                -- Dosyalar
                COUNT(DISTINCT scf.id) AS total_files,
                SUM(CASE WHEN fd.user_id IS NOT NULL THEN 1 ELSE 0 END) AS downloaded_files,
                
                -- Wordwall
                COUNT(DISTINCT scw.id) AS total_wordwalls,
                SUM(CASE WHEN wv.user_id IS NOT NULL THEN 1 ELSE 0 END) AS viewed_wordwalls,
                
                -- Content visits
                SUM(CASE WHEN cv.user_id IS NOT NULL THEN 1 ELSE 0 END) AS content_visits,

                -- ana_score hesaplama
                CASE 
                    WHEN (COUNT(DISTINCT scv.id) + COUNT(DISTINCT scf.id) + COUNT(DISTINCT scw.id) + COUNT(DISTINCT sc.id)) > 0
                    THEN ROUND(
                        (
                            SUM(CASE WHEN vd.duration > 0 AND vt.max_timestamp >= (vd.duration * 0.9) THEN 1 ELSE 0 END)
                            + SUM(CASE WHEN fd.user_id IS NOT NULL THEN 1 ELSE 0 END)
                            + SUM(CASE WHEN wv.user_id IS NOT NULL THEN 1 ELSE 0 END)
                            + SUM(CASE WHEN cv.user_id IS NOT NULL THEN 1 ELSE 0 END)
                        ) * 100.0 /
                        (COUNT(DISTINCT scv.id) + COUNT(DISTINCT scf.id) + COUNT(DISTINCT scw.id) + COUNT(DISTINCT sc.id)),
                        3
                    )
                    ELSE 0
                END AS ana_score

            FROM users_lnp u
            INNER JOIN classes_lnp c ON u.class_id = c.id
            INNER JOIN schools_lnp s ON u.school_id = s.id
            LEFT JOIN school_content_lnp sc ON sc.active = 1
            LEFT JOIN content_visits cv ON sc.id = cv.content_id AND cv.user_id = u.id
            LEFT JOIN school_content_videos_url scv ON sc.id = scv.school_content_id
            LEFT JOIN video_durations vd ON scv.id = vd.video_id
            LEFT JOIN video_timestamp_lnp vt ON scv.id = vt.video_id AND vt.user_id = u.id
            LEFT JOIN school_content_files_lnp scf ON sc.id = scf.school_content_id
            LEFT JOIN file_downloads fd ON scf.id = fd.file_id AND fd.user_id = u.id
            LEFT JOIN school_content_wordwall_lnp scw ON sc.id = scw.school_content_id
            LEFT JOIN wordwall_views wv ON scw.id = wv.wordwall_id AND wv.user_id = u.id
            WHERE u.active = 1
            AND (u.role = ? OR u.role = ?)
            AND u.school_id = ?
            GROUP BY u.id, u.name, c.name, c.slug, u.active, s.name
        ) AS t
        ORDER BY t.ana_score DESC
        LIMIT 5
    ";

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute(["2", "10002", $schoolId]);
            $topStudents = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $topStudents;
        } catch (PDOException $e) {
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

    public function getPreschoolContentAnalyticsOverall($user_id, $class_id)
    {
        $sql = "
            SELECT 
                sc.id as content_id,
                sc.subject,
                sc.school_id,
                sc.week_id,
                sc.main_school_class_id,
                sc.lesson_id,
                sc.unit_id,
                sc.topic_id,
                sc.content_description,
                sc.status,
                
                CASE WHEN cv.user_id IS NOT NULL THEN 1 ELSE 0 END as content_visited,
                
                COUNT(DISTINCT sc.main_school_class_id) as total_videos,
                
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
                
                SUM(CASE WHEN wv.user_id IS NOT NULL THEN 1 ELSE 0 END) as viewed_wordwalls

            FROM main_school_content_lnp sc

            LEFT JOIN content_visits cv ON sc.id = cv.content_id AND cv.user_id = ?

            LEFT JOIN video_durations_preschool vd ON sc.id = vd.video_id
            LEFT JOIN video_timestamp_preschool_lnp vt ON sc.id = vt.video_id AND vt.user_id = ?

            LEFT JOIN mainschool_content_file_lnp scf ON sc.id = scf.main_id
            LEFT JOIN file_downloads fd ON scf.id = fd.file_id AND fd.user_id = ?

            LEFT JOIN school_content_wordwall_lnp scw ON sc.id = scw.school_content_id
            LEFT JOIN wordwall_views wv ON scw.id = wv.wordwall_id AND wv.user_id = ?

            LEFT JOIN topics_lnp ts ON sc.topic_id = ts.id AND sc.lesson_id = ts.lesson_id 

            WHERE sc.status = 1 AND sc.main_school_class_id = ?

            GROUP BY 
                sc.id, sc.subject, sc.school_id, sc.week_id, 
                sc.main_school_class_id, sc.lesson_id, sc.unit_id, sc.topic_id, 
                sc.content_description, sc.status, cv.user_id, ts.name

            ORDER BY sc.id ASC
        ";

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$user_id, $user_id, $user_id, $user_id, $class_id]);
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

    public function getPreschoolContentAnalyticsByLessonId($user_id, $class_id, $lesson_id)
    {
        $sql = "
            SELECT 
                sc.id as content_id,
                sc.subject,
                sc.school_id,
                sc.week_id,
                sc.main_school_class_id,
                sc.lesson_id,
                sc.unit_id,
                sc.topic_id,
                sc.content_description,
                sc.status,
                
                CASE WHEN cv.user_id IS NOT NULL THEN 1 ELSE 0 END as content_visited,
                
                COUNT(DISTINCT sc.main_school_class_id) as total_videos,
                
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
                
                SUM(CASE WHEN wv.user_id IS NOT NULL THEN 1 ELSE 0 END) as viewed_wordwalls

            FROM main_school_content_lnp sc

            LEFT JOIN content_visits cv ON sc.id = cv.content_id AND cv.user_id = ?

            LEFT JOIN video_durations_preschool vd ON sc.id = vd.video_id
            LEFT JOIN video_timestamp_preschool_lnp vt ON sc.id = vt.video_id AND vt.user_id = ?

            LEFT JOIN mainschool_content_file_lnp scf ON sc.id = scf.main_id
            LEFT JOIN file_downloads fd ON scf.id = fd.file_id AND fd.user_id = ?

            LEFT JOIN school_content_wordwall_lnp scw ON sc.id = scw.school_content_id
            LEFT JOIN wordwall_views wv ON scw.id = wv.wordwall_id AND wv.user_id = ?

            LEFT JOIN topics_lnp ts ON sc.topic_id = ts.id AND sc.lesson_id = ts.lesson_id 

            WHERE sc.status = 1 AND sc.main_school_class_id = ? AND sc.lesson_id = ? 

            GROUP BY 
                sc.id, sc.subject, sc.school_id, sc.week_id, 
                sc.main_school_class_id, sc.lesson_id, sc.unit_id, sc.topic_id, 
                sc.content_description, sc.status, cv.user_id, ts.name

            ORDER BY sc.id ASC
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

    public function getPreschoolContentAnalyticsByUnitId($user_id, $class_id, $lesson_id, $unit_id)
    {
        $sql = "
            SELECT 
                sc.id as content_id,
                sc.subject,
                sc.school_id,
                sc.week_id,
                sc.main_school_class_id,
                sc.lesson_id,
                sc.unit_id,
                sc.topic_id,
                sc.content_description,
                sc.status,
                
                CASE WHEN cv.user_id IS NOT NULL THEN 1 ELSE 0 END as content_visited,
                
                COUNT(DISTINCT sc.main_school_class_id) as total_videos,
                
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
                
                SUM(CASE WHEN wv.user_id IS NOT NULL THEN 1 ELSE 0 END) as viewed_wordwalls

            FROM main_school_content_lnp sc

            LEFT JOIN content_visits cv ON sc.id = cv.content_id AND cv.user_id = ?

            LEFT JOIN video_durations_preschool vd ON sc.id = vd.video_id
            LEFT JOIN video_timestamp_preschool_lnp vt ON sc.id = vt.video_id AND vt.user_id = ?

            LEFT JOIN mainschool_content_file_lnp scf ON sc.id = scf.main_id
            LEFT JOIN file_downloads fd ON scf.id = fd.file_id AND fd.user_id = ?

            LEFT JOIN school_content_wordwall_lnp scw ON sc.id = scw.school_content_id
            LEFT JOIN wordwall_views wv ON scw.id = wv.wordwall_id AND wv.user_id = ?

            LEFT JOIN topics_lnp ts ON sc.topic_id = ts.id AND sc.lesson_id = ts.lesson_id 

            WHERE sc.status = 1 AND sc.main_school_class_id = ? AND sc.lesson_id = ? AND sc.unit_id = ? 

            GROUP BY 
                sc.id, sc.subject, sc.school_id, sc.week_id, 
                sc.main_school_class_id, sc.lesson_id, sc.unit_id, sc.topic_id, 
                sc.content_description, sc.status, cv.user_id, ts.name

            ORDER BY sc.id ASC
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

    public function getPreschoolContentAnalyticsListByUserId($user_id, $class_id)
    {
        $sql = "
            SELECT 
                sc.id as content_id,
                sc.subject,
                sc.school_id,
                sc.week_id,
                sc.main_school_class_id,
                sc.lesson_id,
                sc.unit_id,
                sc.topic_id,
                sc.content_description,
                sc.status,
                
                CASE WHEN cv.user_id IS NOT NULL THEN 1 ELSE 0 END as content_visited,
                
                COUNT(DISTINCT sc.main_school_class_id) as total_videos,
                
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
                
                SUM(CASE WHEN wv.user_id IS NOT NULL THEN 1 ELSE 0 END) as viewed_wordwalls

            FROM main_school_content_lnp sc

            LEFT JOIN content_visits cv ON sc.id = cv.content_id AND cv.user_id = :user_id

            LEFT JOIN video_durations_preschool vd ON sc.id = vd.video_id
            LEFT JOIN video_timestamp_preschool_lnp vt ON sc.id = vt.video_id AND vt.user_id = :user_id

            LEFT JOIN mainschool_content_file_lnp scf ON sc.id = scf.main_id
            LEFT JOIN file_downloads fd ON scf.id = fd.file_id AND fd.user_id = :user_id

            LEFT JOIN school_content_wordwall_lnp scw ON sc.id = scw.school_content_id
            LEFT JOIN wordwall_views wv ON scw.id = wv.wordwall_id AND wv.user_id = :user_id

            LEFT JOIN topics_lnp ts ON sc.topic_id = ts.id AND sc.lesson_id = ts.lesson_id 
            
            WHERE sc.status = 1 AND sc.main_school_class_id = :class_id

            GROUP BY 
                sc.id, sc.subject, sc.school_id, sc.week_id, 
                sc.main_school_class_id, sc.lesson_id, sc.unit_id, sc.topic_id, 
                sc.content_description, sc.status, cv.user_id, ts.name

            ORDER BY sc.id ASC
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



    public function getUserActivityRecords($user_id, $class_id)
    {
        $classFilter = $class_id ? "AND sc.class_id = :class_id" : "";
        $sql = "
        SELECT 
            'content' as type,
            cv.id as activity_id,
            cv.user_id,
            cv.content_id as resource_id,
            cv.event_time,
            sc.title,
            sc.slug,
            
            sc.class_id,
            sc.lesson_id,
            sc.unit_id,
            sc.topic_id,
            sc.subtopic_id,
            
            CONCAT('/content/', sc.slug) as content_url,
            ts.name AS topic_name,
            sts.name AS subtopic_name,
            les.name AS lesson_name

        FROM content_visits cv
        LEFT JOIN school_content_lnp sc ON cv.content_id = sc.id
        LEFT JOIN topics_lnp ts ON sc.topic_id = ts.id AND sc.lesson_id = ts.lesson_id 
        LEFT JOIN subtopics_lnp sts ON sc.subtopic_id = sts.id AND sc.lesson_id = sts.lesson_id
        LEFT JOIN lessons_lnp les ON sc.lesson_id = les.id 

        WHERE cv.user_id = :user_id 
        AND sc.active = 1 
        AND sc.class_id = :class_id
        
        UNION ALL
        
        SELECT 
            'video' as type,
            vt.id as activity_id,
            vt.user_id,
            sc.id as resource_id,
            vt.event_time,
            sc.title,
            sc.slug,
            
            sc.class_id,
            sc.lesson_id,
            sc.unit_id,
            sc.topic_id,
            sc.subtopic_id,
            
            CONCAT('/content/', sc.slug) as content_url,
            ts.name AS topic_name,
            sts.name AS subtopic_name,
            les.name AS lesson_name

        FROM video_timestamp_lnp vt
        LEFT JOIN school_content_videos_url scv ON vt.video_id = scv.id
        LEFT JOIN school_content_lnp sc ON scv.school_content_id = sc.id
        LEFT JOIN video_durations vd ON vt.video_id = vd.video_id
        LEFT JOIN topics_lnp ts ON sc.topic_id = ts.id AND sc.lesson_id = ts.lesson_id 
        LEFT JOIN subtopics_lnp sts ON sc.subtopic_id = sts.id AND sc.lesson_id = sts.lesson_id
        LEFT JOIN lessons_lnp les ON sc.lesson_id = les.id 

        WHERE vt.user_id = :user_id 
        AND sc.active = 1 
        AND sc.class_id = :class_id
        
        UNION ALL
        
        SELECT 
            'file' as type,
            fd.id as activity_id,
            fd.user_id,
            sc.id as resource_id,
            fd.event_time,
            sc.title,
            sc.slug,
            
            sc.class_id,
            sc.lesson_id,
            sc.unit_id,
            sc.topic_id,
            sc.subtopic_id,
            
            CONCAT('/content/', sc.slug) as content_url,
            ts.name AS topic_name,
            sts.name AS subtopic_name,
            les.name AS lesson_name


        FROM file_downloads fd
        LEFT JOIN school_content_files_lnp scf ON fd.file_id = scf.id
        LEFT JOIN school_content_lnp sc ON scf.school_content_id = sc.id
        LEFT JOIN topics_lnp ts ON sc.topic_id = ts.id AND sc.lesson_id = ts.lesson_id 
        LEFT JOIN subtopics_lnp sts ON sc.subtopic_id = sts.id AND sc.lesson_id = sts.lesson_id
        LEFT JOIN lessons_lnp les ON sc.lesson_id = les.id 

        WHERE fd.user_id = :user_id 
        AND sc.active = 1 
        AND sc.class_id = :class_id
        
        UNION ALL
        
        SELECT 
            'wordwall' as type,
            wv.id as activity_id,
            wv.user_id,
            sc.id as resource_id,
            wv.event_time,
            sc.title,
            sc.slug,
            
            sc.class_id,
            sc.lesson_id,
            sc.unit_id,
            sc.topic_id,
            sc.subtopic_id,
            
            CONCAT('/content/', sc.slug) as content_url,
            ts.name AS topic_name,
            sts.name AS subtopic_name,
            les.name AS lesson_name

        FROM wordwall_views wv
        LEFT JOIN school_content_wordwall_lnp scw ON wv.wordwall_id = scw.id
        LEFT JOIN school_content_lnp sc ON scw.school_content_id = sc.id
        LEFT JOIN topics_lnp ts ON sc.topic_id = ts.id AND sc.lesson_id = ts.lesson_id 
        LEFT JOIN subtopics_lnp sts ON sc.subtopic_id = sts.id AND sc.lesson_id = sts.lesson_id
        LEFT JOIN lessons_lnp les ON sc.lesson_id = les.id 

        WHERE wv.user_id = :user_id 
        AND sc.active = 1 
        AND sc.class_id = :class_id
        
        ORDER BY event_time DESC, type, activity_id
    ";

        try {
            $stmt = $this->db->prepare($sql);
            $params = ['user_id' => $user_id];
            if ($class_id) {
                $params['class_id'] = $class_id;
            }
            $stmt->execute($params);
            $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // foreach ($items as $key => $item) {
            //     if ($item['type'] === 'video' && $item['duration'] > 0) {
            //         $items[$key]['completion_percentage'] = min(100, ($item['max_timestamp'] / $item['duration']) * 100);
            //         $items[$key]['is_completed'] = $item['max_timestamp'] >= ($item['duration'] * 0.9);
            //     }
            // }

            return $items;
        } catch (PDOException $e) {
            return null;
        }
    }

    public function getUserActivitySummary($user_id, $class_id)
    {
        $classFilter = $class_id ? "AND sc.class_id = :class_id" : "";
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
            sc.order_no,
            CONCAT('/content/', sc.slug) as content_url,
            ts.name AS topic_name,
            sts.name AS subtopic_name,
            les.name AS lesson_name
            CASE WHEN cv.user_id IS NOT NULL THEN 1 ELSE 0 END as content_visited,
            CASE WHEN EXISTS(SELECT 1 FROM video_timestamp_lnp vt2 
                           JOIN school_content_videos_url scv2 ON vt2.video_id = scv2.id 
                           WHERE scv2.school_content_id = sc.id AND vt2.user_id = :user_id) 
                 THEN 1 ELSE 0 END as has_video_activity,
            CASE WHEN EXISTS(SELECT 1 FROM file_downloads fd2 
                           JOIN school_content_files_lnp scf2 ON fd2.file_id = scf2.id 
                           WHERE scf2.school_content_id = sc.id AND fd2.user_id = :user_id) 
                 THEN 1 ELSE 0 END as has_file_downloads,
            CASE WHEN EXISTS(SELECT 1 FROM wordwall_views wv2 
                           JOIN school_content_wordwall_lnp scw2 ON wv2.wordwall_id = scw2.id 
                           WHERE scw2.school_content_id = sc.id AND wv2.user_id = :user_id) 
                 THEN 1 ELSE 0 END as has_wordwall_views,
            
            COUNT(DISTINCT scv.id) as total_videos,
            SUM(CASE WHEN vd.duration > 0 AND vt.max_timestamp >= (vd.duration * 0.9) THEN 1 ELSE 0 END) as completed_videos,
            COUNT(DISTINCT scf.id) as total_files,
            SUM(CASE WHEN fd.user_id IS NOT NULL THEN 1 ELSE 0 END) as downloaded_files,
            COUNT(DISTINCT scw.id) as total_wordwalls,
            SUM(CASE WHEN wv.user_id IS NOT NULL THEN 1 ELSE 0 END) as viewed_wordwalls,
            
            CONCAT_WS(',',
                CASE WHEN cv.user_id IS NOT NULL THEN 'content' END,
                CASE WHEN EXISTS(SELECT 1 FROM video_timestamp_lnp vt3 JOIN school_content_videos_url scv3 ON vt3.video_id = scv3.id WHERE scv3.school_content_id = sc.id AND vt3.user_id = :user_id) THEN 'video' END,
                CASE WHEN EXISTS(SELECT 1 FROM file_downloads fd3 JOIN school_content_files_lnp scf3 ON fd3.file_id = scf3.id WHERE scf3.school_content_id = sc.id AND fd3.user_id = :user_id) THEN 'file' END,
                CASE WHEN EXISTS(SELECT 1 FROM wordwall_views wv3 JOIN school_content_wordwall_lnp scw3 ON wv3.wordwall_id = scw3.id WHERE scw3.school_content_id = sc.id AND wv3.user_id = :user_id) THEN 'wordwall' END
            ) as activity_types
            
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
        LEFT JOIN lessons_lnp les ON sc.lesson_id = les.id 

        WHERE sc.active = 1 
        {$classFilter}
        AND (cv.user_id IS NOT NULL 
             OR EXISTS(SELECT 1 FROM video_timestamp_lnp vt4 JOIN school_content_videos_url scv4 ON vt4.video_id = scv4.id WHERE scv4.school_content_id = sc.id AND vt4.user_id = :user_id)
             OR EXISTS(SELECT 1 FROM file_downloads fd4 JOIN school_content_files_lnp scf4 ON fd4.file_id = scf4.id WHERE scf4.school_content_id = sc.id AND fd4.user_id = :user_id)
             OR EXISTS(SELECT 1 FROM wordwall_views wv4 JOIN school_content_wordwall_lnp scw4 ON wv4.wordwall_id = scw4.id WHERE scw4.school_content_id = sc.id AND wv4.user_id = :user_id))
        GROUP BY 
            sc.id, sc.slug, sc.title, sc.summary, sc.school_id, sc.teacher_id, 
            sc.class_id, sc.lesson_id, sc.unit_id, sc.topic_id, sc.subtopic_id, 
            sc.cover_img, sc.order_no, cv.user_id, ts.name, sts.name
        ORDER BY sc.order_no ASC, sc.id ASC
    ";

        try {
            $stmt = $this->db->prepare($sql);
            $params = ['user_id' => $user_id];
            if ($class_id) {
                $params['class_id'] = $class_id;
            }
            $stmt->execute($params);
            $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $items;
        } catch (PDOException $e) {
            return null;
        }
    }
}
