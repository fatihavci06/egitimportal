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

                                <div id="kt_app_toolbar" class="app-toolbar pt-5">
                                    <!--begin::Toolbar container-->
                                    <div id="kt_app_toolbar_container"
                                        class="app-container container-fluid d-flex align-items-stretch">
                                        <!--begin::Toolbar wrapper-->
                                        <div class="app-toolbar-wrapper d-flex flex-stack flex-wrap gap-4 w-100">
                                            <div class="page-title d-flex flex-column gap-1 me-3 mb-2">
                                                <ul class="breadcrumb breadcrumb-separatorless fw-semibold mb-6">
                                                    <li class="breadcrumb-item text-gray-700 fw-bold lh-1">
                                                        <a href="index.html" class="text-gray-500 text-hover-primary">
                                                            <i class="ki-duotone ki-home fs-3 text-gray-500 me-n1"></i>
                                                        </a>
                                                    </li>
                                                    <li class="breadcrumb-item">
                                                        <i class="ki-duotone ki-right fs-4 text-gray-700 mx-n1"></i>
                                                    </li>

                                                    <li class="breadcrumb-item text-gray-700 fw-bold lh-1">refewfew
                                                    </li>
                                                </ul>
                                                <h1
                                                    class="page-heading d-flex flex-column justify-content-center text-gray-900 fw-bolder fs-1 lh-0">
                                                    frekfewkop
                                                </h1>
                                            </div>
                                        </div>
                                    </div>
                                </div> <!--end::Toolbar-->
                                <div id="kt_app_content" class="app-content flex-column-fluid">
                                    <div id="kt_app_content_container" class="app-container container-fluid">

                                        jifewjoifewjoi

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
        /* Chat Message Styles */
        .message-bubble {
            margin-bottom: 15px;
            max-width: 70%;
        }

        .message-bubble.own {
            margin-left: auto;
            margin-right: 0;
        }

        .message-bubble:not(.own) {
            margin-left: 0;
            margin-right: auto;
        }

        .message-content {
            background-color: #f1f3f4;
            padding: 12px 16px;
            border-radius: 18px;
            word-wrap: break-word;
            position: relative;
        }

        .message-bubble.own .message-content {
            background-color: #007bff;
            color: white;
        }

        .message-time {
            font-size: 11px;
            color: #6c757d;
            margin-top: 4px;
            padding: 0 4px;
        }

        .message-bubble.own .message-time {
            color: #dee2e6;
        }

        /* File Message Styles */
        .file-message {
            background-color: #f8f9fa !important;
            border: 1px solid #e9ecef;
            border-radius: 12px;
            padding: 12px;
        }

        .message-bubble.own .file-message {
            background-color: rgba(255, 255, 255, 0.1) !important;
            border-color: rgba(255, 255, 255, 0.2);
            color: white;
        }

        .file-image-preview img {
            border-radius: 8px;
            max-width: 100%;
            height: auto;
            cursor: pointer;
            transition: transform 0.2s ease;
        }

        .file-image-preview img:hover {
            transform: scale(1.02);
        }

        /* File Preview Area */
        #filePreviewArea {
            border: 2px dashed #dee2e6;
            border-radius: 8px;
            background-color: #f8f9fa;
        }

        /* Conversation Item Styles */
        .conversation-item {
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 8px;
            cursor: pointer;
            transition: all 0.2s ease;
            border: 1px solid transparent;
        }

        .conversation-item:hover {
            background-color: #f8f9fa;
            border-color: #e9ecef;
        }

        .conversation-item.active {
            background-color: #e3f2fd;
            border-color: #2196f3;
        }

        .unread-badge {
            background-color: #dc3545;
            color: white;
            font-size: 12px;
            padding: 2px 8px;
            border-radius: 12px;
            font-weight: bold;
        }

        /* Contact Item Styles */
        .contact-item {
            cursor: pointer;
            transition: background-color 0.2s ease;
            border: none !important;
            padding: 12px 16px;
        }

        .contact-item:hover {
            background-color: #f8f9fa;
        }

        /* Scrollbar Styles */
        .scroll-y::-webkit-scrollbar {
            width: 6px;
        }

        .scroll-y::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 3px;
        }

        .scroll-y::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 3px;
        }

        .scroll-y::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }

        /* Messages Container */
        #messagesContainer {
            height: 400px;
            overflow-y: auto;
            padding: 20px;
            background-color: #ffffff;
        }

        /* Input Area */
        #messageInput {
            border: none;
            outline: none;
            resize: none;
            background: transparent;
        }

        #messageInput:focus {
            box-shadow: none;
        }

        /* Button Styles */
        .btn-icon {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
        }

        /* Modal Styles */
        .modal-dialog-centered {
            display: flex;
            align-items: center;
            min-height: calc(100% - 1rem);
        }

        /* Responsive Styles */
        @media (max-width: 768px) {
            .message-bubble {
                max-width: 85%;
            }

            #messagesContainer {
                height: 300px;
            }

            .card-footer {
                padding: 12px;
            }

            .d-flex.align-items-center {
                flex-wrap: wrap;
                gap: 8px;
            }
        }

        /* Loading Spinner */
        .spinner-border-sm {
            width: 1rem;
            height: 1rem;
        }

        /* File type icons colors */
        .ki-duotone.text-primary {
            color: #007bff !important;
        }

        /* Animation for new messages */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .message-bubble {
            animation: fadeIn 0.3s ease;
        }

        /* Drag and drop styles */
        .drag-over {
            border-color: #007bff !important;
            background-color: #e3f2fd !important;
        }

        /* File upload button hover effect */
        .btn-icon:hover {
            transform: scale(1.05);
            transition: transform 0.2s ease;
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

        function startConversation(userId, username, photo) {
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