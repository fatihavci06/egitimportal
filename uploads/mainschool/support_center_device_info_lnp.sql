-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Anamakine: 127.0.0.1
-- Üretim Zamanı: 12 May 2025, 11:05:17
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
-- Tablo için tablo yapısı `support_center_device_info_lnp`
--

CREATE TABLE `support_center_device_info_lnp` (
  `id` int(11) NOT NULL,
  `support_id` int(11) NOT NULL,
  `deviceType` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci NOT NULL,
  `deviceModel` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci NOT NULL,
  `deviceOs` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci NOT NULL,
  `browser` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci NOT NULL,
  `resolution` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci NOT NULL,
  `ipAddress` varchar(26) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Tablo döküm verisi `support_center_device_info_lnp`
--

INSERT INTO `support_center_device_info_lnp` (`id`, `support_id`, `deviceType`, `deviceModel`, `deviceOs`, `browser`, `resolution`, `ipAddress`) VALUES
(2, 14, 'Desktop', 'Unknown Model', 'Windows', 'Chrome', '1920x1080', '::1');

--
-- Dökümü yapılmış tablolar için indeksler
--

--
-- Tablo için indeksler `support_center_device_info_lnp`
--
ALTER TABLE `support_center_device_info_lnp`
  ADD PRIMARY KEY (`id`);

--
-- Dökümü yapılmış tablolar için AUTO_INCREMENT değeri
--

--
-- Tablo için AUTO_INCREMENT değeri `support_center_device_info_lnp`
--
ALTER TABLE `support_center_device_info_lnp`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
