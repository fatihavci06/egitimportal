<!DOCTYPE html>
<html lang="tr">
<?php
session_start();
define('GUARD', true);
if (isset($_SESSION['role'])) {
    // NOT: Bu kısım, PHP sınıf dosyalarınızın doğru yolda olduğunu varsayar.
    include_once "classes/dbh.classes.php";
    include "classes/classes.classes.php";
    $lesson = new Classes();
    if ($_SESSION['role'] == 2) {
        $lessons = $lesson->getLessonsList($_SESSION['class_id']);
    } elseif ($_SESSION['role'] == 10002) {
        $lessons = $lesson->getPreschoolLessonsList($_SESSION['class_id']);
    } elseif ($_SESSION['role'] == 10005) {
        require_once "classes/student.classes.php";
        $studentInfo = new Student();
        $getPreSchoolStudent = $studentInfo->getPreSchoolStudentsInfoForParents($_SESSION['id']);
        $class_idsi = $getPreSchoolStudent[0]['class_id'];
        $lessons = $lesson->getPreschoolLessonsList($class_idsi);
    }
    include_once "views/pages-head.php";
?>
    <head>
        <style>
            /* Minimal custom style for colors not in Bootstrap's palette, 
           or for specific border widths if Bootstrap's are not enough. */
            .bg-custom-light {
                background-color: #e6e6fa;
                /* Light purple */
            }

            .border-custom-red {
                border-color: #d22b2b !important;
            }

            .text-custom-cart {
                color: #6a5acd;
                /* Slate blue for the cart */
            }

            /* For the circular icon, we'll use a larger padding or fixed size */
            .icon-circle-lg {
                width: 60px;
                /* fixed width */
                height: 60px;
                /* fixed height */
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .icon-circle-lg img {
                max-width: 100%;
                /* Ensure image scales within the circle */
                max-height: 100%;
            }
        </style>
    </head>
    <body id="kt_app_body" data-kt-app-header-fixed="true" data-kt-app-header-fixed-mobile="true" data-kt-app-sidebar-enabled="true" data-kt-app-sidebar-fixed="true" data-kt-app-sidebar-hoverable="true" data-kt-app-sidebar-push-toolbar="true" data-kt-app-sidebar-push-footer="true" data-kt-app-toolbar-enabled="true" data-kt-app-aside-enabled="true" data-kt-app-aside-fixed="true" data-kt-app-aside-push-toolbar="true" data-kt-app-aside-push-footer="true" class="app-default">
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
                <?php include_once "views/header.php"; ?>
                <div class="app-wrapper flex-column flex-row-fluid" id="kt_app_wrapper">
                    <?php include_once "views/sidebar.php"; ?>
                    <div class="app-main flex-column flex-row-fluid" id="kt_app_main">
                        <div class="d-flex flex-column flex-column-fluid">
                            <?php include_once "views/toolbar.php"; ?>
                            <div id="kt_app_content" class="app-content flex-column-fluid pt-0">
                                <div id="kt_app_content_container" class="app-container container-fluid">

                                    <header class="container-fluid bg-custom-light py-3 d-flex justify-content-between align-items-center border-top border-bottom border-custom-red" style="border-width: 5px !important;">
                                        <div class="d-flex align-items-center">
                                            <div class="me-3 icon-circle-lg">
                                                <img src="assets/media/mascots/lineup-robot-maskot.png" style="width: 80px;" alt="Maskot">
                                            </div>
                                            <h1 class="fs-3 fw-bold text-dark mb-0">Yapay Zekaya Sorunu Sor</h1>
                                        </div>
                                    </header>

                                    <div class="row mt-4">

                                        <div class="col-12 col-md-3 col-lg-2 mb-4">
                                            <div class="row g-3">
                                                <?php if ($_SESSION['role'] == 2): foreach ($lessons as $l): ?>
                                                    <?php if ($l['name'] !== 'Robotik Kodlama' && $l['name'] !== 'Ders Deneme'): ?>
                                                        <div class="col-6 col-md-12 text-center">
                                                            <a href="ders/<?= urlencode($l['slug']) ?>" class="text-decoration-none text-dark d-block">
                                                                <img src="assets/media/icons/dersler/<?= htmlspecialchars($l['icons']) ?>"
                                                                    alt="<?= htmlspecialchars($l['name']) ?>"
                                                                    class="img-fluid"
                                                                    style="width: 65px; height: 65px; object-fit: contain;" />
                                                                <div class="mt-2 fw-semibold small"><?= htmlspecialchars($l['name']) ?></div>
                                                            </a>
                                                        </div>
                                                    <?php endif; ?>
                                                <?php endforeach; ?>
                                                <?php endif; ?>
                                                <?php if ($_SESSION['role'] == 10002 or $_SESSION['role'] == 10005): foreach ($lessons as $l): ?>
                                                    <?php if ($l['name'] !== 'Robotik Kodlama' && $l['name'] !== 'Ders Deneme'): ?>
                                                        <div class="col-6 col-md-12 text-center">
                                                            <a href="ana-okulu-icerikler" class="text-decoration-none text-dark d-block">
                                                                <img src="assets/media/icons/dersler/<?= htmlspecialchars($l['icons']) ?>"
                                                                    alt="<?= htmlspecialchars($l['name']) ?>"
                                                                    class="img-fluid"
                                                                    style="width: 65px; height: 65px; object-fit: contain;" />
                                                                <div class="mt-2 fw-semibold small"><?= htmlspecialchars($l['name']) ?></div>
                                                            </a>
                                                        </div>
                                                    <?php endif; ?>
                                                <?php endforeach; ?>
                                                <?php endif; ?>
                                            </div>
                                        </div>

                                        <div class="col-12 col-md-9 col-lg-10">
                                            <div class="card shadow-lg border-0 rounded-4 bg-light">
                                                <div class="card-body px-4 pt-4 pb-3 d-flex flex-column" style="background-color: #f8f9fa;">
                                                    <div id="chat-box" class="mb-3 p-3 rounded bg-white shadow-sm overflow-auto" style="height: 300px;"></div>
                                                    <div class="input-group">
                                                        <input type="text" id="userInput" class="form-control rounded-start-pill" placeholder="Mesajınızı yazın...">
                                                        
                                                        <button class="btn btn-icon btn-secondary" id="voiceBtn" title="Sesli Soru Sor (Başlat/Durdur)">
                                                            <i class="fas fa-microphone fs-2"></i>
                                                        </button>
                                                        
                                                        <button class="btn btn-primary rounded-end-pill px-4" id="sendBtn">Gönder</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    <?php include_once "views/footer.php"; ?>
                    </div>
                <?php include_once "views/aside.php"; ?>
                </div>
            </div>
        </div>
        <div id="kt_scrolltop" class="scrolltop" data-kt-scrolltop="true">
            <i class="ki-duotone ki-arrow-up">
                <span class="path1"></span>
                <span class="path2"></span>
            </i>
        </div>
        <script>
            var hostUrl = "assets/";
        </script>
        <script src="assets/plugins/global/plugins.bundle.js"></script>
        <script src="assets/js/scripts.bundle.js"></script>
        <script src="assets/plugins/custom/datatables/datatables.bundle.js"></script>
        <script src="assets/plugins/custom/tinymce/tinymce.bundle.js"></script>
        <script src="assets/js/custom/apps/subtopics/list/export.js"></script>
        <script src="assets/js/custom/apps/subtopics/list/list.js"></script>
        <script src="assets/js/custom/apps/subtopics/list/topicadd.js"></script>
        <script src="assets/js/custom/apps/subtopics/add.js"></script>
        <script src="assets/js/custom/apps/subtopics/create.js"></script>
        <script src="assets/js/widgets.bundle.js"></script>
        <script src="assets/js/custom/widgets.js"></script>
        <script src="assets/js/custom/apps/chat/chat.js"></script>
        <script src="assets/js/custom/utilities/modals/upgrade-plan.js"></script>
        <script src="assets/js/custom/utilities/modals/create-account.js"></script>
        <script src="assets/js/custom/utilities/modals/create-app.js"></script>
        <script src="assets/js/custom/utilities/modals/users-search.js"></script>
        
        <script>
            const userInput = $('#userInput');
            const sendBtn = $('#sendBtn');
            const chatBox = $('#chat-box');
            
            // YENİ FONKSİYON: Sesli Okuma (Text-to-Speech)
            function speakResponse(text) {
                if ('speechSynthesis' in window) {
                    const synthesis = window.speechSynthesis;
                    if (synthesis.speaking) {
                        synthesis.cancel();
                    }
                    
                    // Sesli giriş zaten aktifse durdur (Gereksiz ses alımını engeller)
                    if ('webkitSpeechRecognition' in window && isRecording) {
                        recognition.stop(); 
                    }

                    const utterance = new SpeechSynthesisUtterance(text);
                    
                    const voices = synthesis.getVoices();
                    const turkishVoice = voices.find(voice => voice.lang.startsWith('tr'));

                    if (turkishVoice) {
                        utterance.voice = turkishVoice;
                    } else {
                        utterance.lang = 'tr-TR'; 
                    }
                    
                    utterance.pitch = 1.0; 
                    utterance.rate = 1.0;  
                    
                    // Konuşma başlarken sesli girişi devre dışı bırak
                    utterance.onstart = function() {
                        voiceBtn.prop('disabled', true); 
                    };
                    
                    // ÖNEMLİ EK: Konuşma bittiğinde mikrofonu resetle ve butonu serbest bırak
                    utterance.onend = function() {
                        // Eğer kayıt hala aktif görünüyorsa, manuel olarak durdururuz
                        if ('webkitSpeechRecognition' in window && isRecording) {
                            recognition.stop(); 
                        }
                        voiceBtn.prop('disabled', false); 
                    };

                    synthesis.speak(utterance);
                } else {
                    console.warn('Tarayıcınız sesli okuma özelliğini desteklemiyor.');
                }
            }


            // 1. ChatGPT Mesaj Gönderme İşlevi
            sendBtn.click(function() {
                let message = userInput.val().trim();
                if (message === '') return;
                
                // GÖNDERİM KONTROLÜ: Sesli giriş yapılıp yapılmadığını kontrol et
                const shouldSpeak = userInput.attr('data-voice') === 'true';

                // Kullanıcı mesajını kutuya ekle
                chatBox.append('<div class="user-msg"><strong>Sen:</strong> ' + message + '</div>');
                userInput.val('');
                chatBox.scrollTop(chatBox[0].scrollHeight);

                // İşareti temizle, sonraki mesaj klavye mesajı olabilir
                userInput.removeAttr('data-voice');

                // Yazıyor... mesajını ekle
                chatBox.append('<div class="bot-msg typing-msg" id="typingMsg"><em>Lineup yazıyor...</em></div>');
                chatBox.scrollTop(chatBox[0].scrollHeight);

                // AJAX ile PHP dosyasına gönder
                $.ajax({
                    url: 'chatgpt.php',
                    method: 'POST',
                    contentType: 'application/json',
                    data: JSON.stringify({
                        message: message
                    }),
                    success: function(res) {
                        // Yazıyor... mesajını kaldır
                        $('#typingMsg').remove();

                        let content = res.choices[0].message.content;
                        chatBox.append('<div class="bot-msg"><strong>Lineup:</strong> ' + content + '</div>');
                        chatBox.scrollTop(chatBox[0].scrollHeight);

                        // YENİ MANTIK: Eğer shouldSpeak doğruysa cevabı sesli oku
                        if (shouldSpeak) {
                            speakResponse(content);
                        }
                    },
                    error: function() {
                        $('#typingMsg').remove();
                        chatBox.append('<div class="bot-msg text-danger">Bir hata oluştu.</div>');
                    }
                });
            });


            // Enter tuşuna basınca gönder
            userInput.keypress(function(e) {
                if (e.which === 13) {
                    userInput.removeAttr('data-voice');
                    sendBtn.click();
                }
            });

            // 2. Sesli Soru Sorma İşlevi (Web Speech API) - BAŞLAT/DURDUR TOGGLE MANTIĞI
            const voiceBtn = $('#voiceBtn'); // jQuery objesi olarak tanımlandı
            let isRecording = false; // Kayıt durumunu tutan değişken
            let recognition; // recognition değişkenini dışarıda tanımlıyoruz

            if ('webkitSpeechRecognition' in window) {
                recognition = new webkitSpeechRecognition();
                recognition.continuous = true; // Sürekli dinleme modu
                recognition.lang = 'tr-TR'; 
                recognition.interimResults = false; 

                // Hata durumları
                recognition.onerror = function(event) {
                    console.error('Konuşma Tanıma Hatası:', event.error);
                    
                    // Hata olduğunda durumu sıfırla
                    isRecording = false;
                    voiceBtn.removeClass('btn-danger').addClass('btn-secondary');
                    
                    let errMsg = "Mesajınızı yazın...";
                    if (event.error === 'no-speech') {
                         errMsg = "Ses algılanamadı, tekrar deneyin.";
                    } else if (event.error === 'not-allowed') {
                         errMsg = "Mikrofon izni verilmedi.";
                    } 
                    userInput.attr('placeholder', errMsg);
                    setTimeout(() => userInput.attr('placeholder', "Mesajınızı yazın..."), 3000);
                };

                // Tanıma başladığında
                recognition.onstart = function() {
                    isRecording = true;
                    voiceBtn.removeClass('btn-secondary').addClass('btn-danger'); // Butonu kırmızı yap: KAYITTA
                    userInput.val(''); // Yeni kayıtta eski metni temizle
                };

                // Tanıma bittiğinde (Manuel stop ile biter veya konuşma durur)
                recognition.onend = function() {
                    isRecording = false;
                    voiceBtn.removeClass('btn-danger').addClass('btn-secondary'); // Butonu normale döndür
                };

                // Sonuç geldikçe: Metni giriş alanına yazar
                recognition.onresult = function(event) {
                    // Continuous modu açık olduğu için tüm sonuçları birleştir
                    let finalTranscript = '';
                    for (let i = event.resultIndex; i < event.results.length; ++i) {
                        if (event.results[i].isFinal) {
                            finalTranscript += event.results[i][0].transcript + ' ';
                        }
                    }
                    
                    // Metni giriş alanına yaz
                    if (finalTranscript) {
                        userInput.val(finalTranscript.trim());
                        // ÖNEMLİ EK: Sesli giriş yapıldığını işaretle!
                        userInput.attr('data-voice', 'true');
                    }
                };

                // Mikrofon butonuna tıklandığında kaydı başlat/durdur
                voiceBtn.on('click', function() {
                    if (isRecording) {
                        // Kayıt aktifse: DURDUR
                        recognition.stop();
                    } else {
                        // Kayıt pasifse: BAŞLAT
                        try {
                            recognition.start();
                        } catch (e) {
                            console.warn('Tanıma başlatılamadı, muhtemelen zaten çalışıyor. Durduruluyor...', e.message);
                            recognition.stop();
                        }
                    }
                });

            } else {
                // Tarayıcı desteklemiyorsa
                voiceBtn.prop('disabled', true);
                voiceBtn.attr('title', "Sesli komut bu tarayıcıda desteklenmiyor.");
                console.warn("Web Speech API desteklenmiyor. Lütfen Chrome gibi modern bir tarayıcı kullanın.");
            }
        </script>

        </body>
    </html>
<?php } else {
    header("location: index");
}