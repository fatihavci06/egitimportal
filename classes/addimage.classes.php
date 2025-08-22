<?php

class ImageUpload
{

    public function profileImage($name, $fileSize, $fileTmpName, $slug)
    {
        $message = "";
        $uploadOk = 1;

        $fileSize = $fileSize;
        $randNum = rand(0, 99999);
        $fileTempPath = $fileTmpName;
        $fileName = $name;
        $maxFileSize = 1024 * 1024 * 2;
        $fileTypes = array("jpg", "jpeg", "png", "JPG", "JPEG", "PNG");
        $uploadFolder = "../assets/media/profile/";

        if ($fileSize > $maxFileSize) {
            $message = "Dosya boyutu 2mb'dan fazla.<br>";
            $uploadOk = 0;
        }

        $fileName_Arr = explode(".", $fileName);
        $fileNameOnly = $fileName_Arr[0];
        $fileType = $fileName_Arr[1];

        if (!in_array($fileType, $fileTypes)) {
            $message .= "Kabul edilen dosya uzantıları : " . implode(", ", $fileTypes) . "<br>";
            $uploadOk = 0;
        }

        $newFileName = $slug . "-" . $randNum . "." . $fileType;
        $photoPath = $uploadFolder . $newFileName;

        if ($uploadOk == 0) {
            $message .= "Dosya yüklenemedi.<br>";
            echo json_encode(["status" => "error", "message" => $name]);
            exit();
        } else {
            if (move_uploaded_file($fileTempPath, $photoPath)) {
                $message .= "dosya yüklendi.<br>";
            }
        }

        return array(
            "isSuccess" => $uploadOk,
            "message" => $message,
            "image" => $newFileName
        );

    }

    public function unitImage($name, $fileSize, $fileTmpName, $slug)
    {
        $message = "";
        $uploadOk = 1;

        $fileSize = $fileSize;
        $randNum = rand(0, 99999);
        $fileTempPath = $fileTmpName;
        $fileName = $name;
        $maxFileSize = 1024 * 1024 * 2;
        $fileTypes = array("jpg", "jpeg", "png", "JPG", "JPEG", "PNG");
        $uploadFolder = "../assets/media/units/";

        if ($fileSize > $maxFileSize) {
            $message = "Dosya boyutu 2mb'dan fazla.<br>";
            $uploadOk = 0;
        }

        $fileName_Arr = explode(".", $fileName);
        $fileNameOnly = $fileName_Arr[0];
        $fileType = $fileName_Arr[1];

        if (!in_array($fileType, $fileTypes)) {
            $message .= "Kabul edilen dosya uzantıları : " . implode(", ", $fileTypes) . "<br>";
            $uploadOk = 0;
        }

        $newFileName = $slug . "-" . $randNum . "." . $fileType;
        $photoPath = $uploadFolder . $newFileName;

        if ($uploadOk == 0) {
            $message .= "Dosya yüklenemedi.<br>";
            echo json_encode(["status" => "error", "message" => $name]);
            exit();
        } else {
            if (move_uploaded_file($fileTempPath, $photoPath)) {
                $message .= "dosya yüklendi.<br>";
            }
        }

        return array(
            "isSuccess" => $uploadOk,
            "message" => $message,
            "image" => $newFileName
        );

    }

