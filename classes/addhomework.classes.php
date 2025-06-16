<?php

if (session_status() == PHP_SESSION_NONE) {
    // Oturum henüz başlatılmamışsa başlat
    session_start();
}

class AddHomework extends Dbh
{

    protected function setHomeworkContent($imgName, $slug, $name, $classes, $lessons, $units, $short_desc, $topics, $sub_topics, $text_content, $video_url, $file_urls, $imageFiles, $descriptions, $titles, $urls, $start_date, $end_date)
    {
        $school_id = $_SESSION['school_id'];
        $teacher_id = $_SESSION['teacher_id'];

        $pdo = $this->connect();

        $pdo->beginTransaction();

        try {

            $stmt = $pdo->prepare('INSERT INTO homework_content_lnp SET 
             slug=?,
             title=?,
             summary=?,
             school_id = ?,
             teacher_id = ?,
             class_id=?, 
             lesson_id=?, 
             unit_id=?, 
             topic_id=?, 
             subtopic_id=?, 
             cover_img=?, 
             text_content=?, 
             start_date = ?, 
             end_date = ?');

            if (!$stmt->execute([
                $slug,
                $name,
                $short_desc,
                $school_id,
                $teacher_id,
                $classes,
                $lessons,
                $units,
                $topics,
                $sub_topics,
                $imgName,
                $text_content,
                $start_date,
                $end_date
            ])) {
                $stmt = null;
                //header("location: ../admin.php?error=stmtfailed");
                exit();
            }

            $mainId = $pdo->lastInsertId();

            if (!empty($video_url)) {

                $stmtFile = $pdo->prepare("INSERT INTO homework_content_videos_url 
                SET homework_content_id=?, 
                video_url=?");


                if (!$stmtFile->execute([$mainId, $video_url])) {
                    $stmtFile = null;
                    //header("location: ../admin.php?error=stmtfailed");
                    exit();
                }
            }

            // Eğer dosya yüklendiyse ayrı tabloya kaydet
            if (!empty($file_urls)) {

                //$descriptions = $_POST['descriptions'] ?? null;


                foreach ($file_urls as $index => $url) {
                    $description = isset($descriptions[$index]) ? $descriptions[$index] : '';
                    $stmtFile = $pdo->prepare("INSERT INTO homework_content_files_lnp (homework_content_id, file_path, description) VALUES (:homework_content_id, :file_path, :description)");
                    $stmtFile->execute([
                        ':homework_content_id' => $mainId,
                        ':file_path' => $url,
                        ':description' => $description
                    ]);
                }
            }

            if (isset($titles) && isset($urls)) {

                /* $titles = $_POST['wordWallTitles'] ?? null; // ['Başlık1', 'Başlık2', ...]
									$urls = $_POST['wordWallUrls'] ?? null;     // ['url1', 'url2', ...] */

                // Temel güvenlik filtresi
                $titles = array_map('strip_tags', $titles);
                $urls = array_map('strip_tags', $urls);

                // Kayıt işlemi (örneğin veritabanına veya dosyaya yazabilirsiniz)
                for ($i = 0; $i < count($titles); $i++) {
                    $title = trim($titles[$i]);
                    $url = trim($urls[$i]);

                    if ($title !== '' && $url !== '') {
                        $stmtFile = $pdo->prepare("INSERT INTO homework_content_wordwall_lnp (homework_content_id, wordwall_title,wordwall_url) VALUES (:homework_content_id, :wordwall_title ,:wordwall_url)");
                        $stmtFile->execute([
                            ':homework_content_id' => $mainId,
                            ':wordwall_title' => $title,
                            ':wordwall_url' => $url
                        ]);
                    }
                }
            }


            $pdo->commit(); // Tüm işlemler başarılıysa commit et

            echo json_encode(["status" => "success", "message" => $name]);
            $pdo = null;
        } catch (\Exception $e) {
            $pdo->rollback(); // Bir hata oluşursa tüm işlemleri geri al
            echo json_encode(["status" => "error", "message" => $e->getMessage()]);
            $pdo = null; // PDO bağlantısını kapat
        } finally {
            $pdo = null;
        }
    }


    public function checkSlug($slug)
    {
        $stmt = $this->connect()->prepare('SELECT slug FROM homework_content_lnp WHERE slug LIKE ? OR slug = ? ORDER BY slug ASC');

        if (!$stmt->execute([$slug . '-%', $slug])) {
            $stmt = null;
            header("location: ../admin.php?error=stmtfailed");
            exit();
        }
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt = null;

        return $result;
    }
}

class GetHomework extends Dbh
{

    public function getAllHomeworkContents()
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
        } elseif ($_SESSION['role'] == 3) {
            $school = $_SESSION['school_id'];
            $stmt = $this->connect()->prepare('SELECT 
            homework_content_lnp.*, 
            classes_lnp.name AS className, 
            lessons_lnp.name AS lessonName, 
            units_lnp.name AS unitName, 
            topics_lnp.name AS topicName, 
            subtopics_lnp.name AS subTopicName
            FROM homework_content_lnp LEFT JOIN classes_lnp 
            ON homework_content_lnp.class_id = classes_lnp.id 
            LEFT JOIN lessons_lnp ON homework_content_lnp.lesson_id = lessons_lnp.id 
            LEFT JOIN units_lnp ON homework_content_lnp.unit_id = units_lnp.id 
            LEFT JOIN topics_lnp ON homework_content_lnp.topic_id = topics_lnp.id 
            LEFT JOIN subtopics_lnp ON homework_content_lnp.subtopic_id = subtopics_lnp.id 
            WHERE homework_content_lnp.school_id = ? ORDER BY homework_content_lnp.id DESC');

            if (!$stmt->execute([$school])) {
                $stmt = null;
                exit();
            }
        } elseif ($_SESSION['role'] == 4) {
            $school = $_SESSION['school_id'];
            $class_id = $_SESSION['class_id'];
            $lesson_id = $_SESSION['lesson_id'];
            $teacher_id = $_SESSION['teacher_id'];
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
            WHERE homework_content_lnp.school_id = ? 
            AND homework_content_lnp.class_id = ? 
            AND homework_content_lnp.lesson_id = ? 
            AND homework_content_lnp.teacher_id = ? 
            ORDER BY homework_content_lnp.id DESC');

            if (!$stmt->execute([$school, $class_id, $lesson_id, $teacher_id])) {
                $stmt = null;
                exit();
            }
        }

        $homeworkContentData = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $homeworkContentData;

        $stmt = null;
    }

    // Homework ID'sini slug'dan alıp çek

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

    public function getHomeworkContentInfoByIds($topicId, $unitId, $lessonId, $classId)
    {
        $stmt = $this->connect()->prepare('SELECT * FROM school_content_lnp 
        WHERE topic_id = ? 
        AND unit_id = ? 
        AND lesson_id = ? 
        AND class_id = ? 
        AND subtopic_id = ?');

        if (!$stmt->execute([$topicId, $unitId, $lessonId, $classId, 0])) {
            $stmt = null;
            exit();
        }

        $contentData = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $contentData;

        $stmt = null;
    }

    public function getHomeworkContentInfoByIdsUnderSubTopic($subTopicId, $topicId, $unitId, $lessonId, $classId)
    {
        $stmt = $this->connect()->prepare('SELECT * FROM school_content_lnp 
        WHERE subtopic_id = ? 
        AND unit_id = ? 
        AND lesson_id = ? 
        AND class_id = ? 
        AND topic_id = ?');

        if (!$stmt->execute([$subTopicId, $unitId, $lessonId, $classId, $topicId])) {
            $stmt = null;
            exit();
        }

        $contentData = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $contentData;

        $stmt = null;
    }

    // protected function setHomeworkContent($imgName, $slug, $name, $classes, $lessons, $units, $short_desc, $topics, $sub_topics, $content, $video_url, $file_urls, $imageFiles, $descriptions, $titles, $urls)
    // {
    //     $pdo = $this->connect();

    //     $pdo->beginTransaction(); // İşlemleri başlat

    //     try {

    //         $stmt = $pdo->prepare('INSERT INTO school_content_lnp SET slug=?, title=?, summary=?, class_id=?, lesson_id=?, unit_id=?, topic_id=?, subtopic_id=?, cover_img=?, text_content=?');

    //         if (!$stmt->execute([$slug, $name, $short_desc, $classes, $lessons, $units, $topics, $sub_topics, $imgName, $content])) {
    //             $stmt = null;
    //             //header("location: ../admin.php?error=stmtfailed");
    //             exit();
    //         }

    //         $mainId = $pdo->lastInsertId();

    //         if (!empty($video_url)) {

    //             $stmtFile = $pdo->prepare("INSERT INTO school_content_videos_url SET school_content_id=?, video_url=?");


    //             if (!$stmtFile->execute([$mainId, $video_url])) {
    //                 $stmtFile = null;
    //                 //header("location: ../admin.php?error=stmtfailed");
    //                 exit();
    //             }
    //         }

    //         // Eğer dosya yüklendiyse ayrı tabloya kaydet
    //         if (!empty($file_urls)) {

    //             //$descriptions = $_POST['descriptions'] ?? null;


    //             foreach ($file_urls as $index => $url) {
    //                 $description = isset($descriptions[$index]) ? $descriptions[$index] : '';
    //                 $stmtFile = $pdo->prepare("INSERT INTO school_content_files_lnp (school_content_id, file_path, description) VALUES (:school_content_id, :file_path, :description)");
    //                 $stmtFile->execute([
    //                     ':school_content_id' => $mainId,
    //                     ':file_path' => $url,
    //                     ':description' => $description
    //                 ]);
    //             }
    //         }

    //         if (isset($titles) && isset($urls)) {

    //             /* $titles = $_POST['wordWallTitles'] ?? null; // ['Başlık1', 'Başlık2', ...]
    // 								$urls = $_POST['wordWallUrls'] ?? null;     // ['url1', 'url2', ...] */

    //             // Temel güvenlik filtresi
    //             $titles = array_map('strip_tags', $titles);
    //             $urls = array_map('strip_tags', $urls);

    //             // Kayıt işlemi (örneğin veritabanına veya dosyaya yazabilirsiniz)
    //             for ($i = 0; $i < count($titles); $i++) {
    //                 $title = trim($titles[$i]);
    //                 $url = trim($urls[$i]);

    //                 if ($title !== '' && $url !== '') {
    //                     $stmtFile = $pdo->prepare("INSERT INTO school_content_wordwall_lnp (school_content_id, wordwall_title,wordwall_url) VALUES (:school_content_id, :wordwall_title ,:wordwall_url)");
    //                     $stmtFile->execute([
    //                         ':school_content_id' => $mainId,
    //                         ':wordwall_title' => $title,
    //                         ':wordwall_url' => $url
    //                     ]);
    //                 }
    //             }
    //         }


    //         $pdo->commit(); // Tüm işlemler başarılıysa commit et

    //         echo json_encode(["status" => "success", "message" => $name]);
    //         $pdo = null;
    //     } catch (\Exception $e) {
    //         $pdo->rollback(); // Bir hata oluşursa tüm işlemleri geri al
    //         echo json_encode(["status" => "error", "message" => "Bir hata oluştu"]);
    //         $pdo = null; // PDO bağlantısını kapat
    //     } finally {
    //         $pdo = null;
    //     }
    // }


    // public function checkSlug($slug)
    // {
    //     $stmt = $this->connect()->prepare('SELECT slug FROM school_content_lnp WHERE slug LIKE ? OR slug = ? ORDER BY slug ASC');

    //     if (!$stmt->execute([$slug . '-%', $slug])) {
    //         $stmt = null;
    //         header("location: ../admin.php?error=stmtfailed");
    //         exit();
    //     }
    //     $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    //     $stmt = null;

    //     return $result;
    // }
}
