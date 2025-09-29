<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Uygulaması</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="assets/plugins/global/plugins.bundle.js"></script>
    <script src="assets/js/scripts.bundle.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f3f4f6;
        }

        span {
            font-size: 17px !important;
        }

        .video-responsive {
            position: relative;
            width: 100%;
            padding-bottom: 45%;
            height: 0;
            margin: 0 auto;
            overflow: hidden;
            border-radius: 0.5rem;
            margin-bottom: 1.5rem;
            max-width: 800px;
        }

        .video-responsive iframe {
            position: absolute;
            top: 0;
            left: 0;
            width: 800px;
            ;
            height: 400px;
        }

        /* Soru resimleri için container */
        .question-image-container {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            margin-bottom: 1.5rem;
            justify-content: center;
        }

        .question-image-container img {
            max-width: 700px;
            max-height: 100%;
            object-fit: contain;
            border-radius: 0.5rem;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            background-color: #f9fafb;
        }

        /* Seçenek resimleri için container */
        .option-image-container {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            margin-top: 0.5rem;
            align-items: center;
        }

        .option-image-container img {
            max-width: 800px;
            max-height: 700px;
            object-fit: contain;
            border-radius: 0.25rem;
            border: 1px solid #e5e7eb;
            background-color: #fff;
        }
    </style>
</head>

