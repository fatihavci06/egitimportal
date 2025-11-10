<?php
if (!defined('GUARD')) {
	die('Erişim yasak!');
}
?>
<!--begin::Head-->

<head>
	<base href="https://lineupcampus.com/online/" />
<?php
// 1️⃣ Mevcut URL'yi al
$currentUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http");
$currentUrl .= "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

// 2️⃣ Base URL'yi ortama göre belirle
if (strpos($currentUrl, 'localhost') !== false) {
    // Local ortam
    $base = "http://localhost/lineup_campus/";
} else {
    // Canlı ortam
    $base = "https://lineupcampus.com/online/";
}

// 3️⃣ Base kısmı kaldır
$page = str_replace($base, '', $currentUrl);

// 4️⃣ Sondaki / karakterlerini temizle
$page = trim($page, '/');

// 5️⃣ .php ve sonrasını tamamen kaldır (örnek: odev-listele.php?id=5 → odev-listele)
$page = preg_replace('/\.php.*/i', '', $page);

// 6️⃣ Eğer / varsa sadece son kısmı al (örnek: ders/turkce → turkce)
if (str_contains($page, '/')) {
    $parts = explode('/', $page);
    $page = end($parts);
}

// 7️⃣ Tireleri boşlukla değiştir
$page = str_replace('-', ' ', $page);

// 8️⃣ lesson_name parametresi varsa onu başlık olarak kullan
$lessonName = isset($_GET['lesson_name']) ? trim($_GET['lesson_name']) : null;
if (!empty($lessonName)) {
    $page = $lessonName;
}

// 9️⃣ Özel durum: ana-okulu-icerikler_icerik sayfası → “İçerikler”
if (str_contains($currentUrl, 'ana-okulu-icerikler_icerik')) {
    $page = 'İçerikler';
}elseif (str_contains($currentUrl, 'ana-okulu-icerikler?lesson_id=9')) {
    $page = 'İngilizce';
}elseif (str_contains($currentUrl, 'ana-okulu-icerikler_konu')) {
    $page = 'Konular';
}

// 🔟 Özel Türkçe kelime dönüştürme (manuel çeviri listesi)
$custom_words = [
    'turkce' => 'Türkçe',
    'matematik' => 'Matematik',
	'ingilizce' => 'İngilizce',
    'odev' => 'Ödev',
    'listele' => 'Listele',
    'profilim' => 'Profilim',
    'etkinlikler' => 'Etkinlikler',
    'anasayfa' => 'Ana Sayfa',
	'ogrenci' => 'Öğrenci',
	'haftalik' => 'Haftalık',
	'gorev' => 'Görev',
	'ogretimi' => 'Öğretimi',
	'ogrenen' => 'Öğrenen',
	'toplantilar' => 'Toplantılar',
	'icerikler' => 'İçerikler',
	'yazili'=>'Yazılı',
	'Ilerleme'=>'İlerleme',
	'kutuphane'=>'Kütüphane',
	'konusma'=>'Konuşma',
	'kulubu'=>'Kulübü',
	'dashboard'=>'Anasayfa',
	'giris'=>'Giriş',
	'suphe'=>'Şüphe',
	'veritabani'=>'Veritabanı',
	'yas'=>'Yaş',
	'onem'=>'Önem',
	'basliklari'=>'Başlıkları',
	'icerik'=>'İçerik',
	'yonetimi'=>'Yönetimi',
	'unite'=>'Ünite',
	'kuponlari'=>'Kuponları',
	'satin'=>'Satın',
	'ogretmen'=>'Öğretmen',
	'ozel'=>'Özel',
	'koc'=>'Koç',
	'canli'=>'Canlı',
	''
];


// 1️⃣1️⃣ lesson_name parametresi varsa özel çeviriyi atla
if (empty($lessonName) && $page !== 'İçerikler') {
    $lower = mb_strtolower($page, 'UTF-8');
    foreach ($custom_words as $key => $val) {
        $lower = str_replace($key, $val, $lower);
    }
    $page = ucwords($lower, " \t\r\n\f\v");
}

// 1️⃣2️⃣ Boşsa varsayılan başlık
if (trim($page) === '') {
    $page = 'Ana Sayfa';
}

// 1️⃣3️⃣ Güvenli yazdır
?>
<title><?php echo htmlspecialchars($page); ?> - LineUp Campus</title>




	<meta charset="utf-8" />
	<meta name="description" content="LineUp Campus" />
	<meta name="keywords" content="Saul, bootstrap, bootstrap 5, dmin themes, free admin themes, bootstrap admin, bootstrap dashboard" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<meta property="og:locale" content="tr_TR" />
	<meta property="og:type" content="article" />
	<meta property="og:title" content="LineUp Campus" />
	<meta property="og:url" content="https://keenthemes.com/products/saul-html-pro" />
	<meta property="og:site_name" content="LineUp Campus" />
	<link rel="canonical" href="http://preview.keenthemes.comapps/calendar.html" />
	<link rel="canonical" href="http://preview.keenthemes.comauthentication/sign-in/basic.html" />
	<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/tr.js"></script>
	<link rel="shortcut icon" href="assets/media/logos/lineup-campus-logo.ico" />
	<!--begin::Fonts(mandatory for all pages)-->
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />
	<!--end::Fonts-->
	<!--begin::Vendor Stylesheets(used for this page only)-->
	<link href="assets/plugins/custom/fullcalendar/fullcalendar.bundle.css" rel="stylesheet" type="text/css" />
	<link href="assets/plugins/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />
	<!--begin::Global Stylesheets Bundle(mandatory for all pages)-->
	<link href="assets/plugins/global/plugins.bundle.css" rel="stylesheet" type="text/css" />
	<link href="assets/css/style.bundle.css" rel="stylesheet" type="text/css" />
	<script src="https://cdn.jsdelivr.net/npm/ua-parser-js@1.0.32/src/ua-parser.min.js"></script>
	<link href="https://fonts.googleapis.com/css2?family=Comic+Relief:wght@400;700&display=swap" rel="stylesheet">
	<!--end::Global Stylesheets Bundle-->
	<script>
		// Frame-busting to prevent site from being loaded within a frame without permission (click-jacking) if (window.top != window.self) { window.top.location.replace(window.self.location.href); }
	</script>
</head>
<!--end::Head-->