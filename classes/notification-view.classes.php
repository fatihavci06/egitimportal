<?php

include_once "dateformat.classes.php";
include_once "notification.classes.php";
class ShowNotification extends NotificationManager
{

    public function getNotificationList()
    {


        $notificationInfo = $this->getAllNotifications();


        $dateFormat = new DateFormat();

        foreach ($notificationInfo as $key => $value) {


            if ($value['target_type'] == "all") {
                $toWhom = "Herkese";
            } elseif ($value['target_type'] == "roles") {
                $toWhom = $this->getnotificationsRole($value['targets'][0]['value']);
            }

            if ($value['target_type'] == "classes") {
                $toWhom = $this->getnotificationsClass($value['targets'][0]['value']);
            } elseif ($value['target_type'] == "lessons") {
                $toWhom = "Ders - " . $this->getnotificationsLesson($value['targets'][0]['value']);
            } elseif ($value['target_type'] == "units") {
                $toWhom = "Ünite - " . $this->getnotificationsUnit($value['targets'][0]['value']);
            } elseif ($value['target_type'] == "topics") {
                $toWhom = "Konu - " . $this->getnotificationsTopic($value['targets'][0]['value']);
            } elseif ($value['target_type'] == "subtopics") {
                @$toWhom = "Altkonu - " . $this->getnotificationsSubtopic($value['targets'][0]['value']);
            }

            $active_status = '<span class="badge badge-light-success">Aktif</span>';
            if (!$value['is_active']) {
                $active_status = '<span class="badge badge-light-danger">Pasif</span>';
            }

            $alter_button = $value['is_active'] ? "Pasif Yap" : "Aktif Yap";
            $notificationList = '
                    <tr id="' . $value['id'] . '">
                        <td>
                            <div class="form-check form-check-sm form-check-custom form-check-solid">
                                <input class="form-check-input" type="checkbox" value="1" />
                            </div>
                        </td>
                        <td>
                            <a href="./bildirim/' . $value['slug'] . '" class="text-gray-800 text-hover-primary mb-1">' . $value['title'] . '</a>
                        </td>
                        <td>
                            <span class="symbol symbol-10px me-2">

                            ' . $active_status . '
                            </span>
                        </td>
                        <td>
                        ' . (strlen($value['content']) > 40 ? substr($value['content'], 0, 40) . '...' : $value['content']) . '
                        </td>
                        <td>
                            ' . $toWhom . '
                        </td>
                        <td>' . $dateFormat->changeDate($value['start_date']) . '</td>
                        <td>' . $dateFormat->changeDate($value['expire_date']) . '</td>

                        <td class="text-end">
                            <a href="#" class="btn btn-sm btn-light btn-flex btn-center btn-active-light-primary"
                                data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">İşlemler
                                <i class="ki-duotone ki-down fs-5 ms-1"></i></a>
                            <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4"
                                data-kt-menu="true">
                                <div class="menu-item px-3">
                                    <a href="./bildirim-detay/' . $value['slug'] . '" class="menu-link px-3">Görüntüle</a>
                                </div>
                                <div class="menu-item px-3">
                                    <a href="#" class="menu-link px-3" data-kt-customer-table-filter="delete_row">' . $alter_button . '</a>
                                </div>
                            </div>
                        </td>
                    </tr>
                ';
            echo $notificationList;
        }
    }

