<?php
if (!defined('GUARD')) {
    die('Erişim yasak!');
}


// URL yolunu al
$path = $_SERVER['REQUEST_URI']; // Örnek: /urunler/kategori/telefon

// Başındaki ve sonundaki '/' karakterlerini temizle
$path = trim($path, '/');

// Segmentlere ayır
$segments = explode('/', $path);

// Son segmenti al
$lastSegment = end($segments);

$img = "lineup-robot-maskot.png";
if($lastSegment == 'hesap-olustur') {
    $img = "maskot-kiz-2.png";
}elseif($lastSegment == 'parolami-unuttum') {
    $img = "parolami-unuttum-maskot.png";
}elseif($lastSegment == 'odeme-al') {
    $img = "maskot-erkek-2.png";
}elseif($lastSegment == 'odeme-sonuc') {
    $img = "odeme-sonuc-maskot.png";
}elseif($lastSegment == 'havale-bilgisi') {
    $img = "havale-bilgisi-maskot.png";
}


?>

<div class="d-flex flex-column flex-lg-row-auto bg-primary w-xl-600px positon-xl-relative">
    <!--begin::Wrapper-->
    <div class="d-flex flex-column position-xl-fixed top-0 bottom-0 w-xl-600px">
        <!--begin::Header-->
        <div class="d-flex flex-row-fluid justify-content-center flex-column text-center p-5 p-lg-10 pt-lg-10">
            <!--begin::Logo-->
            <a href="index" class="py-2 py-lg-10">
                <img alt="Logo" src="assets/media/logos/lineup-campus.jpg" class="h-100px h-lg-150px" />
            </a>
            <!--end::Logo-->
            <!--begin::Title-->
            <h1 class="d-none d-lg-block fw-bold text-white fs-2qx pb-5 pb-md-10">Lineup Campus'e <br>Hoş Geldiniz</h1>
            <!--end::Title-->
            <!--begin::Description-->
            <p class="d-none d-lg-block fw-semibold fs-2 text-white"><img src="assets/media/mascots/<?php echo $img; ?>" alt="Illustration" class="w-37" /></p>
            <!--end::Description-->
        </div>
        <!--end::Header-->
        <!--begin::Illustration-->
        <!--<div class="d-none d-lg-block d-flex flex-row-auto bgi-no-repeat bgi-position-x-center bgi-size-contain bgi-position-y-bottom min-h-100px min-h-lg-350px" style="background-image: url(assets/media/illustrations/illustration/lineup-home.svg)"></div>-->
        <!--end::Illustration-->
    </div>
    <!--end::Wrapper-->
</div>