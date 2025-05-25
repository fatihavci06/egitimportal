<!DOCTYPE html>
<html lang="tr">
<?php
session_start();
define('GUARD', true);
if (isset($_SESSION['role']) and ($_SESSION['role'] == 1 or $_SESSION['role'] == 10001 or $_SESSION['role'] == 10002)) {
  include_once "classes/dbh.classes.php";
  include "classes/classes.classes.php";

  include_once "views/pages-head.php";
?>
  <!--end::Head-->
  <!--begin::Body-->
  <script src="assets/js/custom/apexcharts.js"></script>

  <body id="kt_app_body" data-kt-app-header-fixed="true" data-kt-app-header-fixed-mobile="true" data-kt-app-sidebar-enabled="true" data-kt-app-sidebar-fixed="true" data-kt-app-sidebar-hoverable="true" data-kt-app-sidebar-push-toolbar="true" data-kt-app-sidebar-push-footer="true" data-kt-app-toolbar-enabled="true" data-kt-app-aside-enabled="true" data-kt-app-aside-fixed="true" data-kt-app-aside-push-toolbar="true" data-kt-app-aside-push-footer="true" class="app-default">
    <!--begin::Theme mode setup on page load-->
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
    <!--end::Theme mode setup on page load-->
    <!--begin::App-->
    <div class="d-flex flex-column flex-root app-root" id="kt_app_root">
      <!--begin::Page-->
      <div class="app-page flex-column flex-column-fluid" id="kt_app_page">
        <!--begin::Header-->
        <?php include_once "views/header.php"; ?>
        <!--end::Header-->
        <!--begin::Wrapper-->
        <div class="app-wrapper flex-column flex-row-fluid" id="kt_app_wrapper">
          <!--begin::Sidebar-->
          <?php include_once "views/sidebar.php"; ?>
          <!--end::Sidebar-->
          <!--begin::Main-->
          <div class="app-main flex-column flex-row-fluid" id="kt_app_main">
            <!--begin::Content wrapper-->
            <div class="d-flex flex-column flex-column-fluid">
              <!--begin::Toolbar-->
              <?php include_once "views/toolbar.php"; ?>
              <!--end::Toolbar-->
              <!--begin::Content-->
              <div id="kt_app_content" class="app-content flex-column-fluid">
                <!--begin::Content container-->
                <div id="kt_app_content_container" class="app-container container-fluid">
                  <!--begin::Card-->
                  <div class="card-body pt-5">


                    <div class="container mt-5">
                      <div class="container mt-5">
                        <div class="buttons" style="margin-bottom: 20px;">
                          <button data-period="daily" class="active btn btn-primary btn-sm"">Günlük</button>
                          <button data-period="weekly" class="btn btn-primary btn-sm btn-sm">Haftalık</button>
                          <button data-period="monthly" class="btn btn-primary btn-sm btn-sm">Aylık</button>
                          <button data-period="yearly" class="btn btn-primary btn-sm btn-sm">Yıllık</button>
                        </div>
                        <div id="chart"></div> <!-- EKLENDİ -->
                        <div id="table-container"></div>
                      </div>





                    </div>





                  </div>




                </div>
                <!--end::Card-->
              </div>
              <!--end::Content container-->
            </div>
            <!--end::Content-->
          </div>
          <!--end::Content wrapper-->
          <!--begin::Footer-->
          <?php include_once "views/footer.php"; ?>
          <!--end::Footer-->
        </div>
        <!--end:::Main-->
        <!--begin::aside-->
        <?php include_once "views/aside.php"; ?>
        <!--end::aside-->
      </div>
      <!--end::Wrapper-->
    </div>
    <!--end::Page-->
    </div>
    <!--end::App-->
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
    <!--end::Global Javascript Bundle-->
    <!--begin::Vendors Javascript(used for this page only)-->

    <script src="assets/js/widgets.bundle.js"></script>
    <script src="assets/js/custom/widgets.js"></script>


    <!--end::Custom Javascript-->
    <!--end::Javascript-->
  </body>
  <!--end::Body-->
  <script>
    let chartInstance = null;

    // Grafik çizme fonksiyonu
    function renderChart(data, periodType) { // periodType parametresini ekledik
      const categories = data.map(d => d.period);
      const avgPayments = data.map(d => parseFloat(d.avg_payment));
      const avgTaxes = data.map(d => parseFloat(d.avg_tax));

      // periodType için Türkçe karşılıklar
      const periodTypeTranslations = {
          daily: 'Günlük',
          weekly: 'Haftalık',
          monthly: 'Aylık',
          yearly: 'Yıllık'
      };
      const displayPeriodType = periodTypeTranslations[periodType] || periodType;

      const options = {
        chart: {
          type: 'bar',
          height: 400,
        },
        series: [{
            name: 'Ortalama Ödeme (₺)', // Türkçe isim ve para birimi
            data: avgPayments
          },
          {
            name: 'Ortalama Vergi (₺)', // Türkçe isim ve para birimi
            data: avgTaxes
          }
        ],
        xaxis: {
          categories: categories,
          title: {
            text: `${displayPeriodType} Bazında` // Türkçe başlık
          }
        },
        yaxis: {
          title: {
            text: 'Tutar (₺)' // Türkçe başlık
          }
        },
        tooltip: {
          y: {
            formatter: val => val.toFixed(2) + ' ₺'
          }
        },
        legend: {
          position: 'top'
        }
      };

      if (chartInstance) {
        chartInstance.updateOptions({
          series: options.series,
          xaxis: options.xaxis,
          title: options.xaxis.title, // Başlık güncellemesi için
          yaxis: options.yaxis // Y ekseni başlık güncellemesi için
        });
      } else {
        chartInstance = new ApexCharts(document.querySelector("#chart"), options);
        chartInstance.render();
      }
    }

    // Tablo oluşturma fonksiyonu
    function renderTable(data, periodType) {
        // periodType için Türkçe karşılıklar
        const periodTypeTranslations = {
            daily: 'Günlük',
            weekly: 'Haftalık',
            monthly: 'Aylık',
            yearly: 'Yıllık'
        };
        const displayPeriodType = periodTypeTranslations[periodType] || periodType;

        let tableHTML = `<h4 class="mt-5 mb-3">${displayPeriodType} Ortalama Ödeme ve Vergi Tablosu</h4>`;
        tableHTML += '<table class="table table-bordered table-striped table-hover">';
        tableHTML += '<thead>';
        tableHTML += '<tr>';
        tableHTML += `<th>Dönem (${displayPeriodType})</th>`; // Türkçe başlık
        tableHTML += '<th>Ortalama Ödeme (₺)</th>'; // Türkçe başlık
        tableHTML += '<th>Ortalama Vergi (₺)</th>'; // Türkçe başlık
        tableHTML += '</tr>';
        tableHTML += '</thead>';
        tableHTML += '<tbody>';

        let totalAvgPayment = 0; // Toplam ortalama ödeme için değişken
        let totalAvgTax = 0;     // Toplam ortalama vergi için değişken

        data.forEach(item => {
            const avgPayment = parseFloat(item.avg_payment);
            const avgTax = parseFloat(item.avg_tax);

            tableHTML += '<tr>';
            tableHTML += `<td>${item.period}</td>`;
            tableHTML += `<td>${avgPayment.toFixed(2)} ₺</td>`;
            tableHTML += `<td>${avgTax.toFixed(2)} ₺</td>`;
            tableHTML += '</tr>';

            totalAvgPayment += avgPayment;
            totalAvgTax += avgTax;
        });

        tableHTML += '</tbody>';
        // Toplam satırı ekle (ortalama değerlerin ortalamasını almak yerine, genellikle dönem başına ortalamaları gösteririz)
        // Eğer genel ortalama hesaplanacaksa, veri sayısına bölünmelidir.
        // Burada basitçe görüntülenen ortalamaların toplamını alalım, bu da ortalamanın ortalaması anlamına gelir.
        tableHTML += '<tfoot>';
        tableHTML += '<tr>';
        tableHTML += '<th class="text-end">Ortalamaların Toplamı:</th>'; // Türkçe başlık
        tableHTML += `<th>${totalAvgPayment.toFixed(2)} ₺</th>`;
        tableHTML += `<th>${totalAvgTax.toFixed(2)} ₺</th>`;
        tableHTML += '</tr>';
        tableHTML += '</tfoot>';


        tableHTML += '</table>';

        document.querySelector("#table-container").innerHTML = tableHTML;
    }

    // Raporu ve tabloyu getirme fonksiyonu
    function fetchReport(period) {
      $.ajax({
        url: 'includes/ajax.php?service=userpaymentreport',
        method: 'GET',
        data: {
          action: 'kullaniciBasinaGelir',
          period: period
        },
        dataType: 'json',
        success: function(res) {
          if (res && Array.isArray(res.data)) {
            renderChart(res.data, period); // period parametresini chart'a gönder
            renderTable(res.data, period); // period parametresini tabloya gönder
          } else {
            alert('Geçersiz veya boş veri alındı!'); // Türkçe mesaj
            document.querySelector("#chart").innerHTML = ''; // Grafiği temizle
            document.querySelector("#table-container").innerHTML = '<h4>Bu dönem için veri bulunamadı.</h4>'; // Tabloyu temizle
          }
        },
        error: function(xhr, status, error) {
          alert('Veri alınırken bir hata oluştu. Lütfen konsolu kontrol edin.'); // Türkçe mesaj
          console.error("AJAX Hatası:", status, error);
          document.querySelector("#chart").innerHTML = ''; // Hata durumunda grafiği temizle
          document.querySelector("#table-container").innerHTML = '<h4>Veri yüklenirken bir hata oluştu.</h4>'; // Hata durumunda tabloyu temizle
        }
      });
    }

    $(function() {
      // Sayfa yüklendiğinde varsayılan olarak günlük raporu getir
      fetchReport('daily');

      // Buton tıklama olayları
      $('.buttons button').click(function() {
        $('.buttons button').removeClass('active');
        $(this).addClass('active');
        const period = $(this).data('period');
        fetchReport(period);
      });
    });
</script>


</html>
<?php } else {
  header("location: index");
}
