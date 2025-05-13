-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Anamakine: 127.0.0.1
-- Üretim Zamanı: 12 May 2025, 11:04:35
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
-- Tablo için tablo yapısı `suspicious_attempts_lnp`
--

CREATE TABLE `suspicious_attempts_lnp` (
  `id` int(11) NOT NULL,
  `deviceType` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci NOT NULL,
  `deviceModel` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci NOT NULL,
  `deviceOs` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci NOT NULL,
  `browser` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci NOT NULL,
  `resolution` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci NOT NULL,
  `ipAddress` varchar(26) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci NOT NULL,
  `attempt_time` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Tablo döküm verisi `suspicious_attempts_lnp`
--

INSERT INTO `suspicious_attempts_lnp` (`id`, `deviceType`, `deviceModel`, `deviceOs`, `browser`, `resolution`, `ipAddress`, `attempt_time`) VALUES
(1, 'Desktop', 'Unknown Model', 'Windows', 'Chrome', '1920x1080', '::1', '2025-05-08 19:01:04');

--
-- Dökümü yapılmış tablolar için indeksler
--

--
-- Tablo için indeksler `suspicious_attempts_lnp`
--
ALTER TABLE `suspicious_attempts_lnp`
  ADD PRIMARY KEY (`id`);

--
-- Dökümü yapılmış tablolar için AUTO_INCREMENT değeri
--

--
-- Tablo için AUTO_INCREMENT değeri `suspicious_attempts_lnp`
--
ALTER TABLE `suspicious_attempts_lnp`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
