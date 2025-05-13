-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Anamakine: 127.0.0.1
-- Üretim Zamanı: 12 May 2025, 11:05:08
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
-- Tablo için tablo yapısı `support_center_subjects_lnp`
--

CREATE TABLE `support_center_subjects_lnp` (
  `id` int(11) NOT NULL,
  `name` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci NOT NULL,
  `slug` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Tablo döküm verisi `support_center_subjects_lnp`
--

INSERT INTO `support_center_subjects_lnp` (`id`, `name`, `slug`, `created_at`, `updated_at`) VALUES
(1, 'Şikayet', 'sikayet', '2025-05-08 08:21:44', '2025-05-08 08:21:44'),
(2, 'Öneri', 'oneri', '2025-05-08 08:21:44', '2025-05-08 08:21:44'),
(3, 'Soru', 'soru', '2025-05-08 08:22:22', '2025-05-08 08:22:22'),
(4, 'Teknik Destek', 'teknik-destek', '2025-05-08 08:22:22', '2025-05-08 08:22:22');

--
-- Dökümü yapılmış tablolar için indeksler
--

--
-- Tablo için indeksler `support_center_subjects_lnp`
--
ALTER TABLE `support_center_subjects_lnp`
  ADD PRIMARY KEY (`id`);

--
-- Dökümü yapılmış tablolar için AUTO_INCREMENT değeri
--

--
-- Tablo için AUTO_INCREMENT değeri `support_center_subjects_lnp`
--
ALTER TABLE `support_center_subjects_lnp`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
