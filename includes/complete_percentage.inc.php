<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    require_once '../classes/dbh.classes.php';

    $class_id = (int) $_POST['classes'];
    $lesson_id = (int) $_POST['lessons'];
    $unit_id = (int) $_POST['units'];
    $topic_id = (int) $_POST['topics'];
    $subtopic_id = (int) $_POST['subtopics'];

    $student_id = (int) $_POST['student_id'];
    $school_id = (int) $_POST['school_id'];


    if ($subtopic_id != null) {
        $result = doSubtopics($school_id, $class_id, $lesson_id, $unit_id, $topic_id, $subtopic_id, $student_id);
        // $result = getSubtopicById($subtopic_id);
        echo json_encode(['status' => 'success', 'html' => $result]);
        exit;
    }
    if ($topic_id != null) {
        $result = doTopics($school_id, $class_id, $lesson_id, $unit_id, $topic_id, $student_id);
        echo json_encode(['status' => 'success', 'html' => $result]);
        exit;
    }
    if ($unit_id != null) {
        $result = doUnits($school_id, $class_id, $lesson_id, $unit_id, $student_id);
        echo json_encode(['status' => 'success', 'html' => $result]);
        exit;
    }
    if ($lesson_id != null) {
        $result = doLessons($school_id, $class_id, $lesson_id, $student_id);
        echo json_encode(['status' => 'success', 'html' => $result]);
        exit;
    }

    echo json_encode(['status' => 'fail', 'message' => 'Invalid request method']);
    exit;

}

function doLessons($school_id, $class_id, $lesson_id, $student_id)
{
    $itemModel = getLessonById($lesson_id);
    $items = getUnitsByLessonId($school_id, $class_id, $lesson_id);
    $itemsCount = count($items);

    $styles = ["danger", "success", "primary", "warning", "info", "secondary", "light", "dark"];
    $styleIndex = 0;
    $view = '';

    require_once "../classes/content-tracker.classes.php";

    $contentObj = new ContentTracker();


    $style = $styles[$styleIndex % count($styles)];



    foreach ($items as $item) {


        $subItems = getTopicsByUnitId($school_id, $class_id, $lesson_id, $unit_id);
        $subItemsCount = 0;
        $subItemsCount = count($subItems);
        $percentage = $contentObj->getSchoolContentAnalyticsByUnitId($student_id, $item['id']);

        $view .= '<div class="d-flex flex-stack">
                            <div class="symbol symbol-40px me-4">
                                <div class="symbol-label fs-2 fw-semibold bg-' . $style . ' text-inverse-' . $style . '">' . mb_substr($item['name'], 0, 1, 'UTF-8') . '</div>
                            </div>
                            <div class="d-flex align-items-center flex-row-fluid ">
                                <div class="flex-grow-1 me-2">
                                    <a href="' . $student_id . '" class="text-gray-800 text-hover-primary fs-6 fw-bold">' . $item['name'] . '</a>
                                    <span class="text-muted fw-semibold d-block fs-7">' . $subItemsCount . ' Konu</span>
                                </div>
                                <div class="d-flex align-items-center w-100px w-sm-200px flex-column mt-3">
                                    <div class="d-flex justify-content-between w-100 mt-auto mb-2">
                                        <span class="fw-semibold fs-6 text-gray-500">Tamamlama Oranı</span>
                                        <span class="fw-bold fs-6">' . $percentage . '%</span>
                                    </div>
                                    <div class="h-5px mx-3 w-100 bg-light mb-3">  
                                        <div class="bg-success rounded h-5px" role="progressbar" style="width: ' . $percentage . '%;" aria-valuenow="25"
                                            aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="separator separator-dashed my-4"></div>';
    }

    $FinalView = '<div class="col-xl-6">
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
                    </div>';


    $styleIndex++;

    return $FinalView;



}

function doUnits($school_id, $class_id, $lesson_id, $unit_id, $student_id)
{
    $itemModel = getUnitById($unit_id);
    $items = getTopicsByUnitId($school_id, $class_id, $lesson_id, $unit_id);
    $itemsCount = count($items);

    $styles = ["danger", "success", "primary", "warning", "info", "secondary", "light", "dark"];
    $styleIndex = 0;
    $view = '';

    require_once "../classes/content-tracker.classes.php";

    $contentObj = new ContentTracker();

    $style = $styles[$styleIndex % count($styles)];

    foreach ($items as $item) {


        $subItems = getSubtopicsByTopicId($school_id, $class_id, $lesson_id, $unit_id, $topic_id);
        $subItemsCount = 0;
        $subItemsCount = count($subItems);
        $percentage = $contentObj->getSchoolContentAnalyticsByTopicId($student_id, $item['id']);

        $view .= '<div class="d-flex flex-stack">
                            <div class="symbol symbol-40px me-4">
                                <div class="symbol-label fs-2 fw-semibold bg-' . $style . ' text-inverse-' . $style . '">' . mb_substr($item['name'], 0, 1, 'UTF-8') . '</div>
                            </div>
                            <div class="d-flex align-items-center flex-row-fluid ">
                                <div class="flex-grow-1 me-2">
                                    <a href="' . $student_id . '" class="text-gray-800 text-hover-primary fs-6 fw-bold">' . $item['name'] . '</a>
                                    <span class="text-muted fw-semibold d-block fs-7">' . $subItemsCount . ' Konu</span>
                                </div>
                                <div class="d-flex align-items-center w-100px w-sm-200px flex-column mt-3">
                                    <div class="d-flex justify-content-between w-100 mt-auto mb-2">
                                        <span class="fw-semibold fs-6 text-gray-500">Tamamlama Oranı</span>
                                        <span class="fw-bold fs-6">' . $percentage . '%</span>
                                    </div>
                                    <div class="h-5px mx-3 w-100 bg-light mb-3">  
                                        <div class="bg-success rounded h-5px" role="progressbar" style="width: ' . $percentage . '%;" aria-valuenow="25"
                                            aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="separator separator-dashed my-4"></div>';
    }

    $FinalView = '<div class="col-xl-6">
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
                    </div>';


    $styleIndex++;

    return $FinalView;



}