    public function topicImage($name, $fileSize, $fileTmpName, $slug)
    {
        $message = "";
        $uploadOk = 1;

        $fileSize = $fileSize;
        $randNum = rand(0, 99999);
        $fileTempPath = $fileTmpName;
        $fileName = $name;
        $maxFileSize = 1024 * 1024 * 2;
        $fileTypes = array("jpg", "jpeg", "png", "JPG", "JPEG", "PNG");
        $uploadFolder = "../assets/media/topics/";

        if ($fileSize > $maxFileSize) {
            $message = "Dosya boyutu 2mb'dan fazla.<br>";
            $uploadOk = 0;
        }

        $fileName_Arr = explode(".", $fileName);
        $fileNameOnly = $fileName_Arr[0];
        $fileType = $fileName_Arr[1];

        if (!in_array($fileType, $fileTypes)) {
            $message .= "Kabul edilen dosya uzantıları : " . implode(", ", $fileTypes) . "<br>";
            $uploadOk = 0;
        }

        $newFileName = $slug . "-" . $randNum . "." . $fileType;
        $photoPath = $uploadFolder . $newFileName;

        if ($uploadOk == 0) {
            $message .= "Dosya yüklenemedi.<br>";
            echo json_encode(["status" => "error", "message" => $name]);
            exit();
        } else {
            if (move_uploaded_file($fileTempPath, $photoPath)) {
                $message .= "dosya yüklendi.<br>";
            }
        }

        return array(
            "isSuccess" => $uploadOk,
            "message" => $message,
            "image" => $newFileName
        );

    }

    public function gameImage($name, $fileSize, $fileTmpName, $slug)
    {
        $message = "";
        $uploadOk = 1;

        $fileSize = $fileSize;
        $randNum = rand(0, 99999);
        $fileTempPath = $fileTmpName;
        $fileName = $name;
        $maxFileSize = 1024 * 1024 * 2;
        $fileTypes = array("jpg", "jpeg", "png", "JPG", "JPEG", "PNG");
        $uploadFolder = "../assets/media/games/";

        if ($fileSize > $maxFileSize) {
            $message = "Dosya boyutu 2mb'dan fazla.<br>";
            $uploadOk = 0;
        }

        $fileName_Arr = explode(".", $fileName);
        $fileNameOnly = $fileName_Arr[0];
        $fileType = $fileName_Arr[1];

        if (!in_array($fileType, $fileTypes)) {
            $message .= "Kabul edilen dosya uzantıları : " . implode(", ", $fileTypes) . "<br>";
            $uploadOk = 0;
        }

        $newFileName = $slug . "-" . $randNum . "." . $fileType;
        $photoPath = $uploadFolder . $newFileName;

        if ($uploadOk == 0) {
            $message .= "Dosya yüklenemedi.<br>";
            echo json_encode(["status" => "error", "message" => $name]);
            exit();
        } else {
            if (move_uploaded_file($fileTempPath, $photoPath)) {
                $message .= "dosya yüklendi.<br>";
            }
        }

        return array(
            "isSuccess" => $uploadOk,
            "message" => $message,
            "image" => $newFileName
        );

    }

