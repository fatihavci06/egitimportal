-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Anamakine: 127.0.0.1
-- Üretim Zamanı: 12 May 2025, 11:06:22
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
-- Tablo için tablo yapısı `logininfo_lnp`
--

CREATE TABLE `logininfo_lnp` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `session_id` int(11) NOT NULL,
  `deviceType` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci NOT NULL,
  `deviceModel` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci NOT NULL,
  `deviceOs` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci NOT NULL,
  `browser` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci NOT NULL,
  `resolution` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci NOT NULL,
  `ipAddress` varchar(26) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci NOT NULL,
  `loginTime` datetime NOT NULL,
  `logoutTime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Tablo döküm verisi `logininfo_lnp`
--

INSERT INTO `logininfo_lnp` (`id`, `user_id`, `session_id`, `deviceType`, `deviceModel`, `deviceOs`, `browser`, `resolution`, `ipAddress`, `loginTime`, `logoutTime`) VALUES
(2, 32, 1449131029, 'Desktop', 'Unknown Model', 'Windows', 'Chrome', '1920x1080', '::1', '2025-05-08 16:58:20', '0000-00-00 00:00:00'),
(3, 32, 592630459, 'Desktop', 'Unknown Model', 'Windows', 'Chrome', '1920x1080', '::1', '2025-05-08 17:11:01', '2025-05-08 17:11:10'),
(4, 32, 617296705, 'Desktop', 'Unknown Model', 'Windows', 'Chrome', '1920x1080', '::1', '2025-05-08 18:12:51', '2025-05-08 18:15:19'),
(5, 1, 1255820668, 'Desktop', 'Unknown Model', 'Windows', 'Chrome', '1920x1080', '::1', '2025-05-08 22:08:07', '0000-00-00 00:00:00'),
(6, 32, 862283834, 'Desktop', 'Unknown Model', 'Windows', 'Chrome', '1920x1080', '::1', '2025-05-08 22:35:43', '0000-00-00 00:00:00'),
(7, 67, 1800205257, 'Desktop', 'Unknown Model', 'Windows', 'Firefox', '1920x1080', '127.0.0.1', '2025-05-10 22:19:32', '0000-00-00 00:00:00'),
(8, 67, 217132426, 'Desktop', 'Unknown Model', 'Windows', 'Firefox', '1920x1080', '127.0.0.1', '2025-05-10 22:26:36', '2025-05-10 23:02:50'),
(9, 68, 419361376, 'Desktop', 'Unknown Model', 'Windows', 'Firefox', '1920x1080', '127.0.0.1', '2025-05-10 23:02:56', '0000-00-00 00:00:00'),
(10, 68, 1271680026, 'Desktop', 'Unknown Model', 'Windows', 'Firefox', '1920x1080', '127.0.0.1', '2025-05-10 23:03:10', '0000-00-00 00:00:00'),
(11, 67, 464095980, 'Desktop', 'Unknown Model', 'Windows', 'Firefox', '1920x1080', '127.0.0.1', '2025-05-10 23:04:59', '2025-05-10 23:05:09'),
(12, 68, 1722853377, 'Desktop', 'Unknown Model', 'Windows', 'Firefox', '1920x1080', '127.0.0.1', '2025-05-10 23:05:18', '0000-00-00 00:00:00'),
(13, 69, 582666419, 'Desktop', 'Unknown Model', 'Windows', 'Firefox', '1920x1080', '127.0.0.1', '2025-05-10 23:06:10', '0000-00-00 00:00:00'),
(14, 69, 132799640, 'Desktop', 'Unknown Model', 'Windows', 'Firefox', '1920x1080', '127.0.0.1', '2025-05-10 23:07:14', '0000-00-00 00:00:00'),
(15, 69, 2129980424, 'Desktop', 'Unknown Model', 'Windows', 'Firefox', '1920x1080', '127.0.0.1', '2025-05-10 23:07:32', '0000-00-00 00:00:00'),
(16, 67, 487700255, 'Desktop', 'Unknown Model', 'Windows', 'Firefox', '1920x1080', '127.0.0.1', '2025-05-10 23:08:36', '2025-05-10 23:08:40'),
(17, 67, 599764038, 'Desktop', 'Unknown Model', 'Windows', 'Firefox', '1920x1080', '127.0.0.1', '2025-05-10 23:09:29', '2025-05-10 23:17:06'),
(18, 68, 1060122714, 'Desktop', 'Unknown Model', 'Windows', 'Firefox', '1920x1080', '127.0.0.1', '2025-05-10 23:17:16', '2025-05-10 23:29:10'),
(19, 67, 1315331714, 'Desktop', 'Unknown Model', 'Windows', 'Firefox', '1920x1080', '127.0.0.1', '2025-05-10 23:29:17', '2025-05-10 23:29:40'),
(20, 68, 623784210, 'Desktop', 'Unknown Model', 'Windows', 'Firefox', '1920x1080', '127.0.0.1', '2025-05-10 23:29:48', '0000-00-00 00:00:00'),
(21, 32, 1836093219, 'Desktop', 'Unknown Model', 'Windows', 'Chrome', '1920x1080', '::1', '2025-05-10 23:32:26', '2025-05-12 09:38:40'),
(22, 1, 803169699, 'Desktop', 'Unknown Model', 'Windows', 'Chrome', '1920x1080', '::1', '2025-05-12 11:56:43', '0000-00-00 00:00:00');

--
-- Dökümü yapılmış tablolar için indeksler
--

--
-- Tablo için indeksler `logininfo_lnp`
--
ALTER TABLE `logininfo_lnp`
  ADD PRIMARY KEY (`id`);

--
-- Dökümü yapılmış tablolar için AUTO_INCREMENT değeri
--

--
-- Tablo için AUTO_INCREMENT değeri `logininfo_lnp`
--
ALTER TABLE `logininfo_lnp`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
