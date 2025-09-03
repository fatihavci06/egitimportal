<?php
if (!defined('GUARD')) {
    die('Erişim yasak!');
}

include_once "classes/dbh.classes.php";
include_once "classes/announcement.classes.php";
include_once "classes/announcement-view.classes.php";

include_once "classes/notification.classes.php";
include_once "classes/notification-view.classes.php";
include_once "classes/dateformat.classes.php";


$notification = new NotificationManager();
$anouncement = new AnnouncementManager();

$now = new DateTime();

$timeDifference = new DateFormat();

?>
<div class="app-navbar-item me-lg-1">
    <style>
        .notification-fade-in {
            animation: fadeIn 0.5s ease-in;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .notification-new {
            background-color: #f8f9ff;
            border-left: 3px solid #3f4ed4;
        }
    </style>
    <?php

    $notificationInfo = $notification->getNotificationsWithViewStatus($_SESSION['id'], $_SESSION['role'], $_SESSION['class_id'] ?? '');

    $unviewedNotifications = array_filter($notificationInfo, function ($notif) {
        return isset($notif['is_viewed']) && $notif['is_viewed'] == 0;
    });

    $totalNotification = count($unviewedNotifications);


    $anouncementInfo = $anouncement->getAnnouncementsWithViewStatus($_SESSION['id'], $_SESSION['role'], $_SESSION['class_id'] ?? '');

    $unviewedAnnouncements = array_filter($anouncementInfo, function ($annonce) {
        return isset($annonce['is_viewed']) && $annonce['is_viewed'] == 0;
    });

    $totalAnouncement = count($unviewedAnnouncements);

    $totalNumber = $totalAnouncement + $totalNotification;

    ?>
    <div class="btn btn-icon btn-custom btn-color w-35px h-35px w-md-40px h-md-40px position-relative"
        data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-attach="parent"
        data-kt-menu-placement="bottom-end">

        <i class="ki-duotone ki-graph-3 fs-1">
            <span class="path1"></span>
            <span class="path2"></span>
        </i>

        <span
            class="badge bg-danger position-absolute top-0 start-100 translate-middle p-1 px-2 rounded-circle text-white "
            id='total-na'>
            <?php echo $totalNumber; ?>
        </span>
    </div>
    <div class="menu menu-sub menu-sub-dropdown menu-column w-450px w-lg-500px" data-kt-menu="true"
        id="kt_menu_notifications">
        <div class="d-flex flex-column bgi-no-repeat rounded-top"
            style="background-image: url('assets/media/misc/menu-header-bg.jpg'); background-size: cover; background-position: center;">
            <h3 class="text-white fw-semibold px-9 mt-10 mb-6">Bildirim ve Duyurular

                <span class="fs-8 opacity-75 ps-3"><?php echo $totalNumber; ?> bildirim</span>
            </h3>
            <ul class="nav nav-line-tabs nav-line-tabs-2x nav-stretch fw-semibold px-9">
                <li class="nav-item">
                    <a class="nav-link text-white opacity-75 opacity-state-100 pb-4" data-bs-toggle="tab"
                        href="#kt_topbar_notifications_1">Bildirimler (<?php echo $totalNotification ?>)</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white opacity-75 opacity-state-100 pb-4 active" data-bs-toggle="tab"
                        href="#kt_topbar_notifications_2">Duyurular (<?php echo $totalAnouncement ?>)</a>
                </li>
                <?php if ($_SESSION['role'] == 1) { ?>
                    <li class="nav-item">
                        <a class="nav-link text-white opacity-75 opacity-state-100 pb-4" data-bs-toggle="tab"
                            href="#kt_topbar_notifications_3">Kayıtlar</a>
                    </li>
                <?php } ?>
                <?php if ($_SESSION['role'] == 2) { ?>
                    <li class="nav-item">
                        <a class="nav-link text-white opacity-75 opacity-state-100 pb-4" data-bs-toggle="tab"
                            href="#kt_topbar_notifications_4">Koçluk</a>
                    </li>
                <?php } ?>
            </ul>
        </div>
        <div class="tab-content">
            <div class="tab-pane fade" id="kt_topbar_notifications_1" role="tabpanel">

                <div class="scroll-y mh-325px my-5 px-8" id="notifications-container">
                    <?php
                    if (empty($notificationInfo)) { ?>
                        <div class="d-flex flex-column px-9" id="no-notifications">
                            <div class="pt-10 pb-0">
                                <h3 class="text-gray-900 text-center fw-bold">Bildirim Yok!</h3>
                                <div class="text-center text-gray-600 fw-semibold pt-1">Bildirimleri buradan görebilirsiniz
                                </div>
                            </div>
                            <div class="text-center px-4">
                                <img class="mw-100 mh-200px" alt="image" src="assets/media/illustrations/sketchy-1/1.png" />
                            </div>
                        </div>
                    <?php } else {
                        foreach ($notificationInfo as $key => $value) { ?>
                            <div class="d-flex flex-stack py-4" data-notification-id="<?php echo $value['id']; ?>"
                                data-start-date="<?php echo $value['start_date']; ?>">
                                <div class="d-flex">
                                    <div class="symbol symbol-35px me-4">
                                        <span class="symbol-label bg-light-primary">
                                            <i class="fa-solid fa-bell fs-2 text-primary"></i>
                                        </span>
                                    </div>
                                    <div class="mb-0 me-2">
                                        <a href="./bildirim/<?php echo $value['slug']; ?>"
                                            class="fs-6 <?= $value['is_viewed'] ? 'text-gray-400' : 'text-gray-700' ?> text-hover-primary fw-bold">
                                            <?php echo $value['title']; ?>
                                        </a>
                                    </div>
                                </div>
                                <span
                                    class="badge badge-light fs-8 <?= $value['is_viewed'] ? 'text-gray-500' : 'text-gray-700' ?>">
                                    <?php $timeDifference->timeDifference($now, $value['start_date']) ?>
                                </span>
                            </div>
                        <?php }
                    } ?>
                </div>

                <div class="py-3 text-center border-top">
                    <a href="./bildirimler" class="btn btn-color-gray-600 btn-active-color-primary">Tümünü Gör
                        <i class="ki-duotone ki-arrow-right fs-5">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i></a>
                </div>
            </div>
            <div class="tab-pane fade show active" id="kt_topbar_notifications_2" role="tabpanel">

                <div class="scroll-y mh-325px my-5 px-8" id="announcements-container">
                    <?php
                    if (empty($anouncementInfo)) { ?>
                        <div class="d-flex flex-column px-9">
                            <div class="pt-10 pb-0">
                                <h3 class="text-gray-900 text-center fw-bold">Duyuru Yok!</h3>
                                <div class="text-center text-gray-600 fw-semibold pt-1">Duyuruları buradan görebilirsiniz
                                </div>

                                <!--<div class="text-center mt-5 mb-9">
                            <a href="#" class="btn btn-sm btn-primary px-6" data-bs-toggle="modal" data-bs-target="#kt_modal_upgrade_plan">Upgrade</a>
                        </div>-->
                            </div>
                            <div class="text-center px-4">
                                <img class="mw-100 mh-200px" alt="image" src="assets/media/illustrations/sketchy-1/1.png" />
                            </div>
                        </div>
                    <?php } else {
                        foreach ($anouncementInfo as $key => $value) { ?>
                            <div class="d-flex flex-stack py-4 " data-announcement-id="<?php echo $value['id']; ?>"
                                data-start-date="<?php echo $value['start_date']; ?>">
                                <div class="d-flex">
                                    <div class="symbol symbol-35px me-4">
                                        <span class="symbol-label bg-light-primary ">
                                            <i class="fa-solid fa-bullhorn fs-2 text-primary ">
                                            </i>
                                        </span>
                                    </div>
                                    <div class="mb-0 me-2">
                                        <a href="./duyuru/<?php echo $value['slug']; ?>"
                                            class="fs-6 <?= $value['is_viewed'] ? 'text-gray-400' : 'text-gray-700' ?> text-hover-primary fw-bold">
                                            <?php echo $value['title']; ?>
                                        </a>
                                    </div>
                                </div>
                                <span
                                    class="badge badge-light fs-8  <?= $value['is_viewed'] ? 'text-gray-500' : 'text-gray-700' ?>"><?php $timeDifference->timeDifference($now, $value['start_date']) ?></span>
                            </div>
                        <?php }
                    } ?>

                </div>
                <div class="py-3 text-center border-top">
                    <a href="./duyurular" class="btn btn-color-gray-600 btn-active-color-primary">Tümünü Gör
                        <i class="ki-duotone ki-arrow-right fs-5">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i></a>
                </div>
            </div>

            <?php if ($_SESSION['role'] == 1) { ?>
                <div class="tab-pane fade" id="kt_topbar_notifications_3" role="tabpanel">
                    <div class="scroll-y mh-325px my-5 px-8">
                        <div class="d-flex flex-stack py-4">
                            <div class="d-flex align-items-center me-2">
                                <span class="w-70px badge badge-light-success me-4">200 OK</span>
                                <a href="#" class="text-gray-800 text-hover-primary fw-semibold">Yeni Kayıt</a>
                            </div>
                            <span class="badge badge-light fs-8">Şimdi</span>
                        </div>
                        <div class="d-flex flex-stack py-4">
                            <div class="d-flex align-items-center me-2">
                                <span class="w-70px badge badge-light-danger me-4">500 ERR</span>
                                <a href="#" class="text-gray-800 text-hover-primary fw-semibold">Yeni Konu</a>
                            </div>
                            <span class="badge badge-light fs-8">5 saat</span>
                        </div>
                    </div>
                    <div class="py-3 text-center border-top">
                        <a href="#" class="btn btn-color-gray-600 btn-active-color-primary">Tümünü Gör
                            <i class="ki-duotone ki-arrow-right fs-5">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i></a>
                    </div>
                </div>
            <?php } ?>
            <?php if ($_SESSION['role'] == 2) { ?>
                <div class="tab-pane fade" id="kt_topbar_notifications_4" role="tabpanel">
                    <div class="scroll-y mh-325px my-5 px-8">
                        <?php
                        require_once 'classes/ChatCoaching.php';
                        $chat = new ChatCoaching();
                        $conversations = $chat->getUserConversations($_SESSION['id']);
                        if (empty($conversations)) { ?>
                            <div class="d-flex flex-column px-9">
                                <div class="pt-10 pb-0">
                                    <h3 class="text-gray-900 text-center fw-bold">Sohbet Yok!</h3>
                                    <div class="text-center text-gray-600 fw-semibold pt-1"> buradan görebilirsiniz
                                    </div>
                                </div>
                                <div class="text-center px-4">
                                    <img class="mw-100 mh-200px" alt="image" src="assets/media/illustrations/sketchy-1/1.png" />
                                </div>
                            </div>
                        <?php } else {
                            foreach ($conversations as $conv) { ?>
                                <div class="d-flex flex-stack py-4">
                                    <div class="d-flex align-items-center me-2">
                                        <a href="kocluk-sohbet.php?id=<?php echo $conv['id']; ?>"
                                            class="text-gray-800 text-hover-primary fw-semibold">
                                            <?php echo $conv['other_surname'] . ' ' . $conv['other_name']; ?>
                                        </a>
                                    </div>
                                    <span class="badge badge-light fs-8">
                                        <?php $timeDifference->timeDifference($now, $value['updated_at']) ?>
                                    </span>
                                </div>
                            <?php }
                        } ?>
                    </div>
                    <div class="py-3 text-center border-top">
                        <a href="kocluk-sohbet.php" class="btn btn-color-gray-600 btn-active-color-primary">
                            Tümünü Gör
                            <i class="ki-duotone ki-arrow-right fs-5">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                        </a>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</div>

<script>
    class NotificationManager {
        constructor() {
            this.n_container = document.getElementById('notifications-container');
            this.a_container = document.getElementById('announcements-container');
            this.total_na = document.getElementById('total-na');

            this.lastNotificationId = this.getLastId('[data-notification-id]');
            this.lastAnnouncementId = this.getLastId('[data-announcement-id]');

            this.n_apiUrl = 'includes/fetch_notifications.inc.php';
            this.a_apiUrl = 'includes/fetch_announcements.inc.php';

            this.intervalId = null;
            this.refreshInterval = 1 * 60 * 1000;
            this.init();
        }

        init() {
            this.startAutoRefresh();

            // document.addEventListener('visibilitychange', () => {
            //     if (document.visibilityState === 'visible') {
            //     } else {
            //         this.stopAutoRefresh();
            //     }
            // })

        }

        getLastId(selector) {
            const elements = document.querySelectorAll(selector);
            let maxId = 0;

            elements.forEach(element => {
                const id = parseInt(element.getAttribute(selector.replace(/[\[\]']/g, '')));
                if (id > maxId) {
                    maxId = id;
                }
            });

            return maxId;
        }

        getLastTimestamp(selector) {
            const elements = document.querySelectorAll(selector);
            if (elements.length > 0) {
                const firstElement = elements[0];
                return firstElement.getAttribute('data-start-date') || null;
            }
            return null;
        }

        async fetchNewNotifications() {
            try {
                const lastTimestamp = this.getLastTimestamp('[data-notification-id]');
                let url = `${this.n_apiUrl}?last_id=${this.lastNotificationId}`;

                if (lastTimestamp) {
                    url += `&last_timestamp=${encodeURIComponent(lastTimestamp)}`;
                }

                const response = await fetch(url);

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const data = await response.json();

                if (data.success && data.has_new && data.notifications.length > 0) {
                    this.addNewNotifications(data.notifications);
                    this.lastNotificationId = data.last_id;
                    console.log(`Found ${data.new_count} new notifications`);
                }

            } catch (error) {
                console.error('Error fetching notifications:', error);
            }
        }

        async fetchNewAnnouncements() {
            try {
                const lastTimestamp = this.getLastTimestamp('[data-announcement-id]');
                let url = `${this.a_apiUrl}?last_id=${this.lastAnnouncementId}`;

                if (lastTimestamp) {
                    url += `&last_timestamp=${encodeURIComponent(lastTimestamp)}`;
                }

                const response = await fetch(url);

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const data = await response.json();

                if (data.success && data.has_new && data.announcements.length > 0) {
                    this.addNewAnnouncements(data.announcements);
                    this.lastAnnouncementId = data.last_id;
                    console.log(`Found ${data.new_count} new announcements`);
                }

            } catch (error) {
                console.error('Error fetching announcements:', error);
            }
        }

        addNewNotifications(notifications) {
            const noNotificationsDiv = document.getElementById('no-notifications');
            if (noNotificationsDiv) {
                noNotificationsDiv.remove();
            }

            notifications.forEach(notification => {
                const notificationElement = this.createNotificationElement(notification);
                this.n_container.insertBefore(notificationElement, this.n_container.firstChild);
            });
            this.total_na.innerHTML = parseInt(this.total_na.innerHTML || "0", 10) + notifications.length;

            this.showNewContentIndicator('notification', notifications.length);
        }

        addNewAnnouncements(announcements) {
            const noAnnouncementsDiv = document.getElementById('no-announcements');
            if (noAnnouncementsDiv) {
                noAnnouncementsDiv.remove();
            }

            announcements.forEach(announcement => {
                const announcementElement = this.createAnnouncementElement(announcement);
                this.a_container.insertBefore(announcementElement, this.a_container.firstChild);
            });
            this.total_na.innerHTML = parseInt(this.total_na.innerHTML || "0", 10) + announcements.length;

            this.showNewContentIndicator('announcement', announcements.length);
        }

        createNotificationElement(notification) {
            const div = document.createElement('div');
            div.className = 'd-flex flex-stack py-4 notification-fade-in notification-new';
            div.setAttribute('data-notification-id', notification.id);
            div.setAttribute('data-start-date', notification.start_date);

            div.innerHTML = `
            <div class="d-flex">
                <div class="symbol symbol-35px me-4">
                    <span class="symbol-label bg-light-primary">
                        <i class="fa-solid fa-bell fs-2 text-primary"></i>
                    </span>
                </div>
                <div class="mb-0 me-2">
                    <a href="./bildirim/${notification.slug}"
                        class="fs-6 ${notification.is_viewed ? 'text-gray-400' : 'text-gray-700'} text-hover-primary fw-bold">
                        ${this.escapeHtml(notification.title)}
                    </a>
                </div>
            </div>
            <span class="badge badge-light fs-8 ${notification.is_viewed ? 'text-gray-500' : 'text-gray-700'}">
                ${notification.time_diff}
            </span>
        `;

            this.removeNewIndicator(div);
            return div;
        }

        createAnnouncementElement(announcement) {
            const div = document.createElement('div');
            div.className = 'd-flex flex-stack py-4 announcement-fade-in announcement-new';
            div.setAttribute('data-announcement-id', announcement.id);
            div.setAttribute('data-start-date', announcement.start_date);

            div.innerHTML = `
            <div class="d-flex">
                <div class="symbol symbol-35px me-4">
                    <span class="symbol-label bg-light-success">
                        <i class="fa-solid fa-bullhorn fs-2 text-success"></i>
                    </span>
                </div>
                <div class="mb-0 me-2">
                    <a href="./duyuru/${announcement.slug}"
                        class="fs-6 ${announcement.is_viewed ? 'text-gray-400' : 'text-gray-700'} text-hover-primary fw-bold">
                        ${this.escapeHtml(announcement.title)}
                    </a>
                </div>
            </div>
            <span class="badge badge-light fs-8 ${announcement.is_viewed ? 'text-gray-500' : 'text-gray-700'}">
                ${announcement.time_diff}
            </span>
        `;

            this.removeNewIndicator(div);
            return div;
        }

        removeNewIndicator(element) {
            setTimeout(() => {
                element.classList.remove('notification-new', 'announcement-new');
            }, 5000);
        }

        escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        showNewContentIndicator(type, count) {
            const originalTitle = document.title;
            const prefix = type === 'notification' ? 'Bildirim' : 'Duyuru';
            document.title = `(${count} yeni ${prefix.toLowerCase()}) ${originalTitle}`;

            setTimeout(() => {
                document.title = originalTitle;
            }, 3000);
        }

        async refreshContent() {
            await Promise.all([
                this.fetchNewNotifications(),
                this.fetchNewAnnouncements()
            ]);
        }

        startAutoRefresh() {
            if (this.intervalId) return;

            this.intervalId = setInterval(() => {
                this.refreshContent();
            }, this.refreshInterval);

            console.log('Auto-refresh started for notifications and announcements');
        }

        stopAutoRefresh() {
            if (this.intervalId) {
                clearInterval(this.intervalId);
                this.intervalId = null;
                console.log('Auto-refresh stopped');
            }
        }

        destroy() {
            this.stopAutoRefresh();
        }
    }
    document.addEventListener('DOMContentLoaded', function () {
        window.notificationManager = new NotificationManager();
    });


</script>