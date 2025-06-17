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
        width: 800px;;
        height: 400px;
    }

    /* Image container for question files */
    .question-image-container {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        margin-bottom: 1.5rem;
        justify-content: center;
    }

    .question-image-container img {
        max-width: 700px; /* Burayı büyüttük */
        max-height: 100%; /* Burayı büyüttük */
        object-fit: contain;
        border-radius: 0.5rem;
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        background-color: #f9fafb;
    }

    /* Image container for option files */
    .option-image-container {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        margin-top: 0.5rem;
        /* Seçenek resimlerinin dikeyde de hizalanmasını sağlar */
        align-items: center; 
    }

    .option-image-container img {
        max-width: 800px; /* Burayı büyüttük */
        max-height: 700px; /* Burayı büyüttük */
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
        $(document).ready(function () {
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
                    let imageHtml = '<div class="question-image-container">';
                    question.files.forEach(file => {
                        const imageUrl = file;
                        imageHtml += `
                                        <img src="${imageUrl}" alt="Soru Resmi" onerror="this.onerror=null;this.src='https://placehold.co/300x300/E0E0E0/333333?text=Resim+Yok';" />
                                    `;
                    });
                    imageHtml += '</div>';
                    $questionMedia.append(imageHtml);
                }

                const $optionsContainer = $('#options-container').empty();
                question.options.forEach(option => {
                    const optionId = `option-${question.id}-${option.id}`;
                    const isChecked = userAnswers[index] === option.option_key;
                    let optionHtml = `
                                    <label for="${optionId}" class="flex items-start p-4 bg-white border border-gray-300 rounded-lg cursor-pointer hover:bg-blue-50 transition-colors duration-200 shadow-sm">
                                        <input type="radio" id="${optionId}" name="question-${question.id}" value="${option.option_key}" class="mt-1 mr-3 h-5 w-5 text-blue-600 focus:ring-blue-500" ${isChecked ? 'checked' : ''}>
                                        <div class="flex-1">
                                            <span class="font-semibold text-gray-700">${option.option_key}.</span>
                                            <span class="text-gray-800 ml-2">${option.option_text.replace(/\r\n/g, '<br>')}</span>
                                        `;
                    if (option.files && option.files.length > 0) {
                        optionHtml += '<div class="option-image-container">';
                        option.files.forEach(file => {
                            optionHtml += `
                                                <img src="${file}" alt="Seçenek Resmi" onerror="this.onerror=null;this.src='https://placehold.co/300x200/E0E0E0/333333?text=Resim+Yok';" />
                                            `;
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
                const currentQuestionId = testData.questions[currentQuestionIndex].id;
                const selectedOptionKey = $(`input[name="question-${currentQuestionId}"]:checked`).val();

                userAnswers[currentQuestionIndex] = {
                    question_id: currentQuestionId,
                    selected_option_key: selectedOptionKey || null
                };
            }

            $('#prev-btn').on('click', function () {
                saveCurrentAnswer();
                if (currentQuestionIndex > 0) {
                    currentQuestionIndex--;
                    renderQuestion(currentQuestionIndex);
                }
            });

            $('#next-btn').on('click', function () {
                saveCurrentAnswer();
                if (currentQuestionIndex < testData.questions.length - 1) {
                    currentQuestionIndex++;
                    renderQuestion(currentQuestionIndex);
                }
            });

            $('#submit-btn').on('click', function () {
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
                            success: function (response) {
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
                                        confirmButtonText: 'Devam Et',
                                        confirmButtonColor: '#3085d6',
                                        backdrop: true
                                    }).then((result) => {
                                        if (result.isConfirmed) {
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
                            error: function (xhr, status, error) {
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
                success: function (response) {
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
                error: function (xhr, status, error) {
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