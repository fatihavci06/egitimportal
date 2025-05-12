<?php

if ($_SERVER['REQUEST_METHOD'] == "POST") {

    // Grabbing the data
    $name = $_POST["name"];
    $address = $_POST["address"];
    $district = $_POST["district"];
    $postcode = $_POST["postcode"];
    $city = $_POST["city"];
    $email = $_POST["email"];
    $email_old = $_POST["email_old"];
    $telephone = $_POST["telephone"];
    $old_slug = $_POST["old_slug"];

    // Instantiate UpdateSchoolContr class
    include "../classes/dbh.classes.php";
    include "../classes/updateschool.classes.php";
    include "../classes/updateschool-contr.classes.php";
    include "../classes/slug.classes.php";


    $updateSchool = new UpdateSchoolContr($old_slug, $name, $address, $district, $postcode, $city, $email, $email_old, $telephone);

    // Running error handlers and school updateSchool
    $updateSchool->updateSchoolDb();




    // Going to back to products page
    //header("location: ../kategoriler");
}