    public function audioBookImage($name, $fileSize, $fileTmpName, $slug)
    {
        $message = "";
        $uploadOk = 1;

        $fileSize = $fileSize;
        $randNum = rand(0, 99999);
        $fileTempPath = $fileTmpName;
        $fileName = $name;
        $maxFileSize = 1024 * 1024 * 2;
        $fileTypes = array("jpg", "jpeg", "png", "JPG", "JPEG", "PNG");
        $uploadFolder = "../assets/media/sesli-kitap/";

        if ($fileSize > $maxFileSize) {
            $message = "Dosya boyutu 2mb'dan fazla.<br>";
            $uploadOk = 0;
        }

        $fileName_Arr = explode(".", $fileName);
        $fileNameOnly = $fileName_Arr[0];
        $fileType = $fileName_Arr[1];

        if (!in_array($fileType, $fileTypes)) {
            $message .= "Kabul edilen dosya uzantıları : " . implode(", ", $fileTypes) . "<br>";
            $uploadOk = 0;
        }

        $newFileName = $slug . "-" . $randNum . "." . $fileType;
        $photoPath = $uploadFolder . $newFileName;

        if ($uploadOk == 0) {
            $message .= "Dosya yüklenemedi.<br>";
            echo json_encode(["status" => "error", "message" => $name]);
            exit();
        } else {
            if (move_uploaded_file($fileTempPath, $photoPath)) {
                $message .= "dosya yüklendi.<br>";
            }
        }

        return array(
            "isSuccess" => $uploadOk,
            "message" => $message,
            "image" => $newFileName
        );

    }
    public function doYouKnowImage($name, $fileSize, $fileTmpName, $time)
    {
        $message = "";
        $uploadOk = 1;

        $fileSize = $fileSize;
        $randNum = rand(0, 99999);
        $fileTempPath = $fileTmpName;
        $fileName = $name;
        $maxFileSize = 1024 * 1024 * 2;
        $fileTypes = array("jpg", "jpeg", "png", "JPG", "JPEG", "PNG");
        $uploadFolder = "../assets/media/do-you-know/";

        if ($fileSize > $maxFileSize) {
            $message = "Dosya boyutu 2mb'dan fazla.<br>";
            $uploadOk = 0;
        }

        $fileName_Arr = explode(".", $fileName);
        $fileNameOnly = $fileName_Arr[0];
        $fileType = $fileName_Arr[1];

        if (!in_array($fileType, $fileTypes)) {
            $message .= "Kabul edilen dosya uzantıları : " . implode(", ", $fileTypes) . "<br>";
            $uploadOk = 0;
        }

        $newFileName = $time . "-" . $randNum . "." . $fileType;
        $photoPath = $uploadFolder . $newFileName;

        if ($uploadOk == 0) {
            $message .= "Dosya yüklenemedi.<br>";
            echo json_encode(["status" => "error", "message" => $name]);
            exit();
        } else {
            if (move_uploaded_file($fileTempPath, $photoPath)) {
                $message .= "dosya yüklendi.<br>";
            }
        }

        return array(
            "isSuccess" => $uploadOk,
            "message" => $message,
            "image" => $newFileName
        );

    }
    public function todayWordImage($name, $fileSize, $fileTmpName, $time)
    {
        $message = "";
        $uploadOk = 1;

        $fileSize = $fileSize;
        $randNum = rand(0, 99999);
        $fileTempPath = $fileTmpName;
        $fileName = $name;
        $maxFileSize = 1024 * 1024 * 2;
        $fileTypes = array("jpg", "jpeg", "png", "JPG", "JPEG", "PNG");
        $uploadFolder = "../assets/media/today-word/";

        if ($fileSize > $maxFileSize) {
            $message = "Dosya boyutu 2mb'dan fazla.<br>";
            $uploadOk = 0;
        }

        $fileName_Arr = explode(".", $fileName);
        $fileNameOnly = $fileName_Arr[0];
        $fileType = $fileName_Arr[1];

        if (!in_array($fileType, $fileTypes)) {
            $message .= "Kabul edilen dosya uzantıları : " . implode(", ", $fileTypes) . "<br>";
            $uploadOk = 0;
        }

        $newFileName = $time . "-" . $randNum . "." . $fileType;
        $photoPath = $uploadFolder . $newFileName;

        if ($uploadOk == 0) {
            $message .= "Dosya yüklenemedi.<br>";
            echo json_encode(["status" => "error", "message" => $name]);
            exit();
        } else {
            if (move_uploaded_file($fileTempPath, $photoPath)) {
                $message .= "dosya yüklendi.<br>";
            }
        }

        return array(
            "isSuccess" => $uploadOk,
            "message" => $message,
            "image" => $newFileName
        );

    }
    public function weeklyImage($name, $fileSize, $fileTmpName, $slug)
    {
        $message = "";
        $uploadOk = 1;

        $fileSize = $fileSize;
        $randNum = rand(0, 99999);
        $fileTempPath = $fileTmpName;
        $fileName = $name;
        $maxFileSize = 1024 * 1024 * 2;
        $fileTypes = array("jpg", "jpeg", "png", "JPG", "JPEG", "PNG");
        $uploadFolder = "../assets/media/haftalik-gorev/";

        if ($fileSize > $maxFileSize) {
            $message = "Dosya boyutu 2mb'dan fazla.<br>";
            $uploadOk = 0;
        }

        $fileName_Arr = explode(".", $fileName);
        $fileNameOnly = $fileName_Arr[0];
        $fileType = $fileName_Arr[1];

        if (!in_array($fileType, $fileTypes)) {
            $message .= "Kabul edilen dosya uzantıları : " . implode(", ", $fileTypes) . "<br>";
            $uploadOk = 0;
        }

        $newFileName = $slug . "-" . $randNum . "." . $fileType;
        $photoPath = $uploadFolder . $newFileName;

        if ($uploadOk == 0) {
            $message .= "Dosya yüklenemedi.<br>";
            echo json_encode(["status" => "error", "message" => $name]);
            exit();
        } else {
            if (move_uploaded_file($fileTempPath, $photoPath)) {
                $message .= "dosya yüklendi.<br>";
            }
        }

        return array(
            "isSuccess" => $uploadOk,
            "message" => $message,
            "image" => $newFileName
        );

    }
    public function supportImage($name, $fileSize, $fileTmpName, $slug)
    {
        $message = "";
        $uploadOk = 1;

        $fileSize = $fileSize;
        $randNum = rand(0, 99999);
        $fileTempPath = $fileTmpName;
        $fileName = $name;
        $maxFileSize = 1024 * 1024 * 2;
        $fileTypes = array("jpg", "jpeg", "png", "JPG", "JPEG", "PNG");
        $uploadFolder = "../assets/media/destek/";

        if ($fileSize > $maxFileSize) {
            $message = "Dosya boyutu 2mb'dan fazla.<br>";
            $uploadOk = 0;
        }

        $fileName_Arr = explode(".", $fileName);
        $fileNameOnly = $fileName_Arr[0];
        $fileType = $fileName_Arr[1];

        if (!in_array($fileType, $fileTypes)) {
            $message .= "Kabul edilen dosya uzantıları : " . implode(", ", $fileTypes) . "<br>";
            $uploadOk = 0;
        }

        $newFileName = $slug . "-" . $randNum . "." . $fileType;
        $photoPath = $uploadFolder . $newFileName;

        if ($uploadOk == 0) {
            $message .= "Dosya yüklenemedi.<br>";
            echo json_encode(["status" => "error", "message" => $name]);
            exit();
        } else {
            if (move_uploaded_file($fileTempPath, $photoPath)) {
                $message .= "dosya yüklendi.<br>";
            }
        }

        return array(
            "isSuccess" => $uploadOk,
            "message" => $message,
            "image" => $newFileName
        );

    }

