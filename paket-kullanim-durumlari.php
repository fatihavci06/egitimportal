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
                      <h2>En Çok Tercih Edilen 10 Paket</h2>
                      <div id="chart"></div>
                      <div id="table-container"></div>




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
    // Grafik çizme fonksiyonu
    function renderChart(data) {
        // X-axis etiketleri: class_name ve package_name
        const labels = data.map(item => `${item.class_name} - ${item.package_name}`);

        // Buyer count değerleri (y ekseni)
        const buyerCounts = data.map(item => Number(item.buyer_count));

        const options = {
            chart: {
                type: 'bar',
                height: 400
            },
            series: [{
                name: 'Satın Alan Kişi Sayısı', // Türkçe
                data: buyerCounts
            }],
            xaxis: {
                categories: labels,
                labels: {
                    rotate: 0, // Yazıları dik olarak (0 derece) yapıyoruz
                    style: {
                        fontSize: '9px',
                        whiteSpace: 'normal', // Satır kaydırmaya izin verir
                    }
                },
                title: {
                    text: 'Sınıf - Paket Adı' // Türkçe
                }
            },
            yaxis: {
                title: {
                    text: 'Satın Alan Kişi Sayısı' // Türkçe
                }
            },
            tooltip: {
                y: {
                    formatter: function(val, { dataPointIndex }) {
                        const item = data[dataPointIndex];
                        return `${val} kişi<br>Sınıf: ${item.class_name}<br>Paket: ${item.package_name}`; // Türkçe
                    }
                }
            },
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: '50%',
                    endingShape: 'rounded'
                }
            }
        };

        // Eğer mevcut bir chart nesnesi varsa onu yok et
        // Bu, ApexCharts'ın eski grafik nesnelerinin bellekten düzgün bir şekilde temizlenmesini sağlar.
        if (window.myTopPackagesChart) { // Bu rapor için farklı bir global değişken adı
            window.myTopPackagesChart.destroy();
        }
        const chartElement = document.querySelector("#chart");
        if (chartElement) {
            window.myTopPackagesChart = new ApexCharts(chartElement, options);
            window.myTopPackagesChart.render();
        }
    }

    // Tablo oluşturma fonksiyonu
    function renderTable(data) {
        let tableHTML = '<h4 class="mt-5 mb-3">En Çok Satan Paketler Tablosu</h4>'; // Türkçe başlık
        tableHTML += '<table class="table table-bordered table-striped table-hover">';
        tableHTML += '<thead>';
        tableHTML += '<tr>';
        tableHTML += '<th>Sıra</th>'; // Türkçe
        tableHTML += '<th>Sınıf Adı</th>'; // Türkçe
        tableHTML += '<th>Paket Adı</th>'; // Türkçe
        tableHTML += '<th>Satın Alan Kişi Sayısı</th>'; // Türkçe
        tableHTML += '</tr>';
        tableHTML += '</thead>';
        tableHTML += '<tbody>';

        let totalBuyerCount = 0; // Toplam kişi sayısı için

        data.forEach((item, index) => {
            tableHTML += '<tr>';
            tableHTML += `<td>${index + 1}</td>`; // Sıra numarası
            tableHTML += `<td>${item.class_name}</td>`;
            tableHTML += `<td>${item.package_name}</td>`;
            tableHTML += `<td>${item.buyer_count}</td>`;
            tableHTML += '</tr>';

            totalBuyerCount += Number(item.buyer_count);
        });

        tableHTML += '</tbody>';
        
        // Toplam satırı ekle
        tableHTML += '<tfoot>';
        tableHTML += '<tr>';
        tableHTML += '<th colspan="3" class="text-end">Toplam Satın Alan Kişi Sayısı:</th>'; // Türkçe başlık
        tableHTML += `<th>${totalBuyerCount}</th>`;
        tableHTML += '</tr>';
        tableHTML += '</tfoot>';

        tableHTML += '</table>';

        document.querySelector("#table-container").innerHTML = tableHTML;
    }

    // Veriyi çekme ve hem grafik hem de tabloyu render etme
    fetch('includes/ajax.php?service=toppackages')
      .then(res => res.json())
      .then(data => {
        if (data.status !== 'success') {
          alert('Veri alınamadı: ' + (data.message || 'Bilinmeyen hata')); // Türkçe hata mesajı
          document.querySelector("#chart").innerHTML = '<h4>Veri yüklenemedi.</h4>';
          document.querySelector("#table-container").innerHTML = '<h4>Veri yüklenemedi.</h4>';
          return;
        }

        const realData = data.data;

        // PHP tarafından sıralı geldiği varsayımıyla, JavaScript tarafında ek sıralamaya gerek yok.
        // Eğer PHP'den sıralı gelmiyorsa, burada sort edilebilir:
        // realData.sort((a, b) => b.buyer_count - a.buyer_count); // Çoktan aza sırala

        renderChart(realData); // Grafiği çiz
        renderTable(realData); // Tabloyu oluştur
      })
      .catch(err => {
        alert('Veri alınırken bir hata oluştu: ' + err.message); // Türkçe hata mesajı
        console.error("Fetch Hatası:", err);
        document.querySelector("#chart").innerHTML = '<h4>Veri yüklenirken bir hata oluştu.</h4>';
        document.querySelector("#table-container").innerHTML = '<h4>Veri yüklenirken bir hata oluştu.</h4>';
      });
</script>



</html>
<?php } else {
  header("location: index");
}
