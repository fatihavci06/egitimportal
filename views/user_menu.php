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
        <div class="menu-item px-5">
            <a href="includes/logout.inc.php" class="menu-link px-5">Çıkış Yap</a>
        </div>
        <!--end::Menu item-->
    </div>
    <!--end::User account menu-->
    <!--end::Menu wrapper-->
</div>