<?php 

class ContentTrackerMainSchool
{

    private $db;
    public function __construct()
    {
        require_once 'dbh.classes.php';

        $this->db = (new dbh())->connect();
    }


        public function getUserActivityRecords($user_id, $class_id )
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
            sts.name AS subtopic_name

        FROM content_visits cv
        LEFT JOIN school_content_lnp sc ON cv.content_id = sc.id
        LEFT JOIN topics_lnp ts ON sc.topic_id = ts.id AND sc.lesson_id = ts.lesson_id 
        LEFT JOIN subtopics_lnp sts ON sc.subtopic_id = sts.id AND sc.lesson_id = sts.lesson_id
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
            sts.name AS subtopic_name

        FROM video_timestamp_lnp vt
        LEFT JOIN school_content_videos_url scv ON vt.video_id = scv.id
        LEFT JOIN school_content_lnp sc ON scv.school_content_id = sc.id
        LEFT JOIN video_durations vd ON vt.video_id = vd.video_id
        LEFT JOIN topics_lnp ts ON sc.topic_id = ts.id AND sc.lesson_id = ts.lesson_id 
        LEFT JOIN subtopics_lnp sts ON sc.subtopic_id = sts.id AND sc.lesson_id = sts.lesson_id
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
            sts.name AS subtopic_name

        FROM file_downloads fd
        LEFT JOIN school_content_files_lnp scf ON fd.file_id = scf.id
        LEFT JOIN school_content_lnp sc ON scf.school_content_id = sc.id
        LEFT JOIN topics_lnp ts ON sc.topic_id = ts.id AND sc.lesson_id = ts.lesson_id 
        LEFT JOIN subtopics_lnp sts ON sc.subtopic_id = sts.id AND sc.lesson_id = sts.lesson_id
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
            sts.name AS subtopic_name

        FROM wordwall_views wv
        LEFT JOIN school_content_wordwall_lnp scw ON wv.wordwall_id = scw.id
        LEFT JOIN school_content_lnp sc ON scw.school_content_id = sc.id
        LEFT JOIN topics_lnp ts ON sc.topic_id = ts.id AND sc.lesson_id = ts.lesson_id 
        LEFT JOIN subtopics_lnp sts ON sc.subtopic_id = sts.id AND sc.lesson_id = sts.lesson_id
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

}