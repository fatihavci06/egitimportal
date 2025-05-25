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
                                            <h3 class="mb-4">Ödeme ve Vergi Grafiği</h3>
                                            <div class="mb-3">
                                                <select id="viewType" class="form-select w-25">
                                                    <option value="daily">Günlük</option>
                                                    <option value="weekly">Haftalık</option>
                                                    <option value="monthly" selected>Aylık</option>
                                                    <option value="yearly">Yıllık</option>
                                                </select>
                                            </div>
                                            <div id="chart"></div>
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
    // Grafik çizme fonksiyonu (mevcut kodunuzdan alındı)
    function renderChart(data, labelKey, periodType) { // periodType parametresini ekledik
        const labels = data.map(item => item[labelKey]);
        const paymentSeries = data.map(item => parseFloat(item.total_payment));
        const taxSeries = data.map(item => parseFloat(item.total_tax));

        // periodType için Türkçe karşılıkları
        const periodTypeTranslations = {
            daily: 'Günlük',
            weekly: 'Haftalık',
            monthly: 'Aylık',
            yearly: 'Yıllık'
        };
        const displayPeriodType = periodTypeTranslations[periodType] || periodType; // Eğer eşleşme yoksa orijinali kullan

        const options = {
            chart: {
                type: 'bar',
                height: 400
            },
            series: [{
                name: 'Ödeme', // Türkçe
                data: paymentSeries
            }, {
                name: 'Vergi', // Türkçe
                data: taxSeries
            }],
            xaxis: {
                categories: labels,
                title: {
                    text: `${displayPeriodType} Bazında` // Türkçe başlık
                }
            },
            dataLabels: {
                enabled: false
            },
            tooltip: {
                y: {
                    formatter: val => val.toFixed(2) + " ₺"
                }
            },
            yaxis: {
                title: {
                    text: 'Tutar (₺)' // Türkçe Y ekseni başlığı
                }
            }
        };

        // Eğer mevcut bir chart nesnesi varsa onu yok et
        // Bu, ApexCharts'ın eski grafik nesnelerinin bellekten düzgün bir şekilde temizlenmesini sağlar.
        if (window.myPaymentChart) {
            window.myPaymentChart.destroy();
        }
        const chartElement = document.querySelector("#chart");
        if (chartElement) {
             window.myPaymentChart = new ApexCharts(chartElement, options);
             window.myPaymentChart.render();
        }
    }

    // Tablo oluşturma fonksiyonu
    function renderTable(data, periodType, labelKey) {
        let totalPaymentSum = 0;
        let totalTaxSum = 0;

        // periodType için Türkçe karşılıkları
        const periodTypeTranslations = {
            daily: 'Günlük',
            weekly: 'Haftalık',
            monthly: 'Aylık',
            yearly: 'Yıllık'
        };
        const displayPeriodType = periodTypeTranslations[periodType] || periodType;

        let tableHTML = '<h4 class="mt-5 mb-3">Ödeme ve Vergi Tablosu</h4>'; // Türkçe başlık
        tableHTML += '<table class="table table-bordered table-striped table-hover">';
        tableHTML += '<thead>';
        tableHTML += '<tr>';
        tableHTML += `<th>Dönem (${displayPeriodType})</th>`; // Türkçe dönem başlığı
        tableHTML += '<th>Toplam Ödeme (₺)</th>'; // Türkçe başlık
        tableHTML += '<th>Toplam KDV (₺)</th>'; // Türkçe başlık
        tableHTML += '</tr>';
        tableHTML += '</thead>';
        tableHTML += '<tbody>';

        data.forEach(item => {
            const periodLabel = item[labelKey];
            const payment = parseFloat(item.total_payment);
            const tax = parseFloat(item.total_tax);

            totalPaymentSum += payment;
            totalTaxSum += tax;

            tableHTML += '<tr>';
            tableHTML += `<td>${periodLabel}</td>`;
            tableHTML += `<td>${payment.toFixed(2)}</td>`;
            tableHTML += `<td>${tax.toFixed(2)}</td>`;
            tableHTML += '</tr>';
        });

        tableHTML += '</tbody>';
        
        // Toplam satırı ekle
        tableHTML += '<tfoot>';
        tableHTML += '<tr>';
        tableHTML += '<th colspan="1" class="text-end">Genel Toplam:</th>'; // Türkçe başlık
        tableHTML += `<th>${totalPaymentSum.toFixed(2)} ₺</th>`;
        tableHTML += `<th>${totalTaxSum.toFixed(2)} ₺</th>`;
        tableHTML += '</tr>';
        tableHTML += '</tfoot>';

        tableHTML += '</table>';

        document.querySelector("#table-container").innerHTML = tableHTML;
    }

    // Hem grafik hem de tabloyu yükleyen ana fonksiyon
    function loadChartAndTable(viewType) {
        $.getJSON("includes/ajax.php?service=graphicreport", function(response) {
            if (response.error) {
                alert("API Hatası: " + response.error);
                return;
            }

            let data = response[viewType];
            if (!data) {
                alert("Veri bulunamadı: " + viewType);
                document.querySelector("#chart").innerHTML = '';
                document.querySelector("#table-container").innerHTML = '<h4>Bu dönem için veri bulunamadı.</h4>';
                return;
            }

            document.querySelector("#chart").innerHTML = '';
            document.querySelector("#table-container").innerHTML = '';

            const labelKey = {
                daily: 'day',
                weekly: 'week',
                monthly: 'period',
                yearly: 'year'
            }[viewType];

            renderChart(data, labelKey, viewType); // viewType'ı renderChart'a da gönderiyoruz
            renderTable(data, viewType, labelKey);
        });
    }

    // Sayfa yüklendiğinde ilk grafik ve tabloyu yükle
    $(document).ready(function() {
        loadChartAndTable('monthly');

        $('#viewType').on('change', function() {
            loadChartAndTable($(this).val());
        });
    });
</script>

</html>
<?php } else {
    header("location: index");
}
