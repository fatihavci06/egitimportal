<?php

if ($_SERVER['REQUEST_METHOD'] == "POST") {

    // Grabbing the data
    $name = trim($_POST["name"]);
    $address = trim($_POST["address"]);
    $district = trim($_POST["district"]);
    $postcode = trim($_POST["postcode"]);
    $city = trim($_POST["city"]);
    $email = trim($_POST["email"]);
    $email_old = trim($_POST["email_old"]);
    $telephone = trim($_POST["telephone"]);
    $old_slug = trim($_POST["old_slug"]);
    $schId = intval($_POST["schId"]);
    $schoolAdminName = trim($_POST["schoolAdminName"]);
    $schoolAdminSurname = trim($_POST["schoolAdminSurname"]);
    $schoolAdminEmail = trim($_POST["schoolAdminEmail"]);
    $schoolAdminTelephone = trim($_POST["schoolAdminTelephone"]);
    $schoolCoordinatorName = trim($_POST["schoolCoordinatorName"]);
    $schoolCoordinatorSurname = trim($_POST["schoolCoordinatorSurname"]);
    $schoolCoordinatorEmail = trim($_POST["schoolCoordinatorEmail"]);
    $schoolCoordinatorTelephone = trim($_POST["schoolCoordinatorTelephone"]);
    $old_admin_email = trim($_POST["old_admin_email"]);
    $old_coord_email = trim($_POST["old_coord_email"]);
    $old_admin_name = trim($_POST["old_admin_name"]);
    $old_admin_surname = trim($_POST["old_admin_surname"]);
    $old_coord_name = trim($_POST["old_coord_name"]);
    $old_coord_surname = trim($_POST["old_coord_surname"]);

    // Instantiate UpdateSchoolContr class
    include "../classes/dbh.classes.php";
    include "../classes/updateschool.classes.php";
    include "../classes/updateschool-contr.classes.php";
    include "../classes/slug.classes.php";


    $updateSchool = new UpdateSchoolContr($old_slug, $name, $address, $district, $postcode, $city, $email, $email_old, $telephone, $schoolAdminName, $schoolAdminSurname, $schoolAdminEmail, $schoolAdminTelephone, $schoolCoordinatorName, $schoolCoordinatorSurname, $schoolCoordinatorEmail, $schoolCoordinatorTelephone, $schId, $old_admin_email, $old_coord_email, $old_admin_name, $old_admin_surname, $old_coord_name, $old_coord_surname);

    // Running error handlers and school updateSchool
    $updateSchool->updateSchoolDb();




    // Going to back to products page
    //header("location: ../kategoriler");
}