function doTopics($school_id, $class_id, $lesson_id, $unit_id, $topic_id, $student_id)
{
    $itemModel = getTopicById($topic_id);
    $items = getSubtopicsByTopicId($school_id, $class_id, $lesson_id, $unit_id, $topic_id);
    $itemsCount = count($items);

    $styles = ["danger", "success", "primary", "warning", "info", "secondary", "light", "dark"];
    $styleIndex = 0;
    $view = '';

    require_once "../classes/content-tracker.classes.php";

    $contentObj = new ContentTracker();

    $style = $styles[$styleIndex % count($styles)];

    foreach ($items as $item) {


        // $subItems = getSubtopicsByTopicId($school_id, $class_id, $lesson_id, $unit_id, $topic_id);
        // $subItemsCount = 0;
        // $subItemsCount = count($subItems);
        $percentage = $contentObj->getSchoolContentAnalyticsByTopicId($student_id, $item['id']);
        $view .= '<div class="d-flex flex-stack">
                            <div class="symbol symbol-40px me-4">
                                <div class="symbol-label fs-2 fw-semibold bg-' . $style . ' text-inverse-' . $style . '">' . mb_substr($item['name'], 0, 1, 'UTF-8') . '</div>
                            </div>
                            <div class="d-flex align-items-center flex-row-fluid ">
                                <div class="flex-grow-1 me-2">
                                    <a href="' . $student_id . '" class="text-gray-800 text-hover-primary fs-6 fw-bold">' . $item['name'] . '</a>
                                    
                                </div>
                                <div class="d-flex align-items-center w-100px w-sm-200px flex-column mt-3">
                                    <div class="d-flex justify-content-between w-100 mt-auto mb-2">
                                        <span class="fw-semibold fs-6 text-gray-500">Tamamlama Oranı</span>
                                        <span class="fw-bold fs-6">' . $percentage . '%</span>
                                    </div>
                                    <div class="h-5px mx-3 w-100 bg-light mb-3">  
                                        <div class="bg-success rounded h-5px" role="progressbar" style="width: ' . $percentage . '%;" aria-valuenow="25"
                                            aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="separator separator-dashed my-4"></div>';
    }

    $FinalView = '<div class="col-xl-6">
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
                        </div>
                    </div>';

    $styleIndex++;

    return $FinalView;
}
function doSubtopics($school_id, $class_id, $lesson_id, $unit_id, $topic_id, $subtopic_id, $student_id)
{
    $itemModel = getSubtopicById($subtopic_id);
    // $items = getSubtopicsByTopicId($school_id, $class_id, $lesson_id, $unit_id, $topic_id);
    // $itemsCount = count($items);

    $styles = ["danger", "success", "primary", "warning", "info", "secondary", "light", "dark"];
    $styleIndex = 0;
    $view = '';

    require_once "../classes/content-tracker.classes.php";

    $contentObj = new ContentTracker();
    $itemModel = $itemModel[0];

    $style = $styles[$styleIndex % count($styles)];

    $percentage = $contentObj->getSchoolContentAnalyticsBySubtopicId($student_id, $itemModel['id']);
    $view .= '<div class="d-flex flex-stack">
                            <div class="symbol symbol-40px me-4">
                                <div class="symbol-label fs-2 fw-semibold bg-' . $style . ' text-inverse-' . $style . '">' . mb_substr($itemModel['name'], 0, 1, 'UTF-8') . '</div>
                            </div>
                            <div class="d-flex align-items-center flex-row-fluid ">
                                <div class="flex-grow-1 me-2">
                                    <a href="' . $student_id . '" class="text-gray-800 text-hover-primary fs-6 fw-bold">' . $itemModel['name'] . '</a>
                                </div>
                                <div class="d-flex align-items-center w-100px w-sm-200px flex-column mt-3">
                                    <div class="d-flex justify-content-between w-100 mt-auto mb-2">
                                        <span class="fw-semibold fs-6 text-gray-500">Tamamlama Oranı</span>
                                        <span class="fw-bold fs-6">' . $percentage . '%</span>
                                    </div>
                                    <div class="h-5px mx-3 w-100 bg-light mb-3">  
                                        <div class="bg-success rounded h-5px" role="progressbar" style="width: ' . $percentage . '%;" aria-valuenow="25"
                                            aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="separator separator-dashed my-4"></div>';
    // }

    $FinalView = '<div class="col-xl-6">
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
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;

    } catch (PDOException $e) {
        return [];
    }


}
function getUnitById($id)
{
    $db = (new dbh())->connect();
    $sql = 'SELECT * FROM units_lnp WHERE id=?';

    try {
        $stmt = $db->prepare($sql);
        $stmt->execute([$id]);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;

    } catch (PDOException $e) {
        return [];
    }
}
function getTopicById($id)
{
    $db = (new dbh())->connect();
    $sql = 'SELECT * FROM topics_lnp WHERE id=?';

    try {
        $stmt = $db->prepare($sql);
        $stmt->execute([$id]);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;

    } catch (PDOException $e) {
        return [];
    }
}
function getSubtopicById($id)
{
    $db = (new dbh())->connect();
    $sql = 'SELECT * FROM subtopics_lnp WHERE id=?';

    try {
        $stmt = $db->prepare($sql);
        $stmt->execute([$id]);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;

    } catch (PDOException $e) {
        return [];
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
        return [];
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
        return [];
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
        return [];
    }
}
