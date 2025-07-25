<?php
header('Content-Type: application/json');

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    require_once '../classes/dbh.classes.php';


    $classId = (int) $_POST['classes'];
    $lessonId = (int) $_POST['lessons'];
    $unitId = (int) $_POST['units'];
    $topicId = (int) $_POST['topics'];
    $subtopicId = (int) $_POST['subtopics'];
    $schoolId = (int) $_POST['school_id'];


    // var_dump($subtopicId); 
    // exit();
    // return $subtopicId;

    function respondWithHtml($html)
    {
        echo json_encode(['status' => 'success', 'html' => $html]);
        die();
    }
    function checkIfNotZeroOrNull(...$args)
    {
        foreach ($args as $arg) {
            if (is_null($arg) || $arg == 0) {
                return false;
            }
        }
        return true;
    }
    if (checkIfNotZeroOrNull($classId, $lessonId, $unitId, $topicId, $subtopicId)) {
        respondWithHtml(doSubtopics($schoolId, $classId, $lessonId, $unitId, $topicId, $subtopicId));
    }

    if (checkIfNotZeroOrNull($classId, $lessonId, $unitId, $topicId)) {
        respondWithHtml(doTopics($schoolId, $classId, $lessonId, $unitId, $topicId, $subtopicId));
    }

    if (checkIfNotZeroOrNull($classId, $lessonId, $unitId)) {
        respondWithHtml(doUnits($schoolId, $classId, $lessonId, $unitId, $topicId));
    }

    if (checkIfNotZeroOrNull($classId, $lessonId)) {
        respondWithHtml(doLessons($schoolId, $classId, $lessonId, $unitId));
    }


    echo json_encode([$classId, $lessonId, $unitId, $topicId, $subtopicId]);
    // echo json_encode(['status' => 'fail', 'message' => 'Invalid request method']);
    exit;

}

function doLessons($school_id, $class_id, $lesson_id, $unit_id)
{
    $itemModel = getLessonById($lesson_id);
    $items = getUnitsByLessonId($school_id, $class_id, $lesson_id);
    $itemsCount = count($items) ?? 0;

    $styles = ["danger", "success", "primary", "warning", "info", "secondary", "light", "dark"];
    $styleIndex = 0;
    $view = '';


    $style = $styles[$styleIndex % count($styles)];



    foreach ($items as $item) {


        $subItems = getTopicsByUnitId($school_id, $class_id, $lesson_id, $unit_id);
        $subItemsCount = 0;
        $subItemsCount = count($subItems);

        $view .= '<div class="d-flex flex-stack">
                            <div class="symbol symbol-40px me-4">
                                <div class="symbol-label fs-2 fw-semibold bg-' . $style . ' text-inverse-' . $style . '">' . mb_substr($item['name'], 0, 1, 'UTF-8') . '</div>
                            </div>
                            <div class="d-flex align-items-center flex-row-fluid ">
                                <div class="flex-grow-1 me-2">
                                    <a class="text-gray-800 text-hover-primary fs-6 fw-bold">' . $item['name'] . '</a>
                                    <span class="text-muted fw-semibold d-block fs-7">' . $subItemsCount . ' Konu</span>
                                </div>
                            </div>
                        </div>
                        <div class="separator separator-dashed my-4"></div>';
    }

    $FinalView = '
                        <div class="card mb-5 mb-xl-8">
                            <div class="card-header border-0 pt-5">
                                <h3 class="card-title align-items-start flex-column">
                                    <span class="card-label fw-bold text-gray-900">' . $itemModel['name'] . '</span>
                                    
                                    <span class="text-muted fw-semibold d-block fs-7">' . $itemsCount . ' Ünite</span>
                                </h3>
                            </div>
                            <div class="card-body pt-6">
                                ' . $view . '
                            </div>
                        </div>
              ';


    $styleIndex++;

    return $FinalView;



}