    public function contentImage($name, $fileSize, $fileTmpName, $slug)
    {
        $message = "";
        $uploadOk = 1;

        $fileSize = $fileSize;
        $randNum = rand(0, 99999);
        $fileTempPath = $fileTmpName;
        $fileName = $name;
        $maxFileSize = 1024 * 1024 * 2;
        $fileTypes = array("jpg", "jpeg", "png", "JPG", "JPEG", "PNG");
        $uploadFolder = "../uploads/contents/";

        if ($fileSize > $maxFileSize) {
            $message = "Dosya boyutu 2mb'dan fazla.<br>";
            $uploadOk = 0;
        }

        $fileName_Arr = explode(".", $fileName);
        $fileNameOnly = $fileName_Arr[0];
        $fileType = $fileName_Arr[1];

        if (!in_array($fileType, $fileTypes)) {
            $message .= "Kabul edilen dosya uzantıları : " . implode(", ", $fileTypes) . "<br>";
            $uploadOk = 0;
        }

        $newFileName = $slug . "-" . $randNum . "." . $fileType;
        $photoPath = $uploadFolder . $newFileName;

        if ($uploadOk == 0) {
            $message .= "Dosya yüklenemedi.<br>";
            echo json_encode(["status" => "error", "message" => $name]);
            exit();
        } else {
            if (move_uploaded_file($fileTempPath, $photoPath)) {
                $message .= "dosya yüklendi.<br>";
            }
        }

        return array(
            "isSuccess" => $uploadOk,
            "message" => $message,
            "image" => $newFileName
        );

    }

