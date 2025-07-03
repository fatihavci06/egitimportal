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
if (($_SESSION['role'] != 1) && ($_SESSION['role'] != 3)) {
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
                                                    <div class="card-header pt-7">
                                                        <h3 class="card-title align-items-start flex-column">
                                                            <span class="card-label fw-bold text-gray-800">Mesajlar
                                                                (Okul)</span>
                                                        </h3>
                                                        <?php
                                                        if ((isset($_SESSION['role'])) && ($_SESSION['role'] == 2)) {
                                                            ?>

                                                            <div class="card-toolbar">
                                                                <button type="button" class="btn btn-sm btn-primary"
                                                                    id="openContactsButton" data-bs-toggle="modal"
                                                                    data-bs-target="#contactsModal">
                                                                    Öğretmenler
                                                                </button>
                                                            </div>
                                                            <?php
                                                        }
                                                        ?>
                                                    </div>

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
                                                </div>
                                            </div>

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
                                                        <!-- File Upload Preview Area -->
                                                        <div id="filePreviewArea" class="mb-3" style="display: none;">
                                                            <div class="d-flex align-items-center p-3 bg-light rounded">
                                                                <i
                                                                    class="ki-duotone ki-document fs-2 text-primary me-3">
                                                                    <span class="path1"></span>
                                                                    <span class="path2"></span>
                                                                </i>
                                                                <div class="flex-grow-1">
                                                                    <div class="fw-bold text-gray-800" id="fileName">
                                                                    </div>
                                                                    <div class="text-gray-500 fs-7" id="fileSize"></div>
                                                                </div>
                                                                <button type="button"
                                                                    class="btn btn-sm btn-icon btn-light"
                                                                    onclick="cancelFileUpload()">
                                                                    <i class="ki-duotone ki-cross fs-2">
                                                                        <span class="path1"></span>
                                                                        <span class="path2"></span>
                                                                    </i>
                                                                </button>
                                                            </div>
                                                        </div>

                                                    </div>
                                                    <!--end::Card footer-->
                                                </div>
                                            </div>
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
            color: #6c757d;
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

    <!--end::Global Javascript Bundle-->
    <!--begin::Vendors Javascript(used for this page only)-->
    <script src="assets/plugins/custom/datatables/datatables.bundle.js"></script>
    <!--end::Vendors Javascript-->
    <!--begin::Custom Javascript(used for this page only)-->

    <script src="assets/js/widgets.bundle.js"></script>
    <script src="assets/js/custom/widgets.js"></script>

    <script>
        let currentConversationId = null;
        let currentChatUser = null;
        let conversations = [];
        let selectedFile = null;

        const contactsModal = new bootstrap.Modal(document.getElementById('contactsModal'));

        function showContactsModal() {
            contactsModal.show();
            document.getElementById('contactsModal').querySelector('.btn-close').focus();
        }

        function hideContactsModal() {
            contactsModal.hide();
        }

        function clickConversationFromURL() {
            const urlParams = new URLSearchParams(window.location.search);
            const id = urlParams.get('id');
            if (id) {
                setTimeout(() => {
                    const element = document.querySelector(`[conv-id="${id}"]`);

                    if (element) {
                        element.click();

                    } else {
                        console.warn(`Element with id "${id}" not found`);
                    }

                }, 100);
            }
        }
        document.getElementById('closeModalButton').addEventListener('click', hideContactsModal);

        document.addEventListener('DOMContentLoaded', async function () {
            try {
                await loadConversations();
                clickConversationFromURL();

                setInterval(loadConversations, 30000);
                document.getElementById('conversationSearch').addEventListener('input', filterConversations);
                document.getElementById('contactSearch').addEventListener('input', filterContacts);
            } catch (error) {
                console.error('Error in initialization:', error);
            }
        });
        async function loadConversations() {
            try {
                const response = await fetch('includes/chat_school_handler.inc.php?action=get_all_conversations');
                const data = await response.json();
                if (data.success) {
                    conversations = data.conversations;
                    renderConversations();
                }
            } catch (error) {
                console.error('Error loading conversations:', error);
            }
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
            conv-id="${conv.id}" onclick="selectConversation(${conv.id}, '${conv.student_name} ${conv.student_surname} ${conv.teacher_name} ${conv.teacher_surname}', ${conv.student_id},'${conv.student_photo}')">
            <div class="d-flex align-items-center">
                <div class="symbol symbol-45px me-3">
                    <img src="assets/media/profile/${conv.student_photo}" alt="${conv.student_username}" class="rounded-circle">
                    <img src="assets/media/profile/${conv.teacher_photo}" alt="${conv.teacher_username}" class="rounded-circle">

                </div>
                <div class="flex-grow-1">
                    <div class="d-flex align-items-center justify-content-between mb-1">
                        <span class="text-gray-900 fw-bold fs-6 student-fullname">${conv.student_name} ${conv.student_surname} </span>

                    </div>
                    <div class="d-flex align-items-center justify-content-between mb-1">
                        <span class="text-gray-900 fw-bold fs-6 teacher-fullname">${conv.teacher_name} ${conv.teacher_surname} </span>
                    </div>
                   <div class="d-flex align-items-center justify-content-between mb-1">
                        <span class="text-gray-500 fw-bold fs-8 teacher-fullname">${conv.className} ${conv.lessonName} </span>
                    </div>

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
            fetch(`includes/chat_school_handler.inc.php?action=get_all_messages&conversation_id=${currentConversationId}`)
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
            let selectedId = messages[0].sender_id;
            container.innerHTML = messages.map(msg => {
                // const isOwn = msg.sender_id == getCurrentUserId();
                const isOwn = msg.sender_id == selectedId;

                const messageTime = new Date(msg.created_at).toLocaleTimeString('tr-TR', {
                    hour: '2-digit',
                    minute: '2-digit'
                });

                // Check if message is a file
                if (msg.file_name) {
                    return renderFileMessage(msg, isOwn, messageTime);
                } else {
                    return renderTextMessage(msg, isOwn, messageTime);
                }
            }).join('');

            container.scrollTop = container.scrollHeight;
        }

        function renderTextMessage(msg, isOwn, messageTime) {
            return `
        <div class="message-bubble ${isOwn ? 'own' : ''} d-flex flex-column">
            <span class="text-gray-500 fs-8 px-2 ${isOwn ? '' : ''}"> ${msg.name} ${msg.surname}</span>
            <div class="message-content">
                ${escapeHtml(msg.message)}
            </div>
            <div class="message-time ${isOwn ? 'text-end' : ''}">${messageTime}</div>
        </div>
    `;
        }

        function renderFileMessage(msg, isOwn, messageTime) {
            const fileExtension = msg.file_name.split('.').pop().toLowerCase();
            const isImage = ['jpg', 'jpeg', 'png', 'gif', 'webp'].includes(fileExtension);

            let fileIcon = 'ki-document';
            if (isImage) fileIcon = 'ki-picture';
            else if (['pdf'].includes(fileExtension)) fileIcon = 'ki-file-text';
            else if (['zip', 'rar'].includes(fileExtension)) fileIcon = 'ki-archive';

            return `
        <div class="message-bubble ${isOwn ? 'own' : ''} d-flex flex-column">
            <div class="message-content file-message">
                ${isImage ? `
                    <div class="file-image-preview mb-2">
                        <img src="uploads/school_chat_files/${msg.file_path}" alt="${msg.file_name}" 
                             style="max-width: 200px; max-height: 200px; border-radius: 8px; cursor: pointer;"
                             onclick="openImageModal('uploads/school_chat_files/${msg.file_path}', '${msg.file_name}')">
                    </div>
                ` : ''}
                <div class="d-flex align-items-center">

                    <div class="flex-grow-1">
                        <div class="fw-bold text-gray-800">${escapeHtml(msg.file_name)}</div>
                        <div class="text-gray-200 fs-7">${formatFileSize(msg.file_size)}</div>
                    </div>
                    <a href="uploads/school_chat_files/${encodeURIComponent(msg.file_path)}" 
                       class="btn btn-sm btn-light" download>
                        <i class="ki-outline ki-file-down fs-4">
                        </i>
                    </a>
                </div>
            </div>
            <div class="message-time ${isOwn ? 'text-end' : ''}">${messageTime}</div>
        </div>
    `;
        }

        function formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        }

        function openImageModal(imageSrc, imageName) {
            const modal = document.createElement('div');
            modal.className = 'modal fade';
            modal.innerHTML = `
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">${escapeHtml(imageName)}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center">
                    <img src="${imageSrc}" alt="${escapeHtml(imageName)}" class="img-fluid">
                </div>
            </div>
        </div>
    `;

            document.body.appendChild(modal);
            const bsModal = new bootstrap.Modal(modal);
            bsModal.show();

            modal.addEventListener('hidden.bs.modal', () => {
                document.body.removeChild(modal);
            });
        }

        document.getElementById('contactsModal').addEventListener('show.bs.modal', function () {
            loadUsers();
        });

        function loadUsers() {
            const formData = new FormData();
            formData.append('action', 'get_users');

            fetch('includes/chat_school_handler.inc.php', {
                method: 'POST',
                body: formData
            })
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

        function filterConversations() {
            const searchTerm = document.getElementById('conversationSearch').value.toLowerCase();
            const items = document.querySelectorAll('.conversation-item');

            items.forEach(item => {
                const student = item.querySelector('.student-fullname').textContent.toLowerCase();
                const teacher = item.querySelector('.teacher-fullname').textContent.toLowerCase();

                if (student.includes(searchTerm) || teacher.includes(searchTerm)) {
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