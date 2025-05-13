-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Anamakine: 127.0.0.1
-- Üretim Zamanı: 12 May 2025, 11:05:00
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
-- Tablo için tablo yapısı `support_importance_lnp`
--

CREATE TABLE `support_importance_lnp` (
  `id` int(11) NOT NULL,
  `support_id` int(11) NOT NULL,
  `importance_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Tablo döküm verisi `support_importance_lnp`
--

INSERT INTO `support_importance_lnp` (`id`, `support_id`, `importance_id`, `created_at`, `updated_at`) VALUES
(1, 14, 2, '2025-05-09 09:38:51', '2025-05-09 09:38:51'),
(2, 13, 1, '2025-05-09 09:39:14', '2025-05-09 09:39:14'),
(7, 1, 2, '2025-05-09 15:05:44', '2025-05-09 15:05:44'),
(8, 3, 1, '2025-05-09 16:46:45', '2025-05-09 16:46:45'),
(9, 2, 2, '2025-05-10 19:50:04', '2025-05-10 19:50:04');

--
-- Dökümü yapılmış tablolar için indeksler
--

--
-- Tablo için indeksler `support_importance_lnp`
--
ALTER TABLE `support_importance_lnp`
  ADD PRIMARY KEY (`id`);

--
-- Dökümü yapılmış tablolar için AUTO_INCREMENT değeri
--

--
-- Tablo için AUTO_INCREMENT değeri `support_importance_lnp`
--
ALTER TABLE `support_importance_lnp`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