function doUnits($school_id, $class_id, $lesson_id, $unit_id, $topic_id)
{
    $itemModel = getUnitById($unit_id);
    $items = getTopicsByUnitId($school_id, $class_id, $lesson_id, $unit_id);
    $itemsCount = count($items) ?? 0;

    $styles = ["danger", "success", "primary", "warning", "info", "secondary", "light", "dark"];
    $styleIndex = 0;
    $view = '';

    $style = $styles[$styleIndex % count($styles)];

    foreach ($items as $item) {


        $subItems = getSubtopicsByTopicId($school_id, $class_id, $lesson_id, $unit_id, $topic_id);
        $subItemsCount = 0;
        $subItemsCount = count($subItems);


        $view .= '<div class="d-flex flex-stack">
                            <div class="symbol symbol-40px me-4">
                                <div class="symbol-label fs-2 fw-semibold bg-' . $style . ' text-inverse-' . $style . '">' . mb_substr($item['name'], 0, 1, 'UTF-8') . '</div>
                            </div>
                            <div class="d-flex align-items-center flex-row-fluid ">
                                <div class="flex-grow-1 me-2">
                                    <a  class="text-gray-800 text-hover-primary fs-6 fw-bold">' . $item['name'] . '</a>
                                    <span class="text-muted fw-semibold d-block fs-7">' . $subItemsCount . ' Konu</span>
                                </div>
                            </div>
                        </div>
                        <div class="separator separator-dashed my-4"></div>';
    }

    $FinalView = '
                        <div class="card mb-5 mb-xl-8">
                            <div class="card-header border-0 pt-5">
                                <h3 class="card-title align-items-start flex-column">
                                    <span class="card-label fw-bold text-gray-900">' . $itemModel['name'] . '</span>
                                    
                                    <span class="text-muted fw-semibold d-block fs-7">' . $itemsCount . ' Ünite</span>
                                </h3>
                            </div>
                            <div class="card-body pt-6">
                                ' . $view . '
                            </div>
                        </div>
             ';


    $styleIndex++;

    return $FinalView;



}

function doTopics($school_id, $class_id, $lesson_id, $unit_id, $topic_id, $subtopic_id)
{
    $itemModel = getTopicById($topic_id);
    $items = getSubtopicsByTopicId($school_id, $class_id, $lesson_id, $unit_id, $topic_id);
    $itemsCount = count($items) ?? 0;

    $styles = ["danger", "success", "primary", "warning", "info", "secondary", "light", "dark"];
    $styleIndex = 0;
    $view = '';

    $style = $styles[$styleIndex % count($styles)];

    foreach ($items as $item) {


        // $subItems = getSubtopicsByTopicId($school_id, $class_id, $lesson_id, $unit_id, $topic_id);
        // $subItemsCount = 0;
        // $subItemsCount = count($subItems);


        $view .= '<div class="d-flex flex-stack">
                            <div class="symbol symbol-40px me-4">
                                <div class="symbol-label fs-2 fw-semibold bg-' . $style . ' text-inverse-' . $style . '">' . mb_substr($item['name'], 0, 1, 'UTF-8') . '</div>
                            </div>
                            <div class="d-flex align-items-center flex-row-fluid ">
                                <div class="flex-grow-1 me-2">
                                    <a  class="text-gray-800 text-hover-primary fs-6 fw-bold">' . $item['name'] . '</a>
                                    
                                </div>
                            </div>
                        </div>
                        <div class="separator separator-dashed my-4"></div>';
    }

    $FinalView = '
                        <div class="card mb-5 mb-xl-8">
                            <div class="card-header border-0 pt-5">
                                <h3 class="card-title align-items-start flex-column">
                                    <span class="card-label fw-bold text-gray-900">' . $itemModel['name'] . '</span>
                                    
                                    <span class="text-muted fw-semibold d-block fs-7">' . $itemsCount . ' Konu</span>
                                </h3>
                            </div>
                            <div class="card-body pt-6">
                                ' . $view . '
                            </div>

                    ';

    $styleIndex++;

    return $FinalView;
}
function doSubtopics($school_id, $class_id, $lesson_id, $unit_id, $topic_id, $subtopic_id)
{
    $itemModel = getSubtopicById($subtopic_id);

    // $items = getSubtopicsByTopicId($school_id, $class_id, $lesson_id, $unit_id, $topic_id);
    // $itemsCount = count($items)??0;

    $styles = ["danger", "success", "primary", "warning", "info", "secondary", "light", "dark"];
    $styleIndex = 0;
    $view = '';

    // $itemModel = $itemModel[0];

    $style = $styles[$styleIndex % count($styles)];

    // foreach ($items as $item) {


    $view .= '<div class="d-flex flex-stack">
                            <div class="symbol symbol-40px me-4">
                                <div class="symbol-label fs-2 fw-semibold bg-' . $style . ' text-inverse-' . $style . '">' . mb_substr($itemModel['name'], 0, 1, 'UTF-8') . '</div>
                            </div>
                            <div class="d-flex align-items-center flex-row-fluid ">
                                <div class="flex-grow-1 me-2">
                                    <a  class="text-gray-800 text-hover-primary fs-6 fw-bold">' . $itemModel['name'] . '</a>
                                </div>
                            </div>
                        </div>
                        <div class="separator separator-dashed my-4"></div>';
    // }

    $FinalView = '
                        <div class="card mb-5 mb-xl-8">
                            <div class="card-header border-0 pt-5">
                                <h3 class="card-title align-items-start flex-column">
                                    <span class="card-label fw-bold text-gray-900">' . $itemModel['name'] . '</span>                                    
                                </h3>
                            </div>
                            <div class="card-body pt-6">
                                ' . $view . '
                            </div>
                        </div>
                    </div>';

    $styleIndex++;

    return $FinalView;
}

