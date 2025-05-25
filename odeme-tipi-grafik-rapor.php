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
                                            <div style="text-align:center; margin: 20px;">
                                                <select id="periodSelect" class="form-select w-25">
                                                    <option value="daily">Günlük</option>
                                                    <option value="weekly">Haftalık</option>
                                                    <option value="monthly" selected>Aylık</option>
                                                    <option value="yearly">Yıllık</option>
                                                </select>
                                            </div>

                                            <div id="chart" style="max-width: 1000px; margin: auto;"></div>
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
    function renderChart(data, periodType) {
        const periods = [...new Set(data.map(item => item.period))];
        const paymentTypes = [...new Set(data.map(item => item.payment_type))];

        const series = [];

        paymentTypes.forEach(type => {
            // Ödeme serisi
            series.push({
                name: `${type} - Ödeme`,
                data: periods.map(period => {
                    const found = data.find(d => d.period === period && d.payment_type === type);
                    return found ? parseFloat(found.total_payment) : 0;
                })
            });

            // KDV serisi
            series.push({
                name: `${type} - KDV`,
                data: periods.map(period => {
                    const found = data.find(d => d.period === period && d.payment_type === type);
                    return found ? parseFloat(found.total_tax) : 0;
                })
            });
        });

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
                height: 500,
                stacked: true // Yığılmış bar chart
            },
            series: series,
            xaxis: {
                categories: periods,
                title: { text: `${displayPeriodType} Bazında` } // Türkçe başlık
            },
            yaxis: {
                title: { text: 'Tutar (₺)' } // Türkçe başlık
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

        // Eğer mevcut bir chart nesnesi varsa onu yok et
        if (window.myPaymentTypeChart) {
            window.myPaymentTypeChart.destroy();
        }
        const chartElement = document.querySelector("#chart");
        if (chartElement) {
            window.myPaymentTypeChart = new ApexCharts(chartElement, options);
            window.myPaymentTypeChart.render();
        }
    }

    // Tablo oluşturma fonksiyonu (payment_type bazında)
    function renderTable(data, periodType) {
        const periods = [...new Set(data.map(item => item.period))];
        const paymentTypes = [...new Set(data.map(item => item.payment_type))];

        // periodType için Türkçe karşılıkları
        const periodTypeTranslations = {
            daily: 'Günlük',
            weekly: 'Haftalık',
            monthly: 'Aylık',
            yearly: 'Yıllık'
        };
        const displayPeriodType = periodTypeTranslations[periodType] || periodType;

        let tableHTML = '<h4 class="mt-5 mb-3">Ödeme Tipi Bazında Özet Tablosu</h4>';
        tableHTML += '<table class="table table-bordered table-striped table-hover">';
        tableHTML += '<thead>';
        tableHTML += '<tr>';
        tableHTML += `<th>Dönem (${displayPeriodType})</th>`; // Türkçe başlık
        paymentTypes.forEach(type => {
            tableHTML += `<th colspan="2" class="text-center">${type}</th>`;
        });
        tableHTML += `<th colspan="2" class="text-center">Genel Toplam</th>`; // Türkçe başlık
        tableHTML += '</tr>';
        tableHTML += '<tr>';
        tableHTML += '<th></th>'; // Boş köşe
        paymentTypes.forEach(type => {
            tableHTML += '<th>Ödeme</th>'; // Türkçe başlık
            tableHTML += '<th>KDV</th>'; // Türkçe başlık
        });
        tableHTML += '<th>Toplam Ödeme</th>'; // Türkçe başlık
        tableHTML += '<th>Toplam KDV</th>'; // Türkçe başlık
        tableHTML += '</tr>';
        tableHTML += '</thead>';
        tableHTML += '<tbody>';

        let overallTotalPayment = 0;
        let overallTotalTax = 0;

        periods.forEach(period => {
            tableHTML += '<tr>';
            tableHTML += `<td>${period}</td>`;
            let periodTotalPayment = 0;
            let periodTotalTax = 0;

            paymentTypes.forEach(type => {
                const found = data.find(d => d.period === period && d.payment_type === type);
                const totalPayment = found ? parseFloat(found.total_payment) : 0;
                const totalTax = found ? parseFloat(found.total_tax) : 0;

                tableHTML += `<td>${totalPayment.toFixed(2)} ₺</td>`;
                tableHTML += `<td>${totalTax.toFixed(2)} ₺</td>`;

                periodTotalPayment += totalPayment;
                periodTotalTax += totalTax;
            });
            
            // Dönem bazında toplamları ekle
            tableHTML += `<td>${periodTotalPayment.toFixed(2)} ₺</td>`;
            tableHTML += `<td>${periodTotalTax.toFixed(2)} ₺</td>`;

            overallTotalPayment += periodTotalPayment;
            overallTotalTax += periodTotalTax;

            tableHTML += '</tr>';
        });

        tableHTML += '</tbody>';

        // En alt toplam satırı
        tableHTML += '<tfoot>';
        tableHTML += '<tr>';
        tableHTML += `<th class="text-end">Genel Toplam:</th>`; // Türkçe başlık
        paymentTypes.forEach(type => {
             tableHTML += `<th colspan="2"></th>`; 
        });
        tableHTML += `<th>${overallTotalPayment.toFixed(2)} ₺</th>`;
        tableHTML += `<th>${overallTotalTax.toFixed(2)} ₺</th>`;
        tableHTML += '</tr>';
        tableHTML += '</tfoot>';

        tableHTML += '</table>';

        document.querySelector("#table-container").innerHTML = tableHTML;
    }

    // Hem grafik hem de tabloyu yükleyen ana fonksiyon
    function loadChartAndTable(periodType = 'monthly') {
        $.getJSON('includes/ajax.php?service=paymenttypegraphicreport', function (response) {
            if (response.error) {
                alert("API Hatası: " + response.error);
                return;
            }

            let data = response[periodType];
            if (!data) {
                alert("Veri bulunamadı: " + periodType);
                document.querySelector("#chart").innerHTML = '';
                document.querySelector("#table-container").innerHTML = '<h4>Bu dönem için veri bulunamadı.</h4>';
                return;
            }

            // Aylık ise kronolojik sıralama (PHP tarafından tersine çevrilmiş olarak geldiği varsayılır)
            // Eğer PHP tarafında tersine çevrilmiyorsa, bu sıralama bloğunu aktif edebilirsiniz.
            /*
            if (periodType === 'monthly') {
                data.sort((a, b) => {
                    const dateA = new Date(a.period + '-01');
                    const dateB = new Date(b.period + '-01');
                    return dateA - dateB;
                });
            }
            */

            document.querySelector("#chart").innerHTML = '';
            document.querySelector("#table-container").innerHTML = '';

            renderChart(data, periodType); // Grafiği çiz
            renderTable(data, periodType); // Tabloyu oluştur
        });
    }

    // Olay dinleyicileri ve ilk yükleme
    $(document).ready(function() {
        $('#periodSelect').on('change', function () {
            loadChartAndTable(this.value);
        });

        loadChartAndTable(); // sayfa ilk açıldığında aylık olarak yükle
    });
</script>

</html>
<?php } else {
    header("location: index");
}