    public function getNotificationForStudentList($role, $class_id)
    {

        $notificationInfo = $this->getNotificationsWithViewStatus($_SESSION['id'], $role, $class_id);

        $dateFormat = new DateFormat();

        foreach ($notificationInfo as $key => $value) {
            $view = "Görüntülenmedi";
            $style = "text-gray-700";


            $toWhom = "-";
            if ($value['target_type'] == "all") {
                $toWhom = "Herkese";
            } elseif ($value['target_type'] == "roles") {
                $toWhom = "Tüm Öğrencilere";
            } elseif ($value['target_type'] == "classes") {
                $toWhom = "Sınıfıma";
            } elseif ($value['target_type'] == "lessons") {
                $toWhom = "Ders";
            } elseif ($value['target_type'] == "units") {
                $toWhom = "Ünite";
            } elseif ($value['target_type'] == "topics") {
                $toWhom = "Konu";
            } elseif ($value['target_type'] == "subtopics") {
                $toWhom = "Altkonu";
            }


            if ($value["is_viewed"] == 1) {
                $view = "Görüntülendi";
                $style = "text-gray-500";
            }
            $notificationList = '
                    <tr class="' . $style . '">
                        <td>
                            <a href="./bildirim/' . $value['slug'] . '" class=" ' . $style . ' text-hover-primary mb-1">' . $value['title'] . '</a>
                        </td>
                        <td>
                        ' . (strlen($value['content']) > 40 ? substr($value['content'], 0, 40) . '...' : $value['content']) . '
                        </td>
                        <td>' . $dateFormat->changeDate($value['start_date']) . '</td>
                        <td>
                            ' . $view . '
                        </td>
                    </tr>
                ';
            echo $notificationList;
        }
    }

    public function getNotificationForCoordinatorsList($role)
    {

        $notificationInfo = $this->getNotificationsWithViewStatusCoord($_SESSION['id'], $role);

        $dateFormat = new DateFormat();

        foreach ($notificationInfo as $key => $value) {
            $view = "Görüntülenmedi";
            $style = "text-gray-700";


            $toWhom = "-";
            if ($value['target_type'] == "all") {
                $toWhom = "Herkese";
            } elseif ($value['target_type'] == "roles") {
                $toWhom = "Tüm Öğrencilere";
            } elseif ($value['target_type'] == "classes") {
                $toWhom = "Sınıfıma";
            } elseif ($value['target_type'] == "lessons") {
                $toWhom = "Ders";
            } elseif ($value['target_type'] == "units") {
                $toWhom = "Ünite";
            } elseif ($value['target_type'] == "topics") {
                $toWhom = "Konu";
            } elseif ($value['target_type'] == "subtopics") {
                $toWhom = "Altkonu";
            }


            if ($value["is_viewed"] == 1) {
                $view = "Görüntülendi";
                $style = "text-gray-500";
            }
            $notificationList = '
                    <tr class="' . $style . '">
                        <td>
                            <a href="./bildirim/' . $value['slug'] . '" class=" ' . $style . ' text-hover-primary mb-1">' . $value['title'] . '</a>
                        </td>
                        <td>
                        ' . (strlen($value['content']) > 40 ? substr($value['content'], 0, 40) . '...' : $value['content']) . '
                        </td>
                        <td>
                            ' . $toWhom . '
                        </td>
                        <td>' . $dateFormat->changeDate($value['start_date']) . '</td>
                        <td>
                            ' . $view . '
                        </td>
                    </tr>
                ';
            echo $notificationList;
        }
    }

    public function getNotificaionStats($id)
    {

        $viewers = (new NotificationManager())->getNotificationViewers($id);
        $dateFormat = new DateFormat();
        $html = "";

        foreach ($viewers as $viewer) {
            $fullName = htmlspecialchars($viewer['full_name'] ?? ($viewer['name'] . ' ' . $viewer['surname']));
            $html .= '
            <tr>
                <td>' . $fullName . '</td>
                <td>' . htmlspecialchars($viewer['role_name']) . '</td>
                <td>' . $dateFormat->changeDate($viewer['viewed_at'] ?? 'N/A') . '</td>
            </tr>    ';
        }


        echo $html;
    }