function getLessonById($id)
{
    $db = (new dbh())->connect();
    $sql = 'SELECT * FROM lessons_lnp WHERE id=?';

    try {
        $stmt = $db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);

    } catch (PDOException $e) {
        return false;
    }


}
function getUnitById($id)
{
    $db = (new dbh())->connect();
    $sql = 'SELECT * FROM units_lnp WHERE id=?';

    try {
        $stmt = $db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);

    } catch (PDOException $e) {
        return false;
    }
}
function getTopicById($id)
{
    $db = (new dbh())->connect();
    $sql = 'SELECT * FROM topics_lnp WHERE id=?';

    try {
        $stmt = $db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);

    } catch (PDOException $e) {
        return false;
    }
}
function getSubtopicById($id)
{
    $db = (new dbh())->connect();
    $sql = 'SELECT * FROM subtopics_lnp WHERE id=?';

    try {
        $stmt = $db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);

    } catch (PDOException $e) {
        return false;
    }
}
// _______

function getUnitsByLessonId($school_id, $class_id, $lesson_id)
{
    $db = (new dbh())->connect();
    $sql = 'SELECT * FROM units_lnp WHERE school_id=? AND class_id=? AND lesson_id=?';

    try {
        $stmt = $db->prepare($sql);
        $stmt->execute([$school_id, $class_id, $lesson_id]);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;

    } catch (PDOException $e) {
        return false;
    }
}
function getTopicsByUnitId($school_id, $class_id, $lesson_id, $unit_id)
{
    $db = (new dbh())->connect();
    $sql = 'SELECT * FROM topics_lnp WHERE school_id=? AND class_id=? AND lesson_id=? AND unit_id=?';

    try {
        $stmt = $db->prepare($sql);
        $stmt->execute([$school_id, $class_id, $lesson_id, $unit_id]);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    } catch (PDOException $e) {
        return false;
    }
}
function getSubtopicsByTopicId($school_id, $class_id, $lesson_id, $unit_id, $topic_id)
{
    $db = (new dbh())->connect();
    $sql = 'SELECT * FROM subtopics_lnp WHERE school_id=? AND class_id=? AND lesson_id=? AND unit_id=? AND topic_id=?';

    try {
        $stmt = $db->prepare($sql);
        $stmt->execute([$school_id, $class_id, $lesson_id, $unit_id, $topic_id]);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;

    } catch (PDOException $e) {
        return false;
    }
}
