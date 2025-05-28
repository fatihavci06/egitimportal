<?php

session_start();

if (!isset($_SESSION['role'])) {

    header("HTTP/1.0 404 Not Found");
    header("Location: 404.php");
    exit();
}
if ($_SESSION['role'] == 1) {
    // admin profilim sayfasi

} elseif ($_SESSION["role"] == 2) {
    // ogrenci profilim sayfasi
    require_once "profilim-ogrenci.php";

} elseif ($_SESSION["role"] == 3) {
    // ogretmen profilim sayfasi

} else {
    header("HTTP/1.0 404 Not Found");
    header("Location: 404.php");
    exit();
}