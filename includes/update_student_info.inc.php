<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == "POST") {

    include_once "../classes/dbh.classes.php";
    include_once "../classes/student.classes.php";

    $studentObj = new Student();
    $userInfo = $studentObj->getStudentById($_SESSION['id']);
    $phone = $_POST["phone"];
    $city = $_POST["city"];
    $district = $_POST["district"];
    $address = $_POST["address"];
    $postcode = $_POST["postcode"];

    $photoSize = $_FILES['photo']['size'];
    $photoName = $_FILES['photo']['name'];
    $fileTmpName = $_FILES['photo']['tmp_name'];


    if (empty($phone)) {
        echo json_encode(["status" => "fail", "message" => "Telefon numarası boş bırakılamaz."]);
    } elseif (!ctype_digit($phone)) {
        echo json_encode(["status" => "fail", "message" => "Telefon numaranız sadece rakamlardan oluşmalıdır."]);

    } elseif (strlen($phone) != 11) {
        echo json_encode(["status" => "fail", "message" => "Telefon numaranız tam 11 haneli olmalıdır."]);
    }
    updateStudent($userInfo, $phone, $city, $district, $address, $postcode, $photoSize, $photoName, $fileTmpName);


}

function updateStudent($userInfo, $phone, $city, $district, $address, $postcode, $photoSize, $photoName, $fileTmpName)
{
    $phoneChanged = ($phone !== $userInfo['telephone']);
    $cityChanged = ($city !== $userInfo['city']);
    $districtChanged = ($district !== $userInfo['district']);
    $addressChanged = ($address !== $userInfo['address']);
    $postcodeChanged = ($postcode !== $userInfo['postcaode']);
    $photoUploaded = ($fileTmpName != NULL);
    $imgName = $userInfo['photo'];



    if (!$phoneChanged && !$cityChanged && !$districtChanged && !$addressChanged && !$photoUploaded && !$postcodeChanged) {
        echo json_encode(["status" => "success"]);
        exit();
    }

    if ($photoUploaded) {
        require_once "../classes/addimage.classes.php";
        require_once "../classes/slug.classes.php";
        $imageSent = new ImageUpload();
        $slugName = new Slug($userInfo['surname']);
        $slug = $slugName->slugify($userInfo['surname']);

        $img = $imageSent->profileImage($photoName, $photoSize, $fileTmpName, $userInfo['slug']);
        $imgName = $img['image'];
    }

    require_once '../classes/dbh.classes.php';
    $db = (new dbh())->connect();

    $stmt = $db->prepare('
        UPDATE users_lnp 
        SET telephone = ?, city = ?, district = ?, address = ?, postcode = ?, photo = ?
        WHERE id = ?
    ');

    if (!$stmt->execute([$phone, $city, $district, $address, $postcode, $imgName, $userInfo['id']])) {
        $stmt = null;
        echo json_encode(["status" => "fail", "message" => "Error"]);
        exit();
    }

    echo json_encode(["status" => "success", "message" => ""]);
    exit();

}

