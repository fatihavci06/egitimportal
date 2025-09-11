/* $(document).ready(function() {
  $('.approve').click(function() {
    const gonderilecekId = $(this).data('info');
    const ekBilgi = $(this).data('ek');

    Swal.fire({
      title: 'Emin misiniz?',
      text: `Ödemeyi onaylıyor musunuz?`,
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Evet, Gönder!',
      cancelButtonText: 'İptal'
    }).then((result) => {
      if (result.isConfirmed) {
        // Kullanıcı "Evet, Gönder!" butonuna tıkladı
        $.ajax({
          url: 'includes/approvePayment.php', // Bilgilerin gönderileceği URL
          method: 'POST', // Genellikle veri gönderme işlemleri için POST metodu kullanılır
          data: {
            id: gonderilecekId,
            ekBilgi: ekBilgi
            // İsterseniz direkt tüm data özelliklerini gönderebilirsiniz:
            // $(this).data()
          },
          dataType: 'json', // Beklenen cevap türü (isteğe bağlı)
          success: function(response) {
            // AJAX isteği başarıyla tamamlandıysa yapılacak işlemler
            if (response.status === "success") {
              Swal.fire(
                'Başarılı!',
                'Ödeme onaylanmıştır.',
                'success'
              ).then((result) => {
                // SweetAlert kapatıldıktan sonra yönlendirme yap
                if (result.isConfirmed || result.dismiss === Swal.DismissReason.close || result.dismiss === Swal.DismissReason.timer) {
                  window.location.href = './havale-beklenenler.php'; // Yönlendirmek istediğiniz URL
                }
              });
            } else {
              Swal.fire(
                'Hata!',
                'Bilgiler gönderilirken bir hata oluştu.',
                'error'
              );
            }
          },
          error: function(xhr, status, error) {
            // AJAX isteği sırasında bir hata oluşursa yapılacak işlemler
            console.error("AJAX Hatası:", error);
            Swal.fire(
              'Hata!',
              'Sunucu ile iletişim kurulurken bir hata oluştu.' + xhr.responseText,
              'error'
            );
          }
        });
      }
    });
  });
}); */

$(document).ready(function() {
  $('#kt_customers_table tbody').on('click', '.approve', function() {
    const gonderilecekId = $(this).data('info');
    const ekBilgi = $(this).data('ek');

    Swal.fire({
      title: 'Emin misiniz?',
      text: `Ödemeyi onaylıyor musunuz?`,
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Evet, Onayla!',
      cancelButtonText: 'İptal'
    }).then((result) => {
      if (result.isConfirmed) {
        // Kullanıcı "Evet, Onayla!" butonuna tıkladı
        $.ajax({
          url: 'includes/approvePayment.php', // Bilgilerin gönderileceği URL
          method: 'POST',
          data: {
            id: gonderilecekId,
            ekBilgi: ekBilgi
          },
          dataType: 'json',
          success: function(response) {
            if (response.status === "success") {
              Swal.fire(
                'Başarılı!',
                'Ödeme onaylanmıştır.',
                'success'
              ).then((result) => {
                if (result.isConfirmed || result.dismiss === Swal.DismissReason.close || result.dismiss === Swal.DismissReason.timer) {
                  // Yönlendirme yapmak yerine sayfayı yenilemek daha kullanışlı olabilir.
                  window.location.href = './havale-beklenenler.php';
                  // Eğer yönlendirme yapmayıp sadece tabloyu yenilemek isterseniz aşağıdaki satırı kullanabilirsiniz:
                  // $('#kt_datatable_waiting_students').DataTable().ajax.reload(null, false);
                }
              });
            } else {
              Swal.fire(
                'Hata!',
                'Bilgiler gönderilirken bir hata oluştu.',
                'error'
              );
            }
          },
          error: function(xhr, status, error) {
            console.error("AJAX Hatası:", error);
            Swal.fire(
              'Hata!',
              'Sunucu ile iletişim kurulurken bir hata oluştu.' + xhr.responseText,
              'error'
            );
          }
        });
      }
    });
  });
});