<body class="flex items-center justify-center min-h-screen p-4">
    <div id="kt_app_content_container" class="w-full max-w-6xl">
        <div id="test-app" class="test-container bg-white shadow-xl rounded-xl p-6 md:p-8 border border-gray-200">
            <h2 id="test-title" class="text-3xl font-extrabold mb-6 text-gray-900 text-center"></h2>

            <div id="question-display" class="mb-8 bg-gray-50 p-6 rounded-lg border border-gray-100">
                <p id="question-text" class="text-xl font-medium mb-5 text-gray-800 leading-relaxed"></p>
                <div id="question-media"></div>
                <div id="options-container" class="space-y-4 mt-6">
                </div>
            </div>

            <div class="flex flex-col md:flex-row justify-between items-center mt-8 space-y-4 md:space-y-0">
                <button id="prev-btn"
                    class="w-full md:w-auto px-8 py-4 bg-blue-600 text-white font-semibold rounded-lg shadow-md hover:bg-blue-700 transition-all duration-300 ease-in-out disabled:opacity-40 disabled:cursor-not-allowed">Geri</button>
                <span id="question-counter" class="text-gray-600 text-base md:text-lg font-medium"></span>
                <button id="next-btn"
                    class="w-full md:w-auto px-8 py-4 bg-blue-600 text-white font-semibold rounded-lg shadow-md hover:bg-blue-700 transition-all duration-300 ease-in-out">İleri</button>
                <button id="submit-btn"
                    class="w-full md:w-auto px-8 py-4 bg-green-600 text-white font-semibold rounded-lg shadow-md hover:bg-green-700 transition-all duration-300 ease-in-out hidden">Testi
                    Bitir</button>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            let testData = null;
            let currentQuestionIndex = 0;
            let userAnswers = [];

            const urlParams = new URLSearchParams(window.location.search);
            const testId = urlParams.get('id');

            function getYouTubeVideoId(url) {
                if (!url) return null;
                const regExp =
                    /(?:https?:\/\/)?(?:www\.)?(?:m\.)?(?:youtube\.com|youtu\.be)\/(?:watch\?v=|embed\/|v\/|)([\w-]{11})(?:\S+)?/i;
                const match = url.match(regExp);
                return (match && match[1].length === 11) ? match[1] : null;
            }

            // ... (The rest of your code remains the same)

            function renderResultReport(response) {
                console.log(response); // Now the full response object is logged

                // The test title and content_url should be accessed directly from the response object
                const testTitle = response.test_title || 'Test Sonuçları';
                $('#test-title').text(testTitle);

                // Get the user answers with correctness from the response object
                const results = response.user_answers_with_correctness || [];

                // Add "Konu Anlatımına Git" button to the top of the page
                const contentUrlButtonHtml = response.content_url ?
                    `<a href="${response.content_url}" target="_blank" class="w-full md:w-auto px-8 py-4 bg-orange-600 text-white font-semibold rounded-lg shadow-md hover:bg-orange-700 transition-all duration-300 ease-in-out text-center inline-block mb-4">Konu Anlatımına Git</a>` : '';
                $('#test-app').prepend(contentUrlButtonHtml);

                const $questionDisplay = $('#question-display').empty();
                $questionDisplay.removeClass('bg-gray-50').addClass('bg-white');

                const reportHtml = (results || []).map((userAnswer, index) => {
                    const question = testData.questions[index];
                    const isCorrect = userAnswer.is_correct;
                    const correctOptionKey = userAnswer.correct_option_key;

                    const userSelectedOptionKey = userAnswer.selected_option_key;

                    const statusIcon = isCorrect ? '✅' : '❌';
                    const statusColor = isCorrect ? 'text-green-600' : 'text-red-600';

                    // Yanlış cevap için açıklama ekleyelim
                    let explanationHtml = '';
                    if (!isCorrect) {
                        explanationHtml = `
                `;
                    }

                    let optionsHtml = question.options.map(option => {
                        let optionClass = 'bg-white border border-gray-300';
                        let iconHtml = '';

                        // User's selected answer
                        if (option.option_key === userSelectedOptionKey) {
                            if (isCorrect) {
                                optionClass = 'bg-green-100 border border-green-400';
                                iconHtml = '✅';
                            } else {
                                optionClass = 'bg-red-100 border border-red-400';
                                iconHtml = '❌';
                            }
                        }
                        // Correct answer if the user's answer was wrong
                        else if (!isCorrect && option.option_key === correctOptionKey) {
                            optionClass = 'bg-green-100 border-2 border-green-500';
                            iconHtml = '✅ ';
                        }

                        let optionTextContent = `<span class="font-semibold text-gray-700">${option.option_key}.</span> <span class="text-gray-800 ml-2">${option.option_text.replace(/\r\n/g, '<br>')}</span>`;

                       if (option.files && option.files.length > 0) {
    console.log("Seçenek dosyaları:", option.files);
    let optionMediaHtml = '<div class="option-media-container mt-2">';

    option.files.forEach(file => {
        // Eğer file bir File nesnesiyse geçici URL oluştur
        const fileUrl = (file instanceof File) ? URL.createObjectURL(file) : file;
        const lowerUrl = fileUrl.toLowerCase();

        if (/\.(jpe?g|png|gif|webp)$/i.test(lowerUrl)) {
            // 🎨 Resim
            optionMediaHtml += `
                <img src="${fileUrl}" alt="Seçenek Resmi"
                     onerror="this.onerror=null;
                              this.src='https://placehold.co/300x200/E0E0E0/333333?text=Resim+Yok';"
                     style="max-width:100%; margin-bottom:8px; border-radius:6px;" />
            `;
                } else if (/\.(mp3|wav|ogg)$/i.test(lowerUrl)) {
                    // 🎵 Ses
                    optionMediaHtml += `
                        <div style="
                           
                         
                            padding: 12px;
                            border-radius: 10px;
                            background-color: #f8f9fa;
                            display: flex;
                            align-items: center;
                            justify-content: center;
                        ">
                            <audio controls style="width: 100%; height: 45px;">
                                <source src="${fileUrl}" type="audio/${lowerUrl.split('.').pop()}">
                                Tarayıcınız bu ses dosyasını desteklemiyor.
                            </audio>
                        </div>
                    `;
                } else {
                    // 🗂 Diğer dosya tipleri
                    const fileName = fileUrl.split('/').pop();
                    optionMediaHtml += `
                        <p>
                            <a href="${fileUrl}" target="_blank" rel="noopener">${fileName}</a>
                        </p>
                    `;
                }
            });

            optionMediaHtml += '</div>';
            optionTextContent += optionMediaHtml;
        }


                        return `
                <div class="p-4 rounded-lg shadow-sm flex items-start ${optionClass}">
                    ${iconHtml ? `<span class="mr-2 text-xl flex items-center">${iconHtml}</span>` : ''}
                    <div class="flex-1">${optionTextContent}</div>
                </div>
             `;
                    }).join('');

                    let questionMediaHtml = '';
                    if (question.videos && question.videos.length > 0) {
                        const videoId = getYouTubeVideoId(question.videos[0]);
                        if (videoId) {
                            questionMediaHtml = `<div class="video-responsive shadow-sm"><iframe src="https://www.youtube.com/embed/${videoId}" frameborder="0" allowfullscreen></iframe></div>`;
                        } else {
                            questionMediaHtml = `<div class="text-sm text-gray-500 p-2 border border-gray-200 rounded-md mb-6">Video Linki: <a href="${question.videos[0]}" target="_blank" class="text-blue-500 hover:underline break-all">${question.videos[0]}</a></div>`;
                        }
                    } else if (question.files && question.files.length > 0) {
                            let mediaHtml = '<div class="question-media-container">';

                            question.files.forEach(file => {
                                // Eğer file bir File nesnesiyse URL.createObjectURL ile geçici URL üret
                                const fileUrl = (file instanceof File) ? URL.createObjectURL(file) : file;
                                const lowerUrl = fileUrl.toLowerCase();

                                if (/\.(jpe?g|png|gif|webp)$/i.test(lowerUrl)) {
                                    // 🎨 Resim
                                    mediaHtml += `
                                        <img src="${fileUrl}" alt="Soru Resmi"
                                            onerror="this.onerror=null;
                                                    this.src='https://placehold.co/300x300/E0E0E0/333333?text=Resim+Yok';"
                                            style="max-width:100%; margin-bottom:10px; border-radius:8px;" />
                                    `;
                                } else if (/\.(mp3|wav|ogg)$/i.test(lowerUrl)) {
                                    // 🎵 Ses dosyası
                                    mediaHtml += `
                                        <div style="
                                            width: 100%;
                                          
                                            margin: 10px auto;
                                            padding: 15px;
                                            border: 2px solid #ff8800; /* Turuncu çerçeve */
                                            border-radius: 12px;
                                            background-color: #f8f9fa;
                                            display: flex;
                                            align-items: center;
                                            justify-content: center;
                                        ">
                                            <audio controls style="width: 100%; height: 50px;">
                                                <source src="${fileUrl}" type="audio/${lowerUrl.split('.').pop()}">
                                                Tarayıcınız bu ses dosyasını desteklemiyor.
                                            </audio>
                                        </div>
                                    `;
                                } else {
                                    // 🗂 Diğer dosya tipleri için link
                                    const fileName = fileUrl.split('/').pop();
                                    mediaHtml += `
                                        <p>
                                            <a href="${fileUrl}" target="_blank" rel="noopener">${fileName}</a>
                                        </p>
                                    `;
                                }
                            });

                            mediaHtml += '</div>';
                            questionMediaHtml = mediaHtml;
                        }

                    return `
               <div class="mb-8 p-6 bg-gray-50 rounded-lg border border-gray-100 shadow-md">
                <div class="flex items-center mb-4">
                    <span class="text-xl font-bold mr-3 ${statusColor}">${statusIcon}</span>
                    <p class="text-lg font-medium text-gray-800">${index + 1}. Soru</p>
                </div>
                <p class="text-xl font-medium mb-5 text-gray-800 leading-relaxed">${question.question_text.replace(/\r\n/g, '<br>')}</p>
                ${questionMediaHtml}
                <div class="space-y-4 mt-6">
                    ${optionsHtml}
                </div>
                ${explanationHtml}
                </div>
             `;
                }).join('');

                $questionDisplay.html(reportHtml);

                $('#prev-btn, #next-btn, #submit-btn, #question-counter').hide();
            }

            // ... (The rest of your code remains the same)

            function renderQuestion(index) {
                if (!testData || !testData.questions || testData.questions.length === 0) {
                    return;
                }

                const question = testData.questions[index];
                if (!question) {
                    $('#question-display').html('<p class="text-red-500 text-center">Soru bulunamadı.</p>');
                    $('#prev-btn, #next-btn, #submit-btn').hide();
                    return;
                }

                if (index === 0) {
                    $('#test-title').text(testData.test_title || 'Test Başlığı');
                }

                $('#question-text').html(question.question_text.replace(/\r\n/g, '<br>'));

                const $questionMedia = $('#question-media').empty();
                if (question.videos && question.videos.length > 0) {
                    const firstVideoUrl = question.videos[0];
                    const videoId = getYouTubeVideoId(firstVideoUrl);
                    if (videoId) {
                        $questionMedia.append(`
                            <div class="video-responsive shadow-sm">
                                <iframe src="https://www.youtube.com/embed/${videoId}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                            </div>
                        `);
                    } else {
                        $questionMedia.append(`
                            <div class="text-sm text-gray-500 p-2 border border-gray-200 rounded-md mb-6">
                                Video Linki: <a href="${firstVideoUrl}" target="_blank" class="text-blue-500 hover:underline break-all">${firstVideoUrl}</a>
                            </div>
                        `);
                    }
                } else if (question.files && question.files.length > 0) {
                    console.log("Soru dosyaları:", question.files);
                    let mediaHtml = '<div class="question-media-container">';

                    question.files.forEach(file => {
                        const fileUrl = file; // file zaten URL ise
                        const lowerUrl = fileUrl.toLowerCase();

                        if (/\.(jpe?g|png|gif|webp)$/i.test(lowerUrl)) {
                            // 🎨 Resim
                            mediaHtml += `
                                    <img src="${fileUrl}" alt="Soru Resmi"
                                        onerror="this.onerror=null;
                                                this.src='https://placehold.co/300x300/E0E0E0/333333?text=Resim+Yok';" />
                                `;
                        } else if (/\.(mp3|wav|ogg)$/i.test(lowerUrl)) {
                            // 🎵 Ses
                          mediaHtml += `
                                <div style="
                                    width: 100%;
                                    height: 170px;
                                    margin: 10px auto;
                                    padding: 15px;
                                            border: 2px solid #ff8800; /* Turuncu çerçeve */

                                    border-radius: 12px;
                                    background-color: #f8f9fa;
                                    display: flex;
                                    align-items: center;
                                    justify-content: center;
                                ">
                                    <audio controls style="
                                        width: 100%;
                                        height: 50px;
                                    ">
                                        <source src="${fileUrl}" type="audio/${lowerUrl.split('.').pop()}">
                                        Tarayıcınız bu ses dosyasını desteklemiyor.
                                    </audio>
                                </div>
                            `;


                        } else {
                            // Başka bir dosya türü ise link olarak gösterelim
                            const fileName = fileUrl.split('/').pop();
                            mediaHtml += `
                                        <p>
                                            <a href="${fileUrl}" target="_blank" rel="noopener">
                                                ${fileName}
                                            </a>
                                        </p>
                                    `;
                        }
                    });

                    mediaHtml += '</div>';
                    $questionMedia.append(mediaHtml);
                }

                const $optionsContainer = $('#options-container').empty();
                question.options.forEach(option => {
                    console.log("Seçenek dosyaları:", option.files);
                    const optionId = `option-${question.id}-${option.id}`;
                    const isChecked = userAnswers[index]?.selected_option_key === option.option_key;
                    let optionHtml = `
                        <label for="${optionId}" class="flex items-start p-4 bg-white border border-gray-300 rounded-lg cursor-pointer hover:bg-blue-50 transition-colors duration-200 shadow-sm">
                            <input type="radio" id="${optionId}" name="question-${question.id}" value="${option.option_key}" class="mt-1 mr-3 h-5 w-5 text-blue-600 focus:ring-blue-500" ${isChecked ? 'checked' : ''}>
                            <div class="flex-1">
                                <span class="font-semibold text-gray-700">${option.option_key}.</span>
                                <span class="text-gray-800 ml-2">${option.option_text.replace(/\r\n/g, '<br>')}</span>
                            `;
                    if (option.files && option.files.length > 0) {
                        optionHtml += '<div class="option-media-container">';

                        option.files.forEach(file => {
                            // Eğer file bir File nesnesiyse URL.createObjectURL ile geçici URL üret
                            const fileUrl = (file instanceof File) ? URL.createObjectURL(file) : file;
                            const lowerUrl = fileUrl.toLowerCase();

                            if (/\.(jpe?g|png|gif|webp)$/i.test(lowerUrl)) {
                                // 🎨 Resim dosyası
                                optionHtml += `
                <img src="${fileUrl}" alt="Seçenek Resmi"
                     onerror="this.onerror=null;
                              this.src='https://placehold.co/300x200/E0E0E0/333333?text=Resim+Yok';" />
            `;
                            } else if (/\.(mp3|wav|ogg)$/i.test(lowerUrl)) {
                                // 🎵 Ses dosyası
                                optionHtml += `
                <audio controls style="display:block; margin-top:8px;">
                    <source src="${fileUrl}" type="audio/${lowerUrl.split('.').pop()}">
                    Tarayıcınız bu ses dosyasını desteklemiyor.
                </audio>
            `;
                            } else {
                                // 🗂 Diğer dosya tipleri için bağlantı
                                const fileName = fileUrl.split('/').pop();
                                optionHtml += `
                <p>
                    <a href="${fileUrl}" target="_blank" rel="noopener">
                        ${fileName}
                    </a>
                </p>
            `;
                            }
                        });

                        optionHtml += '</div>';
                    }

                    optionHtml += `
                            </div>
                        </label>
                    `;
                    $optionsContainer.append(optionHtml);
                });

                $('#question-counter').text(`${index + 1} / ${testData.questions.length}`);
                $('#prev-btn').prop('disabled', index === 0);
                $('#next-btn').prop('disabled', index === testData.questions.length - 1);

                if (index === testData.questions.length - 1) {
                    $('#next-btn').addClass('hidden');
                    $('#submit-btn').removeClass('hidden');
                } else {
                    $('#next-btn').removeClass('hidden');
                    $('#submit-btn').addClass('hidden');
                }
            }

            function saveCurrentAnswer() {
                const currentQuestion = testData.questions[currentQuestionIndex];
                if (!currentQuestion) return;

                const selectedOptionKey = $(`input[name="question-${currentQuestion.id}"]:checked`).val();

                userAnswers[currentQuestionIndex] = {
                    question_id: currentQuestion.id,
                    selected_option_key: selectedOptionKey || null
                };
            }

            $('#prev-btn').on('click', function() {
                saveCurrentAnswer();
                if (currentQuestionIndex > 0) {
                    currentQuestionIndex--;
                    renderQuestion(currentQuestionIndex);
                }
            });

            $('#next-btn').on('click', function() {
                saveCurrentAnswer();
                if (currentQuestionIndex < testData.questions.length - 1) {
                    currentQuestionIndex++;
                    renderQuestion(currentQuestionIndex);
                }
            });

            $('#submit-btn').on('click', function() {
                saveCurrentAnswer();

                Swal.fire({
                    title: 'Testi Bitirmek İstiyor Musunuz?',
                    text: "Cevaplarınız kaydedilecektir.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Evet, Bitir!',
                    cancelButtonText: 'Hayır, Devam Et'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Cevaplar Gönderiliyor...',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });

                        const submissionData = {
                            test_id: testId,
                            user_answers: userAnswers
                        };

                        $.ajax({
                            url: 'includes/ajax.php?service=submitTestAnswers',
                            method: 'POST',
                            dataType: 'json',
                            contentType: 'application/json',
                            data: JSON.stringify(submissionData),
                            success: function(response) {
                                Swal.close();

                                if (response.status === 'success') {
                                    let titleText = '';
                                    if (response.score >= 80) {
                                        titleText = '🎉 Harika İş Çıkardınız!';
                                    } else if (response.score >= 50) {
                                        titleText = '💪 Fena Değil, Daha İyisini Yapabilirsin!';
                                    } else {
                                        titleText = '📘 Tekrar Denemelisin!';
                                    }

                                    Swal.fire({
                                        title: titleText,
                                        html: `
                                                            <div style="font-size: 16px; text-align: left; line-height: 1.6;">
                                                                <p>Testi tamamladınız. İşte sonuçlarınız:</p>
                                                                <p>✅ <strong>Doğru Sayısı:</strong> <span style="color:green;">${response.correct_count}</span> / ${response.total_questions}</p>
                                                                <p>📊 <strong>Puanınız:</strong> <span style="color:blue; font-size: 20px;">${response.score} / 100</span></p>
                                                                <hr>
                                                                <p style="font-size: 14px; color: gray;">
                                                                    ${response.score >= 80 ?
                                                                        'Tebrikler, bu başarıyı sürdürmeye devam et! 👏' :
                                                                        response.score >= 50 ?
                                                                            'Biraz daha çalışmayla çok daha iyisini yapabilirsin! 🚀' :
                                                                            'Endişelenme, tekrar denemek gelişmenin bir parçası. 💡'
                                                                    }
                                                                </p>
                                                            </div>
                                                        `,
                                        icon: 'success',
                                        showCancelButton: true,
                                        confirmButtonText: 'Cevapları İncele',
                                        cancelButtonText: 'Ana Sayfaya Dön',
                                        confirmButtonColor: '#3085d6',
                                        cancelButtonColor: '#d33',
                                        backdrop: true,
                                        showDenyButton: true,
                                        denyButtonText: 'Konu Anlatımına Git',
                                        denyButtonColor: '#f97316'
                                    }).then((result) => {
                                        // 'Cevapları İncele' butonu tıklandığında
                                        if (result.isConfirmed) {
                                            renderResultReport(response);
                                        }
                                        // 'Konu Anlatımına Git' butonu tıklandığında
                                        else if (result.isDenied) {
                                            // response'tan gelen content_url'i kullan
                                            if (response.content_url) {
                                                window.open(response.content_url, '_blank');
                                            } else {
                                                console.error("Konu anlatımı URL'si bulunamadı.");
                                                Swal.fire({
                                                    icon: 'info',
                                                    title: 'Bilgi',
                                                    text: "Bu testin bir konu anlatımı bulunmuyor.",
                                                    confirmButtonText: 'Tamam'
                                                });
                                            }
                                        }
                                        // 'Ana Sayfaya Dön' butonu tıklandığında
                                        else if (result.dismiss === Swal.DismissReason.cancel) {
                                            window.location.href = 'ogrenci-testler';
                                        }
                                    });
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Hata!',
                                        text: response.message || 'Cevaplarınız kaydedilirken bir sorun oluştu.',
                                        confirmButtonText: 'Tamam'
                                    });
                                }
                            },
                            error: function(xhr, status, error) {
                                Swal.close();
                                console.error("AJAX Hatası: Cevaplar gönderilemedi.", status, error, xhr);
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Bağlantı Hatası',
                                    text: 'Cevaplarınızı gönderirken bir sorun oluştu. Lütfen internet bağlantınızı kontrol edin veya daha sonra tekrar deneyin.',
                                    confirmButtonText: 'Tamam'
                                });
                            }
                        });
                    }
                });
            });

            if (!testId) {
                Swal.fire({
                    icon: 'error',
                    title: 'Hata',
                    text: 'Test ID bulunamadı. Lütfen geçerli bir test linki kullanın.',
                    confirmButtonText: 'Tamam'
                });
                $('#question-display').html('<p class="text-red-500 text-center">Test ID bulunamadı.</p>');
                $('#prev-btn, #next-btn, #submit-btn').hide();
                return;
            }

            $.ajax({
                url: 'includes/ajax-ayd.php?service=getTestByStudent&test_id=' + testId,
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success' && response.data && Array.isArray(response.data.questions)) {
                        testData = response.data;
                        userAnswers = new Array(testData.questions.length).fill(null).map((_, i) => ({
                            question_id: testData.questions[i].id,
                            selected_option_key: null
                        }));
                        renderQuestion(currentQuestionIndex);
                    } else {
                        console.error("Hata: Sunucudan geçersiz test verisi geldi.", response);
                        Swal.fire({
                            icon: 'error',
                            title: 'Veri Yükleme Hatası',
                            text: response.message ||
                                'Test verileri sunucudan alınamadı veya geçersiz formatta. Lütfen yöneticiyle iletişime geçin.',
                            confirmButtonText: 'Tamam'
                        });
                        $('#question-display').html(
                            '<p class="text-red-500 text-center">Test verisi yüklenemedi veya soru bulunamadı.</p>');
                        $('#prev-btn, #next-btn, #submit-btn').hide();
                    }
                },
                error: function(xhr, status, error) {
                    console.error("AJAX Hatası: Test verileri çekilemedi.", status, error, xhr);
                    Swal.fire({
                        icon: 'error',
                        title: 'Bağlantı Hatası',
                        text: 'Test verilerini sunucudan çekerken bir sorun oluştu. Lütfen internet bağlantınızı kontrol edin veya daha sonra tekrar deneyin.',
                        confirmButtonText: 'Tamam'
                    });
                    $('#question-display').html('<p class="text-red-500 text-center">Test verisi çekilemedi. Bir hata oluştu.</p>');
                    $('#prev-btn, #next-btn, #submit-btn').hide();
                }
            });
        });
    </script>
</body>

</html>