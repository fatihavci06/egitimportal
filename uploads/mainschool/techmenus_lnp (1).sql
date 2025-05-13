-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Anamakine: 127.0.0.1
-- Üretim Zamanı: 12 May 2025, 11:05:52
-- Sunucu sürümü: 10.4.24-MariaDB
-- PHP Sürümü: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Veritabanı: `lineup25academy00`
--

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `techmenus_lnp`
--

CREATE TABLE `techmenus_lnp` (
  `id` int(11) NOT NULL,
  `name` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci NOT NULL,
  `slug` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci NOT NULL,
  `parent` int(11) NOT NULL,
  `role` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci NOT NULL,
  `orderNo` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Tablo döküm verisi `techmenus_lnp`
--

INSERT INTO `techmenus_lnp` (`id`, `name`, `slug`, `parent`, `role`, `orderNo`, `created_at`, `updated_at`) VALUES
(1, 'Ekip Üyeleri', 'ekip-uyeleri', 1, '1', 1, '2025-05-08 20:41:01', '2025-05-08 20:41:01'),
(2, 'Açık Destek Talepleri', 'acik-destek-talepleri', 2, '1,6,7', 2, '2025-05-08 20:41:01', '2025-05-10 19:27:14'),
(3, 'Kapalı Destek Talepleri', 'kapali-destek-talepleri', 2, '1,6,7', 3, '2025-05-08 20:41:01', '2025-05-10 19:27:18'),
(4, 'Kullanıcı Sorun Geçmişi', 'kullanici-sorun-gecmisi', 2, '1,6,7', 4, '2025-05-08 20:41:01', '2025-05-10 19:27:24'),
(5, 'Bildirimler', 'sistem-bildirimleri', 3, '1,6,7', 5, '2025-05-08 20:41:01', '2025-05-10 19:27:26'),
(6, 'Erişim Bilgileri', 'erisim-bilgileri', 4, '1,6,7', 6, '2025-05-08 20:41:01', '2025-05-10 19:27:29'),
(7, 'Yetkisiz Erişim Denemeleri', 'yetkisiz-erisim-denemeleri', 4, '1,6,7', 7, '2025-05-08 20:41:01', '2025-05-10 19:27:32');

--
-- Dökümü yapılmış tablolar için indeksler
--

--
-- Tablo için indeksler `techmenus_lnp`
--
ALTER TABLE `techmenus_lnp`
  ADD PRIMARY KEY (`id`);

--
-- Dökümü yapılmış tablolar için AUTO_INCREMENT değeri
--

--
-- Tablo için AUTO_INCREMENT değeri `techmenus_lnp`
--
ALTER TABLE `techmenus_lnp`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
