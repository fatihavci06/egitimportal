

function handleDelete({ id, url, reload = true, customSuccess, customError }) {
    Swal.fire({
        title: "Emin misiniz?",
        text: "Bu işlem geri alınamaz!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Evet, Kabul Ediyorum!",
        cancelButtonText: "Hayır, iptal et",
        customClass: {
            confirmButton: "btn btn-danger",
            cancelButton: "btn btn-secondary"
        }
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                type: "POST",
                url: url,
                data: { id },
                dataType: "json",
                success: function (response) {
                    if (response.status === "success") {
                        Swal.fire({
                            title: "Başarılı!",
                            text: response.message || "İşlem başarıyla tamamlandı.",
                            icon: "success",
                            confirmButtonText: "Tamam"
                        }).then(() => {
                            if (typeof customSuccess === "function") {
                                customSuccess(response);
                            } else if (reload) {
                                location.reload();
                            }
                        });
                    } else {
                        Swal.fire({
                            title: "Hata!",
                            text: response.message || "Bir hata oluştu.",
                            icon: "error",
                            confirmButtonText: "Tamam"
                        });

                        if (typeof customError === "function") {
                            customError(response);
                        }
                    }
                },
                error: function (xhr, status, error) {
                    Swal.fire({
                        title: "Hata!",
                        text: "Sunucuya ulaşılamadı.",
                        icon: "error",
                        confirmButtonText: "Tamam"
                    });
                }
            });
        }
    });
}
function openGlobalModal({ id, url, title = 'Güncelle' }) {
    const modal = new bootstrap.Modal(document.getElementById('globalModal'));
    modal.show();

    // Başlığı ve içeriği başlangıç haliyle ayarla
    document.getElementById('globalModalLabel').innerText = title;
    document.getElementById('globalModalContent').innerHTML = 'Yükleniyor...';

    // AJAX isteği
    $.ajax({
        type: 'POST',
        url: url,
        data: { id: id },
        success: function (response) {
            document.getElementById('globalModalContent').innerHTML = response;
        },
        error: function () {
            document.getElementById('globalModalContent').innerHTML = 'Bir hata oluştu.';
        }
    });
}
function updateGroup(id) {
    const updatedName = document.getElementById('groupNameInput').value; // Input değerini alıyoruz

    // Ajax isteği gönderme
    fetch('includes/ajax.php?service=groupUpdate', {
        method: 'POST',
        body: new URLSearchParams({
            id: id, // id parametresini gönderiyoruz
            name: updatedName
        }),
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        }
    })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                // SweetAlert ile başarılı mesajı gösteriyoruz
                Swal.fire({
                    icon: 'success',
                    title: 'Değişiklikler başarıyla kaydedildi!',
                    showCancelButton: false,
                    confirmButtonText: 'Tamam',
                    confirmButtonColor: '#3085d6',
                    preConfirm: () => {
                        // Sayfa yenileniyor
                        location.reload();
                    }
                });
            } else {
                // Hata durumunda da bir swal mesajı gösteriyoruz
                Swal.fire({
                    icon: 'error',
                    title: 'Hata',
                    text: data.message || 'Bir hata oluştu.',
                    showConfirmButton: true,
                });
            }
        })
        .catch(error => {
            console.error('Hata oluştu:', error);
            alert('Bir hata oluştu.');
        });
}
function updateWeek(id) {
    const updatedName = document.getElementById('weekNameInput').value; // Input değerini alıyoruz
    const startDate = document.getElementById('startDate').value;
    const endDate = document.getElementById('endDate').value;
    // Ajax isteği gönderme
    fetch('includes/ajax.php?service=weekUpdate', {
        method: 'POST',
        body: new URLSearchParams({
            id: id, // id parametresini gönderiyoruz
            name: updatedName,
            endDate:endDate,
            startDate:startDate
        }),
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        }
    })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                // SweetAlert ile başarılı mesajı gösteriyoruz
                Swal.fire({
                    icon: 'success',
                    title: 'Değişiklikler başarıyla kaydedildi!',
                    showCancelButton: false,
                    confirmButtonText: 'Tamam',
                    confirmButtonColor: '#3085d6',
                    preConfirm: () => {
                        // Sayfa yenileniyor
                        location.reload();
                    }
                });
            } else {
                // Hata durumunda da bir swal mesajı gösteriyoruz
                Swal.fire({
                    icon: 'error',
                    title: 'Hata',
                    text: data.message || 'Bir hata oluştu.',
                    showConfirmButton: true,
                });
            }
        })
        .catch(error => {
            
            alert('Bir hata oluştu.'+error.message);
        });
}

function createTitle() {
    // Seçilen başlık türünü ve girilen başlığı al
    const titleType = $('#titleModalCreate select').val();
    const titleInput = $('#titleInput').val().trim();

    // Boş alan kontrolü
    if (!titleType || !titleInput) {
        Swal.fire({
            icon: 'warning',
            title: 'Eksik Bilgi',
            text: 'Lütfen tüm alanları doldurun.',
            confirmButtonText: 'Tamam'
        });
        return;
    }

    // AJAX isteği
    $.ajax({
        url: 'includes/ajax.php?service=createCategoryTitle', // kendi PHP dosyanla değiştir
        type: 'POST',
        data: {
            title_type: titleType,
            title: titleInput
        },
        success: function (response) {
            

            if (response.status === 'success') {
                Swal.fire({
                    icon: 'success',
                    title: 'Başarılı',
                    text: response.message || 'Başlık başarıyla kaydedildi.',
                    confirmButtonText: 'Tamam'
                }).then(() => {
                    $('#titleModalCreate').modal('hide'); // Modalı kapat
                    $('#titleModalCreate select').val('');
                    $('#titleInput').val('');
                    location.reload;
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Hata',
                    text: response.message || 'Bir hata oluştu.',
                    confirmButtonText: 'Tamam'
                });
            }
        },
        error: function () {
            Swal.fire({
                icon: 'error',
                title: 'Sunucu Hatası',
                text: 'Sunucuya ulaşılamadı.',
                confirmButtonText: 'Tamam'
            });
        }
    });
}
function updateTitle(id) {
    const type = document.getElementById('typeInput').value; // Input değerini alıyoruz
    const title = document.getElementById('titleName').value;
    // Ajax isteği gönderme
    fetch('includes/ajax.php?service=titleUpdate', {
        method: 'POST',
        body: new URLSearchParams({
            id: id, // id parametresini gönderiyoruz
            type: type,
            title:title
        }),
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        }
    })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                // SweetAlert ile başarılı mesajı gösteriyoruz
                Swal.fire({
                    icon: 'success',
                    title: 'Değişiklikler başarıyla kaydedildi!',
                    showCancelButton: false,
                    confirmButtonText: 'Tamam',
                    confirmButtonColor: '#3085d6',
                    preConfirm: () => {
                        // Sayfa yenileniyor
                        location.reload();
                    }
                });
            } else {
                // Hata durumunda da bir swal mesajı gösteriyoruz
                Swal.fire({
                    icon: 'error',
                    title: 'Hata',
                    text: data.message || 'Bir hata oluştu.',
                    showConfirmButton: true,
                });
            }
        })
        .catch(error => {
            console.error('Hata oluştu:', error);
            alert('Bir hata oluştu.');
        });
}


