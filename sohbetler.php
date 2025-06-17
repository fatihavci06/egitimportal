<?php

error_reporting(E_ALL);
ini_set("", 1);
session_start();
define('GUARD', true);
if (!isset($_SESSION['role'])) {
    header("HTTP/1.0 404 Not Found");
    header("Location: 404.php");
    exit();
}
include_once "classes/dbh.classes.php";

include_once "views/pages-head.php";
include_once "classes/dateformat.classes.php";
// $dateFormat = new DateFormat();
// include_once "classes/message.classes.php";
// $user_id = $_SESSION["id"];

?>
<!DOCTYPE html>
<html lang="tr">

</html>

<body id="kt_app_body" data-kt-app-header-fixed="true" data-kt-app-header-fixed-mobile="true"
    data-kt-app-sidebar-enabled="true" data-kt-app-sidebar-fixed="true" data-kt-app-sidebar-hoverable="true"
    data-kt-app-sidebar-push-toolbar="true" data-kt-app-sidebar-push-footer="true" data-kt-app-toolbar-enabled="true"
    data-kt-app-aside-enabled="true" data-kt-app-aside-fixed="true" data-kt-app-aside-push-toolbar="true"
    data-kt-app-aside-push-footer="true" class="app-default">
    <script>
        var defaultThemeMode = "light";
        var themeMode;
        if (document.documentElement) {
            if (document.documentElement.hasAttribute("data-bs-theme-mode")) {
                themeMode = document.documentElement.getAttribute("data-bs-theme-mode");
            } else {
                if (localStorage.getItem("data-bs-theme") !== null) {
                    themeMode = localStorage.getItem("data-bs-theme");
                } else {
                    themeMode = defaultThemeMode;
                }
            }
            if (themeMode === "system") {
                themeMode = window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light";
            }
            document.documentElement.setAttribute("data-bs-theme", themeMode);
        }
    </script>
    <div class="d-flex flex-column flex-root app-root" id="kt_app_root">
        <div class="app-page flex-column flex-column-fluid" id="kt_app_page">
            <!--begin::Header-->
            <?php include_once "views/header.php"; ?>
            <!--end::Header-->
            <div class="d-flex flex-column flex-root app-root" id="kt_app_root">
                <div class="app-page flex-column flex-column-fluid" id="kt_app_page">
                    <!--begin::Header-->
                    <?php include_once "views/header.php"; ?>
                    <!--end::Header-->
                    <div class="app-wrapper flex-column flex-row-fluid" id="kt_app_wrapper">
                        <!--begin::Sidebar-->
                        <?php include_once "views/sidebar.php"; ?>
                        <!--end::Sidebar-->
                        <div class="app-main flex-column flex-row-fluid" id="kt_app_main">
                            <div class="d-flex flex-column flex-column-fluid">
                                <!--begin::Toolbar-->
                                <?php include_once "views/toolbar.php"; ?>
                                <!--end::Toolbar-->
                                <div id="kt_app_content" class="app-content flex-column-fluid">
                                    <div id="kt_app_content_container" class="app-container container-fluid">

                                        <!--begin::Chat Layout-->
                                        <div class="row g-6 g-xl-9">
                                            <!--begin::Conversations List-->
                                            <div class="col-xl-4">
                                                <div class="card card-flush h-xl-100">
                                                    <!--begin::Card header-->
                                                    <div class="card-header pt-7">
                                                        <h3 class="card-title align-items-start flex-column">
                                                            <span
                                                                class="card-label fw-bold text-gray-800">Mesajlar</span>
                                                            <span class="text-gray-500 mt-1 fw-semibold fs-6">Sohbet
                                                                geçmişiniz</span>
                                                        </h3>
                                                        <div class="card-toolbar">
                                                            <button type="button" class="btn btn-sm btn-primary"
                                                                id="openContactsButton" data-bs-toggle="modal"
                                                                data-bs-target="#contactsModal">
                                                                <i class="ki-duotone ki-plus fs-2"></i>
                                                                Yeni Sohbet
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <!--end::Card header-->

                                                    <!--begin::Card body-->
                                                    <div class="card-body pt-5">
                                                        <!--begin::Search-->
                                                        <div class="d-flex align-items-center position-relative mb-5">
                                                            <i
                                                                class="ki-duotone ki-magnifier fs-3 position-absolute ms-3">
                                                                <span class="path1"></span>
                                                                <span class="path2"></span>
                                                            </i>
                                                            <input type="text"
                                                                class="form-control form-control-solid ps-10"
                                                                placeholder="Sohbetlerde ara..."
                                                                id="conversationSearch">
                                                        </div>
                                                        <!--end::Search-->

                                                        <!--begin::Conversations-->
                                                        <div class="scroll-y me-n5 pe-5 h-300px h-xl-auto"
                                                            data-kt-scroll="true"
                                                            data-kt-scroll-activate="{default: false, lg: true}"
                                                            data-kt-scroll-max-height="auto" data-kt-scroll-offset="5px"
                                                            id="conversationsList">
                                                            <div
                                                                class="d-flex justify-content-center align-items-center h-100">
                                                                <div class="text-gray-500">
                                                                    <div class="spinner-border spinner-border-sm me-2"
                                                                        role="status"></div>
                                                                    Sohbetler yükleniyor...
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!--end::Conversations-->
                                                    </div>
                                                    <!--end::Card body-->
                                                </div>
                                            </div>
                                            <!--end::Conversations List-->

                                            <!--begin::Chat Area-->
                                            <div class="col-xl-8">
                                                <div class="card card-flush h-xl-100">
                                                    <!--begin::Card header-->
                                                    <div class="card-header" id="chatHeader" style="display: none;">
                                                        <div class="card-title">
                                                            <div class="d-flex align-items-center">
                                                                <div class="symbol symbol-45px me-3">
                                                                    <img id="currentChatPhoto"
                                                                        src="assets/media/avatars/300-1.jpg" alt=""
                                                                        class="rounded-circle">
                                                                </div>
                                                                <div class="d-flex flex-column">
                                                                    <span
                                                                        class="text-gray-800 text-hover-primary fw-bold fs-4"
                                                                        id="currentChatName">
                                                                        Kullanıcı seçin
                                                                    </span>
                                                                    <span class="text-gray-500 fs-6"
                                                                        id="currentChatStatus"></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="card-toolbar">
                                                            <button type="button" class="btn btn-sm btn-icon btn-light"
                                                                onclick="loadMessages()">
                                                                <i class="ki-duotone ki-arrows-circle fs-2">
                                                                    <span class="path1"></span>
                                                                    <span class="path2"></span>
                                                                </i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <!--end::Card header-->

                                                    <!--begin::Card body-->
                                                    <div class="card-body" id="messagesContainer">
                                                        <div
                                                            class="d-flex justify-content-center align-items-center h-100">
                                                            <div class="text-center">
                                                                <i
                                                                    class="ki-duotone ki-message-text-2 fs-3x text-gray-400 mb-3">
                                                                    <span class="path1"></span>
                                                                    <span class="path2"></span>
                                                                    <span class="path3"></span>
                                                                </i>
                                                                <div class="text-gray-500 fs-5">
                                                                    Sohbet başlatmak için bir kişi seçin
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!--end::Card body-->

                                                    <!--begin::Card footer-->
                                                    <div class="card-footer pt-4" id="messageInputArea"
                                                        style="display: none;">
                                                        <div class="d-flex align-items-center">
                                                            <div class="d-flex align-items-center flex-row-fluid me-3">
                                                                <input class="form-control form-control-flush mb-0"
                                                                    rows="1" placeholder="Mesajınızı yazın..."
                                                                    id="messageInput"
                                                                    onkeypress="handleKeyPress(event)">
                                                            </div>
                                                            <button class="btn btn-primary btn-sm" type="button"
                                                                id="sendBtn" onclick="sendMessage()">
                                                                <i class="ki-duotone ki-send fs-2">
                                                                    <span class="path1"></span>
                                                                    <span class="path2"></span>
                                                                </i>
                                                                Gönder
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <!--end::Card footer-->
                                                </div>
                                            </div>
                                            <!--end::Chat Area-->
                                        </div>
                                        <!--end::Chat Layout-->

                                        <!-- Contacts Modal -->
                                        <div class="modal fade" id="contactsModal" tabindex="-1"
                                            aria-labelledby="contactsModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-scrollable">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="contactsModalLabel">Kişiler</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            id="closeModalButton" aria-label="Close"></button>
                                                    </div>
                                                    <div class="px-5 pt-3 mb-4">
                                                        <input type="text" id="contactSearch"
                                                            class="form-control form-control-solid"
                                                            placeholder="Kişilerde ara...">
                                                    </div>
                                                    <div class="modal-body pt-0 min-h-100px">
                                                        <ul class="list-group" id="contactsList">
                                                            <div class="d-flex justify-content-center">
                                                                <div class="spinner-border spinner-border-sm me-2"
                                                                    role="status"></div>
                                                                Kişiler yükleniyor...
                                                            </div>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <!--begin::Footer-->
                            <?php include_once "views/footer.php"; ?>
                            <!--end::Footer-->
                        </div>
                        <!--begin::aside-->
                        <?php include_once "views/aside.php"; ?>
                        <!--end::aside-->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--begin::Scrolltop-->
    <div id="kt_scrolltop" class="scrolltop" data-kt-scrolltop="true">
        <i class="ki-duotone ki-arrow-up">
            <span class="path1"></span>
            <span class="path2"></span>
        </i>
    </div>
    <!--end::Scrolltop-->
    <!--begin::Javascript-->
    <script>
        var hostUrl = "assets/";
    </script>
    <!--begin::Global Javascript Bundle(mandatory for all pages)-->
    <script src="assets/plugins/global/plugins.bundle.js"></script>
    <script src="assets/js/scripts.bundle.js"></script>
    <style>
        .conversation-item {
            cursor: pointer;
            transition: all 0.15s ease;
            border-radius: 0.475rem;
            margin-bottom: 0.5rem;
            padding: 1rem;
            border: 1px solid transparent;
        }

        .conversation-item:hover {
            background-color: #f1f1f2;
            border-color: #e4e6ea;
        }

        .conversation-item.active {
            background-color: #e8f4f8;
            border-color: #009ef7;
        }

        .message-bubble {
            max-width: 70%;
            margin-bottom: 1rem;
            word-wrap: break-word;
        }

        .message-bubble.own {
            margin-left: auto;
        }

        .message-bubble.own .message-content {
            background-color: #009ef7;
            color: white;
        }

        .message-bubble:not(.own) .message-content {
            background-color: #f1f1f2;
            color: #5e6278;
        }

        .message-content {
            padding: 0.75rem 1rem;
            border-radius: 1rem;
            position: relative;
        }

        .message-time {
            font-size: 0.75rem;
            color: #a1a5b7;
            margin-top: 0.25rem;
        }

        .unread-badge {
            background-color: #f1416c;
            color: white;
            border-radius: 50%;
            padding: 0.125rem 0.375rem;
            font-size: 0.75rem;
            font-weight: 600;
        }

        #messagesContainer {
            height: 400px;
            overflow-y: auto;
            padding: 1rem;
        }

        .contact-item:hover {
            background-color: #f8f9fa;
        }
    </style>


    <!-- <script>
        document.addEventListener("DOMContentLoaded", function () {
            document.querySelectorAll(".contact-item").forEach(function (item) {
                item.addEventListener("click", function () {
                    const url = this.getAttribute("data-url");
                    if (url) {
                        window.location.href = url;
                    }
                });
            });

            const searchInput = document.getElementById("contactSearch");
            const contactItems = document.querySelectorAll("#contactsList .contact-item");

            searchInput.addEventListener("input", function () {
                const query = this.value.toLowerCase();
                contactItems.forEach(function (item) {
                    const name = item.querySelector(".contact-name").textContent.toLowerCase();
                    item.style.display = name.includes(query) ? "" : "none";
                });
            });
        });
    </script> -->

    <!--end::Global Javascript Bundle-->
    <!--begin::Vendors Javascript(used for this page only)-->
    <script src="assets/plugins/custom/datatables/datatables.bundle.js"></script>
    <!--end::Vendors Javascript-->
    <!--begin::Custom Javascript(used for this page only)-->

    <!-- <script src="assets/js/custom/apps/messages/list.js"></script> -->

    <script src="assets/js/widgets.bundle.js"></script>
    <script src="assets/js/custom/widgets.js"></script>

    <script>
        let currentConversationId = null;
        let currentChatUser = null;
        let conversations = [];

        const openContactsButton = document.getElementById('openContactsButton');

        const contactsModal = new bootstrap.Modal(document.getElementById('contactsModal'));

        function showContactsModal() {
            contactsModal.show();
            document.getElementById('contactsModal').querySelector('.btn-close').focus();
        }

        function hideContactsModal() {
            contactsModal.hide();
            openContactsButton.focus();
        }

        document.getElementById('openContactsButton').addEventListener('click', showContactsModal);
        document.getElementById('closeModalButton').addEventListener('click', hideContactsModal);
        document.addEventListener('DOMContentLoaded', function () {
            loadConversations();

            setInterval(loadConversations, 30000);

            document.getElementById('conversationSearch').addEventListener('input', filterConversations);
            document.getElementById('contactSearch').addEventListener('input', filterContacts);
        });

        function loadConversations() {
            fetch('includes/chat_handler.inc.php?action=get_conversations')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        conversations = data.conversations;
                        renderConversations();
                    }
                })
                .catch(error => console.error('Error loading conversations:', error));
        }

        function renderConversations() {
            const list = document.getElementById('conversationsList');

            if (conversations.length === 0) {
                list.innerHTML = `
                    <div class="text-center py-10">
                        <i class="ki-duotone ki-message-text-2 fs-3x text-gray-400 mb-3">
                            <span class="path1"></span>
                            <span class="path2"></span>
                            <span class="path3"></span>
                        </i>
                        <div class="text-gray-500 fs-6">Henüz sohbet yok. Yeni bir sohbet başlatın!</div>
                    </div>
                `;
                return;
            }

            list.innerHTML = conversations.map(conv => `
                <div class="conversation-item ${currentConversationId == conv.id ? 'active' : ''}" 
                     onclick="selectConversation(${conv.id}, '${conv.other_name} ${conv.other_surname}', ${conv.other_user_id},'${conv.other_photo}')">
                    <div class="d-flex align-items-center">
                        <div class="symbol symbol-45px me-3">
                            <img src="assets/media/profile/${conv.other_photo}" alt="${conv.other_username}" class="rounded-circle">
                        </div>
                        <div class="flex-grow-1">
                            <div class="d-flex align-items-center justify-content-between mb-1">
                                <span class="text-gray-900 fw-bold fs-6">${conv.other_name} ${conv.other_surname} </span>
                                ${conv.unread_count > 0 ? `<span class="unread-badge">${conv.unread_count}</span>` : ''}
                            </div>
                            <span class="text-gray-500 fw-semibold fs-7 d-block">
                                ${conv.last_message ? (conv.last_message.length > 30 ? conv.last_message.substring(0, 30) + '...' : conv.last_message) : 'Henüz mesaj yok'}
                            </span>
                        </div>
                    </div>
                </div>
            `).join('');

        }

        function selectConversation(conversationId, userName, userId, photo) {
            currentConversationId = conversationId;
            currentChatUser = userName;

            document.getElementById('currentChatName').textContent = userName;
            document.getElementById('chatHeader').style.display = 'block';
            document.getElementById('messageInputArea').style.display = 'block';
            document.getElementById('currentChatPhoto').src = `assets/media/profile/${photo}`;

            document.querySelectorAll('.conversation-item').forEach(item => {
                item.classList.remove('active');
            });
            if (event) {
                event.target.closest('.conversation-item').classList.add('active');

            }
            loadMessages();
        }

        function loadMessages() {
            if (!currentConversationId) return;

            fetch(`includes/chat_handler.inc.php?action=get_messages&conversation_id=${currentConversationId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        renderMessages(data.messages);
                        loadConversations();
                    }
                })
                .catch(error => console.error('Error loading messages:', error));
        }

        function renderMessages(messages) {
            const container = document.getElementById('messagesContainer');

            if (messages.length === 0) {
                container.innerHTML = `
                    <div class="text-center py-10">
                        <i class="ki-duotone ki-message-text-2 fs-3x text-gray-400 mb-3">
                            <span class="path1"></span>
                            <span class="path2"></span>
                            <span class="path3"></span>
                        </i>
                        <div class="text-gray-500 fs-6">Henüz mesaj yok. Merhaba deyin!</div>
                    </div>
                `;
                return;
            }

            container.innerHTML = messages.map(msg => {
                const isOwn = msg.sender_id == getCurrentUserId();
                const messageTime = new Date(msg.created_at).toLocaleTimeString('tr-TR', {
                    hour: '2-digit',
                    minute: '2-digit'
                });

                return `
                    <div class="message-bubble ${isOwn ? 'own' : ''} d-flex flex-column">
                        <div class="message-content">
                            ${escapeHtml(msg.message)}
                        </div>
                        <div class="message-time ${isOwn ? 'text-end' : ''}">${messageTime}</div>
                    </div>
                `;
            }).join('');

            container.scrollTop = container.scrollHeight;
        }

        function sendMessage() {
            const input = document.getElementById('messageInput');
            const message = input.value.trim();

            if (!message || !currentConversationId) return;

            const sendBtn = document.getElementById('sendBtn');
            sendBtn.disabled = true;
            sendBtn.innerHTML = '<div class="spinner-border spinner-border-sm me-2" role="status"></div>Gönderiliyor...';

            const formData = new FormData();
            formData.append('action', 'send_message');
            formData.append('conversation_id', currentConversationId);
            formData.append('message', message);

            fetch('includes/chat_handler.inc.php', {
                method: 'POST',
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        input.value = '';
                        loadMessages();
                        loadConversations();

                    }
                })
                .catch(error => {
                    console.error('Error sending message:', error);
                    Swal.fire({
                        title: 'Hata!',
                        text: 'Mesaj gönderilirken bir hata oluştu',
                        icon: 'error',
                        confirmButtonText: 'Tamam'
                    });
                })
                .finally(() => {
                    sendBtn.disabled = false;
                    sendBtn.innerHTML = '<i class="ki-duotone ki-send fs-2"><span class="path1"></span><span class="path2"></span></i>Gönder';
                    input.focus();
                });
        }

        function handleKeyPress(event) {
            if (event.key === 'Enter' && !event.shiftKey) {
                event.preventDefault();
                sendMessage();
            }
        }

        document.getElementById('contactsModal').addEventListener('show.bs.modal', function () {
            loadUsers();
        });

        function loadUsers() {
            fetch('includes/get_all_users.inc.php')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        renderUsers(data.users);
                    }
                })
                .catch(error => console.error('Error loading users:', error));
        }

        function renderUsers(users) {
            const list = document.getElementById('contactsList');

            if (users.length === 0) {
                list.innerHTML = `
                    <div class="text-center py-5">
                        <div class="text-gray-500">Başka kullanıcı bulunamadı.</div>
                    </div>
                `;
                return;
            }

            list.innerHTML = users.map(user => `
                <li class="list-group-item contact-item" onclick="startConversation(${user.id}, '${user.username}','${user.photo}')">
                    <div class="d-flex align-items-center">
                        <img src="assets/media/profile/${user.photo}" alt="${user.username}" class="rounded-circle me-3" width="40">
                        <div>
                            <div class="fw-bold">${user.name} ${user.surname}</div>
                        </div>
                    </div>
                </li>
            `).join('');
        }

        function startConversation(userId, username,photo) {
            const formData = new FormData();
            formData.append('action', 'start_conversation');
            formData.append('other_user_id', userId);

            fetch('includes/chat_handler.inc.php', {
                method: 'POST',
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const modal = bootstrap.Modal.getInstance(document.getElementById('contactsModal'));

                        modal.hide();

                        loadConversations();
                        setTimeout(() => {
                            selectConversation(data.conversation_id, username, userId, photo);
                        }, 500);
                    } else {
                        Swal.fire({
                            title: 'Hata!',
                            text: 'Sohbet başlatılamadı: ' + data.error,
                            icon: 'error',
                            confirmButtonText: 'Tamam'
                        });
                    }
                })
                .catch(error => {
                    console.error('Error starting conversation:', error);
                    Swal.fire({
                        title: 'Hata!',
                        text: 'Sohbet başlatılırken bir hata oluştu',
                        icon: 'error',
                        confirmButtonText: 'Tamam'
                    });
                });
        }

        function filterConversations() {
            const searchTerm = document.getElementById('conversationSearch').value.toLowerCase();
            const items = document.querySelectorAll('.conversation-item');

            items.forEach(item => {
                const userName = item.querySelector('.text-gray-900').textContent.toLowerCase();
                const lastMessage = item.querySelector('.text-gray-500').textContent.toLowerCase();

                if (userName.includes(searchTerm) || lastMessage.includes(searchTerm)) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        }

        function filterContacts() {
            const searchTerm = document.getElementById('contactSearch').value.toLowerCase();
            const items = document.querySelectorAll('.contact-item');

            items.forEach(item => {
                const userName = item.querySelector('.fw-bold').textContent.toLowerCase();

                if (userName.includes(searchTerm)) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        }

        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        function getCurrentUserId() {
            return <?php echo $_SESSION['id'] ?? 0; ?>;
        }
    </script>
    <!--end::Custom Javascript-->
    <!--end::Javascript-->
</body>
<!--end::Body-->

</html>