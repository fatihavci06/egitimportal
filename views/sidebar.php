<?php
if (!defined('GUARD')) {
    die('Erişim yasak!');
}

include_once "classes/dbh.classes.php";
include "classes/menu.classes.php";
include "classes/menu-view.classes.php";
include_once "classes/timespend.classes.php";
include_once "classes/student.classes.php";
include_once "classes/student-view.classes.php";

$student = new ShowStudent();

$timeSpend = new timeSpend();

$timeSpendInfo = $timeSpend->getTimeSpend($_SESSION["id"]);

$menu1 = new ShowMenu();

?>
<div id="kt_app_sidebar" class="app-sidebar flex-column" data-kt-drawer="true" data-kt-drawer-name="app-sidebar" data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true" data-kt-drawer-width="250px" data-kt-drawer-direction="start" data-kt-drawer-toggle="#kt_app_sidebar_mobile_toggle" style="background: #fdfdfd;border-right: 2px solid #fa6000;">
    <!--begin::Main-->
    <div class="d-flex flex-column justify-content-between hover-scroll-overlay-x h-100 my-5 mx-5 d-flex flex-column" style="margin-left:0px!important;margin-right:0px!important;" id="kt_app_sidebar_main" data-kt-scroll="true" data-kt-scroll-activate="true" data-kt-scroll-height="auto" data-kt-scroll-dependencies="#kt_app_header" data-kt-scroll-wrappers="#kt_app_main" data-kt-scroll-offset="5px">
        <?php if ($_SESSION['role'] == 2) { ?>
            <!--begin::Sidebar profile-->
            <div class="menu menu-sub-indention menu-column menu-rounded menu-active-bg mb-7 border-bottom-2" style="border-color: #fa6000 !important; border-bottom: solid">
                <span class="menu-link d-flex align-items-center px-3 py-4" style="display: flex; flex-direction: column; justify-content: center; align-items: center;">
                    <span class="symbol symbol-50px">
                        <?php if ($_SESSION['photo'] == "") { ?>
                            <img alt="Logo" src="assets/media/avatars/blank.png" alt="user" class="w-100 h-100 rounded-circle" style="width: 50px !important; height: 50px !important;" />
                        <?php } else { ?>
                            <img alt="Logo" src="assets/media/profile/<?php echo $_SESSION['photo']; ?>" class="w-50 h-50 rounded-circle" style="width: 50px !important; height: 50px !important;" />
                        <?php } ?>
                    </span>
                    <span style="text-align: center; margin-top: 10px; font-weight: bold; color: #333;">
                        <?php echo $_SESSION['name']; ?>
                    </span>
                </span>
                <div style="display: flex; justify-content: space-around; width: 100%; padding: 20px 0;">
                    <div style="flex: 1; text-align: center; padding: 10px; border: 1px solid #eee; margin: 0 5px; border-radius: 5px;">
                        <i class="fa-solid fa-hourglass fs-1 mb-3" style="color:black"></i>
                        <p class="mb-0"><?php echo $timeSpend->dakikayaCevir($timeSpendInfo); ?></p>
                        <p class="mb-0" style="font-size: 11px;">dakika geçirdin</p>
                    </div>
                    <div style="flex: 1; text-align: center; padding: 10px; border: 1px solid #eee; margin: 0 5px; border-radius: 5px;">
                        <i class="fa-solid fa-check fs-1 mb-3" style="color:black"></i>
                        <p class="mb-0"><?php echo $student->getStudentProgressForSidebar($_SESSION['id'], $_SESSION['class_id']); ?></p>
                        <p class="mb-0" style="font-size: 11px;">mateyali tamamladın</p>
                    </div>
                    <div style="flex: 1; text-align: center; padding: 10px; border: 1px solid #eee; margin: 0 5px; border-radius: 5px;">
                        <i class="fa-solid fa-question fs-1 mb-3" style="color:black"></i>
                        <p class="mb-0"><?php echo $student->getStudentTestNumberForSidebar($_SESSION['id']); ?></p>
                        <p class="mb-0" style="font-size: 11px;">sınav çözdün</p>
                    </div>
                </div>
            </div>
            <!--end::Sidebar profile-->
        <?php } ?>
        <?php if ($_SESSION['role'] == 10002) { ?>
            <!--begin::Sidebar profile-->
            <div class="menu menu-sub-indention menu-column menu-rounded menu-active-bg mb-7 border-bottom-2" style="border-color: #fa6000 !important; border-bottom: solid">
                <span class="menu-link d-flex align-items-center px-3 py-4" style="display: flex; flex-direction: column; justify-content: center; align-items: center;">
                    <span class="symbol symbol-50px">
                        <?php if ($_SESSION['photo'] == "") { ?>
                            <img alt="Logo" src="assets/media/avatars/blank.png" alt="user" class="w-100 h-100 rounded-circle" style="width: 50px !important; height: 50px !important;" />
                        <?php } else { ?>
                            <img alt="Logo" src="assets/media/profile/<?php echo $_SESSION['photo']; ?>" class="w-50 h-50 rounded-circle" style="width: 50px !important; height: 50px !important;" />
                        <?php } ?>
                    </span>
                    <span style="text-align: center; margin-top: 10px; font-weight: bold; color: #333;">
                        <?php echo $_SESSION['name']; ?>
                    </span>
                </span>
                <div style="display: flex; justify-content: space-around; width: 100%; padding: 20px 0;">
                    <div style="flex: 1; text-align: center; padding: 10px; border: 1px solid #eee; margin: 0 5px; border-radius: 5px;">
                        <i class="fa-solid fa-hourglass fs-1 mb-3" style="color:black"></i>
                        <p class="mb-0"><?php echo $timeSpend->dakikayaCevir($timeSpendInfo); ?></p>
                        <p class="mb-0" style="font-size: 11px;">dakika geçirdin</p>
                    </div>
                </div>
            </div>
            <!--end::Sidebar profile-->
        <?php } ?>
        <!--begin::Sidebar menu-->
        <div id="#kt_app_sidebar_menu" data-kt-menu="true" data-kt-menu-expand="false" class="flex-column-fluid menu menu-sub-indention menu-column menu-rounded menu-active-bg mb-7">
            <!--begin:Menu item-->
            <?php
            if ($_SESSION['role'] == 1) {
                $menu1->showMenuSuperAdminList();
            } elseif ($_SESSION['role'] == 2) {
                $menu1->showMenuSuperAdminList();
                /* echo '<div id="#kt_app_sidebar_menu" data-kt-menu="true" data-kt-menu-expand="false" class="flex-column-fluid menu menu-sub-indention menu-column menu-rounded menu-active-bg mb-7">
            <!--begin:Menu item-->
            <div data-kt-menu-trigger="click" class="mb-3 menu-item menu-accordion">
                                <!--begin:Menu link-->
                                <span class="menu-link" style="width:280px;">
                                    <span class="menu-icon">
                                        <i class="fa-solid fa-book-open fs-1"></i>
                                    </span>
                                    <span class="menu-title">Dersler</span>
                                    <span class="menu-arrow"></span>
                                </span>
                                <!--end:Menu link-->
                                <!--begin:Menu sub-->
                                <div class="menu-sub menu-sub-accordion">
                                    <!--begin:Menu item-->
                                    <div class="menu-item">
                                        <!--begin:Menu link-->
                                        <a class="menu-link" href="ders/turkce">
                                            <span class="menu-bullet">
                                                <span class="bullet bullet-dot"></span>
                                            </span>
                                            <span class="menu-title">Türkçe</span>
                                        </a>
                                        <!--end:Menu link-->
                                    </div>
                                    <!--end:Menu item-->
                                    
                                    <!--begin:Menu item-->
                                    <div class="menu-item">
                                        <!--begin:Menu link-->
                                        <a class="menu-link" href="ders/matematik">
                                            <span class="menu-bullet">
                                                <span class="bullet bullet-dot"></span>
                                            </span>
                                            <span class="menu-title">Matematik</span>
                                        </a>
                                        <!--end:Menu link-->
                                    </div>
                                    <!--end:Menu item-->
                                    
                                    <!--begin:Menu item-->
                                    <div class="menu-item">
                                        <!--begin:Menu link-->
                                        <a class="menu-link" href="ders/hayat-bilgisi">
                                            <span class="menu-bullet">
                                                <span class="bullet bullet-dot"></span>
                                            </span>
                                            <span class="menu-title">Hayat Bilgisi</span>
                                        </a>
                                        <!--end:Menu link-->
                                    </div>
                                    <!--end:Menu item-->
                                    
                                    <!--begin:Menu item-->
                                    <div class="menu-item">
                                        <!--begin:Menu link-->
                                        <a class="menu-link" href="ders/ingilizce">
                                            <span class="menu-bullet">
                                                <span class="bullet bullet-dot"></span>
                                            </span>
                                            <span class="menu-title">İngilizce</span>
                                        </a>
                                        <!--end:Menu link-->
                                    </div>
                                    <!--end:Menu item-->
                                    <div class="menu-item">
                                        <!--begin:Menu link-->
                                        <a class="menu-link" href="sesli-kitap">
                                            <span class="menu-bullet">
                                                <span class="bullet bullet-dot"></span>
                                            </span>
                                            <span class="menu-title">Sesli Kitap</span>
                                        </a>
                                        <!--end:Menu link-->
                                    </div>
                                    <!--end:Menu item-->
                                    <div class="menu-item">
                                        <!--begin:Menu link-->
                                        <a class="menu-link" href="oyun">
                                            <span class="menu-bullet">
                                                <span class="bullet bullet-dot"></span>
                                            </span>
                                            <span class="menu-title">Oyunlar</span>
                                        </a>
                                        <!--end:Menu link-->
                                    </div>
                                    <!--end:Menu item-->
                                    </div>
                                <!--end:Menu sub-->
                              </div><div data-kt-menu-trigger="click" class="mb-3 menu-item menu-accordion">
                                <!--begin:Menu link-->
                                <span class="menu-link">
                                    <span class="menu-icon">
                                        <i class="fa-solid fa-magnifying-glass fs-1"></i>
                                    </span>
                                    <span class="menu-title">İlerleme ve Performans Takibi</span>
                                    <span class="menu-arrow"></span>
                                </span>
                                <!--end:Menu link-->
                                <!--begin:Menu sub-->
                                <div class="menu-sub menu-sub-accordion"></div>
                                <!--end:Menu sub-->
                              </div><div data-kt-menu-trigger="click" class="mb-3 menu-item menu-accordion">
                                <!--begin:Menu link-->
                                <span class="menu-link">
                                    <span class="menu-icon">
                                        <i class="fa-solid fa-bullhorn fs-1"></i>
                                    </span>
                                    <span class="menu-title">Sistem Bildirimleri ve Duyurular</span>
                                    <span class="menu-arrow"></span>
                                </span>
                                <!--end:Menu link-->
                                <!--begin:Menu sub-->
                                <div class="menu-sub menu-sub-accordion">
                                    <!--begin:Menu item-->
                                    <div class="menu-item">
                                        <!--begin:Menu link-->
                                        <a class="menu-link" href="duyurular">
                                            <span class="menu-bullet">
                                                <span class="bullet bullet-dot"></span>
                                            </span>
                                            <span class="menu-title">Duyurular</span>
                                        </a>
                                        <!--end:Menu link-->
                                    </div>
                                    <!--end:Menu item-->
                                    
                                    <!--begin:Menu item-->
                                    <div class="menu-item">
                                        <!--begin:Menu link-->
                                        <a class="menu-link" href="bildirimler">
                                            <span class="menu-bullet">
                                                <span class="bullet bullet-dot"></span>
                                            </span>
                                            <span class="menu-title">Bildirimler</span>
                                        </a>
                                        <!--end:Menu link-->
                                    </div>
                                    <!--end:Menu item-->
                                    </div>
                                <!--end:Menu sub-->
                              </div><div data-kt-menu-trigger="click" class="mb-3 menu-item menu-accordion">
                                <!--begin:Menu link-->
                                <span class="menu-link">
                                    <span class="menu-icon">
                                        <i class="fa-solid fa-chart-line fs-1"></i>
                                    </span>
                                    <span class="menu-title">Raporlama ve Analitik</span>
                                    <span class="menu-arrow"></span>
                                </span>
                                <!--end:Menu link-->
                                <!--begin:Menu sub-->
                                <div class="menu-sub menu-sub-accordion"></div>
                                <!--end:Menu sub-->
                              </div><div data-kt-menu-trigger="click" class="mb-3 menu-item menu-accordion">
                                <!--begin:Menu link-->
                                <span class="menu-link">
                                    <span class="menu-icon">
                                        <i class="fa-regular fa-rectangle-list fs-1"></i>
                                    </span>
                                    <span class="menu-title">Ödev ve Test Yönetimi</span>
                                    <span class="menu-arrow"></span>
                                </span>
                                <!--end:Menu link-->
                                <!--begin:Menu sub-->
                                <div class="menu-sub menu-sub-accordion">
                                    <!--begin:Menu item-->
                                    <div class="menu-item">
                                        <!--begin:Menu link-->
                                        <a class="menu-link" href="ogrenci-testler">
                                            <span class="menu-bullet">
                                                <span class="bullet bullet-dot"></span>
                                            </span>
                                            <span class="menu-title">Testler</span>
                                        </a>
                                        <!--end:Menu link-->
                                    </div>
                                    <!--end:Menu item-->
                                    </div>
                                <!--end:Menu sub-->
                              </div><div data-kt-menu-trigger="click" class="mb-3 menu-item menu-accordion">
                                <!--begin:Menu link-->
                                <span class="menu-link">
                                    <span class="menu-icon">
                                        <i class="fa-regular fa-circle-question fs-1"></i>
                                    </span>
                                    <span class="menu-title">Destek ve Geri Bildirim Yönetimi</span>
                                    <span class="menu-arrow"></span>
                                </span>
                                <!--end:Menu link-->
                                <!--begin:Menu sub-->
                                <div class="menu-sub menu-sub-accordion">
                                    <!--begin:Menu item-->
                                    <div class="menu-item">
                                        <!--begin:Menu link-->
                                        <a class="menu-link" href="destek-talebi-ekle">
                                            <span class="menu-bullet">
                                                <span class="bullet bullet-dot"></span>
                                            </span>
                                            <span class="menu-title">Yeni Destek Talebi</span>
                                        </a>
                                        <!--end:Menu link-->
                                    </div>
                                    <!--end:Menu item-->
                                    
                                    <!--begin:Menu item-->
                                    <div class="menu-item">
                                        <!--begin:Menu link-->
                                        <a class="menu-link" href="aktif-destek-talepleri">
                                            <span class="menu-bullet">
                                                <span class="bullet bullet-dot"></span>
                                            </span>
                                            <span class="menu-title">Aktif Destek Talepleri</span>
                                        </a>
                                        <!--end:Menu link-->
                                    </div>
                                    <!--end:Menu item-->
                                    
                                    <!--begin:Menu item-->
                                    <div class="menu-item">
                                        <!--begin:Menu link-->
                                        <a class="menu-link" href="cozulmus-destek-talepleri">
                                            <span class="menu-bullet">
                                                <span class="bullet bullet-dot"></span>
                                            </span>
                                            <span class="menu-title">Çözülmüş Destek Talepleri</span>
                                        </a>
                                        <!--end:Menu link-->
                                    </div>
                                    <!--end:Menu item-->
                                    </div>
                                <!--end:Menu sub-->
                              </div>            <!--end:Menu item-->
                    </div>'; */
            } elseif ($_SESSION['role'] == 3) {
                $menu1->showMenuSuperAdminList();
            } elseif ($_SESSION['role'] == 4) {
                $menu1->showMenuSuperAdminList();
            } elseif ($_SESSION['role'] == 5) {
                $menu1->showMenuSuperAdminList();
            } elseif ($_SESSION['role'] == 10001) {

                $menu1->showMenuSuperAdminList();
            } elseif ($_SESSION['role'] == 10002) {
                $menu1->showMenuSuperAdminList();
            } elseif ($_SESSION['role'] == 8) {
                $menu1->showMenuSuperAdminList();
            } elseif ($_SESSION['role'] == 9) {
                $menu1->showMenuSuperAdminList();
            } elseif ($_SESSION['role'] == 10) {
                $menu1->showMenuSuperAdminList();
            }
            ?>
            <!--end:Menu item-->
            <?php if ($_SESSION['role'] == 1) { ?>
            <?php } ?>
        </div>
        <!--end::Sidebar menu-->
    </div>
    <!--end::Main-->
</div>