    public function showOneNotification($slug)
    {

        $notificationInfo = $this->getNotificationBySlug($slug);
        $dateFormat = new DateFormat();

        $toWhom = "-";
        if ($notificationInfo['target_type'] == "all") {
            $toWhom = "Herkese";
        } elseif ($notificationInfo['target_type'] == "roles") {
            $toWhom = "Tüm Öğrencilere";
        } elseif ($notificationInfo['target_type'] == "classes") {
            $toWhom = "Sınıfıma";
        } elseif ($notificationInfo['target_type'] == "lessons") {
            $toWhom = "Ders";
        } elseif ($notificationInfo['target_type'] == "units") {
            $toWhom = "Ünite";
        } elseif ($notificationInfo['target_type'] == "topics") {
            $toWhom = "Konu";
        } elseif ($notificationInfo['target_type'] == "subtopics") {
            $toWhom = "Altkonu";
        }


        $viewToWho = ' ';
        if (($_SESSION['role'] == 1) or ($_SESSION['role'] == 3)) {

            $viewToWho = '<div class="d-flex align-items-center me-5 mb-2">
                    <span class="svg-icon svg-icon-4 me-1">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <path opacity="0.3" d="M22 12C22 17.5 17.5 22 12 22C6.5 22 2 17.5 2 12C2 6.5 6.5 2 12 2C17.5 2 22 6.5 22 12ZM12 7C10.3 7 9 8.3 9 10C9 11.7 10.3 13 12 13C13.7 13 15 11.7 15 10C15 8.3 13.7 7 12 7Z" fill="currentColor"/>
                            <path d="M12 22C14.6 22 17 21 18.7 19.4C17.9 16.9 15.2 15 12 15C8.8 15 6.09999 16.9 5.29999 19.4C6.99999 21 9.4 22 12 22Z" fill="currentColor"/>
                        </svg>
                    </span>
                    <span class="text-gray-600 fw-bold">' . $toWhom . '</span>
                </div> ';
        }

        $notification = '
                <div class="mb-2">
                    <p class="text-gray-800 fs-4">
                    ' . $notificationInfo['content'] . '
                    </p>
                </div>
                
                <div class="d-flex flex-wrap align-items-center">

                    ' . $viewToWho . '
                    
                    <div class="d-flex align-items-center me-5 mb-2">
                        <span class="svg-icon svg-icon-4 me-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <path opacity="0.3" d="M21 22H3C2.4 22 2 21.6 2 21V5C2 4.4 2.4 4 3 4H21C21.6 4 22 4.4 22 5V21C22 21.6 21.6 22 21 22Z" fill="currentColor"/>
                                <path d="M6 6C5.4 6 5 5.6 5 5V3C5 2.4 5.4 2 6 2C6.6 2 7 2.4 7 3V5C7 5.6 6.6 6 6 6ZM11 5V3C11 2.4 10.6 2 10 2C9.4 2 9 2.4 9 3V5C9 5.6 9.4 6 10 6C10.6 6 11 5.6 11 5ZM15 5V3C15 2.4 14.6 2 14 2C13.4 2 13 2.4 13 3V5C13 5.6 13.4 6 14 6C14.6 6 15 5.6 15 5ZM19 5V3C19 2.4 18.6 2 18 2C17.4 2 17 2.4 17 3V5C17 5.6 17.4 6 18 6C18.6 6 19 5.6 19 5Z" fill="currentColor"/>
                                <path d="M8.8 13.1C9.2 13.1 9.5 13 9.7 12.8C9.9 12.6 10.1 12.3 10.1 11.9C10.1 11.6 10 11.3 9.8 11.1C9.6 10.9 9.3 10.8 9 10.8C8.8 10.8 8.59999 10.8 8.39999 10.9C8.19999 11 8.1 11.1 8 11.2C7.9 11.3 7.8 11.4 7.7 11.6C7.6 11.8 7.5 11.9 7.5 12.1C7.5 12.2 7.4 12.2 7.3 12.3C7.2 12.4 7.09999 12.4 6.89999 12.4C6.69999 12.4 6.6 12.3 6.5 12.2C6.4 12.1 6.3 11.9 6.3 11.7C6.3 11.5 6.4 11.3 6.5 11.1C6.6 10.9 6.7 10.7 6.9 10.5C7.1 10.3 7.3 10.2 7.5 10.1C7.7 10 7.9 9.90002 8.2 9.90002C8.5 9.90002 8.7 9.90002 8.9 10C9.1 10.1 9.3 10.2 9.4 10.3C9.5 10.4 9.6 10.6 9.7 10.8C9.8 11 9.8 11.1 9.8 11.3C9.8 11.4 9.7 11.5 9.6 11.6C9.5 11.7 9.4 11.7 9.3 11.7C9.2 11.7 9.09999 11.6 9.09999 11.5C9.09999 11.4 9.09999 11.3 9.09999 11.2C9.09999 11.1 9 11 8.9 10.9C8.8 10.8 8.7 10.7 8.5 10.7C8.3 10.7 8.2 10.7 8 10.8C7.9 10.9 7.8 11 7.7 11.1C7.6 11.2 7.5 11.3 7.5 11.5C7.5 11.7 7.6 11.8 7.7 11.9C7.8 12 8 12.1 8.2 12.1C8.3 12.1 8.5 12.1 8.7 12C8.8 11.9 8.9 11.8 9 11.7C9.1 11.6 9.2 11.4 9.2 11.3C9.2 11.2 9.2 11.1 9.3 11.1C9.4 11 9.5 10.9 9.6 10.9C9.7 10.9 9.8 11 9.8 11.1C9.8 11.2 9.8 11.3 9.8 11.5C9.8 11.7 9.7 11.9 9.6 12.1C9.5 12.3 9.3 12.4 9.1 12.5C8.9 12.6 8.7 12.7 8.4 12.7C8.1 12.7 7.9 12.6 7.7 12.5C7.5 12.4 7.3 12.2 7.2 12.1C7.1 11.9 7 11.7 7 11.5C7 11.3 7 11.1 7.1 10.9C7.2 10.7 7.3 10.5 7.4 10.4C7.5 10.3 7.6 10.2 7.8 10.1C7.9 10 8.1 9.90002 8.3 9.90002C8.5 9.90002 8.7 9.90002 8.8 10C9 10.1 9.1 10.2 9.2 10.3C9.3 10.4 9.4 10.6 9.4 10.8C9.4 10.9 9.4 11 9.3 11.1C9.2 11.2 9.1 11.2 9 11.2C8.9 11.2 8.8 11.1 8.8 11C8.8 10.9 8.8 10.8 8.9 10.7C8.9 10.6 9 10.5 9.1 10.5C9.2 10.5 9.2 10.5 9.3 10.6C9.4 10.7 9.4 10.8 9.4 11C9.4 11.2 9.3 11.3 9.2 11.4C9.1 11.5 8.9 11.6 8.8 11.6C8.7 11.6 8.5 11.6 8.4 11.5C8.3 11.4 8.2 11.3 8.2 11.1C8.2 10.9 8.2 10.8 8.3 10.6C8.4 10.4 8.5 10.3 8.6 10.2C8.7 10.1 8.9 10 9.1 10C9.3 10 9.5 10.1 9.6 10.2C9.7 10.3 9.8 10.5 9.8 10.7C9.8 10.9 9.7 11.1 9.6 11.2C9.5 11.3 9.4 11.4 9.2 11.5C9 11.6 8.8 11.7 8.6 11.7C8.4 11.7 8.2 11.6 8 11.5C7.8 11.4 7.7 11.2 7.6 11C7.5 10.8 7.5 10.6 7.5 10.4C7.5 10.2 7.6 10 7.7 9.8C7.8 9.6 8 9.4 8.2 9.3C8.4 9.2 8.6 9.10002 8.9 9.10002C9.2 9.10002 9.5 9.20002 9.7 9.30002C9.9 9.40002 10.1 9.60002 10.2 9.80002C10.3 10 10.4 10.2 10.4 10.5C10.4 10.8 10.3 11 10.2 11.2C10.1 11.4 9.9 11.6 9.7 11.7C9.5 11.8 9.3 11.9 9 12C8.7 12.1 8.5 12.1 8.2 12.1Z" fill="currentColor"/>
                            </svg>
                        </span>
                        <span class="text-gray-600 fw-bold">' . $dateFormat->changeDate($notificationInfo['start_date']) . '</span>
                    </div>
                    
         
                ';
        if (!$notificationInfo || !is_array($notificationInfo)) {
            echo "Bildrim bulunamadı.";
            return;
        }
        echo $notification;
    }
}