    public function threeDVideoImage($name, $fileSize, $fileTmpName, $slug)
    {
        $message = "";
        $uploadOk = 1;

        $fileSize = $fileSize;
        $randNum = rand(0, 99999);
        $fileTempPath = $fileTmpName;
        $fileName = $name;
        $maxFileSize = 1024 * 1024 * 2;
        $fileTypes = array("jpg", "jpeg", "png", "JPG", "JPEG", "PNG");
        $uploadFolder = "../uploads/3dvideos/";

        if ($fileSize > $maxFileSize) {
            $message = "Dosya boyutu 2mb'dan fazla.<br>";
            $uploadOk = 0;
        }

        $fileName_Arr = explode(".", $fileName);
        $fileNameOnly = $fileName_Arr[0];
        $fileType = $fileName_Arr[1];

        if (!in_array($fileType, $fileTypes)) {
            $message .= "Kabul edilen dosya uzantıları : " . implode(", ", $fileTypes) . "<br>";
            $uploadOk = 0;
        }

        $newFileName = $slug . "-" . $randNum . "." . $fileType;
        $photoPath = $uploadFolder . $newFileName;

        if ($uploadOk == 0) {
            $message .= "Dosya yüklenemedi.<br>";
            echo json_encode(["status" => "error", "message" => $name]);
            exit();
        } else {
            if (move_uploaded_file($fileTempPath, $photoPath)) {
                $message .= "dosya yüklendi.<br>";
            }
        }

        return array(
            "isSuccess" => $uploadOk,
            "message" => $message,
            "image" => $newFileName
        );

    }

    public function homeworkImage($name, $fileSize, $fileTmpName, $slug)
    {
        $message = "";
        $uploadOk = 1;

        $fileSize = $fileSize;
        $randNum = rand(0, 99999);
        $fileTempPath = $fileTmpName;
        $fileName = $name;
        $maxFileSize = 1024 * 1024 * 2;
        $fileTypes = array("jpg", "jpeg", "png", "JPG", "JPEG", "PNG");
        $uploadFolder = "../uploads/homeworks/";

        if ($fileSize > $maxFileSize) {
            $message = "Dosya boyutu 2mb'dan fazla.<br>";
            $uploadOk = 0;
        }

        $fileName_Arr = explode(".", $fileName);
        $fileNameOnly = $fileName_Arr[0];
        $fileType = $fileName_Arr[1];

        if (!in_array($fileType, $fileTypes)) {
            $message .= "Kabul edilen dosya uzantıları : " . implode(", ", $fileTypes) . "<br>";
            $uploadOk = 0;
        }

        $newFileName = $slug . "-" . $randNum . "." . $fileType;
        $photoPath = $uploadFolder . $newFileName;

        if ($uploadOk == 0) {
            $message .= "Dosya yüklenemedi.<br>";
            echo json_encode(["status" => "error", "message" => $name]);
            exit();
        } else {
            if (move_uploaded_file($fileTempPath, $photoPath)) {
                $message .= "dosya yüklendi.<br>";
            }
        }

        return array(
            "isSuccess" => $uploadOk,
            "message" => $message,
            "image" => $newFileName
        );

    }

