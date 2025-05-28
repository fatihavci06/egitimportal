<?php
if (!defined('GUARD')) {
    die('Erişim yasak!');
}
?>
<base href="http://localhost/lineup_campus/" />
<div class="app-navbar-item ms-3 ms-lg-4 me-lg-6" id="kt_header_user_menu_toggle">
    <!--begin::Menu wrapper-->
    <div class="cursor-pointer symbol symbol-30px symbol-lg-40px" data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-attach="parent" data-kt-menu-placement="bottom-end">
        <?php if ($_SESSION['photo'] == "") { ?>
            <img src="assets/media/avatars/blank.png" alt="user" />
        <?php } else { ?>
            <img src="assets/media/profile/<?php echo $_SESSION['photo']; ?>" alt="user" />
        <?php } ?>
    </div>
    <!--begin::User account menu-->
    <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg menu-state-color fw-semibold py-4 fs-6 w-275px" data-kt-menu="true">
        <!--begin::Menu item-->
        <div class="menu-item px-3">
            <div class="menu-content d-flex align-items-center px-3">
                <!--begin::Avatar-->
                <div class="symbol symbol-50px me-5">
                    <?php if ($_SESSION['photo'] == "") { ?>
                        <img alt="Logo" src="assets/media/avatars/blank.png" alt="user" />
                    <?php } else { ?>
                        <img alt="Logo" src="assets/media/profile/<?php echo $_SESSION['photo']; ?>" />
                    <?php } ?>
                </div>
                <!--end::Avatar-->
                <!--begin::Username-->
                <div class="d-flex flex-column">
                    <div class="fw-bold d-flex align-items-center fs-5"><?php echo $_SESSION['name']; ?>
                        <!--<span class="badge badge-light-success fw-bold fs-8 px-2 py-1 ms-2">Pro</span>-->
                    </div>
                    <a href="#" class="fw-semibold text-muted text-hover-primary fs-7"><?php echo $_SESSION['email']; ?></a>
                </div>
                <!--end::Username-->
            </div>
        </div>
        <!--end::Menu item-->
        <!--begin::Menu separator-->
        <div class="separator my-2"></div>
        <!--end::Menu separator-->
        <!--begin::Menu item-->
        <div class="menu-item px-5">
            <a href="profilim" class="menu-link px-5">Profilim</a>
        </div>
        <!--end::Menu item-->
        <!--begin::Menu item-->
        <!--<div class="menu-item px-5">
            <a href="apps/projects/list.html" class="menu-link px-5">
                <span class="menu-text">My Projects</span>
                <span class="menu-badge">
                    <span class="badge badge-light-danger badge-circle fw-bold fs-7">3</span>
                </span>
            </a>
        </div>-->
        <!--end::Menu item-->
        <!--begin::Menu item-->
        <!--<div class="menu-item px-5" data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-placement="left-start" data-kt-menu-offset="-15px, 0">
            <a href="#" class="menu-link px-5">
                <span class="menu-title">My Subscription</span>
                <span class="menu-arrow"></span>
            </a>-->
        <!--begin::Menu sub-->
        <!--<div class="menu-sub menu-sub-dropdown w-175px py-4">-->
        <!--begin::Menu item-->
        <!--<div class="menu-item px-3">
                    <a href="account/referrals.html" class="menu-link px-5">Referrals</a>
                </div>-->
        <!--end::Menu item-->
        <!--begin::Menu item-->
        <!--<div class="menu-item px-3">
                    <a href="account/billing.html" class="menu-link px-5">Billing</a>
                </div>-->
        <!--end::Menu item-->
        <!--begin::Menu item-->
        <!--<div class="menu-item px-3">
                    <a href="account/statements.html" class="menu-link px-5">Payments</a>
                </div>-->
        <!--end::Menu item-->
        <!--begin::Menu item-->
        <!--<div class="menu-item px-3">
                    <a href="account/statements.html" class="menu-link d-flex flex-stack px-5">Statements
                        <span class="ms-2 lh-0" data-bs-toggle="tooltip" title="View your statements">
                            <i class="ki-duotone ki-information-5 fs-5">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                            </i>
                        </span></a>
                </div>-->
        <!--end::Menu item-->
        <!--begin::Menu separator-->
        <!--<div class="separator my-2"></div>-->
        <!--end::Menu separator-->
        <!--begin::Menu item-->
        <!--<div class="menu-item px-3">
                    <div class="menu-content px-3">
                        <label class="form-check form-switch form-check-custom form-check-solid">
                            <input class="form-check-input w-30px h-20px" type="checkbox" value="1" checked="checked" name="notifications" />
                            <span class="form-check-label text-muted fs-7">Notifications</span>
                        </label>
                    </div>
                </div>-->
        <!--end::Menu item-->
        <!--</div>-->
        <!--end::Menu sub-->
        <!--</div>-->
        <!--end::Menu item-->
        <!--begin::Menu item-->
        <!--<div class="menu-item px-5">
            <a href="account/statements.html" class="menu-link px-5">My Statements</a>
        </div>-->
        <!--end::Menu item-->
        <!--begin::Menu separator-->
        <!--<div class="separator my-2"></div>-->
        <!--end::Menu separator-->
        <!--begin::Menu item-->
        <div class="menu-item px-5" data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-placement="left-start" data-kt-menu-offset="-15px, 0">
            <a href="#" class="menu-link px-5">
                <span class="menu-title position-relative">Tema
                    <span class="ms-5 position-absolute translate-middle-y top-50 end-0">
                        <i class="ki-duotone ki-night-day theme-light-show fs-2">
                            <span class="path1"></span>
                            <span class="path2"></span>
                            <span class="path3"></span>
                            <span class="path4"></span>
                            <span class="path5"></span>
                            <span class="path6"></span>
                            <span class="path7"></span>
                            <span class="path8"></span>
                            <span class="path9"></span>
                            <span class="path10"></span>
                        </i>
                        <i class="ki-duotone ki-moon theme-dark-show fs-2">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                    </span></span>
            </a>
            <!--begin::Menu-->
            <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-title-gray-700 menu-icon-gray-500 menu-active-bg menu-state-color fw-semibold py-4 fs-base w-150px" data-kt-menu="true" data-kt-element="theme-mode-menu">
                <!--begin::Menu item-->
                <div class="menu-item px-3 my-0">
                    <a href="#" class="menu-link px-3 py-2" data-kt-element="mode" data-kt-value="light">
                        <span class="menu-icon" data-kt-element="icon">
                            <i class="ki-duotone ki-night-day fs-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                                <span class="path4"></span>
                                <span class="path5"></span>
                                <span class="path6"></span>
                                <span class="path7"></span>
                                <span class="path8"></span>
                                <span class="path9"></span>
                                <span class="path10"></span>
                            </i>
                        </span>
                        <span class="menu-title">Açık</span>
                    </a>
                </div>
                <!--end::Menu item-->
                <!--begin::Menu item-->
                <div class="menu-item px-3 my-0">
                    <a href="#" class="menu-link px-3 py-2" data-kt-element="mode" data-kt-value="dark">
                        <span class="menu-icon" data-kt-element="icon">
                            <i class="ki-duotone ki-moon fs-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                        </span>
                        <span class="menu-title">Koyu</span>
                    </a>
                </div>
                <!--end::Menu item-->
                <!--begin::Menu item-->
                <div class="menu-item px-3 my-0">
                    <a href="#" class="menu-link px-3 py-2" data-kt-element="mode" data-kt-value="system">
                        <span class="menu-icon" data-kt-element="icon">
                            <i class="ki-duotone ki-screen fs-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                                <span class="path4"></span>
                            </i>
                        </span>
                        <span class="menu-title">Sistem</span>
                    </a>
                </div>
                <!--end::Menu item-->
            </div>
            <!--end::Menu-->
        </div>
        <!--end::Menu item-->
        <!--begin::Menu item-->
        <div class="menu-item px-5 my-1">
            <a href="#" class="menu-link px-5">Hesap Ayarları</a>
        </div>
        <!--end::Menu item-->
        <!--begin::Menu item-->
        <div class="menu-item px-5">
            <a href="includes/logout.inc.php" class="menu-link px-5">Çıkış Yap</a>
        </div>
        <!--end::Menu item-->
    </div>
    <!--end::User account menu-->
    <!--end::Menu wrapper-->
</div>