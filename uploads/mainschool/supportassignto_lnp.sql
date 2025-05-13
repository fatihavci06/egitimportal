-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Anamakine: 127.0.0.1
-- Üretim Zamanı: 12 May 2025, 11:05:31
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
-- Tablo için tablo yapısı `supportassignto_lnp`
--

CREATE TABLE `supportassignto_lnp` (
  `id` int(11) NOT NULL,
  `support_id` int(11) NOT NULL,
  `assignTo` int(11) NOT NULL,
  `assignBy` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Tablo döküm verisi `supportassignto_lnp`
--

INSERT INTO `supportassignto_lnp` (`id`, `support_id`, `assignTo`, `assignBy`, `created_at`, `updated_at`) VALUES
(1, 14, 67, 1, '2025-05-09 09:17:20', '2025-05-10 19:38:12'),
(2, 13, 68, 1, '2025-05-09 09:18:57', '2025-05-10 19:38:16'),
(3, 67, 68, 1, '2025-05-09 15:54:25', '2025-05-10 19:38:25'),
(4, 3, 67, 1, '2025-05-09 16:39:16', '2025-05-10 19:38:30'),
(5, 1, 69, 67, '2025-05-10 19:34:26', '2025-05-10 19:38:33'),
(6, 2, 68, 67, '2025-05-10 19:50:11', '2025-05-10 19:50:11');

--
-- Dökümü yapılmış tablolar için indeksler
--

--
-- Tablo için indeksler `supportassignto_lnp`
--
ALTER TABLE `supportassignto_lnp`
  ADD PRIMARY KEY (`id`);

--
-- Dökümü yapılmış tablolar için AUTO_INCREMENT değeri
--

--
-- Tablo için AUTO_INCREMENT değeri `supportassignto_lnp`
--
ALTER TABLE `supportassignto_lnp`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
