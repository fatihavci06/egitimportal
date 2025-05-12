<?php
if (!defined('GUARD')) {
    die('Erişim yasak!');
}

include_once "classes/dbh.classes.php";
include_once "classes/announcement.classes.php";
include_once "classes/announcement-view.classes.php";
include_once "classes/dateformat.classes.php";

$notification = new NotificationForUsers();
$anouncement = new AnnouncementForUsers();

$now = new DateTime();

$timeDifference = new DateFormat();

?>
<div class="app-navbar-item me-lg-1">
    <!--begin::Menu- wrapper-->
    <div class="btn btn-icon btn-custom btn-color-gray-600 btn-active-color-primary w-35px h-35px w-md-40px h-md-40px" data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-attach="parent" data-kt-menu-placement="bottom-end">
        <i class="ki-duotone ki-graph-3 fs-1">
            <span class="path1"></span>
            <span class="path2"></span>
        </i>
    </div>
    <!--begin::Menu-->
    <div class="menu menu-sub menu-sub-dropdown menu-column w-350px w-lg-375px" data-kt-menu="true" id="kt_menu_notifications">
        <!--begin::Heading-->
        <div class="d-flex flex-column bgi-no-repeat rounded-top" style="background-image:url('assets/media/misc/menu-header-bg.jpg')">
            <!--begin::Title-->
            <h3 class="text-white fw-semibold px-9 mt-10 mb-6">Bildirim ve Duyurular
                <?php

                $notificationInfo = $notification->getNotificationsForUsersList($_SESSION['role'], $_SESSION['class_id'], $_SESSION['id']);
                $totalNotification = count($notificationInfo);
                $anouncementInfo = $anouncement->getAnnouncementsForUsersList($_SESSION['role'], $_SESSION['class_id'], $_SESSION['id']);
                $totalAnouncement = count($anouncementInfo);

                $totalNumber = $totalAnouncement + $totalNotification;
                ?>
                <span class="fs-8 opacity-75 ps-3"><?php echo $totalNumber; ?> bildirim</span>
            </h3>
            <!--end::Title-->
            <!--begin::Tabs-->
            <ul class="nav nav-line-tabs nav-line-tabs-2x nav-stretch fw-semibold px-9">
                <li class="nav-item">
                    <a class="nav-link text-white opacity-75 opacity-state-100 pb-4" data-bs-toggle="tab" href="#kt_topbar_notifications_1">Bildirimler (<?php echo $totalNotification ?>)</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white opacity-75 opacity-state-100 pb-4 active" data-bs-toggle="tab" href="#kt_topbar_notifications_2">Duyurular (<?php echo $totalAnouncement ?>)</a>
                </li>
                <?php if ($_SESSION['role'] == 1) { ?>
                    <li class="nav-item">
                        <a class="nav-link text-white opacity-75 opacity-state-100 pb-4" data-bs-toggle="tab" href="#kt_topbar_notifications_3">Kayıtlar</a>
                    </li>
                <?php } ?>
            </ul>
            <!--end::Tabs-->
        </div>
        <!--end::Heading-->
        <!--begin::Tab content-->
        <div class="tab-content">
            <!--begin::Tab panel-->
            <div class="tab-pane fade" id="kt_topbar_notifications_1" role="tabpanel">
                <!--begin::Items-->
                <div class="scroll-y mh-325px my-5 px-8">
                    <?php
                    if (empty($notificationInfo)) { ?>
                        <!--begin::Wrapper-->
                        <div class="d-flex flex-column px-9">
                            <!--begin::Section-->
                            <div class="pt-10 pb-0">
                                <!--begin::Title-->
                                <h3 class="text-gray-900 text-center fw-bold">Bildirim Yok!</h3>
                                <!--end::Title-->
                                <!--begin::Text-->
                                <div class="text-center text-gray-600 fw-semibold pt-1">Bildirimleri buradan görebilirsiniz</div>
                                <!--end::Text-->
                                <!--begin::Action-->
                                <!--<div class="text-center mt-5 mb-9">
                            <a href="#" class="btn btn-sm btn-primary px-6" data-bs-toggle="modal" data-bs-target="#kt_modal_upgrade_plan">Upgrade</a>
                        </div>-->
                                <!--end::Action-->
                            </div>
                            <!--end::Section-->
                            <!--begin::Illustration-->
                            <div class="text-center px-4">
                                <img class="mw-100 mh-200px" alt="image" src="assets/media/illustrations/sketchy-1/1.png" />
                            </div>
                            <!--end::Illustration-->
                        </div>
                        <!--end::Wrapper-->
                        <?php    } else {
                        foreach ($notificationInfo as $key => $value) {

                        ?>
                            <!--begin::Item-->
                            <div class="d-flex flex-stack py-4">
                                <!--begin::Section-->
                                <div class="d-flex">
                                    <!--begin::Symbol-->
                                    <div class="symbol symbol-35px me-4">
                                        <span class="symbol-label bg-light-primary">
                                            <i class="fa-solid fa-bell fs-2 text-primary">
                                            </i>
                                        </span>
                                    </div>
                                    <!--end::Symbol-->
                                    <!--begin::Title-->
                                    <div class="mb-0 me-2">
                                        <a href="#" class="fs-6 text-gray-800 text-hover-primary fw-bold"><?php echo $value['name']; ?></a>
                                        <div class="text-gray-500 fs-7"><?php echo $value['content']; ?></div>
                                    </div>
                                    <!--end::Title-->
                                </div>
                                <!--end::Section-->
                                <!--begin::Label-->
                                <span class="badge badge-light fs-8"><?php $timeDifference->timeDifference($now, $value['created_at']) ?></span>
                                <!--end::Label-->
                            </div>
                            <!--end::Item-->
                    <?php }
                    } ?>

                </div>
                <!--end::Items-->
                <!--begin::View more-->
                <div class="py-3 text-center border-top">
                    <a href="#" class="btn btn-color-gray-600 btn-active-color-primary">Tümünü Gör
                        <i class="ki-duotone ki-arrow-right fs-5">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i></a>
                </div>
                <!--end::View more-->
            </div>
            <!--end::Tab panel-->
            <!--begin::Tab panel-->
            <div class="tab-pane fade show active" id="kt_topbar_notifications_2" role="tabpanel">

                <!--begin::Items-->
                <div class="scroll-y mh-325px my-5 px-8">
                    <?php
                    if (empty($anouncementInfo)) { ?>
                        <!--begin::Wrapper-->
                        <div class="d-flex flex-column px-9">
                            <!--begin::Section-->
                            <div class="pt-10 pb-0">
                                <!--begin::Title-->
                                <h3 class="text-gray-900 text-center fw-bold">Duyuru Yok!</h3>
                                <!--end::Title-->
                                <!--begin::Text-->
                                <div class="text-center text-gray-600 fw-semibold pt-1">Duyuruları buradan görebilirsiniz</div>
                                <!--end::Text-->
                                <!--begin::Action-->
                                <!--<div class="text-center mt-5 mb-9">
                            <a href="#" class="btn btn-sm btn-primary px-6" data-bs-toggle="modal" data-bs-target="#kt_modal_upgrade_plan">Upgrade</a>
                        </div>-->
                                <!--end::Action-->
                            </div>
                            <!--end::Section-->
                            <!--begin::Illustration-->
                            <div class="text-center px-4">
                                <img class="mw-100 mh-200px" alt="image" src="assets/media/illustrations/sketchy-1/1.png" />
                            </div>
                            <!--end::Illustration-->
                        </div>
                        <!--end::Wrapper-->
                        <?php    } else {
                        foreach ($anouncementInfo as $key => $value) {

                        ?>
                            <!--begin::Item-->
                            <div class="d-flex flex-stack py-4">
                                <!--begin::Section-->
                                <div class="d-flex">
                                    <!--begin::Symbol-->
                                    <div class="symbol symbol-35px me-4">
                                        <span class="symbol-label bg-light-primary">
                                            <i class="fa-solid fa-bullhorn fs-2 text-primary">
                                            </i>
                                        </span>
                                    </div>
                                    <!--end::Symbol-->
                                    <!--begin::Title-->
                                    <div class="mb-0 me-2">
                                        <a href="#" class="fs-6 text-gray-800 text-hover-primary fw-bold"><?php echo $value['name']; ?></a>
                                        <div class="text-gray-500 fs-7"><?php echo $value['content']; ?></div>
                                    </div>
                                    <!--end::Title-->
                                </div>
                                <!--end::Section-->
                                <!--begin::Label-->
                                <span class="badge badge-light fs-8"><?php $timeDifference->timeDifference($now, $value['created_at']) ?></span>
                                <!--end::Label-->
                            </div>
                            <!--end::Item-->
                    <?php }
                    } ?>

                </div>
                <!--end::Items-->
                <!--begin::View more-->
                <div class="py-3 text-center border-top">
                    <a href="#" class="btn btn-color-gray-600 btn-active-color-primary">Tümünü Gör
                        <i class="ki-duotone ki-arrow-right fs-5">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i></a>
                </div>
                <!--end::View more-->
            </div>
            <!--end::Tab panel-->
            <!--begin::Tab panel-->
            <?php if ($_SESSION['role'] == 1) { ?>
                <div class="tab-pane fade" id="kt_topbar_notifications_3" role="tabpanel">
                    <!--begin::Items-->
                    <div class="scroll-y mh-325px my-5 px-8">
                        <!--begin::Item-->
                        <div class="d-flex flex-stack py-4">
                            <!--begin::Section-->
                            <div class="d-flex align-items-center me-2">
                                <!--begin::Code-->
                                <span class="w-70px badge badge-light-success me-4">200 OK</span>
                                <!--end::Code-->
                                <!--begin::Title-->
                                <a href="#" class="text-gray-800 text-hover-primary fw-semibold">Yeni Kayıt</a>
                                <!--end::Title-->
                            </div>
                            <!--end::Section-->
                            <!--begin::Label-->
                            <span class="badge badge-light fs-8">Şimdi</span>
                            <!--end::Label-->
                        </div>
                        <!--end::Item-->
                        <!--begin::Item-->
                        <div class="d-flex flex-stack py-4">
                            <!--begin::Section-->
                            <div class="d-flex align-items-center me-2">
                                <!--begin::Code-->
                                <span class="w-70px badge badge-light-danger me-4">500 ERR</span>
                                <!--end::Code-->
                                <!--begin::Title-->
                                <a href="#" class="text-gray-800 text-hover-primary fw-semibold">Yeni Konu</a>
                                <!--end::Title-->
                            </div>
                            <!--end::Section-->
                            <!--begin::Label-->
                            <span class="badge badge-light fs-8">5 saat</span>
                            <!--end::Label-->
                        </div>
                        <!--end::Item-->
                    </div>
                    <!--end::Items-->
                    <!--begin::View more-->
                    <div class="py-3 text-center border-top">
                        <a href="#" class="btn btn-color-gray-600 btn-active-color-primary">Tümünü Gör
                            <i class="ki-duotone ki-arrow-right fs-5">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i></a>
                    </div>
                    <!--end::View more-->
                </div>
            <?php } ?>
            <!--end::Tab panel-->
        </div>
        <!--end::Tab content-->
    </div>
    <!--end::Menu-->
    <!--end::Menu wrapper-->
</div>