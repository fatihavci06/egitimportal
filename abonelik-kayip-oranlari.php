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
                      <select id="time-range" style="margin-bottom: 20px;" class="form-select w-25">
                        <option value="daily">Günlük</option>
                        <option value="weekly">Haftalık</option>
                        <option value="monthly">Aylık</option>
                        <option value="yearly">Yıllık</option>
                      </select>


<div id="chart"></div>

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
  let chart; // ApexCharts örneğini dışarıda tanımlıyoruz
  let chartData = null; // API'den gelen tüm veriyi saklayacağız

  // Grafik oluşturma fonksiyonu
  function renderChart(dataArray, title) {
    const labels = dataArray.map(item => item.day || item.week || item.period || item.year);
    const counts = dataArray.map(item => Number(item.user_count));

    const options = {
      chart: {
        type: 'bar',
        height: 400
      },
      series: [{
        name: 'Aboneliği Bitmiş Kullanıcı Sayısı',
        data: counts
      }],
      xaxis: {
        categories: labels,
        labels: {
          rotate: -45,
          style: {
            fontSize: '12px'
          }
        }
      },
      yaxis: {
        title: {
          text: 'Kullanıcı Sayısı'
        }
      },
      title: {
        text: title,
        align: 'center'
      },
      tooltip: {
        y: {
          formatter: val => `${val} kişi`
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

    if (chart) {
      chart.updateOptions(options);
    } else {
      chart = new ApexCharts(document.querySelector("#chart"), options);
      chart.render();
    }
  }

  // Seçim değiştiğinde grafiği güncelle
  document.getElementById('time-range').addEventListener('change', function () {
    const selected = this.value;
    if (chartData && chartData[selected]) {
      const titleMap = {
        daily: "Aboneliği Bitmiş Kullanıcılar",
        weekly: "Aboneliği Bitmiş Kullanıcılar",
        monthly: "Aboneliği Bitmiş Kullanıcılar",
        yearly: "Aboneliği Bitmiş Kullanıcılar"
      };
      renderChart(chartData[selected], titleMap[selected]);
    }
  });

  // Veriyi çek ve başlangıç grafiğini oluştur
  fetch('includes/ajax.php?service=expired-user-count-report')
    .then(res => res.json())
    .then(data => {
      if (data.status !== 'success') {
        alert('Veri alınamadı: ' + (data.message || 'Bilinmeyen hata'));
        return;
      }

      chartData = data;

      // Varsayılan olarak günlük veriyi göster
      document.getElementById('time-range').value = 'daily';
      renderChart(data.daily, 'Aboneliği Bitmiş Kullanıcılar');
    })
    .catch(err => alert('Hata: ' + err.message));
</script>



</html>
<?php } else {
  header("location: index");
}
