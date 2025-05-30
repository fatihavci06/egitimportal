<?php 

if (!defined('GUARD')) {
    die('Erişim yasak!');
}

$slug = new ShowMenu();

$requestUri = $_SERVER['REQUEST_URI'];

$targetDers = '/ders/';
$targetUnite = '/unite/';
$targetKonu = '/konu/';
$targetAltKonu = '/alt-konu/';
$targetIcerik = '/icerik/';

if (strpos($requestUri, $targetDers) !== false) {
    // '/ders' deseni URL'de bulundu, istenen işlemi yap
    $breadcrumbs = '
                    <!--begin::Item-->
                    <li class="breadcrumb-item">
                        <i class="ki-duotone ki-right fs-4 text-gray-700 mx-n1"></i>
                    </li>
                    <!--end::Item-->
                    <!--begin::Item-->
                    <li class="breadcrumb-item text-gray-700 fw-bold lh-1">Ders</li>
                    <!--end::Item-->
                    ';

}elseif (strpos($requestUri, $targetUnite) !== false) {
    // '/unite' deseni URL'de bulundu, istenen işlemi yap
    $breadcrumbs = '
                    <!--begin::Item-->
                    <li class="breadcrumb-item">
                        <i class="ki-duotone ki-right fs-4 text-gray-700 mx-n1"></i>
                    </li>
                    <!--end::Item-->
                    <!--begin::Item-->
                    <li class="breadcrumb-item text-gray-700 fw-bold lh-1">Ünite</li>
                    <!--end::Item-->
                    ';

} elseif (strpos($requestUri, $targetKonu) !== false) {
    // '/konu' deseni URL'de bulundu, istenen işlemi yap
    $breadcrumbs = '
                    <!--begin::Item-->
                    <li class="breadcrumb-item">
                        <i class="ki-duotone ki-right fs-4 text-gray-700 mx-n1"></i>
                    </li>
                    <!--end::Item-->
                    <!--begin::Item-->
                    <li class="breadcrumb-item text-gray-700 fw-bold lh-1">Konu</li>
                    <!--end::Item-->
                    ';
} elseif (strpos($requestUri, $targetAltKonu) !== false) {
    // '/alt-konu' deseni URL'de bulundu, istenen işlemi yap
    $breadcrumbs = '
                    <!--begin::Item-->
                    <li class="breadcrumb-item">
                        <i class="ki-duotone ki-right fs-4 text-gray-700 mx-n1"></i>
                    </li>
                    <!--end::Item-->
                    <!--begin::Item-->
                    <li class="breadcrumb-item text-gray-700 fw-bold lh-1">Alt Konu</li>
                    <!--end::Item-->
                    ';
} elseif (strpos($requestUri, $targetIcerik) !== false) {
    // '/icerik' deseni URL'de bulundu, istenen işlemi yap
    $breadcrumbs = '
                    <!--begin::Item-->
                    <li class="breadcrumb-item">
                        <i class="ki-duotone ki-right fs-4 text-gray-700 mx-n1"></i>
                    </li>
                    <!--end::Item-->
                    <!--begin::Item-->
                    <li class="breadcrumb-item text-gray-700 fw-bold lh-1">İçerik</li>
                    <!--end::Item-->
                    ';
} else {
    $breadcrumbs = '';
}

?>

<div id="kt_app_toolbar" class="app-toolbar pt-5">
    <!--begin::Toolbar container-->
    <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex align-items-stretch">
        <!--begin::Toolbar wrapper-->
        <div class="app-toolbar-wrapper d-flex flex-stack flex-wrap gap-4 w-100">
            <!--begin::Page title-->
            <div class="page-title d-flex flex-column gap-1 me-3 mb-2">
                <!--begin::Breadcrumb-->
                <ul class="breadcrumb breadcrumb-separatorless fw-semibold mb-6">
                    <!--begin::Item-->
                    <li class="breadcrumb-item text-gray-700 fw-bold lh-1">
                        <a href="index.html" class="text-gray-500 text-hover-primary">
                            <i class="ki-duotone ki-home fs-3 text-gray-500 me-n1"></i>
                        </a>
                    </li>
                    <!--end::Item-->
                    <?php echo $breadcrumbs; ?>
                    <!--begin::Item-->
                    <li class="breadcrumb-item">
                        <i class="ki-duotone ki-right fs-4 text-gray-700 mx-n1"></i>
                    </li>
                    <!--end::Item-->
                    <!--begin::Item-->
                    <li class="breadcrumb-item text-gray-700 fw-bold lh-1"><?php $slug->getTitle(); ?></li>
                    <!--end::Item-->
                </ul>
                <!--end::Breadcrumb-->
                <!--begin::Title-->
                <h1 class="page-heading d-flex flex-column justify-content-center text-gray-900 fw-bolder fs-1 lh-0"><?php $slug->getTitle(); ?></h1>
                <!--end::Title-->
            </div>
            <!--end::Page title-->
        </div>
        <!--end::Toolbar wrapper-->
    </div>
    <!--end::Toolbar container-->
</div>