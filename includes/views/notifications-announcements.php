<?php
if (!defined('GUARD')) {
    die('Erişim yasak!');
}
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


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


// var_dump($totalUnviewedNotifications);
// die();

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
    <div class="btn btn-icon btn-custom btn-color-gray-600 btn-active-color-primary w-35px h-35px w-md-40px h-md-40px"
        data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-attach="parent"
        data-kt-menu-placement="bottom-end">
        <i class="ki-duotone ki-graph-3 fs-1">
            <span class="path1"></span>
            <span class="path2"></span>
        </i>
    </div>
    <div class="menu menu-sub menu-sub-dropdown menu-column w-450px w-lg-500px" data-kt-menu="true"
        id="kt_menu_notifications">
        <div class="d-flex flex-column bgi-no-repeat rounded-top"
            style="background-image: url('assets/media/misc/menu-header-bg.jpg'); background-size: cover; background-position: center;">
            <h3 class="text-white fw-semibold px-9 mt-10 mb-6">Bildirim ve Duyurular
                <?php

                $notificationInfo = $notification->getNotificationsWithViewStatus($_SESSION['id'], $_SESSION['role'], $_SESSION['class_id'] ?? '');

                $unviewedNotifications = array_filter($notificationInfo, function ($notif) {
                    return isset($notif['is_viewed']) && $notif['is_viewed'] == 0;
                });

                $totalNotification = count($unviewedNotifications);


                $anouncementInfo = $anouncement->getAnnouncementsWithViewStatus($_SESSION['id'], $_SESSION['role'], $_SESSION['class_id'] ?? '');

                $unviewedAnnouncements = array_filter($anouncementInfo, function ($annonce) {
                    return isset($annonce['viewed_at']) && $annonce['viewed_at'] == 0;
                });

                $totalAnouncement = count($unviewedAnnouncements);

                $totalNumber = $totalAnouncement + $totalNotification;

                ?>
                <span class="fs-8 opacity-75 ps-3"><?php //echo $totalNumber; ?> bildirim</span>
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
                <div class="scroll-y mh-325px my-5 px-8">
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
                            <div class="d-flex flex-stack py-4 ">
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
                                        <a href="kocluk-sohbet.php?id=<?php echo $conv['id']; ?>" class="text-gray-800 text-hover-primary fw-semibold">
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
            this.container = document.getElementById('notifications-container');
            this.lastNotificationId = this.getLastNotificationId();
            this.apiUrl = 'includes/fetch_notifications.inc.php';
            this.intervalId = null;
            this.refreshInterval = 5 * 60 * 1000;

            this.init();
        }

        init() {
            this.startAutoRefresh();

            document.addEventListener('visibilitychange', () => {
                if (document.hidden) {
                    this.stopAutoRefresh();
                } else {
                    this.startAutoRefresh();
                }
            });
        }

        getLastNotificationId() {
            const notifications = document.querySelectorAll('[data-notification-id]');
            let maxId = 0;

            notifications.forEach(notification => {
                const id = parseInt(notification.getAttribute('data-notification-id'));
                if (id > maxId) {
                    maxId = id;
                }
            });

            return maxId;
        }

        getLastTimestamp() {
            const notifications = document.querySelectorAll('[data-notification-id]');
            if (notifications.length > 0) {
                const firstNotification = notifications[0];
                return firstNotification.getAttribute('data-start-date') || null;
            }
            return null;
        }

        async fetchNewNotifications() {
            try {
                const lastTimestamp = this.getLastTimestamp();
                let url = `${this.apiUrl}?last_id=${this.lastNotificationId}`;

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

        addNewNotifications(notifications) {
            // Remove "no notifications" message if it exists
            const noNotificationsDiv = document.getElementById('no-notifications');
            if (noNotificationsDiv) {
                noNotificationsDiv.remove();
            }

            // Add new notifications to the top of the list
            notifications.forEach(notification => {
                const notificationElement = this.createNotificationElement(notification);
                this.container.insertBefore(notificationElement, this.container.firstChild);
            });

            // Optional: Show a subtle notification indicator
            this.showNewNotificationIndicator(notifications.length);
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

            // Remove the "new" styling after a few seconds
            setTimeout(() => {
                div.classList.remove('notification-new');
            }, 5000);

            return div;
        }

        escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        showNewNotificationIndicator(count) {
            // Optional: You can add a toast notification or update document title
            // For example, update the document title temporarily
            const originalTitle = document.title;
            document.title = `(${count}) ${originalTitle}`;

            setTimeout(() => {
                document.title = originalTitle;
            }, 3000);
        }

        startAutoRefresh() {
            // Clear any existing interval
            this.stopAutoRefresh();

            // Set up new interval
            this.intervalId = setInterval(() => {
                this.fetchNewNotifications();
            }, this.refreshInterval);

            console.log('Notification auto-refresh started ');
        }

        stopAutoRefresh() {
            if (this.intervalId) {
                clearInterval(this.intervalId);
                this.intervalId = null;
                console.log('Notification auto-refresh stopped');
            }
        }

    }

    document.addEventListener('DOMContentLoaded', function () {
        window.notificationManager = new NotificationManager();
    });


</script>