    /*public function profileImage($tmp_name, $name, $fileSize)
    {
        $message = "";
        $uploadOk = 1;

        $fileSize = $fileSize;
        $randNum = rand(0, 99999);
        $fileTempPath = $tmp_name;
        $fileName = $name;
        $maxFileSize = 1024 * 1024 * 2;
        $fileTypes = array("jpg", "jpeg", "png", "webp", "JPG");
        $uploadFolder = "../../img/profil/";

        if($fileSize > $maxFileSize) {
            $message = "Dosya boyutu 2mb'dan fazla.<br>";
            $uploadOk = 0;
        }

        $fileName_Arr = explode(".", $fileName);
        $fileNameOnly = $fileName_Arr[0];
        $fileType = $fileName_Arr[1];

        if(!in_array($fileType, $fileTypes)) {
            $message .= "Kabul edilen dosya uzantıları : ".implode(", ", $fileTypes)."<br>";
            $uploadOk = 0;
        }

        $newFileName = $fileNameOnly . "-" . $randNum . "." . $fileType;
        $photoPath = $uploadFolder.$newFileName;

        if($uploadOk == 0) {
            $message .= "Dosya yüklenemedi.<br>";
        } else {
            if(move_uploaded_file($fileTempPath, $photoPath)) {
                $message .="dosya yüklendi.<br>";
            }
        }

        return array(
            "isSuccess" => $uploadOk,
            "message" => $message,
            "image" => $newFileName
        );

    }

    public function productExtImage($extraImg)
    {
        $message = "";
        $newNames = "";
        $uploadOk = 1;

        foreach($extraImg as $key=>$value){

        $fileSize = $_FILES['extraImg']['size'][$key];
        $randNum = rand(0, 99999);
        $fileTempPath = $_FILES['extraImg']['tmp_name'][$key];
        $fileName = $_FILES['extraImg']['name'][$key];
        $maxFileSize = 1024 * 1024 *2;
        $fileTypes = array("jpg", "jpeg", "png", "webp", "JPG");
        $uploadFolder = "../../img/products/" . $_SESSION['id']  . "/";

        if($fileSize > $maxFileSize) {
            $message = "Dosya boyutu 2mb'dan fazla.<br>";
            $uploadOk = 0;
        }

        $fileName_Arr = explode(".", $fileName);
        $fileNameOnly = $fileName_Arr[0];
        $fileType = $fileName_Arr[1];

        if(!in_array($fileType, $fileTypes)) {
            $message .= "Kabul edilen dosya uzantıları : ".implode(", ", $fileTypes)."<br>";
            $uploadOk = 0;
        }

        $newFileName = $fileNameOnly . "-" . $randNum . "." . $fileType;
        $photoPath = $uploadFolder.$newFileName;

        $newNames .= $fileNameOnly . "-" . $randNum . "." . $fileType . ';'; 

        if($uploadOk == 0) {
            $message .= "Dosya yüklenemedi.<br>";
        } else {
            if(move_uploaded_file($fileTempPath, $photoPath)) {
                $message .="dosya yüklendi.<br>";
            }
        }
    }

        return array(
            "isSuccess" => $uploadOk,
            "message" => $message,
            "image" => $newNames
        );

    }

    public function addImage($extraImg)
    {
        $message = "";
        $newNames = "";
        $uploadOk = 1;

        foreach($extraImg as $key=>$value){

        $fileSize = $_FILES['extraImg']['size'][$key];
        $randNum = rand(0, 99999);
        $fileTempPath = $_FILES['extraImg']['tmp_name'][$key];
        $fileName = $_FILES['extraImg']['name'][$key];
        $maxFileSize = 1024 * 1024 *2;
        $fileTypes = array("jpg", "jpeg", "png", "webp", "JPG");
        $uploadFolder = "../../img/products/" . $_SESSION['id'] . "/";

        if($fileSize > $maxFileSize) {
            $message = "Dosya boyutu 2mb'dan fazla.<br>";
            $uploadOk = 0;
        }

        if(!is_dir($uploadFolder)){
            mkdir($uploadFolder, 0777, true);
        }

        $fileName_Arr = explode(".", $fileName);
        $fileNameOnly = $fileName_Arr[0];
        $fileType = $fileName_Arr[1];

        if(!in_array($fileType, $fileTypes)) {
            $message .= "Kabul edilen dosya uzantıları : ".implode(", ", $fileTypes)."<br>";
            $uploadOk = 0;
        }

        $newFileName = $fileNameOnly . "-" . $randNum . "." . $fileType;
        $photoPath = $uploadFolder.$newFileName;

        $newNames .= $fileNameOnly . "-" . $randNum . "." . $fileType . ';'; 

        if($uploadOk == 0) {
            $message .= "Dosya yüklenemedi.<br>";
        } else {
            if(move_uploaded_file($fileTempPath, $photoPath)) {
                $message .="dosya yüklendi.<br>";
            }
        }
    }

        return array(
            "isSuccess" => $uploadOk,
            "message" => $message,
            "image" => $newNames
        );

    }*/

}