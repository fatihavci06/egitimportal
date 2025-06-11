<?php

if (session_status() == PHP_SESSION_NONE) {
    // Oturum henüz başlatılmamışsa başlat
    session_start();
}

class GetStudentHomework extends Dbh
{

    public function getAllHomeworkForStudent()
    {
        if ($_SESSION['role'] == 1) {
            $stmt = $this->connect()->prepare('SELECT homework_content_lnp.*, 
            classes_lnp.name AS className, 
            lessons_lnp.name AS lessonName, 
            units_lnp.name AS unitName, 
            topics_lnp.name AS topicName, 
            subtopics_lnp.name AS subTopicName
            FROM homework_content_lnp 
            LEFT JOIN classes_lnp ON homework_content_lnp.class_id = classes_lnp.id 
            LEFT JOIN lessons_lnp ON homework_content_lnp.lesson_id = lessons_lnp.id 
            LEFT JOIN units_lnp ON homework_content_lnp.unit_id = units_lnp.id 
            LEFT JOIN topics_lnp ON homework_content_lnp.topic_id = topics_lnp.id 
            LEFT JOIN subtopics_lnp ON homework_content_lnp.subtopic_id = subtopics_lnp.id 
            ORDER BY homework_content_lnp.id DESC');

            if (!$stmt->execute()) {
                $stmt = null;
                exit();
            }
        } elseif ($_SESSION['role'] == 2 or $_SESSION['role'] == 10002) {
            $class_id = $_SESSION['class_id'];
            $stmt = $this->connect()->prepare('SELECT homework_content_lnp.*, 
            classes_lnp.name AS className, 
            lessons_lnp.name AS lessonName, 
            units_lnp.name AS unitName, 
            topics_lnp.name AS topicName, 
            subtopics_lnp.name AS subTopicName
            FROM homework_content_lnp 
            LEFT JOIN classes_lnp ON homework_content_lnp.class_id = classes_lnp.id 
            LEFT JOIN lessons_lnp ON homework_content_lnp.lesson_id = lessons_lnp.id 
            LEFT JOIN units_lnp ON homework_content_lnp.unit_id = units_lnp.id 
            LEFT JOIN topics_lnp ON homework_content_lnp.topic_id = topics_lnp.id 
            LEFT JOIN subtopics_lnp ON homework_content_lnp.subtopic_id = subtopics_lnp.id 
            WHERE homework_content_lnp.class_id = ? 
            ORDER BY homework_content_lnp.id DESC');

            if (!$stmt->execute([$class_id])) {
                $stmt = null;
                exit();
            }
        }

        $homeworkContentData = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $homeworkContentData;

        $stmt = null;
    }

    // Content ID'sini slug'dan alıp çek

    public function getHomeworkContentIdBySlug($slug)
    {
        $stmt = $this->connect()->prepare('SELECT id FROM homework_content_lnp 
        WHERE homework_content_lnp.slug = ?');

        if (!$stmt->execute([$slug])) {
            $stmt = null;
            exit();
        }

        $contentData = $stmt->fetch(PDO::FETCH_ASSOC);

        return $contentData;

        $stmt = null;
    }
     // Homework ID'sine göre tüm içerikleri getirir

    public function getAllHomeworkContentDetailsById($id)
    {
        $stmt = $this->connect()->prepare('SELECT homework_content_lnp.*, 
        classes_lnp.name AS className, 
        lessons_lnp.name AS lessonName, 
        units_lnp.name AS unitName, 
        topics_lnp.name AS topicName, 
        subtopics_lnp.name AS subTopicName 
        FROM homework_content_lnp 
        LEFT JOIN classes_lnp ON homework_content_lnp.class_id = classes_lnp.id 
        LEFT JOIN lessons_lnp ON homework_content_lnp.lesson_id = lessons_lnp.id 
        LEFT JOIN units_lnp ON homework_content_lnp.unit_id = units_lnp.id 
        LEFT JOIN topics_lnp ON homework_content_lnp.topic_id = topics_lnp.id 
        LEFT JOIN subtopics_lnp ON homework_content_lnp.subtopic_id = subtopics_lnp.id 
        WHERE homework_content_lnp.id = ?');

        if (!$stmt->execute([$id])) {
            $stmt = null;
            exit();
        }

        $contentData = $stmt->fetch(PDO::FETCH_ASSOC);

        return $contentData;

        $stmt = null;
    }

    public function getHomeworkContentFilesById($id)
    {
        $stmt = $this->connect()->prepare('SELECT * FROM homework_content_files_lnp 
        WHERE homework_content_id = ?');

        if (!$stmt->execute([$id])) {
            $stmt = null;
            exit();
        }

        $contentData = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $contentData;

        $stmt = null;
    }

    public function getHomeworkContentWordwallsById($id)
    {
        $stmt = $this->connect()->prepare('SELECT * FROM homework_content_wordwall_lnp 
        WHERE homework_content_id = ?');

        if (!$stmt->execute([$id])) {
            $stmt = null;
            exit();
        }

        $contentData = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $contentData;

        $stmt = null;
    }

    public function getHomeworkContentVideosById($id)
    {
        $stmt = $this->connect()->prepare('SELECT * FROM homework_content_videos_url 
        WHERE homework_content_id = ?');

        if (!$stmt->execute([$id])) {
            $stmt = null;
            exit();
        }

        $contentData = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $contentData;

        $stmt = null;
    }

    //Vimeo linkinden iframe kodu oluşturma fonksiyonu


    public function generateVimeoIframe($vimeoUrl, $id, $video_timestamp)
    {
        // Vimeo linkinden video ID'sini ve varsa hash'i ayıklamak için bir düzenli ifade kullanalım.
        // Bu ifade hem "https://vimeo.com/VIDEO_ID" hem de "https://vimeo.com/VIDEO_ID/HASH" formatlarını yakalar.
        $pattern = '/vimeo\.com\/(\d+)(?:\/([a-zA-Z0-9]+))?/';
        preg_match($pattern, $vimeoUrl, $matches);


        if (isset($matches[1])) {
            $videoId = $matches[1];
            $hash = isset($matches[2]) ? $matches[2] : ''; // Hash varsa al, yoksa boş bırak

            $timestamp = $video_timestamp['timestamp_in_seconds'] ?? 0;

            // Iframe için temel embed URL'si
            $embedUrl = "https://player.vimeo.com/video/{$videoId}";

            // Hash varsa URL'ye ekle
            if (!empty($hash)) {
                $embedUrl .= "?h={$hash}";
            }
            if (!empty($hash)) {
                $embedUrl .= "#t={$timestamp}";
            }
            // Iframe HTML kodunu oluştur
            $iframeCode = '<iframe id="' . $id . '" src="' . htmlspecialchars($embedUrl) . '" width="100%" height="600" frameborder="0" allow="autoplay; fullscreen; picture-in-picture"></iframe>';
            // 
            // Opsiyonel: Videonun başlığını da ekleyebilirsiniz (aşağıdaki p etiketi gibi)
            // $iframeCode .= '<p><a href="' . htmlspecialchars($vimeoUrl) . '">Vimeo\'da izle</a>.</p>';

            return $iframeCode;
        } else {
            return "Geçersiz Vimeo linki.";
        }
    }
}
