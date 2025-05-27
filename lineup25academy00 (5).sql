-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Anamakine: 127.0.0.1
-- Üretim Zamanı: 28 May 2025, 00:31:13
-- Sunucu sürümü: 10.4.32-MariaDB
-- PHP Sürümü: 8.2.12

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
-- Tablo için tablo yapısı `additional_packages_lnp`
--

CREATE TABLE `additional_packages_lnp` (
  `id` int(11) NOT NULL,
  `name` int(11) NOT NULL,
  `content` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci NOT NULL,
  `price` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `additional_package_payments_lnp`
--

CREATE TABLE `additional_package_payments_lnp` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `pack_id` int(11) NOT NULL,
  `order_no` varchar(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci NOT NULL,
  `payment_type` int(11) NOT NULL,
  `payment_status` int(11) NOT NULL,
  `installment` int(11) DEFAULT NULL,
  `pay_amount` varchar(11) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci NOT NULL,
  `coupon` varchar(64) DEFAULT NULL,
  `commissionRate` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci DEFAULT NULL,
  `commissionFee` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci DEFAULT NULL,
  `ipAddress` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `additional_pack_detail_lnp`
--

CREATE TABLE `additional_pack_detail_lnp` (
  `id` int(11) NOT NULL,
  `name` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci NOT NULL,
  `icon` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci NOT NULL,
  `brief` text CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci NOT NULL,
  `details` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci NOT NULL,
  `slug` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `pack_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `announcementsstatus_lnp`
--

CREATE TABLE `announcementsstatus_lnp` (
  `id` int(11) NOT NULL,
  `announce_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `created_at` int(11) NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `announcements_lnp`
--

CREATE TABLE `announcements_lnp` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `start_date` datetime NOT NULL,
  `expire_date` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `target_type` enum('all','roles','classes') NOT NULL DEFAULT 'all',
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `slug` varchar(512) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

--
-- Tablo döküm verisi `announcements_lnp`
--

INSERT INTO `announcements_lnp` (`id`, `title`, `content`, `start_date`, `expire_date`, `created_by`, `target_type`, `is_active`, `created_at`, `updated_at`, `slug`) VALUES
(26, 'The book', 'The book details episode after episode where Democratic lawmakers, White House aides, members of Biden’s Cabinet and Democratic donors were shocked at Biden’s diminishing mental and physical capabilities while the president embarked on an ill-fated 2024 reelection bid. But nearly all did not speak out publicly or try to stop him from running.\n\n“What the world saw at his one and only 2024 debate was not an anomaly. It was not a cold; it was not someone who was underprepared or overprepared. It was not someone who was just a little tired,” Tapper and Thompson write. “It was the natural result of an eighty-one-year-old man whose capabilities had been diminishing for years. Biden, his family, and his team let their self-interest and fear of another Trump term justify an attempt to put an at times addled old man in the Oval Office for four more years.”', '2025-05-12 00:00:00', '2025-05-31 00:00:00', 1, 'roles', 0, '2025-05-19 13:25:59', '2025-05-23 08:28:33', 'greagraagr-4'),
(27, 'greagraagr', 'The British Broadcasting Corporation is a British public service broadcaster headquartered at Broadcasting House in London, England. Originally established in 1922 as the British Broadcasting Company, it evolved into its current state with its current name on New Year\'s Day', '2025-05-20 00:00:00', '2025-05-31 00:00:00', 1, 'roles', 0, '2025-05-19 13:26:09', '2025-05-22 01:03:45', 'greagraagr-5'),
(28, 'greagraagr', 'Cable News Network is a multinational news organization operating, most notably, a website and a TV channel headquartered in Atlanta.', '2025-05-20 00:00:00', '2025-05-28 00:00:00', 1, 'roles', 0, '2025-05-19 13:26:34', '2025-05-22 01:03:32', 'greagraagr-6'),
(29, 'greagraagr', 'egraagregreagreargeree erg', '2025-05-20 00:00:00', '2025-05-28 00:00:00', 1, 'all', 0, '2025-05-19 13:27:33', '2025-05-22 01:03:36', 'greagraagr-7'),
(30, 'greagraagr', 'MSNBC is an American cable news channel owned by the NBCUniversal News Group division of NBCUniversal, a subsidiary of Comcast. Launched on July 15, 1996, and headquartered at 30 Rockefeller Plaza in Manhattan, the channel primarily broadcasts rolling news coverage and liberal-leaning political commentary.', '2025-05-20 00:00:00', '2025-05-28 00:00:00', 1, 'all', 0, '2025-05-19 13:28:00', '2025-05-23 08:11:58', 'greagraagr-8'),
(31, 'greagraagr', 'egraagregreagreargeree erg', '2025-05-20 00:00:00', '2025-05-28 00:00:00', 1, 'all', 0, '2025-05-19 13:31:18', '2025-05-23 08:11:58', 'greagraagr-9'),
(32, 'greagraagr', 'Cable News Network is a multinational news organization operating, most notably, a website and a TV channel headquartered in Atlanta.', '2025-05-20 00:00:00', '2025-05-28 00:00:00', 1, 'all', 0, '2025-05-19 13:34:36', '2025-05-23 08:11:58', 'greagraagr-10'),
(39, 'sdagewaeg', 'eagesageseaggeas', '2025-05-19 00:00:00', '2025-05-31 00:00:00', 1, 'all', 0, '2025-05-19 13:37:39', '2025-05-23 08:11:58', 'sdagewaeg'),
(41, 'few', 'weggre grereh hge3r er hre gre  re', '2025-05-20 00:00:00', '2025-05-31 00:00:00', 1, 'roles', 0, '2025-05-21 20:27:07', '2025-05-23 08:11:58', 'few'),
(42, 'gregrargrgrds', 'bfgregergre', '2025-05-06 00:00:00', '2025-05-13 00:00:00', 1, 'all', 1, '2025-05-23 08:21:46', '2025-05-23 08:21:46', 'gregrargrgrds');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `announcement_targets_lnp`
--

CREATE TABLE `announcement_targets_lnp` (
  `id` int(11) NOT NULL,
  `announcement_id` int(11) NOT NULL,
  `target_type` enum('roles','classes') NOT NULL,
  `target_value` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

--
-- Tablo döküm verisi `announcement_targets_lnp`
--

INSERT INTO `announcement_targets_lnp` (`id`, `announcement_id`, `target_type`, `target_value`, `created_at`) VALUES
(16, 26, 'classes', 2, '2025-05-19 13:25:59'),
(17, 27, 'roles', 2, '2025-05-19 13:26:09'),
(18, 28, 'classes', 3, '2025-05-19 13:26:34'),
(24, 41, 'roles', 2, '2025-05-21 20:27:07');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `announcement_views_lnp`
--

CREATE TABLE `announcement_views_lnp` (
  `id` int(11) NOT NULL,
  `announcement_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `viewed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

--
-- Tablo döküm verisi `announcement_views_lnp`
--

INSERT INTO `announcement_views_lnp` (`id`, `announcement_id`, `user_id`, `viewed_at`) VALUES
(1, 26, 1, '2025-05-20 09:13:17'),
(5, 27, 1, '2025-05-18 09:26:17'),
(7, 28, 1, '2025-05-19 09:26:25'),
(14, 29, 1, '2025-05-20 14:01:43'),
(38, 30, 1, '2025-05-21 13:12:33'),
(42, 41, 25, '2025-05-21 22:23:00'),
(43, 27, 25, '2025-05-21 22:23:09'),
(44, 29, 25, '2025-05-21 22:26:57'),
(45, 30, 25, '2025-05-22 00:50:34'),
(46, 32, 25, '2025-05-22 00:50:38'),
(47, 31, 25, '2025-05-22 00:50:40'),
(48, 39, 25, '2025-05-22 00:50:43'),
(49, 31, 1, '2025-05-22 23:23:57');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `audio_book_lnp`
--

CREATE TABLE `audio_book_lnp` (
  `id` int(11) NOT NULL,
  `name` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci NOT NULL,
  `cover_img` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci NOT NULL,
  `book_url` varchar(2048) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci NOT NULL,
  `class_id` int(11) NOT NULL,
  `slug` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `lesson_id` int(11) DEFAULT NULL,
  `unit_id` int(11) DEFAULT NULL,
  `topic_id` int(11) DEFAULT NULL,
  `subtopic_id` int(11) DEFAULT NULL,
  `school_id` int(11) NOT NULL DEFAULT 1,
  `is_active` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `audio_book_lnp`
--

INSERT INTO `audio_book_lnp` (`id`, `name`, `cover_img`, `book_url`, `class_id`, `slug`, `created_at`, `updated_at`, `lesson_id`, `unit_id`, `topic_id`, `subtopic_id`, `school_id`, `is_active`) VALUES
(1, 'Sesli Kitap 1 ', 'sesli-kitap.jpg', '<iframe width=\"100%\" height=\"166\" scrolling=\"no\" frameborder=\"no\" allow=\"autoplay\" src=\"https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/tracks/1184346943&color=ff5500\"></iframe><div style=\"font-size: 10px; color: #cccccc;line-break: anywhere;word-break: normal;overflow: hidden;white-space: nowrap;text-overflow: ellipsis; font-family: Interstate,Lucida Grande,Lucida Sans Unicode,Lucida Sans,Garuda,Verdana,Tahoma,sans-serif;font-weight: 100;\"><a href=\"https://soundcloud.com/seslikitapdunyasi\" title=\"Sesli Kitap Dünyası\" target=\"_blank\" style=\"color: #cccccc; text-decoration: none;\">Sesli Kitap Dünyası</a> · <a href=\"https://soundcloud.com/seslikitapdunyasi/leonardo-da-vinci-kimdir\" title=\"Leonardo da Vinci Kimdir?\" target=\"_blank\" style=\"color: #cccccc; text-decoration: none;\">Leonardo da Vinci Kimdir?</a></div>', 3, 'sesli-kitap-1-3', '2025-04-02 10:04:01', '2025-05-24 21:26:13', 1, 2, 0, 0, 1, 0),
(2, 'Leonardo da Vinci Kimdir?', 'sesli-kitap.jpg', 'jytjytty', 4, 'leonardo-da-vinci-kimdir', '2025-04-02 10:17:18', '2025-05-24 19:42:31', 1, 1, 1, 0, 1, 0),
(21, 'efwewqfew', 'sesli-kitap.jpg', '<iframe width=\"100%\" height=\"166\" scrolling=\"no\" frameborder=\"no\" allow=\"autoplay\" src=\"https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/tracks/1184346943&color=ff5500\"></iframe><div style=\"font-size: 10px; color: #cccccc;line-break: anywhere;word-break: normal;overflow: hidden;white-space: nowrap;text-overflow: ellipsis; font-family: Interstate,Lucida Grande,Lucida Sans Unicode,Lucida Sans,Garuda,Verdana,Tahoma,sans-serif;font-weight: 100;\"><a href=\"https://soundcloud.com/seslikitapdunyasi\" title=\"Sesli Kitap Dünyası\" target=\"_blank\" style=\"color: #cccccc; text-decoration: none;\">Sesli Kitap Dünyası</a> · <a href=\"https://soundcloud.com/seslikitapdunyasi/leonardo-da-vinci-kimdir\" title=\"Leonardo da Vinci Kimdir?\" target=\"_blank\" style=\"color: #cccccc; text-decoration: none;\">Leonardo da Vinci Kimdir?</a></div>', 3, 'efwewqfew', '2025-05-14 15:10:04', '2025-05-24 17:44:18', 0, 0, 0, 0, 1, 0),
(22, 'fewio', 'sesli-kitap.jpg', '<iframe width=\"100%\" height=\"166\" scrolling=\"no\" frameborder=\"no\" allow=\"autoplay\" src=\"https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/tracks/1184346943&color=ff5500\"></iframe><div style=\"font-size: 10px; color: #cccccc;line-break: anywhere;word-break: normal;overflow: hidden;white-space: nowrap;text-overflow: ellipsis; font-family: Interstate,Lucida Grande,Lucida Sans Unicode,Lucida Sans,Garuda,Verdana,Tahoma,sans-serif;font-weight: 100;\"><a href=\"https://soundcloud.com/seslikitapdunyasi\" title=\"Sesli Kitap Dünyası\" target=\"_blank\" style=\"color: #cccccc; text-decoration: none;\">Sesli Kitap Dünyası</a> · <a href=\"https://soundcloud.com/seslikitapdunyasi/leonardo-da-vinci-kimdir\" title=\"Leonardo da Vinci Kimdir?\" target=\"_blank\" style=\"color: #cccccc; text-decoration: none;\">Leonardo da Vinci Kimdir?</a></div>', 3, 'fewio', '2025-05-14 15:10:25', '2025-05-24 17:44:20', 1, 1, 1, 0, 1, 0),
(23, 'fewio', 'sesli-kitap.jpg', '<iframe width=\"100%\" height=\"166\" scrolling=\"no\" frameborder=\"no\" allow=\"autoplay\" src=\"https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/tracks/1184346943&color=ff5500\"></iframe><div style=\"font-size: 10px; color: #cccccc;line-break: anywhere;word-break: normal;overflow: hidden;white-space: nowrap;text-overflow: ellipsis; font-family: Interstate,Lucida Grande,Lucida Sans Unicode,Lucida Sans,Garuda,Verdana,Tahoma,sans-serif;font-weight: 100;\"><a href=\"https://soundcloud.com/seslikitapdunyasi\" title=\"Sesli Kitap Dünyası\" target=\"_blank\" style=\"color: #cccccc; text-decoration: none;\">Sesli Kitap Dünyası</a> · <a href=\"https://soundcloud.com/seslikitapdunyasi/leonardo-da-vinci-kimdir\" title=\"Leonardo da Vinci Kimdir?\" target=\"_blank\" style=\"color: #cccccc; text-decoration: none;\">Leonardo da Vinci Kimdir?</a></div>', 3, 'fewio-1', '2025-05-15 08:04:16', '2025-05-24 19:41:34', 0, 0, 0, 0, 1, 0),
(24, 'LocalPostgreSQL', 'sesli-kitap.jpg', '<iframe width=\"100%\" height=\"166\" scrolling=\"no\" frameborder=\"no\" allow=\"autoplay\" src=\"https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/tracks/1184346943&color=ff5500\"></iframe><div style=\"font-size: 10px; color: #cccccc;line-break: anywhere;word-break: normal;overflow: hidden;white-space: nowrap;text-overflow: ellipsis; font-family: Interstate,Lucida Grande,Lucida Sans Unicode,Lucida Sans,Garuda,Verdana,Tahoma,sans-serif;font-weight: 100;\"><a href=\"https://soundcloud.com/seslikitapdunyasi\" title=\"Sesli Kitap Dünyası\" target=\"_blank\" style=\"color: #cccccc; text-decoration: none;\">Sesli Kitap Dünyası</a> · <a href=\"https://soundcloud.com/seslikitapdunyasi/leonardo-da-vinci-kimdir\" title=\"Leonardo da Vinci Kimdir?\" target=\"_blank\" style=\"color: #cccccc; text-decoration: none;\">Leonardo da Vinci Kimdir?</a></div>', 3, 'localpostgresql', '2025-05-15 08:04:37', '2025-05-24 19:41:34', 1, 1, 1, 3, 1, 0),
(25, 'rwerwer', 'sesli-kitap.jpg', '<iframe width=\"100%\" height=\"166\" scrolling=\"no\" frameborder=\"no\" allow=\"autoplay\" src=\"https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/tracks/1184346943&color=ff5500\"></iframe><div style=\"font-size: 10px; color: #cccccc;line-break: anywhere;word-break: normal;overflow: hidden;white-space: nowrap;text-overflow: ellipsis; font-family: Interstate,Lucida Grande,Lucida Sans Unicode,Lucida Sans,Garuda,Verdana,Tahoma,sans-serif;font-weight: 100;\"><a href=\"https://soundcloud.com/seslikitapdunyasi\" title=\"Sesli Kitap Dünyası\" target=\"_blank\" style=\"color: #cccccc; text-decoration: none;\">Sesli Kitap Dünyası</a> · <a href=\"https://soundcloud.com/seslikitapdunyasi/leonardo-da-vinci-kimdir\" title=\"Leonardo da Vinci Kimdir?\" target=\"_blank\" style=\"color: #cccccc; text-decoration: none;\">Leonardo da Vinci Kimdir?</a></div>', 3, 'rwerwer', '2025-05-16 07:28:25', '2025-05-24 19:41:34', 1, 1, 1, 1, 1, 0);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `category_titles_lnp`
--

CREATE TABLE `category_titles_lnp` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `type` tinyint(4) NOT NULL COMMENT '1:İçerik 2:Kavram, 3:Etkinlik',
  `school_id` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci;

--
-- Tablo döküm verisi `category_titles_lnp`
--

INSERT INTO `category_titles_lnp` (`id`, `title`, `type`, `school_id`, `created_at`, `updated_at`) VALUES
(1, 'Matematik', 1, 1, '2025-05-08 20:14:37', '2025-05-08 20:14:37'),
(2, 'Türkçe', 2, 1, '2025-05-08 20:14:37', '2025-05-08 20:14:37'),
(3, 'Hayat Bilgisi', 3, 1, '2025-05-08 20:14:37', '2025-05-08 20:14:37'),
(5, '123131', 1, 1, '2025-05-08 20:33:50', '2025-05-08 20:33:50'),
(6, 'ETSTESTS', 2, 1, '2025-05-08 20:34:19', '2025-05-08 20:34:19'),
(7, 'adssadsa', 3, 1, '2025-05-08 20:36:11', '2025-05-08 20:36:11'),
(8, 'zcsds', 1, 1, '2025-05-08 20:37:08', '2025-05-08 20:37:08'),
(10, 'Bu bir test', 2, 1, '2025-05-08 20:37:30', '2025-05-08 20:37:30'),
(12, 'dadadadada', 2, 1, '2025-05-08 20:38:20', '2025-05-08 20:38:20'),
(13, 'test', 1, 1, '2025-05-08 20:38:37', '2025-05-08 20:38:37'),
(14, 'testses221', 2, 1, '2025-05-08 20:39:10', '2025-05-08 20:39:10');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `classes_lnp`
--

CREATE TABLE `classes_lnp` (
  `id` int(11) NOT NULL,
  `name` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci NOT NULL,
  `class_grade` int(11) NOT NULL,
  `school_id` int(11) DEFAULT 1,
  `slug` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `class_type` int(11) NOT NULL DEFAULT 0 COMMENT '0:sınıf 1:yaş grubu anaookulu',
  `orderBy` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `classes_lnp`
--

INSERT INTO `classes_lnp` (`id`, `name`, `class_grade`, `school_id`, `slug`, `class_type`, `orderBy`, `created_at`, `updated_at`) VALUES
(3, '1. Sınıf', 0, 1, '1-sinif', 0, 4, '2025-03-04 15:41:02', '2025-05-19 08:57:23'),
(4, '2. Sınıf', 0, 1, '2-sinif', 0, 5, '2025-03-04 15:41:32', '2025-05-19 08:57:23'),
(5, '3. Sınıf', 0, 1, '3-sinif', 0, 6, '2025-03-05 07:40:59', '2025-05-19 08:57:23'),
(6, '4. Sınıf', 0, 1, '4-sinif', 0, 7, '2025-03-05 07:41:07', '2025-05-19 08:57:23'),
(10, '3-4 Yaş', 0, 1, '3-4-yas', 1, 1, '2025-05-16 13:19:28', '2025-05-19 08:57:23'),
(11, '4-5 Yaş', 0, 1, '4-5-yas', 1, 2, '2025-05-16 13:19:28', '2025-05-19 08:57:23'),
(12, '5-6 Yaş', 0, 1, '5-6-yas', 1, 3, '2025-05-16 13:19:28', '2025-05-19 08:57:23');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `coupon_lnp`
--

CREATE TABLE `coupon_lnp` (
  `id` int(11) NOT NULL,
  `coupon_code` varchar(10) NOT NULL,
  `discount_type` varchar(20) NOT NULL,
  `discount_value` int(11) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `coupon_expires` date NOT NULL,
  `coupon_quantity` int(11) NOT NULL,
  `used_coupon_count` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `coupon_lnp`
--

INSERT INTO `coupon_lnp` (`id`, `coupon_code`, `discount_type`, `discount_value`, `is_active`, `coupon_expires`, `coupon_quantity`, `used_coupon_count`, `created_at`, `updated_at`) VALUES
(1, '37MUM5ZY', 'amount', 100, 0, '2025-05-13', 0, 0, '2025-05-13 14:05:30', '2025-05-13 14:05:30'),
(2, 'VVYB6K4Z', 'percentage', 50, 0, '2025-05-13', 0, 0, '2025-05-13 14:08:54', '2025-05-13 14:08:54'),
(3, 'VVYB6K4Z', 'amount', 50, 0, '2025-05-13', 0, 0, '2025-05-13 14:10:15', '2025-05-13 14:10:15'),
(4, 'VVYB6K4Z', 'amount', 50, 0, '2025-05-13', 0, 0, '2025-05-13 14:10:37', '2025-05-13 14:10:37'),
(5, 'VVYB6K4Z', 'amount', 50, 0, '2025-05-13', 0, 0, '2025-05-13 14:11:14', '2025-05-13 14:11:14'),
(6, '', '', 0, 0, '2025-05-13', 0, 0, '2025-05-13 14:19:13', '2025-05-13 14:19:13'),
(7, 'Q5XG8H4V', 'amount', 10, 1, '0000-00-00', 0, 0, '2025-05-13 15:07:50', '2025-05-13 15:07:50'),
(8, 'Z99Q6NXW', 'amount', 100, 1, '0000-00-00', 0, 0, '2025-05-13 15:09:42', '2025-05-13 15:09:42'),
(9, '851QNQBQ', 'percentage', 50, 1, '2025-05-20', 0, 0, '2025-05-14 07:21:02', '2025-05-14 07:21:02'),
(10, 'ZMJCGDUH', 'percentage', 100, 1, '0000-00-00', 0, 0, '2025-05-14 07:28:00', '2025-05-14 07:28:00'),
(11, '851QNQBQ', 'amount', 50, 1, '2025-05-15', 0, 0, '2025-05-14 07:45:16', '2025-05-14 07:45:16'),
(12, '1FVZRUG7', 'percentage', 50, 1, '2025-05-15', 0, 0, '2025-05-14 07:52:50', '2025-05-14 07:52:50'),
(13, '851QNQB1', 'percentage', 100, 1, '2025-05-15', 0, 0, '2025-05-14 07:56:15', '2025-05-14 15:16:44'),
(14, 'F7TGYLRH', 'percentage', 50, 1, '2025-05-15', 5, 5, '2025-05-14 08:12:23', '2025-05-15 14:28:09'),
(15, 'E3MRESF2', 'amount', 50, 1, '2025-05-16', 5, 0, '2025-05-15 12:15:56', '2025-05-15 12:15:56'),
(16, 'DHP5MCCC', 'percentage', 10, 1, '2025-05-16', 5, 0, '2025-05-15 12:17:01', '2025-05-15 12:17:01'),
(17, 'O4EPS21E', 'amount', 10, 1, '2025-05-19', 5, 5, '2025-05-16 07:27:07', '2025-05-18 11:40:07');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `games_lnp`
--

CREATE TABLE `games_lnp` (
  `id` int(11) NOT NULL,
  `name` text CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci NOT NULL,
  `cover_img` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci NOT NULL,
  `game_url` varchar(2048) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci NOT NULL,
  `class_id` int(11) DEFAULT NULL,
  `lesson_id` int(11) DEFAULT NULL,
  `unit_id` int(11) DEFAULT NULL,
  `topic_id` int(11) DEFAULT NULL,
  `slug` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `school_id` int(11) NOT NULL DEFAULT 1,
  `subtopic_id` int(11) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `games_lnp`
--

INSERT INTO `games_lnp` (`id`, `name`, `cover_img`, `game_url`, `class_id`, `lesson_id`, `unit_id`, `topic_id`, `slug`, `created_at`, `updated_at`, `school_id`, `subtopic_id`, `is_active`) VALUES
(1, 'Oyun Deneme ggggg', 'gameDefault.jpg', '<iframe id=\"embededGame\" src=\"https://idev.games/embed/kral-oyun-skor\" frameBorder=\"0\" scrolling=\"no\" width=\"900px\" height=\"600px\">Browser not compatible.</iframe>', 3, 1, 1, 0, 'oyun-deneme-ggggg-1', '2025-03-18 06:36:28', '2025-05-24 23:01:28', 1, 0, 0),
(2, 'Deneme sınıf', 'gameDefault.jpg', 'jwrfewjij rew', 3, 1, 0, 0, 'deneme-sinif', '2025-05-05 21:20:05', '2025-05-24 23:01:34', 1, NULL, 0),
(11, 'LocalPostgreSQL', 'gameDefault.jpg', '<iframe id=\"embededGame\" src=\"https://idev.games/embed/kral-oyun-skor\" frameBorder=\"0\" scrolling=\"no\" width=\"900px\" height=\"600px\">Browser not compatible.</iframe>', 3, 0, 0, 0, 'localpostgresql', '2025-05-14 12:17:01', '2025-05-24 22:44:18', 1, NULL, 0),
(12, 'fewkopfewkop', 'gameDefault.jpg', '<iframe id=\"embededGame\" src=\"https://idev.games/embed/kral-oyun-skor\" frameBorder=\"0\" scrolling=\"no\" width=\"900px\" height=\"600px\">Browser not compatible.</iframe>', 0, 0, 0, 0, 'fewkopfewkop', '2025-05-14 12:17:43', '2025-05-24 22:44:18', 1, NULL, 0),
(13, 'LocalPostgreSQL', 'gameDefault.jpg', '<iframe id=\"embededGame\" src=\"https://idev.games/embed/kral-oyun-skor\" frameBorder=\"0\" scrolling=\"no\" width=\"900px\" height=\"600px\">Browser not compatible.</iframe>', 3, 0, 0, 0, 'localpostgresql-1', '2025-05-14 14:46:55', '2025-05-24 22:44:18', 1, NULL, 0),
(14, 'yeni', 'gameDefault.jpg', '<iframe id=\"embededGame\" src=\"https://idev.games/embed/kral-oyun-skor\" frameBorder=\"0\" scrolling=\"no\" width=\"900px\" height=\"600px\">Browser not compatible.</iframe>', 3, 1, 1, 1, 'yeni', '2025-05-14 15:10:51', '2025-05-24 22:44:18', 1, NULL, 0),
(15, 'fwefqew', 'gameDefault.jpg', '<iframe id=\"embededGame\" src=\"https://idev.games/embed/kral-oyun-skor\" frameBorder=\"0\" scrolling=\"no\" width=\"900px\" height=\"600px\">Browser not compatible.</iframe>', 3, 0, 0, 0, 'fwefqew', '2025-05-14 15:11:06', '2025-05-24 23:01:34', 1, NULL, 0),
(16, 'testf feasfesa', 'gameDefault.jpg', '<iframe id=\"embededGame\" src=\"https://idev.games/embed/kral-oyun-skor\" frameBorder=\"0\" scrolling=\"no\" width=\"900px\" height=\"600px\">Browser not compatible.</iframe>', 3, 1, 0, 0, 'testf-feasfesa', '2025-05-24 22:28:56', '2025-05-24 22:52:52', 1, 0, 0);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `homeworks_lnp`
--

CREATE TABLE `homeworks_lnp` (
  `id` int(11) NOT NULL,
  `name` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci NOT NULL,
  `content` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci NOT NULL,
  `weekly_id` int(11) NOT NULL,
  `slug` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `important_weeks_lnp`
--

CREATE TABLE `important_weeks_lnp` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `school_id` int(11) NOT NULL DEFAULT 1,
  `class_grade` int(11) NOT NULL DEFAULT 0,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci;

--
-- Tablo döküm verisi `important_weeks_lnp`
--

INSERT INTO `important_weeks_lnp` (`id`, `name`, `slug`, `school_id`, `class_grade`, `start_date`, `end_date`, `created_at`, `updated_at`) VALUES
(2, 'Anneler Günü', 'sfsdf', 1, 0, NULL, NULL, '2025-05-13 09:35:12', '2025-05-13 09:35:12'),
(24, 'Yeşilay Haftası', 'test-1', 1, 0, '2026-03-01', '2026-03-07', '2025-05-13 09:35:39', '2025-05-13 09:35:39'),
(25, '19 Mayıs Gençlik ve Spor Bayramı', 'test-1', 1, 0, '2026-05-19', '2026-05-19', '2025-05-13 09:36:53', '2025-05-13 09:36:53'),
(26, 'Dünya Çocuk Günü', 'test-1', 1, 0, '2025-11-20', '2025-11-20', '2025-05-13 09:39:02', '2025-05-13 09:39:02'),
(27, '8 Mart Dünya Kadınlar Günü', 'lkkljkl', 1, 0, '2026-03-08', '2026-03-08', '2025-05-13 09:39:31', '2025-05-13 09:39:31'),
(28, 'Hayvanları Koruma Günü', 'lkslksls', 1, 0, '2025-10-04', '2025-10-04', '2025-05-13 09:39:51', '2025-05-13 09:39:51'),
(29, '12 Mart İstiklal Marşı\'nın kabulü', 'sss', 1, 0, '2026-03-12', '2026-03-12', '2025-05-13 09:41:52', '2025-05-13 09:41:52'),
(30, '29 Mayıs İstanbul\'un Fethi', 'lsklsk', 1, 0, '2026-05-29', '2026-05-29', '2025-05-13 09:42:07', '2025-05-13 09:42:07'),
(31, '29 Ekim Cumhuriyet Bayramı', 'ccdsfs', 1, 0, '2025-10-29', '2025-10-29', '2025-05-13 09:45:34', '2025-05-13 09:45:34'),
(32, 'İtfaiye Haftası', 'itfaiye-haftasi', 1, 0, '2025-09-25', '2025-10-01', '2025-05-22 08:10:07', '2025-05-22 08:10:07'),
(33, '18-24 Mayıs Müzeler Haftası', '18-24-mayis-muzeler-haftasi', 1, 0, '2026-05-18', '2026-05-24', '2025-05-22 08:14:46', '2025-05-22 08:14:46'),
(34, '8-14 Mart Bilim ve Teknoloji Haftası', '8-14-mart-bilim-ve-teknoloji-haftasi', 1, 0, '2026-03-08', '2026-03-14', '2025-05-22 08:16:54', '2025-05-22 08:16:54'),
(35, '5 Haziran Dünya Çevre Günü', '5-haziran-dunya-cevre-gunu', 1, 0, '2026-06-05', '2026-06-05', '2025-05-22 08:17:13', '2025-05-22 08:17:13'),
(36, 'Kızılay Haftası', 'kizilay-haftasi', 1, 0, '2025-10-29', '2025-11-04', '2025-05-22 08:17:39', '2025-05-22 08:17:39'),
(37, '18 Mart Çanakkale Zaferi', '18-mart-canakkale-zaferi', 1, 0, '2026-03-18', '2026-03-18', '2025-05-22 08:17:56', '2025-05-22 08:17:56'),
(38, 'Ramazan Ayı', 'ramazan-ayi', 1, 0, '2026-02-18', '2026-03-19', '2025-05-22 08:18:24', '2025-05-22 08:18:24'),
(39, '10 Kasım Atatürk\'ü Anma Haftası', '10-kasim-ataturk-u-anma-haftasi', 1, 0, '2025-11-10', '2025-11-16', '2025-05-22 08:18:43', '2025-05-22 08:18:43'),
(40, '18-24 Mart Yaşlılara Saygı Haftası', '18-24-mart-yaslilara-saygi-haftasi', 1, 0, '2026-03-18', '2026-03-24', '2025-05-22 08:19:15', '2025-05-22 08:19:15'),
(41, 'Dünya Kitap Günü', 'dunya-kitap-gunu', 1, 0, '2026-04-23', '2026-04-23', '2025-05-22 08:19:40', '2025-05-22 08:19:40'),
(42, 'Dünya Çocuk Hakları Günü', 'dunya-cocuk-haklari-gunu', 1, 0, '2025-11-20', '2025-11-20', '2025-05-22 08:20:05', '2025-05-22 08:20:05'),
(43, '22 Mart Dünya Su Günü', '22-mart-dunya-su-gunu', 1, 0, '2026-03-22', '2026-03-22', '2025-05-22 08:21:23', '2025-05-22 08:21:23'),
(44, '24 Kasım Öğretmenler Günü', '24-kasim-ogretmenler-gunu', 1, 0, '2025-11-24', '2025-11-24', '2025-05-22 08:21:41', '2025-05-22 08:21:41'),
(45, '21-26 Mart Orman Haftası', '21-26-mart-orman-haftasi', 1, 0, '2026-03-21', '2026-03-26', '2025-05-22 08:22:06', '2025-05-22 08:22:06'),
(46, '21-27 Kasım Ağız ve Diş Sağlığı Haftası', '21-27-kasim-agiz-ve-dis-sagligi-haftasi', 1, 0, '2025-11-21', '2025-11-27', '2025-05-22 08:22:24', '2025-05-22 08:22:24'),
(47, '27 Mart Dünya Tiyatrolar Günü', '27-mart-dunya-tiyatrolar-gunu', 1, 0, '2026-03-27', '2026-03-27', '2025-05-22 08:22:48', '2025-05-22 08:22:48'),
(48, '3 Aralık Engelliler Günü', '3-aralik-engelliler-gunu', 1, 0, '2025-12-03', '2025-12-03', '2025-05-22 08:23:01', '2025-05-22 08:23:01'),
(49, 'Dünya Kütüphaneler Haftası', 'dunya-kutuphaneler-haftasi', 1, 0, '2026-04-19', '2026-04-25', '2025-05-22 08:23:33', '2025-05-22 08:23:33'),
(50, '7-17 Aralık Mevlana Haftası', '7-17-aralik-mevlana-haftasi', 1, 0, '2025-12-07', '2025-12-17', '2025-05-22 08:23:56', '2025-05-22 08:23:56'),
(51, '10-16 Nisan Polis Haftası', '10-16-nisan-polis-haftasi', 1, 0, '2026-04-10', '2026-04-16', '2025-05-22 08:24:11', '2025-05-22 08:24:11'),
(52, 'İnsan Hakları ve Demokrasi Haftası', 'insan-haklari-ve-demokrasi-haftasi', 1, 0, '2025-12-10', '2025-12-16', '2025-05-22 08:24:40', '2025-05-22 08:24:40'),
(53, '23 Nisan Ulusal Egemenlik ve Çocuk Bayramı', '23-nisan-ulusal-egemenlik-ve-cocuk-bayrami', 1, 0, '2026-04-23', '2026-04-23', '2025-05-22 08:25:02', '2025-05-22 08:25:02'),
(54, 'Sağlık Haftası', 'saglik-haftasi', 1, 0, '2026-04-07', '2026-04-13', '2025-05-22 08:25:22', '2025-05-22 08:25:22'),
(55, '12-18 Aralık Tutum Yatırım ve Türk Malları Haftası', '12-18-aralik-tutum-yatirim-ve-turk-mallari-haftasi', 1, 0, '2025-12-12', '2025-12-18', '2025-05-22 08:25:47', '2025-05-22 08:25:47'),
(56, '15-22 Nisan Turizm Haftası', '15-22-nisan-turizm-haftasi', 1, 0, '2026-04-15', '2026-04-22', '2025-05-22 08:26:04', '2025-05-22 08:26:04'),
(57, 'Yerli Malı Haftası', 'yerli-mali-haftasi', 1, 0, '2025-12-12', '2025-12-18', '2025-05-22 08:26:27', '2025-05-22 08:26:27'),
(58, 'Enerji Tasarrufu Haftası', 'enerji-tasarrufu-haftasi', 1, 0, '2026-01-11', '2026-01-18', '2025-05-22 08:26:56', '2025-05-22 08:26:56'),
(59, 'Trafik ve İlkyardım Haftası', 'trafik-ve-ilkyardim-haftasi', 1, 0, '2026-05-01', '2026-05-07', '2025-05-22 08:27:18', '2025-05-22 08:27:18'),
(60, 'Uyum Haftası**', 'uyum-haftasi', 1, 0, '2025-09-01', '2025-09-06', '2025-05-22 08:29:06', '2025-05-22 08:29:06'),
(61, 'İlköğretim Haftası', 'ilkogretim-haftasi', 1, 0, '2025-09-15', '2025-09-21', '2025-05-22 08:29:51', '2025-05-22 08:29:51'),
(62, '28 Şubat Sivil Savunma Günü', '28-subat-sivil-savunma-gunu', 1, 0, '2026-02-28', '2026-02-28', '2025-05-22 08:30:06', '2025-05-22 08:30:06'),
(63, '10-16 Mayıs Engelliler Haftası', '10-16-mayis-engelliler-haftasi', 1, 0, '2026-05-10', '2026-05-16', '2025-05-22 08:30:28', '2025-05-22 08:30:28');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `lessons_lnp`
--

CREATE TABLE `lessons_lnp` (
  `id` int(11) NOT NULL,
  `name` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci NOT NULL,
  `class_id` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `school_id` int(11) DEFAULT NULL,
  `slug` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `lessons_lnp`
--

INSERT INTO `lessons_lnp` (`id`, `name`, `class_id`, `school_id`, `slug`, `created_at`, `updated_at`) VALUES
(1, 'Türkçe', '3;4;5;6', NULL, 'turkce', '2025-04-16 07:24:11', '2025-04-16 07:24:11'),
(2, 'Matematik', '3;4;5;6', NULL, 'matematik', '2025-04-16 07:24:30', '2025-04-16 07:24:30'),
(3, 'Hayat Bilgisi', '3;4;5', NULL, 'hayat-bilgisi', '2025-04-16 07:24:45', '2025-04-16 07:24:45'),
(4, 'İngilizce', '3;4;5;6', NULL, 'ingilizce', '2025-04-16 07:25:18', '2025-04-16 07:25:18'),
(5, 'Fen Bilimleri', '5;6', NULL, 'fen-bilimleri', '2025-04-16 07:25:43', '2025-04-16 07:25:43'),
(6, 'Sosyal Bilgiler', '6', NULL, 'sosyal-bilgiler', '2025-04-16 07:26:56', '2025-04-16 07:26:56');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(22, 1, 803169699, 'Desktop', 'Unknown Model', 'Windows', 'Chrome', '1920x1080', '::1', '2025-05-12 11:56:43', '0000-00-00 00:00:00'),
(23, 1, 1840187739, 'Desktop', 'Unknown Model', 'Windows', 'Chrome', '1536x864', '::1', '2025-05-24 02:02:49', '2025-05-24 02:37:49'),
(24, 1, 1040890606, 'Desktop', 'Unknown Model', 'Windows', 'Chrome', '1536x864', '::1', '2025-05-24 11:22:47', '0000-00-00 00:00:00'),
(25, 1, 945036136, 'Desktop', 'Unknown Model', 'Windows', 'Chrome', '1536x864', '::1', '2025-05-24 13:26:24', '0000-00-00 00:00:00'),
(26, 1, 1398350898, 'Desktop', 'Unknown Model', 'Windows', 'Chrome', '1536x864', '::1', '2025-05-27 12:47:19', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `loginlogs_lnp`
--

CREATE TABLE `loginlogs_lnp` (
  `id` int(11) NOT NULL,
  `IpAddress` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `TryTime` varchar(24) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `mainschool_content_file_lnp`
--

CREATE TABLE `mainschool_content_file_lnp` (
  `id` int(11) NOT NULL,
  `main_id` int(11) NOT NULL,
  `file_path` text DEFAULT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci;

--
-- Tablo döküm verisi `mainschool_content_file_lnp`
--

INSERT INTO `mainschool_content_file_lnp` (`id`, `main_id`, `file_path`, `description`, `created_at`) VALUES
(161, 92, 'uploads/preschool/Ana Mödüller (3).docx', 'Açıklama', '2025-05-16 14:46:02'),
(162, 93, 'uploads/preschool/Ana Mödüller (3) (1).docx', 'Açıklama 1', '2025-05-16 14:54:20'),
(163, 94, 'uploads/preschool/Ana Mödüller (3) (1).docx', '1', '2025-05-16 15:00:59'),
(164, 95, 'uploads/preschool/istockphoto-998509626-612x612.jpg', '1', '2025-05-16 15:11:39'),
(165, 95, 'uploads/preschool/Ana Mödüller (3) (1).docx', '2', '2025-05-16 15:11:39'),
(166, 96, 'uploads/preschool/Ana Mödüller (3) (1) (1).docx', '1', '2025-05-19 09:05:57'),
(167, 96, 'uploads/preschool/Ana Mödüller (3) (1).docx', '2', '2025-05-19 09:05:57'),
(168, 122, 'uploads/preschool/fatura_listesi (16).xlsx', '1', '2025-05-23 21:20:35'),
(169, 122, 'uploads/preschool/fatura_listesi (15).xlsx', '2', '2025-05-23 21:20:35'),
(170, 123, 'uploads/preschool/fatura_listesi (16).xlsx', '12', '2025-05-23 21:32:43'),
(171, 123, 'uploads/preschool/fatura_listesi (15).xlsx', '143', '2025-05-23 21:32:43'),
(172, 124, 'uploads/preschool/fatura_listesi (16).xlsx', '1', '2025-05-23 21:35:13'),
(173, 125, 'uploads/preschool/fatura_listesi (16).xlsx', '1', '2025-05-23 21:37:06'),
(174, 125, 'uploads/preschool/fatura_listesi (15).xlsx', '2', '2025-05-23 21:37:06'),
(175, 127, 'uploads/preschool/fatura_listesi (14).xlsx', '1', '2025-05-24 10:28:39'),
(176, 127, 'uploads/preschool/fatura_listesi (13).xlsx', '2', '2025-05-24 10:28:39');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `mainschool_wordwall_lnp`
--

CREATE TABLE `mainschool_wordwall_lnp` (
  `id` int(11) NOT NULL,
  `main_id` int(11) DEFAULT NULL,
  `wordwall_title` varchar(700) DEFAULT NULL,
  `wordwall_url` varchar(700) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci;

--
-- Tablo döküm verisi `mainschool_wordwall_lnp`
--

INSERT INTO `mainschool_wordwall_lnp` (`id`, `main_id`, `wordwall_title`, `wordwall_url`, `created_at`, `updated_at`) VALUES
(1, 124, 'Etkinlik 2', 'https://wordwall.net/tr/embed/766d300d105546aaa2b3b067ec9760ae', '2025-05-23 21:35:13', '2025-05-23 21:35:13'),
(4, NULL, 'Etkinlik 1', 'https://wordwall.net/tr/embed/766d300d105546aaa2b3b067ec9760ae', '2025-05-23 21:56:28', '2025-05-23 21:56:28'),
(5, NULL, 'Etkinlik 3', 'https://wordwall.net/tr/embed/766d300d105546aaa2b3b067ec9760ae', '2025-05-23 21:56:28', '2025-05-23 21:56:28'),
(6, NULL, 'Etkinlik 1', 'https://wordwall.net/tr/embed/766d300d105546aaa2b3b067ec9760ae', '2025-05-23 21:56:46', '2025-05-23 21:56:46'),
(7, NULL, 'Etkinlik 23', 'https://wordwall.net/tr/embed/766d300d105546aaa2b3b067ec9760ae', '2025-05-23 21:56:46', '2025-05-23 21:56:46'),
(8, NULL, 'Etkinlik 1', 'https://wordwall.net/tr/embed/766d300d105546aaa2b3b067ec9760ae', '2025-05-23 21:57:09', '2025-05-23 21:57:09'),
(9, NULL, 'Etkinlik 2', 'https://wordwall.net/tr/embed/766d300d105546aaa2b3b067ec9760ae', '2025-05-23 21:57:09', '2025-05-23 21:57:09'),
(10, NULL, 'Etkinlik 1', 'https://wordwall.net/tr/embed/766d300d105546aaa2b3b067ec9760ae', '2025-05-23 21:57:22', '2025-05-23 21:57:22'),
(11, NULL, 'Etkinlik 23', 'https://wordwall.net/tr/embed/766d300d105546aaa2b3b067ec9760ae', '2025-05-23 21:57:22', '2025-05-23 21:57:22'),
(12, NULL, 'Etkinlik 1', 'https://wordwall.net/tr/embed/766d300d105546aaa2b3b067ec9760ae', '2025-05-23 21:58:26', '2025-05-23 21:58:26'),
(13, NULL, 'Etkinlik 23', 'https://wordwall.net/tr/embed/766d300d105546aaa2b3b067ec9760ae', '2025-05-23 21:58:26', '2025-05-23 21:58:26'),
(21, 125, 'Etkinlik 1', 'https://wordwall.net/tr/embed/766d300d105546aaa2b3b067ec9760ae', '2025-05-23 22:10:47', '2025-05-23 22:10:47'),
(22, 125, 'Etkinlik 3', 'https://wordwall.net/tr/embed/49bd4eb16e484d6890bbbe28d5cc6055', '2025-05-23 22:10:47', '2025-05-23 22:10:47'),
(23, 126, '111', 'https://wordwall.net/tr/embed/766d300d105546aaa2b3b067ec9760ae', '2025-05-24 10:27:01', '2025-05-24 10:27:01'),
(31, 127, 'Etkinlik 1', 'https://wordwall.net/tr/embed/766d300d105546aaa2b3b067ec9760ae', '2025-05-24 10:30:57', '2025-05-24 10:30:57'),
(32, 127, 'Etkinlik 3', 'https://wordwall.net/tr/embed/766d300d105546aaa2b3b067ec9760ae', '2025-05-24 10:30:57', '2025-05-24 10:30:57'),
(33, 127, 'Etkinlik4', 'https://wordwall.net/tr/embed/766d300d105546aaa2b3b067ec9760ae', '2025-05-24 10:30:57', '2025-05-24 10:30:57');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `main_school_classes_lnp`
--

CREATE TABLE `main_school_classes_lnp` (
  `id` int(11) NOT NULL,
  `name` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci NOT NULL,
  `class_grade` int(11) NOT NULL,
  `school_id` int(11) DEFAULT 1,
  `slug` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `main_school_classes_lnp`
--

INSERT INTO `main_school_classes_lnp` (`id`, `name`, `class_grade`, `school_id`, `slug`, `created_at`, `updated_at`) VALUES
(2, '3-4 Yaş', 0, 1, '3-4-yas', '2025-05-02 13:08:47', '2025-05-07 10:13:07'),
(3, '4-5 Yaş', 0, 1, '4-5-yas', '2025-03-04 15:41:02', '2025-05-07 10:13:08'),
(4, '5-6 Yaş', 0, 1, '5-6-yas', '2025-03-04 15:41:32', '2025-05-07 10:13:10'),
(14, 'testt2', 0, 1, 'testt', '2025-05-07 14:37:10', '2025-05-07 14:39:54'),
(15, 'test', 0, 1, 'test-1', '2025-05-08 08:18:23', '2025-05-08 08:18:23');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `main_school_content_lnp`
--

CREATE TABLE `main_school_content_lnp` (
  `id` int(11) NOT NULL,
  `roles` varchar(255) DEFAULT NULL,
  `month` varchar(255) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `week_id` int(11) DEFAULT NULL,
  `content_title_id` int(255) DEFAULT NULL,
  `content` text DEFAULT NULL,
  `content_description` text DEFAULT NULL,
  `video_url` text DEFAULT NULL,
  `file_path` varchar(500) DEFAULT NULL,
  `activity_title_id` varchar(500) DEFAULT NULL,
  `concept_title_id` varchar(500) DEFAULT NULL COMMENT 'kavram',
  `main_school_class_id` int(11) NOT NULL DEFAULT 2,
  `school_id` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci;

--
-- Tablo döküm verisi `main_school_content_lnp`
--

INSERT INTO `main_school_content_lnp` (`id`, `roles`, `month`, `subject`, `week_id`, `content_title_id`, `content`, `content_description`, `video_url`, `file_path`, `activity_title_id`, `concept_title_id`, `main_school_class_id`, `school_id`, `created_at`, `updated_at`) VALUES
(55, '10001,10002', 'Şubat', 'sonmm', 0, 0, NULL, NULL, NULL, NULL, '', '', 2, 1, '2025-05-13 09:08:31', '2025-05-13 09:08:31'),
(56, '10001,10002', 'Şubat', 'yaşşş', 2, 0, NULL, NULL, NULL, NULL, '3', '2', 2, 1, '2025-05-13 12:26:47', '2025-05-13 12:26:47'),
(57, '7,10001,10002', 'Temmuz', 'ete', 25, 4, NULL, 'What is Lorem Ipsum?\r\nLorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.', '1051061734', NULL, '7', '9', 14, 1, '2025-05-13 12:27:33', '2025-05-13 12:27:33'),
(92, '10001,10002', 'Şubat', 'Konu Başlığı', 2, 1, 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.', 'İçerik Özet', 'https://www.youtube.com/watch?v=mFYfftceDWM', NULL, '3', '2', 12, 1, '2025-05-16 11:46:02', '2025-05-16 11:46:02'),
(93, '10001,10002', 'Ocak', 'Konu Başlığı', 2, 1, '', 'Özet Bilgi', 'https://www.youtube.com/watch?v=mFYfftceDWM', NULL, '3', '2', 10, 1, '2025-05-16 11:54:20', '2025-05-16 11:54:20'),
(94, '10001,10002', 'Şubat', 'Konu', 2, 1, '<p><strong style=\"margin: 0px; padding: 0px; font-family: \'Open Sans\', Arial, sans-serif; font-size: 14px; text-align: justify; background-color: #ffffff;\">Lorem Ipsum</strong><span style=\"font-family: \'Open Sans\', Arial, sans-serif; font-size: 14px; text-align: justify; background-color: #ffffff;\">&nbsp;is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</span></p>', 'İçerik Özet', 'https://www.youtube.com/watch?v=mFYfftceDWM', NULL, '3', '2', 12, 1, '2025-05-16 12:00:59', '2025-05-16 12:00:59'),
(95, '10001,10002', 'Ocak', 'Konu', 2, 1, '<p><span style=\"color: #071437; font-family: Inter, Helvetica, \'sans-serif\'; font-size: 13px; background-color: #ffffff;\">Metin İ&ccedil;eriği:</span></p>', 'İçerik Özet', 'https://www.youtube.com/watch?v=mFYfftceDWM', NULL, '3', '2', 11, 1, '2025-05-16 12:11:39', '2025-05-16 12:11:39'),
(96, '10001,10002', 'Ocak', 'Admin Panel - How To Get Started Tutorial. Create a customizable SaaS Based applications and solutions', 0, 0, '<p>First, a disclaimer – the entire process of writing a blog post often takes more than a couple of hours, even if you can type eighty words per minute and your writing skills are sharp. From the seed of the idea to finally hitting “Publish,” you might spend several days or maybe even a week “writing” a blog post, but it’s important to spend those vital hours planning your post and even thinking about Your Post(yes, thinking counts as working if you’re a blogger) before you actually write it.\n\nThere’s an old maxim that states, “No fun for the writer, no fun for the reader.”No matter what industry you’re working in, as a blogger, you should live and die by this statement.\n\nBefore you do any of the following steps, be sure to pick a topic that actually interests you. Nothing – and I mean NOTHING– will kill a blog post more effectively than a lack of enthusiasm from the writer. You can tell when a writer is bored by their subject, and it’s so cringe-worthy it’s a little embarrassing.\n\nI can hear your objections already. “But Dan, I have to blog for a cardboard box manufacturing company.” I feel your pain, I really do. During the course of my career, I’ve written content for dozens of clients in some less-than-thrilling industries (such as financial regulatory compliance and corporate housing), but the hallmark of a professional blogger is the ability to write well about any topic, no matter how dry it may be. Blogging is a lot easier, however, if you can muster at least a little enthusiasm for the topic at hand.</p>', 'First, a disclaimer – the entire process of writing a blog post often takes more than a couple of hours, even if you can type eighty words per minute and your writing skills are sharp writing a blog post often takes more than a couple.', 'https://www.youtube.com/watch?v=mFYfftceDWM', NULL, '', '', 10, 1, '2025-05-19 06:05:57', '2025-05-19 06:05:57'),
(97, '10002', 'Mayıs', '3 Boyutlu Karpuz Yapımı Sanat Etkinliği', 0, 0, '', '3 Boyutlu Karpuz Yapımı Sanat Etkinliği', 'https://youtu.be/8vR3dsN0Rm0', NULL, '77', '', 10, 1, '2025-05-22 10:13:53', '2025-05-22 10:13:53'),
(98, '10001', 'Mayıs', 'Sertab Erener – Bir Tek Annem Olsun-Anneler Günü Şarkısı ve Yazısı', 2, 0, '', 'Sertab Erener – Bir Tek Annem Olsun-Anneler Günü Şarkı Sözünü çocukların ellerinde tutarak fotoğrafı çekilebilir ve şarkı sözüne uygun olarak sıralaması yapılarak kolaj haline getirilebilir.\r\nŞarkı sözü PDF olarak aşağıya yüklenmiştir.', 'https://youtu.be/dOZq8xm2biM', NULL, '', '', 10, 1, '2025-05-22 10:50:15', '2025-05-22 10:50:15'),
(99, '10002', 'Nisan', 'Şarkıcı Kuş Sanat Etkinliği', 0, 0, '', 'Emine Öğretmen Şarkıcı Kuş Sanat Etkinliği ', 'https://youtu.be/Qifdn5GuTTU', NULL, '77', '', 10, 1, '2025-05-22 10:53:37', '2025-05-22 10:53:37'),
(100, '10002', 'Nisan', 'Toca Toca – Zumba Dance Kids', 0, 0, '', 'Toca Toca – Zumba Dance Kids', 'https://youtu.be/rlO7bQQzJXI', NULL, '', '', 10, 1, '2025-05-22 10:55:16', '2025-05-22 10:55:16'),
(101, '10002', 'Nisan', 'Zıpla, Hopla, Dans Et ! – Tav Tav ve Arkadaşlarıyla Zumba !', 0, 0, '', 'Zıpla, Hopla, Dans Et ! – Tav Tav ve Arkadaşlarıyla Zumba !', 'https://youtu.be/MiX5wBhevtc', NULL, '', '', 10, 1, '2025-05-22 10:55:41', '2025-05-22 10:55:41'),
(102, '10002', 'Mart', 'Bayram Şekeri Paketleme Örnekleri', 0, 0, '', 'Bayram Şekeri Paketleme Örnekleri', 'https://youtu.be/BfWx5IbRfgI', NULL, '', '', 10, 1, '2025-05-22 10:57:01', '2025-05-22 10:57:01'),
(103, '10002', 'Şubat', 'Çocuklar İçin Ramazan İlahisi ve Oruç İlahisi', 38, 0, '', 'Çocuklar İçin Ramazan İlahisi ve Oruç İlahisi', 'https://youtu.be/c-EU9AoVsPU', NULL, '75', '', 10, 1, '2025-05-22 10:58:24', '2025-05-22 10:58:24'),
(104, '10002', 'Şubat', 'Karıncayiyen Hazine Arıyor Hikâyesi', 0, 35, '', 'Karıncayiyen Hazine Arıyor Hikâyesi', 'https://youtu.be/FAYWGQRB_Xs', NULL, '', '', 10, 1, '2025-05-22 10:59:12', '2025-05-22 10:59:12'),
(105, '10002', 'Şubat', 'Suda Açan Çiçekler Deneyi', 0, 17, '', 'Suda Açan Çiçekler Deneyi', 'https://youtu.be/KcmYz7JdI-g', NULL, '53', '', 10, 1, '2025-05-22 11:00:43', '2025-05-22 11:00:43'),
(106, '10002', 'Şubat', 'Mimar Sinan ve Mihrimah Sultan Camii (Mimar Sinan)-Mati ve Dada ile Sanat', 0, 10, '', 'Mimar Sinan ve Mihrimah Sultan Camii (Mimar Sinan)-Mati ve Dada ile Sanat', 'https://youtu.be/KWAIW2qeS98', NULL, '67', '', 10, 1, '2025-05-22 11:01:35', '2025-05-22 11:01:35'),
(107, '10002', 'Şubat', 'Zaman Makinesiyle Mimar Sinan’a Yolculuk -Çocuklar İçin Eğitici Tarih Masalı', 0, 10, '', 'Zaman Makinesiyle Mimar Sinan’a Yolculuk -Çocuklar İçin Eğitici Tarih Masalı', 'https://youtu.be/of6ms_0my7Q', NULL, '67', '', 10, 1, '2025-05-22 11:02:05', '2025-05-22 11:02:05'),
(108, '10002', 'Şubat', 'Dünya Miraslarında: Mimar Sinan! Eğitici Film', 0, 10, '', 'Dünya Miraslarında: Mimar Sinan! Eğitici Film', 'https://youtu.be/IELoLsEvODA', NULL, '67', '', 10, 1, '2025-05-22 11:02:50', '2025-05-22 11:02:50'),
(109, '10002', 'Şubat', 'Yade Yade | Mozaik Yol Eğitici Film', 0, 10, '', 'Yade Yade | Mozaik Yol Eğitici Film', 'https://youtu.be/VGcQwO6yT3k', NULL, '67', '', 10, 1, '2025-05-22 11:03:20', '2025-05-22 11:03:20'),
(110, '10002', 'Şubat', 'Çanakkale İçinde Türküsü', 37, 0, '', 'Çanakkale İçinde Türküsü', 'https://youtu.be/p6z5gVFEHmA', NULL, '', '', 10, 1, '2025-05-22 11:04:28', '2025-05-22 11:04:28'),
(111, '10002', 'Ocak', 'Arı Maya 1 Animasyon Filmler Tek Parça Türkçe Dublaj izle', 0, 10, '', 'Arı Maya 1 Animasyon Filmler Tek Parça Türkçe Dublaj izle', 'https://youtu.be/amB_oOZdX9E', NULL, '67', '', 10, 1, '2025-05-22 11:05:32', '2025-05-22 11:05:32'),
(112, '10002', 'Ocak', 'Kahraman Balık | Animasyon Filmi (Türkçe Dublaj)', 0, 10, '', 'Kahraman Balık | Animasyon Filmi (Türkçe Dublaj)', 'https://youtu.be/VIh4YPRwix8', NULL, '67', '', 10, 1, '2025-05-22 11:05:59', '2025-05-22 11:05:59'),
(113, '10002', 'Aralık', 'Yade Yade | Çember Gösterisi Eğitici Film', 0, 10, '', 'Yade Yade | Çember Gösterisi Eğitici Film', 'https://youtu.be/1CnhhKByVwM', NULL, '67', '', 10, 1, '2025-05-22 11:07:43', '2025-05-22 11:07:43'),
(114, '10002', 'Kasım', 'Atam Tutam Men Seni – Ekin Gökçe Küçük – Çocuklara Türküler', 0, 43, '', 'Atam Tutam Men Seni – Ekin Gökçe Küçük – Çocuklara Türküler', 'https://youtu.be/nJGJ6TILRwM', NULL, '86', '', 10, 1, '2025-05-22 11:08:54', '2025-05-22 11:08:54'),
(115, '10002', 'Kasım', 'Burçak Tarlası – Çağlasu Aslan – Çocuklara Türküler', 0, 43, '', 'Burçak Tarlası – Çağlasu Aslan – Çocuklara Türküler', 'https://youtu.be/Syf0uXFb1rk', NULL, '86', '', 10, 1, '2025-05-22 11:09:18', '2025-05-22 11:09:18'),
(116, '10002', 'Kasım', 'Gezsen Anadolu’yu Şarkısı', 0, 43, '', 'Gezsen Anadolu’yu Şarkısı', 'https://youtu.be/K_YlVtvd0Ws', NULL, '86', '', 10, 1, '2025-05-22 11:24:48', '2025-05-22 11:24:48'),
(117, '10002', 'Kasım', 'Bir Başkadır Benim Memleketim Şarkısı', 0, 43, '', 'Bir Başkadır Benim Memleketim Şarkısı', 'https://youtu.be/l7yzWM5n5_k', NULL, '86', '', 10, 1, '2025-05-22 11:25:19', '2025-05-22 11:25:19'),
(118, '10002', 'Kasım', 'Blippi Örümcek Ağlarını Keşfediyor? Eğitici Film', 0, 10, '', 'Blippi Örümcek Ağlarını Keşfediyor? Eğitici Film', 'https://youtu.be/cqPrzxokLxQ', NULL, '67', '', 10, 1, '2025-05-22 11:25:55', '2025-05-22 11:25:55'),
(119, '10002', 'Kasım', 'TRT EBA TV Dinozor Çiziyorum', 0, 10, '', 'TRT EBA TV Dinozor Çiziyorum', 'https://youtu.be/m7gnlFnSWJs', NULL, '67', '', 10, 1, '2025-05-22 11:26:43', '2025-05-22 11:26:43'),
(120, '10002', 'Kasım', '28 Kasım 2024 Perşembe İnteraktif Oyunlar', 0, 0, '', '28 Kasım 2024 Perşembe İnteraktif Oyunlar', 'https://wordwall.net/tr/resource/8538399', NULL, '', '97', 10, 1, '2025-05-22 11:46:21', '2025-05-22 11:46:21'),
(121, '10002', 'Ekim', '10 Kasım – Saat 9’u 5 Geçe', 39, 43, '', '', 'https://youtu.be/LModJJLZJq0', NULL, '86', '', 10, 1, '2025-05-22 11:48:17', '2025-05-22 11:48:17'),
(122, '10001,10002', 'Şubat', '24 mayıs', 25, 1, '<p>Lorem ipsom dolor</p>', 'özet', 'https://www.youtube.com/watch?v=mFYfftceDWM', NULL, '3', '2', 10, 1, '2025-05-23 21:20:35', '2025-05-23 21:20:35'),
(123, '10001,10002', 'Ocak', 'test', 2, 1, '<p>teststs</p>', 'ssfsfs', 'https://www.youtube.com/watch?v=mFYfftceDWM', NULL, '3', '', 10, 1, '2025-05-23 21:32:43', '2025-05-23 21:32:43'),
(124, '10001,10002', 'Ocak', 'test', 24, 1, '<p>test</p>', 'İçerik Özet', 'https://www.youtube.com/watch?v=mFYfftceDWM', NULL, '3', '', 10, 1, '2025-05-23 21:35:13', '2025-05-23 21:35:13'),
(125, '10001,10002', 'Ocak', 'Özet', 0, 0, '<p>eretete</p>', 'İçerik Özet', 'https://www.youtube.com/watch?v=mFYfftceDWM', NULL, '', '', 12, 1, '2025-05-23 21:37:06', '2025-05-23 21:37:06'),
(126, '10001,10002', 'Ocak', 'tests', 0, 0, '', '', '', NULL, '', '', 10, 1, '2025-05-24 10:27:01', '2025-05-24 10:27:01'),
(127, '10001,10002', 'Ocak', 'konu 24.05', 2, 1, '<p>dadada</p>', 'İçerik Özet', 'https://www.youtube.com/watch?v=mFYfftceDWM', NULL, '3', '2', 10, 1, '2025-05-24 10:28:39', '2025-05-24 10:28:39');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `main_school_primary_images`
--

CREATE TABLE `main_school_primary_images` (
  `id` int(11) NOT NULL,
  `file_path` varchar(500) NOT NULL,
  `main_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci;

--
-- Tablo döküm verisi `main_school_primary_images`
--

INSERT INTO `main_school_primary_images` (`id`, `file_path`, `main_id`) VALUES
(1, 'uploads/preschool/primaryimages/Diagram 1 (10).png', 83),
(2, 'uploads/preschool/primaryimages/Diagram 1 (9).png', 83),
(3, 'uploads/preschool/primaryimages/Diagram 1 (8).png', 84),
(4, 'uploads/preschool/primaryimages/Diagram 1 (7).png', 84),
(5, 'uploads/preschool/primaryimages/Diagram 1 (10).png', 85),
(7, 'uploads/preschool/primaryimages/Diagram 1 (9).png', 86),
(8, 'uploads/preschool/primaryimages/kahve-kumbara-1.jpg', 87),
(9, 'uploads/preschool/primaryimages/Diagram 1 (10).png', 88),
(10, 'uploads/preschool/primaryimages/Diagram 1 (10).png', 89),
(11, 'uploads/preschool/primaryimages/Diagram 1 (9).png', 89),
(12, 'uploads/preschool/primaryimages/Diagram 1 (6).png', 90),
(14, 'uploads/preschool/primaryimages/Diagram 1 (8).png', 91),
(15, 'uploads/preschool/primaryimages/Diagram 1 (10).png', 92),
(16, 'uploads/preschool/primaryimages/Diagram 1 (9).png', 92),
(17, 'uploads/preschool/primaryimages/istockphoto-998509626-612x612.jpg', 93),
(18, 'uploads/preschool/primaryimages/istockphoto-998509626-612x612.jpg', 94),
(19, 'uploads/preschool/primaryimages/istockphoto-998509626-612x612.jpg', 95),
(20, 'uploads/preschool/primaryimages/istockphoto-998509626-612x612.jpg', 96),
(21, 'uploads/preschool/primaryimages/istockphoto-998509626-612x612.jpg', 122),
(22, 'uploads/preschool/primaryimages/istockphoto-998509626-612x612.jpg', 123),
(23, 'uploads/preschool/primaryimages/istockphoto-998509626-612x612.jpg', 124),
(24, 'uploads/preschool/primaryimages/istockphoto-998509626-612x612.jpg', 125),
(25, 'uploads/preschool/primaryimages/istockphoto-998509626-612x612.jpg', 127);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `menusparent_lnp`
--

CREATE TABLE `menusparent_lnp` (
  `id` int(11) NOT NULL,
  `name` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci NOT NULL,
  `classes` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci NOT NULL,
  `accordion` int(11) NOT NULL,
  `parent` int(11) NOT NULL,
  `role` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `menusparent_lnp`
--

INSERT INTO `menusparent_lnp` (`id`, `name`, `classes`, `slug`, `accordion`, `parent`, `role`, `created_at`, `updated_at`) VALUES
(24, 'Dersler', 'fa-solid fa-book-open', 'dersler', 1, 1, '2', '2025-04-02 12:33:48', '2025-04-28 07:54:23'),
(25, 'Kullanıcı Yönetimi', 'fa-solid fa-users-line', 'kullanici-yonetimi', 2, 2, '1,3,4', '2025-04-02 12:33:48', '2025-04-28 07:53:14'),
(26, 'Ders ve İçerik Yönetimi', 'fa-solid fa-book-open', 'ders-ve-icerik-yonetimi', 3, 3, '1,3,4', '2025-04-02 12:33:48', '2025-04-28 07:53:19'),
(27, 'İlerleme ve Performans Takibi', 'fa-solid fa-magnifying-glass', 'ilerleme-ve-performans-takibi', 4, 4, '1,3,4,5,2', '2025-04-17 14:55:02', '2025-04-28 07:54:13'),
(28, 'Sistem Bildirimleri ve Duyurular', 'fa-solid fa-bullhorn', 'sistem-bildirimleri-ve-duyurular', 5, 5, '1,3,4,5,2', '2025-04-17 15:00:57', '2025-04-28 07:54:13'),
(29, 'Raporlama ve Analitik', 'fa-solid fa-chart-line', 'raporlama-ve-analitik', 6, 6, '1,3,4,5,2', '2025-04-17 15:08:16', '2025-04-28 07:54:13'),
(30, 'Sistem ve Güvenlik Yönetimi', 'fa-solid fa-shield-halved', 'sistem-ve-guvenlik-yonetimi', 7, 7, '1', '2025-04-17 15:10:16', '2025-04-28 07:54:13'),
(31, 'Ödev ve Test Yönetimi', 'fa-regular fa-rectangle-list', 'odev-ve-test-yonetimi', 8, 8, '1,3,4,5,2', '2025-04-17 15:10:16', '2025-04-28 07:54:13'),
(32, 'Destek ve Geri Bildirim Yönetimi', 'fa-regular fa-circle-question', 'destek-ve-geri-bildirim-yonetimi', 9, 9, '1,3,4,5,2', '2025-04-17 15:12:26', '2025-04-28 07:54:13'),
(33, 'İçerik Yükleme ve Depolama Yönetimi', 'fa-regular fa-square-caret-up', 'icerik-yukleme-ve-depolama-yonetimi', 10, 10, '1', '2025-04-17 15:13:01', '2025-04-28 07:54:13'),
(34, 'Entegrasyon ve API Yönetimi', 'fa-solid fa-gears', 'entegrasyon-ve-api-yonetimi', 11, 11, '1', '2025-04-17 15:14:54', '2025-04-28 07:54:13'),
(35, 'Muhasebe Paneli', 'fa-solid fa-cash-register', 'muhasebe-paneli', 12, 12, '1', '2025-04-17 15:15:24', '2025-04-28 07:54:13'),
(36, 'Teknik Servis Paneli', 'fa-solid fa-wrench', 'teknik-servis-paneli', 13, 13, '1', '2025-04-17 15:16:21', '2025-04-28 07:54:13'),
(37, 'Ana Okulu', 'fa-solid fa-book-open', 'ana-okulu', 55, 55, '1,3,4,10001,10002', '2025-04-02 12:33:48', '2025-05-12 07:12:12'),
(38, 'Paketler', 'fa-solid fa-box', 'paketler', 56, 56, '1,3,4', '2025-04-02 09:33:48', '2025-05-19 16:34:02');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `menus_lnp`
--

CREATE TABLE `menus_lnp` (
  `id` int(11) NOT NULL,
  `name` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci NOT NULL,
  `classes` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci NOT NULL,
  `accordion` int(11) NOT NULL,
  `parent` int(11) NOT NULL,
  `role` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `menus_lnp`
--

INSERT INTO `menus_lnp` (`id`, `name`, `classes`, `slug`, `accordion`, `parent`, `role`, `created_at`, `updated_at`) VALUES
(1, 'Okullar', '', 'okullar', 0, 2, '1', '2025-02-26 10:29:09', '2025-04-28 07:56:05'),
(2, 'Öğretmenler', '', 'ogretmenler', 0, 2, '1,3', '2025-03-12 12:25:13', '2025-04-28 07:56:05'),
(3, 'Öğrenciler', '', 'ogrenciler', 0, 2, '1,3,4', '2025-03-12 12:25:13', '2025-04-28 07:56:05'),
(4, 'Sınıflar', '', 'siniflar', 0, 3, '1', '2025-02-26 10:31:35', '2025-04-28 07:56:05'),
(5, 'Dersler', '', 'dersler', 0, 3, '1', '2025-03-04 12:19:59', '2025-04-28 07:56:05'),
(6, 'Üniteler', '', 'uniteler', 0, 3, '1,3,4', '2025-03-05 14:27:58', '2025-04-28 07:56:05'),
(7, 'Konular', '', 'konular', 0, 3, '1,3,4', '2025-03-06 12:38:50', '2025-04-28 07:56:05'),
(8, 'Alt Konular', '', 'alt-konular', 0, 3, '1,3,4', '2025-03-06 12:38:50', '2025-04-28 07:56:05'),
(9, 'Haftalık Görev', '', 'haftalik-gorev', 0, 3, '1', '2025-03-04 12:30:03', '2025-04-28 07:56:05'),
(10, 'Denemeler', '', 'denemeler', 0, 3, '1', '2025-03-04 12:30:03', '2025-04-28 07:56:05'),
(11, 'Sesli Kitap', '', 'sesli-kitaplar', 0, 3, '1', '2025-03-04 12:30:34', '2025-04-28 07:56:05'),
(12, 'Oyunlar', '', 'oyunlar', 0, 3, '1', '2025-03-04 12:30:34', '2025-04-28 07:56:05'),
(27, 'Veliler', '', 'veliler', 0, 2, '1,3,4', '2025-03-12 12:25:13', '2025-04-28 07:56:05'),
(29, 'Duyurular', '', 'duyurular', 0, 5, '1,2', '2025-04-17 19:50:37', '2025-04-28 09:04:03'),
(30, 'Bildirimler', '', 'bildirimler', 0, 5, '1,2', '2025-04-17 19:50:37', '2025-04-28 09:04:05'),
(31, 'Kullanıcı Yetkilendirme', '', 'kullanici-yetkilendirme', 0, 7, '1', '2025-04-17 19:50:37', '2025-04-28 07:56:05'),
(32, 'Kullanıcı Grupları', '', 'kullanici-gruplari', 0, 7, '1', '2025-04-17 19:50:37', '2025-04-28 07:56:05'),
(33, 'Test Ekle', '', 'test-ekle', 0, 8, '1', '2025-04-17 19:50:37', '2025-04-28 07:56:05'),
(34, 'Testler', '', 'testler', 0, 8, '1', '2025-04-17 19:50:37', '2025-04-28 07:56:05'),
(35, 'Türkçe', '', 'turkce', 0, 1, '2', '2025-04-17 19:50:37', '2025-04-28 07:56:21'),
(36, 'Matematik', '', 'matematik', 0, 1, '2', '2025-04-17 19:50:37', '2025-04-28 07:56:21'),
(37, 'Hayat Bilgisi', '', 'hayat-bilgisi', 0, 1, '2', '2025-04-17 19:50:37', '2025-04-28 07:56:21'),
(38, 'İngilizce', '', 'ingilizce', 0, 1, '2', '2025-04-17 19:50:37', '2025-04-28 07:56:21'),
(39, 'Fen Bilimleri', '', 'fen-bilimleri', 0, 1, '2', '2025-04-17 19:50:37', '2025-04-28 07:56:21'),
(40, 'Sosyal Bilgiler', '', 'sosyal-bilgiler', 0, 1, '2', '2025-04-17 19:50:37', '2025-04-28 07:56:21'),
(41, 'Testler', '', 'testler-ogrenci', 0, 8, '2', '2025-04-17 19:50:37', '2025-04-28 07:56:05'),
(42, 'Yeni Destek Talebi', '', 'destek-talebi-ekle', 0, 9, '2', '2025-04-17 19:50:37', '2025-04-28 21:49:26'),
(43, 'Aktif Destek Talepleri', '', 'aktif-destek-talepleri', 0, 9, '1,2', '2025-04-17 19:50:37', '2025-04-28 21:49:07'),
(44, 'Çözülmüş Destek Talepleri', '', 'cozulmus-destek-talepleri', 0, 9, '1,2', '2025-04-17 19:50:37', '2025-04-28 21:49:09'),
(45, 'Yaş Grupları', '', 'yas-grubu', 0, 55, '1,10001', '2025-02-26 10:31:35', '2025-05-12 21:06:12'),
(46, 'Önemli Haftalar', '', 'onemli-haftalar', 0, 55, '1,10001', '2025-02-26 10:31:35', '2025-05-12 21:06:18'),
(48, 'Kategori Başlıkları', '', 'kategori-basliklari', 0, 55, '1,10001', '2025-02-26 10:31:35', '2025-05-12 21:06:20'),
(51, 'İçerik Yönetimi', '', 'ana-okulu-icerik-yonetimi', 0, 55, '1,10001,10002', '2025-02-26 10:31:35', '2025-05-12 21:00:59'),
(52, 'İçerikler', '', 'ana-okulu-icerikler', 0, 55, '1,10001,10002', '2025-02-26 10:31:35', '2025-05-12 21:00:59'),
(53, 'Paket Listesi', '', 'paketler', 0, 56, '1', '2025-02-26 07:31:35', '2025-05-19 16:35:29'),
(54, 'Ayarlar', '', 'ayarlar', 0, 12, '1', '2025-02-26 07:31:35', '2025-05-20 13:32:12'),
(55, 'Ödeme Raporu', '', 'kullanici-odeme-raporu', 0, 12, '1', '2025-02-26 07:31:35', '2025-05-20 13:34:37'),
(56, 'Ödeme Grafik Raporu', '', 'grafik-rapor', 0, 12, '1', '2025-02-26 07:31:35', '2025-05-21 13:55:28'),
(57, 'Ödeme Tipi Raporu', '', 'odeme-tipi-grafik-rapor', 0, 12, '1', '2025-02-26 07:31:35', '2025-05-20 13:34:37'),
(58, 'Kullanıcı Başına Grafik Raporu', '', 'kullanici-basina-gelir-raporu', 0, 12, '1', '2025-02-26 07:31:35', '2025-05-20 13:34:37'),
(59, 'Paket Kullanım Grafik Raporu', '', 'paket-kullanim-durumlari', 0, 12, '1', '2025-02-26 07:31:35', '2025-05-21 10:53:53'),
(60, 'Abonelik Oranları', '', 'abonelik-buyume-oranlari', 0, 12, '1', '2025-02-26 07:31:35', '2025-05-21 13:35:15'),
(61, 'Abonelik Kayıp Oranları', '', 'abonelik-kayip-oranlari', 0, 12, '1', '2025-02-26 07:31:35', '2025-05-21 13:35:15'),
(62, 'Ayarlar', '', 'api-ayarlar', 11, 11, '1', '2025-02-26 07:31:35', '2025-05-21 13:35:15'),
(63, 'Paket Bitişi Yaklaşan Aboneler', '', 'paket-bitisi-yaklasan-aboneler', 0, 12, '1', '2025-02-26 07:31:35', '2025-05-21 20:05:55'),
(64, 'Fatura Excel Raporu', '', 'fatura-excel-rapor', 0, 12, '1', '2025-02-26 07:31:35', '2025-05-21 20:05:55');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `money_transfer_discount_lnp`
--

CREATE TABLE `money_transfer_discount_lnp` (
  `id` int(11) NOT NULL,
  `discount` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `money_transfer_discount_lnp`
--

INSERT INTO `money_transfer_discount_lnp` (`id`, `discount`) VALUES
(1, 10);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `money_transfer_list_lnp`
--

CREATE TABLE `money_transfer_list_lnp` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 0,
  `order_no` varchar(64) NOT NULL,
  `ip_address` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci NOT NULL,
  `pack_id` int(11) NOT NULL,
  `amount` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci NOT NULL,
  `kdv_amount` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci NOT NULL,
  `kdv_percent` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci NOT NULL,
  `coupon` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `money_transfer_list_lnp`
--

INSERT INTO `money_transfer_list_lnp` (`id`, `user_id`, `status`, `order_no`, `ip_address`, `pack_id`, `amount`, `kdv_amount`, `kdv_percent`, `coupon`, `created_at`, `updated_at`) VALUES
(1, 93, 1, '2147483647', '::1', 5, '1782', '', '', '', '2025-05-17 18:16:03', '2025-05-17 20:37:02'),
(4, 100, 1, '6915166802077400060', '::1', 14, '1836', '', '', 'O4EPS21E', '2025-05-18 09:29:13', '2025-05-18 11:46:14'),
(5, 102, 1, '13088227341932726882', '::1', 15, '1417.5', '', '', '', '2025-05-18 09:43:13', '2025-05-18 11:45:54'),
(6, 108, 0, '7532469851969570018', '::1', 13, '2904.00', '290.4', '10', '', '2025-05-20 11:01:47', '2025-05-20 11:01:47'),
(7, 110, 1, '243407189714666859', '::1', 5, '2603.70', '289.3', '10', 'O4EPS21E', '2025-05-20 13:01:43', '2025-05-20 13:09:00'),
(8, 112, 0, '1360463861956142682', '::1', 21, '2484.90', '276.1', '10', 'O4EPS21E', '2025-05-20 13:13:50', '2025-05-20 13:13:50'),
(9, 114, 0, '5755072331662281205', '::1', 5, '2603.70', '289.3', '10', 'O4EPS21E', '2025-05-20 13:20:42', '2025-05-20 13:20:42'),
(10, 111, 0, '570244696891922130', '::1', 21, '2494.80', '277.2', '10', '', '2025-05-21 12:54:54', '2025-05-21 12:54:54'),
(11, 113, 1, '1610759359265263505', '::1', 13, '2613.60', '290.4', '10', '', '2025-05-21 12:58:30', '2025-05-21 13:01:42');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `notifications_lnp`
--

CREATE TABLE `notifications_lnp` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `slug` varchar(512) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_by` int(11) NOT NULL,
  `start_date` datetime NOT NULL,
  `expire_date` datetime DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `target_type` enum('all','roles','classes','lessons','units','topics','subtopics','assignments') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

--
-- Tablo döküm verisi `notifications_lnp`
--

INSERT INTO `notifications_lnp` (`id`, `title`, `content`, `slug`, `created_by`, `start_date`, `expire_date`, `is_active`, `created_at`, `updated_at`, `target_type`) VALUES
(1, 'mathematical rules of quantum ', ' the number of molecules in one mole of substance.[7] The constant h is now known as the Planck constant. After his theory was validated, Planck was awarded the Nobel Prize in Physics for his discovery in 1918.', 'wefewgra-egagegea', 1, '2025-05-21 00:00:00', '2025-05-30 00:00:00', 1, '2025-05-22 16:00:40', '2025-05-22 22:09:43', 'subtopics'),
(2, 'Overview and fundamental concepts', 'In physics, a quantum (pl.: quanta) is the minimum amount of any physical entity (physical property) involved in an interaction. The fundamental notion that a property can be \"quantized\" is referred to as \"the hypothesis of quantization\".[1] This means that the magnitude of the physical property can take on only discrete values consisting of integer multiples of one quantum. For example, a photon is a single quantum of light of a specific frequency (or of any other form of electromagnetic radiation). Similarly, the energy of an electron bound within an atom is quantized and can exist only in certain discrete values.[2] Atoms and matter in general are stable because electrons can exist only at discrete energy levels within an atom. Quantization is one of the foundations of the much broader physics of quantum mechanics. Quantization of energy and its influence on how energy and matter interact (quantum electrodynamics) is part of the fundamental framework for understanding and describing nature.', 'wegegwfewa', 1, '2025-05-22 00:00:00', '2025-05-29 00:00:00', 1, '2025-05-22 16:28:44', '2025-05-22 22:09:22', 'all'),
(3, 'Quantum', 'While quantization was first discovered in electromagnetic radiation, it describes a fundamental aspect of energy not just restricted to photons.[11] In the attempt to bring theory into agreement with experiment, Max Planck postulated that electromagnetic energy is absorbed or emitted in discrete packets, or quanta.[12]', 'gergrageas', 1, '2025-05-23 00:00:00', '2025-06-07 00:00:00', 1, '2025-05-22 16:29:34', '2025-05-22 22:09:18', 'roles'),
(4, 'Quantum mechanics', 'Quantum mechanics is the fundamental physical theory that describes the behavior of matter and of light; its unusual characteristics typically occur at and below the scale of atoms.[2]: 1.1  It is the foundation of all quantum physics, which includes quantum chemistry, quantum field theory, quantum technology, and quantum information science.', 'drfgrgerger', 1, '2025-05-26 00:00:00', '2025-05-31 00:00:00', 1, '2025-05-22 18:27:42', '2025-05-22 23:33:41', 'roles'),
(5, 'Mandelbrot', 'This set was first defined and drawn by Robert W. Brooks and Peter Matelski in 1978, as part of a study of Kleinian groups.[4] Afterwards, in 1980, Benoit Mandelbrot obtained high-quality visualizations of the set while working at IBM\'s Thomas J. Watson Research Center in Yorktown Heights, New York.[5]', 'mandelbrot', 1, '2025-05-23 00:00:00', '2025-05-31 00:00:00', 1, '2025-05-23 01:04:07', '2025-05-23 01:23:15', 'all');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `notification_targets_lnp`
--

CREATE TABLE `notification_targets_lnp` (
  `id` int(11) NOT NULL,
  `notification_id` int(11) NOT NULL,
  `target_type` enum('all','roles','classes','lessons','units','topics','subtopics','assignments') NOT NULL,
  `target_value` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

--
-- Tablo döküm verisi `notification_targets_lnp`
--

INSERT INTO `notification_targets_lnp` (`id`, `notification_id`, `target_type`, `target_value`) VALUES
(1, 1, 'subtopics', '4'),
(2, 3, 'roles', '2'),
(3, 4, 'roles', '2');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `notification_views_lnp`
--

CREATE TABLE `notification_views_lnp` (
  `id` int(11) NOT NULL,
  `notification_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `viewed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

--
-- Tablo döküm verisi `notification_views_lnp`
--

INSERT INTO `notification_views_lnp` (`id`, `notification_id`, `user_id`, `viewed_at`) VALUES
(16, 5, 25, '2025-05-23 01:21:58'),
(17, 2, 1, '2025-05-23 08:10:07'),
(18, 5, 1, '2025-05-23 08:26:43');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `packages_lnp`
--

CREATE TABLE `packages_lnp` (
  `id` int(11) NOT NULL,
  `name` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci NOT NULL,
  `class_id` int(11) NOT NULL,
  `monthly_fee` int(11) NOT NULL,
  `max_installment` int(11) DEFAULT NULL,
  `discount` int(11) DEFAULT NULL,
  `subscription_period` int(11) NOT NULL,
  `image` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 1 COMMENT '1:aktif 0 pasif',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `packages_lnp`
--

INSERT INTO `packages_lnp` (`id`, `name`, `class_id`, `monthly_fee`, `max_installment`, `discount`, `subscription_period`, `image`, `status`, `created_at`, `updated_at`) VALUES
(1, '12 Aylık Lineup Pro Üyeliği', 10, 210, 12, 10, 12, 'okul-oncesi-12-aylik.png', 0, '2025-05-20 10:13:34', '2025-05-02 13:12:36'),
(2, '6 Aylık Lineup Pro Üyeliği', 10, 330, 6, 10, 6, 'okul-oncesi-6-aylik.png', 1, '2025-05-18 08:28:28', '2025-05-02 13:12:36'),
(3, '3 Aylık Lineup Pro Üyeliği', 10, 515, 3, 10, 3, 'okul-oncesi-3-aylik.png', 1, '2025-05-18 08:28:32', '2025-05-02 13:12:36'),
(4, 'Aylık Lineup Pro Üyeliği', 10, 719, 0, 0, 1, 'okul-oncesi-aylik.png', 1, '2025-05-18 08:28:34', '2025-05-02 13:12:36'),
(5, '12 Aylık Lineup Pro Üyeliği', 3, 220, 12, 10, 12, 'birinci-sinif-12-aylik.png', 1, '2025-05-02 13:26:50', '2025-05-02 13:12:36'),
(6, '6 Aylık Lineup Pro Üyeliği', 3, 340, 6, 10, 6, 'birinci-sinif-6-aylik.png', 1, '2025-05-02 13:26:50', '2025-05-02 13:12:36'),
(7, '3 Aylık Lineup Pro Üyeliği', 3, 525, 3, 10, 3, 'birinci-sinif-3-aylik.png', 1, '2025-05-02 13:26:50', '2025-05-02 13:12:36'),
(8, 'Aylık Lineup Pro Üyeliği', 3, 729, 0, 0, 1, 'birinci-sinif-aylik.png', 1, '2025-05-02 14:03:01', '2025-05-02 13:12:36'),
(9, '12 Aylık Lineup Pro Üyeliği', 4, 220, 12, 10, 12, 'ikinci-sinif-12-aylik.png', 1, '2025-05-02 13:26:50', '2025-05-02 13:12:36'),
(10, '6 Aylık Lineup Pro Üyeliği', 4, 340, 6, 10, 6, 'ikinci-sinif-6-aylik.png', 1, '2025-05-02 13:26:50', '2025-05-02 13:12:36'),
(11, '3 Aylık Lineup Pro Üyeliği', 4, 525, 3, 10, 3, 'ikinci-sinif-3-aylik.png', 1, '2025-05-02 13:26:50', '2025-05-02 13:12:36'),
(12, 'Aylık Lineup Pro Üyeliği', 4, 729, 0, 0, 1, 'ikinci-sinif-aylik.png', 1, '2025-05-02 14:03:01', '2025-05-02 13:12:36'),
(13, '12 Aylık Lineup Pro Üyeliği', 5, 220, 12, 10, 12, 'ucuncu-sinif-12-aylik.png', 1, '2025-05-02 13:26:50', '2025-05-02 13:12:36'),
(14, '6 Aylık Lineup Pro Üyeliği', 5, 340, 6, 10, 6, 'ucuncu-sinif-6-aylik.png', 1, '2025-05-02 13:26:50', '2025-05-02 13:12:36'),
(15, '3 Aylık Lineup Pro Üyeliği', 5, 525, 3, 10, 3, 'ucuncu-sinif-3-aylik.png', 1, '2025-05-02 13:26:50', '2025-05-02 13:12:36'),
(16, 'Aylık Lineup Pro Üyeliği', 5, 729, 0, 0, 1, 'ucuncu-sinif-aylik.png', 1, '2025-05-02 14:03:01', '2025-05-02 13:12:36'),
(17, '12 Aylık Lineup Pro Üyeliği', 6, 220, 12, 10, 12, 'dorduncu-sinif-12-aylik.png', 1, '2025-05-02 13:26:50', '2025-05-02 13:12:36'),
(18, '6 Aylık Lineup Pro Üyeliği', 6, 340, 6, 10, 6, 'dorduncu-sinif-6-aylik.png', 1, '2025-05-02 13:26:50', '2025-05-02 13:12:36'),
(19, '3 Aylık Lineup Pro Üyeliği', 6, 525, 3, 10, 3, 'dorduncu-sinif-3-aylik.png', 1, '2025-05-02 13:26:50', '2025-05-02 13:12:36'),
(20, 'Aylık Lineup Pro Üyeliği', 6, 729, 0, 0, 1, 'dorduncu-sinif-aylik.png', 1, '2025-05-02 14:03:01', '2025-05-02 13:12:36'),
(21, '12 Aylık Lineup Pro Üyeliği', 11, 210, 12, 10, 12, 'okul-oncesi-12-aylik.png', 1, '2025-05-18 08:28:25', '2025-05-02 13:12:36'),
(22, '6 Aylık Lineup Pro Üyeliği', 11, 330, 6, 10, 6, 'okul-oncesi-6-aylik.png', 1, '2025-05-18 08:28:28', '2025-05-02 13:12:36'),
(23, '3 Aylık Lineup Pro Üyeliği', 11, 515, 3, 10, 3, 'okul-oncesi-3-aylik.png', 1, '2025-05-18 08:28:32', '2025-05-02 13:12:36'),
(24, 'Aylık Lineup Pro Üyeliği', 11, 719, 0, 0, 1, 'okul-oncesi-aylik.png', 1, '2025-05-18 08:28:34', '2025-05-02 13:12:36'),
(25, '12 Aylık Lineup Pro Üyeliği', 12, 210, 12, 10, 12, 'okul-oncesi-12-aylik.png', 1, '2025-05-18 08:28:25', '2025-05-02 13:12:36'),
(26, '6 Aylık Lineup Pro Üyeliği', 12, 330, 6, 10, 6, 'okul-oncesi-6-aylik.png', 1, '2025-05-18 08:28:28', '2025-05-02 13:12:36'),
(27, '3 Aylık Lineup Pro Üyeliği', 12, 515, 3, 10, 3, 'okul-oncesi-3-aylik.png', 1, '2025-05-18 08:28:32', '2025-05-02 13:12:36'),
(28, 'Aylık Lineup Pro Üyeliği', 12, 719, 0, 0, 1, 'okul-oncesi-aylik.png', 1, '2025-05-18 08:28:34', '2025-05-02 13:12:36'),
(34, 'test', 11, 15, NULL, NULL, 12, NULL, 0, '2025-05-23 07:32:22', '2025-05-20 11:33:40');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `package_payments_lnp`
--

CREATE TABLE `package_payments_lnp` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `pack_id` int(11) NOT NULL,
  `order_no` varchar(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci NOT NULL,
  `payment_type` int(11) NOT NULL,
  `payment_status` int(11) NOT NULL,
  `installment` int(11) DEFAULT NULL,
  `pay_amount` varchar(11) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci NOT NULL,
  `kdv_amount` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci NOT NULL,
  `kdv_percent` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci NOT NULL,
  `coupon` varchar(64) DEFAULT NULL,
  `commissionRate` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci DEFAULT NULL,
  `commissionFee` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci DEFAULT NULL,
  `ipAddress` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `package_payments_lnp`
--

INSERT INTO `package_payments_lnp` (`id`, `user_id`, `pack_id`, `order_no`, `payment_type`, `payment_status`, `installment`, `pay_amount`, `kdv_amount`, `kdv_percent`, `coupon`, `commissionRate`, `commissionFee`, `ipAddress`, `created_at`, `updated_at`) VALUES
(2, 91, 5, '763494438977830084', 2, 3, 12, '2367', '', '', NULL, '82.6083', '0.25', '::1', '2025-05-17 16:39:02', '2025-05-17 16:40:39'),
(10, 93, 5, '2147483647', 1, 2, NULL, '1782', '', '', NULL, NULL, NULL, '::1', '2024-04-17 20:37:02', '2025-05-21 10:38:44'),
(12, 102, 15, '13088227341932726882', 1, 2, NULL, '1417.5', '', '', NULL, NULL, NULL, '::1', '2025-04-18 10:30:30', '2025-05-21 10:36:37'),
(13, 104, 21, '21414187261791078297', 2, 3, 12, '2259', '', '', 'O4EPS21E', '78.8391', '0.25', '::1', '2025-04-18 11:40:07', '2025-05-21 10:36:34'),
(15, 100, 14, '6915166802077400060', 1, 2, NULL, '1836', '', '', 'O4EPS21E', NULL, NULL, '::1', '2024-05-18 11:46:14', '2025-05-21 10:38:41'),
(16, 110, 5, '243407189714666859', 1, 2, NULL, '2603.70', '289.3', '10', 'O4EPS21E', NULL, NULL, '::1', '2024-05-20 13:09:00', '2025-05-21 10:38:37'),
(18, 118, 21, '10098707031548595529', 2, 3, 12, '2761', '276.1', '10', 'O4EPS21E', '96.3589', '0.25', '::1', '2024-05-20 13:45:03', '2025-05-21 09:10:56'),
(19, 120, 9, '14060116801330205838', 2, 3, 12, '2893', '289.3', '10', 'O4EPS21E', '100.9657', '0.25', '::1', '2025-04-20 13:46:59', '2025-04-21 10:29:38'),
(20, 113, 13, '1610759359265263505', 1, 2, NULL, '2613.60', '290.4', '10', '', NULL, NULL, '::1', '2025-05-21 13:01:42', '2025-05-21 13:01:42');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `password_reset_lnp`
--

CREATE TABLE `password_reset_lnp` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `token` varchar(64) NOT NULL,
  `expires_at` datetime NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `password_reset_lnp`
--

INSERT INTO `password_reset_lnp` (`id`, `user_id`, `token`, `expires_at`, `created_at`) VALUES
(2, 32, '0474b17043d6dd2889c2c7cbe16425765400b987b2ccc0355131def87cedd2ea', '2025-05-16 12:13:19', '2025-05-16 08:13:19'),
(3, 102, 'f5dae1bc0ed4760322ba32a197d79327f7447b9166007a4e01c297543d49f5e5', '2025-05-18 15:15:01', '2025-05-18 11:15:01');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `payment_instalment_lnp`
--

CREATE TABLE `payment_instalment_lnp` (
  `id` int(11) NOT NULL,
  `name` varchar(11) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `payment_instalment_lnp`
--

INSERT INTO `payment_instalment_lnp` (`id`, `name`) VALUES
(1, 'Peşin'),
(2, 'Taksitle');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `payment_status_lnp`
--

CREATE TABLE `payment_status_lnp` (
  `id` int(11) NOT NULL,
  `name` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `payment_status_lnp`
--

INSERT INTO `payment_status_lnp` (`id`, `name`) VALUES
(1, 'Havale Bekleniyor'),
(2, 'Havale Alındı'),
(3, 'Kredi Kartı ile ödendi (Peşin)'),
(4, 'Kredi Kartı ile ödendi (Taksit ile)');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `payment_types_lnp`
--

CREATE TABLE `payment_types_lnp` (
  `id` int(11) NOT NULL,
  `name` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `payment_types_lnp`
--

INSERT INTO `payment_types_lnp` (`id`, `name`) VALUES
(1, 'Havale/EFT'),
(2, 'Kredi Kartı');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `que_answ_lnp`
--

CREATE TABLE `que_answ_lnp` (
  `id` int(11) NOT NULL,
  `question_type` int(1) NOT NULL,
  `question` text CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci NOT NULL,
  `asking` int(11) NOT NULL,
  `asked` int(11) NOT NULL,
  `slug` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `schools_lnp`
--

CREATE TABLE `schools_lnp` (
  `id` int(11) NOT NULL,
  `name` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci NOT NULL,
  `email` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci NOT NULL,
  `telephone` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci NOT NULL,
  `address` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci NOT NULL,
  `district` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci NOT NULL,
  `postcode` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci NOT NULL,
  `city` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci NOT NULL,
  `slug` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `active` int(1) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `schools_lnp`
--

INSERT INTO `schools_lnp` (`id`, `name`, `email`, `telephone`, `address`, `district`, `postcode`, `city`, `slug`, `active`, `created_at`, `updated_at`) VALUES
(1, 'Lineup Campus', 'lineup@lineupcampus.com', '0123456789', '', '', '', 'İzmir', 'lineup-campus', 1, '2025-02-18 14:19:28', '2025-02-20 15:29:19'),
(65, 'Deneme Deneme', 'deneme@deneme.com', '1234567', 'Deneme', 'Deneme', '06123', 'Ankara', 'deneme-deneme', 0, '2025-03-12 15:43:09', '2025-03-13 14:58:53'),
(66, 'Deneme Deneme', 'denemsse@deneme.com', '1234567', 'Deneme', 'Deneme', '06123', 'Ankara', 'deneme-deneme-1', 0, '2025-03-12 15:44:23', '2025-03-13 14:56:43'),
(67, 'Ankara Deneme Okulu2', 'bilgi@epikman.com', '15958585858', 'Ankara Caddesi No:8', 'Çankaya', '06450', 'Ankara', 'ankara-deneme-okulu2', 1, '2025-03-17 12:47:31', '2025-05-23 08:05:34'),
(70, 'Ankara Yeni Okul2', 'yeni@okul.com', '02315123123', 'Okul Caddesi', 'Çankaya', '06400', 'Ankara', 'ankara-yeni-okul2', 1, '2025-05-19 10:32:28', '2025-05-19 10:32:28'),
(71, 'Ankara Okulu', 'mnnjdf@gmasdlasp.com', '02141545486', 'ankara caddesi', 'Çankaya', '064000', 'Ankara', 'ankara-okulu', 1, '2025-05-19 10:35:01', '2025-05-19 10:35:01');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `settings_lnp`
--

CREATE TABLE `settings_lnp` (
  `id` int(11) NOT NULL,
  `tax_rate` decimal(4,2) NOT NULL,
  `discount_rate` decimal(4,2) NOT NULL,
  `notify_sms` tinyint(4) DEFAULT NULL,
  `notify_email` tinyint(4) DEFAULT NULL,
  `sms_template` text DEFAULT NULL,
  `notification_count` int(11) DEFAULT NULL,
  `notification_start_day` int(11) DEFAULT NULL,
  `school_id` int(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci;

--
-- Tablo döküm verisi `settings_lnp`
--

INSERT INTO `settings_lnp` (`id`, `tax_rate`, `discount_rate`, `notify_sms`, `notify_email`, `sms_template`, `notification_count`, `notification_start_day`, `school_id`, `created_at`, `updated_at`) VALUES
(1, 10.00, 10.00, 1, 1, '{name} {surname}  {subscribed_end}  tarihinde aboneliğiniz bitecektir.', 3, 7, 1, '2025-05-20 12:49:15', '2025-05-20 12:49:15');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `sms_settings_lnp`
--

CREATE TABLE `sms_settings_lnp` (
  `id` int(11) NOT NULL,
  `username` varchar(500) NOT NULL,
  `password` varchar(500) NOT NULL,
  `msgheader` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci;

--
-- Tablo döküm verisi `sms_settings_lnp`
--

INSERT INTO `sms_settings_lnp` (`id`, `username`, `password`, `msgheader`) VALUES
(2, '3129116589', 'Y3.3x499', 'FATIHAVCI');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `solvedtest_lnp`
--

CREATE TABLE `solvedtest_lnp` (
  `id` int(11) NOT NULL,
  `answers` longtext NOT NULL,
  `test_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `solvedtest_lnp`
--

INSERT INTO `solvedtest_lnp` (`id`, `answers`, `test_id`, `student_id`, `created_at`, `updated_at`) VALUES
(3, 'A:/;D', 2, 32, '2025-04-26 18:57:53', '2025-04-26 23:15:53'),
(5, 'C:/;B', 2, 25, '2025-04-27 21:15:17', '2025-04-27 21:15:17'),
(28, 'A:/;A:/;C', 3, 25, '2025-04-28 09:42:32', '2025-04-28 09:42:32');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `solved_s_questions_lnp`
--

CREATE TABLE `solved_s_questions_lnp` (
  `id` int(11) NOT NULL,
  `answers` longtext NOT NULL,
  `test_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `subtopics_lnp`
--

CREATE TABLE `subtopics_lnp` (
  `id` int(11) NOT NULL,
  `name` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci NOT NULL,
  `content` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci DEFAULT NULL,
  `short_desc` varchar(512) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci NOT NULL,
  `video_url` varchar(512) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `class_id` int(11) NOT NULL,
  `lesson_id` int(11) NOT NULL,
  `unit_id` int(11) NOT NULL,
  `topic_id` int(11) NOT NULL,
  `is_test` int(1) NOT NULL,
  `is_question` int(1) NOT NULL,
  `slug` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `image` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `subtopics_lnp`
--

INSERT INTO `subtopics_lnp` (`id`, `name`, `content`, `short_desc`, `video_url`, `class_id`, `lesson_id`, `unit_id`, `topic_id`, `is_test`, `is_question`, `slug`, `image`, `created_at`, `updated_at`) VALUES
(1, 'Deneme Alt Konu', '<p>dasdasda</p>', 'Deneme', 'https://storage.googleapis.com/denemevideo/DSC_1781.mp4', 3, 4, 19, 1, 0, 0, 'deneme-alt-konu', 'konuDefault.jpg', '2025-04-21 06:57:17', '2025-05-27 11:17:12'),
(3, 'Deneme Ekle Konu', '', 'Deneme', '', 3, 1, 1, 1, 1, 0, 'deneme-ekle-konu', 'konuDefault.jpg', '2025-04-26 17:47:12', '2025-04-26 17:47:12'),
(4, 'Başkentler', '', 'Başkent', '', 3, 1, 1, 1, 1, 0, 'baskentler', 'konuDefault.jpg', '2025-04-27 21:26:33', '2025-04-27 21:26:33'),
(5, '', '', '', '', 0, 0, 0, 0, 1, 0, 'n-a', 'konuDefault.jpg', '2025-04-28 10:26:20', '2025-04-28 10:26:20'),
(6, '', '', '', '', 0, 0, 0, 0, 1, 0, 'n-a-1', 'konuDefault.jpg', '2025-04-28 21:13:16', '2025-04-28 21:13:16'),
(7, '', '', '', '', 0, 0, 0, 0, 1, 0, 'n-a-2', 'konuDefault.jpg', '2025-04-28 21:13:40', '2025-04-28 21:13:40');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `support_center_device_info_lnp`
--

INSERT INTO `support_center_device_info_lnp` (`id`, `support_id`, `deviceType`, `deviceModel`, `deviceOs`, `browser`, `resolution`, `ipAddress`) VALUES
(2, 14, 'Desktop', 'Unknown Model', 'Windows', 'Chrome', '1920x1080', '::1');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `support_center_lnp`
--

CREATE TABLE `support_center_lnp` (
  `id` int(11) NOT NULL,
  `subject` int(3) NOT NULL,
  `title` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `comment` text CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci NOT NULL,
  `image` varchar(256) DEFAULT NULL,
  `slug` varchar(512) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci NOT NULL,
  `writer` int(11) NOT NULL,
  `openedBy` int(11) NOT NULL,
  `response` int(11) DEFAULT NULL,
  `completed` int(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `support_center_lnp`
--

INSERT INTO `support_center_lnp` (`id`, `subject`, `title`, `comment`, `image`, `slug`, `writer`, `openedBy`, `response`, `completed`, `created_at`, `updated_at`) VALUES
(1, 3, 'Destek Soru', 'cfghfjlasdjfklasdjfas', NULL, '3', 25, 25, NULL, 0, '2025-04-28 10:26:59', '2025-04-28 19:03:57'),
(2, 2, 'Mesela', 'Olabilir mi', '2-18266.png', '2', 25, 25, NULL, 0, '2025-04-28 10:27:35', '2025-04-28 19:03:55'),
(3, 1, 'Deneme Şikayet yazarlı', 'mfksnfklanjfklasdhjkfljasdl', NULL, 'deneme-sikayet-yazarli', 25, 25, NULL, 0, '2025-04-28 10:30:30', '2025-04-28 19:03:52'),
(4, 1, 'deneme Şikayeti', 'nfklsdjlfsjadfa', NULL, 'deneme-sikayeti', 32, 32, NULL, 1, '2025-04-28 19:11:54', '2025-04-28 21:33:36'),
(5, 1, 'deneme Şikayeti', 'nlsdajkfljlkfa', NULL, 'deneme-sikayeti', 1, 32, 4, 1, '2025-04-28 19:19:25', '2025-04-28 21:33:36'),
(7, 2, 'Dene Öneri', 'fnsdjfskl', NULL, 'dene-oneri', 32, 32, NULL, 1, '2025-04-28 19:24:05', '2025-04-28 22:12:17'),
(8, 1, 'deneme Şikayeti', 'mmkvmd', NULL, 'deneme-sikayeti', 32, 32, 4, 1, '2025-04-28 19:45:35', '2025-04-28 21:33:36'),
(10, 1, 'deneme Şikayeti', 'Destek Cevap', NULL, 'deneme-sikayeti', 32, 32, NULL, 1, '2025-04-28 21:22:22', '2025-04-28 21:33:36'),
(11, 2, 'Dene Öneri', 'Admin Cevap', NULL, 'dene-oneri', 1, 32, NULL, 1, '2025-04-28 22:10:20', '2025-04-28 22:12:17');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `support_center_subjects_lnp`
--

INSERT INTO `support_center_subjects_lnp` (`id`, `name`, `slug`, `created_at`, `updated_at`) VALUES
(1, 'Şikayet', 'sikayet', '2025-05-08 08:21:44', '2025-05-08 08:21:44'),
(2, 'Öneri', 'oneri', '2025-05-08 08:21:44', '2025-05-08 08:21:44'),
(3, 'Soru', 'soru', '2025-05-08 08:22:22', '2025-05-08 08:22:22'),
(4, 'Teknik Destek', 'teknik-destek', '2025-05-08 08:22:22', '2025-05-08 08:22:22');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `support_importance_lnp`
--

INSERT INTO `support_importance_lnp` (`id`, `support_id`, `importance_id`, `created_at`, `updated_at`) VALUES
(1, 14, 2, '2025-05-09 09:38:51', '2025-05-09 09:38:51'),
(2, 13, 1, '2025-05-09 09:39:14', '2025-05-09 09:39:14'),
(7, 1, 2, '2025-05-09 15:05:44', '2025-05-09 15:05:44'),
(8, 3, 1, '2025-05-09 16:46:45', '2025-05-09 16:46:45'),
(9, 2, 2, '2025-05-10 19:50:04', '2025-05-10 19:50:04');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `support_importance_titles_lnp`
--

CREATE TABLE `support_importance_titles_lnp` (
  `id` int(11) NOT NULL,
  `name` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `support_importance_titles_lnp`
--

INSERT INTO `support_importance_titles_lnp` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'Düşük', '2025-05-09 08:41:36', '2025-05-09 08:41:36'),
(2, 'Yüksek', '2025-05-09 08:41:36', '2025-05-09 08:41:36');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `suspicious_attempts_lnp`
--

INSERT INTO `suspicious_attempts_lnp` (`id`, `deviceType`, `deviceModel`, `deviceOs`, `browser`, `resolution`, `ipAddress`, `attempt_time`) VALUES
(1, 'Desktop', 'Unknown Model', 'Windows', 'Chrome', '1920x1080', '::1', '2025-05-08 19:01:04');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `s_questions_lnp`
--

CREATE TABLE `s_questions_lnp` (
  `id` int(11) NOT NULL,
  `questions` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci NOT NULL,
  `answers` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci NOT NULL,
  `correct` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci NOT NULL,
  `solutions` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci NOT NULL,
  `subtopic_id` int(11) NOT NULL,
  `slug` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `techmenuparent_lnp`
--

CREATE TABLE `techmenuparent_lnp` (
  `id` int(11) NOT NULL,
  `name` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci NOT NULL,
  `classes` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci NOT NULL,
  `slug` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci NOT NULL,
  `role` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci NOT NULL,
  `orderNo` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `techmenuparent_lnp`
--

INSERT INTO `techmenuparent_lnp` (`id`, `name`, `classes`, `slug`, `role`, `orderNo`, `created_at`, `updated_at`) VALUES
(1, 'Teknik Servis Ekibi', 'fa-solid fa-screwdriver-wrench', 'teknik-servis-ekibi', '1', 1, '2025-05-08 20:27:16', '2025-05-08 20:27:16'),
(2, 'Destek Talepleri', 'fa-regular fa-circle-question', 'destek-talepleri', '1,6,7', 2, '2025-05-08 20:27:16', '2025-05-10 19:27:40'),
(3, 'Sistem Bildirimleri', 'fa-solid fa-bullhorn', 'sistem-bildirimleri', '1,6,7', 3, '2025-05-08 20:27:16', '2025-05-10 19:27:43'),
(4, 'Erişim Kontrolleri', 'fa-regular fa-keyboard', 'erisim-kontrolleri', '1,6,7', 4, '2025-05-08 20:27:16', '2025-05-10 19:27:45');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `tests_lnp`
--

CREATE TABLE `tests_lnp` (
  `id` int(11) NOT NULL,
  `test_title` varchar(750) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci NOT NULL,
  `school_id` int(11) DEFAULT 1,
  `teacher_id` int(11) DEFAULT NULL,
  `cover_img` varchar(750) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci DEFAULT NULL,
  `class_id` int(11) DEFAULT NULL,
  `lesson_id` int(11) DEFAULT NULL,
  `unit_id` int(11) DEFAULT NULL,
  `topic_id` int(11) DEFAULT NULL,
  `subtopic_id` int(11) DEFAULT NULL,
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `tests_lnp`
--

INSERT INTO `tests_lnp` (`id`, `test_title`, `school_id`, `teacher_id`, `cover_img`, `class_id`, `lesson_id`, `unit_id`, `topic_id`, `subtopic_id`, `start_date`, `end_date`, `created_at`, `updated_at`) VALUES
(17, '131', 1, NULL, 'uploads/test/test_6835d03a56e477.10036509.jpg', 3, 4, 19, 1, 0, '2025-05-27 17:35:00', '2025-05-27 17:35:00', '2025-05-27 14:46:18', '2025-05-27 22:13:03'),
(18, 'test', 1, NULL, NULL, 6, 0, 0, 0, 0, '2025-05-27 17:48:00', '2025-05-27 17:48:00', '2025-05-27 14:49:27', '2025-05-27 14:49:27'),
(19, 'sfsf', 1, NULL, NULL, 4, 0, 0, 0, 0, '2025-05-27 17:50:00', '2025-05-01 17:50:00', '2025-05-27 14:51:23', '2025-05-27 14:51:23'),
(20, 'sfsf', 1, NULL, NULL, 4, 0, 0, 0, 0, '2025-05-27 17:50:00', '2025-05-01 17:50:00', '2025-05-27 14:52:11', '2025-05-27 14:52:11'),
(21, 'adad', 1, NULL, NULL, 4, 0, 0, 0, 0, '2025-05-27 17:52:00', '2025-05-27 17:52:00', '2025-05-27 14:52:22', '2025-05-27 14:52:22'),
(29, '131', 1, NULL, NULL, 4, 0, 0, 0, 0, '2025-05-28 01:02:00', '2025-05-28 01:02:00', '2025-05-27 22:02:48', '2025-05-27 22:02:48'),
(30, '131', 1, NULL, NULL, 4, 0, 0, 0, 0, '2025-05-28 01:02:00', '2025-05-28 01:02:00', '2025-05-27 22:02:54', '2025-05-27 22:02:54'),
(32, '131', 1, NULL, NULL, 4, 0, 0, 0, 0, '2025-05-28 01:02:00', '2025-05-28 01:02:00', '2025-05-27 22:04:32', '2025-05-27 22:04:32');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `test_class_option_count`
--

CREATE TABLE `test_class_option_count` (
  `id` int(11) NOT NULL,
  `school_id` int(11) NOT NULL DEFAULT 1,
  `class_id` int(11) NOT NULL,
  `option_count` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `test_class_option_count`
--

INSERT INTO `test_class_option_count` (`id`, `school_id`, `class_id`, `option_count`, `created_at`, `updated_at`) VALUES
(1, 1, 3, 3, '2025-05-26 10:30:58', '2025-05-26 10:30:58'),
(2, 1, 4, 3, '2025-05-26 10:30:58', '2025-05-26 10:30:58'),
(3, 1, 5, 3, '2025-05-26 10:31:16', '2025-05-26 10:31:16'),
(4, 1, 6, 4, '2025-05-26 10:31:16', '2025-05-26 10:31:16');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `test_custom_permission_lnp`
--

CREATE TABLE `test_custom_permission_lnp` (
  `id` int(11) NOT NULL,
  `test_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `test_custom_permission_lnp`
--

INSERT INTO `test_custom_permission_lnp` (`id`, `test_id`, `user_id`, `created_at`, `updated_at`) VALUES
(1, 1, 1, '2025-05-26 17:38:40', '2025-05-26 17:38:40');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `test_questions_lnp`
--

CREATE TABLE `test_questions_lnp` (
  `id` int(11) NOT NULL,
  `question_text` text CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci NOT NULL,
  `test_id` int(11) NOT NULL,
  `correct_answer` varchar(11) NOT NULL COMMENT 'test_question_options_lnp tablosuyla ilişkili',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `test_questions_lnp`
--

INSERT INTO `test_questions_lnp` (`id`, `question_text`, `test_id`, `correct_answer`, `created_at`, `updated_at`) VALUES
(9, '<p>1322131</p>', 17, 'A', '2025-05-27 14:46:18', '2025-05-27 14:46:18'),
(10, '<p><label class=\"form-label\" style=\"box-sizing: border-box; display: inline-block; margin-bottom: 0.5rem; font-size: 1.05rem; color: #252f4a;\">Soru Metni1</label></p>\r\n<p><label class=\"form-label\" style=\"box-sizing: border-box; display: inline-block; margin-bottom: 0.5rem; font-size: 1.05rem; color: #252f4a;\">&nbsp;</label></p>\r\n<div class=\"tox tox-tinymce\" style=\"box-sizing: border-box; scrollbar-width: thin; scrollbar-color: #f1f1f4 rgba(0, 0, 0, 0); border-radius: 0px; box-shadow: none; color: #222f3e; cursor: auto; font-family: -apple-system, BlinkMacSystemFont, \'Segoe UI\', Roboto, Oxygen-Sans, Ubuntu, Cantarell, \'Helvetica Neue\', sans-serif; font-size: 16px; line-height: normal; -webkit-tap-highlight-color: transparent; text-shadow: none; vertical-align: initial; border: 1px solid #cccccc; display: flex; flex-direction: column; overflow: hidden; position: relative; visibility: hidden; direction: ltr; height: 150px;\" role=\"application\" aria-disabled=\"false\">\r\n<div class=\"tox-editor-container\" style=\"box-sizing: inherit; scrollbar-width: thin; scrollbar-color: #f1f1f4 rgba(0, 0, 0, 0); color: inherit; cursor: inherit; direction: inherit; font-family: inherit; font-size: inherit; font-style: inherit; font-weight: inherit; line-height: inherit; -webkit-tap-highlight-color: inherit; text-align: inherit; text-decoration: inherit; text-shadow: inherit; text-transform: inherit; vertical-align: inherit; white-space: inherit; background-image: initial; background-position: 0px 0px; background-size: initial; background-repeat: initial; background-attachment: initial; background-origin: initial; background-clip: initial; border: 0px; box-shadow: none; float: none; height: auto; margin: 0px; max-width: none; outline: 0px; padding: 0px; position: static; width: auto; display: flex; flex: 1 1 auto; flex-direction: column; overflow: hidden;\">\r\n<div class=\"tox-editor-header\" style=\"box-sizing: inherit; scrollbar-width: thin; scrollbar-color: #f1f1f4 rgba(0, 0, 0, 0); cursor: inherit; direction: inherit; line-height: inherit; -webkit-tap-highlight-color: inherit; text-decoration-line: inherit; text-shadow: inherit; vertical-align: inherit; background: 0px 0px #ffffff; border: 0px; box-shadow: none; float: none; height: auto; margin: 0px; max-width: none; outline: 0px; padding: 0px; position: static; width: auto; z-index: 1; transition: box-shadow 0.5s;\" data-alloy-vertical-dir=\"toptobottom\">\r\n<div class=\"tox-toolbar-overlord\" style=\"box-sizing: inherit; scrollbar-width: thin; scrollbar-color: #f1f1f4 rgba(0, 0, 0, 0); color: inherit; cursor: inherit; direction: inherit; font-family: inherit; font-size: inherit; font-style: inherit; font-weight: inherit; line-height: inherit; -webkit-tap-highlight-color: inherit; text-align: inherit; text-decoration: inherit; text-shadow: inherit; text-transform: inherit; vertical-align: inherit; white-space: inherit; background-image: initial; background-position: 0px 0px; background-size: initial; background-repeat: initial; background-attachment: initial; background-origin: initial; background-clip: initial; border: 0px; box-shadow: none; float: none; height: auto; margin: 0px; max-width: none; outline: 0px; padding: 0px; position: static; width: auto;\" role=\"group\" aria-disabled=\"false\">\r\n<div class=\"tox-toolbar__primary\" style=\"box-sizing: inherit; scrollbar-width: thin; scrollbar-color: #f1f1f4 rgba(0, 0, 0, 0); color: inherit; cursor: inherit; direction: inherit; font-family: inherit; font-size: inherit; font-style: inherit; font-weight: inherit; line-height: inherit; -webkit-tap-highlight-color: inherit; text-align: inherit; text-decoration: inherit; text-shadow: inherit; text-transform: inherit; vertical-align: inherit; white-space: inherit; background-position: left 0px top 0px; background-size: initial; background-repeat: initial; background-attachment: initial; background-origin: initial; background-clip: initial; border: 0px; box-shadow: none; float: none; height: auto; margin: 0px; max-width: none; outline: 0px; padding: 0px; position: static; width: auto; display: flex; flex: 0 0 auto; flex-wrap: wrap;\" role=\"group\">\r\n<div class=\"tox-toolbar__group\" style=\"box-sizing: inherit; scrollbar-width: thin; scrollbar-color: #f1f1f4 rgba(0, 0, 0, 0); color: inherit; cursor: inherit; direction: inherit; font-family: inherit; font-size: inherit; font-style: inherit; font-weight: inherit; line-height: inherit; -webkit-tap-highlight-color: inherit; text-align: inherit; text-decoration: inherit; text-shadow: inherit; text-transform: inherit; vertical-align: inherit; white-space: inherit; background: 0px 0px; border-width: 0px 1px 0px 0px; border-image: initial; box-shadow: none; float: none; height: auto; margin: 0px; max-width: none; outline: 0px; padding: 0px 4px; position: static; width: auto; align-items: center; display: flex; flex-wrap: wrap; border-color: initial #cccccc initial initial; border-style: initial solid initial initial;\" tabindex=\"-1\" title=\"\" role=\"toolbar\" data-alloy-tabstop=\"true\">&nbsp;</div>\r\n<div class=\"tox-toolbar__group\" style=\"box-sizing: inherit; scrollbar-width: thin; scrollbar-color: #f1f1f4 rgba(0, 0, 0, 0); color: inherit; cursor: inherit; direction: inherit; font-family: inherit; font-size: inherit; font-style: inherit; font-weight: inherit; line-height: inherit; -webkit-tap-highlight-color: inherit; text-align: inherit; text-decoration: inherit; text-shadow: inherit; text-transform: inherit; vertical-align: inherit; white-space: inherit; background: 0px 0px; border-width: 0px 1px 0px 0px; border-image: initial; box-shadow: none; float: none; height: auto; margin: 0px; max-width: none; outline: 0px; padding: 0px 4px; position: static; width: auto; align-items: center; display: flex; flex-wrap: wrap; border-color: initial #cccccc initial initial; border-style: initial solid initial initial;\" tabindex=\"-1\" title=\"\" role=\"toolbar\" data-alloy-tabstop=\"true\">&nbsp;</div>\r\n<div class=\"tox-toolbar__group\" style=\"box-sizing: inherit; scrollbar-width: thin; scrollbar-color: #f1f1f4 rgba(0, 0, 0, 0); color: inherit; cursor: inherit; direction: inherit; font-family: inherit; font-size: inherit; font-style: inherit; font-weight: inherit; line-height: inherit; -webkit-tap-highlight-color: inherit; text-align: inherit; text-decoration: inherit; text-shadow: inherit; text-transform: inherit; vertical-align: inherit; white-space: inherit; background: 0px 0px; border-width: 0px 1px 0px 0px; border-image: initial; box-shadow: none; float: none; height: auto; margin: 0px; max-width: none; outline: 0px; padding: 0px 4px; position: static; width: auto; align-items: center; display: flex; flex-wrap: wrap; border-color: initial #cccccc initial initial; border-style: initial solid initial initial;\" tabindex=\"-1\" title=\"\" role=\"toolbar\" data-alloy-tabstop=\"true\">&nbsp;</div>\r\n<div class=\"tox-toolbar__group\" style=\"box-sizing: inherit; scrollbar-width: thin; scrollbar-color: #f1f1f4 rgba(0, 0, 0, 0); color: inherit; cursor: inherit; direction: inherit; font-family: inherit; font-size: inherit; font-style: inherit; font-weight: inherit; line-height: inherit; -webkit-tap-highlight-color: inherit; text-align: inherit; text-decoration: inherit; text-shadow: inherit; text-transform: inherit; vertical-align: inherit; white-space: inherit; background: 0px 0px; border-width: 0px 1px 0px 0px; border-image: initial; box-shadow: none; float: none; height: auto; margin: 0px; max-width: none; outline: 0px; padding: 0px 4px; position: static; width: auto; align-items: center; display: flex; flex-wrap: wrap; border-color: initial #cccccc initial initial; border-style: initial solid initial initial;\" tabindex=\"-1\" title=\"\" role=\"toolbar\" data-alloy-tabstop=\"true\">&nbsp;</div>\r\n<div class=\"tox-toolbar__group\" style=\"box-sizing: inherit; scrollbar-width: thin; scrollbar-color: #f1f1f4 rgba(0, 0, 0, 0); color: inherit; cursor: inherit; direction: inherit; font-family: inherit; font-size: inherit; font-style: inherit; font-weight: inherit; line-height: inherit; -webkit-tap-highlight-color: inherit; text-align: inherit; text-decoration: inherit; text-shadow: inherit; text-transform: inherit; vertical-align: inherit; white-space: inherit; background: 0px 0px; border: 0px; box-shadow: none; float: none; height: auto; margin: 0px; max-width: none; outline: 0px; padding: 0px 4px; position: static; width: auto; align-items: center; display: flex; flex-wrap: wrap;\" tabindex=\"-1\" title=\"\" role=\"toolbar\" data-alloy-tabstop=\"true\">&nbsp;</div>\r\n</div>\r\n</div>\r\n<div class=\"tox-anchorbar\" style=\"box-sizing: inherit; scrollbar-width: thin; scrollbar-color: #f1f1f4 rgba(0, 0, 0, 0); color: inherit; cursor: inherit; direction: inherit; font-family: inherit; font-size: inherit; font-style: inherit; font-weight: inherit; line-height: inherit; -webkit-tap-highlight-color: inherit; text-align: inherit; text-decoration: inherit; text-shadow: inherit; text-transform: inherit; vertical-align: inherit; white-space: inherit; background: 0px 0px; border: 0px; box-shadow: none; float: none; height: auto; margin: 0px; max-width: none; outline: 0px; padding: 0px; position: static; width: auto; display: flex; flex: 0 0 auto;\">&nbsp;</div>\r\n</div>\r\n<div class=\"tox-sidebar-wrap\" style=\"box-sizing: inherit; scrollbar-width: thin; scrollbar-color: #f1f1f4 rgba(0, 0, 0, 0); cursor: inherit; direction: inherit; line-height: inherit; -webkit-tap-highlight-color: inherit; text-decoration-line: inherit; text-shadow: inherit; vertical-align: inherit; background: 0px 0px #ffffff; border: 0px; box-shadow: none; float: none; height: auto; margin: 0px; max-width: none; outline: 0px; padding: 0px; position: static; width: auto; display: flex; flex-direction: row; flex-grow: 1; min-height: 0px;\">&nbsp;</div>\r\n</div>\r\n</div>', 18, 'B', '2025-05-27 14:49:27', '2025-05-27 14:49:27'),
(11, '<p>ssfs</p>', 19, 'A', '2025-05-27 14:51:23', '2025-05-27 14:51:23'),
(12, '<p>ssfs</p>', 20, 'A', '2025-05-27 14:52:11', '2025-05-27 14:52:11'),
(20, '<p>adasda</p>', 32, 'B', '2025-05-27 22:04:32', '2025-05-27 22:04:32');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `test_question_files_lnp`
--

CREATE TABLE `test_question_files_lnp` (
  `id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL,
  `file_path` varchar(750) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `test_question_files_lnp`
--

INSERT INTO `test_question_files_lnp` (`id`, `question_id`, `file_path`, `created_at`, `updated_at`) VALUES
(4, 9, 'uploads/questions/img_6835d03a57f173.19083393.jpg', '2025-05-27 14:46:18', '2025-05-27 14:46:18'),
(5, 10, 'uploads/questions/img_6835d0f7994f58.92987319.png', '2025-05-27 14:49:27', '2025-05-27 14:49:27'),
(6, 11, 'uploads/questions/img_6835d16b449d03.28560354.png', '2025-05-27 14:51:23', '2025-05-27 14:51:23'),
(7, 12, 'uploads/questions/img_6835d19bcd5b04.44730514.png', '2025-05-27 14:52:11', '2025-05-27 14:52:11');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `test_question_options_lnp`
--

CREATE TABLE `test_question_options_lnp` (
  `id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL,
  `option_key` text CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci DEFAULT NULL,
  `option_text` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `test_question_options_lnp`
--

INSERT INTO `test_question_options_lnp` (`id`, `question_id`, `option_key`, `option_text`, `created_at`, `updated_at`) VALUES
(1, 9, 'A', '<p>ada</p>', '2025-05-27 14:46:18', '2025-05-27 14:46:18'),
(2, 9, 'B', '<p>dada</p>', '2025-05-27 14:46:18', '2025-05-27 14:46:18'),
(3, 9, 'C', '<p>dada</p>', '2025-05-27 14:46:18', '2025-05-27 14:46:18'),
(4, 10, 'A', '<p>aadsa</p>', '2025-05-27 14:49:27', '2025-05-27 14:49:27'),
(5, 10, 'B', '<p>adada</p>', '2025-05-27 14:49:27', '2025-05-27 14:49:27'),
(6, 10, 'C', '<p>adada</p>', '2025-05-27 14:49:27', '2025-05-27 14:49:27'),
(7, 10, 'D', '<p>dadadada</p>', '2025-05-27 14:49:27', '2025-05-27 14:49:27'),
(8, 11, 'A', '<p>fsfs</p>', '2025-05-27 14:51:23', '2025-05-27 14:51:23'),
(9, 11, 'B', '<p>fsfs</p>', '2025-05-27 14:51:23', '2025-05-27 14:51:23'),
(10, 11, 'C', '<p>fsfdds</p>', '2025-05-27 14:51:23', '2025-05-27 14:51:23'),
(11, 12, 'A', '<p>fsfs</p>', '2025-05-27 14:52:11', '2025-05-27 14:52:11'),
(12, 12, 'B', '<p>fsfs</p>', '2025-05-27 14:52:11', '2025-05-27 14:52:11'),
(13, 12, 'C', '<p>fsfdds</p>', '2025-05-27 14:52:11', '2025-05-27 14:52:11'),
(27, 20, 'A', '<p>a</p>', '2025-05-27 22:04:32', '2025-05-27 22:04:32'),
(28, 20, 'B', '<p>a</p>', '2025-05-27 22:04:32', '2025-05-27 22:04:32'),
(29, 20, 'C', '<p>a</p>', '2025-05-27 22:04:32', '2025-05-27 22:04:32');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `test_question_option_files_lnp`
--

CREATE TABLE `test_question_option_files_lnp` (
  `id` int(11) NOT NULL,
  `option_id` int(11) NOT NULL,
  `file_path` varchar(750) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `test_question_videos_lnp`
--

CREATE TABLE `test_question_videos_lnp` (
  `id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL,
  `video_url` varchar(750) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `test_question_videos_lnp`
--

INSERT INTO `test_question_videos_lnp` (`id`, `question_id`, `video_url`, `created_at`, `updated_at`) VALUES
(9, 9, '123131', '2025-05-27 14:46:18', '2025-05-27 14:46:18'),
(10, 10, '123131', '2025-05-27 14:49:27', '2025-05-27 14:49:27'),
(11, 11, 'sdfs', '2025-05-27 14:51:23', '2025-05-27 14:51:23'),
(12, 12, 'sdfs', '2025-05-27 14:52:11', '2025-05-27 14:52:11'),
(14, 20, '123131', '2025-05-27 22:04:32', '2025-05-27 22:04:32');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `test_success_rate_lnp`
--

CREATE TABLE `test_success_rate_lnp` (
  `id` int(11) NOT NULL,
  `class_id` int(11) DEFAULT NULL,
  `rate` decimal(4,2) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `test_user_answers_lnp`
--

CREATE TABLE `test_user_answers_lnp` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `question_option_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `timespend_lnp`
--

CREATE TABLE `timespend_lnp` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `sayfa_url` varchar(128) NOT NULL,
  `timeSpent` int(11) NOT NULL,
  `exitTime` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `timespend_lnp`
--

INSERT INTO `timespend_lnp` (`id`, `user_id`, `sayfa_url`, `timeSpent`, `exitTime`) VALUES
(1, 32, 'http://localhost/lineup_campus/ders/turkce', 118, '2025-04-10 20:01:08'),
(2, 32, 'http://localhost/lineup_campus/unite/a-harfi', 7, '2025-04-10 20:01:16'),
(3, 32, 'http://localhost/lineup_campus/unite/a-harfi', 3996, '2025-04-10 21:08:01'),
(4, 32, 'http://localhost/lineup_campus/konu/konu-anlatimi', 12, '2025-04-11 10:51:20'),
(5, 1, 'http://localhost/lineup_campus/dashboard', 5, '2025-04-11 10:57:11'),
(6, 1, 'http://localhost/lineup_campus/ogretmenler', 652, '2025-04-11 11:08:04'),
(7, 32, 'http://localhost/lineup_campus/ders/matematik', 1, '2025-04-11 16:07:22'),
(8, 32, 'http://localhost/lineup_campus/ders/turkce', 1, '2025-04-11 16:07:24'),
(9, 32, 'http://localhost/lineup_campus/unite/a-harfi', 3, '2025-04-11 16:07:27'),
(10, 32, 'http://localhost/lineup_campus/konu/konu-anlatimi-1', 8, '2025-04-11 16:07:37'),
(11, 32, 'http://localhost/lineup_campus/unite/a-harfi', 14, '2025-04-11 16:07:51'),
(12, 32, 'http://localhost/lineup_campus/ders/matematik', 1, '2025-04-11 16:07:52'),
(13, 32, 'http://localhost/lineup_campus/ders/fen-bilimleri', 0, '2025-04-11 16:07:53'),
(14, 32, 'http://localhost/lineup_campus/ders/sosyal-bilgiler', 0, '2025-04-11 16:07:54'),
(15, 32, 'http://localhost/lineup_campus/ders/ingilizce', 0, '2025-04-11 16:07:55'),
(16, 32, 'http://localhost/lineup_campus/ders/din-kulturu-ve-ahlak-bilgisi', 0, '2025-04-11 16:07:56'),
(17, 32, 'http://localhost/lineup_campus/ders/insan-haklari-yurttaslik', 1, '2025-04-11 16:07:57'),
(18, 32, 'http://localhost/lineup_campus/ders/turkce', 1, '2025-04-11 16:07:59'),
(19, 32, 'http://localhost/lineup_campus/unite/b-harfi', 0, '2025-04-11 16:08:00'),
(20, 32, 'http://localhost/lineup_campus/ders/turkce', 0, '2025-04-11 16:08:01'),
(21, 32, 'http://localhost/lineup_campus/unite/c-harfi', 0, '2025-04-11 16:08:02'),
(22, 32, 'http://localhost/lineup_campus/ders/turkce', 0, '2025-04-11 16:08:03'),
(23, 32, 'http://localhost/lineup_campus/unite/d-harfi', 4, '2025-04-11 16:08:08'),
(24, 32, 'http://localhost/lineup_campus/unite/a-harfi', 40, '2025-04-11 16:08:48'),
(25, 32, 'http://localhost/lineup_campus/ders/turkce', 41, '2025-04-11 16:09:30'),
(26, 32, 'http://localhost/lineup_campus/unite/a-harfi', 1, '2025-04-11 16:09:32'),
(27, 32, 'http://localhost/lineup_campus/konu/konu-anlatimi', 7, '2025-04-11 16:09:40'),
(28, 32, 'http://localhost/lineup_campus/konu/konu-anlatimi', 140, '2025-04-11 16:12:01'),
(29, 32, 'http://localhost/lineup_campus/konu/konu-anlatimi', 14, '2025-04-11 16:12:16'),
(30, 32, 'http://localhost/lineup_campus/konu/konu-anlatimi', 20, '2025-04-11 16:12:37'),
(31, 32, 'http://localhost/lineup_campus/konu/konu-anlatimi', 14, '2025-04-11 16:12:51'),
(32, 32, 'http://localhost/lineup_campus/dashboard', 5, '2025-04-11 16:14:31'),
(33, 32, 'http://localhost/lineup_campus/ders/turkce', 1, '2025-04-11 16:14:32'),
(34, 32, 'http://localhost/lineup_campus/unite/a-harfi', 1, '2025-04-11 16:14:34'),
(35, 32, 'http://localhost/lineup_campus/konu/konu-anlatimi', 32, '2025-04-11 16:15:06'),
(36, 32, 'http://localhost/lineup_campus/konu/konu-anlatimi', 1243, '2025-04-11 16:33:35'),
(37, 32, 'http://localhost/lineup_campus/konu/konu-anlatimi', 81565, '2025-04-12 14:54:33'),
(38, 1, 'http://localhost/lineup_campus/dashboard', 3, '2025-04-14 09:29:39'),
(39, 1, 'http://localhost/lineup_campus/siniflar', 1, '2025-04-14 09:29:41'),
(40, 1, 'http://localhost/lineup_campus/dersler', 9, '2025-04-14 09:29:50'),
(41, 1, 'http://localhost/lineup_campus/okul-detay/matematik', 7, '2025-04-14 09:29:58'),
(42, 1, 'http://localhost/lineup_campus/dersler', 4, '2025-04-14 09:30:03'),
(43, 1, 'http://localhost/lineup_campus/siniflar', 1, '2025-04-14 09:30:04'),
(44, 1, 'http://localhost/lineup_campus/okul-detay/anasinifi', 4, '2025-04-14 09:30:08'),
(45, 1, 'http://localhost/lineup_campus/siniflar', 2, '2025-04-14 09:30:11'),
(46, 1, 'http://localhost/lineup_campus/dersler', 15, '2025-04-14 09:30:27'),
(47, 1, 'http://localhost/lineup_campus/uniteler', 7, '2025-04-14 09:30:35'),
(48, 1, 'http://localhost/lineup_campus/dersler', 151, '2025-04-14 09:33:06'),
(49, 1, 'http://localhost/lineup_campus/dersler', 3, '2025-04-14 09:33:10'),
(50, 1, 'http://localhost/lineup_campus/dersler', 63, '2025-04-14 09:34:14'),
(51, 1, 'http://localhost/lineup_campus/dersler', 122, '2025-04-14 09:36:17'),
(52, 1, 'http://localhost/lineup_campus/dersler', 43, '2025-04-14 09:37:32'),
(53, 1, 'http://localhost/lineup_campus/dersler', 15, '2025-04-14 09:37:57'),
(54, 1, 'http://localhost/lineup_campus/dersler', 863, '2025-04-14 09:52:20'),
(55, 1, 'http://localhost/lineup_campus/dersler', 132, '2025-04-14 09:54:33'),
(56, 1, 'http://localhost/lineup_campus/dersler', 67, '2025-04-14 09:55:40'),
(57, 1, 'http://localhost/lineup_campus/dersler', 83, '2025-04-14 09:57:04'),
(58, 1, 'http://localhost/lineup_campus/dersler', 1212, '2025-04-14 10:17:17'),
(59, 1, 'http://localhost/lineup_campus/dersler', 246, '2025-04-14 10:21:24'),
(60, 1, 'http://localhost/lineup_campus/dersler', 20, '2025-04-14 10:21:44'),
(61, 1, 'http://localhost/lineup_campus/dersler', 103, '2025-04-14 10:23:27'),
(62, 1, 'http://localhost/lineup_campus/dersler', 14, '2025-04-14 10:23:43'),
(63, 1, 'http://localhost/lineup_campus/dersler', 2702, '2025-04-14 11:08:46'),
(64, 1, 'http://localhost/lineup_campus/dersler', 16, '2025-04-14 11:09:52'),
(65, 1, 'http://localhost/lineup_campus/dersler', 65, '2025-04-14 11:10:58'),
(66, 1, 'http://localhost/lineup_campus/dersler', 7, '2025-04-14 11:11:05'),
(67, 1, 'http://localhost/lineup_campus/dersler', 311, '2025-04-14 11:16:19'),
(68, 1, 'http://localhost/lineup_campus/dersler', 21, '2025-04-14 11:16:41'),
(69, 1, 'http://localhost/lineup_campus/dersler', 170, '2025-04-14 11:19:32'),
(70, 1, 'http://localhost/lineup_campus/konular', 24, '2025-04-14 11:20:28'),
(71, 1, 'http://localhost/lineup_campus/uniteler', 6643, '2025-04-14 11:25:41'),
(72, 1, 'http://localhost/lineup_campus/konu-ekle', 1046, '2025-04-14 11:37:55'),
(73, 1, 'http://localhost/lineup_campus/uniteler', 7538, '2025-04-14 13:31:20'),
(74, 1, 'http://localhost/lineup_campus/uniteler', 45, '2025-04-14 13:32:07'),
(75, 1, 'http://localhost/lineup_campus/uniteler', 42, '2025-04-14 13:32:49'),
(76, 1, 'http://localhost/lineup_campus/uniteler', 199, '2025-04-14 13:36:09'),
(77, 1, 'http://localhost/lineup_campus/uniteler', 45, '2025-04-14 13:36:56'),
(78, 1, 'http://localhost/lineup_campus/uniteler', 1124, '2025-04-14 13:55:40'),
(79, 1, 'http://localhost/lineup_campus/okullar', 104, '2025-04-14 13:57:25'),
(80, 1, 'http://localhost/lineup_campus/dashboard', 14, '2025-04-14 14:05:08'),
(81, 1, 'http://localhost/lineup_campus/okullar', 125, '2025-04-14 14:07:14'),
(82, 1, 'http://localhost/lineup_campus/dashboard', 2, '2025-04-14 14:07:17'),
(83, 1, 'http://localhost/lineup_campus/okullar', 27, '2025-04-14 14:07:44'),
(84, 1, 'http://localhost/lineup_campus/ogretmenler', 6, '2025-04-14 14:07:51'),
(85, 1, 'http://localhost/lineup_campus/okullar', 9, '2025-04-14 14:08:01'),
(86, 1, 'http://localhost/lineup_campus/ogrenciler', 4, '2025-04-14 14:08:06'),
(87, 1, 'http://localhost/lineup_campus/okullar', 1, '2025-04-14 14:08:07'),
(88, 1, 'http://localhost/lineup_campus/dashboard', 2, '2025-04-14 14:08:10'),
(89, 1, 'http://localhost/lineup_campus/okullar', 1, '2025-04-14 14:08:11'),
(90, 1, 'http://localhost/lineup_campus/okul-detay/lineup-campus', 99, '2025-04-14 14:09:51'),
(91, 1, 'http://localhost/lineup_campus/dashboard', 2, '2025-04-14 14:09:54'),
(92, 1, 'http://localhost/lineup_campus/okul-detay/lineup-campus', 13, '2025-04-14 14:10:07'),
(93, 1, 'http://localhost/lineup_campus/ogrenciler', 20, '2025-04-14 14:10:29'),
(94, 1, 'http://localhost/lineup_campus/okullar', 3, '2025-04-14 14:10:32'),
(95, 1, 'http://localhost/lineup_campus/okul-detay/lineup-campus', 114, '2025-04-14 14:12:27'),
(96, 1, 'http://localhost/lineup_campus/dashboard', 1, '2025-04-14 14:12:28'),
(97, 1, 'http://localhost/lineup_campus/okul-detay/lineup-campus', 8, '2025-04-14 14:12:37'),
(98, 1, 'http://localhost/lineup_campus/dashboard', 1, '2025-04-14 14:12:38'),
(99, 1, 'http://localhost/lineup_campus/ogrenciler', 2, '2025-04-14 14:12:44'),
(100, 1, 'http://localhost/lineup_campus/ogrenciler', 8, '2025-04-14 14:12:50'),
(101, 1, 'http://localhost/lineup_campus/ogretmenler', 1, '2025-04-14 14:12:51'),
(102, 1, 'http://localhost/lineup_campus/okullar', 1, '2025-04-14 14:12:53'),
(103, 1, 'http://localhost/lineup_campus/okul-detay/lineup-campus', 63, '2025-04-14 14:13:42'),
(104, 1, 'http://localhost/lineup_campus/ogretmenler', 7, '2025-04-14 14:13:50'),
(105, 1, 'http://localhost/lineup_campus/ogretmenler', 96, '2025-04-14 14:15:28'),
(106, 1, 'http://localhost/lineup_campus/uniteler', 10713, '2025-04-14 14:18:06'),
(107, 1, 'http://localhost/lineup_campus/ogrenciler', 180, '2025-04-14 14:18:29'),
(108, 1, 'http://localhost/lineup_campus/veliler', 10, '2025-04-14 14:18:40'),
(109, 1, 'http://localhost/lineup_campus/ogrenciler', 5, '2025-04-14 14:18:45'),
(110, 1, 'http://localhost/lineup_campus/siniflar', 99, '2025-04-14 14:19:46'),
(111, 1, 'http://localhost/lineup_campus/ogrenciler', 20, '2025-04-14 14:20:07'),
(112, 1, 'http://localhost/lineup_campus/veliler', 145, '2025-04-14 14:21:11'),
(113, 1, 'http://localhost/lineup_campus/siniflar', 18, '2025-04-14 14:21:30'),
(114, 1, 'http://localhost/lineup_campus/dersler', 91, '2025-04-14 14:23:03'),
(115, 1, 'http://localhost/lineup_campus/uniteler', 23, '2025-04-14 14:23:26'),
(116, 1, 'http://localhost/lineup_campus/konular', 13, '2025-04-14 14:23:40'),
(117, 1, 'http://localhost/lineup_campus/konu-ekle', 118, '2025-04-14 14:25:40'),
(118, 1, 'http://localhost/lineup_campus/siniflar', 3, '2025-04-14 14:25:43'),
(119, 1, 'http://localhost/lineup_campus/dersler', 11, '2025-04-14 14:25:56'),
(120, 1, 'http://localhost/lineup_campus/uniteler', 6, '2025-04-14 14:26:02'),
(121, 1, 'http://localhost/lineup_campus/konular', 2, '2025-04-14 14:26:05'),
(122, 1, 'http://localhost/lineup_campus/haftalik-gorev', 523, '2025-04-14 14:34:49'),
(123, 1, 'http://localhost/lineup_campus/okul-detay/deneme-2', 12, '2025-04-14 14:35:02'),
(124, 1, 'http://localhost/lineup_campus/okul-detay/deneme-2', 19, '2025-04-14 14:35:09'),
(125, 1, 'http://localhost/lineup_campus/sesli-kitaplar', 83, '2025-04-14 14:36:33'),
(126, 1, 'http://localhost/lineup_campus/haftalik-gorev', 50, '2025-04-14 14:37:24'),
(127, 1, 'http://localhost/lineup_campus/sesli-kitaplar', 93, '2025-04-14 14:38:58'),
(128, 1, 'http://localhost/lineup_campus/oyunlar', 840, '2025-04-14 14:52:59'),
(129, 1, 'http://localhost/lineup_campus/konular', 1, '2025-04-14 14:53:00'),
(130, 1, 'http://localhost/lineup_campus/konu-ekle', 12256, '2025-04-14 15:02:11'),
(131, 32, 'http://localhost/lineup_campus/dashboard', 2, '2025-04-14 15:02:21'),
(132, 32, 'http://localhost/lineup_campus/ders/turkce', 4, '2025-04-14 15:02:26'),
(133, 32, 'http://localhost/lineup_campus/unite/a-harfi', 3, '2025-04-14 15:02:30'),
(134, 32, 'http://localhost/lineup_campus/konu/konu-anlatimi', 25, '2025-04-14 15:02:56'),
(135, 32, 'http://localhost/lineup_campus/unite/a-harfi', 18, '2025-04-14 15:03:14'),
(136, 32, 'http://localhost/lineup_campus/unite/a-harfi', 0, '2025-04-14 15:03:15'),
(137, 32, 'http://localhost/lineup_campus/dashboard', 132, '2025-04-16 09:03:50'),
(138, 32, 'http://localhost/lineup_campus/dashboard', 6, '2025-04-16 09:04:05'),
(139, 32, 'http://localhost/lineup_campus/dashboard', 16, '2025-04-16 09:04:40'),
(140, 1, 'http://localhost/lineup_campus/dashboard', 9, '2025-04-16 09:05:05'),
(141, 1, 'http://localhost/lineup_campus/uniteler', 5, '2025-04-16 09:05:11'),
(142, 1, 'http://localhost/lineup_campus/konular', 544, '2025-04-16 09:14:16'),
(143, 1, 'http://localhost/lineup_campus/siniflar', 2, '2025-04-16 09:14:19'),
(144, 1, 'http://localhost/lineup_campus/dersler', 573, '2025-04-16 09:23:53'),
(145, 1, 'http://localhost/lineup_campus/dersler', 18, '2025-04-16 09:24:12'),
(146, 1, 'http://localhost/lineup_campus/dersler', 18, '2025-04-16 09:24:31'),
(147, 1, 'http://localhost/lineup_campus/dersler', 15, '2025-04-16 09:24:46'),
(148, 1, 'http://localhost/lineup_campus/dersler', 32, '2025-04-16 09:25:19'),
(149, 1, 'http://localhost/lineup_campus/dersler', 24, '2025-04-16 09:25:44'),
(150, 1, 'http://localhost/lineup_campus/dersler', 72, '2025-04-16 09:26:58'),
(151, 1, 'http://localhost/lineup_campus/dersler', 70, '2025-04-16 09:28:09'),
(152, 1, 'http://localhost/lineup_campus/uniteler', 184, '2025-04-16 09:31:14'),
(153, 1, 'http://localhost/lineup_campus/uniteler', 60, '2025-04-16 09:32:14'),
(154, 1, 'http://localhost/lineup_campus/uniteler', 16, '2025-04-16 09:32:31'),
(155, 1, 'http://localhost/lineup_campus/uniteler', 2180, '2025-04-16 10:08:52'),
(156, 1, 'http://localhost/lineup_campus/uniteler', 40, '2025-04-16 10:09:33'),
(157, 1, 'http://localhost/lineup_campus/uniteler', 14, '2025-04-16 10:09:48'),
(158, 1, 'http://localhost/lineup_campus/uniteler', 29, '2025-04-16 10:10:18'),
(159, 1, 'http://localhost/lineup_campus/uniteler', 21, '2025-04-16 10:10:39'),
(160, 1, 'http://localhost/lineup_campus/uniteler', 11, '2025-04-16 10:10:51'),
(161, 1, 'http://localhost/lineup_campus/uniteler', 212, '2025-04-16 10:14:24'),
(162, 1, 'http://localhost/lineup_campus/uniteler', 237, '2025-04-16 10:18:22'),
(163, 1, 'http://localhost/lineup_campus/uniteler', 290, '2025-04-16 10:23:12'),
(164, 1, 'http://localhost/lineup_campus/uniteler', 84, '2025-04-16 10:24:37'),
(165, 1, 'http://localhost/lineup_campus/konular', 3, '2025-04-16 10:24:41'),
(166, 1, 'http://localhost/lineup_campus/konu-ekle', 4047, '2025-04-16 11:32:09'),
(167, 1, 'http://localhost/lineup_campus/konular', 5, '2025-04-16 11:32:16'),
(168, 1, 'http://localhost/lineup_campus/konu-ekle', 0, '2025-04-16 11:32:16'),
(169, 1, 'http://localhost/lineup_campus/konular', 0, '2025-04-16 11:32:18'),
(170, 1, 'http://localhost/lineup_campus/haftalik-gorev', 1, '2025-04-16 11:37:00'),
(171, 1, 'http://localhost/lineup_campus/konular', 2, '2025-04-16 11:37:03'),
(172, 1, 'http://localhost/lineup_campus/konu-ekle', 79, '2025-04-16 11:38:22'),
(173, 1, 'http://localhost/lineup_campus/konu-ekle', 39, '2025-04-16 11:39:02'),
(174, 1, 'http://localhost/lineup_campus/konu-ekle', 13, '2025-04-16 11:39:16'),
(175, 1, 'http://localhost/lineup_campus/konu-ekle', 56, '2025-04-16 11:40:13'),
(176, 1, 'http://localhost/lineup_campus/konu-ekle', 107, '2025-04-16 11:42:01'),
(177, 1, 'http://localhost/lineup_campus/konu-ekle', 129, '2025-04-16 11:44:11'),
(178, 1, 'http://localhost/lineup_campus/konu-ekle', 36, '2025-04-16 11:44:48'),
(179, 1, 'http://localhost/lineup_campus/konu-ekle', 136, '2025-04-16 11:47:05'),
(180, 1, 'http://localhost/lineup_campus/konu-ekle', 12, '2025-04-16 11:47:18'),
(181, 1, 'http://localhost/lineup_campus/konu-ekle', 47, '2025-04-16 11:48:06'),
(182, 1, 'http://localhost/lineup_campus/konu-ekle', 36, '2025-04-16 11:48:43'),
(183, 1, 'http://localhost/lineup_campus/konu-ekle', 36, '2025-04-16 11:49:20'),
(184, 1, 'http://localhost/lineup_campus/uniteler', 1056, '2025-04-16 11:49:55'),
(185, 1, 'http://localhost/lineup_campus/konular', 2, '2025-04-16 11:49:57'),
(186, 1, 'http://localhost/lineup_campus/uniteler', 49, '2025-04-16 11:50:47'),
(187, 1, 'http://localhost/lineup_campus/uniteler', 4726, '2025-04-16 13:09:33'),
(188, 1, 'http://localhost/lineup_campus/uniteler', 60, '2025-04-16 13:10:34'),
(189, 1, 'http://localhost/lineup_campus/konu-ekle', 5026, '2025-04-16 13:13:07'),
(190, 1, 'http://localhost/lineup_campus/konu-ekle', 25, '2025-04-16 13:13:34'),
(191, 1, 'http://localhost/lineup_campus/konu-ekle', 93, '2025-04-16 13:15:08'),
(192, 1, 'http://localhost/lineup_campus/konu-ekle', 73, '2025-04-16 13:16:21'),
(193, 1, 'http://localhost/lineup_campus/konu-ekle', 72, '2025-04-16 13:17:34'),
(194, 1, 'http://localhost/lineup_campus/konu-ekle', 114, '2025-04-16 13:19:28'),
(195, 1, 'http://localhost/lineup_campus/konu-ekle', 9, '2025-04-16 13:19:38'),
(196, 1, 'http://localhost/lineup_campus/konu-ekle', 19, '2025-04-16 13:19:58'),
(197, 1, 'http://localhost/lineup_campus/konu-ekle', 64, '2025-04-16 13:21:02'),
(198, 1, 'http://localhost/lineup_campus/konu-ekle', 49, '2025-04-16 13:21:52'),
(199, 1, 'http://localhost/lineup_campus/konu-ekle', 170, '2025-04-16 13:24:43'),
(200, 1, 'http://localhost/lineup_campus/konu-ekle', 15, '2025-04-16 13:24:58'),
(201, 1, 'http://localhost/lineup_campus/konu-ekle', 41, '2025-04-16 13:25:40'),
(202, 1, 'http://localhost/lineup_campus/konu-ekle', 80, '2025-04-16 13:27:01'),
(203, 1, 'http://localhost/lineup_campus/konu-ekle', 81, '2025-04-16 13:28:23'),
(204, 1, 'http://localhost/lineup_campus/konu-ekle', 70, '2025-04-16 13:29:34'),
(205, 1, 'http://localhost/lineup_campus/konu-ekle', 30, '2025-04-16 13:30:05'),
(206, 1, 'http://localhost/lineup_campus/konu-ekle', 43, '2025-04-16 13:30:49'),
(207, 1, 'http://localhost/lineup_campus/konu-ekle', 32, '2025-04-16 13:31:22'),
(208, 1, 'http://localhost/lineup_campus/konu-ekle', 65, '2025-04-16 13:32:28'),
(209, 1, 'http://localhost/lineup_campus/konu-ekle', 45, '2025-04-16 13:33:14'),
(210, 1, 'http://localhost/lineup_campus/konu-ekle', 370, '2025-04-16 13:39:25'),
(211, 1, 'http://localhost/lineup_campus/konu-ekle', 119, '2025-04-16 13:41:25'),
(212, 1, 'http://localhost/lineup_campus/konu-ekle', 45, '2025-04-16 13:42:11'),
(213, 1, 'http://localhost/lineup_campus/konu-ekle', 2319, '2025-04-16 14:20:51'),
(214, 1, 'http://localhost/lineup_campus/konu-ekle', 102, '2025-04-16 14:22:34'),
(215, 1, 'http://localhost/lineup_campus/konu-ekle', 46, '2025-04-16 14:23:21'),
(216, 1, 'http://localhost/lineup_campus/konu-ekle', 41, '2025-04-16 14:24:03'),
(217, 1, 'http://localhost/lineup_campus/konu-ekle', 58, '2025-04-16 14:25:02'),
(218, 1, 'http://localhost/lineup_campus/konular', 27, '2025-04-16 14:25:30'),
(219, 1, 'http://localhost/lineup_campus/konu-ekle', 106, '2025-04-16 14:27:17'),
(220, 1, 'http://localhost/lineup_campus/konular', 422, '2025-04-16 14:34:20'),
(221, 1, 'http://localhost/lineup_campus/konu-ekle', 23, '2025-04-16 14:34:44'),
(222, 1, 'http://localhost/lineup_campus/konular', 23527, '2025-04-16 21:06:51'),
(223, 1, 'http://localhost/lineup_campus/konular', 932, '2025-04-16 21:22:24'),
(224, 1, 'http://localhost/lineup_campus/konular', 2, '2025-04-16 21:22:27'),
(225, 1, 'http://localhost/lineup_campus/konular', 148, '2025-04-16 21:24:57'),
(226, 1, 'http://localhost/lineup_campus/alt-konular', 1158, '2025-04-16 21:44:40'),
(227, 1, 'http://localhost/lineup_campus/alt-konular', 4, '2025-04-16 21:44:46'),
(228, 1, 'http://localhost/lineup_campus/altkonu-ekle', 133, '2025-04-16 21:47:00'),
(229, 1, 'http://localhost/lineup_campus/altkonu-ekle', 316, '2025-04-16 21:52:16'),
(230, 1, 'http://localhost/lineup_campus/altkonu-ekle', 12, '2025-04-16 21:52:29'),
(231, 1, 'http://localhost/lineup_campus/altkonu-ekle', 175, '2025-04-16 21:55:25'),
(232, 1, 'http://localhost/lineup_campus/altkonu-ekle', 1899, '2025-04-16 22:27:05'),
(233, 1, 'http://localhost/lineup_campus/altkonu-ekle', 2433, '2025-04-16 23:07:39'),
(234, 1, 'http://localhost/lineup_campus/altkonu-ekle', 95, '2025-04-16 23:09:15'),
(235, 1, 'http://localhost/lineup_campus/uniteler', 36023, '2025-04-16 23:10:58'),
(236, 1, 'http://localhost/lineup_campus/altkonu-ekle', 105, '2025-04-16 23:11:01'),
(237, 1, 'http://localhost/lineup_campus/uniteler', 23, '2025-04-16 23:11:22'),
(238, 1, 'http://localhost/lineup_campus/konular', 2, '2025-04-16 23:11:24'),
(239, 1, 'http://localhost/lineup_campus/konu-ekle', 13, '2025-04-16 23:11:39'),
(240, 1, 'http://localhost/lineup_campus/altkonu-ekle', 428, '2025-04-16 23:18:11'),
(241, 1, 'http://localhost/lineup_campus/altkonu-ekle', 51, '2025-04-16 23:19:03'),
(242, 1, 'http://localhost/lineup_campus/altkonu-ekle', 472, '2025-04-16 23:26:56'),
(243, 1, 'http://localhost/lineup_campus/altkonu-ekle', 107, '2025-04-16 23:28:45'),
(244, 1, 'http://localhost/lineup_campus/konular', 0, '2025-04-16 23:28:46'),
(245, 1, 'http://localhost/lineup_campus/altkonu-ekle', 26, '2025-04-16 23:29:13'),
(246, 1, 'http://localhost/lineup_campus/altkonu-ekle', 29, '2025-04-16 23:29:43'),
(247, 1, 'http://localhost/lineup_campus/konular', 131, '2025-04-16 23:31:55'),
(248, 1, 'http://localhost/lineup_campus/alt-konular', 1, '2025-04-16 23:31:57'),
(249, 1, 'http://localhost/lineup_campus/altkonu-ekle', 35, '2025-04-16 23:32:33'),
(250, 1, 'http://localhost/lineup_campus/konular', 49, '2025-04-16 23:33:23'),
(251, 1, 'http://localhost/lineup_campus/altkonu-ekle', 1, '2025-04-16 23:33:26'),
(252, 1, 'http://localhost/lineup_campus/alt-konular', 29, '2025-04-16 23:33:55'),
(253, 1, 'http://localhost/lineup_campus/altkonu-ekle', 203, '2025-04-16 23:37:19'),
(254, 1, 'http://localhost/lineup_campus/alt-konular', 3, '2025-04-16 23:37:22'),
(255, 1, 'http://localhost/lineup_campus/altkonu-ekle', 289, '2025-04-16 23:42:12'),
(256, 1, 'http://localhost/lineup_campus/alt-konular', 2, '2025-04-16 23:42:15'),
(257, 1, 'http://localhost/lineup_campus/altkonu-ekle', 65, '2025-04-16 23:43:21'),
(258, 1, 'http://localhost/lineup_campus/alt-konular', 170, '2025-04-16 23:46:12'),
(259, 1, 'http://localhost/lineup_campus/altkonu-ekle', 93, '2025-04-16 23:47:46'),
(260, 1, 'http://localhost/lineup_campus/alt-konular', 102, '2025-04-16 23:49:28'),
(261, 1, 'http://localhost/lineup_campus/alt-konular', 46, '2025-04-16 23:50:16'),
(262, 1, 'http://localhost/lineup_campus/alt-konular', 304, '2025-04-16 23:55:20'),
(263, 1, 'http://localhost/lineup_campus/alt-konular', 63, '2025-04-16 23:56:24'),
(264, 1, 'http://localhost/lineup_campus/alt-konular', 70, '2025-04-16 23:57:35'),
(265, 1, 'http://localhost/lineup_campus/alt-konular', 86, '2025-04-16 23:59:03'),
(266, 1, 'http://localhost/lineup_campus/alt-konular', 1, '2025-04-16 23:59:04'),
(267, 1, 'http://localhost/lineup_campus/konular', 1, '2025-04-16 23:59:06'),
(268, 1, 'http://localhost/lineup_campus/uniteler', 15, '2025-04-16 23:59:22'),
(269, 1, 'http://localhost/lineup_campus/uniteler', 1, '2025-04-16 23:59:23'),
(270, 1, 'http://localhost/lineup_campus/dersler', 2, '2025-04-16 23:59:26'),
(271, 1, 'http://localhost/lineup_campus/siniflar', 1, '2025-04-16 23:59:28'),
(272, 1, 'http://localhost/lineup_campus/konular', 1, '2025-04-16 23:59:29'),
(273, 1, 'http://localhost/lineup_campus/alt-konular', 0, '2025-04-16 23:59:30'),
(274, 1, 'http://localhost/lineup_campus/haftalik-gorev', 2, '2025-04-16 23:59:34'),
(275, 1, 'http://localhost/lineup_campus/sesli-kitaplar', 1, '2025-04-16 23:59:35'),
(276, 1, 'http://localhost/lineup_campus/oyunlar', 7, '2025-04-16 23:59:43'),
(277, 1, 'http://localhost/lineup_campus/konular', 43297, '2025-04-17 12:01:20'),
(278, 1, 'http://localhost/lineup_campus/alt-konular', 8, '2025-04-17 12:01:30'),
(279, 1, 'http://localhost/lineup_campus/altkonu-ekle', 17614, '2025-04-17 16:55:06'),
(280, 1, 'http://localhost/lineup_campus/altkonu-ekle', 3, '2025-04-17 16:55:10'),
(281, 1, 'http://localhost/lineup_campus/altkonu-ekle', 139, '2025-04-17 16:57:31'),
(282, 1, 'http://localhost/lineup_campus/altkonu-ekle', 117, '2025-04-17 16:59:29'),
(283, 1, 'http://localhost/lineup_campus/altkonu-ekle', 92, '2025-04-17 17:01:02'),
(284, 1, 'http://localhost/lineup_campus/altkonu-ekle', 20, '2025-04-17 17:01:23'),
(285, 1, 'http://localhost/lineup_campus/altkonu-ekle', 11, '2025-04-17 17:01:35'),
(286, 1, 'http://localhost/lineup_campus/altkonu-ekle', 424, '2025-04-17 17:08:39'),
(287, 1, 'http://localhost/lineup_campus/altkonu-ekle', 124, '2025-04-17 17:10:44'),
(288, 1, 'http://localhost/lineup_campus/altkonu-ekle', 27, '2025-04-17 17:11:12'),
(289, 1, 'http://localhost/lineup_campus/altkonu-ekle', 10, '2025-04-17 17:11:24'),
(290, 1, 'http://localhost/lineup_campus/altkonu-ekle', 159, '2025-04-17 17:14:04'),
(291, 1, 'http://localhost/lineup_campus/altkonu-ekle', 148, '2025-04-17 17:16:33'),
(292, 1, 'http://localhost/lineup_campus/altkonu-ekle', 30, '2025-04-17 17:17:04'),
(293, 1, 'http://localhost/lineup_campus/altkonu-ekle', 6248, '2025-04-17 21:51:08'),
(294, 1, 'http://localhost/lineup_campus/altkonu-ekle', 6, '2025-04-17 21:51:14'),
(295, 1, 'http://localhost/lineup_campus/altkonu-ekle', 94, '2025-04-17 21:52:50'),
(296, 1, 'http://localhost/lineup_campus/okullar', 6, '2025-04-17 21:52:57'),
(297, 1, 'http://localhost/lineup_campus/ogrenciler', 6, '2025-04-17 21:53:05'),
(298, 1, 'http://localhost/lineup_campus/konular', 4, '2025-04-17 22:20:23'),
(299, 1, 'http://localhost/lineup_campus/okullar', 51, '2025-04-17 22:27:46'),
(300, 1, 'http://localhost/lineup_campus/konular', 149, '2025-04-17 22:29:20'),
(301, 1, 'http://localhost/lineup_campus/duyurular', 145, '2025-04-17 22:30:33'),
(302, 1, 'http://localhost/lineup_campus/duyurular', 7, '2025-04-17 22:30:41'),
(303, 1, 'http://localhost/lineup_campus/dashboard', 0, '2025-04-17 22:30:42'),
(304, 1, 'http://localhost/lineup_campus/duyurular', 3, '2025-04-17 22:30:47'),
(305, 1, 'http://localhost/lineup_campus/dashboard', 1, '2025-04-17 22:30:48'),
(306, 1, 'http://localhost/lineup_campus/dashboard', 2, '2025-04-17 22:30:52'),
(307, 1, 'http://localhost/lineup_campus/dashboard', 0, '2025-04-17 22:30:53'),
(308, 1, 'http://localhost/lineup_campus/okullar', 2524, '2025-04-17 22:35:09'),
(309, 1, 'http://localhost/lineup_campus/uniteler', 3, '2025-04-17 22:35:14'),
(310, 1, 'http://localhost/lineup_campus/alt-konular', 0, '2025-04-17 22:35:16'),
(311, 1, 'http://localhost/lineup_campus/duyurular', 478, '2025-04-17 22:38:52'),
(312, 1, 'http://localhost/lineup_campus/duyurular', 66, '2025-04-17 22:40:00'),
(313, 1, 'http://localhost/lineup_campus/duyurular', 84, '2025-04-17 22:41:25'),
(314, 1, 'http://localhost/lineup_campus/altkonu-ekle', 521, '2025-04-17 22:43:57'),
(315, 1, 'http://localhost/lineup_campus/duyurular', 216, '2025-04-17 22:45:03'),
(316, 1, 'http://localhost/lineup_campus/duyurular', 1509, '2025-04-17 23:10:13'),
(317, 1, 'http://localhost/lineup_campus/duyurular', 121, '2025-04-17 23:14:40'),
(318, 1, 'http://localhost/lineup_campus/duyurular', 76, '2025-04-17 23:15:56'),
(319, 1, 'http://localhost/lineup_campus/duyurular', 167, '2025-04-17 23:18:45'),
(320, 1, 'http://localhost/lineup_campus/duyurular', 20, '2025-04-17 23:19:36'),
(321, 1, 'http://localhost/lineup_campus/duyurular', 34898, '2025-04-18 09:01:15'),
(322, 1, 'http://localhost/lineup_campus/duyurular', 81, '2025-04-18 09:02:37'),
(323, 1, 'http://localhost/lineup_campus/ogretmenler', 38048, '2025-04-18 09:03:29'),
(324, 1, 'http://localhost/lineup_campus/ogrenciler', 1, '2025-04-18 09:03:31'),
(325, 1, 'http://localhost/lineup_campus/duyurular', 275, '2025-04-18 09:07:13'),
(326, 1, 'http://localhost/lineup_campus/duyurular', 22, '2025-04-18 09:07:36'),
(327, 1, 'http://localhost/lineup_campus/duyurular', 331, '2025-04-18 09:13:08'),
(328, 1, 'http://localhost/lineup_campus/okullar', 17, '2025-04-18 09:13:26'),
(329, 1, 'http://localhost/lineup_campus/okullar', 7, '2025-04-18 09:13:35'),
(330, 1, 'http://localhost/lineup_campus/ogretmenler', 9, '2025-04-18 09:13:45'),
(331, 1, 'http://localhost/lineup_campus/ogretmenler', 15, '2025-04-18 09:14:01'),
(332, 1, 'http://localhost/lineup_campus/ogretmenler', 7, '2025-04-18 09:14:09'),
(333, 1, 'http://localhost/lineup_campus/ogrenciler', 10, '2025-04-18 09:14:19'),
(334, 1, 'http://localhost/lineup_campus/ogrenciler', 6, '2025-04-18 09:14:27'),
(335, 1, 'http://localhost/lineup_campus/veliler', 3, '2025-04-18 09:14:31'),
(336, 1, 'http://localhost/lineup_campus/veliler', 20, '2025-04-18 09:14:52'),
(337, 1, 'http://localhost/lineup_campus/veliler', 9, '2025-04-18 09:15:02'),
(338, 1, 'http://localhost/lineup_campus/siniflar', 36, '2025-04-18 09:15:39'),
(339, 1, 'http://localhost/lineup_campus/siniflar', 5, '2025-04-18 09:15:45'),
(340, 1, 'http://localhost/lineup_campus/dersler', 6, '2025-04-18 09:15:52'),
(341, 1, 'http://localhost/lineup_campus/uniteler', 2, '2025-04-18 09:15:55'),
(342, 1, 'http://localhost/lineup_campus/konular', 0, '2025-04-18 09:15:56'),
(343, 1, 'http://localhost/lineup_campus/konu-ekle', 1, '2025-04-18 09:15:59'),
(344, 1, 'http://localhost/lineup_campus/konular', 11, '2025-04-18 09:16:11'),
(345, 1, 'http://localhost/lineup_campus/alt-konular', 3, '2025-04-18 09:16:15'),
(346, 1, 'http://localhost/lineup_campus/duyurular', 61, '2025-04-18 09:17:17'),
(347, 1, 'http://localhost/lineup_campus/duyurular', 115, '2025-04-18 09:19:14'),
(348, 1, 'http://localhost/lineup_campus/duyurular', 33, '2025-04-18 09:19:48'),
(349, 1, 'http://localhost/lineup_campus/duyurular', 56, '2025-04-18 09:20:46'),
(350, 1, 'http://localhost/lineup_campus/duyurular', 20, '2025-04-18 09:21:07'),
(351, 1, 'http://localhost/lineup_campus/duyurular', 978, '2025-04-18 09:37:26'),
(352, 1, 'http://localhost/lineup_campus/duyurular', 2, '2025-04-18 09:37:30'),
(353, 1, 'http://localhost/lineup_campus/dashboard', 1, '2025-04-18 09:37:32'),
(354, 1, 'http://localhost/lineup_campus/duyurular', 4, '2025-04-18 09:37:37'),
(355, 1, 'http://localhost/lineup_campus/dashboard', 0, '2025-04-18 09:37:39'),
(356, 1, 'http://localhost/lineup_campus/duyurular', 19, '2025-04-18 09:37:59'),
(357, 1, 'http://localhost/lineup_campus/duyurular', 6, '2025-04-18 09:38:06'),
(358, 1, 'http://localhost/lineup_campus/dashboard', 219, '2025-04-18 09:41:46'),
(359, 1, 'http://localhost/lineup_campus/duyurular', 0, '2025-04-18 09:41:47'),
(360, 1, 'http://localhost/lineup_campus/duyurular', 2, '2025-04-18 09:41:50'),
(361, 1, 'http://localhost/lineup_campus/dashboard', 1, '2025-04-18 09:41:52'),
(362, 1, 'http://localhost/lineup_campus/duyurular', 6, '2025-04-18 09:41:59'),
(363, 1, 'http://localhost/lineup_campus/duyurular', 65, '2025-04-18 09:43:05'),
(364, 1, 'http://localhost/lineup_campus/duyurular', 1, '2025-04-18 09:43:08'),
(365, 1, 'http://localhost/lineup_campus/dashboard', 0, '2025-04-18 09:43:09'),
(366, 1, 'http://localhost/lineup_campus/duyurular', 26, '2025-04-18 09:43:36'),
(367, 1, 'http://localhost/lineup_campus/duyurular', 29, '2025-04-18 09:44:06'),
(368, 1, 'http://localhost/lineup_campus/duyurular', 18, '2025-04-18 09:44:25'),
(369, 1, 'http://localhost/lineup_campus/dashboard', 65, '2025-04-18 09:45:31'),
(370, 1, 'http://localhost/lineup_campus/duyurular', 146, '2025-04-18 09:47:57'),
(371, 1, 'http://localhost/lineup_campus/duyurular', 1, '2025-04-18 09:47:59'),
(372, 1, 'http://localhost/lineup_campus/dashboard', 13, '2025-04-18 09:48:13'),
(373, 1, 'http://localhost/lineup_campus/duyurular', 0, '2025-04-18 09:48:14'),
(374, 1, 'http://localhost/lineup_campus/duyurular', 6, '2025-04-18 09:48:21'),
(375, 1, 'http://localhost/lineup_campus/duyurular', 37, '2025-04-18 09:48:59'),
(376, 1, 'http://localhost/lineup_campus/duyurular', 19, '2025-04-18 09:49:19'),
(377, 1, 'http://localhost/lineup_campus/dashboard', 0, '2025-04-18 09:49:20'),
(378, 1, 'http://localhost/lineup_campus/duyurular', 37, '2025-04-18 09:49:58'),
(379, 1, 'http://localhost/lineup_campus/duyurular', 9, '2025-04-18 09:50:08'),
(380, 1, 'http://localhost/lineup_campus/dashboard', 0, '2025-04-18 09:50:10'),
(381, 1, 'http://localhost/lineup_campus/duyurular', 7, '2025-04-18 09:50:17'),
(382, 1, 'http://localhost/lineup_campus/duyurular', 185, '2025-04-18 09:53:23'),
(383, 1, 'http://localhost/lineup_campus/alt-konular', 0, '2025-04-18 09:53:25'),
(384, 1, 'http://localhost/lineup_campus/alt-konular', 4, '2025-04-18 09:53:30'),
(385, 1, 'http://localhost/lineup_campus/duyurular', 2, '2025-04-18 09:53:33'),
(386, 1, 'http://localhost/lineup_campus/dashboard', 1, '2025-04-18 09:53:34'),
(387, 1, 'http://localhost/lineup_campus/duyurular', 0, '2025-04-18 09:53:36'),
(388, 1, 'http://localhost/lineup_campus/duyurular', 11, '2025-04-18 09:53:47'),
(389, 1, 'http://localhost/lineup_campus/dashboard', 0, '2025-04-18 09:53:49'),
(390, 1, 'http://localhost/lineup_campus/duyurular', 4, '2025-04-18 09:53:53'),
(391, 1, 'http://localhost/lineup_campus/dashboard', 0, '2025-04-18 09:53:54'),
(392, 1, 'http://localhost/lineup_campus/duyurular', 34, '2025-04-18 09:54:30'),
(393, 1, 'http://localhost/lineup_campus/alt-konular', 4, '2025-04-18 09:54:35'),
(394, 1, 'http://localhost/lineup_campus/duyurular', 0, '2025-04-18 09:54:36'),
(395, 1, 'http://localhost/lineup_campus/duyurular', 2, '2025-04-18 09:54:39'),
(396, 1, 'http://localhost/lineup_campus/dashboard', 1, '2025-04-18 09:54:41'),
(397, 1, 'http://localhost/lineup_campus/duyurular', 4, '2025-04-18 09:54:45'),
(398, 1, 'http://localhost/lineup_campus/dashboard', 0, '2025-04-18 09:54:47'),
(399, 1, 'http://localhost/lineup_campus/duyurular', 1, '2025-04-18 09:54:48'),
(400, 1, 'http://localhost/lineup_campus/duyurular', 134, '2025-04-18 09:57:03'),
(401, 1, 'http://localhost/lineup_campus/duyurular', 11, '2025-04-18 09:57:15'),
(402, 1, 'http://localhost/lineup_campus/dashboard', 0, '2025-04-18 09:57:16'),
(403, 1, 'http://localhost/lineup_campus/duyurular', 178, '2025-04-18 10:00:16'),
(404, 1, 'http://localhost/lineup_campus/duyurular', 6, '2025-04-18 10:00:22'),
(405, 1, 'http://localhost/lineup_campus/dashboard', 0, '2025-04-18 10:00:23'),
(406, 1, 'http://localhost/lineup_campus/duyurular', 42, '2025-04-18 10:01:06'),
(407, 1, 'http://localhost/lineup_campus/duyurular', 31, '2025-04-18 10:01:38'),
(408, 1, 'http://localhost/lineup_campus/duyurular', 3935, '2025-04-18 11:07:14'),
(409, 1, 'http://localhost/lineup_campus/duyurular', 37, '2025-04-18 11:07:52'),
(410, 1, 'http://localhost/lineup_campus/duyurular', 48, '2025-04-18 11:08:41'),
(411, 1, 'http://localhost/lineup_campus/duyurular', 77, '2025-04-18 11:09:59'),
(412, 1, 'http://localhost/lineup_campus/okullar', 155, '2025-04-18 11:12:36'),
(413, 1, 'http://localhost/lineup_campus/okullar', 4, '2025-04-18 11:12:41'),
(414, 1, 'http://localhost/lineup_campus/duyurular', 5, '2025-04-18 11:12:46'),
(415, 1, 'http://localhost/lineup_campus/duyurular', 47, '2025-04-18 11:13:35'),
(416, 1, 'http://localhost/lineup_campus/okullar', 1, '2025-04-18 11:13:37'),
(417, 1, 'http://localhost/lineup_campus/okullar', 5, '2025-04-18 11:13:43'),
(418, 1, 'http://localhost/lineup_campus/duyurular', 15, '2025-04-18 11:13:59'),
(419, 1, 'http://localhost/lineup_campus/duyurular', 11, '2025-04-18 11:14:11'),
(420, 1, 'http://localhost/lineup_campus/duyurular', 28, '2025-04-18 11:14:41'),
(421, 1, 'http://localhost/lineup_campus/duyurular', 126, '2025-04-18 11:16:48'),
(422, 1, 'http://localhost/lineup_campus/duyurular', 507, '2025-04-18 11:25:17'),
(423, 1, 'http://localhost/lineup_campus/duyurular', 311, '2025-04-18 11:31:15'),
(424, 1, 'http://localhost/lineup_campus/duyurular', 31, '2025-04-18 11:31:46'),
(425, 1, 'http://localhost/lineup_campus/duyurular', 1376, '2025-04-18 11:54:43'),
(426, 1, 'http://localhost/lineup_campus/duyurular', 11, '2025-04-18 11:54:55'),
(427, 1, 'http://localhost/lineup_campus/duyurular', 46, '2025-04-18 11:55:48'),
(428, 1, 'http://localhost/lineup_campus/duyurular', 35, '2025-04-18 11:56:24'),
(429, 1, 'http://localhost/lineup_campus/duyurular', 11, '2025-04-18 11:56:36'),
(430, 1, 'http://localhost/lineup_campus/duyurular', 83, '2025-04-18 11:58:00'),
(431, 1, 'http://localhost/lineup_campus/duyurular', 20, '2025-04-18 11:58:22'),
(432, 1, 'http://localhost/lineup_campus/duyurular', 4089, '2025-04-18 13:06:32'),
(433, 1, 'http://localhost/lineup_campus/duyurular', 32, '2025-04-18 13:07:27'),
(434, 1, 'http://localhost/lineup_campus/duyurular', 66, '2025-04-18 13:08:34'),
(435, 1, 'http://localhost/lineup_campus/duyurular', 16, '2025-04-18 13:08:51'),
(436, 1, 'http://localhost/lineup_campus/duyurular', 21, '2025-04-18 13:09:12'),
(437, 1, 'http://localhost/lineup_campus/duyurular', 46, '2025-04-18 13:09:59'),
(438, 1, 'http://localhost/lineup_campus/duyurular', 13, '2025-04-18 13:10:13'),
(439, 1, 'http://localhost/lineup_campus/duyurular', 57, '2025-04-18 13:11:11'),
(440, 1, 'http://localhost/lineup_campus/duyurular', 2470, '2025-04-18 13:52:23'),
(441, 1, 'http://localhost/lineup_campus/duyurular', 1, '2025-04-18 13:52:24'),
(442, 1, 'http://localhost/lineup_campus/bildirimler', 67, '2025-04-18 14:00:51'),
(443, 1, 'http://localhost/lineup_campus/bildirimler', 3, '2025-04-18 14:00:55'),
(444, 1, 'http://localhost/lineup_campus/bildirimler', 379, '2025-04-18 14:07:15'),
(445, 1, 'http://localhost/lineup_campus/bildirimler', 21, '2025-04-18 14:07:38'),
(446, 1, 'http://localhost/lineup_campus/bildirimler', 20, '2025-04-18 14:07:58'),
(447, 1, 'http://localhost/lineup_campus/bildirimler', 27, '2025-04-18 14:08:26'),
(448, 1, 'http://localhost/lineup_campus/bildirimler', 9, '2025-04-18 14:08:36'),
(449, 1, 'http://localhost/lineup_campus/duyurular', 1, '2025-04-18 14:08:38'),
(450, 1, 'http://localhost/lineup_campus/bildirimler', 13, '2025-04-18 14:08:52'),
(451, 1, 'http://localhost/lineup_campus/bildirimler', 52, '2025-04-18 14:09:45'),
(452, 1, 'http://localhost/lineup_campus/bildirimler', 847, '2025-04-18 14:23:52'),
(453, 32, 'http://localhost/lineup_campus/dashboard', 306, '2025-04-18 14:29:07'),
(454, 32, 'http://localhost/lineup_campus/dashboard', 984, '2025-04-18 14:45:31'),
(455, 32, 'http://localhost/lineup_campus/dashboard', 462, '2025-04-18 14:53:31'),
(456, 32, 'http://localhost/lineup_campus/dashboard', 12, '2025-04-18 14:56:42'),
(457, 32, 'http://localhost/lineup_campus/dashboard', 2, '2025-04-18 14:56:44'),
(458, 32, 'http://localhost/lineup_campus/dashboard', 7, '2025-04-18 14:56:52'),
(459, 32, 'http://localhost/lineup_campus/dashboard', 710, '2025-04-18 15:14:45'),
(460, 32, 'http://localhost/lineup_campus/dashboard', 3488, '2025-04-18 16:12:53'),
(461, 32, 'http://localhost/lineup_campus/dashboard', 13, '2025-04-18 16:13:07'),
(462, 32, 'http://localhost/lineup_campus/dashboard', 413, '2025-04-18 16:20:01'),
(463, 32, 'http://localhost/lineup_campus/dashboard', 430, '2025-04-18 16:31:08'),
(464, 32, 'http://localhost/lineup_campus/dashboard', 39, '2025-04-18 16:31:48'),
(465, 32, 'http://localhost/lineup_campus/dashboard', 516, '2025-04-18 16:40:24'),
(466, 32, 'http://localhost/lineup_campus/dashboard', 16, '2025-04-18 16:40:41'),
(467, 32, 'http://localhost/lineup_campus/dashboard', 2, '2025-04-18 16:40:44'),
(468, 32, 'http://localhost/lineup_campus/dashboard', 181, '2025-04-18 16:43:45'),
(469, 32, 'http://localhost/lineup_campus/dashboard', 50, '2025-04-18 16:44:36'),
(470, 32, 'http://localhost/lineup_campus/dashboard', 293, '2025-04-18 16:49:30'),
(471, 32, 'http://localhost/lineup_campus/dashboard', 513, '2025-04-18 16:58:04'),
(472, 32, 'http://localhost/lineup_campus/dashboard', 41, '2025-04-18 16:59:54'),
(473, 32, 'http://localhost/lineup_campus/dashboard', 135, '2025-04-18 17:06:14'),
(474, 32, 'http://localhost/lineup_campus/dashboard', 59, '2025-04-18 17:08:35'),
(475, 32, 'http://localhost/lineup_campus/dashboard', 5, '2025-04-18 17:08:43'),
(476, 32, 'http://localhost/lineup_campus/dashboard', 49, '2025-04-18 17:09:33'),
(477, 32, 'http://localhost/lineup_campus/dashboard', 30, '2025-04-18 17:10:04'),
(478, 32, 'http://localhost/lineup_campus/dashboard', 941, '2025-04-18 17:25:45'),
(479, 32, 'http://localhost/lineup_campus/dashboard', 31, '2025-04-18 17:26:17'),
(480, 32, 'http://localhost/lineup_campus/dashboard', 33, '2025-04-18 17:26:51'),
(481, 32, 'http://localhost/lineup_campus/dashboard', 21, '2025-04-18 17:27:12'),
(482, 32, 'http://localhost/lineup_campus/dashboard', 139, '2025-04-18 17:29:32'),
(483, 32, 'http://localhost/lineup_campus/dashboard', 88, '2025-04-18 17:31:00'),
(484, 32, 'http://localhost/lineup_campus/dashboard', 137, '2025-04-18 17:33:18'),
(485, 32, 'http://localhost/lineup_campus/dashboard', 1, '2025-04-21 08:50:57'),
(486, 32, 'http://localhost/lineup_campus/ders/turkce', 5, '2025-04-21 08:51:03'),
(487, 32, 'http://localhost/lineup_campus/ders/matematik', 1, '2025-04-21 08:51:04'),
(488, 32, 'http://localhost/lineup_campus/ders/hayat-bilgisi', 1, '2025-04-21 08:51:05'),
(489, 32, 'http://localhost/lineup_campus/ders/ingilizce', 1, '2025-04-21 08:51:07'),
(490, 32, 'http://localhost/lineup_campus/dashboard', 3, '2025-04-21 08:51:32'),
(491, 1, 'http://localhost/lineup_campus/dashboard', 4, '2025-04-21 08:51:46'),
(492, 1, 'http://localhost/lineup_campus/uniteler', 26, '2025-04-21 08:52:13'),
(493, 1, 'http://localhost/lineup_campus/uniteler', 1, '2025-04-21 08:52:15'),
(494, 1, 'http://localhost/lineup_campus/konular', 1, '2025-04-21 08:52:17'),
(495, 1, 'http://localhost/lineup_campus/konu-ekle', 15, '2025-04-21 08:52:33'),
(496, 1, 'http://localhost/lineup_campus/konular', 1, '2025-04-21 08:52:35'),
(497, 1, 'http://localhost/lineup_campus/alt-konular', 1, '2025-04-21 08:52:37'),
(498, 1, 'http://localhost/lineup_campus/altkonu-ekle', 280, '2025-04-21 08:57:18'),
(499, 1, 'http://localhost/lineup_campus/alt-konular', 5, '2025-04-21 08:57:24'),
(500, 32, 'http://localhost/lineup_campus/dashboard', 2, '2025-04-21 08:57:33'),
(501, 32, 'http://localhost/lineup_campus/ders/turkce', 1, '2025-04-21 08:57:34'),
(502, 32, 'http://localhost/lineup_campus/unite/deneme-unite', 2, '2025-04-21 08:57:37'),
(503, 32, 'http://localhost/lineup_campus/konu/deneme-konu', 3, '2025-04-21 08:57:40'),
(504, 32, 'http://localhost/lineup_campus/ders/fen-bilimleri', 689, '2025-04-21 09:02:37'),
(505, 32, 'http://localhost/lineup_campus/unite/deneme-unite', 306, '2025-04-21 09:02:47'),
(506, 32, 'http://localhost/lineup_campus/konu/deneme-konu', 18, '2025-04-21 09:03:06'),
(507, 32, 'http://localhost/lineup_campus/ders/turkce', 2032, '2025-04-21 09:36:29'),
(508, 32, 'http://localhost/lineup_campus/ders/turkce', 0, '2025-04-21 09:36:30'),
(509, 32, 'http://localhost/lineup_campus/unite/deneme-unite', 3, '2025-04-21 09:36:34'),
(510, 32, 'http://localhost/lineup_campus/unite/deneme-unite', 6, '2025-04-21 09:36:41'),
(511, 32, 'http://localhost/lineup_campus/ders/turkce', 2, '2025-04-21 09:36:44'),
(512, 32, 'http://localhost/lineup_campus/unite/deneme-unite', 3, '2025-04-21 09:36:48'),
(513, 32, 'http://localhost/lineup_campus/unite/deneme-unite', 3, '2025-04-21 09:36:53'),
(514, 32, 'http://localhost/lineup_campus/ders/turkce', 0, '2025-04-21 09:36:54'),
(515, 32, 'http://localhost/lineup_campus/ders/fen-bilimleri', 1, '2025-04-21 09:36:56'),
(516, 32, 'http://localhost/lineup_campus/ders/turkce', 1, '2025-04-21 09:36:57'),
(517, 32, 'http://localhost/lineup_campus/unite/deneme-unite', 1, '2025-04-21 09:36:59'),
(518, 32, 'http://localhost/lineup_campus/konu/deneme-konu', 37, '2025-04-21 09:38:22'),
(519, 32, 'http://localhost/lineup_campus/konu/deneme-konu', 48, '2025-04-21 09:39:11'),
(520, 32, 'http://localhost/lineup_campus/konu/deneme-konu', 11, '2025-04-21 09:39:23'),
(521, 32, 'http://localhost/lineup_campus/unite/deneme-unite', 65, '2025-04-21 09:40:28'),
(522, 32, 'http://localhost/lineup_campus/konu/deneme-konu', 298, '2025-04-21 09:45:27'),
(523, 32, 'http://localhost/lineup_campus/unite/deneme-unite', 1, '2025-04-21 09:45:29'),
(524, 32, 'http://localhost/lineup_campus/konu/deneme-konu', 180, '2025-04-21 09:48:30'),
(525, 32, 'http://localhost/lineup_campus/unite/deneme-unite', 12, '2025-04-21 09:48:42'),
(526, 32, 'http://localhost/lineup_campus/ders/turkce', 0, '2025-04-21 09:48:43'),
(527, 32, 'http://localhost/lineup_campus/ders/fen-bilimleri', 0, '2025-04-21 09:48:44'),
(528, 32, 'http://localhost/lineup_campus/ders/turkce', 0, '2025-04-21 09:48:44'),
(529, 32, 'http://localhost/lineup_campus/unite/deneme-unite', 0, '2025-04-21 09:48:45'),
(530, 32, 'http://localhost/lineup_campus/konu/deneme-konu', 0, '2025-04-21 09:48:46'),
(531, 32, 'http://localhost/lineup_campus/unite/deneme-unite', 22, '2025-04-21 09:49:09'),
(532, 32, 'http://localhost/lineup_campus/konu/deneme-konu', 1, '2025-04-21 09:49:10'),
(533, 32, 'http://localhost/lineup_campus/unite/deneme-unite', 0, '2025-04-21 09:49:11'),
(534, 32, 'http://localhost/lineup_campus/ders/turkce', 0, '2025-04-21 09:49:12'),
(535, 32, 'http://localhost/lineup_campus/unite/deneme-unite', 0, '2025-04-21 09:49:12'),
(536, 32, 'http://localhost/lineup_campus/konu/deneme-konu', 29, '2025-04-21 09:49:41'),
(537, 32, 'http://localhost/lineup_campus/unite/deneme-unite', 5401, '2025-04-21 10:33:07'),
(538, 32, 'http://localhost/lineup_campus/ders/turkce', 0, '2025-04-21 10:33:08'),
(539, 32, 'http://localhost/lineup_campus/dashboard', 0, '2025-04-21 10:33:09'),
(540, 32, 'http://localhost/lineup_campus/ders/turkce', 0, '2025-04-21 10:33:09'),
(541, 32, 'http://localhost/lineup_campus/unite/deneme-unite', 0, '2025-04-21 10:33:10'),
(542, 32, 'http://localhost/lineup_campus/ders/turkce', 0, '2025-04-21 10:33:11'),
(543, 32, 'http://localhost/lineup_campus/unite/deneme-unite', 1, '2025-04-21 10:33:12'),
(544, 32, 'http://localhost/lineup_campus/konu/deneme-konu', 234, '2025-04-21 10:46:59'),
(545, 32, 'http://localhost/lineup_campus/konu/deneme-konu', 15, '2025-04-21 10:47:14'),
(546, 32, 'http://localhost/lineup_campus/unite/deneme-unite', 5, '2025-04-21 10:47:20'),
(547, 32, 'http://localhost/lineup_campus/unite/deneme-unite', 60, '2025-04-21 10:48:20'),
(548, 32, 'http://localhost/lineup_campus/konu/deneme-konu', 10, '2025-04-21 10:49:09'),
(549, 32, 'http://localhost/lineup_campus/konu/deneme-konu', 2, '2025-04-21 10:49:11'),
(550, 32, 'http://localhost/lineup_campus/unite/deneme-unite', 1, '2025-04-21 10:49:13'),
(551, 32, 'http://localhost/lineup_campus/konu/deneme-konu', 1, '2025-04-21 10:49:15'),
(552, 32, 'http://localhost/lineup_campus/unite/deneme-unite', 0, '2025-04-21 10:49:16'),
(553, 32, 'http://localhost/lineup_campus/konu/deneme-konu', 2, '2025-04-21 10:49:18'),
(554, 32, 'http://localhost/lineup_campus/unite/deneme-unite', 0, '2025-04-21 10:49:19'),
(555, 32, 'http://localhost/lineup_campus/konu/deneme-konu', 0, '2025-04-21 10:49:20'),
(556, 32, 'http://localhost/lineup_campus/unite/deneme-unite', 1, '2025-04-21 10:49:22'),
(557, 32, 'http://localhost/lineup_campus/ders/turkce', 0, '2025-04-21 10:49:23'),
(558, 32, 'http://localhost/lineup_campus/unite/deneme-unite', 1, '2025-04-21 10:49:24'),
(559, 32, 'http://localhost/lineup_campus/konu/deneme-konu', 122, '2025-04-21 10:51:26'),
(560, 32, 'http://localhost/lineup_campus/unite/deneme-unite', 2, '2025-04-21 10:51:29'),
(561, 32, 'http://localhost/lineup_campus/ders/turkce', 0, '2025-04-21 10:51:30'),
(562, 32, 'http://localhost/lineup_campus/unite/deneme-unite', 269, '2025-04-21 10:56:00'),
(563, 32, 'http://localhost/lineup_campus/konu/deneme-konu', 26, '2025-04-21 10:56:27'),
(564, 32, 'http://localhost/lineup_campus/unite/deneme-unite', 446, '2025-04-21 11:03:54'),
(565, 32, 'http://localhost/lineup_campus/unite/deneme-unite', 1, '2025-04-21 11:03:56'),
(566, 32, 'http://localhost/lineup_campus/konu/deneme-konu', 3, '2025-04-21 11:04:00'),
(567, 32, 'http://localhost/lineup_campus/konu/deneme-alt-konu', 3, '2025-04-21 11:04:03'),
(568, 32, 'http://localhost/lineup_campus/konu/deneme-alt-konu', 38, '2025-04-21 11:05:45'),
(569, 32, 'http://localhost/lineup_campus/konu/deneme-alt-konu', 3, '2025-04-21 11:05:48'),
(570, 32, 'http://localhost/lineup_campus/konu/deneme-konu', 106, '2025-04-21 11:05:50'),
(571, 32, 'http://localhost/lineup_campus/konu/deneme-konu', 1, '2025-04-21 11:05:51'),
(572, 32, 'http://localhost/lineup_campus/alt-konu/deneme-alt-konu', 46, '2025-04-21 11:06:38'),
(573, 32, 'http://localhost/lineup_campus/alt-konu/deneme-alt-konu', 36, '2025-04-21 11:07:42'),
(574, 32, 'http://localhost/lineup_campus/alt-konu/deneme-alt-konu', 72, '2025-04-21 11:08:54'),
(575, 32, 'http://localhost/lineup_campus/alt-konu/deneme-alt-konu', 32, '2025-04-21 11:09:27'),
(576, 32, 'http://localhost/lineup_campus/alt-konu/deneme-alt-konu', 203, '2025-04-21 11:12:50'),
(577, 32, 'http://localhost/lineup_campus/alt-konu/deneme-alt-konu', 1224, '2025-04-21 11:33:15'),
(578, 32, 'http://localhost/lineup_campus/alt-konu/deneme-alt-konu', 2921, '2025-04-21 12:21:57'),
(579, 32, 'http://localhost/lineup_campus/alt-konu/deneme-alt-konu', 1, '2025-04-21 12:21:59'),
(580, 32, 'http://localhost/lineup_campus/alt-konu/deneme-alt-konu', 52, '2025-04-21 12:22:51'),
(581, 32, 'http://localhost/lineup_campus/alt-konu/deneme-alt-konu', 1, '2025-04-21 12:22:53'),
(582, 32, 'http://localhost/lineup_campus/alt-konu/deneme-alt-konu', 0, '2025-04-21 12:22:53'),
(583, 32, 'http://localhost/lineup_campus/alt-konu/deneme-alt-konu', 149, '2025-04-21 12:25:23'),
(584, 32, 'http://localhost/lineup_campus/alt-konu/deneme-alt-konu', 1, '2025-04-21 12:25:25'),
(585, 32, 'http://localhost/lineup_campus/alt-konu/deneme-alt-konu', 26, '2025-04-21 12:25:51'),
(586, 32, 'http://localhost/lineup_campus/alt-konu/deneme-alt-konu', 41, '2025-04-21 12:26:33'),
(587, 32, 'http://localhost/lineup_campus/alt-konu/deneme-alt-konu', 92, '2025-04-21 12:28:06'),
(588, 32, 'http://localhost/lineup_campus/alt-konu/deneme-alt-konu', 10, '2025-04-21 12:28:17'),
(589, 32, 'http://localhost/lineup_campus/alt-konu/deneme-alt-konu', 54, '2025-04-21 12:29:12'),
(590, 32, 'http://localhost/lineup_campus/alt-konu/deneme-alt-konu', 20, '2025-04-21 12:29:32'),
(591, 32, 'http://localhost/lineup_campus/alt-konu/deneme-alt-konu', 3628, '2025-04-21 13:30:01'),
(592, 32, 'http://localhost/lineup_campus/alt-konu/deneme-alt-konu', 28, '2025-04-21 13:30:30'),
(593, 32, 'http://localhost/lineup_campus/alt-konu/deneme-alt-konu', 111, '2025-04-21 13:32:21'),
(594, 32, 'http://localhost/lineup_campus/alt-konu/deneme-alt-konu', 104, '2025-04-21 13:34:06'),
(595, 32, 'http://localhost/lineup_campus/alt-konu/deneme-alt-konu', 52, '2025-04-21 13:34:59'),
(596, 32, 'http://localhost/lineup_campus/unite/deneme-unite', 16668, '2025-04-21 15:11:02'),
(597, 32, 'http://localhost/lineup_campus/unite/deneme-unite', 57134, '2025-04-22 08:56:56'),
(598, 1, 'http://localhost/lineup_campus/dashboard', 410, '2025-04-24 10:06:33'),
(599, 1, 'http://localhost/lineup_campus/ogrenciler', 445, '2025-04-24 10:17:30'),
(600, 1, 'http://localhost/lineup_campus/ogrenciler', 3, '2025-04-24 10:17:35'),
(601, 1, 'http://localhost/lineup_campus/ogrenciler', 3903, '2025-04-24 11:22:35'),
(602, 1, 'http://localhost/lineup_campus/dashboard', 1, '2025-04-24 11:46:56'),
(603, 1, 'http://localhost/lineup_campus/dashboard', 31, '2025-04-24 11:47:31'),
(604, 1, 'http://localhost/lineup_campus/kullanici-yetkilendirme', 17, '2025-04-24 12:12:50'),
(605, 1, 'http://localhost/lineup_campus/kullanici-yetkilendirme', 107, '2025-04-24 12:14:39'),
(606, 1, 'http://localhost/lineup_campus/kullanici-yetkilendirme', 77, '2025-04-24 12:28:49'),
(607, 1, 'http://localhost/lineup_campus/kullanici-yetkilendirme', 1169, '2025-04-24 12:48:19'),
(608, 1, 'http://localhost/lineup_campus/kullanici-yetkilendirme', 2, '2025-04-24 12:48:22'),
(609, 1, 'http://localhost/lineup_campus/kullanici-yetkilendirme', 453, '2025-04-24 12:55:56'),
(610, 1, 'http://localhost/lineup_campus/kullanici-yetkilendirme', 23, '2025-04-24 12:56:20'),
(611, 1, 'http://localhost/lineup_campus/kullanici-yetkilendirme', 35, '2025-04-24 12:56:57'),
(612, 1, 'http://localhost/lineup_campus/kullanici-yetkilendirme', 64, '2025-04-24 12:58:01'),
(613, 1, 'http://localhost/lineup_campus/kullanici-yetkilendirme', 15, '2025-04-24 12:58:18');
INSERT INTO `timespend_lnp` (`id`, `user_id`, `sayfa_url`, `timeSpent`, `exitTime`) VALUES
(614, 1, 'http://localhost/lineup_campus/dashboard', 11356, '2025-04-24 13:15:52'),
(615, 1, 'http://localhost/lineup_campus/ogrenciler', 49, '2025-04-24 13:36:36'),
(616, 1, 'http://localhost/lineup_campus/ogrenciler', 638, '2025-04-24 13:47:16'),
(617, 1, 'http://localhost/lineup_campus/kullanici-gruplari', 6523, '2025-04-24 15:36:00'),
(618, 1, 'http://localhost/lineup_campus/uniteler', 8, '2025-04-24 15:36:10'),
(619, 1, 'http://localhost/lineup_campus/duyurular', 56, '2025-04-24 15:39:01'),
(620, 1, 'http://localhost/lineup_campus/duyurular', 41, '2025-04-24 15:39:43'),
(621, 1, 'http://localhost/lineup_campus/duyurular', 207, '2025-04-24 15:43:12'),
(622, 1, 'http://localhost/lineup_campus/duyurular', 16, '2025-04-24 15:43:30'),
(623, 1, 'http://localhost/lineup_campus/bildirimler', 18, '2025-04-24 15:51:20'),
(624, 1, 'http://localhost/lineup_campus/duyurular', 5, '2025-04-24 15:54:24'),
(625, 1, 'http://localhost/lineup_campus/bildirimler', 352, '2025-04-24 16:04:41'),
(626, 1, 'http://localhost/lineup_campus/kullanici-gruplari', 51, '2025-04-24 16:27:49'),
(627, 1, 'http://localhost/lineup_campus/kullanici-gruplari', 46, '2025-04-24 16:28:36'),
(628, 1, 'http://localhost/lineup_campus/kullanici-gruplari', 141, '2025-04-24 16:31:13'),
(629, 1, 'http://localhost/lineup_campus/kullanici-gruplari', 6, '2025-04-24 16:31:20'),
(630, 1, 'http://localhost/lineup_campus/dashboard', 15706, '2025-04-24 17:37:39'),
(631, 1, 'http://localhost/lineup_campus/kullanici-gruplari', 1, '2025-04-24 17:50:26'),
(632, 1, 'http://localhost/lineup_campus/kullanici-gruplari2', 61, '2025-04-24 17:51:28'),
(633, 1, 'http://localhost/lineup_campus/kullanici-gruplari2', 7, '2025-04-24 17:51:36'),
(634, 1, 'http://localhost/lineup_campus/kullanici-gruplari2', 14, '2025-04-24 17:51:51'),
(635, 1, 'http://localhost/lineup_campus/kullanici-gruplari2', 137, '2025-04-24 17:54:09'),
(636, 1, 'http://localhost/lineup_campus/kullanici-gruplari2', 499, '2025-04-24 18:02:30'),
(637, 1, 'http://localhost/lineup_campus/kullanici-gruplari2', 493, '2025-04-24 18:10:44'),
(638, 1, 'http://localhost/lineup_campus/kullanici-gruplari2', 131, '2025-04-24 18:12:56'),
(639, 1, 'http://localhost/lineup_campus/kullanici-gruplari2', 376, '2025-04-24 18:19:14'),
(640, 1, 'http://localhost/lineup_campus/apps/user-management/roles/view.html', 0, '2025-04-24 18:19:15'),
(641, 1, 'http://localhost/lineup_campus/kullanici-gruplari2', 2333, '2025-04-24 18:58:09'),
(642, 1, 'http://localhost/lineup_campus/kullanici-gruplari2', 32, '2025-04-24 18:58:43'),
(643, 1, 'http://localhost/lineup_campus/kullanici-gruplari2', 440, '2025-04-24 19:06:04'),
(644, 1, 'http://localhost/lineup_campus/kullanici-gruplari2', 3, '2025-04-24 19:06:09'),
(645, 1, 'http://localhost/lineup_campus/apps/user-management/roles/view.html', 7, '2025-04-24 19:06:17'),
(646, 1, 'http://localhost/lineup_campus/kullanici-gruplari2', 621, '2025-04-24 19:16:39'),
(647, 1, 'http://localhost/lineup_campus/dashboard', 1, '2025-04-24 19:16:42'),
(648, 1, 'http://localhost/lineup_campus/kullanici-gruplari', 14, '2025-04-24 19:16:57'),
(649, 1, 'http://localhost/lineup_campus/dashboard', 14, '2025-04-24 19:17:12'),
(650, 1, 'http://localhost/lineup_campus/kullanici-gruplari', 2, '2025-04-24 19:17:15'),
(651, 1, 'http://localhost/lineup_campus/kullanici-gruplari2', 29, '2025-04-24 19:17:45'),
(652, 1, 'http://localhost/lineup_campus/kullanici-gruplari', 157, '2025-04-24 19:20:27'),
(653, 1, 'http://localhost/lineup_campus/kullanici-gruplari', 4, '2025-04-24 19:21:02'),
(654, 1, 'http://localhost/lineup_campus/dashboard', 21, '2025-04-24 19:21:23'),
(655, 1, 'http://localhost/lineup_campus/kullanici-gruplari', 2, '2025-04-24 19:21:27'),
(656, 1, 'http://localhost/lineup_campus/kullanici-grup-detay', 56, '2025-04-24 19:22:24'),
(657, 1, 'http://localhost/lineup_campus/apps/user-management/users/view.html', 2, '2025-04-24 19:22:27'),
(658, 1, 'http://localhost/lineup_campus/kullanici-grup-detay', 76, '2025-04-24 19:23:44'),
(659, 1, 'http://localhost/lineup_campus/apps/user-management/roles/view.html', 1461, '2025-04-24 19:36:14'),
(660, 1, 'http://localhost/lineup_campus/apps/user-management/roles/list.html', 2, '2025-04-24 19:36:17'),
(661, 1, 'http://localhost/lineup_campus/okullar', 1, '2025-04-24 21:41:16'),
(662, 1, 'http://localhost/lineup_campus/dashboard', 0, '2025-04-24 21:41:18'),
(663, 1, 'http://localhost/lineup_campus/okullar', 3, '2025-04-24 21:41:22'),
(664, 1, 'http://localhost/lineup_campus/kullanici-gruplari', 9154, '2025-04-24 21:53:02'),
(665, 1, 'http://localhost/lineup_campus/kullanici-gruplari', 3, '2025-04-24 21:53:07'),
(666, 1, 'http://localhost/lineup_campus/apps/user-management/roles/view.html', 1, '2025-04-24 21:53:09'),
(667, 1, 'http://localhost/lineup_campus/kullanici-gruplari', 70, '2025-04-24 21:54:20'),
(668, 1, 'http://localhost/lineup_campus/kullanici-gruplari', 2, '2025-04-24 21:54:23'),
(669, 1, 'http://localhost/lineup_campus/kullanici-grup-detay/ogrenciler', 22, '2025-04-24 21:59:53'),
(670, 1, 'http://localhost/lineup_campus/kullanici-grup-detay/ogrenciler', 24, '2025-04-24 22:00:18'),
(671, 1, 'http://localhost/lineup_campus/kullanici-grup-detay/ogrenciler', 38, '2025-04-24 22:00:57'),
(672, 1, 'http://localhost/lineup_campus/kullanici-grup-detay/ogrenciler', 288, '2025-04-24 22:05:47'),
(673, 1, 'http://localhost/lineup_campus/kullanici-grup-detay/ogrenciler', 141, '2025-04-24 22:08:10'),
(674, 1, 'http://localhost/lineup_campus/kullanici-grup-detay/ogrenciler', 357, '2025-04-24 22:14:07'),
(675, 1, 'http://localhost/lineup_campus/kullanici-gruplari', 16608, '2025-04-24 22:14:29'),
(676, 1, 'http://localhost/lineup_campus/kullanici-grup-detay/ogrenciler', 91, '2025-04-24 22:15:39'),
(677, 1, 'http://localhost/lineup_campus/kullanici-grup-detay/ogrenciler', 16, '2025-04-24 22:15:56'),
(678, 1, 'http://localhost/lineup_campus/kullanici-grup-detay/ogrenciler', 62, '2025-04-24 22:17:00'),
(679, 1, 'http://localhost/lineup_campus/kullanici-grup-detay/ogrenciler', 48, '2025-04-24 22:17:50'),
(680, 1, 'http://localhost/lineup_campus/kullanici-grup-detay/ogrenciler', 122, '2025-04-24 22:19:53'),
(681, 1, 'http://localhost/lineup_campus/kullanici-grup-detay', 10697, '2025-04-24 22:22:02'),
(682, 1, 'http://localhost/lineup_campus/kullanici-grup-detay/ogrenciler', 140, '2025-04-24 22:22:15'),
(683, 1, 'http://localhost/lineup_campus/kullanici-grup-detay/ogrenciler', 27, '2025-04-24 22:22:44'),
(684, 1, 'http://localhost/lineup_campus/kullanici-grup-detay/ogrenciler', 51, '2025-04-24 22:23:36'),
(685, 1, 'http://localhost/lineup_campus/kullanici-gruplari', 3, '2025-04-24 22:23:41'),
(686, 1, 'http://localhost/lineup_campus/kullanici-grup-detay/super-admin', 4, '2025-04-24 22:23:46'),
(687, 1, 'http://localhost/lineup_campus/kullanici-gruplari', 1, '2025-04-24 22:23:49'),
(688, 1, 'http://localhost/lineup_campus/kullanici-grup-detay/okul-koordinatorleri', 4, '2025-04-24 22:23:54'),
(689, 1, 'http://localhost/lineup_campus/kullanici-gruplari', 2, '2025-04-24 22:23:57'),
(690, 1, 'http://localhost/lineup_campus/kullanici-grup-detay/ogretmenler', 12, '2025-04-24 22:24:11'),
(691, 1, 'http://localhost/lineup_campus/kullanici-gruplari', 1, '2025-04-24 22:24:13'),
(692, 1, 'http://localhost/lineup_campus/kullanici-grup-detay/veliler', 8, '2025-04-24 22:24:23'),
(693, 1, 'http://localhost/lineup_campus/kullanici-gruplari', 36, '2025-04-24 22:25:00'),
(694, 1, 'http://localhost/lineup_campus/kullanici-grup-detay/ogrenciler', 26, '2025-04-24 22:25:28'),
(695, 1, 'http://localhost/lineup_campus/kullanici-grup-detay/ogrenciler', 741, '2025-04-24 22:37:51'),
(696, 1, 'http://localhost/lineup_campus/kullanici-gruplari', 50, '2025-04-24 22:38:42'),
(697, 1, 'http://localhost/lineup_campus/kullanici-grup-detay/ogrenciler', 2, '2025-04-24 22:38:45'),
(698, 1, 'http://localhost/lineup_campus/kullanici-grup-detay/ogrencilers', 1, '2025-04-24 22:38:48'),
(699, 1, 'http://localhost/lineup_campus/kullanici-grup-detay/ogrenciler', 1, '2025-04-24 22:38:50'),
(700, 1, 'http://localhost/lineup_campus/kullanici-grup-detay/ogrenciler', 5, '2025-04-24 22:38:58'),
(701, 1, 'http://localhost/lineup_campus/kullanici-grup-detay', 3, '2025-04-24 22:39:03'),
(702, 1, 'http://localhost/lineup_campus/kullanici-grup-detay/ogrenciler', 5, '2025-04-24 22:39:09'),
(703, 1, 'http://localhost/lineup_campus/kullanici-grup-detay/ogrencilers', 3, '2025-04-24 22:39:13'),
(704, 1, 'http://localhost/lineup_campus/kullanici-grup-detay/ogrenciler', 73, '2025-04-24 22:40:28'),
(705, 1, 'http://localhost/lineup_campus/kullanici-grup-detay/ogrenciler', 36241, '2025-04-25 08:44:31'),
(706, 1, 'http://localhost/lineup_campus/kullanici-grup-detay', 37494, '2025-04-25 08:46:57'),
(707, 1, 'http://localhost/lineup_campus/kullanici-grup-detay', 3, '2025-04-25 08:47:02'),
(708, 1, 'http://localhost/lineup_campus/kullanici-grup-detay/ogrenciler', 536, '2025-04-25 08:53:27'),
(709, 1, 'http://localhost/lineup_campus/kullanici-grup-detay/ogrenciler', 46, '2025-04-25 08:54:15'),
(710, 1, 'http://localhost/lineup_campus/kullanici-grup-detay/ogrenciler', 648, '2025-04-25 09:05:04'),
(711, 1, 'http://localhost/lineup_campus/kullanici-grup-detay/ogrenciler', 260, '2025-04-25 09:09:26'),
(712, 1, 'http://localhost/lineup_campus/kullanici-grup-detay/ogrenciler', 43, '2025-04-25 09:10:10'),
(713, 1, 'http://localhost/lineup_campus/kullanici-grup-detay/ogrenciler', 860, '2025-04-25 09:24:32'),
(714, 1, 'http://localhost/lineup_campus/kullanici-grup-detay/ogrenciler', 337, '2025-04-25 09:30:10'),
(715, 1, 'http://localhost/lineup_campus/kullanici-grup-detay/ogrenciler', 108, '2025-04-25 09:32:00'),
(716, 1, 'http://localhost/lineup_campus/kullanici-grup-detay/ogrenciler', 2206, '2025-04-25 10:08:47'),
(717, 1, 'http://localhost/lineup_campus/kullanici-grup-detay/ogrenciler', 4476, '2025-04-25 11:23:25'),
(718, 1, 'http://localhost/lineup_campus/kullanici-grup-detay/ogrenciler', 442, '2025-04-25 11:30:48'),
(719, 1, 'http://localhost/lineup_campus/kullanici-grup-detay/ogrenciler', 3734, '2025-04-25 12:33:03'),
(720, 1, 'http://localhost/lineup_campus/kullanici-grup-detay/ogrenciler', 49, '2025-04-25 12:33:54'),
(721, 1, 'http://localhost/lineup_campus/kullanici-grup-detay/ogrenciler', 12, '2025-04-25 12:34:09'),
(722, 1, 'http://localhost/lineup_campus/kullanici-grup-detay/ogrenciler', 7, '2025-04-25 12:34:17'),
(723, 1, 'http://localhost/lineup_campus/kullanici-grup-detay/ogrenciler', 33, '2025-04-25 12:34:53'),
(724, 1, 'http://localhost/lineup_campus/kullanici-grup-detay/ogrenciler', 3730, '2025-04-25 13:37:05'),
(725, 1, 'http://localhost/lineup_campus/kullanici-grup-detay/ogrenciler', 2960, '2025-04-25 14:26:28'),
(726, 1, 'http://localhost/lineup_campus/kullanici-grup-detay/ogrenciler', 124, '2025-04-25 14:28:34'),
(727, 1, 'http://localhost/lineup_campus/kullanici-grup-detay/ogrenciler', 75, '2025-04-25 14:29:51'),
(728, 1, 'http://localhost/lineup_campus/kullanici-grup-detay/ogrenciler', 2, '2025-04-25 14:29:56'),
(729, 1, 'http://localhost/lineup_campus/kullanici-grup-detay/ogrenciler', 286, '2025-04-25 14:34:43'),
(730, 1, 'http://localhost/lineup_campus/kullanici-gruplari', 3, '2025-04-25 14:34:48'),
(731, 1, 'http://localhost/lineup_campus/kullanici-grup-detay/ogrenciler', 160, '2025-04-25 14:37:29'),
(732, 1, 'http://localhost/lineup_campus/kullanici-grup-detay/ogrenciler', 7, '2025-04-25 14:37:38'),
(733, 1, 'http://localhost/lineup_campus/kullanici-gruplari', 29, '2025-04-25 14:38:09'),
(734, 1, 'http://localhost/lineup_campus/kullanici-grup-detay/ogrenciler', 433, '2025-04-25 14:45:24'),
(735, 1, 'http://localhost/lineup_campus/kullanici-grup-detay/ogrenciler', 9, '2025-04-25 14:45:34'),
(736, 1, 'http://localhost/lineup_campus/kullanici-gruplari', 0, '2025-04-25 14:45:36'),
(737, 1, 'http://localhost/lineup_campus/kullanici-grup-detay/ogrenciler', 71, '2025-04-25 14:46:48'),
(738, 1, 'http://localhost/lineup_campus/kullanici-grup-detay/ogrenciler', 27, '2025-04-25 14:47:17'),
(739, 1, 'http://localhost/lineup_campus/kullanici-grup-detay/ogrenciler', 43, '2025-04-25 14:48:01'),
(740, 1, 'http://localhost/lineup_campus/kullanici-grup-detay/ogrenciler', 9, '2025-04-25 14:48:12'),
(741, 1, 'http://localhost/lineup_campus/kullanici-grup-detay/ogrenciler', 19, '2025-04-25 14:48:33'),
(742, 1, 'http://localhost/lineup_campus/kullanici-grup-detay/ogrenciler', 251, '2025-04-25 14:52:44'),
(743, 1, 'http://localhost/lineup_campus/kullanici-grup-detay/ogrenciler', 28, '2025-04-25 14:53:14'),
(744, 1, 'http://localhost/lineup_campus/kullanici-grup-detay/ogrenciler', 79, '2025-04-25 14:54:34'),
(745, 1, 'http://localhost/lineup_campus/kullanici-gruplari', 60881, '2025-04-25 14:55:18'),
(746, 1, 'http://localhost/lineup_campus/kullanici-grup-detay/ogrenciler', 49, '2025-04-25 14:55:24'),
(747, 1, 'http://localhost/lineup_campus/kullanici-grup-detay/ogrenciler', 142, '2025-04-25 14:57:48'),
(748, 1, 'http://localhost/lineup_campus/kullanici-grup-detay/ogrenciler', 23, '2025-04-25 14:58:12'),
(749, 1, 'http://localhost/lineup_campus/kullanici-grup-detay/ogrenciler', 101, '2025-04-25 14:59:55'),
(750, 1, 'http://localhost/lineup_campus/kullanici-grup-detay/ogrenciler', 107, '2025-04-25 15:01:43'),
(751, 1, 'http://localhost/lineup_campus/kullanici-grup-detay/ogrenciler', 465, '2025-04-25 15:09:30'),
(752, 1, 'http://localhost/lineup_campus/kullanici-grup-detay/ogrenciler', 1, '2025-04-25 15:09:33'),
(753, 1, 'http://localhost/lineup_campus/kullanici-grup-detay/ogrenciler', 10, '2025-04-25 15:09:44'),
(754, 1, 'http://localhost/lineup_campus/kullanici-grup-detay/ogrenciler', 12, '2025-04-25 15:09:57'),
(755, 1, 'http://localhost/lineup_campus/kullanici-gruplari', 41, '2025-04-25 15:10:39'),
(756, 1, 'http://localhost/lineup_campus/kullanici-gruplari', 1, '2025-04-25 15:10:42'),
(757, 1, 'http://localhost/lineup_campus/okul-detay/lineup-campus', 63075, '2025-04-25 15:12:38'),
(758, 1, 'http://localhost/lineup_campus/kullanici-grup-detay/ogrenciler', 117, '2025-04-25 15:12:40'),
(759, 1, 'http://localhost/lineup_campus/kullanici-grup-detay/ogrenciler', 12, '2025-04-25 15:12:54'),
(760, 1, 'http://localhost/lineup_campus/kullanici-gruplari', 2, '2025-04-25 15:12:57'),
(761, 1, 'http://localhost/lineup_campus/kullanici-grup-detay/ogrenciler', 7, '2025-04-25 15:13:05'),
(762, 1, 'http://localhost/lineup_campus/kullanici-gruplari', 2, '2025-04-25 15:13:09'),
(763, 1, 'http://localhost/lineup_campus/kullanici-grup-detay/ogrenciler', 9, '2025-04-25 15:13:20'),
(764, 1, 'http://localhost/lineup_campus/kullanici-gruplari', 907, '2025-04-25 15:28:28'),
(765, 1, 'http://localhost/lineup_campus/kullanici-gruplari', 1, '2025-04-25 15:28:32'),
(766, 1, 'http://localhost/lineup_campus/kullanici-grup-detay/ogrenciler', 116, '2025-04-25 15:30:30'),
(767, 1, 'http://localhost/lineup_campus/kullanici-grup-detay/ogrenciler', 10, '2025-04-25 15:30:42'),
(768, 1, 'http://localhost/lineup_campus/kullanici-gruplari', 2, '2025-04-25 15:30:45'),
(769, 1, 'http://localhost/lineup_campus/kullanici-grup-detay/ogrenciler', 397, '2025-04-25 15:37:23'),
(770, 1, 'http://localhost/lineup_campus/kullanici-grup-detay/ogrenciler', 8, '2025-04-25 15:37:32'),
(771, 1, 'http://localhost/lineup_campus/kullanici-gruplari', 1, '2025-04-25 15:37:35'),
(772, 1, 'http://localhost/lineup_campus/kullanici-grup-detay/ogrenciler', 88, '2025-04-25 15:39:04'),
(773, 1, 'http://localhost/lineup_campus/kullanici-gruplari', 1931, '2025-04-25 16:11:17'),
(774, 1, 'http://localhost/lineup_campus/kullanici-grup-detay/ogrenciler', 14, '2025-04-25 16:11:32'),
(775, 1, 'http://localhost/lineup_campus/kullanici-gruplari', 2, '2025-04-25 16:11:36'),
(776, 1, 'http://localhost/lineup_campus/kullanici-grup-detay/ogrenciler', 289, '2025-04-25 16:16:26'),
(777, 1, 'http://localhost/lineup_campus/kullanici-grup-detay/ogrenciler', 11, '2025-04-25 16:16:38'),
(778, 1, 'http://localhost/lineup_campus/kullanici-gruplari', 499, '2025-04-25 16:24:59'),
(779, 1, 'http://localhost/lineup_campus/kullanici-gruplari', 12, '2025-04-25 16:25:12'),
(780, 1, 'http://localhost/lineup_campus/kullanici-grup-detay/ogrenciler', 10, '2025-04-25 16:25:23'),
(781, 1, 'http://localhost/lineup_campus/kullanici-gruplari', 7, '2025-04-25 16:25:31'),
(782, 1, 'http://localhost/lineup_campus/kullanici-grup-detay/ogrenciler', 7, '2025-04-25 16:25:40'),
(783, 1, 'http://localhost/lineup_campus/kullanici-gruplari', 1, '2025-04-25 16:25:42'),
(784, 1, 'http://localhost/lineup_campus/kullanici-grup-detay/ogrenciler', 30, '2025-04-25 16:26:14'),
(785, 1, 'http://localhost/lineup_campus/kullanici-grup-detay/ogrenciler', 41, '2025-04-25 16:26:56'),
(786, 1, 'http://localhost/lineup_campus/kullanici-gruplari', 468, '2025-04-25 16:34:45'),
(787, 1, 'http://localhost/lineup_campus/kullanici-grup-detay/ogrenciler', 37, '2025-04-25 16:35:24'),
(788, 1, 'http://localhost/lineup_campus/kullanici-gruplari', 2, '2025-04-25 16:35:27'),
(789, 1, 'http://localhost/lineup_campus/kullanici-grup-detay/ogrenciler', 11, '2025-04-25 16:35:40'),
(790, 1, 'http://localhost/lineup_campus/kullanici-gruplari', 3, '2025-04-25 16:35:44'),
(791, 1, 'http://localhost/lineup_campus/kullanici-grup-detay/ogrenciler', 8, '2025-04-25 16:35:53'),
(792, 1, 'http://localhost/lineup_campus/kullanici-gruplari', 445, '2025-04-25 16:43:19'),
(793, 1, 'http://localhost/lineup_campus/kullanici-grup-detay/ogrenciler', 873, '2025-04-25 16:57:54'),
(794, 1, 'http://localhost/lineup_campus/kullanici-grup-detay/ogrenciler', 8, '2025-04-25 16:58:04'),
(795, 1, 'http://localhost/lineup_campus/kullanici-gruplari', 2, '2025-04-25 16:58:08'),
(796, 1, 'http://localhost/lineup_campus/kullanici-grup-detay/ogrenciler', 32, '2025-04-25 16:58:41'),
(797, 1, 'http://localhost/lineup_campus/kullanici-gruplari', 19, '2025-04-25 16:59:01'),
(798, 1, 'http://localhost/lineup_campus/kullanici-grup-detay/ogrenciler', 16, '2025-04-25 16:59:18'),
(799, 1, 'http://localhost/lineup_campus/apps/user-management/roles/view.html', 82237, '2025-04-25 17:01:40'),
(800, 1, 'http://localhost/lineup_campus/kullanici-gruplari', 1791, '2025-04-25 17:29:10'),
(801, 1, 'http://localhost/lineup_campus/kullanici-grup-detay/ogrenciler', 2389, '2025-04-25 18:09:01'),
(802, 1, 'http://localhost/lineup_campus/kullanici-grup-detay/ogrenciler', 15, '2025-04-25 18:09:17'),
(803, 1, 'http://localhost/lineup_campus/kullanici-gruplari', 4, '2025-04-25 18:09:23'),
(804, 1, 'http://localhost/lineup_campus/kullanici-grup-detay/ogrenciler', 15, '2025-04-25 18:09:40'),
(805, 1, 'http://localhost/lineup_campus/kullanici-gruplari', 1, '2025-04-25 18:09:42'),
(806, 1, 'http://localhost/lineup_campus/kullanici-grup-detay/ogrenciler', 15, '2025-04-25 18:09:59'),
(807, 1, 'http://localhost/lineup_campus/kullanici-gruplari', 136, '2025-04-25 18:12:16'),
(808, 1, 'http://localhost/lineup_campus/kullanici-gruplari', 0, '2025-04-25 18:12:18'),
(809, 1, 'http://localhost/lineup_campus/kullanici-grup-detay/ogrenciler', 14, '2025-04-25 18:12:33'),
(810, 1, 'http://localhost/lineup_campus/kullanici-gruplari', 7, '2025-04-25 18:12:41'),
(811, 1, 'http://localhost/lineup_campus/kullanici-grup-detay/ogrenciler', 8, '2025-04-25 18:12:51'),
(812, 1, 'http://localhost/lineup_campus/kullanici-gruplari', 344, '2025-04-25 18:18:36'),
(813, 1, 'http://localhost/lineup_campus/kullanici-gruplari', 13508, '2025-04-25 18:40:28'),
(814, 1, 'http://localhost/lineup_campus/kullanici-gruplari', 1, '2025-04-25 18:40:30'),
(815, 1, 'http://localhost/lineup_campus/kullanici-grup-detay/ogrenciler', 19, '2025-04-25 18:40:51'),
(816, 1, 'http://localhost/lineup_campus/kullanici-gruplari', 21, '2025-04-25 18:41:14'),
(817, 1, 'http://localhost/lineup_campus/kullanici-grup-detay/ogrenciler', 15, '2025-04-25 18:41:30'),
(818, 1, 'http://localhost/lineup_campus/kullanici-gruplari', 126, '2025-04-25 18:43:37'),
(819, 1, 'http://localhost/lineup_campus/kullanici-grup-detay/ogrenciler', 12, '2025-04-25 18:43:51'),
(820, 1, 'http://localhost/lineup_campus/kullanici-gruplari', 132, '2025-04-25 18:46:05'),
(821, 1, 'http://localhost/lineup_campus/kullanici-grup-detay/ogrenciler', 13, '2025-04-25 18:46:19'),
(822, 1, 'http://localhost/lineup_campus/kullanici-gruplari', 23, '2025-04-25 18:46:44'),
(823, 1, 'http://localhost/lineup_campus/kullanici-grup-detay/ogrenciler', 13, '2025-04-25 18:46:58'),
(824, 1, 'http://localhost/lineup_campus/kullanici-gruplari', 943, '2025-04-25 19:02:42'),
(825, 1, 'http://localhost/lineup_campus/kullanici-grup-detay/ogrenciler', 9, '2025-04-25 19:02:52'),
(826, 1, 'http://localhost/lineup_campus/kullanici-gruplari', 1, '2025-04-25 19:02:55'),
(827, 1, 'http://localhost/lineup_campus/kullanici-grup-detay/ogrenciler', 9, '2025-04-25 19:03:04'),
(828, 1, 'http://localhost/lineup_campus/kullanici-gruplari', 265, '2025-04-25 19:07:31'),
(829, 1, 'http://localhost/lineup_campus/kullanici-gruplari', 1, '2025-04-25 19:07:33'),
(830, 1, 'http://localhost/lineup_campus/kullanici-grup-detay/ogrenciler', 109, '2025-04-25 19:09:24'),
(831, 1, 'http://localhost/lineup_campus/kullanici-gruplari', 107, '2025-04-25 19:11:12'),
(832, 1, 'http://localhost/lineup_campus/kullanici-grup-detay/ogrenciler', 11, '2025-04-25 19:11:24'),
(833, 1, 'http://localhost/lineup_campus/kullanici-gruplari', 431, '2025-04-25 19:18:37'),
(834, 1, 'http://localhost/lineup_campus/kullanici-grup-detay/ogrenciler', 9, '2025-04-25 19:18:47'),
(835, 1, 'http://localhost/lineup_campus/kullanici-gruplari', 34, '2025-04-25 19:19:22'),
(836, 1, 'http://localhost/lineup_campus/kullanici-grup-detay/ogrenciler', 10, '2025-04-25 19:19:34'),
(837, 1, 'http://localhost/lineup_campus/kullanici-gruplari', 4, '2025-04-25 19:19:39'),
(838, 1, 'http://localhost/lineup_campus/kullanici-grup-detay/ogrenciler', 9, '2025-04-25 19:19:49'),
(839, 1, 'http://localhost/lineup_campus/kullanici-gruplari', 410, '2025-04-25 19:26:41'),
(840, 1, 'http://localhost/lineup_campus/kullanici-grup-detay/veliler', 8, '2025-04-25 19:26:50'),
(841, 1, 'http://localhost/lineup_campus/kullanici-gruplari', 2, '2025-04-25 19:26:54'),
(842, 1, 'http://localhost/lineup_campus/kullanici-grup-detay/veliler', 9, '2025-04-25 19:27:04'),
(843, 1, 'http://localhost/lineup_campus/kullanici-gruplari', 65584, '2025-04-26 13:40:10'),
(844, 1, 'http://localhost/lineup_campus/kullanici-grup-detay/super-admin', 8, '2025-04-26 13:40:20'),
(845, 1, 'http://localhost/lineup_campus/kullanici-gruplari', 2, '2025-04-26 13:40:24'),
(846, 1, 'http://localhost/lineup_campus/kullanici-gruplari', 7, '2025-04-26 13:40:32'),
(847, 1, 'http://localhost/lineup_campus/kullanici-grup-detay/super-admin', 149, '2025-04-26 13:43:02'),
(848, 1, 'http://localhost/lineup_campus/kullanici-grup-detay/super-admin', 10, '2025-04-26 13:43:14'),
(849, 1, 'http://localhost/lineup_campus/alt-konular', 1, '2025-04-26 13:44:31'),
(850, 1, 'http://localhost/lineup_campus/altkonu-ekle', 9922, '2025-04-26 16:29:54'),
(851, 1, 'http://localhost/lineup_campus/test-ekle', 220, '2025-04-26 16:33:35'),
(852, 1, 'http://localhost/lineup_campus/kullanici-yetkilendirme', 186713, '2025-04-26 16:50:12'),
(853, 1, 'http://localhost/lineup_campus/ogretmenler', 2, '2025-04-26 16:50:16'),
(854, 1, 'http://localhost/lineup_campus/kullanici-gruplari', 173972, '2025-04-26 16:50:53'),
(855, 1, 'http://localhost/lineup_campus/test-ekle', 1123, '2025-04-26 16:52:20'),
(856, 1, 'http://localhost/lineup_campus/test-ekle', 156, '2025-04-26 16:54:57'),
(857, 1, 'http://localhost/lineup_campus/test-ekle', 23, '2025-04-26 16:55:22'),
(858, 1, 'http://localhost/lineup_campus/test-ekle', 614, '2025-04-26 17:05:37'),
(859, 1, 'http://localhost/lineup_campus/test-ekle', 65, '2025-04-26 17:06:43'),
(860, 1, 'http://localhost/lineup_campus/test-ekle', 796, '2025-04-26 17:20:01'),
(861, 1, 'http://localhost/lineup_campus/test-ekle', 337, '2025-04-26 17:25:39'),
(862, 1, 'http://localhost/lineup_campus/test-ekle', 30, '2025-04-26 17:26:11'),
(863, 1, 'http://localhost/lineup_campus/test-ekle', 18, '2025-04-26 17:26:31'),
(864, 1, 'http://localhost/lineup_campus/test-ekle', 2472, '2025-04-26 18:07:44'),
(865, 1, 'http://localhost/lineup_campus/test-ekle', 292, '2025-04-26 18:12:37'),
(866, 1, 'http://localhost/lineup_campus/test-ekle', 10, '2025-04-26 18:12:49'),
(867, 1, 'http://localhost/lineup_campus/ogrenciler', 158316, '2025-04-26 18:13:06'),
(868, 1, 'http://localhost/lineup_campus/alt-konular', 2, '2025-04-26 18:13:09'),
(869, 1, 'http://localhost/lineup_campus/altkonu-ekle', 357, '2025-04-26 18:19:08'),
(870, 1, 'http://localhost/lineup_campus/test-ekle', 381, '2025-04-26 18:19:12'),
(871, 1, 'http://localhost/lineup_campus/test-ekle', 4337, '2025-04-26 19:31:29'),
(872, 1, 'http://localhost/lineup_campus/test-ekle', 227, '2025-04-26 19:35:17'),
(873, 1, 'http://localhost/lineup_campus/test-ekle', 79, '2025-04-26 19:36:38'),
(874, 1, 'http://localhost/lineup_campus/test-ekle', 37, '2025-04-26 19:45:43'),
(875, 1, 'http://localhost/lineup_campus/kullanici-gruplari', 22874, '2025-04-26 20:04:29'),
(876, 1, 'http://localhost/lineup_campus/test-ekle', 2031, '2025-04-26 20:19:36'),
(877, 1, 'http://localhost/lineup_campus/testler', 49, '2025-04-26 20:20:26'),
(878, 1, 'http://localhost/lineup_campus/testler', 372, '2025-04-26 20:26:59'),
(879, 1, 'http://localhost/lineup_campus/testler', 508, '2025-04-26 20:35:28'),
(880, 1, 'http://localhost/lineup_campus/testler', 43, '2025-04-26 20:36:30'),
(881, 1, 'http://localhost/lineup_campus/testler', 60, '2025-04-26 20:37:32'),
(882, 1, 'http://localhost/lineup_campus/kullanici-gruplari', 2, '2025-04-26 20:37:49'),
(883, 32, 'http://localhost/lineup_campus/dashboard', 2, '2025-04-26 20:54:23'),
(884, 32, 'http://localhost/lineup_campus/ders/turkce', 1, '2025-04-26 20:54:25'),
(885, 32, 'http://localhost/lineup_campus/unite/deneme-unite', 0, '2025-04-26 20:54:26'),
(886, 32, 'http://localhost/lineup_campus/alt-konu/deneme-alt-konu', 1, '2025-04-26 20:54:36'),
(887, 32, 'http://localhost/lineup_campus/konu/deneme-konu', 1, '2025-04-26 20:56:58'),
(888, 32, 'http://localhost/lineup_campus/alt-konu/deneme-ekle-konu', 20, '2025-04-26 20:57:54'),
(889, 32, 'http://localhost/lineup_campus/dashboard', 1, '2025-04-26 20:57:56'),
(890, 32, 'http://localhost/lineup_campus/ders/turkce', 1, '2025-04-26 20:57:58'),
(891, 32, 'http://localhost/lineup_campus/unite/deneme-unite', 0, '2025-04-26 20:57:59'),
(892, 1, 'http://localhost/lineup_campus/testler', 13022, '2025-04-27 00:14:35'),
(893, 1, 'http://localhost/lineup_campus/testler', 16, '2025-04-27 00:14:53'),
(894, 1, 'http://localhost/lineup_campus/testler', 105, '2025-04-27 00:16:40'),
(895, 1, 'http://localhost/lineup_campus/kullanici-grup-detay/ogrenciler', 13197, '2025-04-27 00:17:47'),
(896, 1, 'http://localhost/lineup_campus/ogrenciler', 18, '2025-04-27 00:19:23'),
(897, 1, 'http://localhost/lineup_campus/test-detay/deneme-ekle-konu', 182, '2025-04-27 00:19:46'),
(898, 1, 'http://localhost/lineup_campus/kullanici-grup-detay/ogrenciler', 13317, '2025-04-27 00:19:48'),
(899, 1, 'http://localhost/lineup_campus/test-detay/deneme-ekle-konu', 1944, '2025-04-27 00:52:12'),
(900, 1, 'http://localhost/lineup_campus/test-detay/deneme-ekle-konu', 2383, '2025-04-27 01:45:06'),
(901, 1, 'http://localhost/lineup_campus/test-detay/deneme-ekle-konu', 27, '2025-04-27 01:49:10'),
(902, 1, 'http://localhost/lineup_campus/test-detay/deneme-ekle-konu', 27, '2025-04-27 01:49:38'),
(903, 1, 'http://localhost/lineup_campus/test-detay/deneme-ekle-konu', 6, '2025-04-27 01:49:45'),
(904, 1, 'http://localhost/lineup_campus/test-detay/deneme-ekle-konu', 307, '2025-04-27 01:54:55'),
(905, 1, 'http://localhost/lineup_campus/test-detay/deneme-ekle-konu', 157, '2025-04-27 01:57:33'),
(906, 1, 'http://localhost/lineup_campus/test-detay/deneme-ekle-konu', 9, '2025-04-27 01:57:45'),
(907, 1, 'http://localhost/lineup_campus/test-detay/deneme-ekle-konu', 19, '2025-04-27 01:58:05'),
(908, 1, 'http://localhost/lineup_campus/test-detay/deneme-ekle-konu', 9, '2025-04-27 01:58:15'),
(909, 1, 'http://localhost/lineup_campus/test-detay/deneme-ekle-konu', 303, '2025-04-27 02:03:19'),
(910, 1, 'http://localhost/lineup_campus/test-detay/deneme-ekle-konu', 57, '2025-04-27 02:04:51'),
(911, 1, 'http://localhost/lineup_campus/test-detay/deneme-ekle-konu', 39850, '2025-04-27 13:09:03'),
(912, 1, 'http://localhost/lineup_campus/test-detay/deneme-ekle-konu', 44, '2025-04-27 13:09:49'),
(913, 1, 'http://localhost/lineup_campus/test-detay/deneme-ekle-konu', 36, '2025-04-27 13:10:26'),
(914, 1, 'http://localhost/lineup_campus/test-detay/deneme-ekle-konu', 27, '2025-04-27 13:10:54'),
(915, 1, 'http://localhost/lineup_campus/test-detay/deneme-ekle-konu', 1653, '2025-04-27 13:38:29'),
(916, 1, 'http://localhost/lineup_campus/test-detay/deneme-ekle-konu', 36, '2025-04-27 13:40:25'),
(917, 1, 'http://localhost/lineup_campus/test-detay/deneme-ekle-konu', 337, '2025-04-27 13:46:02'),
(918, 1, 'http://localhost/lineup_campus/test-detay/deneme-ekle-konu', 49, '2025-04-27 13:46:52'),
(919, 1, 'http://localhost/lineup_campus/test-detay/deneme-ekle-konu', 207, '2025-04-27 13:50:21'),
(920, 1, 'http://localhost/lineup_campus/test-detay/deneme-ekle-konu', 523, '2025-04-27 13:59:05'),
(921, 1, 'http://localhost/lineup_campus/test-detay/deneme-ekle-konu', 119, '2025-04-27 14:01:22'),
(922, 1, 'http://localhost/lineup_campus/apps/user-management/users/view.html', 38, '2025-04-27 14:02:01'),
(923, 1, 'http://localhost/lineup_campus/test-detay/deneme-ekle-konu', 38, '2025-04-27 14:02:41'),
(924, 1, 'http://localhost/lineup_campus/test-detay/deneme-ekle-konu', 25, '2025-04-27 14:03:06'),
(925, 1, 'http://localhost/lineup_campus/test-detay/deneme-ekle-konu', 1389, '2025-04-27 14:26:25'),
(926, 1, 'http://localhost/lineup_campus/test-detay/deneme-ekle-konu', 1, '2025-04-27 14:26:27'),
(927, 1, 'http://localhost/lineup_campus/dashboard', 1, '2025-04-27 14:26:30'),
(928, 1, 'http://localhost/lineup_campus/test-detay/deneme-ekle-konu', 35, '2025-04-27 14:27:05'),
(929, 1, 'http://localhost/lineup_campus/test-detay/deneme-ekle-konu', 144, '2025-04-27 14:29:31'),
(930, 1, 'http://localhost/lineup_campus/test-detay/deneme-ekle-konu', 2746, '2025-04-27 15:15:19'),
(931, 1, 'http://localhost/lineup_campus/testler', 54792, '2025-04-27 15:29:53'),
(932, 1, 'http://localhost/lineup_campus/test-detay-ogrenci', 22, '2025-04-27 15:30:17'),
(933, 1, 'http://localhost/lineup_campus/test-detay-ogrenci', 5, '2025-04-27 15:30:24'),
(934, 1, 'http://localhost/lineup_campus/test-detay-ogrenci', 13, '2025-04-27 15:30:38'),
(935, 1, 'http://localhost/lineup_campus/test-detay-ogrenci?id=2', 23, '2025-04-27 15:31:03'),
(936, 1, 'http://localhost/lineup_campus/test-detay/deneme-ekle-konu', 5613, '2025-04-27 16:48:53'),
(937, 1, 'http://localhost/lineup_campus/test-detay-ogrenci/deneme-ekle-konu', 6, '2025-04-27 16:49:00'),
(938, 1, 'http://localhost/lineup_campus/test-detay-ogrenci/deneme-ekle-konu?id=32', 37, '2025-04-27 16:49:38'),
(939, 1, 'http://localhost/lineup_campus/test-detay-ogrenci/deneme-ekle-konu?id=32', 23, '2025-04-27 16:50:02'),
(940, 1, 'http://localhost/lineup_campus/test-detay-ogrenci/deneme-ekle-konu?id=32', 9, '2025-04-27 16:50:12'),
(941, 1, 'http://localhost/lineup_campus/test-detay-ogrenci/deneme-ekle-konu?id=32', 395, '2025-04-27 16:56:50'),
(942, 1, 'http://localhost/lineup_campus/test-detay-ogrenci/deneme-ekle-konu?id=32', 15100, '2025-04-27 21:08:30'),
(943, 1, 'http://localhost/lineup_campus/test-detay-ogrenci/deneme-ekle-konu?id=32', 25, '2025-04-27 21:08:56'),
(944, 1, 'http://localhost/lineup_campus/test-detay-ogrenci/deneme-ekle-konu?id=32', 64, '2025-04-27 21:10:02'),
(945, 1, 'http://localhost/lineup_campus/test-detay-ogrenci/deneme-ekle-konu?id=32', 931, '2025-04-27 21:25:35'),
(946, 1, 'http://localhost/lineup_campus/testler', 4, '2025-04-27 21:25:48'),
(947, 1, 'http://localhost/lineup_campus/test-detay/deneme-ekle-konu', 2, '2025-04-27 21:25:50'),
(948, 1, 'http://localhost/lineup_campus/test-detay-ogrenci/deneme-ekle-konu?id=32', 365, '2025-04-27 21:31:40'),
(949, 1, 'http://localhost/lineup_campus/test-detay-ogrenci/deneme-ekle-konu?id=32', 66, '2025-04-27 21:33:22'),
(950, 1, 'http://localhost/lineup_campus/test-detay-ogrenci/deneme-ekle-konu?id=32', 50, '2025-04-27 21:34:41'),
(951, 1, 'http://localhost/lineup_campus/alt-konular', 17200, '2025-04-27 21:35:24'),
(952, 1, 'http://localhost/lineup_campus/testler', 2, '2025-04-27 21:35:27'),
(953, 1, 'http://localhost/lineup_campus/test-detay-ogrenci/deneme-ekle-konu?id=32', 85, '2025-04-27 21:36:07'),
(954, 1, 'http://localhost/lineup_campus/test-detay-ogrenci/deneme-ekle-konu?id=32', 274, '2025-04-27 21:40:42'),
(955, 1, 'http://localhost/lineup_campus/test-detay-ogrenci/deneme-ekle-konu?id=32', 47, '2025-04-27 21:43:35'),
(956, 1, 'http://localhost/lineup_campus/test-detay-ogrenci/deneme-ekle-konu?id=32', 14, '2025-04-27 21:43:51'),
(957, 1, 'http://localhost/lineup_campus/test-detay-ogrenci/deneme-ekle-konu?id=32', 13, '2025-04-27 21:44:05'),
(958, 1, 'http://localhost/lineup_campus/test-detay-ogrenci/deneme-ekle-konu?id=32', 630, '2025-04-27 21:54:36'),
(959, 1, 'http://localhost/lineup_campus/test-detay-ogrenci/deneme-ekle-konu?id=32', 6, '2025-04-27 21:54:44'),
(960, 1, 'http://localhost/lineup_campus/test-detay-ogrenci/deneme-ekle-konu?id=32', 49, '2025-04-27 21:55:34'),
(961, 1, 'http://localhost/lineup_campus/test-detay-ogrenci/deneme-ekle-konu?id=32', 6, '2025-04-27 21:55:42'),
(962, 1, 'http://localhost/lineup_campus/test-detay-ogrenci/deneme-ekle-konu?id=32', 5, '2025-04-27 21:55:48'),
(963, 1, 'http://localhost/lineup_campus/test-detay-ogrenci/deneme-ekle-konu?id=32', 11, '2025-04-27 21:56:00'),
(964, 1, 'http://localhost/lineup_campus/test-detay-ogrenci/deneme-ekle-konu?id=32', 69, '2025-04-27 21:57:10'),
(965, 1, 'http://localhost/lineup_campus/test-detay-ogrenci/deneme-ekle-konu?id=32', 72, '2025-04-27 21:58:24'),
(966, 1, 'http://localhost/lineup_campus/test-detay-ogrenci/deneme-ekle-konu?id=32', 56, '2025-04-27 21:59:22'),
(967, 1, 'http://localhost/lineup_campus/test-detay-ogrenci/deneme-ekle-konu?id=32', 31, '2025-04-27 21:59:54'),
(968, 1, 'http://localhost/lineup_campus/test-detay-ogrenci/deneme-ekle-konu?id=32', 31, '2025-04-27 22:00:27'),
(969, 1, 'http://localhost/lineup_campus/test-detay-ogrenci/deneme-ekle-konu?id=32', 12, '2025-04-27 22:00:40'),
(970, 1, 'http://localhost/lineup_campus/test-detay-ogrenci/deneme-ekle-konu?id=32', 5, '2025-04-27 22:00:46'),
(971, 1, 'http://localhost/lineup_campus/test-detay-ogrenci/deneme-ekle-konu?id=32', 650, '2025-04-27 22:11:38'),
(972, 1, 'http://localhost/lineup_campus/test-detay-ogrenci/deneme-ekle-konu?id=32', 32, '2025-04-27 22:12:11'),
(973, 1, 'http://localhost/lineup_campus/test-detay-ogrenci/deneme-ekle-konu?id=32', 1131, '2025-04-27 22:31:03'),
(974, 1, 'http://localhost/lineup_campus/test-detay-ogrenci/deneme-ekle-konu?id=32', 273, '2025-04-27 22:36:03'),
(975, 1, 'http://localhost/lineup_campus/test-detay-ogrenci/deneme-ekle-konu?id=32', 17, '2025-04-27 22:36:22'),
(976, 1, 'http://localhost/lineup_campus/test-detay-ogrenci/deneme-ekle-konu?id=32', 68, '2025-04-27 22:37:31'),
(977, 1, 'http://localhost/lineup_campus/test-detay-ogrenci/deneme-ekle-konu?id=32', 39, '2025-04-27 22:38:12'),
(978, 1, 'http://localhost/lineup_campus/test-detay-ogrenci/deneme-ekle-konu?id=32', 215, '2025-04-27 22:41:48'),
(979, 1, 'http://localhost/lineup_campus/test-detay-ogrenci/deneme-ekle-konu?id=32', 40, '2025-04-27 22:42:30'),
(980, 1, 'http://localhost/lineup_campus/test-detay-ogrenci/deneme-ekle-konu?id=32', 17, '2025-04-27 22:42:48'),
(981, 1, 'http://localhost/lineup_campus/test-detay-ogrenci/deneme-ekle-konu?id=32', 106, '2025-04-27 22:44:36'),
(982, 1, 'http://localhost/lineup_campus/test-detay-ogrenci/deneme-ekle-konu?id=32', 25, '2025-04-27 22:45:02'),
(983, 1, 'http://localhost/lineup_campus/test-detay-ogrenci/deneme-ekle-konu?id=32', 4, '2025-04-27 22:45:07'),
(984, 1, 'http://localhost/lineup_campus/test-detay-ogrenci/deneme-ekle-konu?id=32', 13, '2025-04-27 22:45:21'),
(985, 1, 'http://localhost/lineup_campus/testler', 2, '2025-04-27 22:45:25'),
(986, 32, 'http://localhost/lineup_campus/dashboard', 2, '2025-04-27 22:45:51'),
(987, 25, 'http://localhost/lineup_campus/dashboard', 1, '2025-04-27 22:46:36'),
(988, 25, 'http://localhost/lineup_campus/ders/turkce', 3, '2025-04-27 22:46:40'),
(989, 25, 'http://localhost/lineup_campus/ders/turkce', 0, '2025-04-27 22:46:41'),
(990, 25, 'http://localhost/lineup_campus/unite/deneme-unite', 1, '2025-04-27 22:46:43'),
(991, 25, 'http://localhost/lineup_campus/konu/deneme-konu', 440, '2025-04-27 22:54:04'),
(992, 25, 'http://localhost/lineup_campus/konu/deneme-konu', 2, '2025-04-27 22:54:07'),
(993, 25, 'http://localhost/lineup_campus/alt-konu/deneme-ekle-konu', 14, '2025-04-27 22:55:08'),
(994, 25, 'http://localhost/lineup_campus/dashboard', 1, '2025-04-27 22:55:11'),
(995, 25, 'http://localhost/lineup_campus/ders/turkce', 0, '2025-04-27 22:55:12'),
(996, 25, 'http://localhost/lineup_campus/unite/deneme-unite', 0, '2025-04-27 22:55:13'),
(997, 1, 'http://localhost/lineup_campus/test-detay/deneme-ekle-konu', 593, '2025-04-27 22:55:19'),
(998, 1, 'http://localhost/lineup_campus/test-detay/deneme-ekle-konu', 4, '2025-04-27 22:55:24'),
(999, 1, 'http://localhost/lineup_campus/dashboard', 1, '2025-04-27 22:55:26'),
(1000, 1, 'http://localhost/lineup_campus/test-detay/deneme-ekle-konu', 1, '2025-04-27 22:55:29'),
(1001, 1, 'http://localhost/lineup_campus/apps/user-management/users/view.html', 1, '2025-04-27 22:55:31'),
(1002, 1, 'http://localhost/lineup_campus/test-detay/deneme-ekle-konu', 568, '2025-04-27 23:05:00'),
(1003, 1, 'http://localhost/lineup_campus/test-detay/deneme-ekle-konu', 15, '2025-04-27 23:05:16'),
(1004, 1, 'http://localhost/lineup_campus/test-detay-ogrenci/deneme-ekle-konu?id=25', 11, '2025-04-27 23:05:29'),
(1005, 1, 'http://localhost/lineup_campus/test-detay/deneme-ekle-konu', 2, '2025-04-27 23:05:33'),
(1006, 1, 'http://localhost/lineup_campus/test-detay-ogrenci/deneme-ekle-konu?id=32', 1, '2025-04-27 23:05:35'),
(1007, 1, 'http://localhost/lineup_campus/test-detay/deneme-ekle-konu', 1, '2025-04-27 23:05:37'),
(1008, 1, 'http://localhost/lineup_campus/test-detay-ogrenci/deneme-ekle-konu?id=25', 75, '2025-04-27 23:06:54'),
(1009, 25, 'http://localhost/lineup_campus/konu/deneme-konu', 721, '2025-04-27 23:07:15'),
(1010, 25, 'http://localhost/lineup_campus/konu/deneme-konu', 0, '2025-04-27 23:07:16'),
(1011, 25, 'http://localhost/lineup_campus/alt-konu/deneme-ekle-konu', 410, '2025-04-27 23:14:13'),
(1012, 25, 'http://localhost/lineup_campus/alt-konu/deneme-ekle-konu', 14, '2025-04-27 23:15:18'),
(1013, 1, 'http://localhost/lineup_campus/test-detay-ogrenci/deneme-ekle-konu?id=25', 511, '2025-04-27 23:15:26'),
(1014, 1, 'http://localhost/lineup_campus/test-detay-ogrenci/deneme-ekle-konu?id=25', 11, '2025-04-27 23:15:39'),
(1015, 1, 'http://localhost/lineup_campus/testler', 2, '2025-04-27 23:15:43'),
(1016, 1, 'http://localhost/lineup_campus/test-detay/deneme-ekle-konu', 1, '2025-04-27 23:15:45'),
(1017, 1, 'http://localhost/lineup_campus/testler', 37, '2025-04-27 23:16:23'),
(1018, 1, 'http://localhost/lineup_campus/testler', 0, '2025-04-27 23:16:25'),
(1019, 1, 'http://localhost/lineup_campus/test-detay/deneme-ekle-konu', 71, '2025-04-27 23:17:37'),
(1020, 1, 'http://localhost/lineup_campus/test-detay/deneme-ekle-konu', 74, '2025-04-27 23:18:53'),
(1021, 1, 'http://localhost/lineup_campus/test-detay/deneme-ekle-konu', 71, '2025-04-27 23:20:05'),
(1022, 1, 'http://localhost/lineup_campus/test-detay/deneme-ekle-konu', 54, '2025-04-27 23:21:00'),
(1023, 1, 'http://localhost/lineup_campus/test-detay/deneme-ekle-konu', 20, '2025-04-27 23:21:22'),
(1024, 1, 'http://localhost/lineup_campus/test-detay/deneme-ekle-konu', 6, '2025-04-27 23:21:29'),
(1025, 1, 'http://localhost/lineup_campus/test-detay-ogrenci/deneme-ekle-konu?id=32', 7, '2025-04-27 23:21:37'),
(1026, 1, 'http://localhost/lineup_campus/test-detay/deneme-ekle-konu', 1, '2025-04-27 23:21:40'),
(1027, 1, 'http://localhost/lineup_campus/testler', 1, '2025-04-27 23:21:42'),
(1028, 1, 'http://localhost/lineup_campus/altkonu-ekle', 2, '2025-04-27 23:21:46'),
(1029, 1, 'http://localhost/lineup_campus/testler', 9, '2025-04-27 23:21:56'),
(1030, 1, 'http://localhost/lineup_campus/alt-konular', 0, '2025-04-27 23:21:58'),
(1031, 1, 'http://localhost/lineup_campus/testler', 48, '2025-04-27 23:22:47'),
(1032, 1, 'http://localhost/lineup_campus/testler', 1, '2025-04-27 23:22:49'),
(1033, 1, 'http://localhost/lineup_campus/test-ekle', 66, '2025-04-27 23:23:56'),
(1034, 1, 'http://localhost/lineup_campus/test-ekle', 157, '2025-04-27 23:26:35'),
(1035, 1, 'http://localhost/lineup_campus/testler', 167, '2025-04-27 23:29:23'),
(1036, 1, 'http://localhost/lineup_campus/test-ekle', 0, '2025-04-27 23:29:24'),
(1037, 25, 'http://localhost/lineup_campus/dashboard', 862, '2025-04-27 23:29:41'),
(1038, 25, 'http://localhost/lineup_campus/ders/turkce', 1, '2025-04-27 23:29:44'),
(1039, 25, 'http://localhost/lineup_campus/unite/deneme-unite', 1, '2025-04-27 23:29:45'),
(1040, 25, 'http://localhost/lineup_campus/konu/deneme-konu', 5, '2025-04-27 23:29:51'),
(1041, 25, 'http://localhost/lineup_campus/alt-konu/baskentler', 46, '2025-04-27 23:30:38'),
(1042, 1, 'http://localhost/lineup_campus/test-ekle', 77, '2025-04-27 23:30:42'),
(1043, 1, 'http://localhost/lineup_campus/testler', 1, '2025-04-27 23:30:45'),
(1044, 1, 'http://localhost/lineup_campus/test-detay/baskentler', 8, '2025-04-27 23:30:54'),
(1045, 1, 'http://localhost/lineup_campus/test-detay-ogrenci/baskentler?id=25', 55, '2025-04-27 23:31:51'),
(1046, 25, 'http://localhost/lineup_campus/dashboard', 124, '2025-04-27 23:32:43'),
(1047, 25, 'http://localhost/lineup_campus/ders/turkce', 1, '2025-04-27 23:32:45'),
(1048, 25, 'http://localhost/lineup_campus/unite/deneme-unite', 0, '2025-04-27 23:32:47'),
(1049, 25, 'http://localhost/lineup_campus/konu/deneme-konu', 22, '2025-04-27 23:33:09'),
(1050, 25, 'http://localhost/lineup_campus/konu/deneme-konu', 1, '2025-04-27 23:33:11'),
(1051, 25, 'http://localhost/lineup_campus/alt-konu/baskentler', 40, '2025-04-27 23:33:53'),
(1052, 25, 'http://localhost/lineup_campus/alt-konu/baskentler', 34, '2025-04-27 23:34:29'),
(1053, 25, 'http://localhost/lineup_campus/alt-konu/baskentler', 13, '2025-04-27 23:34:43'),
(1054, 1, 'http://localhost/lineup_campus/test-detay-ogrenci/baskentler?id=25', 175, '2025-04-27 23:34:47'),
(1055, 25, 'http://localhost/lineup_campus/dashboard', 303, '2025-04-27 23:39:47'),
(1056, 25, 'http://localhost/lineup_campus/alt-konu/baskentler', 48, '2025-04-27 23:40:36'),
(1057, 25, 'http://localhost/lineup_campus/dashboard', 0, '2025-04-27 23:40:37'),
(1058, 25, 'http://localhost/lineup_campus/alt-konu/baskentler', 198, '2025-04-27 23:43:56'),
(1059, 25, 'http://localhost/lineup_campus/dashboard', 1, '2025-04-27 23:43:58'),
(1060, 25, 'http://localhost/lineup_campus/alt-konu/baskentler', 63, '2025-04-27 23:45:02'),
(1061, 25, 'http://localhost/lineup_campus/dashboard', 1, '2025-04-27 23:45:04'),
(1062, 25, 'http://localhost/lineup_campus/alt-konu/baskentler', 9, '2025-04-27 23:45:13'),
(1063, 25, 'http://localhost/lineup_campus/alt-konu/baskentler', 568, '2025-04-27 23:54:43'),
(1064, 25, 'http://localhost/lineup_campus/alt-konu/baskentler', 55, '2025-04-27 23:55:39'),
(1065, 25, 'http://localhost/lineup_campus/dashboard', 0, '2025-04-27 23:55:39'),
(1066, 25, 'http://localhost/lineup_campus/alt-konu/baskentler', 4, '2025-04-27 23:55:44'),
(1067, 25, 'http://localhost/lineup_campus/dashboard', 34, '2025-04-27 23:56:19'),
(1068, 25, 'http://localhost/lineup_campus/alt-konu/baskentler', 124, '2025-04-27 23:58:24'),
(1069, 25, 'http://localhost/lineup_campus/dashboard', 0, '2025-04-27 23:58:25'),
(1070, 25, 'http://localhost/lineup_campus/alt-konu/baskentler', 7, '2025-04-27 23:58:33'),
(1071, 25, 'http://localhost/lineup_campus/konu/deneme-konu', 0, '2025-04-27 23:58:33'),
(1072, 25, 'http://localhost/lineup_campus/alt-konu/baskentler', 49, '2025-04-27 23:59:23'),
(1073, 25, 'http://localhost/lineup_campus/konu/deneme-konu', 1, '2025-04-27 23:59:24'),
(1074, 25, 'http://localhost/lineup_campus/alt-konu/baskentler', 19, '2025-04-27 23:59:44'),
(1075, 25, 'http://localhost/lineup_campus/dashboard', 0, '2025-04-27 23:59:45'),
(1076, 25, 'http://localhost/lineup_campus/alt-konu/baskentler', 40, '2025-04-28 00:00:26'),
(1077, 25, 'http://localhost/lineup_campus/dashboard', 0, '2025-04-28 00:00:27'),
(1078, 25, 'http://localhost/lineup_campus/alt-konu/baskentler', 10, '2025-04-28 00:00:38'),
(1079, 25, 'http://localhost/lineup_campus/dashboard', 5, '2025-04-28 00:00:43'),
(1080, 25, 'http://localhost/lineup_campus/alt-konu/baskentler', 1, '2025-04-28 00:00:45'),
(1081, 25, 'http://localhost/lineup_campus/alt-konu/baskentler', 8, '2025-04-28 00:00:54'),
(1082, 25, 'http://localhost/lineup_campus/dashboard', 30, '2025-04-28 00:01:25'),
(1083, 25, 'http://localhost/lineup_campus/alt-konu/baskentler', 4, '2025-04-28 00:01:30'),
(1084, 25, 'http://localhost/lineup_campus/dashboard', 4, '2025-04-28 00:01:35'),
(1085, 25, 'http://localhost/lineup_campus/alt-konu/baskentler', 7, '2025-04-28 00:01:43'),
(1086, 25, 'http://localhost/lineup_campus/alt-konu/baskentler', 15, '2025-04-28 00:01:59'),
(1087, 25, 'http://localhost/lineup_campus/dashboard', 4, '2025-04-28 00:02:03'),
(1088, 25, 'http://localhost/lineup_campus/alt-konu/baskentler', 12, '2025-04-28 00:02:16'),
(1089, 25, 'http://localhost/lineup_campus/dashboard', 22, '2025-04-28 00:02:39'),
(1090, 25, 'http://localhost/lineup_campus/alt-konu/baskentler', 316, '2025-04-28 00:07:56'),
(1091, 25, 'http://localhost/lineup_campus/alt-konu/baskentler', 152, '2025-04-28 00:10:29'),
(1092, 25, 'http://localhost/lineup_campus/dashboard', 0, '2025-04-28 00:10:29'),
(1093, 25, 'http://localhost/lineup_campus/dashboard', 0, '2025-04-28 00:10:31'),
(1094, 25, 'http://localhost/lineup_campus/alt-konu/baskentler', 1, '2025-04-28 00:10:32'),
(1095, 25, 'http://localhost/lineup_campus/alt-konu/baskentler', 16, '2025-04-28 00:10:50'),
(1096, 1, 'http://localhost/lineup_campus/test-detay-ogrenci/baskentler?id=25', 2166, '2025-04-28 00:10:54'),
(1097, 25, 'http://localhost/lineup_campus/dashboard', 26, '2025-04-28 00:11:17'),
(1098, 25, 'http://localhost/lineup_campus/alt-konu/baskentler', 15, '2025-04-28 00:11:33'),
(1099, 25, 'http://localhost/lineup_campus/dashboard', 0, '2025-04-28 00:11:34'),
(1100, 25, 'http://localhost/lineup_campus/alt-konu/baskentler', 10, '2025-04-28 00:11:44'),
(1101, 25, 'http://localhost/lineup_campus/dashboard', 6, '2025-04-28 00:11:51'),
(1102, 25, 'http://localhost/lineup_campus/alt-konu/baskentler', 9, '2025-04-28 00:12:01'),
(1103, 25, 'http://localhost/lineup_campus/dashboard', 9, '2025-04-28 00:12:11'),
(1104, 25, 'http://localhost/lineup_campus/alt-konu/baskentler', 10, '2025-04-28 00:12:21'),
(1105, 1, 'http://localhost/lineup_campus/test-detay-ogrenci/baskentler?id=25', 94, '2025-04-28 00:12:30'),
(1106, 1, 'http://localhost/lineup_campus/testler', 2, '2025-04-28 00:12:33'),
(1107, 1, 'http://localhost/lineup_campus/test-detay/baskentler', 3, '2025-04-28 00:12:37'),
(1108, 1, 'http://localhost/lineup_campus/test-detay-ogrenci/baskentler?id=25', 14, '2025-04-28 00:12:52'),
(1109, 25, 'http://localhost/lineup_campus/dashboard', 31003, '2025-04-28 08:49:05'),
(1110, 25, 'http://localhost/lineup_campus/konu/deneme-konu', 129675, '2025-04-28 08:59:16'),
(1111, 25, 'http://localhost/lineup_campus/konu/deneme-konu', 695, '2025-04-28 09:10:52'),
(1112, 25, 'http://localhost/lineup_campus/konu/deneme-konu', 6, '2025-04-28 09:10:59'),
(1113, 1, 'http://localhost/lineup_campus/test-detay/baskentler', 32289, '2025-04-28 09:11:03'),
(1114, 25, 'http://localhost/lineup_campus/konu/deneme-konu', 20, '2025-04-28 09:11:20'),
(1115, 25, 'http://localhost/lineup_campus/konu/deneme-konu', 4, '2025-04-28 09:11:25'),
(1116, 25, 'http://localhost/lineup_campus/dashboard', 6, '2025-04-28 09:11:32'),
(1117, 25, 'http://localhost/lineup_campus/dashboard', 132, '2025-04-28 09:13:44'),
(1118, 25, 'http://localhost/lineup_campus/dashboard', 444, '2025-04-28 09:21:09'),
(1119, 25, 'http://localhost/lineup_campus/dashboard', 21, '2025-04-28 09:21:30'),
(1120, 25, 'http://localhost/lineup_campus/dashboard', 37, '2025-04-28 09:22:08'),
(1121, 25, 'http://localhost/lineup_campus/dashboard', 145, '2025-04-28 09:24:33'),
(1122, 1, 'http://localhost/lineup_campus/test-detay/baskentler', 828, '2025-04-28 09:24:53'),
(1123, 25, 'http://localhost/lineup_campus/dashboard', 54, '2025-04-28 09:25:28'),
(1124, 1, 'http://localhost/lineup_campus/test-detay/baskentler', 61, '2025-04-28 09:25:55'),
(1125, 1, 'http://localhost/lineup_campus/kullanici-yetkilendirme', 1, '2025-04-28 09:25:57'),
(1126, 1, 'http://localhost/lineup_campus/kullanici-gruplari', 3, '2025-04-28 09:26:02'),
(1127, 1, 'http://localhost/lineup_campus/kullanici-grup-detay/ogrenciler', 11, '2025-04-28 09:26:14'),
(1128, 25, 'http://localhost/lineup_campus/dashboard', 41, '2025-04-28 09:26:18'),
(1129, 25, 'http://localhost/lineup_campus/dashboard', 50, '2025-04-28 09:27:08'),
(1130, 25, 'http://localhost/lineup_campus/dashboard', 403, '2025-04-28 09:33:52'),
(1131, 25, 'http://localhost/lineup_campus/dashboard', 23, '2025-04-28 09:34:16'),
(1132, 25, 'http://localhost/lineup_campus/dashboard', 927, '2025-04-28 09:50:55'),
(1133, 25, 'http://localhost/lineup_campus/dashboard', 949, '2025-04-28 09:50:55'),
(1134, 25, 'http://localhost/lineup_campus/dashboard', 954, '2025-04-28 09:50:56'),
(1135, 25, 'http://localhost/lineup_campus/dashboard', 3, '2025-04-28 09:51:00'),
(1136, 25, 'http://localhost/lineup_campus/dashboard', 324, '2025-04-28 09:56:24'),
(1137, 25, 'http://localhost/lineup_campus/dashboard', 3, '2025-04-28 09:56:29'),
(1138, 25, 'http://localhost/lineup_campus/dashboard', 1095, '2025-04-28 10:14:40'),
(1139, 25, 'http://localhost/lineup_campus/dashboard', 117, '2025-04-28 10:16:38'),
(1140, 25, 'http://localhost/lineup_campus/dashboard', 2, '2025-04-28 10:16:41'),
(1141, 25, 'http://localhost/lineup_campus/dashboard', 0, '2025-04-28 10:16:53'),
(1142, 25, 'http://localhost/lineup_campus/dashboard', 1, '2025-04-28 10:16:55'),
(1143, 25, 'http://localhost/lineup_campus/ders/turkce', 2, '2025-04-28 10:16:58'),
(1144, 25, 'http://localhost/lineup_campus/unite/deneme-unite', 1, '2025-04-28 10:17:00'),
(1145, 1, 'http://localhost/lineup_campus/kullanici-gruplari', 3093, '2025-04-28 10:17:48'),
(1146, 1, 'http://localhost/lineup_campus/kullanici-gruplari', 4, '2025-04-28 10:17:53'),
(1147, 1, 'http://localhost/lineup_campus/kullanici-gruplari', 4, '2025-04-28 10:17:58'),
(1148, 25, 'http://localhost/lineup_campus/ders/turkce', 148, '2025-04-28 10:19:29'),
(1149, 25, 'http://localhost/lineup_campus/ders/turkce', 1, '2025-04-28 10:19:31'),
(1150, 25, 'http://localhost/lineup_campus/ders/matematik', 1, '2025-04-28 10:19:32');
INSERT INTO `timespend_lnp` (`id`, `user_id`, `sayfa_url`, `timeSpent`, `exitTime`) VALUES
(1151, 25, 'http://localhost/lineup_campus/ders/hayat-bilgisi', 1, '2025-04-28 10:19:34'),
(1152, 25, 'http://localhost/lineup_campus/ders/turkce', 777, '2025-04-28 10:32:32'),
(1153, 25, 'http://localhost/lineup_campus/ders/turkce', 27, '2025-04-28 10:33:33'),
(1154, 25, 'http://localhost/lineup_campus/ders/turkce', 1, '2025-04-28 10:33:35'),
(1155, 1, 'http://localhost/lineup_campus/uniteler', 973, '2025-04-28 10:34:13'),
(1156, 1, 'http://localhost/lineup_campus/uniteler', 88, '2025-04-28 10:35:42'),
(1157, 25, 'http://localhost/lineup_campus/ders/turkce', 143, '2025-04-28 10:35:59'),
(1158, 1, 'http://localhost/lineup_campus/uniteler', 158, '2025-04-28 10:38:20'),
(1159, 25, 'http://localhost/lineup_campus/ders/turkce', 146, '2025-04-28 10:38:26'),
(1160, 1, 'http://localhost/lineup_campus/uniteler', 72, '2025-04-28 10:39:34'),
(1161, 1, 'http://localhost/lineup_campus/uniteler', 1, '2025-04-28 10:39:36'),
(1162, 25, 'http://localhost/lineup_campus/ders/turkce', 77, '2025-04-28 10:39:43'),
(1163, 25, 'http://localhost/lineup_campus/ders/turkce', 40, '2025-04-28 10:40:24'),
(1164, 1, 'http://localhost/lineup_campus/siniflar', 53, '2025-04-28 10:40:30'),
(1165, 1, 'http://localhost/lineup_campus/siniflar', 2, '2025-04-28 10:40:33'),
(1166, 25, 'http://localhost/lineup_campus/ders/turkce', 49, '2025-04-28 10:41:14'),
(1167, 25, 'http://localhost/lineup_campus/ders/turkce', 75, '2025-04-28 10:42:29'),
(1168, 25, 'http://localhost/lineup_campus/ders/turkce', 30, '2025-04-28 10:43:00'),
(1169, 25, 'http://localhost/lineup_campus/ders/turkce', 9, '2025-04-28 10:43:10'),
(1170, 25, 'http://localhost/lineup_campus/ders/turkce', 44, '2025-04-28 10:43:55'),
(1171, 25, 'http://localhost/lineup_campus/ders/turkce', 61, '2025-04-28 10:44:57'),
(1172, 25, 'http://localhost/lineup_campus/ders/turkce', 20, '2025-04-28 10:45:18'),
(1173, 25, 'http://localhost/lineup_campus/ders/turkce', 13, '2025-04-28 10:45:33'),
(1174, 25, 'http://localhost/lineup_campus/ders/turkce', 86, '2025-04-28 10:47:00'),
(1175, 25, 'http://localhost/lineup_campus/ders/turkce', 26, '2025-04-28 10:47:27'),
(1176, 25, 'http://localhost/lineup_campus/ders/turkce', 6, '2025-04-28 10:47:34'),
(1177, 25, 'http://localhost/lineup_campus/ders/turkce', 143, '2025-04-28 10:49:58'),
(1178, 25, 'http://localhost/lineup_campus/ders/turkce', 16, '2025-04-28 10:50:15'),
(1179, 1, 'http://localhost/lineup_campus/uniteler', 620, '2025-04-28 10:50:53'),
(1180, 25, 'http://localhost/lineup_campus/ders/turkce', 96, '2025-04-28 10:51:52'),
(1181, 25, 'http://localhost/lineup_campus/ders/turkce', 50, '2025-04-28 10:52:42'),
(1182, 25, 'http://localhost/lineup_campus/ders/turkce', 4, '2025-04-28 10:52:47'),
(1183, 25, 'http://localhost/lineup_campus/ders/turkce', 89, '2025-04-28 10:54:17'),
(1184, 25, 'http://localhost/lineup_campus/ders/turkce', 26, '2025-04-28 10:54:45'),
(1185, 25, 'http://localhost/lineup_campus/ders/turkce', 25, '2025-04-28 10:55:11'),
(1186, 25, 'http://localhost/lineup_campus/ders/turkce', 39, '2025-04-28 10:55:51'),
(1187, 25, 'http://localhost/lineup_campus/ders/ingilizce', 1, '2025-04-28 10:55:53'),
(1188, 25, 'http://localhost/lineup_campus/ders/turkce', 1, '2025-04-28 10:55:55'),
(1189, 1, 'http://localhost/lineup_campus/uniteler', 670, '2025-04-28 11:02:05'),
(1190, 1, 'http://localhost/lineup_campus/uniteler', 3, '2025-04-28 11:02:09'),
(1191, 1, 'http://localhost/lineup_campus/duyurular', 11, '2025-04-28 11:02:22'),
(1192, 1, 'http://localhost/lineup_campus/dashboard', 0, '2025-04-28 11:02:23'),
(1193, 1, 'http://localhost/lineup_campus/duyurular', 63, '2025-04-28 11:03:27'),
(1194, 1, 'http://localhost/lineup_campus/bildirimler', 2, '2025-04-28 11:03:30'),
(1195, 25, 'http://localhost/lineup_campus/unite/deneme-unite', 498, '2025-04-28 11:04:14'),
(1196, 25, 'http://localhost/lineup_campus/unite/deneme-unite', 3, '2025-04-28 11:04:18'),
(1197, 25, 'http://localhost/lineup_campus/dashboard', 1, '2025-04-28 11:04:20'),
(1198, 25, 'http://localhost/lineup_campus/unite/deneme-unite', 164, '2025-04-28 11:07:05'),
(1199, 25, 'http://localhost/lineup_campus/dashboard', 0, '2025-04-28 11:07:06'),
(1200, 25, 'http://localhost/lineup_campus/unite/deneme-unite', 34, '2025-04-28 11:07:42'),
(1201, 25, 'http://localhost/lineup_campus/duyurular', 67, '2025-04-28 11:08:49'),
(1202, 25, 'http://localhost/lineup_campus/dashboard', 108, '2025-04-28 11:10:38'),
(1203, 25, 'http://localhost/lineup_campus/dashboard', 159, '2025-04-28 11:13:19'),
(1204, 25, 'http://localhost/lineup_campus/dashboard', 2, '2025-04-28 11:13:21'),
(1205, 25, 'http://localhost/lineup_campus/testler-ogrenci', 212, '2025-04-28 11:32:30'),
(1206, 25, 'http://localhost/lineup_campus/testler-ogrenci', 28, '2025-04-28 11:33:00'),
(1207, 25, 'http://localhost/lineup_campus/testler-ogrenci', 163, '2025-04-28 11:35:44'),
(1208, 25, 'http://localhost/lineup_campus/testler-ogrenci', 2, '2025-04-28 11:35:47'),
(1209, 25, 'http://localhost/lineup_campus/ders/turkce', 1, '2025-04-28 11:36:12'),
(1210, 25, 'http://localhost/lineup_campus/unite/deneme-unite', 0, '2025-04-28 11:36:14'),
(1211, 25, 'http://localhost/lineup_campus/konu/deneme-konu', 33, '2025-04-28 11:36:48'),
(1212, 25, 'http://localhost/lineup_campus/testler-ogrenci', 1, '2025-04-28 11:36:50'),
(1213, 25, 'http://localhost/lineup_campus/alt-konu/baskentler', 1, '2025-04-28 11:36:52'),
(1214, 25, 'http://localhost/lineup_campus/alt-konu/baskentler', 258, '2025-04-28 11:41:38'),
(1215, 25, 'http://localhost/lineup_campus/testler-ogrenci', 288, '2025-04-28 11:41:42'),
(1216, 25, 'http://localhost/lineup_campus/testler-ogrenci', 30, '2025-04-28 11:42:13'),
(1217, 25, 'http://localhost/lineup_campus/testler-ogrenci', 8, '2025-04-28 11:42:22'),
(1218, 25, 'http://localhost/lineup_campus/testler-ogrenci', 1, '2025-04-28 11:42:25'),
(1219, 25, 'http://localhost/lineup_campus/alt-konu/baskentler', 8, '2025-04-28 11:42:34'),
(1220, 25, 'http://localhost/lineup_campus/dashboard', 2, '2025-04-28 11:42:37'),
(1221, 1, 'http://localhost/lineup_campus/apps/user-management/users/view.html', 10455, '2025-04-28 11:43:42'),
(1222, 1, 'http://localhost/lineup_campus/apps/support-center/overview.html', 3, '2025-04-28 11:43:46'),
(1223, 1, 'http://localhost/lineup_campus/apps/support-center/tickets/list.html', 2, '2025-04-28 11:43:49'),
(1224, 1, 'http://localhost/lineup_campus/duyurular', 2563, '2025-04-28 11:46:14'),
(1225, 25, 'http://localhost/lineup_campus/testler-ogrenci', 374, '2025-04-28 11:48:52'),
(1226, 25, 'http://localhost/lineup_campus/testler-ogrenci', 802, '2025-04-28 11:49:19'),
(1227, 25, 'http://localhost/lineup_campus/ders/turkce', 1, '2025-04-28 11:49:21'),
(1228, 25, 'http://localhost/lineup_campus/unite/deneme-unite', 43, '2025-04-28 11:50:05'),
(1229, 25, 'http://localhost/lineup_campus/unite/deneme-unite', 2, '2025-04-28 11:50:07'),
(1230, 25, 'http://localhost/lineup_campus/destek-talebi', 263, '2025-04-28 11:54:31'),
(1231, 25, 'http://localhost/lineup_campus/destek-talebi', 34, '2025-04-28 11:55:06'),
(1232, 25, 'http://localhost/lineup_campus/destek-talebi', 158, '2025-04-28 11:57:45'),
(1233, 25, 'http://localhost/lineup_campus/destek-talebi', 114, '2025-04-28 11:59:40'),
(1234, 25, 'http://localhost/lineup_campus/destek-talebi', 44, '2025-04-28 12:00:25'),
(1235, 25, 'http://localhost/lineup_campus/destek-talebi', 5, '2025-04-28 12:00:31'),
(1236, 1, 'http://localhost/lineup_campus/apps/support-center/overview.html', 1030, '2025-04-28 12:00:59'),
(1237, 1, 'http://localhost/lineup_campus/apps/support-center/tickets/list.html', 0, '2025-04-28 12:01:01'),
(1238, 25, 'http://localhost/lineup_campus/destek-talebi', 1535, '2025-04-28 12:26:08'),
(1239, 25, 'http://localhost/lineup_campus/destek-talebi', 36, '2025-04-28 12:26:45'),
(1240, 25, 'http://localhost/lineup_campus/destek-talebi', 17, '2025-04-28 12:27:03'),
(1241, 25, 'http://localhost/lineup_campus/dashboard', 9, '2025-04-28 12:27:13'),
(1242, 25, 'http://localhost/lineup_campus/destek-talebi', 22, '2025-04-28 12:27:36'),
(1243, 25, 'http://localhost/lineup_campus/dashboard', 154, '2025-04-28 12:30:11'),
(1244, 25, 'http://localhost/lineup_campus/destek-talebi', 20, '2025-04-28 12:30:32'),
(1245, 1, 'http://localhost/lineup_campus/apps/support-center/overview.html', 1851, '2025-04-28 12:31:52'),
(1246, 1, 'http://localhost/lineup_campus/apps/support-center/tickets/list.html', 1, '2025-04-28 12:31:53'),
(1247, 25, 'http://localhost/lineup_campus/dashboard', 382, '2025-04-28 12:36:55'),
(1248, 25, 'http://localhost/lineup_campus/dashboard', 2, '2025-04-28 12:36:58'),
(1249, 1, 'http://localhost/lineup_campus/apps/support-center/tickets/view.html', 427, '2025-04-28 12:39:01'),
(1250, 1, 'http://localhost/lineup_campus/apps/support-center/tickets/list.html', 19, '2025-04-28 12:39:21'),
(1251, 1, 'http://localhost/lineup_campus/apps/support-center/tutorials/list.html', 1, '2025-04-28 12:39:22'),
(1252, 1, 'http://localhost/lineup_campus/apps/support-center/tickets/list.html', 30, '2025-04-28 12:39:31'),
(1253, 1, 'http://localhost/lineup_campus/apps/support-center/overview.html', 30, '2025-04-28 12:40:02'),
(1254, 1, 'http://localhost/lineup_campus/apps/support-center/tickets/list.html', 0, '2025-04-28 12:40:03'),
(1255, 1, 'http://localhost/lineup_campus/apps/support-center/tickets/view.html', 77, '2025-04-28 12:41:21'),
(1256, 1, 'http://localhost/lineup_campus/apps/support-center/overview.html', 16, '2025-04-28 12:41:38'),
(1257, 1, 'http://localhost/lineup_campus/apps/support-center/tickets/list.html', 12, '2025-04-28 12:41:50'),
(1258, 1, 'http://localhost/lineup_campus/dashboard', 1, '2025-04-28 12:41:52'),
(1259, 25, 'http://localhost/lineup_campus/destek-talebi', 515, '2025-04-28 12:45:34'),
(1260, 25, 'http://localhost/lineup_campus/destek-ekle', 3413, '2025-04-28 12:45:46'),
(1261, 25, 'http://localhost/lineup_campus/dashboard', 1, '2025-04-28 12:45:48'),
(1262, 25, 'http://localhost/lineup_campus/aktif-destek-talepleri', 179, '2025-04-28 12:48:34'),
(1263, 25, 'http://localhost/lineup_campus/aktif-destek-talepleri', 96, '2025-04-28 12:50:12'),
(1264, 25, 'http://localhost/lineup_campus/aktif-destek-talepleri', 38, '2025-04-28 12:50:50'),
(1265, 1, 'http://localhost/lineup_campus/dashboard', 2, '2025-04-28 20:10:35'),
(1266, 32, 'http://localhost/lineup_campus/dashboard', 65, '2025-04-28 20:11:47'),
(1267, 32, 'http://localhost/lineup_campus/destek-talebi', 109, '2025-04-28 20:13:38'),
(1268, 32, 'http://localhost/lineup_campus/aktif-destek-talepleri', 18, '2025-04-28 20:14:02'),
(1269, 32, 'http://localhost/lineup_campus/apps/ecommerce/catalog/products.html', 1, '2025-04-28 20:14:04'),
(1270, 32, 'http://localhost/lineup_campus/aktif-destek-talepleri', 22, '2025-04-28 20:14:06'),
(1271, 32, 'http://localhost/lineup_campus/account/overview.html', 0, '2025-04-28 20:14:07'),
(1272, 32, 'http://localhost/lineup_campus/aktif-destek-talepleri', 1, '2025-04-28 20:14:09'),
(1273, 32, 'http://localhost/lineup_campus/apps/calendar.html', 1, '2025-04-28 20:14:10'),
(1274, 32, 'http://localhost/lineup_campus/aktif-destek-talepleri', 32, '2025-04-28 20:14:44'),
(1275, 32, 'http://localhost/lineup_campus/aktif-destek-talepleri', 185, '2025-04-28 20:17:49'),
(1276, 32, 'http://localhost/lineup_campus/aktif-destek-talepleri', 129, '2025-04-28 20:19:59'),
(1277, 32, 'http://localhost/lineup_campus/dashboard', 1, '2025-04-28 20:22:30'),
(1278, 32, 'http://localhost/lineup_campus/aktif-destek-talepleri', 55, '2025-04-28 20:55:48'),
(1279, 32, 'http://localhost/lineup_campus/aktif-destek-talepleri', 142, '2025-04-28 20:58:11'),
(1280, 32, 'http://localhost/lineup_campus/aktif-destek-talepleri', 38, '2025-04-28 20:58:50'),
(1281, 32, 'http://localhost/lineup_campus/aktif-destek-talepleri', 54, '2025-04-28 20:59:45'),
(1282, 32, 'http://localhost/lineup_campus/aktif-destek-talepleri', 94, '2025-04-28 21:01:20'),
(1283, 32, 'http://localhost/lineup_campus/aktif-destek-talepleri', 51, '2025-04-28 21:02:12'),
(1284, 32, 'http://localhost/lineup_campus/aktif-destek-talepleri', 4, '2025-04-28 21:02:17'),
(1285, 32, 'http://localhost/lineup_campus/aktif-destek-talepleri', 35, '2025-04-28 21:02:54'),
(1286, 32, 'http://localhost/lineup_campus/aktif-destek-talepleri', 4, '2025-04-28 21:02:59'),
(1287, 32, 'http://localhost/lineup_campus/aktif-destek-talepleri', 15, '2025-04-28 21:03:15'),
(1288, 32, 'http://localhost/lineup_campus/aktif-destek-talepleri', 44, '2025-04-28 21:04:01'),
(1289, 32, 'http://localhost/lineup_campus/aktif-destek-talepleri', 13, '2025-04-28 21:04:15'),
(1290, 32, 'http://localhost/lineup_campus/aktif-destek-talepleri', 19, '2025-04-28 21:04:35'),
(1291, 32, 'http://localhost/lineup_campus/aktif-destek-talepleri', 93, '2025-04-28 21:06:09'),
(1292, 32, 'http://localhost/lineup_campus/aktif-destek-talepleri', 12, '2025-04-28 21:06:22'),
(1293, 32, 'http://localhost/lineup_campus/aktif-destek-talepleri', 87, '2025-04-28 21:07:50'),
(1294, 32, 'http://localhost/lineup_campus/aktif-destek-talepleri', 61, '2025-04-28 21:08:53'),
(1295, 32, 'http://localhost/lineup_campus/aktif-destek-talepleri', 47, '2025-04-28 21:09:42'),
(1296, 32, 'http://localhost/lineup_campus/destek-talebi', 133, '2025-04-28 21:11:56'),
(1297, 32, 'http://localhost/lineup_campus/dashboard', 3, '2025-04-28 21:12:00'),
(1298, 32, 'http://localhost/lineup_campus/destek-talebi', 2990, '2025-04-28 21:12:22'),
(1299, 32, 'http://localhost/lineup_campus/aktif-destek-talepleri', 203, '2025-04-28 21:15:48'),
(1300, 32, 'http://localhost/lineup_campus/aktif-destek-talepleri', 31, '2025-04-28 21:16:20'),
(1301, 32, 'http://localhost/lineup_campus/aktif-destek-talepleri', 20, '2025-04-28 21:16:42'),
(1302, 32, 'http://localhost/lineup_campus/testler-ogrenci', 5, '2025-04-28 21:16:48'),
(1303, 32, 'http://localhost/lineup_campus/alt-konu/baskentler', 1, '2025-04-28 21:16:50'),
(1304, 32, 'http://localhost/lineup_campus/testler-ogrenci', 164, '2025-04-28 21:19:35'),
(1305, 32, 'http://localhost/lineup_campus/aktif-destek-talepleri', 3, '2025-04-28 21:19:39'),
(1306, 32, 'http://localhost/lineup_campus/aktif-destek-talepleri', 99, '2025-04-28 21:21:19'),
(1307, 32, 'http://localhost/lineup_campus/aktif-destek-talepleri', 2, '2025-04-28 21:21:22'),
(1308, 32, 'http://localhost/lineup_campus/index', 4276, '2025-04-28 21:21:34'),
(1309, 32, 'http://localhost/lineup_campus/aktif-destek-talepleri', 3, '2025-04-28 21:21:37'),
(1310, 32, 'http://localhost/lineup_campus/aktif-destek-talepleri', 34, '2025-04-28 21:22:12'),
(1311, 32, 'http://localhost/lineup_campus/aktif-destek-talepleri', 23, '2025-04-28 21:22:37'),
(1312, 32, 'http://localhost/lineup_campus/aktif-destek-talepleri', 8, '2025-04-28 21:22:46'),
(1313, 32, 'http://localhost/lineup_campus/aktif-destek-talepleri', 21, '2025-04-28 21:23:08'),
(1314, 32, 'http://localhost/lineup_campus/aktif-destek-talepleri', 35, '2025-04-28 21:23:45'),
(1315, 32, 'http://localhost/lineup_campus/aktif-destek-talepleri', 4, '2025-04-28 21:23:50'),
(1316, 32, 'http://localhost/lineup_campus/destek-talebi', 725, '2025-04-28 21:24:06'),
(1317, 32, 'http://localhost/lineup_campus/aktif-destek-talepleri', 17, '2025-04-28 21:24:08'),
(1318, 32, 'http://localhost/lineup_campus/aktif-destek-talepleri', 10, '2025-04-28 21:24:19'),
(1319, 32, 'http://localhost/lineup_campus/aktif-destek-talepleri', 29, '2025-04-28 21:24:48'),
(1320, 32, 'http://localhost/lineup_campus/aktif-destek-talepleri', 46, '2025-04-28 21:25:36'),
(1321, 32, 'http://localhost/lineup_campus/aktif-destek-talepleri', 73, '2025-04-28 21:26:50'),
(1322, 32, 'http://localhost/lineup_campus/aktif-destek-talepleri', 18, '2025-04-28 21:27:09'),
(1323, 32, 'http://localhost/lineup_campus/aktif-destek-talepleri', 18, '2025-04-28 21:27:28'),
(1324, 32, 'http://localhost/lineup_campus/aktif-destek-talepleri', 1, '2025-04-28 21:27:30'),
(1325, 32, 'http://localhost/lineup_campus/aktif-destek-talepleri', 16, '2025-04-28 21:27:48'),
(1326, 32, 'http://localhost/lineup_campus/aktif-destek-talepleri', 41, '2025-04-28 21:28:29'),
(1327, 32, 'http://localhost/lineup_campus/aktif-destek-talepleri', 36, '2025-04-28 21:29:07'),
(1328, 32, 'http://localhost/lineup_campus/aktif-destek-talepleri', 1, '2025-04-28 21:29:09'),
(1329, 32, 'http://localhost/lineup_campus/aktif-destek-talepleri', 130, '2025-04-28 21:31:21'),
(1330, 32, 'http://localhost/lineup_campus/aktif-destek-talepleri', 173, '2025-04-28 21:34:16'),
(1331, 32, 'http://localhost/lineup_campus/aktif-destek-talepleri', 21, '2025-04-28 21:34:37'),
(1332, 32, 'http://localhost/lineup_campus/aktif-destek-talepleri', 60, '2025-04-28 21:35:38'),
(1333, 32, 'http://localhost/lineup_campus/aktif-destek-talepleri', 35, '2025-04-28 21:36:14'),
(1334, 32, 'http://localhost/lineup_campus/aktif-destek-talepleri', 41, '2025-04-28 21:36:57'),
(1335, 32, 'http://localhost/lineup_campus/aktif-destek-talepleri', 310, '2025-04-28 21:42:08'),
(1336, 32, 'http://localhost/lineup_campus/aktif-destek-talepleri', 29, '2025-04-28 21:44:16'),
(1337, 32, 'http://localhost/lineup_campus/aktif-destek-talepleri', 28, '2025-04-28 21:44:46'),
(1338, 32, 'http://localhost/lineup_campus/aktif-destek-talepleri', 57, '2025-04-28 21:45:44'),
(1339, 32, 'http://localhost/lineup_campus/aktif-destek-talepleri', 20, '2025-04-28 21:46:05'),
(1340, 32, 'http://localhost/lineup_campus/aktif-destek-talepleri', 261, '2025-04-28 21:50:27'),
(1341, 32, 'http://localhost/lineup_campus/aktif-destek-talepleri', 101, '2025-04-28 21:52:09'),
(1342, 32, 'http://localhost/lineup_campus/aktif-destek-talepleri', 8, '2025-04-28 21:52:19'),
(1343, 32, 'http://localhost/lineup_campus/aktif-destek-talepleri', 11, '2025-04-28 21:52:30'),
(1344, 32, 'http://localhost/lineup_campus/aktif-destek-talepleri', 515, '2025-04-28 22:01:06'),
(1345, 32, 'http://localhost/lineup_campus/destek-talebi', 5, '2025-04-28 22:01:12'),
(1346, 32, 'http://localhost/lineup_campus/aktif-destek-talepleri', 63, '2025-04-28 22:02:16'),
(1347, 32, 'http://localhost/lineup_campus/aktif-destek-talepleri', 1, '2025-04-28 22:02:18'),
(1348, 32, 'http://localhost/lineup_campus/destek-talebi-ekle', 2, '2025-04-28 22:02:21'),
(1349, 32, 'http://localhost/lineup_campus/aktif-destek-talepleri', 2, '2025-04-28 22:02:24'),
(1350, 32, 'http://localhost/lineup_campus/destek-talebi-ekle', 1, '2025-04-28 22:02:26'),
(1351, 32, 'http://localhost/lineup_campus/aktif-destek-talepleri', 85, '2025-04-28 22:03:52'),
(1352, 32, 'http://localhost/lineup_campus/aktif-destek-talepleri', 22, '2025-04-28 22:04:15'),
(1353, 32, 'http://localhost/lineup_campus/aktif-destek-talepleri', 1, '2025-04-28 22:04:17'),
(1354, 32, 'http://localhost/lineup_campus/destek-talebi?id=8', 295, '2025-04-28 22:23:01'),
(1355, 32, 'http://localhost/lineup_campus/destek-talebi?id=8', 68, '2025-04-28 22:24:10'),
(1356, 32, 'http://localhost/lineup_campus/destek-talebi?id=8', 35, '2025-04-28 22:24:46'),
(1357, 32, 'http://localhost/lineup_campus/destek-talebi?id=8', 57, '2025-04-28 22:25:44'),
(1358, 32, 'http://localhost/lineup_campus/destek-talebi?id=8', 36, '2025-04-28 22:26:50'),
(1359, 32, 'http://localhost/lineup_campus/destek-talebi?id=deneme-sikayeti', 27, '2025-04-28 22:27:18'),
(1360, 32, 'http://localhost/lineup_campus/destek-talebi?id=deneme-sikayeti', 25, '2025-04-28 22:27:44'),
(1361, 32, 'http://localhost/lineup_campus/destek-talebi?id=8', 1981, '2025-04-28 22:37:19'),
(1362, 32, 'http://localhost/lineup_campus/destek-talebi?id=deneme-sikayeti', 578, '2025-04-28 22:37:24'),
(1363, 32, 'http://localhost/lineup_campus/destek-talebi?id=deneme-sikayeti', 36, '2025-04-28 22:38:37'),
(1364, 32, 'http://localhost/lineup_campus/destek-talebi?id=deneme-sikayeti', 6, '2025-04-28 22:38:44'),
(1365, 32, 'http://localhost/lineup_campus/destek-talebi?id=deneme-sikayeti', 29, '2025-04-28 22:39:13'),
(1366, 32, 'http://localhost/lineup_campus/destek-talebi?id=deneme-sikayeti', 41, '2025-04-28 22:39:56'),
(1367, 32, 'http://localhost/lineup_campus/destek-talebi?id=deneme-sikayeti', 29, '2025-04-28 22:40:26'),
(1368, 32, 'http://localhost/lineup_campus/destek-talebi?id=deneme-sikayeti', 20, '2025-04-28 22:40:48'),
(1369, 32, 'http://localhost/lineup_campus/destek-talebi?id=deneme-sikayeti', 17, '2025-04-28 22:41:05'),
(1370, 32, 'http://localhost/lineup_campus/destek-talebi?id=deneme-sikayeti', 14, '2025-04-28 22:41:21'),
(1371, 32, 'http://localhost/lineup_campus/destek-talebi?id=deneme-sikayeti', 171, '2025-04-28 22:44:12'),
(1372, 32, 'http://localhost/lineup_campus/destek-talebi?id=deneme-sikayeti', 30, '2025-04-28 22:44:43'),
(1373, 32, 'http://localhost/lineup_campus/destek-talebi?id=deneme-sikayeti', 21, '2025-04-28 22:45:05'),
(1374, 32, 'http://localhost/lineup_campus/destek-talebi?id=deneme-sikayeti', 50, '2025-04-28 22:45:56'),
(1375, 32, 'http://localhost/lineup_campus/destek-talebi?id=deneme-sikayeti', 14, '2025-04-28 22:46:11'),
(1376, 32, 'http://localhost/lineup_campus/destek-talebi?id=deneme-sikayeti', 11, '2025-04-28 22:46:41'),
(1377, 32, 'http://localhost/lineup_campus/aktif-destek-talepleri', 1, '2025-04-28 22:47:47'),
(1378, 32, 'http://localhost/lineup_campus/destek-talebi?id=deneme-sikayeti', 68, '2025-04-28 22:48:56'),
(1379, 32, 'http://localhost/lineup_campus/destek-talebi?id=deneme-sikayeti', 184, '2025-04-28 22:52:01'),
(1380, 32, 'http://localhost/lineup_campus/destek-talebi?id=deneme-sikayeti', 18, '2025-04-28 22:52:21'),
(1381, 32, 'http://localhost/lineup_campus/destek-talebi?id=deneme-sikayeti', 1, '2025-04-28 22:52:22'),
(1382, 32, 'http://localhost/lineup_campus/dashboard', 0, '2025-04-28 22:52:24'),
(1383, 32, 'http://localhost/lineup_campus/destek-talebi?id=deneme-sikayeti', 2, '2025-04-28 22:52:26'),
(1384, 32, 'http://localhost/lineup_campus/destek-talebi?id=deneme-sikayeti', 1, '2025-04-28 22:52:28'),
(1385, 32, 'http://localhost/lineup_campus/dashboard', 0, '2025-04-28 22:52:30'),
(1386, 32, 'http://localhost/lineup_campus/destek-talebi?id=deneme-sikayeti', 52, '2025-04-28 22:53:23'),
(1387, 32, 'http://localhost/lineup_campus/destek-talebi?id=deneme-sikayeti', 1, '2025-04-28 22:53:24'),
(1388, 32, 'http://localhost/lineup_campus/dashboard', 0, '2025-04-28 22:53:26'),
(1389, 32, 'http://localhost/lineup_campus/destek-talebi?id=deneme-sikayeti', 2, '2025-04-28 22:53:28'),
(1390, 32, 'http://localhost/lineup_campus/dashboard', 0, '2025-04-28 22:53:29'),
(1391, 32, 'http://localhost/lineup_campus/destek-talebi?id=deneme-sikayeti', 104, '2025-04-28 22:55:14'),
(1392, 32, 'http://localhost/lineup_campus/destek-talebi?id=deneme-sikayeti', 386, '2025-04-28 23:01:42'),
(1393, 32, 'http://localhost/lineup_campus/destek-talebi?id=deneme-sikayeti', 571, '2025-04-28 23:11:14'),
(1394, 32, 'http://localhost/lineup_campus/destek-talebi?id=deneme-sikayeti', 40, '2025-04-28 23:11:55'),
(1395, 32, 'http://localhost/lineup_campus/destek-talebi?id=deneme-sikayeti', 59, '2025-04-28 23:13:09'),
(1396, 32, 'http://localhost/lineup_campus/destek-talebi?id=deneme-sikayeti', 23, '2025-04-28 23:13:33'),
(1397, 32, 'http://localhost/lineup_campus/destek-talebi?id=deneme-sikayeti', 38, '2025-04-28 23:14:13'),
(1398, 32, 'http://localhost/lineup_campus/destek-talebi?id=deneme-sikayeti', 50, '2025-04-28 23:15:04'),
(1399, 32, 'http://localhost/lineup_campus/destek-talebi?id=deneme-sikayeti', 202, '2025-04-28 23:18:27'),
(1400, 32, 'http://localhost/lineup_campus/aktif-destek-talepleri', 5, '2025-04-28 23:18:32'),
(1401, 32, 'http://localhost/lineup_campus/destek-talebi?id=deneme-sikayeti', 113, '2025-04-28 23:20:27'),
(1402, 32, 'http://localhost/lineup_campus/aktif-destek-talepleri', 102, '2025-04-28 23:22:10'),
(1403, 32, 'http://localhost/lineup_campus/aktif-destek-talepleri', 1, '2025-04-28 23:22:12'),
(1404, 32, 'http://localhost/lineup_campus/destek-talebi?id=deneme-sikayeti', 11, '2025-04-28 23:22:24'),
(1405, 32, 'http://localhost/lineup_campus/aktif-destek-talepleri', 30, '2025-04-28 23:22:55'),
(1406, 32, 'http://localhost/lineup_campus/destek-talebi?id=dene-oneri', 5, '2025-04-28 23:23:01'),
(1407, 32, 'http://localhost/lineup_campus/aktif-destek-talepleri', 2, '2025-04-28 23:23:04'),
(1408, 32, 'http://localhost/lineup_campus/destek-talebi?id=deneme-sikayeti', 166, '2025-04-28 23:25:51'),
(1409, 32, 'http://localhost/lineup_campus/destek-talebi?id=deneme-sikayeti', 440, '2025-04-28 23:33:13'),
(1410, 32, 'http://localhost/lineup_campus/destek-talebi?id=deneme-sikayeti', 18, '2025-04-28 23:33:31'),
(1411, 32, 'http://localhost/lineup_campus/destek-talebi?id=deneme-sikayeti', 6, '2025-04-28 23:33:39'),
(1412, 32, 'http://localhost/lineup_campus/destek-talebi?id=dene-oneri', 7, '2025-04-28 23:36:33'),
(1413, 32, 'http://localhost/lineup_campus/destek-talebi?id=deneme-sikayeti', 106, '2025-04-28 23:38:20'),
(1414, 32, 'http://localhost/lineup_campus/destek-talebi?id=deneme-sikayeti', 20, '2025-04-28 23:38:41'),
(1415, 32, 'http://localhost/lineup_campus/destek-talebi?id=deneme-sikayeti', 7, '2025-04-28 23:38:49'),
(1416, 32, 'http://localhost/lineup_campus/destek-talebi?id=deneme-sikayeti', 38, '2025-04-28 23:39:28'),
(1417, 32, 'http://localhost/lineup_campus/destek-talebi?id=deneme-sikayeti', 10, '2025-04-28 23:39:40'),
(1418, 32, 'http://localhost/lineup_campus/destek-talebi?id=deneme-sikayeti', 24, '2025-04-28 23:40:05'),
(1419, 32, 'http://localhost/lineup_campus/aktif-destek-talepleri', 81, '2025-04-28 23:41:27'),
(1420, 32, 'http://localhost/lineup_campus/aktif-destek-talepleri', 8, '2025-04-28 23:41:36'),
(1421, 32, 'http://localhost/lineup_campus/aktif-destek-talepleri', 37, '2025-04-28 23:42:05'),
(1422, 32, 'http://localhost/lineup_campus/cozulmus-destek-talepleri', 144, '2025-04-28 23:44:31'),
(1423, 32, 'http://localhost/lineup_campus/cozulmus-destek-talepleri', 2, '2025-04-28 23:44:34'),
(1424, 32, 'http://localhost/lineup_campus/aktif-destek-talepleri', 1, '2025-04-28 23:44:36'),
(1425, 32, 'http://localhost/lineup_campus/cozulmus-destek-talepleri', 11, '2025-04-28 23:44:48'),
(1426, 32, 'http://localhost/lineup_campus/cozulmus-destek-talepleri', 1, '2025-04-28 23:44:51'),
(1427, 32, 'http://localhost/lineup_campus/aktif-destek-talepleri', 1, '2025-04-28 23:44:53'),
(1428, 32, 'http://localhost/lineup_campus/cozulmus-destek-talepleri', 14, '2025-04-28 23:45:09'),
(1429, 32, 'http://localhost/lineup_campus/destek-talebi?id=deneme-sikayeti', 7, '2025-04-28 23:45:16'),
(1430, 32, 'http://localhost/lineup_campus/cozulmus-destek-talepleri', 24, '2025-04-28 23:45:18'),
(1431, 32, 'http://localhost/lineup_campus/destek-talebi?id=8', 4238, '2025-04-28 23:47:58'),
(1432, 1, 'http://localhost/lineup_campus/dashboard', 39, '2025-04-28 23:49:13'),
(1433, 1, 'http://localhost/lineup_campus/dashboard', 54, '2025-04-28 23:50:08'),
(1434, 1, 'http://localhost/lineup_campus/dashboard', 3, '2025-04-28 23:50:13'),
(1435, 1, 'http://localhost/lineup_campus/aktif-destek-talepleri', 164, '2025-04-28 23:52:58'),
(1436, 1, 'http://localhost/lineup_campus/aktif-destek-talepleri', 10, '2025-04-28 23:53:09'),
(1437, 1, 'http://localhost/lineup_campus/dashboard', 1, '2025-04-28 23:53:11'),
(1438, 1, 'http://localhost/lineup_campus/aktif-destek-talepleri', 144, '2025-04-28 23:55:36'),
(1439, 1, 'http://localhost/lineup_campus/cozulmus-destek-talepleri', 2, '2025-04-28 23:55:39'),
(1440, 1, 'http://localhost/lineup_campus/aktif-destek-talepleri', 1, '2025-04-28 23:55:41'),
(1441, 1, 'http://localhost/lineup_campus/cozulmus-destek-talepleri', 18, '2025-04-28 23:56:01'),
(1442, 1, 'http://localhost/lineup_campus/cozulmus-destek-talepleri', 4, '2025-04-28 23:56:05'),
(1443, 1, 'http://localhost/lineup_campus/aktif-destek-talepleri', 2, '2025-04-28 23:56:08'),
(1444, 1, 'http://localhost/lineup_campus/cozulmus-destek-talepleri', 6, '2025-04-28 23:56:16'),
(1445, 1, 'http://localhost/lineup_campus/dashboard', 24, '2025-04-28 23:56:41'),
(1446, 1, 'http://localhost/lineup_campus/cozulmus-destek-talepleri', 1, '2025-04-28 23:56:43'),
(1447, 1, 'http://localhost/lineup_campus/cozulmus-destek-talepleri', 0, '2025-04-28 23:56:44'),
(1448, 32, 'http://localhost/lineup_campus/destek-talebi?id=deneme-sikayeti', 4221, '2025-04-28 23:57:02'),
(1449, 1, 'http://localhost/lineup_campus/destek-talebi?id=deneme-sikayeti', 236, '2025-04-29 00:00:41'),
(1450, 1, 'http://localhost/lineup_campus/destek-talebi?id=deneme-sikayeti', 8, '2025-04-29 00:00:51'),
(1451, 1, 'http://localhost/lineup_campus/aktif-destek-talepleri', 2, '2025-04-29 00:00:53'),
(1452, 1, 'http://localhost/lineup_campus/destek-talebi?id=dene-oneri', 96, '2025-04-29 00:02:31'),
(1453, 1, 'http://localhost/lineup_campus/destek-talebi?id=dene-oneri', 128, '2025-04-29 00:04:40'),
(1454, 32, 'http://localhost/lineup_campus/destek-talebi?id=deneme-sikayeti', 462, '2025-04-29 00:04:45'),
(1455, 32, 'http://localhost/lineup_campus/aktif-destek-talepleri', 7, '2025-04-29 00:04:53'),
(1456, 32, 'http://localhost/lineup_campus/destek-talebi-ekle', 0, '2025-04-29 00:04:55'),
(1457, 32, 'http://localhost/lineup_campus/aktif-destek-talepleri', 0, '2025-04-29 00:04:57'),
(1458, 32, 'http://localhost/lineup_campus/cozulmus-destek-talepleri', 1, '2025-04-29 00:04:59'),
(1459, 1, 'http://localhost/lineup_campus/destek-talebi?id=dene-oneri', 36, '2025-04-29 00:05:17'),
(1460, 1, 'http://localhost/lineup_campus/aktif-destek-talepleri', 2, '2025-04-29 00:05:20'),
(1461, 32, 'http://localhost/lineup_campus/aktif-destek-talepleri', 52, '2025-04-29 00:05:52'),
(1462, 32, 'http://localhost/lineup_campus/aktif-destek-talepleri', 1, '2025-04-29 00:05:54'),
(1463, 32, 'http://localhost/lineup_campus/aktif-destek-talepleri', 15, '2025-04-29 00:06:10'),
(1464, 32, 'http://localhost/lineup_campus/aktif-destek-talepleri', 137, '2025-04-29 00:08:29'),
(1465, 32, 'http://localhost/lineup_campus/aktif-destek-talepleri', 5, '2025-04-29 00:08:34'),
(1466, 32, 'http://localhost/lineup_campus/cozulmus-destek-talepleri', 12, '2025-04-29 00:08:48'),
(1467, 32, 'http://localhost/lineup_campus/cozulmus-destek-talepleri', 5, '2025-04-29 00:08:54'),
(1468, 32, 'http://localhost/lineup_campus/aktif-destek-talepleri', 4, '2025-04-29 00:08:59'),
(1469, 32, 'http://localhost/lineup_campus/aktif-destek-talepleri', 15, '2025-04-29 00:09:16'),
(1470, 32, 'http://localhost/lineup_campus/aktif-destek-talepleri', 43, '2025-04-29 00:10:00'),
(1471, 32, 'http://localhost/lineup_campus/aktif-destek-talepleri', 1, '2025-04-29 00:10:02'),
(1472, 32, 'http://localhost/lineup_campus/cozulmus-destek-talepleri', 1, '2025-04-29 00:10:05'),
(1473, 1, 'http://localhost/lineup_campus/aktif-destek-talepleri', 291, '2025-04-29 00:10:13'),
(1474, 1, 'http://localhost/lineup_campus/destek-talebi?id=dene-oneri', 15, '2025-04-29 00:10:29'),
(1475, 32, 'http://localhost/lineup_campus/aktif-destek-talepleri', 30, '2025-04-29 00:10:36'),
(1476, 32, 'http://localhost/lineup_campus/aktif-destek-talepleri', 92, '2025-04-29 00:12:09'),
(1477, 32, 'http://localhost/lineup_campus/destek-talebi?id=dene-oneri', 9, '2025-04-29 00:12:18'),
(1478, 32, 'http://localhost/lineup_campus/aktif-destek-talepleri', 2, '2025-04-29 00:12:22'),
(1479, 32, 'http://localhost/lineup_campus/cozulmus-destek-talepleri', 14, '2025-04-29 00:12:37'),
(1480, 32, 'http://localhost/lineup_campus/destek-talebi?id=dene-oneri', 0, '2025-04-29 00:12:39'),
(1481, 1, 'http://localhost/lineup_campus/aktif-destek-talepleri', 171, '2025-04-29 00:13:21'),
(1482, 1, 'http://localhost/lineup_campus/kullanici-yetkilendirme', 1, '2025-04-29 00:13:24'),
(1483, 1, 'http://localhost/lineup_campus/kullanici-gruplari', 1, '2025-04-29 00:13:26'),
(1484, 1, 'http://localhost/lineup_campus/kullanici-grup-detay/ogrenciler', 4, '2025-04-29 00:13:31'),
(1485, 1, 'http://localhost/lineup_campus/kullanici-gruplari', 55, '2025-04-29 00:14:28'),
(1486, 1, 'http://localhost/lineup_campus/kullanici-gruplari', 2, '2025-04-29 00:14:30'),
(1487, 1, 'http://localhost/lineup_campus/kullanici-gruplari', 0, '2025-04-29 00:14:32'),
(1488, 1, 'http://localhost/lineup_campus/kullanici-grup-detay?$q=ogrenciler', 41, '2025-04-29 00:15:14'),
(1489, 1, 'http://localhost/lineup_campus/kullanici-gruplari', 20, '2025-04-29 00:15:35'),
(1490, 1, 'http://localhost/lineup_campus/kullanici-gruplari', 1, '2025-04-29 00:15:38'),
(1491, 1, 'http://localhost/lineup_campus/kullanici-grup-detay?q=ogrenciler', 6, '2025-04-29 00:15:44'),
(1492, 1, 'http://localhost/lineup_campus/dashboard', 12, '2025-04-29 00:15:57'),
(1493, 1, 'http://localhost/lineup_campus/ogretmenler', 6, '2025-04-29 00:16:05'),
(1494, 32, 'http://localhost/lineup_campus/cozulmus-destek-talepleri', 283, '2025-04-29 00:17:24'),
(1495, 1, 'http://localhost/lineup_campus/dashboard', 86, '2025-04-29 00:17:32'),
(1496, 1, 'http://localhost/lineup_campus/dashboard', 5, '2025-04-29 00:17:37'),
(1497, 1, 'http://localhost/lineup_campus/veliler', 0, '2025-04-29 00:17:39'),
(1498, 1, 'http://localhost/lineup_campus/dashboard', 16, '2025-04-29 00:17:56'),
(1499, 1, 'http://localhost/lineup_campus/dashboard', 4, '2025-04-29 00:18:01'),
(1500, 1, 'http://localhost/lineup_campus/dashboard', 27, '2025-04-29 00:18:28'),
(1501, 1, 'http://localhost/lineup_campus/dashboard', 4, '2025-04-29 00:18:34'),
(1502, 1, 'http://localhost/lineup_campus/dashboard', 58, '2025-04-29 00:19:33'),
(1503, 1, 'http://localhost/lineup_campus/dashboard', 3, '2025-04-29 00:19:36'),
(1504, 1, 'http://localhost/lineup_campus/dashboard', 17, '2025-04-29 00:19:54'),
(1505, 1, 'http://localhost/lineup_campus/dashboard', 3, '2025-04-29 00:19:59'),
(1506, 1, 'http://localhost/lineup_campus/dashboard', 9, '2025-04-29 00:20:09'),
(1507, 1, 'http://localhost/lineup_campus/dashboard', 3, '2025-04-29 00:20:13'),
(1508, 1, 'http://localhost/lineup_campus/dashboard', 0, '2025-04-29 00:20:30'),
(1509, 1, 'http://localhost/lineup_campus/dashboard', 5, '2025-04-29 00:20:37'),
(1510, 32, 'http://localhost/lineup_campus/cozulmus-destek-talepleri', 2028, '2025-04-29 00:51:13'),
(1511, 32, 'http://localhost/lineup_campus/aktif-destek-talepleri', 1, '2025-04-29 01:00:00'),
(1512, 32, 'http://localhost/lineup_campus/destek-talebi-ekle', 245, '2025-04-29 01:04:06'),
(1513, 1, 'http://localhost/lineup_campus/ogrenciler', 3, '2025-04-29 08:32:27'),
(1514, 32, 'http://localhost/lineup_campus/dashboard', 27664, '2025-04-29 20:15:31'),
(1515, 32, 'http://localhost/lineup_campus/dashboard', 0, '2025-04-29 20:17:48'),
(1516, 32, 'http://localhost/lineup_campus/dashboard', 0, '2025-04-29 20:17:49'),
(1517, 32, 'http://localhost/lineup_campus/dashboard', 0, '2025-04-29 20:17:50'),
(1518, 32, 'http://localhost/lineup_campus/dashboard', 0, '2025-04-29 20:17:51'),
(1519, 32, 'http://localhost/lineup_campus/dashboard', 1, '2025-04-29 20:17:53'),
(1520, 32, 'http://localhost/lineup_campus/dashboard', 82454, '2025-04-29 20:18:21'),
(1521, 32, 'http://localhost/lineup_campus/dashboard', 4, '2025-05-02 11:22:18'),
(1522, 32, 'http://localhost/lineup_campus/sesli-kitap', 5, '2025-05-02 11:22:24'),
(1523, 32, 'http://localhost/lineup_campus/sesli-kitap', 21, '2025-05-02 11:22:48'),
(1524, 32, 'http://localhost/lineup_campus/sesli-kitap2', 449, '2025-05-02 11:33:35'),
(1525, 32, 'http://localhost/lineup_campus/sesli-kitap2', 165, '2025-05-02 11:36:20'),
(1526, 32, 'http://localhost/lineup_campus/sesli-kitap2', 18, '2025-05-02 11:36:39'),
(1527, 32, 'http://localhost/lineup_campus/sesli-kitap2', 112, '2025-05-02 11:38:32'),
(1528, 32, 'http://localhost/lineup_campus/ders/turkce', 2, '2025-05-02 11:39:39'),
(1529, 32, 'http://localhost/lineup_campus/unite/deneme-unite', 3, '2025-05-02 11:39:43'),
(1530, 32, 'http://localhost/lineup_campus/sesli-kitap2', 123, '2025-05-02 11:40:35'),
(1531, 32, 'http://localhost/lineup_campus/sesli-kitap2', 26, '2025-05-02 11:41:02'),
(1532, 32, 'http://localhost/lineup_campus/sesli-kitap2', 49, '2025-05-02 11:41:52'),
(1533, 32, 'http://localhost/lineup_campus/sesli-kitap2', 10, '2025-05-02 11:42:03'),
(1534, 32, 'http://localhost/lineup_campus/sesli-kitap2', 0, '2025-05-02 11:42:08'),
(1535, 32, 'http://localhost/lineup_campus/sesli-kitap2', 24, '2025-05-02 11:42:35'),
(1536, 32, 'http://localhost/lineup_campus/oyun', 1249, '2025-05-02 11:43:19'),
(1537, 1, 'http://localhost/lineup_campus/dashboard', 15, '2025-05-02 11:43:42'),
(1538, 1, 'http://localhost/lineup_campus/sesli-kitap', 509, '2025-05-02 11:51:05'),
(1539, 1, 'http://localhost/lineup_campus/dashboard', 83, '2025-05-02 11:52:29'),
(1540, 1, 'http://localhost/lineup_campus/dashboard', 73, '2025-05-02 11:53:43'),
(1541, 1, 'http://localhost/lineup_campus/dashboard', 15, '2025-05-02 11:53:59'),
(1542, 1, 'http://localhost/lineup_campus/kullanici-yetkilendirme', 2, '2025-05-02 11:54:03'),
(1543, 1, 'http://localhost/lineup_campus/kullanici-gruplari', 381, '2025-05-02 12:00:26'),
(1544, 1, 'http://localhost/lineup_campus/duyurular', 1, '2025-05-02 12:00:28'),
(1545, 1, 'http://localhost/lineup_campus/duyurular', 1, '2025-05-02 12:00:28'),
(1546, 1, 'http://localhost/lineup_campus/bildirimler', 98, '2025-05-02 12:02:07'),
(1547, 1, 'http://localhost/lineup_campus/alt-konular', 1, '2025-05-02 12:02:09'),
(1548, 1, 'http://localhost/lineup_campus/haftalik-gorev', 0, '2025-05-02 12:02:11'),
(1549, 1, 'http://localhost/lineup_campus/alt-konular', 2651, '2025-05-02 12:46:24'),
(1550, 1, 'http://localhost/lineup_campus/duyurular', 122, '2025-05-02 13:06:37'),
(1551, 32, 'http://localhost/lineup_campus/dashboard', 6, '2025-05-02 13:06:50'),
(1552, 32, 'http://localhost/lineup_campus/dashboard', 185, '2025-05-02 13:09:55'),
(1553, 32, 'http://localhost/lineup_campus/duyurular', 216, '2025-05-02 13:13:32'),
(1554, 32, 'http://localhost/lineup_campus/index', 7, '2025-05-02 13:13:46'),
(1555, 32, 'http://localhost/lineup_campus/dashboard', 3, '2025-05-02 13:13:49'),
(1556, 1, 'http://localhost/lineup_campus/dashboard', 4, '2025-05-02 13:14:09'),
(1557, 1, 'http://localhost/lineup_campus/duyurular', 1, '2025-05-02 13:14:11'),
(1558, 1, 'http://localhost/lineup_campus/bildirimler', 0, '2025-05-02 13:14:12'),
(1559, 32, 'http://localhost/lineup_campus/duyurular', 2421, '2025-05-02 13:26:46'),
(1560, 32, 'http://localhost/lineup_campus/duyurular', 312, '2025-05-02 13:31:59'),
(1561, 1, 'http://localhost/lineup_campus/duyurular', 1318, '2025-05-02 13:36:12'),
(1562, 32, 'http://localhost/lineup_campus/duyurular', 1368, '2025-05-02 13:36:21'),
(1563, 32, 'http://localhost/lineup_campus/duyurular', 86, '2025-05-02 13:37:48'),
(1564, 32, 'http://localhost/lineup_campus/duyurular', 161, '2025-05-02 13:40:30'),
(1565, 32, 'http://localhost/lineup_campus/duyurular', 3, '2025-05-02 13:40:34'),
(1566, 32, 'http://localhost/lineup_campus/duyurular', 1164, '2025-05-02 14:00:00'),
(1567, 1, 'http://localhost/lineup_campus/dashboard', 8, '2025-05-02 14:00:25'),
(1568, 1, 'http://localhost/lineup_campus/okullar', 25, '2025-05-02 14:00:52'),
(1569, 1, 'http://localhost/lineup_campus/haftalik-gorev', 82, '2025-05-02 14:02:14'),
(1570, 1, 'http://localhost/lineup_campus/test-ekle', 6, '2025-05-02 14:02:21'),
(1571, 1, 'http://localhost/lineup_campus/testler', 1, '2025-05-02 14:02:24'),
(1572, 1, 'http://localhost/lineup_campus/duyurular', 2311, '2025-05-02 14:14:45'),
(1573, 1, 'http://localhost/lineup_campus/test-ekle', 748, '2025-05-02 14:14:52'),
(1574, 32, 'http://localhost/lineup_campus/dashboard', 4, '2025-05-02 14:15:05'),
(1575, 32, 'http://localhost/lineup_campus/duyurular', 297, '2025-05-02 14:20:03'),
(1576, 1, 'http://localhost/lineup_campus/dashboard', 6, '2025-05-02 14:37:50'),
(1577, 1, 'http://localhost/lineup_campus/account/overview.html', 2, '2025-05-02 14:37:53'),
(1578, 1, 'http://localhost/lineup_campus/dashboard', 2, '2025-05-02 14:37:56'),
(1579, 1, 'http://localhost/lineup_campus/okullar', 0, '2025-05-02 14:37:58'),
(1580, 1, 'http://localhost/lineup_campus/dashboard', 2, '2025-05-02 14:38:00'),
(1581, 1, 'http://localhost/lineup_campus/okullar', 1, '2025-05-02 14:38:02'),
(1582, 1, 'http://localhost/lineup_campus/okul-detay/lineup-campus', 99, '2025-05-02 14:39:42'),
(1583, 1, 'http://localhost/lineup_campus/ogrenciler', 4, '2025-05-02 14:39:48'),
(1584, 1, 'http://localhost/lineup_campus/ogrenciler', 253, '2025-05-02 14:43:57'),
(1585, 1, 'http://localhost/lineup_campus/veliler', 125, '2025-05-02 14:46:03'),
(1586, 1, 'http://localhost/lineup_campus/dashboard', 3, '2025-05-02 16:44:58'),
(1587, 1, 'http://localhost/lineup_campus/ogrenciler', 7, '2025-05-02 16:45:06'),
(1588, 1, 'http://localhost/lineup_campus/ogretmenler', 11, '2025-05-02 16:45:18'),
(1589, 1, 'http://localhost/lineup_campus/duyurular', 9, '2025-05-02 16:45:29'),
(1590, 1, 'http://localhost/lineup_campus/duyurular', 85057, '2025-05-03 13:52:23'),
(1591, 1, 'http://localhost/lineup_campus/test-ekle', 76014, '2025-05-03 13:52:24'),
(1592, 1, 'http://localhost/lineup_campus/dashboard', 3, '2025-05-03 15:20:45'),
(1593, 1, 'http://localhost/lineup_campus/dashboard', 5, '2025-05-04 14:15:49'),
(1594, 1, 'http://localhost/lineup_campus/uniteler', 13, '2025-05-04 14:16:04'),
(1595, 1, 'http://localhost/lineup_campus/ogrenciler', 226, '2025-05-04 14:19:51'),
(1596, 1, 'http://localhost/lineup_campus/ogrenciler', 778, '2025-05-04 14:32:50'),
(1597, 1, 'http://localhost/lineup_campus/ogrenciler', 7, '2025-05-04 14:32:59'),
(1598, 1, 'http://localhost/lineup_campus/ogrenciler', 171, '2025-05-04 14:35:51'),
(1599, 1, 'http://localhost/lineup_campus/ogrenciler', 14, '2025-05-04 14:36:05'),
(1600, 1, 'http://localhost/lineup_campus/ogrenciler', 231, '2025-05-04 14:39:58'),
(1601, 1, 'http://localhost/lineup_campus/ogrenciler', 2540, '2025-05-04 15:22:19'),
(1602, 1, 'http://localhost/lineup_campus/hesap-olustur', 74887, '2025-05-05 08:41:02'),
(1603, 1, 'http://localhost/lineup_campus/hesap-olustur', 21, '2025-05-05 08:41:24'),
(1604, 1, 'http://localhost/lineup_campus/odeme-al.php', 23, '2025-05-05 08:41:49'),
(1605, 1, 'http://localhost/lineup_campus/hesap-olustur', 20, '2025-05-05 08:44:09'),
(1606, 1, 'http://localhost/lineup_campus/odeme-al.php', 16, '2025-05-05 08:44:26'),
(1607, 1, 'http://localhost/lineup_campus/hesap-olustur', 238, '2025-05-05 08:51:50'),
(1608, 1, 'http://localhost/lineup_campus/odeme-al.php', 49, '2025-05-05 08:52:40'),
(1609, 59, 'http://localhost/lineup_campus/index', 26, '2025-05-05 22:43:18'),
(1610, 58, 'http://localhost/lineup_campus/dashboard', 6, '2025-05-05 22:43:26'),
(1611, 58, 'http://localhost/lineup_campus/ders/turkce', 3, '2025-05-05 22:43:30'),
(1612, 1, 'http://localhost/lineup_campus/dashboard', 6, '2025-05-05 22:43:48'),
(1613, 1, 'http://localhost/lineup_campus/ogrenciler', 6, '2025-05-05 22:43:55'),
(1614, 1, 'http://localhost/lineup_campus/ogrenciler', 1, '2025-05-05 22:43:57'),
(1615, 1, 'http://localhost/lineup_campus/ogrenciler', 222, '2025-05-05 22:47:42'),
(1616, 1, 'http://localhost/lineup_campus/veliler', 39, '2025-05-05 22:48:22'),
(1617, 1, 'http://localhost/lineup_campus/veliler', 236, '2025-05-05 22:52:19'),
(1618, 1, 'http://localhost/lineup_campus/dashboard', 2, '2025-05-05 22:56:33'),
(1619, 1, 'http://localhost/lineup_campus/ogrenciler', 2, '2025-05-05 22:56:36'),
(1620, 1, 'http://localhost/lineup_campus/veliler', 166, '2025-05-05 22:59:23'),
(1621, 1, 'http://localhost/lineup_campus/hesap-olustur', 8128, '2025-05-05 22:59:35'),
(1622, 1, 'http://localhost/lineup_campus/sesli-kitaplar', 168, '2025-05-05 23:02:13'),
(1623, 1, 'http://localhost/lineup_campus/sesli-kitaplar', 172, '2025-05-05 23:05:06'),
(1624, 1, 'http://localhost/lineup_campus/sesli-kitaplar', 26, '2025-05-05 23:05:34'),
(1625, 32, 'http://localhost/lineup_campus/dashboard', 3, '2025-05-05 23:06:49'),
(1626, 32, 'http://localhost/lineup_campus/sesli-kitap', 13, '2025-05-05 23:07:03'),
(1627, 32, 'http://localhost/lineup_campus/sesli-kitap', 161, '2025-05-05 23:09:45'),
(1628, 32, 'http://localhost/lineup_campus/sesli-kitap', 24, '2025-05-05 23:10:09'),
(1629, 32, 'http://localhost/lineup_campus/sesli-kitap', 5, '2025-05-05 23:10:15'),
(1630, 32, 'http://localhost/lineup_campus/sesli-kitap', 0, '2025-05-05 23:12:14'),
(1631, 32, 'http://localhost/lineup_campus/sesli-kitap', 2, '2025-05-05 23:12:17'),
(1632, 32, 'http://localhost/lineup_campus/sesli-kitap', 36, '2025-05-05 23:13:36'),
(1633, 1, 'http://localhost/lineup_campus/sesli-kitaplar', 489, '2025-05-05 23:13:44'),
(1634, 1, 'http://localhost/lineup_campus/index', 43022, '2025-05-05 23:15:27'),
(1635, 1, 'http://localhost/lineup_campus/dashboard', 5, '2025-05-05 23:15:34'),
(1636, 1, 'http://localhost/lineup_campus/oyunlar', 65, '2025-05-05 23:16:40'),
(1637, 1, 'http://localhost/lineup_campus/oyunlar', 204, '2025-05-05 23:20:06'),
(1638, 32, 'http://localhost/lineup_campus/sesli-kitap', 404, '2025-05-05 23:20:21'),
(1639, 32, 'http://localhost/lineup_campus/dashboard', 0, '2025-05-05 23:20:22'),
(1640, 32, 'http://localhost/lineup_campus/sesli-kitap', 1, '2025-05-05 23:20:24'),
(1641, 32, 'http://localhost/lineup_campus/oyun', 50, '2025-05-05 23:21:15'),
(1642, 32, 'http://localhost/lineup_campus/oyun', 239, '2025-05-05 23:25:14'),
(1643, 32, 'http://localhost/lineup_campus/oyun', 65, '2025-05-05 23:27:28'),
(1644, 32, 'http://localhost/lineup_campus/oyun', 3, '2025-05-05 23:28:07'),
(1645, 1, 'http://localhost/lineup_campus/oyunlar', 879, '2025-05-05 23:28:25'),
(1646, 1, 'http://localhost/lineup_campus/oyunlar', 185, '2025-05-05 23:31:31'),
(1647, 1, 'http://localhost/lineup_campus/oyunlar', 50, '2025-05-05 23:32:23'),
(1648, 1, 'http://localhost/lineup_campus/oyunlar', 16, '2025-05-05 23:32:39'),
(1649, 32, 'http://localhost/lineup_campus/oyun-oyna/deneme-sinif', 291, '2025-05-05 23:32:59'),
(1650, 32, 'http://localhost/lineup_campus/sesli-kitap', 2, '2025-05-05 23:33:03'),
(1651, 32, 'http://localhost/lineup_campus/oyun', 1, '2025-05-05 23:33:04'),
(1652, 1, 'http://localhost/lineup_campus/sesli-kitaplar', 158, '2025-05-05 23:35:18'),
(1653, 1, 'http://localhost/lineup_campus/sesli-kitaplar', 17, '2025-05-05 23:35:59'),
(1654, 1, 'http://localhost/lineup_campus/sesli-kitaplar', 2, '2025-05-05 23:36:03'),
(1655, 1, 'http://localhost/lineup_campus/dashboard', 0, '2025-05-05 23:36:04'),
(1656, 1, 'http://localhost/lineup_campus/oyunlar', 1422, '2025-05-05 23:43:49'),
(1657, 1, 'http://localhost/lineup_campus/okullar', 2, '2025-05-05 23:44:03'),
(1658, 1, 'http://localhost/lineup_campus/dashboard', 1, '2025-05-05 23:44:05'),
(1659, 1, 'http://localhost/lineup_campus/okullar', 2, '2025-05-05 23:44:08'),
(1660, 1, 'http://localhost/lineup_campus/okul-detay/lineup-campus', 106, '2025-05-05 23:45:55'),
(1661, 1, 'http://localhost/lineup_campus/dashboard', 1, '2025-05-05 23:45:57'),
(1662, 1, 'http://localhost/lineup_campus/ogrenciler', 31204, '2025-05-06 08:23:54'),
(1663, 32, 'http://localhost/lineup_campus/oyun-oyna/deneme-sinif', 34567, '2025-05-06 09:09:12'),
(1664, 1, 'http://localhost/lineup_campus/hesap-olustur', 114560, '2025-05-06 19:33:58'),
(1665, 1, 'http://localhost/lineup_campus/dashboard', 32838, '2025-05-06 19:33:59'),
(1666, 1, 'http://localhost/lineup_campus/okul-detay/lineup-campus', 0, '2025-05-06 19:34:22'),
(1667, 1, 'http://localhost/lineup_campus/dashboard', 21, '2025-05-07 09:41:05'),
(1668, 1, 'http://localhost/lineup_campus/dersler', 57, '2025-05-07 10:30:17'),
(1669, 1, 'http://localhost/lineup_campus/dersler', 68, '2025-05-07 10:31:27'),
(1670, 1, 'http://localhost/lineup_campus/dersler', 7, '2025-05-07 10:31:36'),
(1671, 1, 'http://localhost/lineup_campus/yas-grubu', 14, '2025-05-07 10:32:54'),
(1672, 1, 'http://localhost/lineup_campus/yas-grubu', 0, '2025-05-07 10:32:56'),
(1673, 1, 'http://localhost/lineup_campus/yas-grubu', 1071, '2025-05-07 10:50:49'),
(1674, 1, 'http://localhost/lineup_campus/yas-grubu', 1072, '2025-05-07 10:50:50'),
(1675, 1, 'http://localhost/lineup_campus/dashboard', 15, '2025-05-07 10:51:07'),
(1676, 1, 'http://localhost/lineup_campus/dashboard', 2, '2025-05-07 11:54:06'),
(1677, 1, 'http://localhost/lineup_campus/yas-grubu', 73, '2025-05-07 11:55:21'),
(1678, 1, 'http://localhost/lineup_campus/yas-grubu', 673, '2025-05-07 12:06:36'),
(1679, 1, 'http://localhost/lineup_campus/yas-grubu', 330, '2025-05-07 12:12:08'),
(1680, 1, 'http://localhost/lineup_campus/yas-grubu', 26, '2025-05-07 12:12:36'),
(1681, 1, 'http://localhost/lineup_campus/yas-grubu', 1, '2025-05-07 12:12:39'),
(1682, 1, 'http://localhost/lineup_campus/yas-grubu', 17, '2025-05-07 12:12:58'),
(1683, 1, 'http://localhost/lineup_campus/yas-grubu', 13, '2025-05-07 12:13:12'),
(1684, 1, 'http://localhost/lineup_campus/yas-grubu', 26, '2025-05-07 12:13:40'),
(1685, 1, 'http://localhost/lineup_campus/yas-grubu', 87, '2025-05-07 12:15:09'),
(1686, 1, 'http://localhost/lineup_campus/yas-grubu', 51, '2025-05-07 12:16:02'),
(1687, 1, 'http://localhost/lineup_campus/yas-grubu', 133, '2025-05-07 12:18:17'),
(1688, 1, 'http://localhost/lineup_campus/yas-grubu', 19, '2025-05-07 12:18:38'),
(1689, 1, 'http://localhost/lineup_campus/yas-grubu', 21, '2025-05-07 12:19:01'),
(1690, 1, 'http://localhost/lineup_campus/yas-grubu', 32, '2025-05-07 12:19:35'),
(1691, 1, 'http://localhost/lineup_campus/yas-grubu', 18, '2025-05-07 12:19:55'),
(1692, 1, 'http://localhost/lineup_campus/yas-grubu', 794, '2025-05-07 12:33:11'),
(1693, 1, 'http://localhost/lineup_campus/yas-grubu', 9, '2025-05-07 12:33:22'),
(1694, 1, 'http://localhost/lineup_campus/yas-grubu', 37, '2025-05-07 12:34:01'),
(1695, 1, 'http://localhost/lineup_campus/yas-grubu', 156, '2025-05-07 12:36:39'),
(1696, 1, 'http://localhost/lineup_campus/yas-grubu', 522, '2025-05-07 12:45:23'),
(1697, 1, 'http://localhost/lineup_campus/yas-grubu', 4, '2025-05-07 12:45:31'),
(1698, 1, 'http://localhost/lineup_campus/dashboard', 36, '2025-05-07 12:46:08'),
(1699, 1, 'http://localhost/lineup_campus/duyurular', 12, '2025-05-07 12:46:23'),
(1700, 1, 'http://localhost/lineup_campus/bildirimler', 25, '2025-05-07 12:46:50'),
(1701, 1, 'http://localhost/lineup_campus/kullanici-yetkilendirme', 38, '2025-05-07 12:47:30'),
(1702, 1, 'http://localhost/lineup_campus/oyunlar', 5, '2025-05-07 12:47:37'),
(1703, 1, 'http://localhost/lineup_campus/okul-detay/oyun-deneme', 3, '2025-05-07 12:47:41'),
(1704, 1, 'http://localhost/lineup_campus/dashboard', 60, '2025-05-07 12:48:42'),
(1705, 1, 'http://localhost/lineup_campus/dashboard', 61, '2025-05-07 12:48:44'),
(1706, 1, 'http://localhost/lineup_campus/kullanici-yetkilendirme', 5, '2025-05-07 12:48:51'),
(1707, 1, 'http://localhost/lineup_campus/bildirimler', 41, '2025-05-07 12:49:33'),
(1708, 1, 'http://localhost/lineup_campus/okul-detay/bildirim-1', 3, '2025-05-07 12:49:38'),
(1709, 1, 'http://localhost/lineup_campus/dashboard', 1, '2025-05-07 12:49:42'),
(1710, 1, 'http://localhost/lineup_campus/okul-detay/bildirim-1', 1, '2025-05-07 12:49:45'),
(1711, 1, 'http://localhost/lineup_campus/dashboard', 25, '2025-05-07 12:50:11'),
(1712, 1, 'http://localhost/lineup_campus/alt-konular', 1, '2025-05-07 12:50:14'),
(1713, 1, 'http://localhost/lineup_campus/altkonu-ekle', 340, '2025-05-07 12:55:56'),
(1714, 1, 'http://localhost/lineup_campus/siniflar', 7, '2025-05-07 12:56:05'),
(1715, 1, 'http://localhost/lineup_campus/dersler', 1, '2025-05-07 12:56:08');
INSERT INTO `timespend_lnp` (`id`, `user_id`, `sayfa_url`, `timeSpent`, `exitTime`) VALUES
(1716, 1, 'http://localhost/lineup_campus/uniteler', 11, '2025-05-07 12:56:21'),
(1717, 1, 'http://localhost/lineup_campus/konular', 6, '2025-05-07 12:56:29'),
(1718, 1, 'http://localhost/lineup_campus/alt-konular', 5, '2025-05-07 12:56:35'),
(1719, 1, 'http://localhost/lineup_campus/haftalik-gorev', 3, '2025-05-07 12:56:40'),
(1720, 1, 'http://localhost/lineup_campus/haftalik-gorev', 240, '2025-05-07 13:00:37'),
(1721, 1, 'http://localhost/lineup_campus/dashboard', 89, '2025-05-07 13:02:07'),
(1722, 1, 'http://localhost/lineup_campus/okullar', 15, '2025-05-07 13:02:24'),
(1723, 1, 'http://localhost/lineup_campus/alt-konular', 2, '2025-05-07 13:02:28'),
(1724, 1, 'http://localhost/lineup_campus/altkonu-ekle', 2356, '2025-05-07 13:41:45'),
(1725, 1, 'http://localhost/lineup_campus/yas-grubu', 757, '2025-05-07 13:54:25'),
(1726, 1, 'http://localhost/lineup_campus/siniflar', 5, '2025-05-07 13:54:32'),
(1727, 1, 'http://localhost/lineup_campus/yas-grubu', 90, '2025-05-07 13:56:04'),
(1728, 1, 'http://localhost/lineup_campus/yas-grubu', 199, '2025-05-07 13:59:25'),
(1729, 1, 'http://localhost/lineup_campus/siniflar', 6, '2025-05-07 13:59:33'),
(1730, 1, 'http://localhost/lineup_campus/yas-grubu', 3, '2025-05-07 13:59:38'),
(1731, 1, 'http://localhost/lineup_campus/yas-grubu', 11, '2025-05-07 13:59:50'),
(1732, 1, 'http://localhost/lineup_campus/yas-grubu', 9, '2025-05-07 14:00:01'),
(1733, 1, 'http://localhost/lineup_campus/okul-detay/8-9-yas', 2, '2025-05-07 14:00:04'),
(1734, 1, 'http://localhost/lineup_campus/yas-grubu', 27, '2025-05-07 14:00:33'),
(1735, 1, 'http://localhost/lineup_campus/siniflar', 2, '2025-05-07 14:00:38'),
(1736, 1, 'http://localhost/lineup_campus/okul-detay/1-sinif', 12, '2025-05-07 14:00:52'),
(1737, 1, 'http://localhost/lineup_campus/okul-detay/1-sinif', 13, '2025-05-07 14:00:53'),
(1738, 1, 'http://localhost/lineup_campus/okul-detay/1-sinif', 14, '2025-05-07 14:00:54'),
(1739, 1, 'http://localhost/lineup_campus/dashboard', 16, '2025-05-07 14:01:11'),
(1740, 1, 'http://localhost/lineup_campus/yas-grubu', 160, '2025-05-07 14:03:53'),
(1741, 1, 'http://localhost/lineup_campus/yas-grubu', 283, '2025-05-07 14:08:38'),
(1742, 1, 'http://localhost/lineup_campus/alt-konular', 1, '2025-05-07 14:08:41'),
(1743, 1, 'http://localhost/lineup_campus/altkonu-ekle', 759, '2025-05-07 14:21:22'),
(1744, 1, 'http://localhost/lineup_campus/altkonu-ekle', 1, '2025-05-07 14:21:25'),
(1745, 1, 'http://localhost/lineup_campus/yas-grubu', 31, '2025-05-07 14:21:58'),
(1746, 1, 'http://localhost/lineup_campus/yas-grubu', 6, '2025-05-07 14:22:06'),
(1747, 1, 'http://localhost/lineup_campus/yas-grubu', 9, '2025-05-07 14:22:17'),
(1748, 1, 'http://localhost/lineup_campus/yas-grubu', 22, '2025-05-07 14:22:41'),
(1749, 1, 'http://localhost/lineup_campus/yas-grubu', 11, '2025-05-07 14:22:54'),
(1750, 1, 'http://localhost/lineup_campus/yas-grubu', 55, '2025-05-07 14:23:51'),
(1751, 1, 'http://localhost/lineup_campus/yas-grubu', 11, '2025-05-07 14:24:04'),
(1752, 1, 'http://localhost/lineup_campus/yas-grubu', 71, '2025-05-07 14:25:17'),
(1753, 1, 'http://localhost/lineup_campus/yas-grubu', 202, '2025-05-07 14:28:41'),
(1754, 1, 'http://localhost/lineup_campus/yas-grubu', 15, '2025-05-07 14:28:59'),
(1755, 1, 'http://localhost/lineup_campus/yas-grubu', 13, '2025-05-07 14:29:14'),
(1756, 1, 'http://localhost/lineup_campus/yas-grubu', 103, '2025-05-07 14:30:59'),
(1757, 1, 'http://localhost/lineup_campus/yas-grubu', 67, '2025-05-07 14:32:09'),
(1758, 1, 'http://localhost/lineup_campus/yas-grubu', 373, '2025-05-07 14:38:24'),
(1759, 1, 'http://localhost/lineup_campus/yas-grubu', 49, '2025-05-07 14:39:15'),
(1760, 1, 'http://localhost/lineup_campus/yas-grubu', 18, '2025-05-07 14:39:35'),
(1761, 1, 'http://localhost/lineup_campus/yas-grubu', 155, '2025-05-07 14:42:12'),
(1762, 1, 'http://localhost/lineup_campus/yas-grubu', 47, '2025-05-07 14:43:01'),
(1763, 1, 'http://localhost/lineup_campus/yas-grubu', 30, '2025-05-07 14:43:33'),
(1764, 1, 'http://localhost/lineup_campus/yas-grubu', 33, '2025-05-07 14:44:08'),
(1765, 1, 'http://localhost/lineup_campus/yas-grubu', 73, '2025-05-07 14:45:22'),
(1766, 1, 'http://localhost/lineup_campus/yas-grubu', 121, '2025-05-07 14:47:26'),
(1767, 1, 'http://localhost/lineup_campus/yas-grubu', 113, '2025-05-07 14:49:21'),
(1768, 1, 'http://localhost/lineup_campus/yas-grubu', 146, '2025-05-07 14:51:49'),
(1769, 1, 'http://localhost/lineup_campus/yas-grubu', 54, '2025-05-07 14:52:45'),
(1770, 1, 'http://localhost/lineup_campus/yas-grubu', 301, '2025-05-07 14:57:49'),
(1771, 1, 'http://localhost/lineup_campus/yas-grubu', 8, '2025-05-07 14:57:59'),
(1772, 1, 'http://localhost/lineup_campus/yas-grubu', 263, '2025-05-07 15:02:23'),
(1773, 1, 'http://localhost/lineup_campus/yas-grubu', 67, '2025-05-07 15:03:32'),
(1774, 1, 'http://localhost/lineup_campus/yas-grubu', 4, '2025-05-07 15:03:38'),
(1775, 1, 'http://localhost/lineup_campus/yas-grubu', 59, '2025-05-07 15:04:39'),
(1776, 1, 'http://localhost/lineup_campus/yas-grubu', 5, '2025-05-07 15:04:46'),
(1777, 1, 'http://localhost/lineup_campus/yas-grubu', 1649, '2025-05-07 15:32:18'),
(1778, 1, 'http://localhost/lineup_campus/yas-grubu', 16, '2025-05-07 15:32:35'),
(1779, 1, 'http://localhost/lineup_campus/yas-grubu', 47, '2025-05-07 15:33:24'),
(1780, 1, 'http://localhost/lineup_campus/yas-grubu', 31, '2025-05-07 15:33:57'),
(1781, 1, 'http://localhost/lineup_campus/yas-grubu', 219, '2025-05-07 15:37:38'),
(1782, 1, 'http://localhost/lineup_campus/yas-grubu', 110, '2025-05-07 15:39:30'),
(1783, 1, 'http://localhost/lineup_campus/yas-grubu', 8, '2025-05-07 15:39:40'),
(1784, 1, 'http://localhost/lineup_campus/yas-grubu', 451, '2025-05-07 15:47:13'),
(1785, 1, 'http://localhost/lineup_campus/yas-grubu', 7, '2025-05-07 15:47:22'),
(1786, 1, 'http://localhost/lineup_campus/yas-grubu', 7, '2025-05-07 15:47:31'),
(1787, 1, 'http://localhost/lineup_campus/yas-grubu', 122, '2025-05-07 15:49:34'),
(1788, 1, 'http://localhost/lineup_campus/yas-grubu', 9, '2025-05-07 15:49:45'),
(1789, 1, 'http://localhost/lineup_campus/yas-grubu', 84, '2025-05-07 15:51:11'),
(1790, 1, 'http://localhost/lineup_campus/yas-grubu', 90, '2025-05-07 15:52:43'),
(1791, 1, 'http://localhost/lineup_campus/yas-grubu', 8, '2025-05-07 15:52:52'),
(1792, 1, 'http://localhost/lineup_campus/yas-grubu', 38, '2025-05-07 15:53:34'),
(1793, 1, 'http://localhost/lineup_campus/yas-grubu', 47, '2025-05-07 15:54:23'),
(1794, 1, 'http://localhost/lineup_campus/yas-grubu', 19, '2025-05-07 15:54:43'),
(1795, 1, 'http://localhost/lineup_campus/yas-grubu', 84, '2025-05-07 15:56:10'),
(1796, 1, 'http://localhost/lineup_campus/yas-grubu', 49, '2025-05-07 15:57:01'),
(1797, 1, 'http://localhost/lineup_campus/yas-grubu', 218, '2025-05-07 16:00:42'),
(1798, 1, 'http://localhost/lineup_campus/yas-grubu', 61, '2025-05-07 16:01:44'),
(1799, 1, 'http://localhost/lineup_campus/yas-grubu', 1, '2025-05-07 16:01:47'),
(1800, 1, 'http://localhost/lineup_campus/yas-grubu', 17, '2025-05-07 16:02:06'),
(1801, 1, 'http://localhost/lineup_campus/yas-grubu', 223, '2025-05-07 16:05:51'),
(1802, 1, 'http://localhost/lineup_campus/yas-grubu', 50, '2025-05-07 16:06:42'),
(1803, 1, 'http://localhost/lineup_campus/yas-grubu', 37, '2025-05-07 16:07:21'),
(1804, 1, 'http://localhost/lineup_campus/yas-grubu', 25, '2025-05-07 16:07:48'),
(1805, 1, 'http://localhost/lineup_campus/yas-grubu', 31, '2025-05-07 16:08:20'),
(1806, 1, 'http://localhost/lineup_campus/yas-grubu', 55, '2025-05-07 16:09:18'),
(1807, 1, 'http://localhost/lineup_campus/yas-grubu', 485, '2025-05-07 16:17:25'),
(1808, 1, 'http://localhost/lineup_campus/yas-grubu', 43, '2025-05-07 16:18:10'),
(1809, 1, 'http://localhost/lineup_campus/yas-grubu', 87, '2025-05-07 16:19:40'),
(1810, 1, 'http://localhost/lineup_campus/yas-grubu', 22, '2025-05-07 16:20:03'),
(1811, 1, 'http://localhost/lineup_campus/yas-grubu', 21, '2025-05-07 16:20:26'),
(1812, 1, 'http://localhost/lineup_campus/yas-grubu', 245, '2025-05-07 16:24:34'),
(1813, 1, 'http://localhost/lineup_campus/yas-grubu', 165, '2025-05-07 16:29:26'),
(1814, 1, 'http://localhost/lineup_campus/yas-grubu', 131, '2025-05-07 16:31:39'),
(1815, 1, 'http://localhost/lineup_campus/yas-grubu', 41, '2025-05-07 16:32:22'),
(1816, 1, 'http://localhost/lineup_campus/yas-grubu', 21, '2025-05-07 16:32:44'),
(1817, 1, 'http://localhost/lineup_campus/yas-grubu', 3, '2025-05-07 16:32:49'),
(1818, 1, 'http://localhost/lineup_campus/yas-grubu', 7, '2025-05-07 16:32:58'),
(1819, 1, 'http://localhost/lineup_campus/yas-grubu', 4, '2025-05-07 16:33:04'),
(1820, 1, 'http://localhost/lineup_campus/yas-grubu', 53, '2025-05-07 16:33:59'),
(1821, 1, 'http://localhost/lineup_campus/yas-grubu', 4, '2025-05-07 16:34:05'),
(1822, 1, 'http://localhost/lineup_campus/yas-grubu', 10, '2025-05-07 16:34:16'),
(1823, 1, 'http://localhost/lineup_campus/yas-grubu', 74, '2025-05-07 16:35:32'),
(1824, 1, 'http://localhost/lineup_campus/yas-grubu', 7, '2025-05-07 16:35:42'),
(1825, 1, 'http://localhost/lineup_campus/yas-grubu', 17, '2025-05-07 16:36:01'),
(1826, 1, 'http://localhost/lineup_campus/yas-grubu', 13, '2025-05-07 16:36:16'),
(1827, 1, 'http://localhost/lineup_campus/yas-grubu', 27, '2025-05-07 16:36:45'),
(1828, 1, 'http://localhost/lineup_campus/yas-grubu', 8, '2025-05-07 16:36:55'),
(1829, 1, 'http://localhost/lineup_campus/yas-grubu', 6, '2025-05-07 16:37:03'),
(1830, 1, 'http://localhost/lineup_campus/yas-grubu', 7, '2025-05-07 16:37:12'),
(1831, 1, 'http://localhost/lineup_campus/yas-grubu', 152, '2025-05-07 16:39:46'),
(1832, 1, 'http://localhost/lineup_campus/yas-grubu', 6, '2025-05-07 16:39:54'),
(1833, 1, 'http://localhost/lineup_campus/yas-grubu', 24, '2025-05-07 16:40:20'),
(1834, 1, 'http://localhost/lineup_campus/yas-grubu', 2, '2025-05-07 16:40:24'),
(1835, 1, 'http://localhost/lineup_campus/yas-grubu', 12, '2025-05-07 16:40:38'),
(1836, 1, 'http://localhost/lineup_campus/yas-grubu', 22, '2025-05-07 16:41:02'),
(1837, 1, 'http://localhost/lineup_campus/yas-grubu', 61, '2025-05-07 16:42:05'),
(1838, 1, 'http://localhost/lineup_campus/yas-grubu', 6, '2025-05-07 16:42:14'),
(1839, 1, 'http://localhost/lineup_campus/yas-grubu', 26, '2025-05-07 16:42:42'),
(1840, 1, 'http://localhost/lineup_campus/yas-grubu', 13, '2025-05-07 16:42:57'),
(1841, 1, 'http://localhost/lineup_campus/okullar', 5, '2025-05-07 16:43:04'),
(1842, 1, 'http://localhost/lineup_campus/yas-grubu', 10319, '2025-05-07 19:35:04'),
(1843, 1, 'http://localhost/lineup_campus/dashboard', 9, '2025-05-07 19:35:15'),
(1844, 1, 'http://localhost/lineup_campus/dashboard', 3, '2025-05-07 19:35:19'),
(1845, 1, 'http://localhost/lineup_campus/yas-grubu', 50193, '2025-05-08 09:31:55'),
(1846, 1, 'http://localhost/lineup_campus/yas-grubu', 2, '2025-05-08 09:31:59'),
(1847, 1, 'http://localhost/lineup_campus/dashboard', 8, '2025-05-08 09:32:11'),
(1848, 1, 'http://localhost/lineup_campus/yas-grubu', 110, '2025-05-08 09:34:03'),
(1849, 1, 'http://localhost/lineup_campus/yas-grubu', 23, '2025-05-08 09:34:29'),
(1850, 1, 'http://localhost/lineup_campus/onemli-haftalar', 580, '2025-05-08 09:44:10'),
(1851, 1, 'http://localhost/lineup_campus/yas-grubu', 4, '2025-05-08 09:47:13'),
(1852, 1, 'http://localhost/lineup_campus/onemli-haftalar', 15, '2025-05-08 09:47:57'),
(1853, 1, 'http://localhost/lineup_campus/onemli-haftalar', 38, '2025-05-08 09:48:37'),
(1854, 1, 'http://localhost/lineup_campus/onemli-haftalar', 54, '2025-05-08 09:52:48'),
(1855, 1, 'http://localhost/lineup_campus/onemli-haftalar', 30, '2025-05-08 10:00:19'),
(1856, 1, 'http://localhost/lineup_campus/onemli-haftalar', 13, '2025-05-08 10:00:33'),
(1857, 1, 'http://localhost/lineup_campus/onemli-haftalar', 70, '2025-05-08 10:01:45'),
(1858, 1, 'http://localhost/lineup_campus/onemli-haftalar', 10, '2025-05-08 10:01:57'),
(1859, 1, 'http://localhost/lineup_campus/onemli-haftalar', 10, '2025-05-08 10:01:58'),
(1860, 1, 'http://localhost/lineup_campus/yas-grubu', 15, '2025-05-08 10:02:15'),
(1861, 1, 'http://localhost/lineup_campus/onemli-haftalar', 102, '2025-05-08 10:03:59'),
(1862, 1, 'http://localhost/lineup_campus/onemli-haftalar', 61, '2025-05-08 10:05:02'),
(1863, 1, 'http://localhost/lineup_campus/onemli-haftalar', 43, '2025-05-08 10:05:47'),
(1864, 1, 'http://localhost/lineup_campus/onemli-haftalar', 97, '2025-05-08 10:07:27'),
(1865, 1, 'http://localhost/lineup_campus/onemli-haftalar', 45, '2025-05-08 10:08:13'),
(1866, 1, 'http://localhost/lineup_campus/onemli-haftalar', 196, '2025-05-08 10:11:31'),
(1867, 1, 'http://localhost/lineup_campus/onemli-haftalar', 22, '2025-05-08 10:15:35'),
(1868, 1, 'http://localhost/lineup_campus/onemli-haftalar', 81, '2025-05-08 10:16:58'),
(1869, 1, 'http://localhost/lineup_campus/onemli-haftalar', 35, '2025-05-08 10:17:35'),
(1870, 1, 'http://localhost/lineup_campus/onemli-haftalar', 249, '2025-05-08 10:21:47'),
(1871, 1, 'http://localhost/lineup_campus/onemli-haftalar', 8, '2025-05-08 10:21:55'),
(1872, 1, 'http://localhost/lineup_campus/yas-grubu', 4, '2025-05-08 10:22:01'),
(1873, 1, 'http://localhost/lineup_campus/yas-grubu', 1, '2025-05-08 10:22:05'),
(1874, 1, 'http://localhost/lineup_campus/onemli-haftalar', 22, '2025-05-08 10:22:29'),
(1875, 1, 'http://localhost/lineup_campus/onemli-haftalar', 114, '2025-05-08 10:24:26'),
(1876, 1, 'http://localhost/lineup_campus/onemli-haftalar', 83, '2025-05-08 10:25:51'),
(1877, 1, 'http://localhost/lineup_campus/onemli-haftalar', 6, '2025-05-08 10:25:59'),
(1878, 1, 'http://localhost/lineup_campus/yas-grubu', 5, '2025-05-08 10:26:07'),
(1879, 1, 'http://localhost/lineup_campus/onemli-haftalar', 58, '2025-05-08 10:27:07'),
(1880, 1, 'http://localhost/lineup_campus/onemli-haftalar', 6, '2025-05-08 10:27:15'),
(1881, 1, 'http://localhost/lineup_campus/onemli-haftalar', 17, '2025-05-08 10:27:34'),
(1882, 1, 'http://localhost/lineup_campus/onemli-haftalar', 1443, '2025-05-08 10:51:42'),
(1883, 1, 'http://localhost/lineup_campus/yas-grubu', 8, '2025-05-08 10:51:52'),
(1884, 1, 'http://localhost/lineup_campus/yas-grubu', 3, '2025-05-08 10:51:58'),
(1885, 1, 'http://localhost/lineup_campus/yas-grubu', 238, '2025-05-08 10:55:58'),
(1886, 1, 'http://localhost/lineup_campus/yas-grubu', 3, '2025-05-08 10:56:03'),
(1887, 1, 'http://localhost/lineup_campus/onemli-haftalar', 14, '2025-05-08 10:56:19'),
(1888, 1, 'http://localhost/lineup_campus/onemli-haftalar', 14, '2025-05-08 10:56:20'),
(1889, 1, 'http://localhost/lineup_campus/dashboard', 108, '2025-05-08 10:58:10'),
(1890, 1, 'http://localhost/lineup_campus/dashboard', 2, '2025-05-08 10:58:15'),
(1891, 1, 'http://localhost/lineup_campus/onemli-haftalar', 4, '2025-05-08 10:58:21'),
(1892, 1, 'http://localhost/lineup_campus/onemli-haftalar', 47, '2025-05-08 10:59:11'),
(1893, 1, 'http://localhost/lineup_campus/onemli-haftalar', 101, '2025-05-08 11:00:54'),
(1894, 1, 'http://localhost/lineup_campus/onemli-haftalar', 6, '2025-05-08 11:01:03'),
(1895, 1, 'http://localhost/lineup_campus/onemli-haftalar', 2, '2025-05-08 11:01:06'),
(1896, 1, 'http://localhost/lineup_campus/onemli-haftalar', 64, '2025-05-08 11:02:12'),
(1897, 1, 'http://localhost/lineup_campus/onemli-haftalar', 166, '2025-05-08 11:05:00'),
(1898, 1, 'http://localhost/lineup_campus/onemli-haftalar', 82, '2025-05-08 11:06:25'),
(1899, 1, 'http://localhost/lineup_campus/onemli-haftalar', 7, '2025-05-08 11:06:33'),
(1900, 1, 'http://localhost/lineup_campus/onemli-haftalar', 69, '2025-05-08 11:07:44'),
(1901, 1, 'http://localhost/lineup_campus/onemli-haftalar', 5, '2025-05-08 11:07:51'),
(1902, 1, 'http://localhost/lineup_campus/onemli-haftalar', 70, '2025-05-08 11:09:03'),
(1903, 1, 'http://localhost/lineup_campus/onemli-haftalar', 2, '2025-05-08 11:09:07'),
(1904, 1, 'http://localhost/lineup_campus/onemli-haftalar', 49, '2025-05-08 11:09:54'),
(1905, 1, 'http://localhost/lineup_campus/onemli-haftalar', 1, '2025-05-08 11:09:56'),
(1906, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-yonetimi', 1351, '2025-05-08 11:32:29'),
(1907, 1, 'http://localhost/lineup_campus/dashboard', 1, '2025-05-08 11:32:32'),
(1908, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-yonetimi', 3085, '2025-05-08 12:24:00'),
(1909, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-yonetimi', 2, '2025-05-08 12:24:04'),
(1910, 1, 'http://localhost/lineup_campus/onemli-haftalar', 1, '2025-05-08 12:24:07'),
(1911, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-yonetimi', 62, '2025-05-08 12:25:11'),
(1912, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-yonetimi', 345, '2025-05-08 12:30:58'),
(1913, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-yonetimi', 9, '2025-05-08 12:31:52'),
(1914, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-yonetimi', 53, '2025-05-08 12:32:47'),
(1915, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-yonetimi', 50, '2025-05-08 12:33:40'),
(1916, 1, 'http://localhost/lineup_campus/alt-konular', 4, '2025-05-08 12:33:46'),
(1917, 1, 'http://localhost/lineup_campus/altkonu-ekle', 75, '2025-05-08 12:35:03'),
(1918, 1, 'http://localhost/lineup_campus/alt-konular', 3, '2025-05-08 12:35:07'),
(1919, 1, 'http://localhost/lineup_campus/altkonu-ekle', 1, '2025-05-08 12:35:10'),
(1920, 1, 'http://localhost/lineup_campus/alt-konular', 0, '2025-05-08 12:35:12'),
(1921, 1, 'http://localhost/lineup_campus/alt-konular', 4, '2025-05-08 12:35:18'),
(1922, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-yonetimi', 16, '2025-05-08 12:35:36'),
(1923, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-yonetimi', 2, '2025-05-08 12:35:40'),
(1924, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-yonetimi', 13, '2025-05-08 12:36:11'),
(1925, 1, 'http://localhost/lineup_campus/alt-konular', 1, '2025-05-08 12:36:15'),
(1926, 1, 'http://localhost/lineup_campus/altkonu-ekle', 14, '2025-05-08 12:36:31'),
(1927, 1, 'http://localhost/lineup_campus/alt-konular', 33, '2025-05-08 12:37:07'),
(1928, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-yonetimi', 1, '2025-05-08 12:37:10'),
(1929, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-ekle', 908, '2025-05-08 12:52:20'),
(1930, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-ekle', 76, '2025-05-08 12:53:38'),
(1931, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-ekle', 63, '2025-05-08 12:54:43'),
(1932, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-ekle', 5, '2025-05-08 12:54:50'),
(1933, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-ekle', 14, '2025-05-08 12:55:06'),
(1934, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-ekle', 26, '2025-05-08 12:55:34'),
(1935, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-ekle', 35, '2025-05-08 12:56:11'),
(1936, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-ekle', 50, '2025-05-08 12:57:03'),
(1937, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-ekle', 9, '2025-05-08 12:57:14'),
(1938, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-ekle', 68, '2025-05-08 12:58:23'),
(1939, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-ekle', 53, '2025-05-08 12:59:19'),
(1940, 1, 'http://localhost/lineup_campus/alt-konular', 2, '2025-05-08 12:59:31'),
(1941, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-ekle', 50, '2025-05-08 13:00:10'),
(1942, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-ekle', 50, '2025-05-08 13:01:02'),
(1943, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-ekle', 116, '2025-05-08 13:02:59'),
(1944, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-ekle', 292, '2025-05-08 13:07:55'),
(1945, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-ekle', 39, '2025-05-08 13:08:34'),
(1946, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-ekle', 32, '2025-05-08 13:09:08'),
(1947, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-ekle', 1, '2025-05-08 13:09:12'),
(1948, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-ekle', 25, '2025-05-08 13:09:38'),
(1949, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-ekle', 40, '2025-05-08 13:10:20'),
(1950, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-ekle', 14, '2025-05-08 13:10:36'),
(1951, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-ekle', 234, '2025-05-08 13:14:34'),
(1952, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-ekle', 26, '2025-05-08 13:15:00'),
(1953, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-ekle', 73, '2025-05-08 13:16:15'),
(1954, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-ekle', 25, '2025-05-08 13:16:42'),
(1955, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-ekle', 12, '2025-05-08 13:16:56'),
(1956, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-ekle', 178, '2025-05-08 13:19:56'),
(1957, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-ekle', 24, '2025-05-08 13:20:22'),
(1958, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-ekle', 26, '2025-05-08 13:20:50'),
(1959, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-ekle', 70, '2025-05-08 13:22:02'),
(1960, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-ekle', 8, '2025-05-08 13:22:12'),
(1961, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-ekle', 33, '2025-05-08 13:22:54'),
(1962, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-ekle', 8, '2025-05-08 13:23:04'),
(1963, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-ekle', 45, '2025-05-08 13:23:51'),
(1964, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-ekle', 42, '2025-05-08 13:24:35'),
(1965, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-ekle', 79, '2025-05-08 13:25:56'),
(1966, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-ekle', 24, '2025-05-08 13:26:22'),
(1967, 1, 'http://localhost/lineup_campus/altkonu-ekle', 1706, '2025-05-08 13:27:59'),
(1968, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-ekle', 99, '2025-05-08 13:28:03'),
(1969, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-ekle', 153, '2025-05-08 13:30:38'),
(1970, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-ekle', 36, '2025-05-08 13:31:16'),
(1971, 1, 'http://localhost/lineup_campus/altkonu-ekle', 244, '2025-05-08 13:32:05'),
(1972, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-ekle', 51, '2025-05-08 13:32:08'),
(1973, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-ekle', 21, '2025-05-08 13:32:31'),
(1974, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-ekle', 11, '2025-05-08 13:32:45'),
(1975, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-ekle', 53, '2025-05-08 13:33:41'),
(1976, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-ekle', 23, '2025-05-08 13:34:05'),
(1977, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-ekle', 726, '2025-05-08 13:46:13'),
(1978, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-ekle', 9, '2025-05-08 13:46:24'),
(1979, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-ekle', 17, '2025-05-08 13:46:45'),
(1980, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-ekle', 74, '2025-05-08 13:48:00'),
(1981, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-ekle', 121, '2025-05-08 13:50:03'),
(1982, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-ekle', 231, '2025-05-08 13:53:57'),
(1983, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-ekle', 188, '2025-05-08 13:57:06'),
(1984, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-ekle', 113, '2025-05-08 13:59:01'),
(1985, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-ekle', 46, '2025-05-08 13:59:49'),
(1986, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-ekle', 42, '2025-05-08 14:00:33'),
(1987, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-ekle', 8, '2025-05-08 14:00:43'),
(1988, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-ekle', 46, '2025-05-08 14:01:32'),
(1989, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-ekle', 32, '2025-05-08 14:02:05'),
(1990, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-ekle', 164, '2025-05-08 14:04:51'),
(1991, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-ekle', 99, '2025-05-08 14:06:32'),
(1992, 1, 'http://localhost/lineup_campus/altkonu-ekle', 2202, '2025-05-08 14:08:50'),
(1993, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-ekle', 140, '2025-05-08 14:08:54'),
(1994, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-ekle', 201, '2025-05-08 14:12:18'),
(1995, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-ekle', 704, '2025-05-08 14:24:04'),
(1996, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-ekle', 932, '2025-05-08 14:39:38'),
(1997, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-ekle', 838, '2025-05-08 14:53:38'),
(1998, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-ekle', 28, '2025-05-08 14:54:08'),
(1999, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-ekle', 131, '2025-05-08 14:56:21'),
(2000, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-ekle', 226, '2025-05-08 15:00:09'),
(2001, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-ekle', 395, '2025-05-08 15:06:46'),
(2002, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-yonetimi', 38, '2025-05-08 15:07:26'),
(2003, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-yonetimi', 113, '2025-05-08 15:09:22'),
(2004, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-yonetimi', 21, '2025-05-08 15:09:43'),
(2005, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-yonetimi', 5, '2025-05-08 15:09:50'),
(2006, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-yonetimi', 3, '2025-05-08 15:09:55'),
(2007, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-yonetimi', 60, '2025-05-08 15:10:57'),
(2008, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-yonetimi', 4, '2025-05-08 15:11:02'),
(2009, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-yonetimi', 25, '2025-05-08 15:11:29'),
(2010, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-yonetimi', 5, '2025-05-08 15:11:36'),
(2011, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-yonetimi', 278, '2025-05-08 15:16:15'),
(2012, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-yonetimi', 3, '2025-05-08 15:16:21'),
(2013, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-yonetimi', 17, '2025-05-08 15:16:42'),
(2014, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-yonetimi', 1, '2025-05-08 15:16:44'),
(2015, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=7', 142, '2025-05-08 15:19:28'),
(2016, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=7', 22, '2025-05-08 15:20:37'),
(2017, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=7', 327, '2025-05-08 15:26:05'),
(2018, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=7', 36, '2025-05-08 15:26:43'),
(2019, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=7', 63, '2025-05-08 15:27:48'),
(2020, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=7', 5, '2025-05-08 15:27:54'),
(2021, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=7', 182, '2025-05-08 15:30:58'),
(2022, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=7', 4, '2025-05-08 15:31:04'),
(2023, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=7', 98, '2025-05-08 15:32:44'),
(2024, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=7', 109, '2025-05-08 15:34:36'),
(2025, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=7', 17, '2025-05-08 15:34:54'),
(2026, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=7', 27, '2025-05-08 15:35:23'),
(2027, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=7', 147, '2025-05-08 15:37:52'),
(2028, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=7', 74, '2025-05-08 15:39:09'),
(2029, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=7', 28, '2025-05-08 15:39:39'),
(2030, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=7', 21, '2025-05-08 15:40:01'),
(2031, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=7', 11, '2025-05-08 15:40:14'),
(2032, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=7', 110, '2025-05-08 15:42:06'),
(2033, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=7', 61, '2025-05-08 15:43:10'),
(2034, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=7', 104, '2025-05-08 15:44:55'),
(2035, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=7', 92, '2025-05-08 15:46:29'),
(2036, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=7', 51, '2025-05-08 15:47:21'),
(2037, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=7', 41, '2025-05-08 15:48:04'),
(2038, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=7', 82, '2025-05-08 15:49:28'),
(2039, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=7', 51, '2025-05-08 15:50:21'),
(2040, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=7', 50, '2025-05-08 15:51:13'),
(2041, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=7', 29, '2025-05-08 15:51:44'),
(2042, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=7', 4, '2025-05-08 15:51:50'),
(2043, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=7', 392, '2025-05-08 15:58:25'),
(2044, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=7', 25, '2025-05-08 15:58:51'),
(2045, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=7', 265, '2025-05-08 16:03:19'),
(2046, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=7', 155, '2025-05-08 16:05:56'),
(2047, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=7', 179, '2025-05-08 16:08:58'),
(2048, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=7', 16, '2025-05-08 16:09:16'),
(2049, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=7', 7, '2025-05-08 16:09:25'),
(2050, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=7', 23, '2025-05-08 16:09:50'),
(2051, 1, 'http://localhost/lineup_campus/yas-grubu', 1, '2025-05-08 16:09:53'),
(2052, 1, 'http://localhost/lineup_campus/onemli-haftalar', 1, '2025-05-08 16:09:56'),
(2053, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-yonetimi', 5, '2025-05-08 16:10:04'),
(2054, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-yonetimi', 2, '2025-05-08 16:10:08'),
(2055, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-yonetimi', 2, '2025-05-08 16:10:12'),
(2056, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-yonetimi', 4, '2025-05-08 16:10:18'),
(2057, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=1', 13, '2025-05-08 16:10:33'),
(2058, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-yonetimi', 533, '2025-05-08 16:19:27'),
(2059, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-yonetimi', 9, '2025-05-08 16:19:38'),
(2060, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=2', 16, '2025-05-08 16:19:56'),
(2061, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-yonetimi', 21, '2025-05-08 16:20:19'),
(2062, 1, 'http://localhost/lineup_campus/dashboard', 13, '2025-05-08 16:57:28'),
(2063, 1, 'http://localhost/lineup_campus/dashboard', 38, '2025-05-08 16:58:09'),
(2064, 1, 'http://localhost/lineup_campus/dashboard', 3, '2025-05-08 16:58:16'),
(2065, 1, 'http://localhost/lineup_campus/onemli-haftalar', 18284, '2025-05-08 22:03:02'),
(2066, 1, 'http://localhost/lineup_campus/onemli-haftalar', 1, '2025-05-08 22:03:05'),
(2067, 1, 'http://localhost/lineup_campus/kategori-basliklari', 414, '2025-05-08 22:10:33'),
(2068, 1, 'http://localhost/lineup_campus/kategori-basliklari', 139, '2025-05-08 22:12:54'),
(2069, 1, 'http://localhost/lineup_campus/kategori-basliklari', 82, '2025-05-08 22:14:18'),
(2070, 1, 'http://localhost/lineup_campus/kategori-basliklari', 49, '2025-05-08 22:15:08'),
(2071, 1, 'http://localhost/lineup_campus/kategori-basliklari', 216, '2025-05-08 22:18:48'),
(2072, 1, 'http://localhost/lineup_campus/kategori-basliklari', 90, '2025-05-08 22:20:19'),
(2073, 1, 'http://localhost/lineup_campus/kategori-basliklari', 10, '2025-05-08 22:20:31'),
(2074, 1, 'http://localhost/lineup_campus/kategori-basliklari', 48, '2025-05-08 22:21:22'),
(2075, 1, 'http://localhost/lineup_campus/kategori-basliklari', 30, '2025-05-08 22:21:54'),
(2076, 1, 'http://localhost/lineup_campus/kategori-basliklari', 59, '2025-05-08 22:22:55'),
(2077, 1, 'http://localhost/lineup_campus/kategori-basliklari', 51, '2025-05-08 22:23:48'),
(2078, 1, 'http://localhost/lineup_campus/kategori-basliklari', 188, '2025-05-08 22:26:59'),
(2079, 1, 'http://localhost/lineup_campus/kategori-basliklari', 171, '2025-05-08 22:29:52'),
(2080, 1, 'http://localhost/lineup_campus/kategori-basliklari', 258, '2025-05-08 22:34:12'),
(2081, 1, 'http://localhost/lineup_campus/kategori-basliklari', 110, '2025-05-08 22:36:04'),
(2082, 1, 'http://localhost/lineup_campus/kategori-basliklari', 50, '2025-05-08 22:36:56'),
(2083, 1, 'http://localhost/lineup_campus/kategori-basliklari', 24, '2025-05-08 22:37:23'),
(2084, 1, 'http://localhost/lineup_campus/kategori-basliklari', 42, '2025-05-08 22:38:07'),
(2085, 1, 'http://localhost/lineup_campus/kategori-basliklari', 33, '2025-05-08 22:38:43'),
(2086, 1, 'http://localhost/lineup_campus/kategori-basliklari', 19, '2025-05-08 22:39:04'),
(2087, 1, 'http://localhost/lineup_campus/kategori-basliklari', 96, '2025-05-08 22:40:42'),
(2088, 1, 'http://localhost/lineup_campus/kategori-basliklari', 35, '2025-05-08 22:41:20'),
(2089, 1, 'http://localhost/lineup_campus/kategori-basliklari', 90, '2025-05-08 22:42:53'),
(2090, 1, 'http://localhost/lineup_campus/kategori-basliklari', 118, '2025-05-08 22:44:53'),
(2091, 1, 'http://localhost/lineup_campus/kategori-basliklari', 191, '2025-05-08 22:48:06'),
(2092, 1, 'http://localhost/lineup_campus/kategori-basliklari', 53, '2025-05-08 22:49:01'),
(2093, 1, 'http://localhost/lineup_campus/kategori-basliklari', 260, '2025-05-08 22:53:22'),
(2094, 1, 'http://localhost/lineup_campus/kategori-basliklari', 141, '2025-05-08 22:55:46'),
(2095, 1, 'http://localhost/lineup_campus/kategori-basliklari', 10, '2025-05-08 22:55:58'),
(2096, 1, 'http://localhost/lineup_campus/kategori-basliklari', 11, '2025-05-08 22:56:11'),
(2097, 1, 'http://localhost/lineup_campus/kategori-basliklari', 14, '2025-05-08 22:56:27'),
(2098, 1, 'http://localhost/lineup_campus/kategori-basliklari', 40, '2025-05-08 22:57:09'),
(2099, 1, 'http://localhost/lineup_campus/kategori-basliklari', 6, '2025-05-08 22:57:16'),
(2100, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-yonetimi', 4, '2025-05-08 22:57:22'),
(2101, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-ekle', 71, '2025-05-08 22:58:35'),
(2102, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-ekle', 400, '2025-05-08 23:05:17'),
(2103, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-ekle', 48, '2025-05-08 23:06:07'),
(2104, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-ekle', 27, '2025-05-08 23:06:36'),
(2105, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-ekle', 131, '2025-05-08 23:08:49'),
(2106, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-ekle', 39, '2025-05-08 23:09:30'),
(2107, 1, 'http://localhost/lineup_campus/yas-grubu', 0, '2025-05-08 23:09:32'),
(2108, 1, 'http://localhost/lineup_campus/kategori-basliklari', 8, '2025-05-08 23:09:42'),
(2109, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-yonetimi', 5, '2025-05-08 23:09:49'),
(2110, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=17', 123, '2025-05-08 23:11:54'),
(2111, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=17', 10, '2025-05-08 23:12:05'),
(2112, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=17', 69, '2025-05-08 23:13:16'),
(2113, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=17', 142, '2025-05-08 23:15:40'),
(2114, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-yonetimi', 1, '2025-05-08 23:15:43'),
(2115, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-ekle', 62, '2025-05-08 23:16:47'),
(2116, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-yonetimi', 1, '2025-05-08 23:16:50'),
(2117, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-ekle', 30, '2025-05-08 23:17:22'),
(2118, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-yonetimi', 3, '2025-05-08 23:17:27'),
(2119, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=18', 99, '2025-05-08 23:19:08'),
(2120, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=18', 103, '2025-05-08 23:20:52'),
(2121, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=18', 94, '2025-05-08 23:22:30'),
(2122, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=18', 80, '2025-05-08 23:23:51'),
(2123, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=18', 15, '2025-05-08 23:24:07'),
(2124, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-yonetimi', 30, '2025-05-08 23:24:40'),
(2125, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-yonetimi', 2, '2025-05-08 23:24:43'),
(2126, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=18', 13, '2025-05-08 23:24:58'),
(2127, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-yonetimi', 2, '2025-05-08 23:25:02'),
(2128, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-ekle', 24, '2025-05-08 23:25:28'),
(2129, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-yonetimi', 6, '2025-05-08 23:25:37'),
(2130, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=20', 11, '2025-05-08 23:25:49'),
(2131, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=20', 13, '2025-05-08 23:26:04'),
(2132, 1, 'http://localhost/lineup_campus/yas-grubu', 1, '2025-05-08 23:26:07'),
(2133, 1, 'http://localhost/lineup_campus/onemli-haftalar', 1, '2025-05-08 23:26:10'),
(2134, 1, 'http://localhost/lineup_campus/kategori-basliklari', 3, '2025-05-08 23:26:15'),
(2135, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-yonetimi', 24, '2025-05-08 23:26:41'),
(2136, 1, 'http://localhost/lineup_campus/kategori-basliklari', 39612, '2025-05-09 10:26:54'),
(2137, 1, 'http://localhost/lineup_campus/kategori-basliklari', 4, '2025-05-09 10:27:00'),
(2138, 1, 'http://localhost/lineup_campus/kategori-basliklari', 13, '2025-05-09 10:27:14'),
(2139, 1, 'http://localhost/lineup_campus/kategori-basliklari', 27, '2025-05-09 10:27:43'),
(2140, 1, 'http://localhost/lineup_campus/kategori-basliklari', 11, '2025-05-09 10:27:56'),
(2141, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-yonetimi', 0, '2025-05-09 10:27:58'),
(2142, 1, 'http://localhost/lineup_campus/kategori-basliklari', 1, '2025-05-09 10:28:01'),
(2143, 1, 'http://localhost/lineup_campus/yas-grubu', 4, '2025-05-09 10:28:07'),
(2144, 1, 'http://localhost/lineup_campus/onemli-haftalar', 4, '2025-05-09 10:28:13'),
(2145, 1, 'http://localhost/lineup_campus/kategori-basliklari', 2, '2025-05-09 10:28:17'),
(2146, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-yonetimi', 1, '2025-05-09 10:28:20'),
(2147, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-ekle', 216, '2025-05-09 10:31:58'),
(2148, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-yonetimi', 1, '2025-05-09 10:32:01'),
(2149, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=18', 44, '2025-05-09 10:32:47'),
(2150, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=18', 26, '2025-05-09 10:33:16'),
(2151, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=18', 35, '2025-05-09 10:33:53'),
(2152, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=18', 30, '2025-05-09 10:34:25'),
(2153, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=18', 87, '2025-05-09 10:35:54'),
(2154, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=18', 4, '2025-05-09 10:36:01'),
(2155, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-yonetimi', 2, '2025-05-09 10:36:05'),
(2156, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-ekle', 203, '2025-05-09 10:39:30'),
(2157, 1, 'http://localhost/lineup_campus/yas-grubu', 0, '2025-05-09 10:39:32'),
(2158, 1, 'http://localhost/lineup_campus/kategori-basliklari', 0, '2025-05-09 10:39:34'),
(2159, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-yonetimi', 2, '2025-05-09 10:39:38'),
(2160, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=20', 24, '2025-05-09 10:40:03'),
(2161, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=20', 62, '2025-05-09 10:41:08'),
(2162, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=20', 20, '2025-05-09 10:41:30'),
(2163, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=20', 66, '2025-05-09 10:42:39'),
(2164, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=20', 64, '2025-05-09 10:43:47'),
(2165, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=20', 18, '2025-05-09 10:44:07'),
(2166, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=20', 14, '2025-05-09 10:44:24'),
(2167, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=20', 23, '2025-05-09 10:44:49'),
(2168, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=20', 7, '2025-05-09 10:44:58'),
(2169, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=20', 8, '2025-05-09 10:45:08'),
(2170, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-yonetimi', 1, '2025-05-09 10:45:10'),
(2171, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-ekle', 29, '2025-05-09 10:45:41'),
(2172, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-yonetimi', 3, '2025-05-09 10:45:47'),
(2173, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-yonetimi', 2, '2025-05-09 10:45:52'),
(2174, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=21', 10, '2025-05-09 10:46:04'),
(2175, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-yonetimi', 1, '2025-05-09 10:46:06'),
(2176, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-ekle', 29, '2025-05-09 10:46:37'),
(2177, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-yonetimi', 2, '2025-05-09 10:46:41'),
(2178, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-yonetimi', 3, '2025-05-09 10:46:47'),
(2179, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=22', 45, '2025-05-09 10:47:34'),
(2180, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-yonetimi', 1, '2025-05-09 10:47:37'),
(2181, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-ekle', 36, '2025-05-09 10:48:15'),
(2182, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-yonetimi', 5, '2025-05-09 10:48:22'),
(2183, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=23', 1, '2025-05-09 10:48:25'),
(2184, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-yonetimi', 3, '2025-05-09 10:48:30'),
(2185, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=23', 2, '2025-05-09 10:48:33'),
(2186, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-yonetimi', 1661, '2025-05-09 11:16:16'),
(2187, 1, 'http://localhost/lineup_campus/aktif-destek-talepleri', 4, '2025-05-09 11:16:22'),
(2188, 1, 'http://localhost/lineup_campus/cozulmus-destek-talepleri', 7, '2025-05-09 11:16:31'),
(2189, 1, 'http://localhost/lineup_campus/destek-talebi?id=dene-oneri', 11, '2025-05-09 11:16:44'),
(2190, 1, 'http://localhost/lineup_campus/test-ekle', 12, '2025-05-09 11:16:58'),
(2191, 1, 'http://localhost/lineup_campus/kullanici-yetkilendirme', 24, '2025-05-09 11:17:24'),
(2192, 1, 'http://localhost/lineup_campus/haftalik-gorev', 1211, '2025-05-09 11:37:37'),
(2193, 1, 'http://localhost/lineup_campus/kullanici-yetkilendirme', 14, '2025-05-09 11:37:54'),
(2194, 1, 'http://localhost/lineup_campus/yas-grubu', 8, '2025-05-09 11:38:04'),
(2195, 1, 'http://localhost/lineup_campus/onemli-haftalar', 661, '2025-05-09 11:49:07'),
(2196, 1, 'http://localhost/lineup_campus/dashboard', 10, '2025-05-09 12:43:37'),
(2197, 1, 'http://localhost/lineup_campus/yas-grubu', 10, '2025-05-09 12:43:54'),
(2198, 1, 'http://localhost/lineup_campus/onemli-haftalar', 8, '2025-05-09 12:44:04'),
(2199, 1, 'http://localhost/lineup_campus/kategori-basliklari', 26, '2025-05-09 12:44:32'),
(2200, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-yonetimi', 3, '2025-05-09 12:44:37'),
(2201, 1, 'http://localhost/lineup_campus/dashboard', 308, '2025-05-09 12:48:47'),
(2202, 32, 'http://localhost/lineup_campus/dashboard', 13, '2025-05-09 12:49:15'),
(2203, 32, 'http://localhost/lineup_campus/testler-ogrenci', 3, '2025-05-09 12:49:19'),
(2204, 32, 'http://localhost/lineup_campus/duyurular', 1, '2025-05-09 12:49:21'),
(2205, 32, 'http://localhost/lineup_campus/ana-okulu-icerik-ekle', 1348, '2025-05-09 13:07:07'),
(2206, 32, 'http://localhost/lineup_campus/dashboard', 20908, '2025-05-09 18:37:51'),
(2207, 32, 'http://localhost/lineup_campus/testler-ogrenci', 2, '2025-05-09 18:37:54'),
(2208, 1, 'http://localhost/lineup_campus/dashboard', 306, '2025-05-12 08:35:54'),
(2209, 1, 'http://localhost/lineup_campus/yas-grubu', 18, '2025-05-12 08:36:14'),
(2210, 1, 'http://localhost/lineup_campus/yas-grubu', 7, '2025-05-12 08:36:25'),
(2211, 1, 'http://localhost/lineup_campus/onemli-haftalar', 66, '2025-05-12 08:37:33'),
(2212, 1, 'http://localhost/lineup_campus/kategori-basliklari', 17, '2025-05-12 08:37:52'),
(2213, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-yonetimi', 1, '2025-05-12 08:37:55'),
(2214, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-ekle', 94, '2025-05-12 08:39:31'),
(2215, 1, 'http://localhost/lineup_campus/veliler', 52, '2025-05-12 08:40:26'),
(2216, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-yonetimi', 0, '2025-05-12 08:40:28'),
(2217, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-ekle', 87, '2025-05-12 08:41:57'),
(2218, 1, 'http://localhost/lineup_campus/dashboard', 8, '2025-05-12 08:42:18'),
(2219, 68, 'http://localhost/lineup_campus/index', 8, '2025-05-12 08:46:26'),
(2220, 68, 'http://localhost/lineup_campus/index', 2, '2025-05-12 08:46:28'),
(2221, 32, 'http://localhost/lineup_campus/dashboard', 98, '2025-05-12 08:48:07'),
(2222, 32, 'http://localhost/lineup_campus/dashboard', 3, '2025-05-12 08:48:55'),
(2223, 68, 'http://localhost/lineup_campus/index', 58, '2025-05-12 08:49:58'),
(2224, 68, 'http://localhost/lineup_campus/dashboard', 283, '2025-05-12 08:54:50'),
(2225, 68, 'http://localhost/lineup_campus/dashboard', 57, '2025-05-12 08:57:50'),
(2226, 68, 'http://localhost/lineup_campus/dashboard', 183, '2025-05-12 09:00:54'),
(2227, 68, 'http://localhost/lineup_campus/dashboard', 94, '2025-05-12 09:07:04'),
(2228, 68, 'http://localhost/lineup_campus/dashboard', 28, '2025-05-12 09:07:33'),
(2229, 68, 'http://localhost/lineup_campus/dashboard', 28, '2025-05-12 09:14:43'),
(2230, 68, 'http://localhost/lineup_campus/index', 2, '2025-05-12 09:45:09'),
(2231, 32, 'http://localhost/lineup_campus/dashboard', 6, '2025-05-12 09:45:15'),
(2232, 1, 'http://localhost/lineup_campus/dashboard', 14, '2025-05-12 09:58:52'),
(2233, 1, 'http://localhost/lineup_campus/dashboard', 0, '2025-05-12 09:58:53'),
(2234, 1, 'http://localhost/lineup_campus/dashboard', 0, '2025-05-12 09:58:54'),
(2235, 1, 'http://localhost/lineup_campus/dashboard', 15, '2025-05-12 09:59:09'),
(2236, 1, 'http://localhost/lineup_campus/dashboard', 0, '2025-05-12 09:59:10'),
(2237, 1, 'http://localhost/lineup_campus/dashboard', 0, '2025-05-12 09:59:10'),
(2238, 1, 'http://localhost/lineup_campus/dashboard', 0, '2025-05-12 09:59:10'),
(2239, 1, 'http://localhost/lineup_campus/dashboard', 473, '2025-05-12 11:45:34'),
(2240, 1, 'http://localhost/lineup_campus/dashboard', 2941, '2025-05-12 12:34:37'),
(2241, 1, 'http://localhost/lineup_campus/kategori-basliklari', 689, '2025-05-12 12:46:08'),
(2242, 1, 'http://localhost/lineup_campus/index', 11149, '2025-05-12 12:46:08'),
(2243, 1, 'http://localhost/lineup_campus/dashboard', 6, '2025-05-12 12:48:25'),
(2244, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-yonetimi', 2, '2025-05-12 12:48:29'),
(2245, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-ekle', 33, '2025-05-12 12:49:05'),
(2246, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-ekle', 30, '2025-05-12 12:49:38'),
(2247, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-ekle', 133, '2025-05-12 12:51:53'),
(2248, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-ekle', 47, '2025-05-12 12:52:41'),
(2249, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-ekle', 887, '2025-05-12 13:07:31'),
(2250, 1, 'http://localhost/lineup_campus/onemli-haftalar', 2834, '2025-05-12 13:54:47'),
(2251, 1, 'http://localhost/lineup_campus/onemli-haftalar', 40, '2025-05-12 13:55:28'),
(2252, 1, 'http://localhost/lineup_campus/siniflar', 2, '2025-05-12 13:55:32'),
(2253, 1, 'http://localhost/lineup_campus/okul-detay/okul-oncesi', 7, '2025-05-12 13:55:41'),
(2254, 1, 'http://localhost/lineup_campus/konular', 7, '2025-05-12 13:55:50'),
(2255, 1, 'http://localhost/lineup_campus/okullar', 6, '2025-05-12 13:55:58'),
(2256, 1, 'http://localhost/lineup_campus/ogretmenler', 7, '2025-05-12 13:56:07'),
(2257, 1, 'http://localhost/lineup_campus/alt-konular', 1, '2025-05-12 13:56:10'),
(2258, 1, 'http://localhost/lineup_campus/altkonu-ekle', 5, '2025-05-12 13:56:17'),
(2259, 1, 'http://localhost/lineup_campus/test-ekle', 11, '2025-05-12 13:56:30'),
(2260, 1, 'http://localhost/lineup_campus/duyurular', 5, '2025-05-12 13:56:37'),
(2261, 1, 'http://localhost/lineup_campus/haftalik-gorev', 104, '2025-05-12 13:58:23'),
(2262, 1, 'http://localhost/lineup_campus/haftalik-gorev', 7, '2025-05-12 13:58:31'),
(2263, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-yonetimi', 1, '2025-05-12 13:58:34'),
(2264, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-ekle', 36, '2025-05-12 13:59:12'),
(2265, 1, 'http://localhost/lineup_campus/onemli-haftalar', 12, '2025-05-12 13:59:26'),
(2266, 1, 'http://localhost/lineup_campus/onemli-haftalar', 120, '2025-05-12 14:01:29'),
(2267, 1, 'http://localhost/lineup_campus/dersler', 2, '2025-05-12 14:01:33'),
(2268, 1, 'http://localhost/lineup_campus/haftalik-gorev', 8, '2025-05-12 14:01:43'),
(2269, 1, 'http://localhost/lineup_campus/dersler', 2, '2025-05-12 14:01:47'),
(2270, 1, 'http://localhost/lineup_campus/haftalik-gorev', 50, '2025-05-12 14:02:38'),
(2271, 1, 'http://localhost/lineup_campus/yas-grubu', 1, '2025-05-12 14:02:51'),
(2272, 1, 'http://localhost/lineup_campus/onemli-haftalar', 545, '2025-05-12 14:11:56'),
(2273, 1, 'http://localhost/lineup_campus/onemli-haftalar', 57, '2025-05-12 14:12:55'),
(2274, 1, 'http://localhost/lineup_campus/onemli-haftalar', 103, '2025-05-12 14:14:40'),
(2275, 1, 'http://localhost/lineup_campus/onemli-haftalar', 275, '2025-05-12 14:19:17'),
(2276, 1, 'http://localhost/lineup_campus/onemli-haftalar', 974, '2025-05-12 14:35:35'),
(2277, 1, 'http://localhost/lineup_campus/onemli-haftalar', 756, '2025-05-12 14:48:13'),
(2278, 1, 'http://localhost/lineup_campus/onemli-haftalar', 34, '2025-05-12 14:48:49'),
(2279, 1, 'http://localhost/lineup_campus/onemli-haftalar', 225, '2025-05-12 14:52:36'),
(2280, 1, 'http://localhost/lineup_campus/onemli-haftalar', 80, '2025-05-12 14:53:58');
INSERT INTO `timespend_lnp` (`id`, `user_id`, `sayfa_url`, `timeSpent`, `exitTime`) VALUES
(2281, 1, 'http://localhost/lineup_campus/onemli-haftalar', 36, '2025-05-12 14:54:36'),
(2282, 1, 'http://localhost/lineup_campus/onemli-haftalar', 61, '2025-05-12 14:55:39'),
(2283, 1, 'http://localhost/lineup_campus/onemli-haftalar', 6, '2025-05-12 14:55:46'),
(2284, 1, 'http://localhost/lineup_campus/onemli-haftalar', 192, '2025-05-12 14:59:00'),
(2285, 1, 'http://localhost/lineup_campus/onemli-haftalar', 9, '2025-05-12 14:59:11'),
(2286, 1, 'http://localhost/lineup_campus/onemli-haftalar', 48, '2025-05-12 15:00:02'),
(2287, 1, 'http://localhost/lineup_campus/onemli-haftalar', 6, '2025-05-12 15:00:10'),
(2288, 1, 'http://localhost/lineup_campus/onemli-haftalar', 21, '2025-05-12 15:00:33'),
(2289, 1, 'http://localhost/lineup_campus/onemli-haftalar', 107, '2025-05-12 15:02:22'),
(2290, 1, 'http://localhost/lineup_campus/onemli-haftalar', 554, '2025-05-12 15:11:38'),
(2291, 1, 'http://localhost/lineup_campus/onemli-haftalar', 67, '2025-05-12 15:12:47'),
(2292, 1, 'http://localhost/lineup_campus/onemli-haftalar', 275, '2025-05-12 15:17:48'),
(2293, 1, 'http://localhost/lineup_campus/onemli-haftalar', 17, '2025-05-12 15:18:07'),
(2294, 1, 'http://localhost/lineup_campus/onemli-haftalar', 112, '2025-05-12 15:20:00'),
(2295, 1, 'http://localhost/lineup_campus/onemli-haftalar', 13, '2025-05-12 15:20:15'),
(2296, 1, 'http://localhost/lineup_campus/onemli-haftalar', 1, '2025-05-12 15:20:19'),
(2297, 1, 'http://localhost/lineup_campus/onemli-haftalar', 3, '2025-05-12 15:20:23'),
(2298, 1, 'http://localhost/lineup_campus/onemli-haftalar', 11, '2025-05-12 15:20:37'),
(2299, 1, 'http://localhost/lineup_campus/onemli-haftalar', 142, '2025-05-12 15:23:01'),
(2300, 1, 'http://localhost/lineup_campus/onemli-haftalar', 219, '2025-05-12 15:26:42'),
(2301, 1, 'http://localhost/lineup_campus/onemli-haftalar', 49, '2025-05-12 15:27:33'),
(2302, 1, 'http://localhost/lineup_campus/onemli-haftalar', 158, '2025-05-12 15:30:13'),
(2303, 1, 'http://localhost/lineup_campus/onemli-haftalar', 51, '2025-05-12 15:31:07'),
(2304, 1, 'http://localhost/lineup_campus/onemli-haftalar', 18, '2025-05-12 15:31:27'),
(2305, 1, 'http://localhost/lineup_campus/onemli-haftalar', 59, '2025-05-12 15:32:28'),
(2306, 1, 'http://localhost/lineup_campus/onemli-haftalar', 24, '2025-05-12 15:32:54'),
(2307, 1, 'http://localhost/lineup_campus/onemli-haftalar', 43, '2025-05-12 15:33:39'),
(2308, 1, 'http://localhost/lineup_campus/onemli-haftalar', 61, '2025-05-12 15:34:43'),
(2309, 1, 'http://localhost/lineup_campus/onemli-haftalar', 34, '2025-05-12 15:35:18'),
(2310, 1, 'http://localhost/lineup_campus/onemli-haftalar', 119, '2025-05-12 15:37:19'),
(2311, 1, 'http://localhost/lineup_campus/onemli-haftalar', 10, '2025-05-12 15:37:31'),
(2312, 1, 'http://localhost/lineup_campus/onemli-haftalar', 20, '2025-05-12 15:37:53'),
(2313, 1, 'http://localhost/lineup_campus/onemli-haftalar', 62, '2025-05-12 15:38:57'),
(2314, 1, 'http://localhost/lineup_campus/onemli-haftalar', 104, '2025-05-12 15:40:43'),
(2315, 1, 'http://localhost/lineup_campus/onemli-haftalar', 6, '2025-05-12 15:40:51'),
(2316, 1, 'http://localhost/lineup_campus/onemli-haftalar', 35, '2025-05-12 15:41:28'),
(2317, 1, 'http://localhost/lineup_campus/onemli-haftalar', 26, '2025-05-12 15:41:56'),
(2318, 1, 'http://localhost/lineup_campus/onemli-haftalar', 118, '2025-05-12 15:44:09'),
(2319, 1, 'http://localhost/lineup_campus/onemli-haftalar', 305, '2025-05-12 15:49:16'),
(2320, 1, 'http://localhost/lineup_campus/onemli-haftalar', 38, '2025-05-12 15:49:56'),
(2321, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-yonetimi', 2, '2025-05-12 15:50:00'),
(2322, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-ekle', 95, '2025-05-12 15:51:37'),
(2323, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-ekle', 38, '2025-05-12 15:52:17'),
(2324, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-ekle', 200, '2025-05-12 15:55:39'),
(2325, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-ekle', 57, '2025-05-12 15:56:38'),
(2326, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-ekle', 227, '2025-05-12 16:00:27'),
(2327, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-ekle', 1328, '2025-05-12 16:22:37'),
(2328, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-ekle', 3165, '2025-05-12 17:15:24'),
(2329, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-yonetimi', 4, '2025-05-12 17:15:30'),
(2330, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=28', 51, '2025-05-12 17:16:23'),
(2331, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-yonetimi', 1, '2025-05-12 17:16:26'),
(2332, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-ekle', 131, '2025-05-12 17:18:39'),
(2333, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-yonetimi', 4, '2025-05-12 17:18:45'),
(2334, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=29', 147, '2025-05-12 17:21:14'),
(2335, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=29', 33, '2025-05-12 17:22:08'),
(2336, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=29', 77, '2025-05-12 17:23:28'),
(2337, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=29', 183, '2025-05-12 17:26:33'),
(2338, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=29', 22, '2025-05-12 17:26:57'),
(2339, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=29', 9, '2025-05-12 17:27:08'),
(2340, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=29', 121, '2025-05-12 17:29:11'),
(2341, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=29', 85, '2025-05-12 17:30:38'),
(2342, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=29', 39, '2025-05-12 17:31:20'),
(2343, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=29', 141, '2025-05-12 17:33:43'),
(2344, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=29', 12, '2025-05-12 17:33:58'),
(2345, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=29', 17, '2025-05-12 17:34:17'),
(2346, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=29', 5, '2025-05-12 17:34:25'),
(2347, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=29', 41, '2025-05-12 17:35:09'),
(2348, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=29', 151, '2025-05-12 17:37:42'),
(2349, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=29', 422, '2025-05-12 17:44:46'),
(2350, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=29', 54, '2025-05-12 17:45:43'),
(2351, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=29', 33, '2025-05-12 17:46:18'),
(2352, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=29', 10, '2025-05-12 17:46:30'),
(2353, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=29', 22, '2025-05-12 17:46:55'),
(2354, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=29', 45, '2025-05-12 17:47:42'),
(2355, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=29', 37, '2025-05-12 17:48:21'),
(2356, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=29', 47, '2025-05-12 17:49:10'),
(2357, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=29', 6, '2025-05-12 17:49:18'),
(2358, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=29', 32, '2025-05-12 17:49:52'),
(2359, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=29', 62, '2025-05-12 17:50:56'),
(2360, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=29', 911, '2025-05-12 18:06:11'),
(2361, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=29', 141, '2025-05-12 18:08:33'),
(2362, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=29', 47, '2025-05-12 18:09:23'),
(2363, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=29', 52, '2025-05-12 18:10:16'),
(2364, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=29', 45, '2025-05-12 18:11:03'),
(2365, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=29', 11, '2025-05-12 18:11:16'),
(2366, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=29', 14, '2025-05-12 18:11:32'),
(2367, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=29', 18, '2025-05-12 18:11:53'),
(2368, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=29', 20, '2025-05-12 18:12:15'),
(2369, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=29', 11, '2025-05-12 18:12:28'),
(2370, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=29', 88, '2025-05-12 18:13:58'),
(2371, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=29', 72, '2025-05-12 18:15:13'),
(2372, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=29', 13, '2025-05-12 18:15:28'),
(2373, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=29', 170, '2025-05-12 18:18:20'),
(2374, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=29', 106, '2025-05-12 18:20:08'),
(2375, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=29', 29, '2025-05-12 18:20:39'),
(2376, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=29', 89, '2025-05-12 18:22:10'),
(2377, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=29', 216, '2025-05-12 18:25:48'),
(2378, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=29', 76, '2025-05-12 18:27:06'),
(2379, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=29', 18, '2025-05-12 18:27:26'),
(2380, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=29', 8, '2025-05-12 18:27:36'),
(2381, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=29', 232, '2025-05-12 18:31:30'),
(2382, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=29', 125, '2025-05-12 18:33:38'),
(2383, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=29', 5, '2025-05-12 18:33:45'),
(2384, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=29', 1, '2025-05-12 18:33:49'),
(2385, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=29', 40, '2025-05-12 18:34:31'),
(2386, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=29', 23, '2025-05-12 18:34:56'),
(2387, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=29', 125, '2025-05-12 18:37:04'),
(2388, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=29', 49, '2025-05-12 18:37:56'),
(2389, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=29', 10, '2025-05-12 18:38:08'),
(2390, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=29', 202, '2025-05-12 18:41:31'),
(2391, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=29', 51, '2025-05-12 18:42:24'),
(2392, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=29', 3, '2025-05-12 18:42:30'),
(2393, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=29', 71, '2025-05-12 18:43:43'),
(2394, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=29', 58, '2025-05-12 18:44:44'),
(2395, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=29', 89, '2025-05-12 18:46:14'),
(2396, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=29', 21, '2025-05-12 18:46:37'),
(2397, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=29', 10, '2025-05-12 18:46:49'),
(2398, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=29', 27, '2025-05-12 18:47:18'),
(2399, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=29', 236, '2025-05-12 18:51:17'),
(2400, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=29', 73, '2025-05-12 18:52:31'),
(2401, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=29', 117, '2025-05-12 18:54:30'),
(2402, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=29', 30, '2025-05-12 18:55:03'),
(2403, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=29', 45, '2025-05-12 18:55:50'),
(2404, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=29', 18, '2025-05-12 18:56:10'),
(2405, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=29', 24, '2025-05-12 18:56:36'),
(2406, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=29', 8, '2025-05-12 18:56:46'),
(2407, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=29', 478, '2025-05-12 19:04:46'),
(2408, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=29', 64, '2025-05-12 19:05:52'),
(2409, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=29', 115, '2025-05-12 19:07:49'),
(2410, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=29', 89, '2025-05-12 19:09:20'),
(2411, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=29', 75, '2025-05-12 19:10:37'),
(2412, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=29', 108, '2025-05-12 19:12:27'),
(2413, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=29', 238, '2025-05-12 19:16:28'),
(2414, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=29', 46, '2025-05-12 19:17:16'),
(2415, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=29', 425, '2025-05-12 19:24:24'),
(2416, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=29', 13, '2025-05-12 19:24:38'),
(2417, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=29', 66, '2025-05-12 19:25:46'),
(2418, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=29', 5614, '2025-05-12 20:59:22'),
(2419, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=29', 75, '2025-05-12 21:00:40'),
(2420, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=29', 112, '2025-05-12 21:09:23'),
(2421, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=29', 21, '2025-05-12 21:09:45'),
(2422, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=29', 3, '2025-05-12 21:09:51'),
(2423, 1, 'http://localhost/lineup_campus/dashboard', 1, '2025-05-12 21:09:54'),
(2424, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=29', 172, '2025-05-12 21:12:48'),
(2425, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=29', 4, '2025-05-12 21:12:54'),
(2426, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=29', 32, '2025-05-12 21:13:30'),
(2427, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=29', 200, '2025-05-12 21:16:54'),
(2428, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=29', 32, '2025-05-12 21:17:27'),
(2429, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=29', 12, '2025-05-12 21:17:41'),
(2430, 1, 'http://localhost/lineup_campus/dashboard', 1, '2025-05-12 21:17:43'),
(2431, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=29', 4, '2025-05-12 21:17:49'),
(2432, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=29', 1, '2025-05-12 21:17:53'),
(2433, 1, 'http://localhost/lineup_campus/dashboard', 50, '2025-05-12 21:18:45'),
(2434, 1, 'http://localhost/lineup_campus/dashboard', 0, '2025-05-12 21:18:46'),
(2435, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=29', 1, '2025-05-12 21:18:49'),
(2436, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=29', 46, '2025-05-12 21:19:37'),
(2437, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=29', 157, '2025-05-12 21:22:16'),
(2438, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=29', 342, '2025-05-12 21:28:00'),
(2439, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=29', 50, '2025-05-12 21:28:52'),
(2440, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=29', 43, '2025-05-12 21:29:37'),
(2441, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=29', 11, '2025-05-12 21:29:50'),
(2442, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=29', 8, '2025-05-12 21:30:01'),
(2443, 1, 'http://localhost/lineup_campus/dashboard', 24974, '2025-05-12 21:31:52'),
(2444, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=29', 179, '2025-05-12 21:33:02'),
(2445, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=29', 146, '2025-05-12 21:35:30'),
(2446, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=29', 9, '2025-05-12 21:35:41'),
(2447, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=29', 28, '2025-05-12 21:36:11'),
(2448, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=29', 59, '2025-05-12 21:37:12'),
(2449, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=29', 2, '2025-05-12 21:37:16'),
(2450, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=29', 15, '2025-05-12 21:37:33'),
(2451, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=29', 2, '2025-05-12 21:37:37'),
(2452, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=29', 157, '2025-05-12 21:40:16'),
(2453, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=29', 141, '2025-05-12 21:42:39'),
(2454, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=29', 18, '2025-05-12 21:42:59'),
(2455, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=29', 16, '2025-05-12 21:43:17'),
(2456, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=29', 90, '2025-05-12 21:44:49'),
(2457, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-yonetimi', 2, '2025-05-12 21:44:53'),
(2458, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-ekle', 22, '2025-05-12 21:45:18'),
(2459, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-ekle', 77, '2025-05-12 21:46:37'),
(2460, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-ekle', 3, '2025-05-12 21:46:41'),
(2461, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-yonetimi', 4, '2025-05-12 21:46:47'),
(2462, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=30', 23, '2025-05-12 21:47:12'),
(2463, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=30', 110, '2025-05-12 21:49:04'),
(2464, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=30', 187, '2025-05-12 21:52:13'),
(2465, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=30', 185, '2025-05-12 21:55:20'),
(2466, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=30', 48, '2025-05-12 21:56:10'),
(2467, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=30', 75, '2025-05-12 21:57:28'),
(2468, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=30', 48, '2025-05-12 21:58:18'),
(2469, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=30', 26, '2025-05-12 21:58:46'),
(2470, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=30', 19, '2025-05-12 21:59:48'),
(2471, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=30', 30, '2025-05-12 22:00:20'),
(2472, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=30', 244, '2025-05-12 22:04:26'),
(2473, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=30', 22, '2025-05-12 22:04:50'),
(2474, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=30', 76, '2025-05-12 22:06:09'),
(2475, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=30', 22, '2025-05-12 22:06:33'),
(2476, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=30', 43, '2025-05-12 22:07:19'),
(2477, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=30', 421, '2025-05-12 22:14:22'),
(2478, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=30', 11, '2025-05-12 22:14:35'),
(2479, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=30', 76, '2025-05-12 22:15:53'),
(2480, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=30', 114, '2025-05-12 22:17:49'),
(2481, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=30', 25, '2025-05-12 22:18:16'),
(2482, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=30', 113, '2025-05-12 22:20:11'),
(2483, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-yonetimi', 2, '2025-05-12 22:20:15'),
(2484, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-ekle', 24, '2025-05-12 22:20:41'),
(2485, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-ekle', 1, '2025-05-12 22:20:44'),
(2486, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-yonetimi', 0, '2025-05-12 22:20:46'),
(2487, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=30', 0, '2025-05-12 22:20:48'),
(2488, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-yonetimi', 3, '2025-05-12 22:20:53'),
(2489, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=31', 24, '2025-05-12 22:21:19'),
(2490, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=31', 22, '2025-05-12 22:21:43'),
(2491, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=31', 521, '2025-05-12 22:30:26'),
(2492, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=31', 4, '2025-05-12 22:30:32'),
(2493, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=31', 26, '2025-05-12 22:31:00'),
(2494, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=31', 42, '2025-05-12 22:31:43'),
(2495, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=31', 71, '2025-05-12 22:32:56'),
(2496, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=31', 10, '2025-05-12 22:33:08'),
(2497, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=31', 9, '2025-05-12 22:33:19'),
(2498, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=31', 0, '2025-05-12 22:33:21'),
(2499, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=31', 91, '2025-05-12 22:34:54'),
(2500, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=31', 48, '2025-05-12 22:35:44'),
(2501, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-yonetimi', 0, '2025-05-12 22:35:46'),
(2502, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-ekle', 30, '2025-05-12 22:36:18'),
(2503, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-ekle', 5, '2025-05-12 22:36:26'),
(2504, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-yonetimi', 7, '2025-05-12 22:36:34'),
(2505, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=32', 11, '2025-05-12 22:36:47'),
(2506, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=32', 2, '2025-05-12 22:36:50'),
(2507, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=32', 1, '2025-05-12 22:36:53'),
(2508, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=32', 1, '2025-05-12 22:36:55'),
(2509, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=32', 3, '2025-05-12 22:37:00'),
(2510, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-yonetimi', 1, '2025-05-12 22:37:03'),
(2511, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-ekle', 31, '2025-05-12 22:37:35'),
(2512, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-ekle', 3, '2025-05-12 22:37:41'),
(2513, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-yonetimi', 4, '2025-05-12 22:37:46'),
(2514, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=33', 48, '2025-05-12 22:38:37'),
(2515, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=33', 36, '2025-05-12 22:39:15'),
(2516, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-yonetimi', 1, '2025-05-12 22:39:18'),
(2517, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-ekle', 121, '2025-05-12 22:41:20'),
(2518, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-ekle', 39, '2025-05-12 22:42:01'),
(2519, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-ekle', 5, '2025-05-12 22:42:08'),
(2520, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-yonetimi', 5, '2025-05-12 22:42:15'),
(2521, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=36', 8, '2025-05-12 22:42:25'),
(2522, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-yonetimi', 5, '2025-05-12 22:42:32'),
(2523, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-ekle', 88, '2025-05-12 22:44:02'),
(2524, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-ekle', 50, '2025-05-12 22:44:54'),
(2525, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-ekle', 87, '2025-05-12 22:46:23'),
(2526, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-ekle', 113, '2025-05-12 22:48:18'),
(2527, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-ekle', 26, '2025-05-12 22:48:47'),
(2528, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-ekle', 0, '2025-05-12 22:48:49'),
(2529, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-ekle', 32, '2025-05-12 22:49:23'),
(2530, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-ekle', 3, '2025-05-12 22:49:27'),
(2531, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-yonetimi', 4, '2025-05-12 22:49:34'),
(2532, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=42', 23, '2025-05-12 22:49:58'),
(2533, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=42', 104, '2025-05-12 22:51:45'),
(2534, 68, 'http://localhost/lineup_campus/dashboard', 11, '2025-05-12 22:54:16'),
(2535, 1, 'http://localhost/lineup_campus/dashboard', 8, '2025-05-12 22:54:28'),
(2536, 1, 'http://localhost/lineup_campus/dashboard', 15, '2025-05-12 22:54:45'),
(2537, 1, 'http://localhost/lineup_campus/dashboard', 16, '2025-05-12 22:55:02'),
(2538, 1, 'http://localhost/lineup_campus/dashboard', 2, '2025-05-12 22:55:05'),
(2539, 68, 'http://localhost/lineup_campus/dashboard', 83, '2025-05-12 22:56:36'),
(2540, 68, 'http://localhost/lineup_campus/dashboard', 98, '2025-05-12 23:01:01'),
(2541, 68, 'http://localhost/lineup_campus/dashboard', 2, '2025-05-12 23:01:04'),
(2542, 68, 'http://localhost/lineup_campus/index', 1, '2025-05-12 23:01:06'),
(2543, 68, 'http://localhost/lineup_campus/index', 5, '2025-05-12 23:01:11'),
(2544, 68, 'http://localhost/lineup_campus/dashboard', 1, '2025-05-12 23:01:14'),
(2545, 68, 'http://localhost/lineup_campus/index', 29, '2025-05-12 23:01:44'),
(2546, 68, 'http://localhost/lineup_campus/dashboard', 1, '2025-05-12 23:01:47'),
(2547, 68, 'http://localhost/lineup_campus/dashboard', 0, '2025-05-12 23:01:49'),
(2548, 68, 'http://localhost/lineup_campus/index', 68, '2025-05-12 23:02:58'),
(2549, 68, 'http://localhost/lineup_campus/dashboard', 1, '2025-05-12 23:03:00'),
(2550, 68, 'http://localhost/lineup_campus/ana-okulu-icerik-yonetimi', 49, '2025-05-12 23:03:51'),
(2551, 68, 'http://localhost/lineup_campus/ana-okulu-icerik-yonetimi', 0, '2025-05-12 23:03:53'),
(2552, 68, 'http://localhost/lineup_campus/ana-okulu-icerik-yonetimi', 181, '2025-05-12 23:06:57'),
(2553, 68, 'http://localhost/lineup_campus/ana-okulu-icerik-yonetimi', 1, '2025-05-12 23:06:59'),
(2554, 68, 'http://localhost/lineup_campus/yas-grubu', 38, '2025-05-12 23:07:39'),
(2555, 68, 'http://localhost/lineup_campus/yas-grubu', 39, '2025-05-12 23:07:40'),
(2556, 68, 'http://localhost/lineup_campus/kategori-basliklari', 3, '2025-05-12 23:07:45'),
(2557, 68, 'http://localhost/lineup_campus/ana-okulu-icerik-yonetimi', 0, '2025-05-12 23:07:47'),
(2558, 68, 'http://localhost/lineup_campus/kategori-basliklari', 4, '2025-05-12 23:07:53'),
(2559, 68, 'http://localhost/lineup_campus/ana-okulu-icerik-yonetimi', 5, '2025-05-12 23:07:59'),
(2560, 68, 'http://localhost/lineup_campus/index', 36, '2025-05-12 23:08:36'),
(2561, 68, 'http://localhost/lineup_campus/dashboard', 22, '2025-05-12 23:09:00'),
(2562, 68, 'http://localhost/lineup_campus/kategori-basliklari', 0, '2025-05-12 23:09:02'),
(2563, 68, 'http://localhost/lineup_campus/onemli-haftalar', 198, '2025-05-12 23:12:21'),
(2564, 68, 'http://localhost/lineup_campus/ana-okulu-icerik-yonetimi', 0, '2025-05-12 23:12:23'),
(2565, 68, 'http://localhost/lineup_campus/ana-okulu-icerik-ekle', 51, '2025-05-12 23:13:15'),
(2566, 68, 'http://localhost/lineup_campus/ana-okulu-icerik-ekle', 11, '2025-05-12 23:13:29'),
(2567, 68, 'http://localhost/lineup_campus/ana-okulu-icerik-yonetimi', 3, '2025-05-12 23:13:34'),
(2568, 68, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=22', 4, '2025-05-12 23:13:39'),
(2569, 68, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=22', 201, '2025-05-12 23:17:02'),
(2570, 68, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=22', 4, '2025-05-12 23:17:08'),
(2571, 68, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=22', 1, '2025-05-12 23:17:11'),
(2572, 68, 'http://localhost/lineup_campus/ana-okulu-icerik-yonetimi', 1, '2025-05-12 23:17:13'),
(2573, 68, 'http://localhost/lineup_campus/ana-okulu-icerik-ekle', 16, '2025-05-12 23:17:31'),
(2574, 68, 'http://localhost/lineup_campus/ana-okulu-icerik-ekle', 3, '2025-05-12 23:17:37'),
(2575, 68, 'http://localhost/lineup_campus/ana-okulu-icerik-yonetimi', 3, '2025-05-12 23:17:41'),
(2576, 68, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=43', 13, '2025-05-12 23:17:56'),
(2577, 1, 'http://localhost/lineup_campus/dashboard', 34, '2025-05-13 08:45:43'),
(2578, 1, 'http://localhost/lineup_campus/kategori-basliklari', 280, '2025-05-13 08:50:24'),
(2579, 1, 'http://localhost/lineup_campus/kategori-basliklari', 104, '2025-05-13 08:52:09'),
(2580, 1, 'http://localhost/lineup_campus/kategori-basliklari', 75, '2025-05-13 08:53:26'),
(2581, 1, 'http://localhost/lineup_campus/kategori-basliklari', 1405, '2025-05-13 09:16:53'),
(2582, 1, 'http://localhost/lineup_campus/kategori-basliklari', 8, '2025-05-13 09:17:02'),
(2583, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-yonetimi', 1, '2025-05-13 09:17:04'),
(2584, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-ekle', 318, '2025-05-13 09:22:24'),
(2585, 1, 'http://localhost/lineup_campus2/dashboard', 3, '2025-05-13 09:22:45'),
(2586, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-yonetimi', 1, '2025-05-13 09:22:48'),
(2587, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-ekle', 153, '2025-05-13 09:25:22'),
(2588, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-ekle', 136, '2025-05-13 09:27:41'),
(2589, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-ekle', 98, '2025-05-13 09:29:21'),
(2590, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-ekle', 10, '2025-05-13 09:29:31'),
(2591, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-ekle', 62, '2025-05-13 09:30:38'),
(2592, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-ekle', 390, '2025-05-13 09:37:10'),
(2593, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-ekle', 15, '2025-05-13 09:37:27'),
(2594, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-yonetimi', 3, '2025-05-13 09:37:32'),
(2595, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=43', 16, '2025-05-13 09:42:14'),
(2596, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-yonetimi', 1, '2025-05-13 09:42:17'),
(2597, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-ekle', 287, '2025-05-13 09:47:07'),
(2598, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-ekle', 3, '2025-05-13 09:47:12'),
(2599, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-yonetimi', 4, '2025-05-13 09:47:19'),
(2600, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=43', 9, '2025-05-13 09:50:19'),
(2601, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=42', 12, '2025-05-13 09:50:33'),
(2602, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=42', 39, '2025-05-13 09:51:13'),
(2603, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=42', 2, '2025-05-13 09:51:18'),
(2604, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=42', 140, '2025-05-13 09:53:40'),
(2605, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=42', 199, '2025-05-13 09:57:01'),
(2606, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=42', 64, '2025-05-13 09:58:08'),
(2607, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=42', 11, '2025-05-13 09:58:21'),
(2608, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=42', 18, '2025-05-13 09:58:42'),
(2609, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=42', 5, '2025-05-13 09:58:49'),
(2610, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=42', 31, '2025-05-13 09:59:23'),
(2611, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-yonetimi', 1, '2025-05-13 10:06:11'),
(2612, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-ekle', 20, '2025-05-13 10:06:34'),
(2613, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-ekle', 5, '2025-05-13 10:06:41'),
(2614, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-yonetimi', 3, '2025-05-13 10:06:46'),
(2615, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=42', 472, '2025-05-13 10:11:11'),
(2616, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=42', 71, '2025-05-13 10:12:24'),
(2617, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=42', 12, '2025-05-13 10:12:38'),
(2618, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=42', 7, '2025-05-13 10:12:47'),
(2619, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=42', 6, '2025-05-13 10:12:55'),
(2620, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=42', 3, '2025-05-13 10:13:00'),
(2621, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=42', 81, '2025-05-13 10:14:26'),
(2622, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=42', 6, '2025-05-13 10:14:32'),
(2623, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=42', 63, '2025-05-13 10:15:38'),
(2624, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=42', 89, '2025-05-13 10:17:10'),
(2625, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=42', 22, '2025-05-13 10:17:34'),
(2626, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=42', 91, '2025-05-13 10:19:07'),
(2627, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=42', 30, '2025-05-13 10:19:40'),
(2628, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=42', 25, '2025-05-13 10:20:07'),
(2629, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=42', 84, '2025-05-13 10:21:33'),
(2630, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=44', 924, '2025-05-13 10:22:12'),
(2631, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=42', 1848, '2025-05-13 10:52:23'),
(2632, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=42', 112, '2025-05-13 10:54:18'),
(2633, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=42', 16, '2025-05-13 10:54:36'),
(2634, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=42', 102, '2025-05-13 10:56:20'),
(2635, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=42', 26, '2025-05-13 10:56:48'),
(2636, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=42', 56, '2025-05-13 10:57:46'),
(2637, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=42', 23, '2025-05-13 10:58:11'),
(2638, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=42', 40, '2025-05-13 10:58:53'),
(2639, 68, 'http://localhost/lineup_campus/dashboard', 31, '2025-05-13 10:59:30'),
(2640, 68, 'http://localhost/lineup_campus/dashboard', 3, '2025-05-13 10:59:35'),
(2641, 68, 'http://localhost/lineup_campus/index', 66, '2025-05-13 11:00:42'),
(2642, 68, 'http://localhost/lineup_campus/dashboard', 4, '2025-05-13 11:00:48'),
(2643, 68, 'http://localhost/lineup_campus/ana-okulu-icerik-yonetimi', 1, '2025-05-13 11:00:51'),
(2644, 68, 'http://localhost/lineup_campus/ana-okulu-icerik-ekle', 41, '2025-05-13 11:01:34'),
(2645, 68, 'http://localhost/lineup_campus/yas-grubu', 8, '2025-05-13 11:01:44'),
(2646, 68, 'http://localhost/lineup_campus/yas-grubu', 16, '2025-05-13 11:02:01'),
(2647, 68, 'http://localhost/lineup_campus/onemli-haftalar', 36, '2025-05-13 11:02:39'),
(2648, 68, 'http://localhost/lineup_campus/onemli-haftalar', 20, '2025-05-13 11:03:01'),
(2649, 68, 'http://localhost/lineup_campus/onemli-haftalar', 2, '2025-05-13 11:03:05'),
(2650, 68, 'http://localhost/lineup_campus/index', 1, '2025-05-13 11:03:06'),
(2651, 68, 'http://localhost/lineup_campus/onemli-haftalar', 10, '2025-05-13 11:03:18'),
(2652, 68, 'http://localhost/lineup_campus/onemli-haftalar', 0, '2025-05-13 11:03:20'),
(2653, 68, 'http://localhost/lineup_campus/kategori-basliklari', 9, '2025-05-13 11:03:31'),
(2654, 68, 'http://localhost/lineup_campus/ana-okulu-icerik-yonetimi', 1, '2025-05-13 11:03:33'),
(2655, 68, 'http://localhost/lineup_campus/onemli-haftalar', 9, '2025-05-13 11:03:44'),
(2656, 68, 'http://localhost/lineup_campus/onemli-haftalar', 38, '2025-05-13 11:04:23'),
(2657, 68, 'http://localhost/lineup_campus/index', 20, '2025-05-13 11:04:43'),
(2658, 68, 'http://localhost/lineup_campus/dashboard', 5, '2025-05-13 11:04:50'),
(2659, 68, 'http://localhost/lineup_campus/onemli-haftalar', 27, '2025-05-13 11:05:19'),
(2660, 68, 'http://localhost/lineup_campus/onemli-haftalar', 0, '2025-05-13 11:05:22'),
(2661, 68, 'http://localhost/lineup_campus/ana-okulu-icerik-yonetimi', 17, '2025-05-13 11:05:41'),
(2662, 68, 'http://localhost/lineup_campus/ana-okulu-icerik-ekle', 35, '2025-05-13 11:06:18'),
(2663, 68, 'http://localhost/lineup_campus/ana-okulu-icerik-ekle', 112, '2025-05-13 11:08:12'),
(2664, 68, 'http://localhost/lineup_campus/ana-okulu-icerik-ekle', 19, '2025-05-13 11:08:33'),
(2665, 68, 'http://localhost/lineup_campus/ana-okulu-icerik-ekle', 4, '2025-05-13 11:08:38'),
(2666, 68, 'http://localhost/lineup_campus/ana-okulu-icerik-ekle', 3, '2025-05-13 11:08:44'),
(2667, 68, 'http://localhost/lineup_campus/ana-okulu-icerik-yonetimi', 6, '2025-05-13 11:08:51'),
(2668, 68, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=55', 80, '2025-05-13 11:10:13'),
(2669, 68, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=55', 4, '2025-05-13 11:10:23'),
(2670, 68, 'http://localhost/lineup_campus/ana-okulu-icerik-yonetimi', 0, '2025-05-13 11:10:26'),
(2671, 68, 'http://localhost/lineup_campus/onemli-haftalar', 16, '2025-05-13 11:10:43'),
(2672, 68, 'http://localhost/lineup_campus/onemli-haftalar', 47, '2025-05-13 11:11:32'),
(2673, 68, 'http://localhost/lineup_campus/ana-okulu-icerik-yonetimi', 3, '2025-05-13 11:11:36'),
(2674, 68, 'http://localhost/lineup_campus/ana-okulu-icerik-yonetimi', 4, '2025-05-13 11:11:38'),
(2675, 68, 'http://localhost/lineup_campus/ana-okulu-icerik-ekle', 17, '2025-05-13 11:11:56'),
(2676, 68, 'http://localhost/lineup_campus/ana-okulu-icerik-yonetimi', 25, '2025-05-13 11:12:23'),
(2677, 68, 'http://localhost/lineup_campus/onemli-haftalar', 46, '2025-05-13 11:13:11'),
(2678, 68, 'http://localhost/lineup_campus/onemli-haftalar', 219, '2025-05-13 11:16:51'),
(2679, 68, 'http://localhost/lineup_campus/onemli-haftalar', 29, '2025-05-13 11:17:23'),
(2680, 68, 'http://localhost/lineup_campus/onemli-haftalar', 18, '2025-05-13 11:17:42'),
(2681, 68, 'http://localhost/lineup_campus/onemli-haftalar', 221, '2025-05-13 11:21:25'),
(2682, 68, 'http://localhost/lineup_campus/onemli-haftalar', 22, '2025-05-13 11:21:49'),
(2683, 68, 'http://localhost/lineup_campus/onemli-haftalar', 8, '2025-05-13 11:21:59'),
(2684, 68, 'http://localhost/lineup_campus/onemli-haftalar', 36, '2025-05-13 11:22:37'),
(2685, 68, 'http://localhost/lineup_campus/onemli-haftalar', 23, '2025-05-13 11:23:01'),
(2686, 68, 'http://localhost/lineup_campus/onemli-haftalar', 11, '2025-05-13 11:23:14'),
(2687, 68, 'http://localhost/lineup_campus/onemli-haftalar', 2, '2025-05-13 11:23:18'),
(2688, 68, 'http://localhost/lineup_campus/onemli-haftalar', 10, '2025-05-13 11:23:31'),
(2689, 68, 'http://localhost/lineup_campus/onemli-haftalar', 202, '2025-05-13 11:26:54'),
(2690, 68, 'http://localhost/lineup_campus/onemli-haftalar', 295, '2025-05-13 11:31:51'),
(2691, 68, 'http://localhost/lineup_campus/onemli-haftalar', 1, '2025-05-13 11:31:54'),
(2692, 68, 'http://localhost/lineup_campus/onemli-haftalar', 14, '2025-05-13 11:32:10'),
(2693, 68, 'http://localhost/lineup_campus/onemli-haftalar', 115, '2025-05-13 11:34:07'),
(2694, 68, 'http://localhost/lineup_campus/onemli-haftalar', 112, '2025-05-13 11:38:45'),
(2695, 68, 'http://localhost/lineup_campus/onemli-haftalar', 16, '2025-05-13 11:39:03'),
(2696, 68, 'http://localhost/lineup_campus/onemli-haftalar', 15, '2025-05-13 11:39:20'),
(2697, 68, 'http://localhost/lineup_campus/onemli-haftalar', 13, '2025-05-13 11:39:35'),
(2698, 68, 'http://localhost/lineup_campus/onemli-haftalar', 53, '2025-05-13 11:40:30'),
(2699, 68, 'http://localhost/lineup_campus/onemli-haftalar', 82, '2025-05-13 11:41:54'),
(2700, 68, 'http://localhost/lineup_campus/onemli-haftalar', 189, '2025-05-13 11:45:05'),
(2701, 68, 'http://localhost/lineup_campus/onemli-haftalar', 29, '2025-05-13 11:45:36'),
(2702, 68, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=44', 10415, '2025-05-13 13:15:49'),
(2703, 68, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=44', 2, '2025-05-13 13:15:52'),
(2704, 68, 'http://localhost/lineup_campus/ana-okulu-icerikler', 57, '2025-05-13 13:17:27'),
(2705, 68, 'http://localhost/lineup_campus/ana-okulu-icerikler', 110, '2025-05-13 13:19:20'),
(2706, 68, 'http://localhost/lineup_campus/ana-okulu-icerikler', 19, '2025-05-13 13:19:40'),
(2707, 68, 'http://localhost/lineup_campus/ana-okulu-icerikler', 627, '2025-05-13 13:30:08'),
(2708, 68, 'http://localhost/lineup_campus/ana-okulu-icerikler', 124, '2025-05-13 13:32:14'),
(2709, 68, 'http://localhost/lineup_campus/ana-okulu-icerikler', 26, '2025-05-13 13:32:41'),
(2710, 68, 'http://localhost/lineup_campus/ana-okulu-icerikler', 186, '2025-05-13 13:35:49'),
(2711, 68, 'http://localhost/lineup_campus/ana-okulu-icerikler', 31, '2025-05-13 13:36:22'),
(2712, 68, 'http://localhost/lineup_campus/ana-okulu-icerikler', 356, '2025-05-13 13:42:20'),
(2713, 68, 'http://localhost/lineup_campus/ana-okulu-icerikler', 80, '2025-05-13 13:43:42'),
(2714, 68, 'http://localhost/lineup_campus/ana-okulu-icerikler', 255, '2025-05-13 13:47:59'),
(2715, 68, 'http://localhost/lineup_campus/ana-okulu-icerikler', 97, '2025-05-13 13:49:39'),
(2716, 68, 'http://localhost/lineup_campus/ana-okulu-icerikler', 82, '2025-05-13 13:51:03'),
(2717, 68, 'http://localhost/lineup_campus/ana-okulu-icerikler', 28, '2025-05-13 13:51:33'),
(2718, 68, 'http://localhost/lineup_campus/ana-okulu-icerikler', 20, '2025-05-13 13:51:55'),
(2719, 68, 'http://localhost/lineup_campus/ana-okulu-icerikler', 100, '2025-05-13 13:53:37'),
(2720, 68, 'http://localhost/lineup_campus/ana-okulu-icerikler', 111, '2025-05-13 13:56:24'),
(2721, 68, 'http://localhost/lineup_campus/ana-okulu-icerikler', 8, '2025-05-13 13:56:33'),
(2722, 68, 'http://localhost/lineup_campus/ana-okulu-icerikler', 14, '2025-05-13 13:56:50'),
(2723, 68, 'http://localhost/lineup_campus/ana-okulu-icerikler', 101, '2025-05-13 13:58:33'),
(2724, 68, 'http://localhost/lineup_campus/ana-okulu-icerikler', 24, '2025-05-13 13:58:59'),
(2725, 68, 'http://localhost/lineup_campus/ana-okulu-icerikler', 21, '2025-05-13 13:59:22'),
(2726, 68, 'http://localhost/lineup_campus/ana-okulu-icerikler', 17, '2025-05-13 13:59:41'),
(2727, 68, 'http://localhost/lineup_campus/ana-okulu-icerikler', 104, '2025-05-13 14:01:27'),
(2728, 68, 'http://localhost/lineup_campus/ana-okulu-icerikler', 47, '2025-05-13 14:02:16'),
(2729, 68, 'http://localhost/lineup_campus/ana-okulu-icerik-yonetimi', 3, '2025-05-13 14:02:21'),
(2730, 68, 'http://localhost/lineup_campus/onemli-haftalar', 8272, '2025-05-13 14:03:29'),
(2731, 68, 'http://localhost/lineup_campus/ana-okulu-icerik-yonetimi', 4, '2025-05-13 14:03:35'),
(2732, 68, 'http://localhost/lineup_campus/ana-okulu-icerik-yonetimi', 2, '2025-05-13 14:03:40'),
(2733, 68, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=18', 369, '2025-05-13 14:08:31'),
(2734, 68, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=18', 4, '2025-05-13 14:08:38'),
(2735, 68, 'http://localhost/lineup_campus/ana-okulu-icerikler', 61, '2025-05-13 14:09:41'),
(2736, 68, 'http://localhost/lineup_campus/yas-grubu', 83, '2025-05-13 14:11:06'),
(2737, 68, 'http://localhost/lineup_campus/ana-okulu-icerikler', 143, '2025-05-13 14:13:30'),
(2738, 68, 'http://localhost/lineup_campus/ana-okulu-icerik-yonetimi', 1, '2025-05-13 14:13:33'),
(2739, 68, 'http://localhost/lineup_campus/ana-okulu-icerik-ekle', 203, '2025-05-13 14:16:58'),
(2740, 68, 'http://localhost/lineup_campus/ana-okulu-icerikler', 223, '2025-05-13 14:20:42'),
(2741, 68, 'http://localhost/lineup_campus/ana-okulu-icerikler', 31, '2025-05-13 14:21:15'),
(2742, 68, 'http://localhost/lineup_campus/ana-okulu-icerik-yonetimi', 1, '2025-05-13 14:21:18'),
(2743, 68, 'http://localhost/lineup_campus/ana-okulu-icerik-ekle', 93, '2025-05-13 14:22:53'),
(2744, 68, 'http://localhost/lineup_campus/ana-okulu-icerik-ekle', 191, '2025-05-13 14:26:05'),
(2745, 68, 'http://localhost/lineup_campus/ana-okulu-icerik-ekle', 16, '2025-05-13 14:26:23'),
(2746, 68, 'http://localhost/lineup_campus/ana-okulu-icerik-ekle', 38, '2025-05-13 14:27:04'),
(2747, 68, 'http://localhost/lineup_campus/ana-okulu-icerik-ekle', 55, '2025-05-13 14:28:00'),
(2748, 68, 'http://localhost/lineup_campus/ana-okulu-icerik-ekle', 2, '2025-05-13 14:28:04'),
(2749, 68, 'http://localhost/lineup_campus/ana-okulu-icerik-yonetimi', 3, '2025-05-13 14:28:09'),
(2750, 68, 'http://localhost/lineup_campus/ana-okulu-icerikler', 58, '2025-05-13 14:29:10'),
(2751, 68, 'http://localhost/lineup_campus/ana-okulu-icerikler', 59, '2025-05-13 14:29:11'),
(2752, 68, 'http://localhost/lineup_campus/ana-okulu-icerik-yonetimi', 6, '2025-05-13 14:29:19'),
(2753, 68, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=57', 73, '2025-05-13 14:30:34'),
(2754, 68, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=57', 84, '2025-05-13 14:31:59'),
(2755, 68, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=57', 40, '2025-05-13 14:32:41'),
(2756, 68, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=57', 15, '2025-05-13 14:32:58'),
(2757, 68, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=57', 2, '2025-05-13 14:33:02'),
(2758, 68, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=57', 5, '2025-05-13 14:33:10'),
(2759, 68, 'http://localhost/lineup_campus/ana-okulu-icerikler', 155, '2025-05-13 14:35:46'),
(2760, 68, 'http://localhost/lineup_campus/ana-okulu-icerikler', 2, '2025-05-13 14:35:50'),
(2761, 68, 'http://localhost/lineup_campus/ana-okulu-icerik-yonetimi', 1, '2025-05-13 14:35:52'),
(2762, 68, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=55', 4488, '2025-05-13 15:18:30'),
(2763, 68, 'http://localhost/lineup_campus/ana-okulu-icerikler', 30, '2025-05-13 15:19:02'),
(2764, 68, 'http://localhost/lineup_campus/ana-okulu-icerikler', 61, '2025-05-13 15:20:04'),
(2765, 68, 'http://localhost/lineup_campus/ana-okulu-icerikler', 2, '2025-05-13 15:20:08'),
(2766, 68, 'http://localhost/lineup_campus/ana-okulu-icerikler-detay.php?id=18', 43, '2025-05-13 15:20:52'),
(2767, 68, 'http://localhost/lineup_campus/ana-okulu-icerikler-detay.php?id=18', 52, '2025-05-13 15:21:46'),
(2768, 68, 'http://localhost/lineup_campus/ana-okulu-icerikler-detay.php?id=18', 75, '2025-05-13 15:23:04'),
(2769, 68, 'http://localhost/lineup_campus/ana-okulu-icerikler-detay.php?id=18', 138, '2025-05-13 15:25:23'),
(2770, 68, 'http://localhost/lineup_campus/ana-okulu-icerikler-detay.php?id=18', 103, '2025-05-13 15:27:08'),
(2771, 68, 'http://localhost/lineup_campus/ana-okulu-icerikler-detay.php?id=18', 12, '2025-05-13 15:27:21'),
(2772, 68, 'http://localhost/lineup_campus/ana-okulu-icerikler-detay.php?id=18', 78, '2025-05-13 15:28:41'),
(2773, 68, 'http://localhost/lineup_campus/ana-okulu-icerikler-detay.php?id=18', 17, '2025-05-13 15:29:00'),
(2774, 68, 'http://localhost/lineup_campus/ana-okulu-icerikler-detay.php?id=18', 12, '2025-05-13 15:29:13'),
(2775, 68, 'http://localhost/lineup_campus/ana-okulu-icerikler-detay.php?id=18', 127, '2025-05-13 15:31:22'),
(2776, 68, 'http://localhost/lineup_campus/ana-okulu-icerikler-detay.php?id=18', 175, '2025-05-13 15:34:18'),
(2777, 68, 'http://localhost/lineup_campus/ana-okulu-icerikler-detay.php?id=18', 11, '2025-05-13 15:34:31'),
(2778, 68, 'http://localhost/lineup_campus/ana-okulu-icerikler-detay.php?id=18', 85, '2025-05-13 15:35:57'),
(2779, 68, 'http://localhost/lineup_campus/ana-okulu-icerikler-detay.php?id=18', 35, '2025-05-13 15:36:34'),
(2780, 68, 'http://localhost/lineup_campus/ana-okulu-icerikler', 5, '2025-05-13 15:36:40'),
(2781, 68, 'http://localhost/lineup_campus/ana-okulu-icerik-yonetimi', 3, '2025-05-13 15:36:45'),
(2782, 68, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=57', 7, '2025-05-13 15:36:54'),
(2783, 68, 'http://localhost/lineup_campus/ana-okulu-icerikler', 3672, '2025-05-13 15:37:06'),
(2784, 68, 'http://localhost/lineup_campus/ana-okulu-icerikler', 4, '2025-05-13 15:37:14'),
(2785, 68, 'http://localhost/lineup_campus/ana-okulu-icerikler-detay.php?id=57', 57, '2025-05-13 15:38:12'),
(2786, 68, 'http://localhost/lineup_campus/ana-okulu-icerikler-detay.php?id=57', 67, '2025-05-13 15:39:22'),
(2787, 68, 'http://localhost/lineup_campus/ana-okulu-icerikler-detay.php?id=57', 32, '2025-05-13 15:39:55'),
(2788, 68, 'http://localhost/lineup_campus/ana-okulu-icerikler-detay.php?id=57', 81, '2025-05-13 15:41:18'),
(2789, 68, 'http://localhost/lineup_campus/ana-okulu-icerikler-detay.php?id=57', 67, '2025-05-13 15:42:26'),
(2790, 68, 'http://localhost/lineup_campus/ana-okulu-icerikler-detay.php?id=57', 23, '2025-05-13 15:42:51'),
(2791, 68, 'http://localhost/lineup_campus/ana-okulu-icerikler-detay.php?id=57', 31, '2025-05-13 15:43:23'),
(2792, 68, 'http://localhost/lineup_campus/ana-okulu-icerikler-detay.php?id=57', 62, '2025-05-13 15:44:27'),
(2793, 68, 'http://localhost/lineup_campus/ana-okulu-icerikler-detay.php?id=57', 126, '2025-05-13 15:46:35'),
(2794, 68, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=57', 611, '2025-05-13 15:47:07'),
(2795, 68, 'http://localhost/lineup_campus/ana-okulu-icerikler-detay.php?id=57', 33, '2025-05-13 15:47:10'),
(2796, 68, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=57', 81, '2025-05-13 15:48:30'),
(2797, 68, 'http://localhost/lineup_campus/ana-okulu-icerikler-detay.php?id=57', 81, '2025-05-13 15:48:34'),
(2798, 68, 'http://localhost/lineup_campus/ana-okulu-icerikler-detay.php?id=57', 26, '2025-05-13 15:49:02'),
(2799, 68, 'http://localhost/lineup_campus/ana-okulu-icerikler-detay.php?id=57', 25, '2025-05-13 15:49:29'),
(2800, 68, 'http://localhost/lineup_campus/ana-okulu-icerikler-detay.php?id=57', 27, '2025-05-13 15:49:58'),
(2801, 68, 'http://localhost/lineup_campus/ana-okulu-icerikler-detay.php?id=57', 66, '2025-05-13 15:51:05');
INSERT INTO `timespend_lnp` (`id`, `user_id`, `sayfa_url`, `timeSpent`, `exitTime`) VALUES
(2802, 68, 'http://localhost/lineup_campus/ana-okulu-icerikler-detay.php?id=57', 90, '2025-05-13 15:52:37'),
(2803, 68, 'http://localhost/lineup_campus/ana-okulu-icerikler-detay.php?id=57', 13, '2025-05-13 15:52:50'),
(2804, 68, 'http://localhost/lineup_campus/ana-okulu-icerikler-detay.php?id=57', 74, '2025-05-13 15:54:06'),
(2805, 68, 'http://localhost/lineup_campus/ana-okulu-icerikler', 4, '2025-05-13 15:54:12'),
(2806, 68, 'http://localhost/lineup_campus/ana-okulu-icerikler-detay.php?id=57', 1747, '2025-05-13 16:23:21'),
(2807, 68, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=57', 5351, '2025-05-13 17:17:43'),
(2808, 68, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=57', 62, '2025-05-13 17:18:47'),
(2809, 68, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=57', 4, '2025-05-13 17:18:53'),
(2810, 68, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=57', 17, '2025-05-13 17:19:13'),
(2811, 68, 'http://localhost/lineup_campus/ana-okulu-icerikler', 95, '2025-05-13 17:20:50'),
(2812, 68, 'http://localhost/lineup_campus/ana-okulu-icerikler', 327, '2025-05-13 17:26:17'),
(2813, 68, 'http://localhost/lineup_campus/ana-okulu-icerikler', 61, '2025-05-13 17:27:20'),
(2814, 68, 'http://localhost/lineup_campus/ana-okulu-icerikler-detay.php?id=57', 66366, '2025-05-14 10:49:28'),
(2815, 68, 'http://localhost/lineup_campus/ana-okulu-icerikler', 19, '2025-05-14 10:49:50'),
(2816, 68, 'http://localhost/lineup_campus/ana-okulu-icerikler-detay.php?id=20', 119, '2025-05-14 10:51:50'),
(2817, 68, 'http://localhost/lineup_campus/ana-okulu-icerikler-detay.php?id=20', 20, '2025-05-14 10:52:12'),
(2818, 68, 'http://localhost/lineup_campus/ana-okulu-icerikler', 66198, '2025-05-14 11:50:40'),
(2819, 68, 'http://localhost/lineup_campus/index', 2, '2025-05-14 11:50:42'),
(2820, 68, 'http://localhost/lineup_campus/ana-okulu-icerikler-detay.php?id=20', 3512, '2025-05-14 11:50:46'),
(2821, 68, 'http://localhost/lineup_campus/ana-okulu-icerikler-detay.php?id=20', 182, '2025-05-14 11:53:50'),
(2822, 68, 'http://localhost/lineup_campus/ana-okulu-icerikler-detay.php?id=20', 21, '2025-05-14 11:54:13'),
(2823, 68, 'http://localhost/lineup_campus/ana-okulu-icerikler-detay.php?id=20', 15, '2025-05-14 11:54:30'),
(2824, 1, 'http://localhost/lineup_campus/dashboard', 19, '2025-05-14 11:54:54'),
(2825, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-yonetimi', 3, '2025-05-14 11:54:59'),
(2826, 1, 'http://localhost/lineup_campus/ana-okulu-icerikler', 18, '2025-05-14 11:55:19'),
(2827, 1, 'http://localhost/lineup_campus/ana-okulu-icerikler-detay.php?id=20', 13, '2025-05-14 11:55:33'),
(2828, 1, 'http://localhost/lineup_campus/ana-okulu-icerikler', 3, '2025-05-14 11:55:39'),
(2829, 1, 'http://localhost/lineup_campus/ana-okulu-icerikler-detay.php?id=57', 18616, '2025-05-14 17:05:57'),
(2830, 1, 'http://localhost/lineup_campus/ana-okulu-icerikler', 3, '2025-05-14 17:06:03'),
(2831, 1, 'http://localhost/lineup_campus/ana-okulu-icerikler-detay.php?id=28', 12, '2025-05-14 17:06:17'),
(2832, 1, 'http://localhost/lineup_campus/ana-okulu-icerikler-detay.php?id=28', 61632, '2025-05-15 10:13:30'),
(2833, 1, 'http://localhost/lineup_campus/dashboard', 41, '2025-05-16 08:56:33'),
(2834, 1, 'http://localhost/lineup_campus/dashboard', 244, '2025-05-16 09:00:38'),
(2835, 1, 'http://localhost/lineup_campus/dashboard', 5, '2025-05-16 09:00:45'),
(2836, 68, 'http://localhost/lineup_campus/dashboard', 69, '2025-05-16 09:02:00'),
(2837, 68, 'http://localhost/lineup_campus/dashboard', 336, '2025-05-16 09:07:37'),
(2838, 68, 'http://localhost/lineup_campus/ana-okulu-icerikler', 9, '2025-05-16 09:07:47'),
(2839, 68, 'http://localhost/lineup_campus/ana-okulu-icerikler-detay.php?id=28', 2, '2025-05-16 09:07:51'),
(2840, 68, 'http://localhost/lineup_campus/ana-okulu-icerikler', 54, '2025-05-16 09:08:47'),
(2841, 68, 'http://localhost/lineup_campus/ana-okulu-icerikler', 2, '2025-05-16 09:08:51'),
(2842, 68, 'http://localhost/lineup_campus/ana-okulu-icerikler-detay.php?id=57', 434, '2025-05-16 09:16:06'),
(2843, 68, 'http://localhost/lineup_campus/ana-okulu-icerikler-detay.php?id=57', 24, '2025-05-16 09:16:31'),
(2844, 68, 'http://localhost/lineup_campus/ana-okulu-icerikler-detay.php?id=57', 14, '2025-05-16 09:16:47'),
(2845, 68, 'http://localhost/lineup_campus/ana-okulu-icerikler-detay.php?id=57', 449, '2025-05-16 09:24:18'),
(2846, 68, 'http://localhost/lineup_campus/ana-okulu-icerikler-detay.php?id=57', 1, '2025-05-16 09:24:20'),
(2847, 68, 'http://localhost/lineup_campus/ana-okulu-icerikler', 48, '2025-05-16 09:25:10'),
(2848, 68, 'http://localhost/lineup_campus/ana-okulu-icerik-yonetimi', 9, '2025-05-16 09:25:21'),
(2849, 1, 'http://localhost/lineup_campus/dashboard', 10, '2025-05-16 09:25:34'),
(2850, 1, 'http://localhost/lineup_campus/siniflar', 16, '2025-05-16 09:25:52'),
(2851, 1, 'http://localhost/lineup_campus/uniteler', 7, '2025-05-16 09:26:00'),
(2852, 1, 'http://localhost/lineup_campus/uniteler', 8, '2025-05-16 09:26:02'),
(2853, 1, 'http://localhost/lineup_campus/okullar', 8, '2025-05-16 09:26:12'),
(2854, 1, 'http://localhost/lineup_campus/paketler', 2, '2025-05-16 09:26:16'),
(2855, 1, 'http://localhost/lineup_campus/paket-detay?id=1', 23, '2025-05-16 09:26:41'),
(2856, 1, 'http://localhost/lineup_campus/kupon', 75, '2025-05-16 09:27:58'),
(2857, 1, 'http://localhost/lineup_campus/sesli-kitaplar', 26, '2025-05-16 09:28:26'),
(2858, 1, 'http://localhost/lineup_campus/sesli-kitaplar', 4, '2025-05-16 09:28:32'),
(2859, 1, 'http://localhost/lineup_campus/oyunlar', 9, '2025-05-16 09:28:43'),
(2860, 1, 'http://localhost/lineup_campus/yas-grubu', 5, '2025-05-16 09:28:50'),
(2861, 1, 'http://localhost/lineup_campus/onemli-haftalar', 19, '2025-05-16 09:29:10'),
(2862, 1, 'http://localhost/lineup_campus/kategori-basliklari', 3, '2025-05-16 09:29:15'),
(2863, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-yonetimi', 3, '2025-05-16 09:29:20'),
(2864, 1, 'http://localhost/lineup_campus/ana-okulu-icerikler', 3, '2025-05-16 09:29:25'),
(2865, 1, 'http://localhost/lineup_campus/ana-okulu-icerikler-detay.php?id=55', 13, '2025-05-16 09:29:40'),
(2866, 1, 'http://localhost/lineup_campus/ana-okulu-icerikler', 4, '2025-05-16 09:29:46'),
(2867, 1, 'http://localhost/lineup_campus/ana-okulu-icerikler-detay.php?id=57', 12, '2025-05-16 09:30:00'),
(2868, 32, 'http://localhost/lineup_campus/dashboard', 12, '2025-05-16 10:15:05'),
(2869, 32, 'http://localhost/lineup_campus/sesli-kitap', 16, '2025-05-16 10:15:22'),
(2870, 32, 'http://localhost/lineup_campus/oyun', 48, '2025-05-16 10:16:10'),
(2871, 32, 'http://localhost/lineup_campus/sesli-kitap', 202, '2025-05-16 10:19:33'),
(2872, 32, 'http://localhost/lineup_campus/dashboard', 2, '2025-05-16 10:22:04'),
(2873, 32, 'http://localhost/lineup_campus/oyun', 2, '2025-05-16 10:22:07'),
(2874, 32, 'http://localhost/lineup_campus/sesli-kitap', 114, '2025-05-16 10:24:01'),
(2875, 32, 'http://localhost/lineup_campus/sesli-kitap', 5, '2025-05-16 10:24:07'),
(2876, 1, 'http://localhost/lineup_campus/dashboard', 3, '2025-05-16 10:38:30'),
(2877, 32, 'http://localhost/lineup_campus/dashboard', 2, '2025-05-16 10:38:41'),
(2878, 1, 'http://localhost/lineup_campus/dashboard', 6, '2025-05-16 16:54:48'),
(2879, 1, 'http://localhost/lineup_campus/veliler', 34, '2025-05-16 16:55:23'),
(2880, 1, 'http://localhost/lineup_campus/veliler', 1527, '2025-05-16 17:20:52'),
(2881, 1, 'http://localhost/lineup_campus/paketler', 139, '2025-05-16 17:23:18'),
(2882, 1, 'http://localhost/lineup_campus/paket-detay?id=5', 11, '2025-05-16 17:23:30'),
(2883, 1, 'http://localhost/lineup_campus/paket-detay?id=6', 2246, '2025-05-16 17:58:19'),
(2884, 32, 'http://localhost/lineup_campus/dashboard', 36, '2025-05-16 17:59:42'),
(2885, 32, 'http://localhost/lineup_campus/duyurular', 3, '2025-05-16 17:59:46'),
(2886, 32, 'http://localhost/lineup_campus/duyurular', 3, '2025-05-16 17:59:53'),
(2887, 32, 'http://localhost/lineup_campus/duyurular', 5, '2025-05-16 18:00:06'),
(2888, 32, 'http://localhost/lineup_campus/dashboard', 3, '2025-05-16 18:00:10'),
(2889, 1, 'http://localhost/lineup_campus/paketler', 2221, '2025-05-16 18:00:32'),
(2890, 1, 'http://localhost/lineup_campus/kullanici-yetkilendirme', 1, '2025-05-16 18:00:35'),
(2891, 1, 'http://localhost/lineup_campus/kullanici-gruplari', 13, '2025-05-16 18:00:49'),
(2892, 1, 'http://localhost/lineup_campus/duyurular', 5, '2025-05-16 18:00:55'),
(2893, 1, 'http://localhost/lineup_campus/dashboard', 2, '2025-05-16 18:00:58'),
(2894, 1, 'http://localhost/lineup_campus/okullar', 1, '2025-05-16 18:01:00'),
(2895, 1, 'http://localhost/lineup_campus/okul-detay/lineup-campus', 2, '2025-05-16 19:48:25'),
(2896, 32, 'http://localhost/lineup_campus/dashboard', 10380, '2025-05-16 20:53:10'),
(2897, 1, 'http://localhost/lineup_campus/dashboard', 5, '2025-05-17 20:17:11'),
(2898, 1, 'http://localhost/lineup_campus/ogrenciler', 402, '2025-05-17 20:23:55'),
(2899, 1, 'http://localhost/lineup_campus/ogrenciler', 1, '2025-05-17 20:23:58'),
(2900, 1, 'http://localhost/lineup_campus/ogrenciler', 9, '2025-05-17 20:24:09'),
(2901, 1, 'http://localhost/lineup_campus/ogrenciler', 19, '2025-05-17 20:27:52'),
(2902, 1, 'http://localhost/lineup_campus/ogrenciler', 16, '2025-05-17 20:28:09'),
(2903, 1, 'http://localhost/lineup_campus/ogrenciler', 34, '2025-05-17 20:28:46'),
(2904, 1, 'http://localhost/lineup_campus/ogrenciler', 243, '2025-05-17 20:32:50'),
(2905, 1, 'http://localhost/lineup_campus/ogrenciler', 119, '2025-05-17 20:34:51'),
(2906, 1, 'http://localhost/lineup_campus/ogrenciler', 3, '2025-05-17 20:35:08'),
(2907, 1, 'http://localhost/lineup_campus/ogrenciler', 161, '2025-05-17 20:37:52'),
(2908, 1, 'http://localhost/lineup_campus/havale-beklenenler', 949, '2025-05-17 20:53:54'),
(2909, 1, 'http://localhost/lineup_campus/havale-beklenenler', 96, '2025-05-17 20:57:39'),
(2910, 1, 'http://localhost/lineup_campus/havale-beklenenler', 265, '2025-05-17 21:02:05'),
(2911, 1, 'http://localhost/lineup_campus/havale-beklenenler', 249, '2025-05-17 21:06:16'),
(2912, 1, 'http://localhost/lineup_campus/havale-beklenenler', 24, '2025-05-17 21:06:42'),
(2913, 1, 'http://localhost/lineup_campus/havale-beklenenler', 180, '2025-05-17 21:09:44'),
(2914, 1, 'http://localhost/lineup_campus/havale-beklenenler', 42, '2025-05-17 21:10:38'),
(2915, 1, 'http://localhost/lineup_campus/havale-beklenenler', 127, '2025-05-17 21:12:47'),
(2916, 1, 'http://localhost/lineup_campus/havale-beklenenler', 24, '2025-05-17 21:13:14'),
(2917, 1, 'http://localhost/lineup_campus/havale-beklenenler', 29, '2025-05-17 21:13:44'),
(2918, 1, 'http://localhost/lineup_campus/havale-beklenenler', 14, '2025-05-17 21:14:00'),
(2919, 1, 'http://localhost/lineup_campus/havale-beklenenler', 44, '2025-05-17 21:14:45'),
(2920, 1, 'http://localhost/lineup_campus/okullar', 1, '2025-05-17 21:15:56'),
(2921, 1, 'http://localhost/lineup_campus/havale-beklenenler', 96, '2025-05-17 21:16:23'),
(2922, 1, 'http://localhost/lineup_campus/havale-beklenenler', 35, '2025-05-17 21:17:00'),
(2923, 1, 'http://localhost/lineup_campus/havale-beklenenler', 3, '2025-05-17 21:17:05'),
(2924, 1, 'http://localhost/lineup_campus/havale-beklenenler', 8, '2025-05-17 21:17:17'),
(2925, 1, 'http://localhost/lineup_campus/havale-beklenenler', 151, '2025-05-17 21:19:50'),
(2926, 1, 'http://localhost/lineup_campus/havale-beklenenler', 31, '2025-05-17 21:20:22'),
(2927, 1, 'http://localhost/lineup_campus/havale-beklenenler', 1, '2025-05-17 21:20:25'),
(2928, 1, 'http://localhost/lineup_campus/ogrenciler', 1, '2025-05-17 21:20:28'),
(2929, 1, 'http://localhost/lineup_campus/ogrenciler', 664, '2025-05-17 21:26:59'),
(2930, 1, 'http://localhost/lineup_campus/duyurular', 1, '2025-05-17 21:27:02'),
(2931, 1, 'http://localhost/lineup_campus/bildirimler', 3, '2025-05-17 21:27:07'),
(2932, 1, 'http://localhost/lineup_campus/testler', 10, '2025-05-17 21:27:19'),
(2933, 1, 'http://localhost/lineup_campus/havale-beklenenler', 533, '2025-05-17 21:29:23'),
(2934, 1, 'http://localhost/lineup_campus/havale-beklenenler', 23, '2025-05-17 21:29:48'),
(2935, 1, 'http://localhost/lineup_campus/havale-beklenenler', 293, '2025-05-17 21:34:43'),
(2936, 1, 'http://localhost/lineup_campus/havale-beklenenler', 210, '2025-05-17 21:38:15'),
(2937, 1, 'http://localhost/lineup_campus/veliler', 37, '2025-05-17 21:41:11'),
(2938, 1, 'http://localhost/lineup_campus/havale-beklenenler', 175, '2025-05-17 21:41:12'),
(2939, 1, 'http://localhost/lineup_campus/havale-beklenenler', 2339, '2025-05-17 22:20:13'),
(2940, 1, 'http://localhost/lineup_campus/havale-beklenenler', 80, '2025-05-17 22:21:35'),
(2941, 1, 'http://localhost/lineup_campus/havale-beklenenler', 52, '2025-05-17 22:22:34'),
(2942, 1, 'http://localhost/lineup_campus/havale-beklenenler', 277, '2025-05-17 22:27:13'),
(2943, 1, 'http://localhost/lineup_campus/havale-beklenenler', 159, '2025-05-17 22:29:53'),
(2944, 1, 'http://localhost/lineup_campus/havale-beklenenler', 640, '2025-05-17 22:32:17'),
(2945, 1, 'http://localhost/lineup_campus/havale-beklenenler', 320, '2025-05-17 22:35:16'),
(2946, 1, 'http://localhost/lineup_campus/havale-beklenenler', 5, '2025-05-17 22:35:23'),
(2947, 1, 'http://localhost/lineup_campus/havale-beklenenler', 86, '2025-05-17 22:36:57'),
(2948, 1, 'http://localhost/lineup_campus/havale-beklenenler', 5, '2025-05-17 22:37:04'),
(2949, 1, 'http://localhost/lineup_campus/havale-beklenenler.php', 5, '2025-05-17 22:37:11'),
(2950, 1, 'http://localhost/lineup_campus/aktif-destek-talepleri', 4693, '2025-05-17 22:45:34'),
(2951, 1, 'http://localhost/lineup_campus/okullar', 1, '2025-05-17 22:45:38'),
(2952, 1, 'http://localhost/lineup_campus/okul-detay/lineup-campus', 1, '2025-05-17 22:45:41'),
(2953, 1, 'http://localhost/lineup_campus/ogrenciler', 4, '2025-05-18 10:28:42'),
(2954, 1, 'http://localhost/lineup_campus/paketler', 76, '2025-05-18 10:30:00'),
(2955, 1, 'http://localhost/lineup_campus/okullar', 42356, '2025-05-18 10:31:38'),
(2956, 1, 'http://localhost/lineup_campus/dashboard', 3, '2025-05-18 11:48:09'),
(2957, 1, 'http://localhost/lineup_campus/ogrenciler', 1, '2025-05-18 11:48:11'),
(2958, 1, 'http://localhost/lineup_campus/havale-beklenenler', 1845, '2025-05-18 12:18:58'),
(2959, 1, 'http://localhost/lineup_campus/havale-beklenenler', 10, '2025-05-18 12:19:10'),
(2960, 1, 'http://localhost/lineup_campus/havale-beklenenler', 201, '2025-05-18 12:22:34'),
(2961, 1, 'http://localhost/lineup_campus/havale-beklenenler', 29, '2025-05-18 12:23:05'),
(2962, 1, 'http://localhost/lineup_campus/havale-beklenenler', 0, '2025-05-18 12:23:08'),
(2963, 1, 'http://localhost/lineup_campus/havale-beklenenler', 12, '2025-05-18 12:23:21'),
(2964, 1, 'http://localhost/lineup_campus/havale-beklenenler', 8, '2025-05-18 12:23:32'),
(2965, 1, 'http://localhost/lineup_campus/havale-beklenenler.php', 92, '2025-05-18 12:25:06'),
(2966, 103, 'http://localhost/lineup_campus/dashboard', 2, '2025-05-18 12:27:55'),
(2967, 1, 'http://localhost/lineup_campus/dashboard', 2, '2025-05-18 12:28:12'),
(2968, 1, 'http://localhost/lineup_campus/veliler', 119, '2025-05-18 12:30:13'),
(2969, 1, 'http://localhost/lineup_campus/veliler', 2, '2025-05-18 12:30:16'),
(2970, 1, 'http://localhost/lineup_campus/veliler', 3, '2025-05-18 12:30:18'),
(2971, 1, 'http://localhost/lineup_campus/veliler', 4, '2025-05-18 12:30:19'),
(2972, 1, 'http://localhost/lineup_campus/ogrenciler', 0, '2025-05-18 12:30:21'),
(2973, 1, 'http://localhost/lineup_campus/ogrenciler', 1, '2025-05-18 12:30:24'),
(2974, 1, 'http://localhost/lineup_campus/havale-beklenenler', 8, '2025-05-18 12:30:33'),
(2975, 1, 'http://localhost/lineup_campus/havale-beklenenler.php', 47, '2025-05-18 12:31:22'),
(2976, 102, 'http://localhost/lineup_campus/dashboard', 2, '2025-05-18 12:31:44'),
(2977, 103, 'http://localhost/lineup_campus/dashboard', 3, '2025-05-18 12:32:00'),
(2978, 1, 'http://localhost/lineup_campus/dashboard', 2, '2025-05-18 13:11:21'),
(2979, 1, 'http://localhost/lineup_campus/ogrenciler', 4, '2025-05-18 13:11:26'),
(2980, 1, 'http://localhost/lineup_campus/dashboard', 5, '2025-05-18 13:44:35'),
(2981, 1, 'http://localhost/lineup_campus/ogrenciler', 3, '2025-05-18 13:44:41'),
(2982, 1, 'http://localhost/lineup_campus/havale-beklenenler', 34, '2025-05-18 13:45:17'),
(2983, 1, 'http://localhost/lineup_campus/havale-beklenenler.php', 51, '2025-05-18 13:46:09'),
(2984, 1, 'http://localhost/lineup_campus/havale-beklenenler.php', 7, '2025-05-18 13:46:18'),
(2985, 1, 'http://localhost/lineup_campus/havale-beklenenler.php', 4, '2025-05-18 14:04:43'),
(2986, 1, 'http://localhost/lineup_campus/okullar', 161, '2025-05-18 14:09:00'),
(2987, 1, 'http://localhost/lineup_campus/okullar', 9, '2025-05-18 14:09:12'),
(2988, 1, 'http://localhost/lineup_campus/okullar', 51, '2025-05-18 14:10:04'),
(2989, 1, 'http://localhost/lineup_campus/okullar', 91, '2025-05-18 14:11:37'),
(2990, 1, 'http://localhost/lineup_campus/okullar', 46, '2025-05-18 14:12:25'),
(2991, 1, 'http://localhost/lineup_campus/okullar', 8, '2025-05-18 14:12:36'),
(2992, 1, 'http://localhost/lineup_campus/okullar', 15, '2025-05-18 14:12:52'),
(2993, 1, 'http://localhost/lineup_campus/okullar', 30, '2025-05-18 14:13:24'),
(2994, 1, 'http://localhost/lineup_campus/okullar', 135, '2025-05-18 14:15:43'),
(2995, 1, 'http://localhost/lineup_campus/okullar', 31, '2025-05-18 14:16:17'),
(2996, 1, 'http://localhost/lineup_campus/okullar', 1010, '2025-05-18 14:21:33'),
(2997, 1, 'http://localhost/lineup_campus/okullar', 200, '2025-05-18 14:24:56'),
(2998, 1, 'http://localhost/lineup_campus/okullar', 16, '2025-05-18 14:25:16'),
(2999, 1, 'http://localhost/lineup_campus/okullar', 8, '2025-05-18 14:25:25'),
(3000, 1, 'http://localhost/lineup_campus/okullar', 11, '2025-05-18 14:25:39'),
(3001, 1, 'http://localhost/lineup_campus/okullar', 8, '2025-05-18 14:25:48'),
(3002, 1, 'http://localhost/lineup_campus/okullar', 19, '2025-05-18 14:26:09'),
(3003, 1, 'http://localhost/lineup_campus/okullar', 165, '2025-05-18 14:28:56'),
(3004, 1, 'http://localhost/lineup_campus/okullar', 2, '2025-05-18 14:28:59'),
(3005, 1, 'http://localhost/lineup_campus/okullar', 28, '2025-05-18 14:29:28'),
(3006, 1, 'http://localhost/lineup_campus/dashboard', 2, '2025-05-18 14:29:38'),
(3007, 1, 'http://localhost/lineup_campus/okullar', 88, '2025-05-18 14:31:07'),
(3008, 1, 'http://localhost/lineup_campus/okullar', 208, '2025-05-18 14:34:37'),
(3009, 1, 'http://localhost/lineup_campus/havale-beklenenler', 57755, '2025-05-18 14:34:54'),
(3010, 1, 'http://localhost/lineup_campus/veliler', 176, '2025-05-18 14:37:52'),
(3011, 1, 'http://localhost/lineup_campus/veliler', 56, '2025-05-18 14:38:50'),
(3012, 1, 'http://localhost/lineup_campus/veliler', 24, '2025-05-18 14:39:16'),
(3013, 1, 'http://localhost/lineup_campus/veliler', 149, '2025-05-18 14:41:47'),
(3014, 1, 'http://localhost/lineup_campus/veliler', 42, '2025-05-18 14:42:30'),
(3015, 1, 'http://localhost/lineup_campus/veliler', 5, '2025-05-18 14:42:37'),
(3016, 1, 'http://localhost/lineup_campus/veliler', 21, '2025-05-18 14:43:00'),
(3017, 1, 'http://localhost/lineup_campus/okullar', 41, '2025-05-18 15:25:48'),
(3018, 1, 'http://localhost/lineup_campus/ogrenciler', 3690, '2025-05-18 15:36:09'),
(3019, 1, 'http://localhost/lineup_campus/veliler', 3354, '2025-05-18 15:38:56'),
(3020, 1, 'http://localhost/lineup_campus/veliler', 22, '2025-05-18 15:39:20'),
(3021, 1, 'http://localhost/lineup_campus/veliler', 31, '2025-05-18 15:39:53'),
(3022, 1, 'http://localhost/lineup_campus/veliler', 20, '2025-05-18 15:40:15'),
(3023, 1, 'http://localhost/lineup_campus/veliler', 23, '2025-05-18 15:40:39'),
(3024, 1, 'http://localhost/lineup_campus/veliler', 4, '2025-05-18 15:40:45'),
(3025, 1, 'http://localhost/lineup_campus/veliler', 1159, '2025-05-18 16:00:05'),
(3026, 1, 'http://localhost/lineup_campus/veliler', 41, '2025-05-18 16:00:48'),
(3027, 1, 'http://localhost/lineup_campus/dashboard', 1, '2025-05-18 17:15:29'),
(3028, 1, 'http://localhost/lineup_campus/veliler', 6034, '2025-05-18 17:16:46'),
(3029, 1, 'http://localhost/lineup_campus/apps/customers/list.html', 1, '2025-05-19 08:36:15'),
(3030, 1, 'http://localhost/lineup_campus/hesap-olustur', 0, '2025-05-19 08:37:45'),
(3031, 1, 'https://localhost/lineup_campus/authentication/sign-in/new-password.html?token=5ea0f58debf76e8546727168bc1ac9a81b328ac87b5fcdb9a', 2, '2025-05-19 08:38:50'),
(3032, 1, 'https://localhost/lineup_campus/authentication/sign-in/new-password.html?token=5ea0f58debf76e8546727168bc1ac9a81b328ac87b5fcdb9a', 2, '2025-05-19 08:39:00'),
(3033, 1, 'https://localhost/lineup_campus/authentication/sign-in/new-password.html?token=5ea0f58debf76e8546727168bc1ac9a81b328ac87b5fcdb9a', 1, '2025-05-19 08:39:02'),
(3034, 1, 'http://localhost/lineup_campus/dashboard', 1, '2025-05-19 08:39:26'),
(3035, 1, 'http://localhost/lineup_campus/paket-detay?id=6', 1, '2025-05-19 08:39:29'),
(3036, 1, 'http://localhost/lineup_campus/dashboard', 3, '2025-05-19 10:29:42'),
(3037, 1, 'http://localhost/lineup_campus/okullar', 285, '2025-05-19 10:34:29'),
(3038, 1, 'http://localhost/lineup_campus/okullar', 45, '2025-05-19 10:35:16'),
(3039, 1, 'http://localhost/lineup_campus/okullar', 23, '2025-05-19 10:35:42'),
(3040, 1, 'http://localhost/lineup_campus/okullar', 1098, '2025-05-19 10:54:00'),
(3041, 1, 'http://localhost/lineup_campus/okullar', 154, '2025-05-19 10:56:38'),
(3042, 1, 'http://localhost/lineup_campus/okullar', 12, '2025-05-19 10:56:52'),
(3043, 1, 'http://localhost/lineup_campus/okullar', 382, '2025-05-19 11:03:14'),
(3044, 1, 'http://localhost/lineup_campus/hesap-olustur', 85585, '2025-05-19 12:21:43'),
(3045, 1, 'http://localhost/lineup_campus/okullar', 4713, '2025-05-19 12:21:49'),
(3046, 1, 'http://localhost/lineup_campus/okullar', 418, '2025-05-19 12:28:49'),
(3047, 1, 'http://localhost/lineup_campus/okullar', 221, '2025-05-19 12:32:32'),
(3048, 1, 'http://localhost/lineup_campus/okullar', 102, '2025-05-19 12:34:16'),
(3049, 1, 'http://localhost/lineup_campus/okullar', 44, '2025-05-19 12:35:02'),
(3050, 1, 'http://localhost/lineup_campus/okullar', 44, '2025-05-19 12:35:48'),
(3051, 1, 'http://localhost/lineup_campus/ogrenciler', 69724, '2025-05-19 12:38:52'),
(3052, 1, 'http://localhost/lineup_campus/okul-detay/ankara-yeni-okul2', 2370, '2025-05-19 13:15:20'),
(3053, 1, 'http://localhost/lineup_campus/okul-detay/ankara-yeni-okul2', 34, '2025-05-19 13:15:57'),
(3054, 1, 'http://localhost/lineup_campus/okul-detay/ankara-yeni-okul2', 87, '2025-05-19 13:17:26'),
(3055, 1, 'http://localhost/lineup_campus/okul-detay/ankara-yeni-okul2', 47, '2025-05-19 13:18:15'),
(3056, 1, 'http://localhost/lineup_campus/okul-detay/ankara-yeni-okul2', 185, '2025-05-19 13:21:21'),
(3057, 1, 'http://localhost/lineup_campus/okul-detay/ankara-yeni-okul2', 10, '2025-05-19 13:21:53'),
(3058, 1, 'http://localhost/lineup_campus/okullar', 3, '2025-05-19 13:21:58'),
(3059, 1, 'http://localhost/lineup_campus/okul-detay/ankara-yeni-okul2', 12, '2025-05-19 13:22:13'),
(3060, 1, 'http://localhost/lineup_campus/okul-detay/ankara-yeni-okul2', 18, '2025-05-19 13:22:32'),
(3061, 1, 'http://localhost/lineup_campus/okul-detay/ankara-yeni-okul2', 72, '2025-05-19 13:23:45'),
(3062, 1, 'http://localhost/lineup_campus/okul-detay/ankara-yeni-okul2', 22, '2025-05-19 13:24:09'),
(3063, 1, 'http://localhost/lineup_campus/okul-detay/ankara-yeni-okul2', 383, '2025-05-19 13:30:34'),
(3064, 1, 'http://localhost/lineup_campus/okul-detay/ankara-yeni-okul2', 70, '2025-05-19 13:31:46'),
(3065, 1, 'http://localhost/lineup_campus/okul-detay/ankara-yeni-okul2', 117, '2025-05-19 13:33:45'),
(3066, 1, 'http://localhost/lineup_campus/okul-detay/ankara-yeni-okul2', 17, '2025-05-19 13:34:05'),
(3067, 1, 'http://localhost/lineup_campus/okul-detay/ankara-yeni-okul2', 119, '2025-05-19 13:36:04'),
(3068, 1, 'http://localhost/lineup_campus/okul-detay/ankara-yeni-okul2', 578, '2025-05-19 13:45:45'),
(3069, 1, 'http://localhost/lineup_campus/okul-detay/ankara-yeni-okul2', 4, '2025-05-19 13:45:51'),
(3070, 1, 'http://localhost/lineup_campus/okullar', 1, '2025-05-19 13:45:54'),
(3071, 1, 'http://localhost/lineup_campus/okul-detay/lineup-campus', 36, '2025-05-19 13:46:31'),
(3072, 1, 'http://localhost/lineup_campus/okul-detay/lineup-campus', 216, '2025-05-19 13:50:09'),
(3073, 1, 'http://localhost/lineup_campus/dashboard', 2, '2025-05-19 13:50:12'),
(3074, 1, 'http://localhost/lineup_campus/okul-detay/lineup-campus', 92, '2025-05-19 13:51:46'),
(3075, 1, 'http://localhost/lineup_campus/okul-detay/lineup-campus', 2, '2025-05-19 13:51:50'),
(3076, 1, 'http://localhost/lineup_campus/okul-detay/lineup-campus', 18, '2025-05-19 13:52:10'),
(3077, 1, 'http://localhost/lineup_campus/okul-detay/lineup-campus', 20, '2025-05-19 13:52:32'),
(3078, 1, 'http://localhost/lineup_campus/ogretmenler', 2, '2025-05-19 13:52:35'),
(3079, 1, 'http://localhost/lineup_campus/okul-detay/lineup-campus', 3, '2025-05-19 13:52:40'),
(3080, 1, 'http://localhost/lineup_campus/okullar', 1, '2025-05-19 13:52:43'),
(3081, 1, 'http://localhost/lineup_campus/okul-detay/ankara-deneme-okulu', 84, '2025-05-19 13:54:09'),
(3082, 1, 'http://localhost/lineup_campus/okul-detay/ankara-deneme-okulu', 60, '2025-05-19 13:55:11'),
(3083, 1, 'http://localhost/lineup_campus/okul-detay/ankara-deneme-okulu', 102, '2025-05-19 13:56:54'),
(3084, 1, 'http://localhost/lineup_campus/okul-detay/ankara-deneme-okulu', 177, '2025-05-19 13:59:53'),
(3085, 1, 'http://localhost/lineup_campus/dashboard', 1, '2025-05-19 13:59:55'),
(3086, 1, 'http://localhost/lineup_campus/okul-detay/ankara-deneme-okulu', 0, '2025-05-19 13:59:58'),
(3087, 1, 'http://localhost/lineup_campus/okullar', 0, '2025-05-19 13:59:59'),
(3088, 1, 'http://localhost/lineup_campus/okul-detay/ankara-deneme-okulu', 3, '2025-05-19 14:00:04'),
(3089, 1, 'http://localhost/lineup_campus/okul-detay/ankara-deneme-okulu', 67, '2025-05-19 14:01:13'),
(3090, 1, 'http://localhost/lineup_campus/okul-detay/ankara-deneme-okulu', 38, '2025-05-19 14:01:54'),
(3091, 1, 'http://localhost/lineup_campus/okul-detay/ankara-deneme-okulu', 23, '2025-05-19 14:02:20'),
(3092, 1, 'http://localhost/lineup_campus/veliler', 79350, '2025-05-19 14:03:20'),
(3093, 1, 'http://localhost/lineup_campus/okul-detay/ankara-deneme-okulu', 83, '2025-05-19 14:03:43'),
(3094, 1, 'http://localhost/lineup_campus/okul-detay/ankara-deneme-okulu', 137, '2025-05-19 14:06:02'),
(3095, 1, 'http://localhost/lineup_campus/veliler', 237, '2025-05-19 14:07:19'),
(3096, 1, 'http://localhost/lineup_campus/okul-detay/ankara-deneme-okulu', 334, '2025-05-19 14:11:37'),
(3097, 1, 'http://localhost/lineup_campus/okul-detay/ankara-deneme-okulu', 25, '2025-05-19 14:12:04'),
(3098, 1, 'http://localhost/lineup_campus/okul-detay/ankara-deneme-okulu', 41, '2025-05-19 14:12:48'),
(3099, 1, 'http://localhost/lineup_campus/okul-detay/ankara-deneme-okulu', 12, '2025-05-19 14:13:01'),
(3100, 1, 'http://localhost/lineup_campus/ogrenciler', 435, '2025-05-19 14:14:36'),
(3101, 1, 'http://localhost/lineup_campus/ogrenciler', 2, '2025-05-19 14:14:41'),
(3102, 1, 'http://localhost/lineup_campus/ogrenci-detay2', 761, '2025-05-19 14:48:31'),
(3103, 1, 'http://localhost/lineup_campus/ogrenci-detay2/ogrenci1', 56, '2025-05-19 14:49:57'),
(3104, 1, 'http://localhost/lineup_campus/ogrenci-detay2/ogrenci1', 16, '2025-05-19 14:50:14'),
(3105, 1, 'http://localhost/lineup_campus/ogrenci-detay2/ogrenci1', 39, '2025-05-19 14:50:55'),
(3106, 1, 'http://localhost/lineup_campus/ogrenci-detay2/ogrenci1', 4, '2025-05-19 14:51:01'),
(3107, 1, 'http://localhost/lineup_campus/ogrenci-detay2/ogrenci1', 1033, '2025-05-19 15:08:16'),
(3108, 1, 'http://localhost/lineup_campus/ogrenci-detay2/ogrenci1', 59, '2025-05-19 15:09:34'),
(3109, 1, 'http://localhost/lineup_campus/ogrenci-detay2/ogrenci1', 26, '2025-05-19 15:10:02'),
(3110, 1, 'http://localhost/lineup_campus/ogrenci-detay2/ogrenci1', 58, '2025-05-19 15:11:02'),
(3111, 1, 'http://localhost/lineup_campus/ogrenci-detay2/ogrenci1', 20, '2025-05-19 15:11:24'),
(3112, 1, 'http://localhost/lineup_campus/ogrenci-detay2/ogrenci1', 14, '2025-05-19 15:11:40'),
(3113, 1, 'http://localhost/lineup_campus/ogrenci-detay2/ogrenci1', 6, '2025-05-19 15:11:48'),
(3114, 1, 'http://localhost/lineup_campus/pages/user-profile/projects.html', 2, '2025-05-19 15:11:50'),
(3115, 1, 'http://localhost/lineup_campus/account/overview.html', 1, '2025-05-19 15:12:12'),
(3116, 1, 'http://localhost/lineup_campus/account/settings.html', 1, '2025-05-19 15:12:13'),
(3117, 1, 'http://localhost/lineup_campus/pages/user-profile/overview.html', 46, '2025-05-19 15:12:37'),
(3118, 1, 'http://localhost/lineup_campus/pages/user-profile/projects.html', 0, '2025-05-19 15:12:38'),
(3119, 1, 'http://localhost/lineup_campus/account/overview.html', 55, '2025-05-19 15:13:09'),
(3120, 1, 'http://localhost/lineup_campus/apps/customers/list.html', 1, '2025-05-19 15:13:11'),
(3121, 1, 'http://localhost/lineup_campus/apps/customers/view.html', 11, '2025-05-19 15:13:22'),
(3122, 1, 'http://localhost/lineup_campus/pages/user-profile/overview.html', 1, '2025-05-19 15:13:24'),
(3123, 1, 'http://localhost/lineup_campus/pages/user-profile/projects.html', 1, '2025-05-19 15:13:26'),
(3124, 1, 'http://localhost/lineup_campus/ogrenci-detay2/ogrenci1', 94, '2025-05-19 15:14:14'),
(3125, 1, 'http://localhost/lineup_campus/ogrenci-detay2/denenne', 343, '2025-05-19 15:19:59'),
(3126, 1, 'http://localhost/lineup_campus/ogrenciler', 1935, '2025-05-19 15:20:43'),
(3127, 1, 'http://localhost/lineup_campus/dashboard', 2, '2025-05-19 15:20:47'),
(3128, 1, 'http://localhost/lineup_campus/ogrenci-detay2/denenne', 194, '2025-05-19 15:23:14'),
(3129, 1, 'http://localhost/lineup_campus/ogrenci-detay2/denenne', 45, '2025-05-19 15:24:01'),
(3130, 1, 'http://localhost/lineup_campus/ogrenci-detay2/denenne', 5, '2025-05-19 15:24:07'),
(3131, 1, 'http://localhost/lineup_campus/ogrenci-detay2/denenne', 46, '2025-05-19 15:24:55'),
(3132, 1, 'http://localhost/lineup_campus/ogrenci-detay2/denenne', 288, '2025-05-19 15:29:45'),
(3133, 1, 'http://localhost/lineup_campus/ogrenci-detay2/denenne', 29, '2025-05-19 15:30:16'),
(3134, 1, 'http://localhost/lineup_campus/ogrenci-detay2/ogrenci1', 1, '2025-05-19 15:30:19'),
(3135, 1, 'http://localhost/lineup_campus/ogrenci-detay2/denenne', 0, '2025-05-19 15:30:21'),
(3136, 1, 'http://localhost/lineup_campus/ogrenci-detay2/ogrenci1', 42, '2025-05-19 15:31:05'),
(3137, 1, 'http://localhost/lineup_campus/ogrenci-detay2/ogrenci1', 11, '2025-05-19 15:31:18'),
(3138, 1, 'http://localhost/lineup_campus/ogrenci-detay2/ogrenci1', 39, '2025-05-19 15:31:59'),
(3139, 1, 'http://localhost/lineup_campus/ogrenci-detay2/ogrenci1', 40, '2025-05-19 15:32:00'),
(3140, 1, 'http://localhost/lineup_campus/ogrenci-detay2/ogrenci1', 40, '2025-05-19 15:32:01'),
(3141, 1, 'http://localhost/lineup_campus/dashboard', 0, '2025-05-19 15:32:03'),
(3142, 1, 'http://localhost/lineup_campus/ogrenci-detay2/ogrenci1', 269, '2025-05-19 15:36:33'),
(3143, 1, 'http://localhost/lineup_campus/ogrenci-detay2/ogrenci1', 10, '2025-05-19 15:36:45'),
(3144, 1, 'http://localhost/lineup_campus/ogrenci-detay2/ogrenci1', 117, '2025-05-19 15:38:44'),
(3145, 1, 'http://localhost/lineup_campus/ogrenci-detay2/ogrenci1', 94, '2025-05-19 15:40:20'),
(3146, 1, 'http://localhost/lineup_campus/ogrenci-detay2/ogrenci1', 48, '2025-05-19 15:41:10'),
(3147, 1, 'http://localhost/lineup_campus/ogrenci-detay2/ogrenci1', 29, '2025-05-19 15:41:41'),
(3148, 1, 'http://localhost/lineup_campus/ogrenci-detay2/ogrenci1', 59, '2025-05-19 15:42:41'),
(3149, 1, 'http://localhost/lineup_campus/ogrenci-detay2/ogrenci1', 170, '2025-05-19 15:45:33'),
(3150, 1, 'http://localhost/lineup_campus/ogrenci-detay2/ogrenci1', 16, '2025-05-19 15:45:52'),
(3151, 1, 'http://localhost/lineup_campus/ogrenci-detay2/ogrenci1', 23, '2025-05-19 15:46:16'),
(3152, 1, 'http://localhost/lineup_campus/ogrenci-detay2/ogrenci1', 44, '2025-05-19 15:47:03'),
(3153, 1, 'http://localhost/lineup_campus/dashboard', 1, '2025-05-19 15:47:05'),
(3154, 1, 'http://localhost/lineup_campus/ogrenci-detay2/ogrenci1', 1, '2025-05-19 15:47:07'),
(3155, 1, 'http://localhost/lineup_campus/ogrenci-detay2/ogrenci1', 18, '2025-05-19 15:47:28'),
(3156, 1, 'http://localhost/lineup_campus/ogrenci-detay2/ogrenci1', 45, '2025-05-19 15:48:15'),
(3157, 1, 'http://localhost/lineup_campus/ogrenci-detay2/ogrenci1', 9, '2025-05-19 15:48:25'),
(3158, 1, 'http://localhost/lineup_campus/ogrenci-detay2/ogrenci1', 392, '2025-05-19 15:54:58'),
(3159, 1, 'http://localhost/lineup_campus/ogrenci-detay2/ogrenci1', 38, '2025-05-19 15:55:38'),
(3160, 1, 'http://localhost/lineup_campus/ogrenci-detay2/ogrenci1', 344, '2025-05-19 16:01:26'),
(3161, 1, 'http://localhost/lineup_campus/ogrenci-detay2/ogrenci1', 134, '2025-05-19 16:03:41'),
(3162, 1, 'http://localhost/lineup_campus/ogrenci-detay2/ogrenci1', 14, '2025-05-19 16:03:57'),
(3163, 1, 'http://localhost/lineup_campus/ogrenci-detay2/ogrenci1', 324, '2025-05-19 16:09:23'),
(3164, 1, 'http://localhost/lineup_campus/ogrenci-detay2/ogrenci1', 1, '2025-05-19 16:10:34'),
(3165, 1, 'http://localhost/lineup_campus/ogrenci-detay2/wisufejig', 11, '2025-05-19 16:10:47'),
(3166, 1, 'http://localhost/lineup_campus/ogrenci-detay2/ogrenci1', 484, '2025-05-19 16:18:21'),
(3167, 1, 'http://localhost/lineup_campus/ogrenci-detay2/ogrenci1', 11, '2025-05-19 16:18:34'),
(3168, 1, 'http://localhost/lineup_campus/okullar', 4, '2025-05-19 16:34:30'),
(3169, 1, 'http://localhost/lineup_campus/okul-detay/lineup-campus', 185, '2025-05-19 16:37:37'),
(3170, 1, 'http://localhost/lineup_campus/okullar', 1, '2025-05-19 16:37:39'),
(3171, 1, 'http://localhost/lineup_campus/okul-detay/ankara-deneme-okulu', 9314, '2025-05-19 16:48:17'),
(3172, 1, 'http://localhost/lineup_campus/okul-detay/ankara-deneme-okulu', 9315, '2025-05-19 16:48:18'),
(3173, 1, 'http://localhost/lineup_campus/ogrenci-detay2/ogrenci1', 2134, '2025-05-19 16:54:10'),
(3174, 1, 'http://localhost/lineup_campus/ogrenci-detay2/ogrenci1', 83, '2025-05-19 16:55:35'),
(3175, 1, 'http://localhost/lineup_campus/ogrenci-detay2/ogrenci1', 15, '2025-05-19 16:55:52'),
(3176, 1, 'http://localhost/lineup_campus/pages/user-profile/overview.html', 2, '2025-05-19 16:55:54'),
(3177, 1, 'http://localhost/lineup_campus/ogrenci-detay2/ogrenci1', 254, '2025-05-19 17:00:10'),
(3178, 1, 'http://localhost/lineup_campus/ogrenci-detay2/ogrenci1', 2, '2025-05-19 17:00:14'),
(3179, 1, 'http://localhost/lineup_campus/ogrenci-detay2/ogrenci1', 58, '2025-05-19 17:01:13'),
(3180, 1, 'http://localhost/lineup_campus/ogrenci-detay2/ogrenci1', 121, '2025-05-19 17:03:17'),
(3181, 1, 'http://localhost/lineup_campus/ogrenci-detay2/ogrenci1', 4, '2025-05-19 17:03:23'),
(3182, 1, 'http://localhost/lineup_campus/ogrenci-detay2/ogrenci1', 334, '2025-05-19 17:08:59'),
(3183, 1, 'http://localhost/lineup_campus/ogrenci-detay2/ogrenci1', 8, '2025-05-19 17:09:09'),
(3184, 1, 'http://localhost/lineup_campus/ogrenci-detay2/ogrenci1', 20, '2025-05-19 17:09:30'),
(3185, 1, 'http://localhost/lineup_campus/ogrenci-detay2/ogrenci1', 33, '2025-05-19 17:10:05'),
(3186, 1, 'http://localhost/lineup_campus/ogrenci-detay2/ogrenci1', 1, '2025-05-19 17:10:09'),
(3187, 1, 'http://localhost/lineup_campus/ogrenci-detay2/ogrenci1', 2, '2025-05-19 17:10:10'),
(3188, 1, 'http://localhost/lineup_campus/ogrenci-detay2/ogrenci1', 2, '2025-05-19 17:10:10'),
(3189, 1, 'http://localhost/lineup_campus/dashboard', 10, '2025-05-19 17:10:22'),
(3190, 1, 'http://localhost/lineup_campus/ogrenci-detay2/ogrenci1', 0, '2025-05-19 17:10:24'),
(3191, 1, 'http://localhost/lineup_campus/ogrenci-detay2/ogrenci1', 179, '2025-05-19 17:13:26'),
(3192, 1, 'http://localhost/lineup_campus/ogrenci-detay2/ogrenci1', 22, '2025-05-19 17:13:49'),
(3193, 1, 'http://localhost/lineup_campus/ogrenci-detay2/ogrenci1', 17, '2025-05-19 17:14:08'),
(3194, 1, 'http://localhost/lineup_campus/ogrenci-detay2/ogrenci1', 21, '2025-05-19 17:14:30'),
(3195, 1, 'http://localhost/lineup_campus/ogrenci-detay2/ogrenci1', 26, '2025-05-19 17:14:58'),
(3196, 1, 'http://localhost/lineup_campus/ogrenci-detay2/ogrenci1', 299, '2025-05-19 17:20:00'),
(3197, 1, 'http://localhost/lineup_campus/okul-detay/ankara-yeni-okul2', 2906, '2025-05-19 17:26:07'),
(3198, 1, 'http://localhost/lineup_campus/dersler', 1, '2025-05-19 17:26:10'),
(3199, 1, 'http://localhost/lineup_campus/dashboard', 0, '2025-05-19 17:26:12'),
(3200, 1, 'http://localhost/lineup_campus/dashboard', 2901, '2025-05-19 17:36:41'),
(3201, 1, 'http://localhost/lineup_campus/ogrenci-detay2/ogrenci1', 1291, '2025-05-19 17:41:32'),
(3202, 1, 'http://localhost/lineup_campus/ogrenci-detay2/ogrenci1', 5, '2025-05-19 17:41:40'),
(3203, 1, 'http://localhost/lineup_campus/ogrenci-detay2/ogrenci1', 14, '2025-05-19 17:41:55'),
(3204, 1, 'http://localhost/lineup_campus/ogrenci-detay2/ogrenci1', 43, '2025-05-19 17:42:40'),
(3205, 1, 'http://localhost/lineup_campus/ogrenci-detay2/ogrenci1', 37, '2025-05-19 17:43:19'),
(3206, 1, 'http://localhost/lineup_campus/ogrenci-detay2/ogrenci1', 66, '2025-05-19 17:44:27'),
(3207, 1, 'http://localhost/lineup_campus/ogrenci-detay2/ogrenci1', 113, '2025-05-19 17:46:22'),
(3208, 1, 'http://localhost/lineup_campus/ogrenci-detay2/ogrenci1', 75, '2025-05-19 17:47:39'),
(3209, 1, 'http://localhost/lineup_campus/ogrenci-detay2/ogrenci1', 36, '2025-05-19 17:48:17'),
(3210, 1, 'http://localhost/lineup_campus/ogrenci-detay2/ogrenci1', 3, '2025-05-19 17:48:22'),
(3211, 1, 'http://localhost/lineup_campus/ogrenci-detay2/ogrenci1', 3, '2025-05-19 17:48:27'),
(3212, 1, 'http://localhost/lineup_campus/ogrenci-detay2/ogrenci1', 62, '2025-05-19 17:49:31'),
(3213, 1, 'http://localhost/lineup_campus/ogrenci-detay2/ogrenci1', 12, '2025-05-19 17:49:45'),
(3214, 1, 'http://localhost/lineup_campus/ogrenci-detay2/ogrenci1', 268, '2025-05-19 17:54:15'),
(3215, 1, 'http://localhost/lineup_campus/okul-detay/ankara-deneme-okulu', 1060, '2025-05-19 17:54:23'),
(3216, 1, 'http://localhost/lineup_campus/ogrenci-detay2/ogrenci1', 12, '2025-05-19 17:54:28'),
(3217, 1, 'http://localhost/lineup_campus/ogrenci-detay2/ogrenci1', 15, '2025-05-19 17:54:46'),
(3218, 1, 'http://localhost/lineup_campus/ogrenci-detay2/ogrenci1', 4, '2025-05-19 17:54:51'),
(3219, 1, 'http://localhost/lineup_campus/ogrenci-detay2/ogrenci1', 1, '2025-05-19 17:54:54'),
(3220, 1, 'http://localhost/lineup_campus/ogrenci-detay2/ogrenci1', 16, '2025-05-19 17:55:19'),
(3221, 1, 'http://localhost/lineup_campus/ogrenci-detay2/ogrenci1', 222, '2025-05-19 17:59:03'),
(3222, 1, 'http://localhost/lineup_campus/ogrenci-detay2/ogrenci1', 18, '2025-05-19 17:59:22'),
(3223, 1, 'http://localhost/lineup_campus/ogrenci-detay2/ogrenci1', 49, '2025-05-19 18:00:13'),
(3224, 1, 'http://localhost/lineup_campus/ogrenci-detay2/ogrenci1', 64, '2025-05-19 18:01:20'),
(3225, 1, 'http://localhost/lineup_campus/ogrenci-detay2/ogrenci1', 104, '2025-05-19 18:03:05'),
(3226, 1, 'http://localhost/lineup_campus/ogrenci-detay2/ogrenci1', 36, '2025-05-19 18:03:43'),
(3227, 1, 'http://localhost/lineup_campus/ogrenci-detay2/ogrenci1', 38, '2025-05-19 18:04:23'),
(3228, 1, 'http://localhost/lineup_campus/ogrenci-detay2/ogrenci1', 46, '2025-05-19 18:05:11'),
(3229, 1, 'http://localhost/lineup_campus/ogrenci-detay2/ogrenci1', 18, '2025-05-19 18:05:31'),
(3230, 1, 'http://localhost/lineup_campus/ogrenci-detay2/ogrenci1', 15, '2025-05-19 18:05:48'),
(3231, 1, 'http://localhost/lineup_campus/ogrenci-detay2/ogrenci1', 94, '2025-05-19 18:07:24'),
(3232, 1, 'http://localhost/lineup_campus/ogrenci-detay2/ogrenci1', 34, '2025-05-19 18:08:00'),
(3233, 1, 'http://localhost/lineup_campus/ogrenci-detay2/ogrenci1', 1905, '2025-05-19 18:40:13'),
(3234, 1, 'http://localhost/lineup_campus/ogrenci-detay2/ogrenci1', 705, '2025-05-19 18:52:00'),
(3235, 1, 'http://localhost/lineup_campus/ogrenci-detay2/ogrenci1', 10089, '2025-05-19 18:58:58'),
(3236, 1, 'http://localhost/lineup_campus/ogrenci-detay2/ogrenci1', 10110, '2025-05-19 18:59:19'),
(3237, 1, 'http://localhost/lineup_campus/ogretmenler', 3, '2025-05-19 18:59:24'),
(3238, 1, 'http://localhost/lineup_campus/ogretmenler', 2, '2025-05-19 18:59:28'),
(3239, 1, 'http://localhost/lineup_campus/ogretmenler', 15, '2025-05-19 18:59:46'),
(3240, 1, 'http://localhost/lineup_campus/ogrenci-detay2/ogrenci1', 633, '2025-05-19 19:02:36'),
(3241, 1, 'http://localhost/lineup_campus/ogrenci-detay2/ogrenci1', 274, '2025-05-19 19:07:11'),
(3242, 1, 'http://localhost/lineup_campus/ogrenci-detay2/ogrenci1', 197, '2025-05-19 19:10:31'),
(3243, 1, 'http://localhost/lineup_campus/ogrenci-detay2/ogrenci1', 59, '2025-05-19 19:11:31'),
(3244, 1, 'http://localhost/lineup_campus/ogrenci-detay2/ogrenci1', 14, '2025-05-19 19:11:47'),
(3245, 1, 'http://localhost/lineup_campus/ogrenci-detay2/ogrenci1', 14, '2025-05-19 19:12:03'),
(3246, 1, 'http://localhost/lineup_campus/ogrenci-detay2/ogrenci1', 10, '2025-05-19 19:12:15'),
(3247, 1, 'http://localhost/lineup_campus/ogrenci-detay2/ogrenci1', 1290, '2025-05-19 19:33:47'),
(3248, 1, 'http://localhost/lineup_campus/ogrenci-detay2/ogrenci1', 1579, '2025-05-19 20:00:08'),
(3249, 1, 'http://localhost/lineup_campus/ogrenci-detay2/ogrenci1', 129, '2025-05-19 20:02:19'),
(3250, 1, 'http://localhost/lineup_campus/ogrenci-detay2/ogrenci1', 28, '2025-05-19 20:02:49'),
(3251, 1, 'http://localhost/lineup_campus/ogrenci-detay2/ogrenci1', 26, '2025-05-19 20:03:17'),
(3252, 1, 'http://localhost/lineup_campus/ogrenci-detay2/ogrenci1', 32, '2025-05-19 20:03:59'),
(3253, 1, 'http://localhost/lineup_campus/ogrenci-detay2/wisufejig', 56, '2025-05-19 20:04:57'),
(3254, 1, 'http://localhost/lineup_campus/ogrenci-detay2/wisufejig', 91, '2025-05-19 20:06:30'),
(3255, 1, 'http://localhost/lineup_campus/ogrenci-detay2/wisufejig', 132, '2025-05-19 20:08:44'),
(3256, 1, 'http://localhost/lineup_campus/ogrenci-detay2/wisufejig', 34, '2025-05-19 20:09:19'),
(3257, 1, 'http://localhost/lineup_campus/ogrenci-detay2/wisufejig', 72, '2025-05-19 20:10:33'),
(3258, 1, 'http://localhost/lineup_campus/ogrenci-detay2/wisufejig', 86, '2025-05-19 20:12:01'),
(3259, 1, 'http://localhost/lineup_campus/ogrenci-detay2/wisufejig', 140, '2025-05-19 20:14:54'),
(3260, 1, 'http://localhost/lineup_campus/ogrenci-detay2/wisufejig', 3042, '2025-05-19 21:05:38'),
(3261, 1, 'http://localhost/lineup_campus/account/overview.html', 2, '2025-05-19 21:05:41'),
(3262, 1, 'http://localhost/lineup_campus/account/settings.html', 0, '2025-05-19 21:05:42'),
(3263, 1, 'http://localhost/lineup_campus/account/overview.html', 5, '2025-05-19 21:05:48'),
(3264, 1, 'http://localhost/lineup_campus/account/settings.html', 6, '2025-05-19 21:05:48'),
(3265, 1, 'http://localhost/lineup_campus/account/overview.html', 10, '2025-05-19 21:05:49'),
(3266, 1, 'http://localhost/lineup_campus/ogrenci-detay2/ogrenci1', 4014, '2025-05-19 21:10:13'),
(3267, 1, 'http://localhost/lineup_campus/ogrenci-detay2/wisufejig', 3328, '2025-05-19 21:10:24'),
(3268, 1, 'http://localhost/lineup_campus/ogrenci-detay2/wisufejig', 18, '2025-05-19 21:10:44'),
(3269, 1, 'http://localhost/lineup_campus/ogrenci-detay2/wisufejig', 353, '2025-05-19 21:16:38'),
(3270, 1, 'http://localhost/lineup_campus/ogrenci-detay/wisufejig', 24, '2025-05-19 21:17:23'),
(3271, 1, 'http://localhost/lineup_campus/ogrenciler', 3, '2025-05-19 21:17:34'),
(3272, 1, 'http://localhost/lineup_campus/ogrenci-detay/ogrenci1', 0, '2025-05-19 21:17:36'),
(3273, 1, 'http://localhost/lineup_campus/ogrenciler', 1, '2025-05-19 21:17:39'),
(3274, 1, 'http://localhost/lineup_campus/ogrenci-detay/ogrenci1', 41, '2025-05-19 21:18:22'),
(3275, 1, 'http://localhost/lineup_campus/okul-detay/lineup-campus', 52, '2025-05-19 21:19:20'),
(3276, 1, 'http://localhost/lineup_campus/okul-detay/lineup-campus', 44, '2025-05-19 21:20:06'),
(3277, 1, 'http://localhost/lineup_campus/dashboard', 0, '2025-05-19 21:20:07'),
(3278, 1, 'http://localhost/lineup_campus/dashboard', 0, '2025-05-19 21:20:08'),
(3279, 1, 'http://localhost/lineup_campus/okul-detay/lineup-campus', 1, '2025-05-19 21:20:11'),
(3280, 1, 'http://localhost/lineup_campus/okul-detay/lineup-campus', 0, '2025-05-19 21:20:13'),
(3281, 1, 'http://localhost/lineup_campus/okul-detay/lineup-campus', 1, '2025-05-19 21:20:16'),
(3282, 1, 'http://localhost/lineup_campus/dashboard', 1, '2025-05-19 21:20:18'),
(3283, 1, 'http://localhost/lineup_campus/okul-detay/lineup-campus', 72, '2025-05-19 21:21:32'),
(3284, 1, 'http://localhost/lineup_campus/okul-detay/lineup-campus', 3, '2025-05-19 21:21:37'),
(3285, 1, 'http://localhost/lineup_campus/ogrenci-detay/wisufejig', 1, '2025-05-19 21:21:40'),
(3286, 1, 'http://localhost/lineup_campus/okul-detay/lineup-campus', 72, '2025-05-19 21:22:54'),
(3287, 1, 'http://localhost/lineup_campus/ogrenci-detay/wisufejig', 600, '2025-05-19 21:27:24'),
(3288, 1, 'http://localhost/lineup_campus/ogrenci-detay/wisufejig', 16, '2025-05-19 21:27:42'),
(3289, 1, 'http://localhost/lineup_campus/ogrenci-detay/wisufejig', 70, '2025-05-19 21:28:54'),
(3290, 1, 'http://localhost/lineup_campus/ogrenci-detay2/ogrenci1', 1212, '2025-05-19 21:30:27'),
(3291, 1, 'http://localhost/lineup_campus/ogrenci-detay/wisufejig', 94, '2025-05-19 21:30:30'),
(3292, 1, 'http://localhost/lineup_campus/ogrenci-detay/wisufejig', 80, '2025-05-19 21:31:51'),
(3293, 1, 'http://localhost/lineup_campus/ogrenci-detay/wisufejig', 36, '2025-05-19 21:32:29'),
(3294, 1, 'http://localhost/lineup_campus/ogrenci-detay/wisufejig', 337, '2025-05-19 21:38:09'),
(3295, 1, 'http://localhost/lineup_campus/ogrenci-detay/wisufejig', 553, '2025-05-19 21:47:23'),
(3296, 1, 'http://localhost/lineup_campus/ogrenci-detay/wisufejig', 57, '2025-05-19 21:48:22'),
(3297, 1, 'http://localhost/lineup_campus/ogrenci-detay/wisufejig', 48, '2025-05-19 21:49:12'),
(3298, 1, 'http://localhost/lineup_campus/ogrenciler', 123, '2025-05-19 21:51:16'),
(3299, 1, 'http://localhost/lineup_campus/ogrenciler', 7, '2025-05-19 21:51:25'),
(3300, 1, 'http://localhost/lineup_campus/ogrenciler', 115, '2025-05-19 21:53:22'),
(3301, 1, 'http://localhost/lineup_campus/ogrenciler', 76, '2025-05-19 21:54:40'),
(3302, 1, 'http://localhost/lineup_campus/ogrenciler', 29, '2025-05-19 21:55:11'),
(3303, 1, 'http://localhost/lineup_campus/ogrenciler', 35, '2025-05-19 21:55:48'),
(3304, 1, 'http://localhost/lineup_campus/ogrenciler', 70, '2025-05-19 21:57:00'),
(3305, 1, 'http://localhost/lineup_campus/ogrenciler', 3, '2025-05-19 21:57:06'),
(3306, 1, 'http://localhost/lineup_campus/ogrenciler', 43, '2025-05-19 21:57:51'),
(3307, 1, 'http://localhost/lineup_campus/ogrenci-detay/wisufejig', 1, '2025-05-19 21:57:54'),
(3308, 1, 'http://localhost/lineup_campus/ogrenciler', 4, '2025-05-19 21:58:00'),
(3309, 1, 'http://localhost/lineup_campus/ogrenci-detay/xacopiqym', 9, '2025-05-19 21:58:11'),
(3310, 1, 'http://localhost/lineup_campus/ogrenciler', 3, '2025-05-19 21:58:15'),
(3311, 1, 'http://localhost/lineup_campus/ogrenci-detay/ludaj', 5, '2025-05-19 21:58:22'),
(3312, 1, 'http://localhost/lineup_campus/ogrenciler', 2, '2025-05-19 21:58:26'),
(3313, 1, 'http://localhost/lineup_campus/ogrenci-detay/vejogym', 155, '2025-05-19 22:01:04'),
(3314, 1, 'http://localhost/lineup_campus/ogrenci-detay/vejogym', 27, '2025-05-19 22:01:32'),
(3315, 1, 'http://localhost/lineup_campus/ogrenci-detay/vejogym', 30, '2025-05-19 22:02:04'),
(3316, 1, 'http://localhost/lineup_campus/ogrenci-detay/vejogym', 13, '2025-05-19 22:02:19'),
(3317, 1, 'http://localhost/lineup_campus/ogrenci-detay/vejogym', 142, '2025-05-19 22:04:42'),
(3318, 1, 'http://localhost/lineup_campus/ogrenciler', 1169, '2025-05-19 22:05:46'),
(3319, 1, 'http://localhost/lineup_campus/ogrenciler', 2, '2025-05-19 22:05:51'),
(3320, 1, 'http://localhost/lineup_campus/ogrenciler', 7, '2025-05-19 22:05:59'),
(3321, 1, 'http://localhost/lineup_campus/ogrenci-detay/ludaj', 1, '2025-05-19 22:06:02'),
(3322, 1, 'http://localhost/lineup_campus/ogrenciler', 8, '2025-05-19 22:06:12'),
(3323, 102, 'http://localhost/lineup_campus/dashboard', 50, '2025-05-19 22:06:19'),
(3324, 1, 'http://localhost/lineup_campus/ogrenci-detay/wisufejig', 6, '2025-05-19 22:06:20'),
(3325, 102, 'http://localhost/lineup_campus/ders/matematik', 7, '2025-05-19 22:06:27'),
(3326, 102, 'http://localhost/lineup_campus/unite/dogal-sayilar-3', 3, '2025-05-19 22:06:30'),
(3327, 1, 'http://localhost/lineup_campus/ogrenci-detay/wisufejig', 19, '2025-05-19 22:06:41'),
(3328, 102, 'http://localhost/lineup_campus/unite/dogal-sayilar-2', 182, '2025-05-19 22:09:34'),
(3329, 102, 'http://localhost/lineup_campus/cozulmus-destek-talepleri', 2, '2025-05-19 22:09:37'),
(3330, 102, 'http://localhost/lineup_campus/aktif-destek-talepleri', 2, '2025-05-19 22:09:40'),
(3331, 102, 'http://localhost/lineup_campus/destek-talebi-ekle', 5, '2025-05-19 22:09:45'),
(3332, 102, 'http://localhost/lineup_campus/testler-ogrenci', 3, '2025-05-19 22:09:49'),
(3333, 102, 'http://localhost/lineup_campus/ders/matematik', 2, '2025-05-19 22:09:52'),
(3334, 102, 'http://localhost/lineup_campus/ders/ingilizce', 4, '2025-05-19 22:09:57'),
(3335, 102, 'http://localhost/lineup_campus/unite/in-my-city', 15, '2025-05-19 22:10:13'),
(3336, 102, 'http://localhost/lineup_campus/dashboard', 6, '2025-05-19 22:10:19'),
(3337, 1, 'http://localhost/lineup_campus/ogrenci-detay/wisufejig', 219, '2025-05-19 22:10:22'),
(3338, 1, 'http://localhost/lineup_campus/ogrenci-detay/wisufejig', 1379, '2025-05-19 22:28:43'),
(3339, 1, 'http://localhost/lineup_campus/ogrenci-detay/wisufejig', 11, '2025-05-19 22:28:56'),
(3340, 1, 'http://localhost/lineup_campus/ogrenci-detay/wisufejig', 81, '2025-05-19 22:30:19'),
(3341, 1, 'http://localhost/lineup_campus/ogrenci-detay/wisufejig', 28, '2025-05-19 22:30:49'),
(3342, 1, 'http://localhost/lineup_campus/pages/user-profile/activity.html', 9, '2025-05-19 22:30:59'),
(3343, 1, 'http://localhost/lineup_campus/account/activity.html', 2, '2025-05-19 22:31:02'),
(3344, 1, 'http://localhost/lineup_campus/account/billing.html', 0, '2025-05-19 22:31:03'),
(3345, 1, 'http://localhost/lineup_campus/account/statements.html', 1, '2025-05-19 22:31:04'),
(3346, 1, 'http://localhost/lineup_campus/account/referrals.html', 1, '2025-05-19 22:31:06'),
(3347, 1, 'http://localhost/lineup_campus/ogrenci-detay/vejogym', 1647, '2025-05-19 22:32:11'),
(3348, 1, 'http://localhost/lineup_campus/account/logs.html', 119, '2025-05-19 22:33:05'),
(3349, 1, 'http://localhost/lineup_campus/dashboard', 0, '2025-05-19 22:33:07'),
(3350, 1, 'http://localhost/lineup_campus/ogrenci-detay/vejogym', 194, '2025-05-19 22:35:27'),
(3351, 1, 'http://localhost/lineup_campus/okullar', 3886, '2025-05-19 22:42:18'),
(3352, 1, 'http://localhost/lineup_campus/siniflar', 19, '2025-05-19 22:42:39'),
(3353, 1, 'http://localhost/lineup_campus/siniflar', 12, '2025-05-19 22:42:52'),
(3354, 1, 'http://localhost/lineup_campus/ogrenci-detay/vejogym', 502, '2025-05-19 22:43:51'),
(3355, 1, 'http://localhost/lineup_campus/ogrenciler', 2, '2025-05-19 22:43:56'),
(3356, 1, 'http://localhost/lineup_campus/ogrenci-detay/wisufejig', 67, '2025-05-19 22:45:07');
INSERT INTO `timespend_lnp` (`id`, `user_id`, `sayfa_url`, `timeSpent`, `exitTime`) VALUES
(3357, 1, 'http://localhost/lineup_campus/ogrenci-detay/wisufejig', 15, '2025-05-19 22:45:22'),
(3358, 1, 'http://localhost/lineup_campus/ogrenci-detay/wisufejig', 27, '2025-05-19 22:45:51'),
(3359, 1, 'http://localhost/lineup_campus/ogrenci-detay/wisufejig', 60, '2025-05-19 22:46:53'),
(3360, 1, 'http://localhost/lineup_campus/siniflar', 442, '2025-05-19 22:50:16'),
(3361, 1, 'http://localhost/lineup_campus/dersler', 63, '2025-05-19 22:51:22'),
(3362, 1, 'http://localhost/lineup_campus/account/logs.html', 33340, '2025-05-20 07:46:46'),
(3363, 1, 'http://localhost/lineup_campus/account/api-keys.html', 9, '2025-05-20 07:46:56'),
(3364, 1, 'http://localhost/lineup_campus/ogrenci-detay/wisufejig', 32452, '2025-05-20 07:47:47'),
(3365, 1, 'http://localhost/lineup_campus/dashboard', 0, '2025-05-20 07:47:50'),
(3366, 1, 'http://localhost/lineup_campus/ogrenci-detay/wisufejig', 203, '2025-05-20 07:51:16'),
(3367, 1, 'http://localhost/lineup_campus/ogrenci-detay/wisufejig', 22, '2025-05-20 07:51:39'),
(3368, 1, 'http://localhost/lineup_campus/ogrenci-detay/wisufejig', 18, '2025-05-20 07:51:59'),
(3369, 1, 'http://localhost/lineup_campus/ogrenci-detay/wisufejig', 233, '2025-05-20 08:34:24'),
(3370, 1, 'http://localhost/lineup_campus/dersler', 35833, '2025-05-20 08:48:38'),
(3371, 1, 'http://localhost/lineup_campus/dersler', 1236, '2025-05-20 08:51:06'),
(3372, 1, 'http://localhost/lineup_campus/dersler', 2, '2025-05-20 08:51:08'),
(3373, 1, 'http://localhost/lineup_campus/ogrenciler', 773, '2025-05-20 09:04:03'),
(3374, 1, 'http://localhost/lineup_campus/ogrenciler', 7, '2025-05-20 09:04:13'),
(3375, 1, 'http://localhost/lineup_campus/ana-okulu-icerikler-detay.php?id=96', 147, '2025-05-20 09:38:54'),
(3376, 1, 'http://localhost/lineup_campus/paketler', 41358, '2025-05-20 09:43:52'),
(3377, 1, 'http://localhost/lineup_campus/paketler', 86, '2025-05-20 09:45:20'),
(3378, 1, 'http://localhost/lineup_campus/paketler', 17, '2025-05-20 09:45:39'),
(3379, 1, 'http://localhost/lineup_campus/paketler', 51, '2025-05-20 09:46:33'),
(3380, 1, 'http://localhost/lineup_campus/paketler', 45, '2025-05-20 09:47:19'),
(3381, 1, 'http://localhost/lineup_campus/ana-okulu-icerikler-detay.php?id=96', 625, '2025-05-20 09:49:33'),
(3382, 1, 'http://localhost/lineup_campus/yas-grubu', 6, '2025-05-20 09:49:42'),
(3383, 1, 'http://localhost/lineup_campus/okullar', 4, '2025-05-20 09:49:48'),
(3384, 1, 'http://localhost/lineup_campus/onemli-haftalar', 12, '2025-05-20 09:50:02'),
(3385, 1, 'http://localhost/lineup_campus/paket-detay?id=1', 2174, '2025-05-20 10:23:36'),
(3386, 1, 'http://localhost/lineup_campus/ogrenciler', 6, '2025-05-20 10:23:43'),
(3387, 1, 'http://localhost/lineup_campus/apps/support-center/tutorials/post.html', 7063, '2025-05-20 10:46:22'),
(3388, 1, 'http://localhost/lineup_campus/paketler', 3643, '2025-05-20 10:50:47'),
(3389, 1, 'http://localhost/lineup_campus/paketler', 34, '2025-05-20 10:51:23'),
(3390, 1, 'http://localhost/lineup_campus/paketler', 37, '2025-05-20 10:52:02'),
(3391, 1, 'http://localhost/lineup_campus/paketler', 42, '2025-05-20 10:52:46'),
(3392, 1, 'http://localhost/lineup_campus/paketler', 8, '2025-05-20 10:52:56'),
(3393, 1, 'http://localhost/lineup_campus/paketler', 8, '2025-05-20 10:52:57'),
(3394, 1, 'http://localhost/lineup_campus/paketler', 10, '2025-05-20 10:52:59'),
(3395, 1, 'http://localhost/lineup_campus/onemli-haftalar', 50, '2025-05-20 10:53:51'),
(3396, 1, 'http://localhost/lineup_campus/ogrenci-detay/ogrenci1', 1813, '2025-05-20 10:53:58'),
(3397, 1, 'http://localhost/lineup_campus/paketler', 39, '2025-05-20 10:54:40'),
(3398, 1, 'http://localhost/lineup_campus/paketler', 16, '2025-05-20 10:54:58'),
(3399, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-ekle', 1, '2025-05-20 10:55:01'),
(3400, 1, 'http://localhost/lineup_campus/paketler', 31, '2025-05-20 10:55:33'),
(3401, 1, 'http://localhost/lineup_campus/paketler', 39, '2025-05-20 10:56:15'),
(3402, 1, 'http://localhost/lineup_campus/paketler', 68, '2025-05-20 10:57:23'),
(3403, 1, 'http://localhost/lineup_campus/onemli-haftalar', 680, '2025-05-20 11:05:12'),
(3404, 1, 'http://localhost/lineup_campus/paketler', 351, '2025-05-20 11:05:16'),
(3405, 1, 'http://localhost/lineup_campus/paketler', 24, '2025-05-20 11:05:42'),
(3406, 1, 'http://localhost/lineup_campus/paketler', 82, '2025-05-20 11:07:06'),
(3407, 1, 'http://localhost/lineup_campus/paketler', 159, '2025-05-20 11:09:46'),
(3408, 1, 'http://localhost/lineup_campus/paketler', 6, '2025-05-20 11:09:55'),
(3409, 1, 'http://localhost/lineup_campus/paketler', 33, '2025-05-20 11:10:30'),
(3410, 1, 'http://localhost/lineup_campus/onemli-haftalar', 563, '2025-05-20 11:14:38'),
(3411, 1, 'http://localhost/lineup_campus/paketler', 248, '2025-05-20 11:14:40'),
(3412, 1, 'http://localhost/lineup_campus/paketler', 40, '2025-05-20 11:15:22'),
(3413, 1, 'http://localhost/lineup_campus/paketler', 42, '2025-05-20 11:16:07'),
(3414, 1, 'http://localhost/lineup_campus/paketler', 73, '2025-05-20 11:17:22'),
(3415, 1, 'http://localhost/lineup_campus/onemli-haftalar', 296, '2025-05-20 11:19:35'),
(3416, 1, 'http://localhost/lineup_campus/paketler', 133, '2025-05-20 11:19:38'),
(3417, 1, 'http://localhost/lineup_campus/paketler', 44, '2025-05-20 11:21:33'),
(3418, 1, 'http://localhost/lineup_campus/paketler', 36, '2025-05-20 11:22:11'),
(3419, 1, 'http://localhost/lineup_campus/paketler', 11, '2025-05-20 11:22:24'),
(3420, 1, 'http://localhost/lineup_campus/paketler', 11, '2025-05-20 11:22:36'),
(3421, 1, 'http://localhost/lineup_campus/onemli-haftalar', 336, '2025-05-20 11:25:13'),
(3422, 1, 'http://localhost/lineup_campus/paketler', 159, '2025-05-20 11:25:17'),
(3423, 1, 'http://localhost/lineup_campus/paketler', 8, '2025-05-20 11:25:27'),
(3424, 1, 'http://localhost/lineup_campus/paketler', 39, '2025-05-20 11:26:08'),
(3425, 1, 'http://localhost/lineup_campus/paketler', 5, '2025-05-20 11:26:15'),
(3426, 1, 'http://localhost/lineup_campus/paketler', 15, '2025-05-20 11:26:32'),
(3427, 1, 'http://localhost/lineup_campus/paketler', 71, '2025-05-20 11:27:45'),
(3428, 1, 'http://localhost/lineup_campus/paketler', 27, '2025-05-20 11:28:14'),
(3429, 1, 'http://localhost/lineup_campus/paketler', 11, '2025-05-20 11:28:28'),
(3430, 1, 'http://localhost/lineup_campus/paketler', 13, '2025-05-20 11:28:44'),
(3431, 1, 'http://localhost/lineup_campus/paketler', 191, '2025-05-20 11:31:57'),
(3432, 1, 'http://localhost/lineup_campus/paketler', 44, '2025-05-20 11:32:44'),
(3433, 1, 'http://localhost/lineup_campus/paketler', 126, '2025-05-20 11:34:52'),
(3434, 1, 'http://localhost/lineup_campus/paketler', 71, '2025-05-20 11:36:05'),
(3435, 1, 'http://localhost/lineup_campus/paketler', 7, '2025-05-20 11:36:14'),
(3436, 1, 'http://localhost/lineup_campus/paketler', 25, '2025-05-20 11:36:41'),
(3437, 1, 'http://localhost/lineup_campus/paketler', 145, '2025-05-20 11:39:08'),
(3438, 1, 'http://localhost/lineup_campus/paketler', 389, '2025-05-20 11:45:38'),
(3439, 1, 'http://localhost/lineup_campus/paketler', 1674, '2025-05-20 12:13:35'),
(3440, 1, 'http://localhost/lineup_campus/paketler', 61, '2025-05-20 12:14:38'),
(3441, 1, 'http://localhost/lineup_campus/paket-detay?id=1', 323, '2025-05-20 12:20:03'),
(3442, 1, 'http://localhost/lineup_campus/paketler', 87, '2025-05-20 12:21:33'),
(3443, 1, 'http://localhost/lineup_campus/paketler', 92, '2025-05-20 12:23:07'),
(3444, 1, 'http://localhost/lineup_campus/paketler', 84, '2025-05-20 12:24:33'),
(3445, 1, 'http://localhost/lineup_campus/paketler', 181, '2025-05-20 12:27:36'),
(3446, 1, 'http://localhost/lineup_campus/paketler', 128, '2025-05-20 12:29:46'),
(3447, 1, 'http://localhost/lineup_campus/paketler', 8, '2025-05-20 12:29:56'),
(3448, 1, 'http://localhost/lineup_campus/paketler', 232, '2025-05-20 12:33:50'),
(3449, 1, 'http://localhost/lineup_campus/onemli-haftalar', 4217, '2025-05-20 12:35:32'),
(3450, 1, 'http://localhost/lineup_campus/paketler', 102, '2025-05-20 12:35:34'),
(3451, 1, 'http://localhost/lineup_campus/paketler', 17, '2025-05-20 12:35:53'),
(3452, 1, 'http://localhost/lineup_campus/paketler', 226, '2025-05-20 12:39:42'),
(3453, 1, 'http://localhost/lineup_campus/paketler', 140, '2025-05-20 12:42:04'),
(3454, 1, 'http://localhost/lineup_campus/paketler', 65, '2025-05-20 12:43:12'),
(3455, 1, 'http://localhost/lineup_campus/paketler', 153, '2025-05-20 12:45:47'),
(3456, 1, 'http://localhost/lineup_campus/paketler', 37, '2025-05-20 12:46:26'),
(3457, 1, 'http://localhost/lineup_campus/paketler', 40, '2025-05-20 12:47:07'),
(3458, 1, 'http://localhost/lineup_campus/paketler', 101, '2025-05-20 12:48:51'),
(3459, 1, 'http://localhost/lineup_campus/paketler', 27, '2025-05-20 12:49:19'),
(3460, 1, 'http://localhost/lineup_campus/paketler', 126, '2025-05-20 12:51:28'),
(3461, 1, 'http://localhost/lineup_campus/paketler', 33, '2025-05-20 12:52:03'),
(3462, 1, 'http://localhost/lineup_campus/paketler', 586, '2025-05-20 13:01:51'),
(3463, 1, 'http://localhost/lineup_campus/paketler', 302, '2025-05-20 13:06:56'),
(3464, 1, 'http://localhost/lineup_campus/paketler', 127, '2025-05-20 13:09:05'),
(3465, 1, 'http://localhost/lineup_campus/paketler', 166, '2025-05-20 13:11:53'),
(3466, 1, 'http://localhost/lineup_campus/paketler', 250, '2025-05-20 13:16:06'),
(3467, 1, 'http://localhost/lineup_campus/paketler', 7, '2025-05-20 13:16:15'),
(3468, 1, 'http://localhost/lineup_campus/paketler', 90, '2025-05-20 13:17:47'),
(3469, 1, 'http://localhost/lineup_campus/paketler', 33, '2025-05-20 13:18:22'),
(3470, 1, 'http://localhost/lineup_campus/paketler', 9, '2025-05-20 13:18:33'),
(3471, 1, 'http://localhost/lineup_campus/paketler', 19, '2025-05-20 13:18:54'),
(3472, 1, 'http://localhost/lineup_campus/paketler', 14, '2025-05-20 13:19:10'),
(3473, 1, 'http://localhost/lineup_campus/paketler', 685, '2025-05-20 13:30:36'),
(3474, 1, 'http://localhost/lineup_campus/paket-detay?id=33', 2, '2025-05-20 13:30:40'),
(3475, 1, 'http://localhost/lineup_campus/paketler', 4, '2025-05-20 13:30:47'),
(3476, 1, 'http://localhost/lineup_campus/onemli-haftalar', 3386, '2025-05-20 13:32:01'),
(3477, 1, 'http://localhost/lineup_campus/paketler', 86, '2025-05-20 13:33:29'),
(3478, 1, 'http://localhost/lineup_campus/paketler', 11, '2025-05-20 13:33:41'),
(3479, 1, 'http://localhost/lineup_campus/paketler', 1, '2025-05-20 13:33:44'),
(3480, 1, 'http://localhost/lineup_campus/paket-detay?id=34', 223, '2025-05-20 13:37:28'),
(3481, 1, 'http://localhost/lineup_campus/paket-detay?id=34', 65, '2025-05-20 13:38:35'),
(3482, 1, 'http://localhost/lineup_campus/paket-detay?id=28', 507, '2025-05-20 13:39:16'),
(3483, 1, 'http://localhost/lineup_campus/paket-detay?id=34', 144, '2025-05-20 13:41:01'),
(3484, 1, 'http://localhost/lineup_campus/paket-detay?id=34', 16, '2025-05-20 13:41:18'),
(3485, 1, 'http://localhost/lineup_campus/paket-detay?id=34', 24, '2025-05-20 13:41:45'),
(3486, 1, 'http://localhost/lineup_campus/paket-detay?id=34', 6, '2025-05-20 13:41:52'),
(3487, 1, 'http://localhost/lineup_campus/paket-detay?id=34', 73, '2025-05-20 13:43:07'),
(3488, 1, 'http://localhost/lineup_campus/paket-detay?id=34', 45, '2025-05-20 13:43:54'),
(3489, 1, 'http://localhost/lineup_campus/paket-detay?id=34', 17, '2025-05-20 13:44:13'),
(3490, 1, 'http://localhost/lineup_campus/paket-detay?id=34', 5, '2025-05-20 13:44:20'),
(3491, 1, 'http://localhost/lineup_campus/paketler', 305, '2025-05-20 13:44:23'),
(3492, 1, 'http://localhost/lineup_campus/paket-detay?id=34', 25, '2025-05-20 13:44:49'),
(3493, 1, 'http://localhost/lineup_campus/paket-detay?id=34', 32, '2025-05-20 13:45:24'),
(3494, 1, 'http://localhost/lineup_campus/paket-detay?id=34', 26, '2025-05-20 13:45:51'),
(3495, 1, 'http://localhost/lineup_campus/paket-detay?id=34', 119, '2025-05-20 13:46:20'),
(3496, 1, 'http://localhost/lineup_campus/paket-detay?id=34', 70, '2025-05-20 13:47:03'),
(3497, 1, 'http://localhost/lineup_campus/paket-detay?id=34', 23, '2025-05-20 13:47:28'),
(3498, 1, 'http://localhost/lineup_campus/paket-detay?id=34', 142, '2025-05-20 13:49:52'),
(3499, 1, 'http://localhost/lineup_campus/paket-detay?id=34', 55, '2025-05-20 13:50:49'),
(3500, 1, 'http://localhost/lineup_campus/paket-detay?id=34', 18, '2025-05-20 13:51:09'),
(3501, 1, 'http://localhost/lineup_campus/paket-detay?id=34', 89, '2025-05-20 13:52:40'),
(3502, 1, 'http://localhost/lineup_campus/paket-detay?id=34', 32, '2025-05-20 13:53:14'),
(3503, 1, 'http://localhost/lineup_campus/paketler', 681, '2025-05-20 13:57:44'),
(3504, 1, 'http://localhost/lineup_campus/paket-detay?id=34', 279, '2025-05-20 13:57:55'),
(3505, 1, 'http://localhost/lineup_campus/paketler', 111, '2025-05-20 13:59:37'),
(3506, 1, 'http://localhost/lineup_campus/paket-detay?id=34', 105, '2025-05-20 13:59:41'),
(3507, 1, 'http://localhost/lineup_campus/paket-detay?id=34', 31, '2025-05-20 14:00:14'),
(3508, 1, 'http://localhost/lineup_campus/paket-detay?id=34', 239, '2025-05-20 14:04:15'),
(3509, 1, 'http://localhost/lineup_campus/paket-detay?id=34', 23, '2025-05-20 14:04:41'),
(3510, 1, 'http://localhost/lineup_campus/paket-detay?id=34', 62, '2025-05-20 14:05:45'),
(3511, 1, 'http://localhost/lineup_campus/paket-detay?id=34', 3, '2025-05-20 14:05:51'),
(3512, 1, 'http://localhost/lineup_campus/paket-detay?id=34', 4, '2025-05-20 14:05:52'),
(3513, 1, 'http://localhost/lineup_campus/paket-detay?id=34', 6, '2025-05-20 14:05:53'),
(3514, 1, 'http://localhost/lineup_campus/paket-detay?id=34', 6, '2025-05-20 14:05:54'),
(3515, 1, 'http://localhost/lineup_campus/paket-detay?id=34', 8, '2025-05-20 14:05:56'),
(3516, 1, 'http://localhost/lineup_campus/dashboard', 87, '2025-05-20 14:07:25'),
(3517, 1, 'http://localhost/lineup_campus/paketler', 476, '2025-05-20 14:07:35'),
(3518, 1, 'http://localhost/lineup_campus/paket-detay?id=34', 1, '2025-05-20 14:07:38'),
(3519, 1, 'http://localhost/lineup_campus/paket-detay?id=34', 26, '2025-05-20 14:08:06'),
(3520, 1, 'http://localhost/lineup_campus/paket-detay?id=34', 60, '2025-05-20 14:09:08'),
(3521, 1, 'http://localhost/lineup_campus/paket-detay?id=34', 7, '2025-05-20 14:09:17'),
(3522, 1, 'http://localhost/lineup_campus/paket-detay?id=34', 17, '2025-05-20 14:09:36'),
(3523, 1, 'http://localhost/lineup_campus/paket-detay?id=34', 60, '2025-05-20 14:10:39'),
(3524, 1, 'http://localhost/lineup_campus/paket-detay?id=34', 37, '2025-05-20 14:11:17'),
(3525, 1, 'http://localhost/lineup_campus/paket-detay?id=34', 15, '2025-05-20 14:11:34'),
(3526, 1, 'http://localhost/lineup_campus/paket-detay?id=34', 45, '2025-05-20 14:12:21'),
(3527, 1, 'http://localhost/lineup_campus/dashboard', 400, '2025-05-20 14:14:07'),
(3528, 1, 'http://localhost/lineup_campus/paket-detay?id=34', 106, '2025-05-20 14:14:09'),
(3529, 1, 'http://localhost/lineup_campus/paket-detay?id=34', 45, '2025-05-20 14:14:56'),
(3530, 1, 'http://localhost/lineup_campus/paket-detay?id=34', 181, '2025-05-20 14:17:59'),
(3531, 1, 'http://localhost/lineup_campus/paket-detay?id=34', 23, '2025-05-20 14:18:24'),
(3532, 1, 'http://localhost/lineup_campus/paket-detay?id=34', 8, '2025-05-20 14:18:35'),
(3533, 1, 'http://localhost/lineup_campus/paket-detay?id=34', 6, '2025-05-20 14:18:43'),
(3534, 1, 'http://localhost/lineup_campus/paket-detay?id=34', 8, '2025-05-20 14:18:52'),
(3535, 1, 'http://localhost/lineup_campus/paket-detay?id=34', 183, '2025-05-20 14:21:58'),
(3536, 1, 'http://localhost/lineup_campus/paket-detay?id=34', 2, '2025-05-20 14:22:02'),
(3537, 1, 'http://localhost/lineup_campus/paket-detay?id=34', 11, '2025-05-20 14:23:31'),
(3538, 1, 'http://localhost/lineup_campus/paket-detay?id=34', 1, '2025-05-20 14:23:34'),
(3539, 1, 'http://localhost/lineup_campus/paket-ayarlar', 43, '2025-05-20 14:24:19'),
(3540, 1, 'http://localhost/lineup_campus/paket-ayarlar', 13, '2025-05-20 14:24:34'),
(3541, 1, 'http://localhost/lineup_campus/paket-ayarlar', 16, '2025-05-20 14:24:51'),
(3542, 1, 'http://localhost/lineup_campus/paket-ayarlar', 816, '2025-05-20 14:38:29'),
(3543, 1, 'http://localhost/lineup_campus/paket-ayarlar', 23, '2025-05-20 14:38:54'),
(3544, 1, 'http://localhost/lineup_campus/paket-ayarlar', 15, '2025-05-20 14:39:11'),
(3545, 1, 'http://localhost/lineup_campus/paket-ayarlar', 24, '2025-05-20 14:39:37'),
(3546, 1, 'http://localhost/lineup_campus/paket-ayarlar', 10, '2025-05-20 14:39:49'),
(3547, 1, 'http://localhost/lineup_campus/paket-ayarlar', 18, '2025-05-20 14:40:09'),
(3548, 1, 'http://localhost/lineup_campus/paket-ayarlar', 15, '2025-05-20 14:40:26'),
(3549, 1, 'http://localhost/lineup_campus/paket-ayarlar', 27, '2025-05-20 14:40:54'),
(3550, 1, 'http://localhost/lineup_campus/paketler', 19, '2025-05-20 14:41:16'),
(3551, 1, 'http://localhost/lineup_campus/paket-ayarlar', 30, '2025-05-20 14:41:48'),
(3552, 1, 'http://localhost/lineup_campus/paket-ayarlar', 30, '2025-05-20 14:42:20'),
(3553, 1, 'http://localhost/lineup_campus/paket-ayarlar', 61, '2025-05-20 14:43:23'),
(3554, 1, 'http://localhost/lineup_campus/paket-ayarlar', 685, '2025-05-20 14:54:50'),
(3555, 1, 'http://localhost/lineup_campus/paket-ayarlar', 21, '2025-05-20 14:55:47'),
(3556, 1, 'http://localhost/lineup_campus/paket-ayarlar', 13, '2025-05-20 14:57:36'),
(3557, 1, 'http://localhost/lineup_campus/paket-ayarlar', 55, '2025-05-20 14:58:32'),
(3558, 1, 'http://localhost/lineup_campus/paket-ayarlar', 22, '2025-05-20 14:59:47'),
(3559, 1, 'http://localhost/lineup_campus/dashboard', 9, '2025-05-20 15:01:18'),
(3560, 1, 'http://localhost/lineup_campus/paketler', 4, '2025-05-20 15:01:25'),
(3561, 1, 'http://localhost/lineup_campus/paket-ayarlar', 150, '2025-05-20 15:05:35'),
(3562, 1, 'http://localhost/lineup_campus/dashboard', 1, '2025-05-20 15:05:39'),
(3563, 1, 'http://localhost/lineup_campus/dashboard', 4, '2025-05-20 15:05:40'),
(3564, 1, 'http://localhost/lineup_campus/paket-ayarlar', 4, '2025-05-20 15:05:42'),
(3565, 1, 'http://localhost/lineup_campus/dashboard', 3107, '2025-05-20 15:05:56'),
(3566, 1, 'http://localhost/lineup_campus/paket-ayarlar', 13, '2025-05-20 15:05:57'),
(3567, 1, 'http://localhost/lineup_campus/dashboard', 4, '2025-05-20 15:06:17'),
(3568, 1, 'http://localhost/lineup_campus/paket-ayarlar', 11, '2025-05-20 15:06:30'),
(3569, 1, 'http://localhost/lineup_campus/hesap-olustur', 19179, '2025-05-20 15:07:52'),
(3570, 1, 'http://localhost/lineup_campus/paket-ayarlar', 394, '2025-05-20 15:08:01'),
(3571, 1, 'http://localhost/lineup_campus/paket-ayarlar', 2, '2025-05-20 15:08:05'),
(3572, 1, 'http://localhost/lineup_campus/paket-ayarlar', 197, '2025-05-20 15:11:23'),
(3573, 1, 'http://localhost/lineup_campus/paket-ayarlar', 4, '2025-05-20 15:11:29'),
(3574, 1, 'http://localhost/lineup_campus/paket-ayarlar', 67, '2025-05-20 15:12:38'),
(3575, 1, 'http://localhost/lineup_campus/paket-ayarlar', 3, '2025-05-20 15:12:43'),
(3576, 1, 'http://localhost/lineup_campus/paket-ayarlar', 5, '2025-05-20 15:15:22'),
(3577, 1, 'http://localhost/lineup_campus/paket-ayarlar', 1036, '2025-05-20 15:30:01'),
(3578, 1, 'http://localhost/lineup_campus/paket-ayarlar', 8, '2025-05-20 15:30:11'),
(3579, 1, 'http://localhost/lineup_campus/paket-ayarlar', 3, '2025-05-20 15:30:16'),
(3580, 1, 'http://localhost/lineup_campus/paket-ayarlar', 129, '2025-05-20 15:32:27'),
(3581, 1, 'http://localhost/lineup_campus/paketler', 4, '2025-05-20 15:32:35'),
(3582, 1, 'http://localhost/lineup_campus/ayarlar', 41, '2025-05-20 15:33:18'),
(3583, 1, 'http://localhost/lineup_campus/ayarlar', 8, '2025-05-20 15:33:28'),
(3584, 1, 'http://localhost/lineup_campus/ayarlar', 37, '2025-05-20 15:33:57'),
(3585, 1, 'http://localhost/lineup_campus/ayarlar', 15, '2025-05-20 15:34:14'),
(3586, 1, 'http://localhost/lineup_campus/ayarlar', 44, '2025-05-20 15:34:43'),
(3587, 1, 'http://localhost/lineup_campus/ayarlar', 52, '2025-05-20 15:35:38'),
(3588, 1, 'http://localhost/lineup_campus/ayarlar', 7, '2025-05-20 15:35:47'),
(3589, 1, 'http://localhost/lineup_campus/kullanici-odeme-raporu', 58, '2025-05-20 15:36:48'),
(3590, 1, 'http://localhost/lineup_campus/kullanici-odeme-raporu', 37, '2025-05-20 15:37:26'),
(3591, 1, 'http://localhost/lineup_campus/kullanici-odeme-raporu', 20, '2025-05-20 15:37:49'),
(3592, 1, 'http://localhost/lineup_campus/kullanici-odeme-raporu', 16, '2025-05-20 15:38:06'),
(3593, 1, 'http://localhost/lineup_campus/kullanici-odeme-raporu', 713, '2025-05-20 15:50:03'),
(3594, 1, 'http://localhost/lineup_campus/kullanici-odeme-raporu', 368, '2025-05-20 15:56:22'),
(3595, 1, 'http://localhost/lineup_campus/okullar', 3320, '2025-05-20 16:10:44'),
(3596, 1, 'http://localhost/lineup_campus/okullar', 13, '2025-05-20 16:10:58'),
(3597, 1, 'http://localhost/lineup_campus/paketler', 5, '2025-05-20 16:11:05'),
(3598, 1, 'http://localhost/lineup_campus/kullanici-odeme-raporu', 1848, '2025-05-20 16:27:13'),
(3599, 1, 'http://localhost/lineup_campus/kullanici-odeme-raporu', 51, '2025-05-20 16:28:05'),
(3600, 1, 'http://localhost/lineup_campus/kullanici-odeme-raporu', 42, '2025-05-20 16:28:51'),
(3601, 1, 'http://localhost/lineup_campus/kullanici-odeme-raporu', 68, '2025-05-20 16:29:59'),
(3602, 1, 'http://localhost/lineup_campus/kullanici-odeme-raporu', 13, '2025-05-20 16:30:16'),
(3603, 1, 'http://localhost/lineup_campus/kullanici-odeme-raporu', 3227, '2025-05-20 17:24:03'),
(3604, 1, 'http://localhost/lineup_campus/kullanici-odeme-raporu', 63, '2025-05-20 17:25:09'),
(3605, 1, 'http://localhost/lineup_campus/kullanici-odeme-raporu', 259, '2025-05-20 17:29:31'),
(3606, 1, 'http://localhost/lineup_campus/kullanici-odeme-raporu', 23, '2025-05-20 17:29:57'),
(3607, 1, 'http://localhost/lineup_campus/kullanici-odeme-raporu', 108, '2025-05-20 17:31:48'),
(3608, 1, 'http://localhost/lineup_campus/kullanici-odeme-raporu', 160, '2025-05-20 17:34:30'),
(3609, 1, 'http://localhost/lineup_campus/kullanici-odeme-raporu', 18, '2025-05-20 17:35:49'),
(3610, 1, 'http://localhost/lineup_campus/kullanici-odeme-raporu', 183, '2025-05-20 17:38:56'),
(3611, 1, 'http://localhost/lineup_campus/kullanici-odeme-raporu', 254, '2025-05-20 17:43:12'),
(3612, 1, 'http://localhost/lineup_campus/kullanici-odeme-raporu', 146, '2025-05-20 17:45:39'),
(3613, 1, 'http://localhost/lineup_campus/kullanici-odeme-raporu', 100, '2025-05-20 17:47:21'),
(3614, 1, 'http://localhost/lineup_campus/kullanici-odeme-raporu', 19, '2025-05-20 17:47:42'),
(3615, 1, 'http://localhost/lineup_campus/kullanici-odeme-raporu', 25, '2025-05-20 17:48:09'),
(3616, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-yonetimi', 68, '2025-05-20 17:49:19'),
(3617, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-yonetimi', 3, '2025-05-20 17:49:23'),
(3618, 1, 'http://localhost/lineup_campus/kullanici-odeme-raporu', 7, '2025-05-20 17:49:34'),
(3619, 1, 'http://localhost/lineup_campus/kullanici-odeme-raporu', 21, '2025-05-20 17:49:57'),
(3620, 1, 'http://localhost/lineup_campus/kullanici-odeme-raporu', 202, '2025-05-20 17:53:21'),
(3621, 1, 'http://localhost/lineup_campus/kullanici-odeme-raporu', 5, '2025-05-20 17:53:26'),
(3622, 1, 'http://localhost/lineup_campus/ayarlar', 11, '2025-05-20 17:53:39'),
(3623, 1, 'http://localhost/lineup_campus/ayarlar', 4, '2025-05-20 17:53:45'),
(3624, 1, 'http://localhost/lineup_campus/ayarlar', 2165, '2025-05-20 18:29:51'),
(3625, 1, 'http://localhost/lineup_campus/kullanici-odeme-raporu', 20750, '2025-05-20 21:56:57'),
(3626, 1, 'http://localhost/lineup_campus/dashboard', 25974, '2025-05-20 22:19:26'),
(3627, 1, 'http://localhost/lineup_campus/dashboard', 0, '2025-05-20 22:19:30'),
(3628, 1, 'http://localhost/lineup_campus/dashboard', 3, '2025-05-20 22:19:34'),
(3629, 1, 'http://localhost/lineup_campus/ayarlar', 5, '2025-05-20 22:19:41'),
(3630, 1, 'http://localhost/lineup_campus/ayarlar', 1, '2025-05-20 22:19:44'),
(3631, 1, 'http://localhost/lineup_campus/kullanici-odeme-raporu', 42917, '2025-05-21 10:15:03'),
(3632, 1, 'http://localhost/lineup_campus/account/overview.html', 274, '2025-05-21 10:19:38'),
(3633, 1, 'http://localhost/lineup_campus/kullanici-odeme-raporu', 2564, '2025-05-21 11:02:25'),
(3634, 1, 'http://localhost/lineup_campus/kullanici-odeme-raporu', 79, '2025-05-21 11:03:46'),
(3635, 1, 'http://localhost/lineup_campus/kullanici-odeme-raporu', 1, '2025-05-21 11:03:48'),
(3636, 1, 'http://localhost/lineup_campus/grafik-rapor', 44, '2025-05-21 11:04:34'),
(3637, 1, 'http://localhost/lineup_campus/grafik-rapor', 28, '2025-05-21 11:05:04'),
(3638, 1, 'http://localhost/lineup_campus/grafik-rapor', 39, '2025-05-21 11:05:45'),
(3639, 1, 'http://localhost/lineup_campus/grafik-rapor', 144, '2025-05-21 11:08:11'),
(3640, 1, 'http://localhost/lineup_campus/grafik-rapor', 8, '2025-05-21 11:08:21'),
(3641, 1, 'http://localhost/lineup_campus/grafik-rapor', 89, '2025-05-21 11:09:55'),
(3642, 1, 'http://localhost/lineup_campus/grafik-rapor', 231, '2025-05-21 11:13:50'),
(3643, 1, 'http://localhost/lineup_campus/grafik-rapor', 272, '2025-05-21 11:18:24'),
(3644, 1, 'http://localhost/lineup_campus/grafik-rapor', 38, '2025-05-21 11:19:04'),
(3645, 1, 'http://localhost/lineup_campus/grafik-rapor', 43, '2025-05-21 11:19:50'),
(3646, 1, 'http://localhost/lineup_campus/grafik-rapor', 61, '2025-05-21 11:20:55'),
(3647, 1, 'http://localhost/lineup_campus/grafik-rapor', 153, '2025-05-21 11:23:30'),
(3648, 1, 'http://localhost/lineup_campus/grafik-rapor', 570, '2025-05-21 11:33:02'),
(3649, 1, 'http://localhost/lineup_campus/grafik-rapor', 1, '2025-05-21 11:33:06'),
(3650, 1, 'http://localhost/lineup_campus/odeme-tipi-grafik-rapor', 120, '2025-05-21 11:35:07'),
(3651, 1, 'http://localhost/lineup_campus/odeme-tipi-grafik-rapor', 177, '2025-05-21 11:38:06'),
(3652, 1, 'http://localhost/lineup_campus/odeme-tipi-grafik-rapor', 1207, '2025-05-21 11:58:16'),
(3653, 1, 'http://localhost/lineup_campus/odeme-tipi-grafik-rapor', 34, '2025-05-21 11:58:52'),
(3654, 1, 'http://localhost/lineup_campus/odeme-tipi-grafik-rapor', 39, '2025-05-21 11:59:33'),
(3655, 1, 'http://localhost/lineup_campus/odeme-tipi-grafik-rapor', 22, '2025-05-21 11:59:57'),
(3656, 1, 'http://localhost/lineup_campus/odeme-tipi-grafik-rapor', 23, '2025-05-21 11:59:58'),
(3657, 1, 'http://localhost/lineup_campus/grafik-rapor', 15, '2025-05-21 12:00:16'),
(3658, 1, 'http://localhost/lineup_campus/odeme-tipi-grafik-rapor', 8, '2025-05-21 12:00:26'),
(3659, 1, 'http://localhost/lineup_campus/odeme-tipi-grafik-rapor', 195, '2025-05-21 12:03:42'),
(3660, 1, 'http://localhost/lineup_campus/odeme-tipi-grafik-rapor', 1, '2025-05-21 12:03:45'),
(3661, 1, 'http://localhost/lineup_campus/kullanici-basina-gelir-raporu', 249, '2025-05-21 12:07:57'),
(3662, 1, 'http://localhost/lineup_campus/kullanici-basina-gelir-raporu', 242, '2025-05-21 12:12:00'),
(3663, 1, 'http://localhost/lineup_campus/kullanici-basina-gelir-raporu', 211, '2025-05-21 12:15:33'),
(3664, 1, 'http://localhost/lineup_campus/kullanici-basina-gelir-raporu', 335, '2025-05-21 12:21:10'),
(3665, 1, 'http://localhost/lineup_campus/kullanici-basina-gelir-raporu', 85, '2025-05-21 12:22:38'),
(3666, 1, 'http://localhost/lineup_campus/kullanici-basina-gelir-raporu', 51, '2025-05-21 12:23:32'),
(3667, 1, 'http://localhost/lineup_campus/kullanici-basina-gelir-raporu', 87, '2025-05-21 12:25:01'),
(3668, 1, 'http://localhost/lineup_campus/kullanici-basina-gelir-raporu', 97, '2025-05-21 12:26:40'),
(3669, 1, 'http://localhost/lineup_campus/kullanici-basina-gelir-raporu', 60, '2025-05-21 12:27:43'),
(3670, 1, 'http://localhost/lineup_campus/kullanici-basina-gelir-raporu', 63, '2025-05-21 12:28:48'),
(3671, 1, 'http://localhost/lineup_campus/kullanici-basina-gelir-raporu', 50, '2025-05-21 12:29:41'),
(3672, 1, 'http://localhost/lineup_campus/kullanici-basina-gelir-raporu', 317, '2025-05-21 12:34:59'),
(3673, 1, 'http://localhost/lineup_campus/kullanici-basina-gelir-raporu', 100, '2025-05-21 12:36:42'),
(3674, 1, 'http://localhost/lineup_campus/kullanici-basina-gelir-raporu', 154, '2025-05-21 12:39:18'),
(3675, 1, 'http://localhost/lineup_campus/kullanici-basina-gelir-raporu', 7, '2025-05-21 12:39:27'),
(3676, 1, 'http://localhost/lineup_campus/kullanici-basina-gelir-raporu', 16, '2025-05-21 12:39:45'),
(3677, 1, 'http://localhost/lineup_campus/kullanici-basina-gelir-raporu', 80, '2025-05-21 12:41:07'),
(3678, 1, 'http://localhost/lineup_campus/odeme-tipi-grafik-rapor', 2, '2025-05-21 12:41:11'),
(3679, 1, 'http://localhost/lineup_campus/grafik-rapor', 5, '2025-05-21 12:41:17'),
(3680, 1, 'http://localhost/lineup_campus/kullanici-odeme-raporu', 3, '2025-05-21 12:41:23'),
(3681, 1, 'http://localhost/lineup_campus/grafik-rapor', 19, '2025-05-21 12:41:43'),
(3682, 1, 'http://localhost/lineup_campus/grafik-rapor', 235, '2025-05-21 12:45:40'),
(3683, 1, 'http://localhost/lineup_campus/grafik-rapor', 7, '2025-05-21 12:45:49'),
(3684, 1, 'http://localhost/lineup_campus/grafik-rapor', 29, '2025-05-21 12:46:38'),
(3685, 1, 'http://localhost/lineup_campus/grafik-rapor', 110, '2025-05-21 12:48:30'),
(3686, 1, 'http://localhost/lineup_campus/grafik-rapor', 15, '2025-05-21 12:48:47'),
(3687, 1, 'http://localhost/lineup_campus/kullanici-basina-gelir-raporu', 292, '2025-05-21 12:53:41'),
(3688, 1, 'http://localhost/lineup_campus/kullanici-basina-gelir-raporu', 11, '2025-05-21 12:53:55'),
(3689, 1, 'http://localhost/lineup_campus/kullanici-basina-gelir-raporu', 204, '2025-05-21 12:57:20'),
(3690, 1, 'http://localhost/lineup_campus/kullanici-basina-gelir-raporu', 1, '2025-05-21 12:57:25'),
(3691, 1, 'http://localhost/lineup_campus/paket-kullanim-durumlari', 29, '2025-05-21 12:57:56'),
(3692, 1, 'http://localhost/lineup_campus/paket-kullanim-durumlari', 66, '2025-05-21 12:59:05'),
(3693, 1, 'http://localhost/lineup_campus/paket-kullanim-durumlari', 10, '2025-05-21 12:59:17'),
(3694, 1, 'http://localhost/lineup_campus/paket-kullanim-durumlari', 35, '2025-05-21 12:59:53'),
(3695, 1, 'http://localhost/lineup_campus/paket-kullanim-durumlari', 240, '2025-05-21 13:03:55'),
(3696, 1, 'http://localhost/lineup_campus/paket-kullanim-durumlari', 3, '2025-05-21 13:04:00'),
(3697, 1, 'http://localhost/lineup_campus/paket-kullanim-durumlari', 48, '2025-05-21 13:05:03'),
(3698, 1, 'http://localhost/lineup_campus/paket-kullanim-durumlari', 127, '2025-05-21 13:07:23'),
(3699, 1, 'http://localhost/lineup_campus/paket-kullanim-durumlari', 89, '2025-05-21 13:08:55'),
(3700, 1, 'http://localhost/lineup_campus/paket-kullanim-durumlari', 95, '2025-05-21 13:10:31'),
(3701, 1, 'http://localhost/lineup_campus/paket-kullanim-durumlari', 73, '2025-05-21 13:11:53'),
(3702, 1, 'http://localhost/lineup_campus/paket-kullanim-durumlari', 9, '2025-05-21 13:12:04'),
(3703, 1, 'http://localhost/lineup_campus/paket-kullanim-durumlari', 49, '2025-05-21 13:12:55'),
(3704, 1, 'http://localhost/lineup_campus/kullanici-basina-gelir-raporu', 30, '2025-05-21 13:13:27'),
(3705, 1, 'http://localhost/lineup_campus/grafik-rapor', 6, '2025-05-21 13:13:34'),
(3706, 1, 'http://localhost/lineup_campus/kullanici-odeme-raporu', 11, '2025-05-21 13:13:48'),
(3707, 1, 'http://localhost/lineup_campus/odeme-tipi-grafik-rapor', 8, '2025-05-21 13:13:57'),
(3708, 1, 'http://localhost/lineup_campus/kullanici-basina-gelir-raporu', 6, '2025-05-21 13:14:05'),
(3709, 1, 'http://localhost/lineup_campus/paket-kullanim-durumlari', 2638, '2025-05-21 13:58:05'),
(3710, 1, 'http://localhost/lineup_campus/ayarlar', 3, '2025-05-21 13:58:10'),
(3711, 1, 'http://localhost/lineup_campus/kullanici-odeme-raporu', 1, '2025-05-21 13:58:12'),
(3712, 1, 'http://localhost/lineup_campus/ayarlar', 122, '2025-05-21 14:00:16'),
(3713, 1, 'http://localhost/lineup_campus/ayarlar', 16, '2025-05-21 14:00:34'),
(3714, 1, 'http://localhost/lineup_campus/ayarlar', 16, '2025-05-21 14:00:53'),
(3715, 1, 'http://localhost/lineup_campus/ayarlar', 15, '2025-05-21 14:01:10'),
(3716, 1, 'http://localhost/lineup_campus/ayarlar', 10, '2025-05-21 14:01:22'),
(3717, 1, 'http://localhost/lineup_campus/ayarlar', 39, '2025-05-21 14:02:03'),
(3718, 1, 'http://localhost/lineup_campus/ayarlar', 9, '2025-05-21 14:02:14'),
(3719, 1, 'http://localhost/lineup_campus/ayarlar', 65, '2025-05-21 14:03:21'),
(3720, 1, 'http://localhost/lineup_campus/ayarlar', 102, '2025-05-21 14:05:05'),
(3721, 1, 'http://localhost/lineup_campus/ayarlar', 11, '2025-05-21 14:05:19'),
(3722, 1, 'http://localhost/lineup_campus/ayarlar', 223, '2025-05-21 14:09:05'),
(3723, 1, 'http://localhost/lineup_campus/ayarlar', 49, '2025-05-21 14:09:56'),
(3724, 1, 'http://localhost/lineup_campus/ayarlar', 266, '2025-05-21 14:14:24'),
(3725, 1, 'http://localhost/lineup_campus/ayarlar', 9, '2025-05-21 14:14:35'),
(3726, 1, 'http://localhost/lineup_campus/ayarlar', 30, '2025-05-21 14:15:07'),
(3727, 1, 'http://localhost/lineup_campus/ayarlar', 24, '2025-05-21 14:15:34'),
(3728, 1, 'http://localhost/lineup_campus/ayarlar', 7, '2025-05-21 14:15:44'),
(3729, 1, 'http://localhost/lineup_campus/ayarlar', 38, '2025-05-21 14:16:24'),
(3730, 1, 'http://localhost/lineup_campus/ayarlar', 35, '2025-05-21 14:17:01'),
(3731, 1, 'http://localhost/lineup_campus/ayarlar', 90, '2025-05-21 14:18:33'),
(3732, 1, 'http://localhost/lineup_campus/ayarlar', 17, '2025-05-21 14:18:52'),
(3733, 1, 'http://localhost/lineup_campus/dashboard', 5, '2025-05-21 14:19:00'),
(3734, 1, 'http://localhost/lineup_campus/ayarlar', 0, '2025-05-21 14:19:01'),
(3735, 1, 'http://localhost/lineup_campus/kullanici-odeme-raporu', 29, '2025-05-21 14:19:32'),
(3736, 1, 'http://localhost/lineup_campus/kullanici-odeme-raporu', 60, '2025-05-21 14:20:34'),
(3737, 1, 'http://localhost/lineup_campus/kullanici-odeme-raporu', 8, '2025-05-21 14:20:45'),
(3738, 1, 'http://localhost/lineup_campus/dashboard', 213, '2025-05-21 14:24:20'),
(3739, 1, 'http://localhost/lineup_campus/dashboard', 74, '2025-05-21 14:25:36'),
(3740, 1, 'http://localhost/lineup_campus/dashboard', 31, '2025-05-21 14:26:09'),
(3741, 1, 'http://localhost/lineup_campus/dashboard', 5, '2025-05-21 14:26:15'),
(3742, 1, 'http://localhost/lineup_campus/okullar', 6, '2025-05-21 14:26:23'),
(3743, 1, 'http://localhost/lineup_campus/yas-grubu', 6, '2025-05-21 14:26:31'),
(3744, 1, 'http://localhost/lineup_campus/ayarlar', 0, '2025-05-21 14:26:33'),
(3745, 1, 'http://localhost/lineup_campus/grafik-rapor', 874, '2025-05-21 14:41:10'),
(3746, 1, 'http://localhost/lineup_campus/grafik-rapor', 40, '2025-05-21 14:41:52'),
(3747, 1, 'http://localhost/lineup_campus/grafik-rapor', 48, '2025-05-21 14:42:42'),
(3748, 1, 'http://localhost/lineup_campus/grafik-rapor', 47, '2025-05-21 14:43:31'),
(3749, 1, 'http://localhost/lineup_campus/grafik-rapor', 113, '2025-05-21 14:45:26'),
(3750, 1, 'http://localhost/lineup_campus/grafik-rapor', 62, '2025-05-21 14:46:30'),
(3751, 1, 'http://localhost/lineup_campus/grafik-rapor', 5, '2025-05-21 14:46:38'),
(3752, 1, 'http://localhost/lineup_campus/grafik-rapor', 1, '2025-05-21 14:46:40'),
(3753, 1, 'http://localhost/lineup_campus/dashboard', 54, '2025-05-21 14:47:36'),
(3754, 1, 'http://localhost/lineup_campus/dashboard', 16, '2025-05-21 14:47:54'),
(3755, 1, 'http://localhost/lineup_campus/dashboard', 45, '2025-05-21 14:48:41'),
(3756, 1, 'http://localhost/lineup_campus/dashboard', 6, '2025-05-21 15:00:17'),
(3757, 1, 'http://localhost/lineup_campus/ogrenciler', 1, '2025-05-21 15:00:20'),
(3758, 1, 'http://localhost/lineup_campus/havale-beklenenler', 82, '2025-05-21 15:01:44'),
(3759, 1, 'http://localhost/lineup_campus/havale-beklenenler.php', 143, '2025-05-21 15:04:09'),
(3760, 1, 'http://localhost/lineup_campus/havale-beklenenler.php', 1453, '2025-05-21 15:28:25'),
(3761, 1, 'http://localhost/lineup_campus/havale-beklenenler.php', 180, '2025-05-21 15:31:27'),
(3762, 1, 'http://localhost/lineup_campus/ayarlar', 1, '2025-05-21 15:31:30'),
(3763, 1, 'http://localhost/lineup_campus/kullanici-odeme-raporu', 1, '2025-05-21 15:31:33'),
(3764, 1, 'http://localhost/lineup_campus/grafik-rapor', 94, '2025-05-21 15:33:09'),
(3765, 1, 'http://localhost/lineup_campus/abonelik-b%C3%BCy%C3%BCme-oranlari', 99, '2025-05-21 15:34:50'),
(3766, 1, 'http://localhost/lineup_campus/abonelik-b%C3%BCy%C3%BCme-oranlari', 6, '2025-05-21 15:34:58'),
(3767, 1, 'http://localhost/lineup_campus/abonelik-b%C3%BCy%C3%BCme-oranlari', 16, '2025-05-21 15:35:17'),
(3768, 1, 'http://localhost/lineup_campus/abonelik-b%C3%BCy%C3%BCme-oranlari', 2, '2025-05-21 15:35:21'),
(3769, 1, 'http://localhost/lineup_campus/abonelik-b%C3%BCy%C3%BCme-oranlari', 36, '2025-05-21 15:36:00'),
(3770, 1, 'http://localhost/lineup_campus/grafik-rapor', 2, '2025-05-21 15:36:06'),
(3771, 1, 'http://localhost/lineup_campus/abonelik-buyume-oranlari', 31, '2025-05-21 15:36:39'),
(3772, 1, 'http://localhost/lineup_campus/abonelik-buyume-oranlari', 69, '2025-05-21 15:37:50'),
(3773, 1, 'http://localhost/lineup_campus/abonelik-buyume-oranlari', 331, '2025-05-21 15:43:23'),
(3774, 1, 'http://localhost/lineup_campus/abonelik-buyume-oranlari', 1, '2025-05-21 15:43:26'),
(3775, 1, 'http://localhost/lineup_campus/abonelik-kayip-oranlari', 42, '2025-05-21 15:44:10'),
(3776, 1, 'http://localhost/lineup_campus/abonelik-kayip-oranlari', 6, '2025-05-21 15:44:18'),
(3777, 1, 'http://localhost/lineup_campus/abonelik-kayip-oranlari', 51, '2025-05-21 15:45:12'),
(3778, 1, 'http://localhost/lineup_campus/abonelik-kayip-oranlari', 5, '2025-05-21 15:45:19'),
(3779, 1, 'http://localhost/lineup_campus/abonelik-kayip-oranlari', 74, '2025-05-21 15:46:37'),
(3780, 1, 'http://localhost/lineup_campus/abonelik-kayip-oranlari', 69, '2025-05-21 15:47:48'),
(3781, 1, 'http://localhost/lineup_campus/abonelik-kayip-oranlari', 27, '2025-05-21 15:48:18'),
(3782, 1, 'http://localhost/lineup_campus/abonelik-kayip-oranlari', 13, '2025-05-21 15:48:33'),
(3783, 1, 'http://localhost/lineup_campus/abonelik-kayip-oranlari', 4, '2025-05-21 15:48:39'),
(3784, 1, 'http://localhost/lineup_campus/abonelik-kayip-oranlari', 30, '2025-05-21 15:49:12'),
(3785, 1, 'http://localhost/lineup_campus/abonelik-kayip-oranlari', 212, '2025-05-21 15:52:46'),
(3786, 1, 'http://localhost/lineup_campus/abonelik-kayip-oranlari', 2, '2025-05-21 15:52:50'),
(3787, 1, 'http://localhost/lineup_campus/abonelik-buyume-oranlari', 1, '2025-05-21 15:52:52'),
(3788, 1, 'http://localhost/lineup_campus/paket-kullanim-durumlari', 2, '2025-05-21 15:52:56'),
(3789, 1, 'http://localhost/lineup_campus/abonelik-buyume-oranlari', 2, '2025-05-21 15:53:00'),
(3790, 1, 'http://localhost/lineup_campus/abonelik-kayip-oranlari', 3, '2025-05-21 15:53:05'),
(3791, 1, 'http://localhost/lineup_campus/ayarlar', 2, '2025-05-21 15:53:09'),
(3792, 1, 'http://localhost/lineup_campus/kullanici-odeme-raporu', 107, '2025-05-21 15:54:58'),
(3793, 1, 'http://localhost/lineup_campus/grafik-rapor', 30, '2025-05-21 15:55:30'),
(3794, 1, 'http://localhost/lineup_campus/grafik-rapor', 8, '2025-05-21 15:55:41'),
(3795, 1, 'http://localhost/lineup_campus/ayarlar', 0, '2025-05-21 15:55:43'),
(3796, 1, 'http://localhost/lineup_campus/kullanici-odeme-raporu', 1, '2025-05-21 15:55:46'),
(3797, 1, 'http://localhost/lineup_campus/grafik-rapor', 1, '2025-05-21 15:55:48'),
(3798, 1, 'http://localhost/lineup_campus/odeme-tipi-grafik-rapor', 7, '2025-05-21 15:55:58'),
(3799, 1, 'http://localhost/lineup_campus/kullanici-basina-gelir-raporu', 5, '2025-05-21 15:56:05'),
(3800, 1, 'http://localhost/lineup_campus/kullanici-basina-gelir-raporu', 6, '2025-05-21 15:56:06'),
(3801, 1, 'http://localhost/lineup_campus/paket-kullanim-durumlari', 2, '2025-05-21 15:56:10'),
(3802, 1, 'http://localhost/lineup_campus/abonelik-buyume-oranlari', 6, '2025-05-21 15:56:18'),
(3803, 1, 'http://localhost/lineup_campus/abonelik-kayip-oranlari', 2, '2025-05-21 15:56:22'),
(3804, 1, 'http://localhost/lineup_campus/abonelik-buyume-oranlari', 46, '2025-05-21 15:57:10'),
(3805, 1, 'http://localhost/lineup_campus/abonelik-buyume-oranlari', 5, '2025-05-21 15:57:17'),
(3806, 1, 'http://localhost/lineup_campus/paket-kullanim-durumlari', 1, '2025-05-21 15:57:20'),
(3807, 1, 'http://localhost/lineup_campus/kullanici-basina-gelir-raporu', 7, '2025-05-21 15:57:29'),
(3808, 1, 'http://localhost/lineup_campus/abonelik-kayip-oranlari', 60, '2025-05-21 15:58:31'),
(3809, 1, 'http://localhost/lineup_campus/ayarlar', 0, '2025-05-21 15:58:33'),
(3810, 1, 'http://localhost/lineup_campus/kullanici-odeme-raporu', 1, '2025-05-21 15:58:36'),
(3811, 1, 'http://localhost/lineup_campus/grafik-rapor', 1, '2025-05-21 15:58:39'),
(3812, 1, 'http://localhost/lineup_campus/odeme-tipi-grafik-rapor', 3, '2025-05-21 15:58:44'),
(3813, 1, 'http://localhost/lineup_campus/kullanici-basina-gelir-raporu', 10, '2025-05-21 15:58:55'),
(3814, 1, 'http://localhost/lineup_campus/paket-kullanim-durumlari', 4, '2025-05-21 15:59:02'),
(3815, 1, 'http://localhost/lineup_campus/abonelik-buyume-oranlari', 3, '2025-05-21 15:59:06'),
(3816, 1, 'http://localhost/lineup_campus/abonelik-kayip-oranlari', 134, '2025-05-21 16:01:22'),
(3817, 1, 'http://localhost/lineup_campus/kullanici-odeme-raporu', 0, '2025-05-21 16:01:24'),
(3818, 1, 'http://localhost/lineup_campus/grafik-rapor', 74, '2025-05-21 16:02:40'),
(3819, 1, 'http://localhost/lineup_campus/oyunlar', 3, '2025-05-21 16:02:45'),
(3820, 1, 'http://localhost/lineup_campus/okul-detay/oyun-deneme', 17, '2025-05-21 16:03:03'),
(3821, 1, 'http://localhost/lineup_campus/dashboard', 121, '2025-05-21 16:05:06'),
(3822, 1, 'http://localhost/lineup_campus/dashboard', 4, '2025-05-21 16:05:13'),
(3823, 1, 'http://localhost/lineup_campus/dashboard', 29, '2025-05-21 16:05:44'),
(3824, 1, 'http://localhost/lineup_campus/dashboard', 43, '2025-05-21 16:06:29'),
(3825, 1, 'http://localhost/lineup_campus/dashboard', 27, '2025-05-21 16:06:58'),
(3826, 1, 'http://localhost/lineup_campus/dashboard', 14, '2025-05-21 16:07:14'),
(3827, 1, 'http://localhost/lineup_campus/account/overview.html', 4, '2025-05-21 16:07:19'),
(3828, 1, 'http://localhost/lineup_campus/dashboard', 55, '2025-05-21 16:07:55'),
(3829, 1, 'http://localhost/lineup_campus/dashboard', 58, '2025-05-21 16:08:54'),
(3830, 1, 'http://localhost/lineup_campus/ana-okulu-icerikler', 10, '2025-05-21 16:09:06'),
(3831, 1, 'http://localhost/lineup_campus/ana-okulu-icerikler-detay.php?id=96', 115, '2025-05-21 16:11:04'),
(3832, 1, 'http://localhost/lineup_campus/ana-okulu-icerikler-detay.php?id=96', 248, '2025-05-21 16:15:14'),
(3833, 1, 'http://localhost/lineup_campus/paketler', 6, '2025-05-21 16:15:22'),
(3834, 1, 'http://localhost/lineup_campus/paketler', 41, '2025-05-21 16:16:05'),
(3835, 1, 'http://localhost/lineup_campus/dashboard', 28, '2025-05-21 16:16:35'),
(3836, 1, 'http://localhost/lineup_campus/dashboard', 15, '2025-05-21 16:16:52'),
(3837, 1, 'http://localhost/lineup_campus/dashboard', 438, '2025-05-21 16:24:12'),
(3838, 1, 'http://localhost/lineup_campus/dashboard', 610, '2025-05-21 16:34:24'),
(3839, 1, 'http://localhost/lineup_campus/dashboard', 3, '2025-05-21 16:34:29'),
(3840, 1, 'http://localhost/lineup_campus/kullanici-yetkilendirme', 8, '2025-05-21 16:34:39'),
(3841, 1, 'http://localhost/lineup_campus/kullanici-gruplari', 9, '2025-05-21 16:34:51'),
(3842, 1, 'http://localhost/lineup_campus/kullanici-grup-detay?q=ana-okulu-ogrencisi', 10, '2025-05-21 16:35:02'),
(3843, 1, 'http://localhost/lineup_campus/dashboard', 108, '2025-05-21 16:36:52'),
(3844, 1, 'http://localhost/lineup_campus/dashboard', 83, '2025-05-21 16:38:17'),
(3845, 1, 'http://localhost/lineup_campus/dashboard', 31, '2025-05-21 16:38:50'),
(3846, 1, 'http://localhost/lineup_campus/ana-okulu-icerikler', 0, '2025-05-21 16:38:52'),
(3847, 1, 'http://localhost/lineup_campus/onemli-haftalar', 2, '2025-05-21 16:38:56'),
(3848, 1, 'http://localhost/lineup_campus/paketler', 208, '2025-05-21 16:42:25'),
(3849, 1, 'http://localhost/lineup_campus/dashboard', 189, '2025-05-21 16:45:36'),
(3850, 1, 'http://localhost/lineup_campus/dashboard', 188, '2025-05-21 16:48:47'),
(3851, 1, 'http://localhost/lineup_campus/dashboard', 5, '2025-05-21 16:48:54'),
(3852, 1, 'http://localhost/lineup_campus/okullar', 7, '2025-05-21 16:49:03'),
(3853, 1, 'http://localhost/lineup_campus/grafik-rapor', 25, '2025-05-21 16:49:30'),
(3854, 1, 'http://localhost/lineup_campus/dashboard', 4, '2025-05-21 16:49:36'),
(3855, 1, 'http://localhost/lineup_campus/ayarlar', 3, '2025-05-21 16:49:41'),
(3856, 1, 'http://localhost/lineup_campus/kullanici-odeme-raporu', 0, '2025-05-21 16:49:43'),
(3857, 1, 'http://localhost/lineup_campus/grafik-rapor', 3, '2025-05-21 16:49:48'),
(3858, 1, 'http://localhost/lineup_campus/abonelik-kayip-oranlari', 0, '2025-05-21 16:49:50'),
(3859, 1, 'http://localhost/lineup_campus/abonelik-buyume-oranlari', 1, '2025-05-21 16:49:54'),
(3860, 1, 'http://localhost/lineup_campus/paket-kullanim-durumlari', 14, '2025-05-21 16:50:09'),
(3861, 1, 'http://localhost/lineup_campus/yas-grubu', 3, '2025-05-21 16:50:14'),
(3862, 1, 'http://localhost/lineup_campus/dashboard', 11871, '2025-05-21 20:08:07'),
(3863, 1, 'http://localhost/lineup_campus/dashboard', 61, '2025-05-21 20:09:10'),
(3864, 1, 'http://localhost/lineup_campus/paketler', 234, '2025-05-21 20:13:06'),
(3865, 1, 'http://localhost/lineup_campus/dashboard', 21, '2025-05-21 20:13:29'),
(3866, 1, 'http://localhost/lineup_campus/dashboard', 18, '2025-05-21 20:13:48'),
(3867, 1, 'http://localhost/lineup_campus/dashboard', 2, '2025-05-21 20:13:52'),
(3868, 1, 'http://localhost/lineup_campus/abonelik-kayip-oranlari', 32, '2025-05-21 20:14:26'),
(3869, 1, 'http://localhost/lineup_campus/paket-kullanim-durumlari', 1, '2025-05-21 20:14:29'),
(3870, 1, 'http://localhost/lineup_campus/kullanici-basina-gelir-raporu', 10, '2025-05-21 20:14:41'),
(3871, 1, 'http://localhost/lineup_campus/odeme-tipi-grafik-rapor', 7, '2025-05-21 20:14:50'),
(3872, 1, 'http://localhost/lineup_campus/ayarlar', 2, '2025-05-21 20:14:54'),
(3873, 1, 'http://localhost/lineup_campus/kullanici-odeme-raporu', 25, '2025-05-21 20:15:21'),
(3874, 1, 'http://localhost/lineup_campus/dashboard', 230, '2025-05-21 20:19:13'),
(3875, 1, 'http://localhost/lineup_campus/dashboard', 5, '2025-05-21 20:19:19'),
(3876, 1, 'http://localhost/lineup_campus/api-ayarlar', 39, '2025-05-21 20:20:44'),
(3877, 1, 'http://localhost/lineup_campus/abonelik-kayip-oranlari', 0, '2025-05-21 20:20:46'),
(3878, 1, 'http://localhost/lineup_campus/ayarlar', 54, '2025-05-21 20:21:42'),
(3879, 1, 'http://localhost/lineup_campus/ayarlar', 39, '2025-05-21 20:22:23'),
(3880, 1, 'http://localhost/lineup_campus/ayarlar', 30, '2025-05-21 20:22:54'),
(3881, 1, 'http://localhost/lineup_campus/ayarlar', 81, '2025-05-21 20:24:18'),
(3882, 1, 'http://localhost/lineup_campus/ayarlar', 9, '2025-05-21 20:24:29'),
(3883, 1, 'http://localhost/lineup_campus/ayarlar', 29, '2025-05-21 20:25:00'),
(3884, 1, 'http://localhost/lineup_campus/ayarlar', 11, '2025-05-21 20:25:13'),
(3885, 1, 'http://localhost/lineup_campus/ayarlar', 8, '2025-05-21 20:25:24'),
(3886, 1, 'http://localhost/lineup_campus/ayarlar', 119, '2025-05-21 20:27:24'),
(3887, 1, 'http://localhost/lineup_campus/ayarlar', 16, '2025-05-21 20:27:42'),
(3888, 1, 'http://localhost/lineup_campus/ayarlar', 14, '2025-05-21 20:27:58'),
(3889, 1, 'http://localhost/lineup_campus/ayarlar', 171, '2025-05-21 20:30:51'),
(3890, 1, 'http://localhost/lineup_campus/ayarlar', 28, '2025-05-21 20:31:22'),
(3891, 1, 'http://localhost/lineup_campus/ayarlar', 149, '2025-05-21 20:33:52'),
(3892, 1, 'http://localhost/lineup_campus/ayarlar', 569, '2025-05-21 20:43:24'),
(3893, 1, 'http://localhost/lineup_campus/kullanici-odeme-raporu', 3, '2025-05-21 20:43:29'),
(3894, 1, 'http://localhost/lineup_campus/grafik-rapor', 2, '2025-05-21 20:43:33'),
(3895, 1, 'http://localhost/lineup_campus/odeme-tipi-grafik-rapor', 313, '2025-05-21 20:48:48'),
(3896, 1, 'http://localhost/lineup_campus/ayarlar', 79, '2025-05-21 20:50:09'),
(3897, 1, 'http://localhost/lineup_campus/kullanici-odeme-raporu', 14, '2025-05-21 20:50:25'),
(3898, 1, 'http://localhost/lineup_campus/abonelik-kayip-oranlari', 1, '2025-05-21 20:50:28'),
(3899, 1, 'http://localhost/lineup_campus/abonelik-buyume-oranlari', 1, '2025-05-21 20:50:31'),
(3900, 1, 'http://localhost/lineup_campus/paket-kullanim-durumlari', 2, '2025-05-21 20:50:35'),
(3901, 1, 'http://localhost/lineup_campus/kullanici-basina-gelir-raporu', 6, '2025-05-21 20:50:42'),
(3902, 1, 'http://localhost/lineup_campus/ayarlar', 1, '2025-05-21 20:50:46'),
(3903, 1, 'http://localhost/lineup_campus/kullanici-odeme-raporu', 1, '2025-05-21 20:50:49'),
(3904, 1, 'http://localhost/lineup_campus/grafik-rapor', 3, '2025-05-21 20:50:54'),
(3905, 1, 'http://localhost/lineup_campus/odeme-tipi-grafik-rapor', 6, '2025-05-21 20:51:02'),
(3906, 1, 'http://localhost/lineup_campus/api-ayarlar', 947, '2025-05-21 21:06:51'),
(3907, 1, 'http://localhost/lineup_campus/api-ayarlar', 23, '2025-05-21 21:07:17'),
(3908, 1, 'http://localhost/lineup_campus/api-ayarlar', 14, '2025-05-21 21:07:33'),
(3909, 1, 'http://localhost/lineup_campus/api-ayarlar', 47, '2025-05-21 21:08:22'),
(3910, 1, 'http://localhost/lineup_campus/ayarlar', 170, '2025-05-21 21:11:14'),
(3911, 1, 'http://localhost/lineup_campus/kullanici-odeme-raporu', 222, '2025-05-21 21:14:58'),
(3912, 1, 'http://localhost/lineup_campus/kullanici-odeme-raporu', 1, '2025-05-21 21:15:01'),
(3913, 1, 'http://localhost/lineup_campus/kullanici-odeme-raporu', 19, '2025-05-21 21:15:19'),
(3914, 1, 'http://localhost/lineup_campus/kullanici-odeme-raporu', 0, '2025-05-21 21:15:21'),
(3915, 1, 'http://localhost/lineup_campus/kullanici-odeme-raporu', 64, '2025-05-21 21:16:25'),
(3916, 1, 'http://localhost/lineup_campus/kullanici-odeme-raporu', 3, '2025-05-21 21:23:40'),
(3917, 1, 'http://localhost/lineup_campus/kullanici-odeme-raporu', 114, '2025-05-21 21:25:31'),
(3918, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-yonetimi', 39, '2025-05-21 21:26:12'),
(3919, 1, 'http://localhost/lineup_campus/api-ayarlar', 419, '2025-05-21 21:33:14'),
(3920, 1, 'http://localhost/lineup_campus/api-ayarlar', 8, '2025-05-21 21:33:24'),
(3921, 1, 'http://localhost/lineup_campus/dashboard', 1, '2025-05-21 21:33:27'),
(3922, 1, 'http://localhost/lineup_campus/api-ayarlar', 19, '2025-05-21 21:33:48'),
(3923, 1, 'http://localhost/lineup_campus/api-ayarlar', 64, '2025-05-21 21:34:54'),
(3924, 1, 'http://localhost/lineup_campus/api-ayarlar', 77, '2025-05-21 21:36:14'),
(3925, 1, 'http://localhost/lineup_campus/api-ayarlar', 4, '2025-05-21 21:36:20'),
(3926, 1, 'http://localhost/lineup_campus/api-ayarlar', 15, '2025-05-21 21:36:37'),
(3927, 1, 'http://localhost/lineup_campus/api-ayarlar', 65, '2025-05-21 21:37:44'),
(3928, 1, 'http://localhost/lineup_campus/api-ayarlar', 5, '2025-05-21 21:37:51'),
(3929, 1, 'http://localhost/lineup_campus/api-ayarlar', 19, '2025-05-21 21:38:13'),
(3930, 1, 'http://localhost/lineup_campus/api-ayarlar', 3, '2025-05-21 21:38:18'),
(3931, 1, 'http://localhost/lineup_campus/api-ayarlar', 6, '2025-05-21 21:38:26'),
(3932, 1, 'http://localhost/lineup_campus/api-ayarlar', 53, '2025-05-21 21:39:21'),
(3933, 1, 'http://localhost/lineup_campus/ayarlar', 142, '2025-05-21 21:41:45'),
(3934, 1, 'http://localhost/lineup_campus/ayarlar', 89, '2025-05-21 21:43:16'),
(3935, 1, 'http://localhost/lineup_campus/ayarlar', 74, '2025-05-21 21:44:32');
INSERT INTO `timespend_lnp` (`id`, `user_id`, `sayfa_url`, `timeSpent`, `exitTime`) VALUES
(3936, 1, 'http://localhost/lineup_campus/ayarlar', 12, '2025-05-21 21:44:46'),
(3937, 1, 'http://localhost/lineup_campus/ayarlar', 13, '2025-05-21 21:45:02'),
(3938, 1, 'http://localhost/lineup_campus/ayarlar', 132, '2025-05-21 21:47:15'),
(3939, 1, 'http://localhost/lineup_campus/ayarlar', 322, '2025-05-21 21:52:39'),
(3940, 1, 'http://localhost/lineup_campus/ayarlar', 3, '2025-05-21 21:52:44'),
(3941, 1, 'http://localhost/lineup_campus/ayarlar', 5, '2025-05-21 21:52:51'),
(3942, 1, 'http://localhost/lineup_campus/ayarlar', 74, '2025-05-21 21:54:08'),
(3943, 1, 'http://localhost/lineup_campus/ayarlar', 141, '2025-05-21 21:56:30'),
(3944, 1, 'http://localhost/lineup_campus/ayarlar', 8, '2025-05-21 21:56:40'),
(3945, 1, 'http://localhost/lineup_campus/ayarlar', 13, '2025-05-21 21:56:55'),
(3946, 1, 'http://localhost/lineup_campus/ayarlar', 12, '2025-05-21 21:57:09'),
(3947, 1, 'http://localhost/lineup_campus/ayarlar', 2, '2025-05-21 21:57:13'),
(3948, 1, 'http://localhost/lineup_campus/ayarlar', 0, '2025-05-21 21:57:16'),
(3949, 1, 'http://localhost/lineup_campus/ayarlar', 102, '2025-05-21 21:58:59'),
(3950, 1, 'http://localhost/lineup_campus/ayarlar', 36, '2025-05-21 21:59:37'),
(3951, 1, 'http://localhost/lineup_campus/ayarlar', 13, '2025-05-21 21:59:52'),
(3952, 1, 'http://localhost/lineup_campus/ayarlar', 30, '2025-05-21 22:00:24'),
(3953, 1, 'http://localhost/lineup_campus/ayarlar', 20, '2025-05-21 22:00:45'),
(3954, 1, 'http://localhost/lineup_campus/ayarlar', 55, '2025-05-21 22:01:42'),
(3955, 1, 'http://localhost/lineup_campus/dashboard', 11, '2025-05-21 22:01:55'),
(3956, 1, 'http://localhost/lineup_campus/dashboard', 30, '2025-05-21 22:02:26'),
(3957, 1, 'http://localhost/lineup_campus/ayarlar', 46, '2025-05-21 22:03:15'),
(3958, 1, 'http://localhost/lineup_campus/ayarlar', 6, '2025-05-21 22:03:23'),
(3959, 1, 'http://localhost/lineup_campus/api-ayarlar', 124, '2025-05-21 22:05:28'),
(3960, 1, 'http://localhost/lineup_campus/api-ayarlar', 199, '2025-05-21 22:08:49'),
(3961, 1, 'http://localhost/lineup_campus/api-ayarlar', 3, '2025-05-21 22:08:55'),
(3962, 1, 'http://localhost/lineup_campus/paket-bitisi-yaklasan-aboneler', 206, '2025-05-21 22:12:23'),
(3963, 1, 'http://localhost/lineup_campus/paket-bitisi-yaklasan-aboneler', 101, '2025-05-21 22:14:06'),
(3964, 1, 'http://localhost/lineup_campus/paket-bitisi-yaklasan-aboneler', 104, '2025-05-21 22:15:53'),
(3965, 1, 'http://localhost/lineup_campus/paket-bitisi-yaklasan-aboneler', 66, '2025-05-21 22:17:03'),
(3966, 1, 'http://localhost/lineup_campus/paket-bitisi-yaklasan-aboneler', 190, '2025-05-21 22:22:12'),
(3967, 1, 'http://localhost/lineup_campus/paket-bitisi-yaklasan-aboneler', 132, '2025-05-21 22:30:55'),
(3968, 1, 'http://localhost/lineup_campus/paket-bitisi-yaklasan-aboneler', 187, '2025-05-21 22:45:05'),
(3969, 1, 'http://localhost/lineup_campus/paket-bitisi-yaklasan-aboneler', 293, '2025-05-21 22:50:00'),
(3970, 1, 'http://localhost/lineup_campus/paket-bitisi-yaklasan-aboneler', 54, '2025-05-21 22:50:55'),
(3971, 1, 'http://localhost/lineup_campus/paket-bitisi-yaklasan-aboneler', 88, '2025-05-21 22:52:25'),
(3972, 1, 'http://localhost/lineup_campus/paket-bitisi-yaklasan-aboneler', 238, '2025-05-21 22:56:25'),
(3973, 1, 'http://localhost/lineup_campus/dashboard', 13, '2025-05-21 22:56:40'),
(3974, 1, 'http://localhost/lineup_campus/paket-bitisi-yaklasan-aboneler', 80, '2025-05-21 22:58:02'),
(3975, 1, 'http://localhost/lineup_campus/abonelik-kayip-oranlari', 4, '2025-05-21 22:58:09'),
(3976, 1, 'http://localhost/lineup_campus/api-ayarlar', 5, '2025-05-21 22:58:16'),
(3977, 1, 'http://localhost/lineup_campus/ayarlar', 66, '2025-05-21 22:59:24'),
(3978, 1, 'http://localhost/lineup_campus/ayarlar', 68, '2025-05-21 22:59:25'),
(3979, 1, 'http://localhost/lineup_campus/ayarlar', 119, '2025-05-21 23:01:26'),
(3980, 1, 'http://localhost/lineup_campus/kullanici-odeme-raporu', 1, '2025-05-21 23:01:29'),
(3981, 1, 'http://localhost/lineup_campus/kullanici-odeme-raporu', 45, '2025-05-21 23:02:13'),
(3982, 1, 'http://localhost/lineup_campus/grafik-rapor', 6592, '2025-05-22 00:52:07'),
(3983, 1, 'http://localhost/lineup_campus/paket-bitisi-yaklasan-aboneler', 1512, '2025-05-22 01:17:21'),
(3984, 1, 'http://localhost/lineup_campus/paket-bitisi-yaklasan-aboneler', 2, '2025-05-22 01:17:24'),
(3985, 1, 'http://localhost/lineup_campus/fatura-excel-rapor', 54, '2025-05-22 01:18:20'),
(3986, 1, 'http://localhost/lineup_campus/fatura-excel-rapor', 60, '2025-05-22 01:19:22'),
(3987, 1, 'http://localhost/lineup_campus/fatura-excel-rapor', 467, '2025-05-22 01:27:12'),
(3988, 1, 'http://localhost/lineup_campus/fatura-excel-rapor', 170, '2025-05-22 01:30:04'),
(3989, 1, 'http://localhost/lineup_campus/fatura-excel-rapor', 46, '2025-05-22 01:30:52'),
(3990, 1, 'http://localhost/lineup_campus/fatura-excel-rapor', 188, '2025-05-22 01:34:02'),
(3991, 1, 'http://localhost/lineup_campus/kullanici-odeme-raporu', 40, '2025-05-22 01:34:44'),
(3992, 1, 'http://localhost/lineup_campus/kullanici-odeme-raporu', 3, '2025-05-22 01:34:49'),
(3993, 1, 'http://localhost/lineup_campus/fatura-excel-rapor', 244, '2025-05-22 01:38:55'),
(3994, 1, 'http://localhost/lineup_campus/fatura-excel-rapor', 2, '2025-05-22 01:38:59'),
(3995, 1, 'http://localhost/lineup_campus/fatura-excel-rapor', 39, '2025-05-22 01:39:36'),
(3996, 1, 'http://localhost/lineup_campus/fatura-excel-rapor', 3, '2025-05-22 01:39:41'),
(3997, 1, 'http://localhost/lineup_campus/fatura-excel-rapor', 63, '2025-05-22 01:40:41'),
(3998, 1, 'http://localhost/lineup_campus/fatura-excel-rapor', 10, '2025-05-22 01:40:53'),
(3999, 1, 'http://localhost/lineup_campus/fatura-excel-rapor', 382, '2025-05-22 01:47:06'),
(4000, 1, 'http://localhost/lineup_campus/fatura-excel-rapor', 444, '2025-05-22 01:48:08'),
(4001, 1, 'http://localhost/lineup_campus/fatura-excel-rapor', 549, '2025-05-22 01:49:52'),
(4002, 1, 'http://localhost/lineup_campus/kullanici-odeme-raporu', 3, '2025-05-22 01:52:00'),
(4003, 1, 'http://localhost/lineup_campus/fatura-excel-rapor', 6, '2025-05-22 01:52:08'),
(4004, 1, 'http://localhost/lineup_campus/fatura-excel-rapor', 48, '2025-05-22 01:52:50'),
(4005, 1, 'http://localhost/lineup_campus/fatura-excel-rapor', 151, '2025-05-22 01:54:33'),
(4006, 1, 'http://localhost/lineup_campus/fatura-excel-rapor', 0, '2025-05-22 01:55:41'),
(4007, 1, 'http://localhost/lineup_campus/kullanici-odeme-raporu', 1, '2025-05-22 01:55:44'),
(4008, 1, 'http://localhost/lineup_campus/fatura-excel-rapor', 1, '2025-05-22 01:55:47'),
(4009, 1, 'http://localhost/lineup_campus/fatura-excel-rapor', 46, '2025-05-22 01:56:32'),
(4010, 1, 'http://localhost/lineup_campus/fatura-excel-rapor', 186, '2025-05-22 01:58:52'),
(4011, 1, 'http://localhost/lineup_campus/fatura-excel-rapor', 336, '2025-05-22 02:01:22'),
(4012, 1, 'http://localhost/lineup_campus/fatura-excel-rapor', 494, '2025-05-22 02:03:59'),
(4013, 1, 'http://localhost/lineup_campus/fatura-excel-rapor', 585, '2025-05-22 02:05:31'),
(4014, 1, 'http://localhost/lineup_campus/fatura-excel-rapor', 690, '2025-05-22 02:07:16'),
(4015, 1, 'http://localhost/lineup_campus/fatura-excel-rapor', 748, '2025-05-22 02:08:14'),
(4016, 1, 'http://localhost/lineup_campus/fatura-excel-rapor', 813, '2025-05-22 02:09:19'),
(4017, 1, 'http://localhost/lineup_campus/fatura-excel-rapor', 935, '2025-05-22 02:11:21'),
(4018, 1, 'http://localhost/lineup_campus/fatura-excel-rapor', 960, '2025-05-22 02:11:46'),
(4019, 1, 'http://localhost/lineup_campus/fatura-excel-rapor', 1063, '2025-05-22 02:13:29'),
(4020, 1, 'http://localhost/lineup_campus/fatura-excel-rapor', 7, '2025-05-22 02:13:38'),
(4021, 1, 'http://localhost/lineup_campus/fatura-excel-rapor', 70, '2025-05-22 02:14:41'),
(4022, 1, 'http://localhost/lineup_campus/fatura-excel-rapor', 178, '2025-05-22 02:16:29'),
(4023, 1, 'http://localhost/lineup_campus/fatura-excel-rapor', 299, '2025-05-22 02:18:31'),
(4024, 1, 'http://localhost/lineup_campus/fatura-excel-rapor', 405, '2025-05-22 02:20:16'),
(4025, 1, 'http://localhost/lineup_campus/fatura-excel-rapor', 450, '2025-05-22 02:21:01'),
(4026, 1, 'http://localhost/lineup_campus/fatura-excel-rapor', 474, '2025-05-22 02:21:26'),
(4027, 1, 'http://localhost/lineup_campus/fatura-excel-rapor', 496, '2025-05-22 02:21:47'),
(4028, 1, 'http://localhost/lineup_campus/fatura-excel-rapor', 604, '2025-05-22 02:23:36'),
(4029, 1, 'http://localhost/lineup_campus/fatura-excel-rapor', 881, '2025-05-22 02:28:12'),
(4030, 1, 'http://localhost/lineup_campus/ayarlar', 164, '2025-05-22 02:30:58'),
(4031, 1, 'http://localhost/lineup_campus/fatura-excel-rapor', 7, '2025-05-22 02:31:07'),
(4032, 1, 'http://localhost/lineup_campus/fatura-excel-rapor', 93, '2025-05-22 02:32:33'),
(4033, 1, 'http://localhost/lineup_campus/fatura-excel-rapor', 163, '2025-05-22 02:33:43'),
(4034, 1, 'http://localhost/lineup_campus/fatura-excel-rapor', 179, '2025-05-22 02:34:00'),
(4035, 1, 'http://localhost/lineup_campus/fatura-excel-rapor', 493, '2025-05-22 02:39:14'),
(4036, 1, 'http://localhost/lineup_campus/dashboard', 13, '2025-05-22 03:13:54'),
(4037, 1, 'http://localhost/lineup_campus/api-ayarlar', 508, '2025-05-22 03:22:24'),
(4038, 1, 'http://localhost/lineup_campus/api-ayarlar', 0, '2025-05-22 03:22:27'),
(4039, 1, 'http://localhost/lineup_campus/dashboard', 0, '2025-05-22 03:22:28'),
(4040, 1, 'http://localhost/lineup_campus/fatura-excel-rapor', 9, '2025-05-22 08:45:13'),
(4041, 1, 'http://localhost/lineup_campus/onemli-haftalar', 556, '2025-05-22 08:54:31'),
(4042, 1, 'http://localhost/lineup_campus/onemli-haftalar', 157, '2025-05-22 08:57:10'),
(4043, 1, 'http://localhost/lineup_campus/onemli-haftalar', 397, '2025-05-22 09:03:49'),
(4044, 1, 'http://localhost/lineup_campus/ayarlar', 665, '2025-05-22 09:14:56'),
(4045, 1, 'http://localhost/lineup_campus/dashboard', 3, '2025-05-22 09:15:00'),
(4046, 1, 'http://localhost/lineup_campus/fatura-excel-rapor', 62, '2025-05-22 09:16:04'),
(4047, 1, 'http://localhost/lineup_campus/fatura-excel-rapor', 76, '2025-05-22 09:16:19'),
(4048, 1, 'http://localhost/lineup_campus/paket-bitisi-yaklasan-aboneler', 28, '2025-05-22 09:16:49'),
(4049, 1, 'http://localhost/lineup_campus/abonelik-kayip-oranlari', 13, '2025-05-22 09:17:04'),
(4050, 1, 'http://localhost/lineup_campus/abonelik-buyume-oranlari', 36, '2025-05-22 09:17:42'),
(4051, 1, 'http://localhost/lineup_campus/paket-kullanim-durumlari', 20, '2025-05-22 09:18:04'),
(4052, 1, 'http://localhost/lineup_campus/kullanici-basina-gelir-raporu', 34, '2025-05-22 09:18:41'),
(4053, 1, 'http://localhost/lineup_campus/odeme-tipi-grafik-rapor', 37, '2025-05-22 09:19:20'),
(4054, 1, 'http://localhost/lineup_campus/grafik-rapor', 14, '2025-05-22 09:19:36'),
(4055, 1, 'http://localhost/lineup_campus/kullanici-odeme-raporu', 21, '2025-05-22 09:19:59'),
(4056, 1, 'http://localhost/lineup_campus/ayarlar', 44, '2025-05-22 09:20:44'),
(4057, 1, 'http://localhost/lineup_campus/api-ayarlar', 46, '2025-05-22 09:21:32'),
(4058, 1, 'http://localhost/lineup_campus/dashboard', 23, '2025-05-22 09:21:57'),
(4059, 1, 'http://localhost/lineup_campus/fatura-excel-rapor', 3382, '2025-05-22 10:18:21'),
(4060, 1, 'http://localhost/lineup_campus/fatura-excel-rapor', 50, '2025-05-22 10:19:12'),
(4061, 1, 'http://localhost/lineup_campus/ayarlar', 239, '2025-05-22 10:23:13'),
(4062, 1, 'http://localhost/lineup_campus/ayarlar', 67, '2025-05-22 10:24:22'),
(4063, 1, 'http://localhost/lineup_campus/ayarlar', 38, '2025-05-22 10:25:02'),
(4064, 1, 'http://localhost/lineup_campus/ayarlar', 19, '2025-05-22 10:25:23'),
(4065, 1, 'http://localhost/lineup_campus/ayarlar', 197, '2025-05-22 10:28:42'),
(4066, 1, 'http://localhost/lineup_campus/ayarlar', 33, '2025-05-22 10:29:17'),
(4067, 1, 'http://localhost/lineup_campus/ayarlar', 2, '2025-05-22 10:29:21'),
(4068, 1, 'http://localhost/lineup_campus/ayarlar', 434, '2025-05-22 10:36:38'),
(4069, 1, 'http://localhost/lineup_campus/ayarlar', 37, '2025-05-22 10:37:17'),
(4070, 1, 'http://localhost/lineup_campus/ayarlar', 28, '2025-05-22 10:37:47'),
(4071, 1, 'http://localhost/lineup_campus/ayarlar', 23, '2025-05-22 10:38:12'),
(4072, 1, 'http://localhost/lineup_campus/ayarlar', 14, '2025-05-22 10:38:27'),
(4073, 1, 'http://localhost/lineup_campus/ayarlar', 16, '2025-05-22 10:38:45'),
(4074, 1, 'http://localhost/lineup_campus/ayarlar', 21, '2025-05-22 10:39:08'),
(4075, 1, 'http://localhost/lineup_campus/ayarlar', 27, '2025-05-22 10:39:37'),
(4076, 1, 'http://localhost/lineup_campus/ayarlar', 144, '2025-05-22 10:42:03'),
(4077, 1, 'http://localhost/lineup_campus/ayarlar', 55, '2025-05-22 10:43:00'),
(4078, 1, 'http://localhost/lineup_campus/ayarlar', 240, '2025-05-22 10:47:02'),
(4079, 1, 'http://localhost/lineup_campus/grafik-rapor', 7, '2025-05-22 10:47:11'),
(4080, 1, 'http://localhost/lineup_campus/odeme-tipi-grafik-rapor', 147, '2025-05-22 10:49:41'),
(4081, 1, 'http://localhost/lineup_campus/odeme-tipi-grafik-rapor', 332, '2025-05-22 10:55:14'),
(4082, 1, 'http://localhost/lineup_campus/ayarlar', 885, '2025-05-22 11:10:01'),
(4083, 1, 'http://localhost/lineup_campus/dashboard', 194, '2025-05-22 11:13:17'),
(4084, 1, 'http://localhost/lineup_campus/dashboard', 3, '2025-05-22 15:26:04'),
(4085, 1, 'http://localhost/lineup_campus/dashboard', 7, '2025-05-22 15:26:12'),
(4086, 1, 'http://localhost/lineup_campus/ana-okulu-icerikler', 98, '2025-05-22 15:27:53'),
(4087, 1, 'http://localhost/lineup_campus/dashboard', 3, '2025-05-22 15:27:58'),
(4088, 1, 'http://localhost/lineup_campus/dashboard', 711, '2025-05-22 15:39:51'),
(4089, 1, 'http://localhost/lineup_campus/account/overview.html', 14, '2025-05-22 15:40:07'),
(4090, 1, 'http://localhost/lineup_campus/dashboard', 805, '2025-05-22 15:41:25'),
(4091, 1, 'http://localhost/lineup_campus/dashboard', 234, '2025-05-22 15:45:21'),
(4092, 1, 'http://localhost/lineup_campus/dashboard', 33, '2025-05-22 15:45:56'),
(4093, 1, 'http://localhost/lineup_campus/dashboard', 95, '2025-05-22 15:47:34'),
(4094, 1, 'http://localhost/lineup_campus/dashboard', 123, '2025-05-22 15:49:40'),
(4095, 1, 'http://localhost/lineup_campus/dashboard', 70, '2025-05-22 15:50:52'),
(4096, 1, 'http://localhost/lineup_campus/dashboard', 56, '2025-05-22 15:51:51'),
(4097, 1, 'http://localhost/lineup_campus/dashboard', 14, '2025-05-22 15:52:07'),
(4098, 1, 'http://localhost/lineup_campus/ayarlar', 9, '2025-05-22 15:52:19'),
(4099, 1, 'http://localhost/lineup_campus/kullanici-odeme-raporu', 122, '2025-05-22 15:54:22'),
(4100, 1, 'http://localhost/lineup_campus/kullanici-odeme-raporu', 70, '2025-05-22 15:55:34'),
(4101, 1, 'http://localhost/lineup_campus/okullar', 83, '2025-05-22 15:56:59'),
(4102, 1, 'http://localhost/lineup_campus/account/overview.html', 3, '2025-05-22 15:57:03'),
(4103, 1, 'http://localhost/lineup_campus/account/settings.html', 82, '2025-05-22 15:58:25'),
(4104, 1, 'http://localhost/lineup_campus/account/overview.html', 87, '2025-05-22 15:58:27'),
(4105, 1, 'http://localhost/lineup_campus/okullar', 172, '2025-05-22 15:58:28'),
(4106, 1, 'http://localhost/lineup_campus/okullar', 41, '2025-05-22 15:59:12'),
(4107, 1, 'http://localhost/lineup_campus/okullar', 24, '2025-05-22 15:59:39'),
(4108, 1, 'http://localhost/lineup_campus/ogretmenler', 230, '2025-05-22 16:03:31'),
(4109, 1, 'http://localhost/lineup_campus/ogretmenler', 6, '2025-05-22 16:03:39'),
(4110, 1, 'http://localhost/lineup_campus/ayarlar', 3, '2025-05-22 16:03:44'),
(4111, 1, 'http://localhost/lineup_campus/ayarlar', 3, '2025-05-22 16:03:45'),
(4112, 1, 'http://localhost/lineup_campus/kullanici-odeme-raporu', 47, '2025-05-22 16:04:35'),
(4113, 1, 'http://localhost/lineup_campus/kullanici-odeme-raporu', 65, '2025-05-22 16:05:42'),
(4114, 1, 'http://localhost/lineup_campus/ayarlar', 6, '2025-05-22 16:05:50'),
(4115, 1, 'http://localhost/lineup_campus/dashboard', 10, '2025-05-22 16:06:20'),
(4116, 1, 'http://localhost/lineup_campus/dashboard', 7, '2025-05-22 16:10:54'),
(4117, 1, 'http://localhost/lineup_campus/aktif-destek-talepleri', 2, '2025-05-22 16:10:58'),
(4118, 1, 'http://localhost/lineup_campus/destek-talebi?id=3', 67, '2025-05-22 16:12:08'),
(4119, 1, 'http://localhost/lineup_campus/destek-talebi?id=3', 120, '2025-05-22 16:14:11'),
(4120, 1, 'http://localhost/lineup_campus/destek-talebi?id=3', 4, '2025-05-22 16:14:17'),
(4121, 1, 'http://localhost/lineup_campus/destek-talebi?id=3', 4, '2025-05-22 16:14:24'),
(4122, 1, 'http://localhost/lineup_campus/okullar', 35, '2025-05-22 16:15:01'),
(4123, 1, 'http://localhost/lineup_campus/okullar', 18, '2025-05-22 16:15:21'),
(4124, 1, 'http://localhost/lineup_campus/yas-grubu', 16, '2025-05-22 16:15:40'),
(4125, 1, 'http://localhost/lineup_campus/fatura-excel-rapor', 27, '2025-05-22 16:16:09'),
(4126, 1, 'http://localhost/lineup_campus/fatura-excel-rapor', 24, '2025-05-22 16:16:35'),
(4127, 1, 'http://localhost/lineup_campus/fatura-excel-rapor', 41, '2025-05-22 16:17:18'),
(4128, 1, 'http://localhost/lineup_campus/test-ekle', 7, '2025-05-22 16:17:27'),
(4129, 1, 'http://localhost/lineup_campus/siniflar', 498, '2025-05-22 16:25:47'),
(4130, 1, 'http://localhost/lineup_campus/siniflar', 148, '2025-05-22 16:28:17'),
(4131, 1, 'http://localhost/lineup_campus/dashboard', 58706, '2025-05-23 08:46:45'),
(4132, 1, 'http://localhost/lineup_campus/dashboard', 39, '2025-05-23 08:47:26'),
(4133, 1, 'http://localhost/lineup_campus/dashboard', 196, '2025-05-23 08:50:43'),
(4134, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-yonetimi', 3, '2025-05-23 08:50:48'),
(4135, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-yonetimi', 3, '2025-05-23 08:50:49'),
(4136, 1, 'http://localhost/lineup_campus/ana-okulu-icerikler', 10, '2025-05-23 08:51:01'),
(4137, 1, 'http://localhost/lineup_campus/ana-okulu-icerikler-detay.php?id=96', 125, '2025-05-23 08:53:09'),
(4138, 1, 'http://localhost/lineup_campus/abonelik-kayip-oranlari', 235, '2025-05-23 08:57:06'),
(4139, 1, 'http://localhost/lineup_campus/abonelik-kayip-oranlari', 15, '2025-05-23 08:57:23'),
(4140, 1, 'http://localhost/lineup_campus/abonelik-kayip-oranlari', 54, '2025-05-23 08:58:19'),
(4141, 1, 'http://localhost/lineup_campus/abonelik-kayip-oranlari', 7, '2025-05-23 08:58:28'),
(4142, 1, 'http://localhost/lineup_campus/abonelik-buyume-oranlari', 5, '2025-05-23 08:58:35'),
(4143, 1, 'http://localhost/lineup_campus/ayarlar', 5, '2025-05-23 08:58:42'),
(4144, 1, 'http://localhost/lineup_campus/kullanici-odeme-raporu', 37, '2025-05-23 08:59:22'),
(4145, 1, 'http://localhost/lineup_campus/kullanici-odeme-raporu', 2, '2025-05-23 08:59:26'),
(4146, 1, 'http://localhost/lineup_campus/grafik-rapor', 6, '2025-05-23 08:59:34'),
(4147, 1, 'http://localhost/lineup_campus/odeme-tipi-grafik-rapor', 8, '2025-05-23 08:59:44'),
(4148, 1, 'http://localhost/lineup_campus/kullanici-basina-gelir-raporu', 8, '2025-05-23 08:59:55'),
(4149, 1, 'http://localhost/lineup_campus/paket-kullanim-durumlari', 2, '2025-05-23 08:59:59'),
(4150, 1, 'http://localhost/lineup_campus/abonelik-buyume-oranlari', 1, '2025-05-23 09:00:02'),
(4151, 1, 'http://localhost/lineup_campus/paket-kullanim-durumlari', 6, '2025-05-23 09:00:10'),
(4152, 1, 'http://localhost/lineup_campus/paket-kullanim-durumlari', 27, '2025-05-23 09:00:40'),
(4153, 1, 'http://localhost/lineup_campus/abonelik-buyume-oranlari', 21, '2025-05-23 09:01:03'),
(4154, 1, 'http://localhost/lineup_campus/abonelik-kayip-oranlari', 10, '2025-05-23 09:01:15'),
(4155, 1, 'http://localhost/lineup_campus/paket-bitisi-yaklasan-aboneler', 18, '2025-05-23 09:01:36'),
(4156, 1, 'http://localhost/lineup_campus/paket-bitisi-yaklasan-aboneler', 141, '2025-05-23 09:04:01'),
(4157, 1, 'http://localhost/lineup_campus/paket-bitisi-yaklasan-aboneler', 11, '2025-05-23 09:04:14'),
(4158, 1, 'http://localhost/lineup_campus/fatura-excel-rapor', 106, '2025-05-23 09:06:02'),
(4159, 1, 'http://localhost/lineup_campus/fatura-excel-rapor', 130, '2025-05-23 09:06:26'),
(4160, 1, 'http://localhost/lineup_campus/fatura-excel-rapor', 999, '2025-05-23 09:20:55'),
(4161, 1, 'http://localhost/lineup_campus/fatura-excel-rapor', 4, '2025-05-23 09:21:01'),
(4162, 1, 'http://localhost/lineup_campus/fatura-excel-rapor', 3, '2025-05-23 09:21:07'),
(4163, 1, 'http://localhost/lineup_campus/siniflar', 4, '2025-05-23 09:21:13'),
(4164, 1, 'http://localhost/lineup_campus/ayarlar', 4, '2025-05-23 09:21:19'),
(4165, 1, 'http://localhost/lineup_campus/api-ayarlar', 4, '2025-05-23 09:21:25'),
(4166, 1, 'http://localhost/lineup_campus/paketler', 23, '2025-05-23 09:21:51'),
(4167, 1, 'http://localhost/lineup_campus/paketler', 109, '2025-05-23 09:23:42'),
(4168, 1, 'http://localhost/lineup_campus/paketler', 22, '2025-05-23 09:24:06'),
(4169, 1, 'http://localhost/lineup_campus/paketler', 43, '2025-05-23 09:24:51'),
(4170, 1, 'http://localhost/lineup_campus/paketler', 10, '2025-05-23 09:25:04'),
(4171, 1, 'http://localhost/lineup_campus/yas-grubu', 5, '2025-05-23 09:25:11'),
(4172, 1, 'http://localhost/lineup_campus/ana-okulu-icerikler', 3, '2025-05-23 09:25:16'),
(4173, 1, 'http://localhost/lineup_campus/ana-okulu-icerikler-detay.php?id=96', 3, '2025-05-23 09:25:20'),
(4174, 1, 'http://localhost/lineup_campus/kategori-basliklari', 5, '2025-05-23 09:25:27'),
(4175, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-yonetimi', 3, '2025-05-23 09:25:32'),
(4176, 1, 'http://localhost/lineup_campus/paketler', 408, '2025-05-23 09:32:22'),
(4177, 1, 'http://localhost/lineup_campus/paketler', 36, '2025-05-23 09:33:01'),
(4178, 1, 'http://localhost/lineup_campus/dashboard', 289, '2025-05-23 09:37:52'),
(4179, 1, 'http://localhost/lineup_campus/ana-okulu-icerikler', 3, '2025-05-23 09:37:57'),
(4180, 1, 'http://localhost/lineup_campus/ana-okulu-icerikler-detay.php?id=101', 5, '2025-05-23 09:38:03'),
(4181, 1, 'http://localhost/lineup_campus/ana-okulu-icerikler', 3, '2025-05-23 09:38:08'),
(4182, 1, 'http://localhost/lineup_campus/ana-okulu-icerikler-detay.php?id=121', 25, '2025-05-23 09:38:36'),
(4183, 1, 'http://localhost/lineup_campus/yas-grubu', 1, '2025-05-23 09:38:38'),
(4184, 1, 'http://localhost/lineup_campus/onemli-haftalar', 51, '2025-05-23 09:39:31'),
(4185, 1, 'http://localhost/lineup_campus/onemli-haftalar', 65, '2025-05-23 09:40:38'),
(4186, 1, 'http://localhost/lineup_campus/kullanici-odeme-raporu', 3, '2025-05-23 09:40:43'),
(4187, 1, 'http://localhost/lineup_campus/grafik-rapor', 241, '2025-05-23 09:44:46'),
(4188, 1, 'http://localhost/lineup_campus/api-ayarlar', 78, '2025-05-23 09:46:06'),
(4189, 1, 'http://localhost/lineup_campus/api-ayarlar', 599, '2025-05-23 09:56:07'),
(4190, 1, 'http://localhost/lineup_campus/dashboard', 38, '2025-05-23 09:58:00'),
(4191, 1, 'http://localhost/lineup_campus/paketler', 9, '2025-05-23 09:58:11'),
(4192, 1, 'http://localhost/lineup_campus/paket-detay?id=34', 12, '2025-05-23 09:58:25'),
(4193, 1, 'http://localhost/lineup_campus/paket-detay?id=34', 11, '2025-05-23 09:58:38'),
(4194, 1, 'http://localhost/lineup_campus/paket-detay?id=34', 47, '2025-05-23 09:59:26'),
(4195, 1, 'http://localhost/lineup_campus/paketler', 4, '2025-05-23 09:59:33'),
(4196, 1, 'http://localhost/lineup_campus/paket-detay?id=28', 21, '2025-05-23 09:59:56'),
(4197, 1, 'http://localhost/lineup_campus/ayarlar', 7, '2025-05-23 10:00:05'),
(4198, 1, 'http://localhost/lineup_campus/yas-grubu', 100, '2025-05-23 10:01:47'),
(4199, 1, 'http://localhost/lineup_campus/okullar', 199, '2025-05-23 10:05:08'),
(4200, 1, 'http://localhost/lineup_campus/okullar', 1, '2025-05-23 10:05:11'),
(4201, 1, 'http://localhost/lineup_campus/okul-detay/ankara-deneme-okulu', 9, '2025-05-23 10:05:22'),
(4202, 1, 'http://localhost/lineup_campus/okullar', 3, '2025-05-23 10:05:27'),
(4203, 1, 'http://localhost/lineup_campus/okul-detay/ankara-deneme-okulu', 7, '2025-05-23 10:05:35'),
(4204, 1, 'http://localhost/lineup_campus/okullar', 15, '2025-05-23 10:05:52'),
(4205, 1, 'http://localhost/lineup_campus/okul-detay/lineup-campus', 622, '2025-05-23 10:16:16'),
(4206, 1, 'http://localhost/lineup_campus/okullar', 1, '2025-05-23 10:16:19'),
(4207, 1, 'http://localhost/lineup_campus/ogretmenler', 97, '2025-05-23 10:17:59'),
(4208, 1, 'http://localhost/lineup_campus/ogretmenler', 253, '2025-05-23 10:22:21'),
(4209, 1, 'http://localhost/lineup_campus/dersler', 160, '2025-05-23 10:25:03'),
(4210, 1, 'http://localhost/lineup_campus/dersler', 160, '2025-05-23 10:25:04'),
(4211, 1, 'http://localhost/lineup_campus/dersler', 14, '2025-05-23 10:25:21'),
(4212, 1, 'http://localhost/lineup_campus/testler', 5, '2025-05-23 10:25:28'),
(4213, 1, 'http://localhost/lineup_campus/test-detay/baskentler', 12, '2025-05-23 10:25:42'),
(4214, 1, 'http://localhost/lineup_campus/test-detay-ogrenci/baskentler?id=25', 16, '2025-05-23 10:26:01'),
(4215, 1, 'http://localhost/lineup_campus/test-detay/baskentler', 29, '2025-05-23 10:26:32'),
(4216, 1, 'http://localhost/lineup_campus/yas-grubu', 1388, '2025-05-23 10:49:42'),
(4217, 1, 'http://localhost/lineup_campus/dashboard', 273, '2025-05-23 10:54:17'),
(4218, 1, 'http://localhost/lineup_campus/dersler', 22, '2025-05-23 10:54:41'),
(4219, 1, 'http://localhost/lineup_campus/uniteler', 49, '2025-05-23 10:55:31'),
(4220, 1, 'http://localhost/lineup_campus/dersler', 17, '2025-05-23 10:55:50'),
(4221, 1, 'http://localhost/lineup_campus/uniteler', 8, '2025-05-23 10:56:00'),
(4222, 1, 'http://localhost/lineup_campus/okul-detay/dogada-hayat', 13, '2025-05-23 10:56:15'),
(4223, 1, 'http://localhost/lineup_campus/uniteler', 41, '2025-05-23 10:56:57'),
(4224, 1, 'http://localhost/lineup_campus/konular', 8, '2025-05-23 10:57:07'),
(4225, 1, 'http://localhost/lineup_campus/konu-ekle', 43, '2025-05-23 10:57:52'),
(4226, 1, 'http://localhost/lineup_campus/konular', 184, '2025-05-23 11:00:58'),
(4227, 1, 'http://localhost/lineup_campus/konu-ekle', 29, '2025-05-23 11:01:29'),
(4228, 1, 'http://localhost/lineup_campus/alt-konular', 1, '2025-05-23 11:01:31'),
(4229, 1, 'http://localhost/lineup_campus/altkonu-ekle', 147, '2025-05-23 11:04:00'),
(4230, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-yonetimi', 2, '2025-05-23 11:04:04'),
(4231, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-ekle', 189, '2025-05-23 11:07:15'),
(4232, 1, 'http://localhost/lineup_campus/haftalik-gorev', 270, '2025-05-23 11:11:46'),
(4233, 1, 'http://localhost/lineup_campus/test-ekle', 618, '2025-05-23 11:22:07'),
(4234, 1, 'http://localhost/lineup_campus/haftalik-gorev', 153, '2025-05-23 11:24:42'),
(4235, 1, 'http://localhost/lineup_campus/haftalik-gorev', 160, '2025-05-23 11:24:48'),
(4236, 1, 'http://localhost/lineup_campus/haftalik-gorev', 22, '2025-05-23 11:25:14'),
(4237, 1, 'http://localhost/lineup_campus/sesli-kitaplar', 11, '2025-05-23 11:25:27'),
(4238, 1, 'http://localhost/lineup_campus/okul-detay/leonardo-da-vinci-kimdir', 2, '2025-05-23 11:25:31'),
(4239, 1, 'http://localhost/lineup_campus/sesli-kitaplar', 19, '2025-05-23 11:25:52'),
(4240, 1, 'http://localhost/lineup_campus/okul-detay/rwerwer', 47, '2025-05-23 11:26:41'),
(4241, 1, 'http://localhost/lineup_campus/sesli-kitaplar', 28, '2025-05-23 11:27:11'),
(4242, 1, 'http://localhost/lineup_campus/sesli-kitaplar', 29, '2025-05-23 11:27:12'),
(4243, 1, 'http://localhost/lineup_campus/dashboard', 2, '2025-05-23 11:27:16'),
(4244, 1, 'http://localhost/lineup_campus/sesli-kitaplar', 4550, '2025-05-23 12:43:08'),
(4245, 1, 'http://localhost/lineup_campus/paketler', 3, '2025-05-23 12:43:13'),
(4246, 1, 'http://localhost/lineup_campus/paket-detay?id=34', 63, '2025-05-23 12:44:18'),
(4247, 1, 'http://localhost/lineup_campus/paketler', 2, '2025-05-23 12:44:22'),
(4248, 1, 'http://localhost/lineup_campus/paket-detay?id=34', 8, '2025-05-23 12:44:31'),
(4249, 1, 'http://localhost/lineup_campus/paketler', 1, '2025-05-23 12:44:35'),
(4250, 1, 'http://localhost/lineup_campus/paket-detay?id=28', 1, '2025-05-23 12:44:38'),
(4251, 1, 'http://localhost/lineup_campus/paket-detay?id=5', 812, '2025-05-23 12:58:26'),
(4252, 1, 'http://localhost/lineup_campus/ayarlar', 79, '2025-05-23 12:59:47'),
(4253, 1, 'http://localhost/lineup_campus/kullanici-odeme-raporu', 217, '2025-05-23 13:03:26'),
(4254, 1, 'http://localhost/lineup_campus/grafik-rapor', 30, '2025-05-23 13:03:58'),
(4255, 1, 'http://localhost/lineup_campus/grafik-rapor', 16, '2025-05-23 13:04:16'),
(4256, 1, 'http://localhost/lineup_campus/odeme-tipi-grafik-rapor', 8, '2025-05-23 13:04:26'),
(4257, 1, 'http://localhost/lineup_campus/kullanici-basina-gelir-raporu', 24, '2025-05-23 13:04:52'),
(4258, 1, 'http://localhost/lineup_campus/paket-kullanim-durumlari', 2, '2025-05-23 13:04:56'),
(4259, 1, 'http://localhost/lineup_campus/abonelik-buyume-oranlari', 19, '2025-05-23 13:05:17'),
(4260, 1, 'http://localhost/lineup_campus/abonelik-kayip-oranlari', 4, '2025-05-23 13:05:23'),
(4261, 1, 'http://localhost/lineup_campus/abonelik-buyume-oranlari', 1, '2025-05-23 13:05:26'),
(4262, 1, 'http://localhost/lineup_campus/abonelik-kayip-oranlari', 8, '2025-05-23 13:05:35'),
(4263, 1, 'http://localhost/lineup_campus/abonelik-buyume-oranlari', 4, '2025-05-23 13:05:41'),
(4264, 1, 'http://localhost/lineup_campus/abonelik-kayip-oranlari', 16, '2025-05-23 13:05:58'),
(4265, 1, 'http://localhost/lineup_campus/abonelik-buyume-oranlari', 10, '2025-05-23 13:06:10'),
(4266, 1, 'http://localhost/lineup_campus/abonelik-kayip-oranlari', 12, '2025-05-23 13:06:24'),
(4267, 1, 'http://localhost/lineup_campus/paket-bitisi-yaklasan-aboneler', 118, '2025-05-23 13:08:24'),
(4268, 1, 'http://localhost/lineup_campus/fatura-excel-rapor', 16, '2025-05-23 13:08:42'),
(4269, 1, 'http://localhost/lineup_campus/grafik-rapor', 96, '2025-05-23 13:10:20'),
(4270, 1, 'http://localhost/lineup_campus/odeme-tipi-grafik-rapor', 33, '2025-05-23 13:10:55'),
(4271, 1, 'http://localhost/lineup_campus/grafik-rapor', 52, '2025-05-23 13:11:49'),
(4272, 1, 'http://localhost/lineup_campus/odeme-tipi-grafik-rapor', 8, '2025-05-23 13:11:59'),
(4273, 1, 'http://localhost/lineup_campus/kullanici-basina-gelir-raporu', 5, '2025-05-23 13:12:06'),
(4274, 1, 'http://localhost/lineup_campus/paket-kullanim-durumlari', 9, '2025-05-23 13:12:16'),
(4275, 1, 'http://localhost/lineup_campus/paket-kullanim-durumlari', 10, '2025-05-23 13:12:18'),
(4276, 1, 'http://localhost/lineup_campus/abonelik-buyume-oranlari', 77, '2025-05-23 13:13:37'),
(4277, 1, 'http://localhost/lineup_campus/abonelik-kayip-oranlari', 9, '2025-05-23 13:13:47'),
(4278, 1, 'http://localhost/lineup_campus/api-ayarlar', 261, '2025-05-23 13:18:10'),
(4279, 1, 'http://localhost/lineup_campus/api-ayarlar', 39, '2025-05-23 13:18:51'),
(4280, 1, 'http://localhost/lineup_campus/aktif-destek-talepleri', 7, '2025-05-23 13:19:00'),
(4281, 1, 'http://localhost/lineup_campus/paket-kullanim-durumlari', 1, '2025-05-23 13:19:03'),
(4282, 1, 'http://localhost/lineup_campus/kullanici-basina-gelir-raporu', 2, '2025-05-23 13:19:07'),
(4283, 1, 'http://localhost/lineup_campus/abonelik-buyume-oranlari', 13, '2025-05-23 13:19:22'),
(4284, 1, 'http://localhost/lineup_campus/abonelik-kayip-oranlari', 7, '2025-05-23 13:19:31'),
(4285, 1, 'http://localhost/lineup_campus/abonelik-buyume-oranlari', 5, '2025-05-23 13:19:38'),
(4286, 1, 'http://localhost/lineup_campus/abonelik-kayip-oranlari', 3, '2025-05-23 13:19:43'),
(4287, 1, 'http://localhost/lineup_campus/abonelik-buyume-oranlari', 7, '2025-05-23 13:19:51'),
(4288, 1, 'http://localhost/lineup_campus/abonelik-buyume-oranlari', 7, '2025-05-23 13:19:53'),
(4289, 1, 'http://localhost/lineup_campus/abonelik-buyume-oranlari', 9, '2025-05-23 13:19:54'),
(4290, 1, 'http://localhost/lineup_campus/abonelik-buyume-oranlari', 70, '2025-05-23 13:21:06'),
(4291, 1, 'http://localhost/lineup_campus/paketler', 2664, '2025-05-23 13:29:04'),
(4292, 1, 'http://localhost/lineup_campus/paketler', 3, '2025-05-23 13:29:09'),
(4293, 1, 'http://localhost/lineup_campus/kullanici-odeme-raporu', 0, '2025-05-23 13:29:11'),
(4294, 1, 'http://localhost/lineup_campus/grafik-rapor', 2, '2025-05-23 13:29:15'),
(4295, 1, 'http://localhost/lineup_campus/kullanici-basina-gelir-raporu', 3, '2025-05-23 13:29:20'),
(4296, 1, 'http://localhost/lineup_campus/paket-kullanim-durumlari', 2, '2025-05-23 13:29:23'),
(4297, 1, 'http://localhost/lineup_campus/abonelik-buyume-oranlari', 6, '2025-05-23 13:29:31'),
(4298, 1, 'http://localhost/lineup_campus/abonelik-kayip-oranlari', 10, '2025-05-23 13:29:43'),
(4299, 1, 'http://localhost/lineup_campus/abonelik-buyume-oranlari', 191, '2025-05-23 13:32:57'),
(4300, 1, 'http://localhost/lineup_campus/abonelik-buyume-oranlari', 68, '2025-05-23 13:34:07'),
(4301, 1, 'http://localhost/lineup_campus/abonelik-buyume-oranlari', 8, '2025-05-23 13:34:16'),
(4302, 1, 'http://localhost/lineup_campus/abonelik-kayip-oranlari', 17, '2025-05-23 13:34:35'),
(4303, 1, 'http://localhost/lineup_campus/abonelik-kayip-oranlari', 3, '2025-05-23 13:34:40'),
(4304, 1, 'http://localhost/lineup_campus/abonelik-buyume-oranlari', 26, '2025-05-23 13:35:08'),
(4305, 1, 'http://localhost/lineup_campus/abonelik-buyume-oranlari', 62, '2025-05-23 13:36:12'),
(4306, 1, 'http://localhost/lineup_campus/abonelik-buyume-oranlari', 85, '2025-05-23 13:37:39'),
(4307, 1, 'http://localhost/lineup_campus/abonelik-buyume-oranlari', 25, '2025-05-23 13:38:05'),
(4308, 1, 'http://localhost/lineup_campus/abonelik-kayip-oranlari', 10, '2025-05-23 13:38:17'),
(4309, 1, 'http://localhost/lineup_campus/abonelik-kayip-oranlari', 84, '2025-05-23 13:39:43'),
(4310, 1, 'http://localhost/lineup_campus/abonelik-kayip-oranlari', 6, '2025-05-23 13:39:52'),
(4311, 1, 'http://localhost/lineup_campus/abonelik-kayip-oranlari', 128, '2025-05-23 13:42:02'),
(4312, 1, 'http://localhost/lineup_campus/abonelik-kayip-oranlari', 7, '2025-05-23 13:42:11'),
(4313, 1, 'http://localhost/lineup_campus/abonelik-kayip-oranlari', 291, '2025-05-23 13:47:05'),
(4314, 1, 'http://localhost/lineup_campus/abonelik-buyume-oranlari', 1, '2025-05-23 13:47:08'),
(4315, 1, 'http://localhost/lineup_campus/abonelik-kayip-oranlari', 14, '2025-05-23 13:47:24'),
(4316, 1, 'http://localhost/lineup_campus/paket-bitisi-yaklasan-aboneler', 8, '2025-05-23 13:47:34'),
(4317, 1, 'http://localhost/lineup_campus/abonelik-kayip-oranlari', 14, '2025-05-23 13:47:50'),
(4318, 1, 'http://localhost/lineup_campus/abonelik-kayip-oranlari', 2, '2025-05-23 13:47:54'),
(4319, 1, 'http://localhost/lineup_campus/paket-bitisi-yaklasan-aboneler', 1, '2025-05-23 13:47:56'),
(4320, 1, 'http://localhost/lineup_campus/abonelik-kayip-oranlari', 19, '2025-05-23 13:48:17'),
(4321, 1, 'http://localhost/lineup_campus/abonelik-kayip-oranlari', 4035, '2025-05-23 14:55:35'),
(4322, 1, 'http://localhost/lineup_campus/paketler', 6, '2025-05-23 14:55:42'),
(4323, 1, 'http://localhost/lineup_campus/paket-detay?id=34', 225, '2025-05-23 14:59:29'),
(4324, 1, 'http://localhost/lineup_campus/paket-detay?id=34', 31, '2025-05-23 15:00:02'),
(4325, 1, 'http://localhost/lineup_campus/paket-detay?id=34', 28, '2025-05-23 15:00:33'),
(4326, 1, 'http://localhost/lineup_campus/paket-detay?id=34', 205, '2025-05-23 15:04:00'),
(4327, 1, 'http://localhost/lineup_campus/paket-detay?id=34', 1496, '2025-05-23 15:28:58'),
(4328, 1, 'http://localhost/lineup_campus/kullanici-odeme-raporu', 148, '2025-05-23 15:31:28'),
(4329, 1, 'http://localhost/lineup_campus/kullanici-odeme-raporu', 94, '2025-05-23 15:33:05'),
(4330, 1, 'http://localhost/lineup_campus/kullanici-odeme-raporu', 127, '2025-05-23 15:35:14'),
(4331, 1, 'http://localhost/lineup_campus/kullanici-odeme-raporu', 7, '2025-05-23 15:35:23'),
(4332, 1, 'http://localhost/lineup_campus/grafik-rapor', 107, '2025-05-23 15:37:12'),
(4333, 1, 'http://localhost/lineup_campus/grafik-rapor', 30, '2025-05-23 15:37:44'),
(4334, 1, 'http://localhost/lineup_campus/odeme-tipi-grafik-rapor', 12, '2025-05-23 15:37:58'),
(4335, 1, 'http://localhost/lineup_campus/odeme-tipi-grafik-rapor', 255, '2025-05-23 15:42:15'),
(4336, 1, 'http://localhost/lineup_campus/odeme-tipi-grafik-rapor', 54, '2025-05-23 15:43:11'),
(4337, 1, 'http://localhost/lineup_campus/odeme-tipi-grafik-rapor', 11, '2025-05-23 15:43:23'),
(4338, 1, 'http://localhost/lineup_campus/odeme-tipi-grafik-rapor', 89, '2025-05-23 15:44:55'),
(4339, 1, 'http://localhost/lineup_campus/odeme-tipi-grafik-rapor', 32, '2025-05-23 15:45:29'),
(4340, 1, 'http://localhost/lineup_campus/odeme-tipi-grafik-rapor', 50, '2025-05-23 15:46:21'),
(4341, 1, 'http://localhost/lineup_campus/grafik-rapor', 45, '2025-05-23 15:47:08'),
(4342, 1, 'http://localhost/lineup_campus/grafik-rapor', 82, '2025-05-23 15:48:32'),
(4343, 1, 'http://localhost/lineup_campus/grafik-rapor', 17, '2025-05-23 15:48:51'),
(4344, 1, 'http://localhost/lineup_campus/odeme-tipi-grafik-rapor', 103, '2025-05-23 15:50:36'),
(4345, 1, 'http://localhost/lineup_campus/odeme-tipi-grafik-rapor', 19, '2025-05-23 15:50:56'),
(4346, 1, 'http://localhost/lineup_campus/odeme-tipi-grafik-rapor', 19, '2025-05-23 15:51:18'),
(4347, 1, 'http://localhost/lineup_campus/paket-kullanim-durumlari', 3, '2025-05-23 15:51:23'),
(4348, 1, 'http://localhost/lineup_campus/abonelik-buyume-oranlari', 59, '2025-05-23 15:52:23'),
(4349, 1, 'http://localhost/lineup_campus/abonelik-buyume-oranlari', 70, '2025-05-23 15:53:35'),
(4350, 1, 'http://localhost/lineup_campus/abonelik-buyume-oranlari', 25, '2025-05-23 15:54:02'),
(4351, 1, 'http://localhost/lineup_campus/abonelik-kayip-oranlari', 89, '2025-05-23 15:55:33'),
(4352, 1, 'http://localhost/lineup_campus/abonelik-kayip-oranlari', 13, '2025-05-23 15:55:48'),
(4353, 1, 'http://localhost/lineup_campus/abonelik-kayip-oranlari', 16, '2025-05-23 15:56:06'),
(4354, 1, 'http://localhost/lineup_campus/paket-bitisi-yaklasan-aboneler', 81, '2025-05-23 15:57:29'),
(4355, 1, 'http://localhost/lineup_campus/paket-bitisi-yaklasan-aboneler', 38, '2025-05-23 15:58:09'),
(4356, 1, 'http://localhost/lineup_campus/paket-bitisi-yaklasan-aboneler', 18, '2025-05-23 15:58:29'),
(4357, 1, 'http://localhost/lineup_campus/fatura-excel-rapor', 15, '2025-05-23 15:58:46'),
(4358, 1, 'http://localhost/lineup_campus/paket-bitisi-yaklasan-aboneler', 149, '2025-05-23 16:01:17'),
(4359, 1, 'http://localhost/lineup_campus/paket-bitisi-yaklasan-aboneler', 1, '2025-05-23 16:01:21'),
(4360, 1, 'http://localhost/lineup_campus/kullanici-odeme-raporu', 2, '2025-05-23 16:01:25'),
(4361, 1, 'http://localhost/lineup_campus/grafik-rapor', 20, '2025-05-23 16:01:47'),
(4362, 1, 'http://localhost/lineup_campus/odeme-tipi-grafik-rapor', 34, '2025-05-23 16:02:23'),
(4363, 1, 'http://localhost/lineup_campus/grafik-rapor', 19, '2025-05-23 16:02:44'),
(4364, 1, 'http://localhost/lineup_campus/kullanici-basina-gelir-raporu', 51, '2025-05-23 16:03:37'),
(4365, 1, 'http://localhost/lineup_campus/kullanici-basina-gelir-raporu', 22, '2025-05-23 16:04:01'),
(4366, 1, 'http://localhost/lineup_campus/kullanici-basina-gelir-raporu', 16, '2025-05-23 16:04:18'),
(4367, 1, 'http://localhost/lineup_campus/kullanici-basina-gelir-raporu', 7, '2025-05-23 16:04:27'),
(4368, 1, 'http://localhost/lineup_campus/kullanici-basina-gelir-raporu', 11, '2025-05-23 16:04:40'),
(4369, 1, 'http://localhost/lineup_campus/paket-kullanim-durumlari', 4, '2025-05-23 16:04:46'),
(4370, 1, 'http://localhost/lineup_campus/abonelik-buyume-oranlari', 2, '2025-05-23 16:04:50'),
(4371, 1, 'http://localhost/lineup_campus/abonelik-kayip-oranlari', 24894, '2025-05-23 22:59:46'),
(4372, 1, 'http://localhost/lineup_campus/abonelik-kayip-oranlari', 10, '2025-05-23 23:00:04'),
(4373, 1, 'http://localhost/lineup_campus/abonelik-buyume-oranlari', 10, '2025-05-23 23:00:16'),
(4374, 1, 'http://localhost/lineup_campus/paket-bitisi-yaklasan-aboneler', 48, '2025-05-23 23:00:59'),
(4375, 1, 'http://localhost/lineup_campus/fatura-excel-rapor', 13, '2025-05-23 23:01:14'),
(4376, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-yonetimi', 2, '2025-05-23 23:01:18'),
(4377, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-ekle', 368, '2025-05-23 23:07:27'),
(4378, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-ekle', 237, '2025-05-23 23:11:27'),
(4379, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-ekle', 59, '2025-05-23 23:12:27'),
(4380, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-ekle', 6, '2025-05-23 23:12:36'),
(4381, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-ekle', 111, '2025-05-23 23:14:29'),
(4382, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-ekle', 81, '2025-05-23 23:15:52'),
(4383, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-ekle', 36, '2025-05-23 23:16:30'),
(4384, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-ekle', 21, '2025-05-23 23:16:53'),
(4385, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-ekle', 46, '2025-05-23 23:17:42'),
(4386, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-ekle', 18, '2025-05-23 23:18:02'),
(4387, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-ekle', 74, '2025-05-23 23:19:18'),
(4388, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-ekle', 697, '2025-05-23 23:30:58'),
(4389, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-ekle', 193, '2025-05-23 23:34:14'),
(4390, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-ekle', 109, '2025-05-23 23:36:06'),
(4391, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-ekle', 70, '2025-05-23 23:37:18'),
(4392, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-ekle', 48, '2025-05-23 23:38:09'),
(4393, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-yonetimi', 43, '2025-05-23 23:38:54'),
(4394, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-yonetimi', 51, '2025-05-23 23:39:47'),
(4395, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-yonetimi', 3, '2025-05-23 23:39:53'),
(4396, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=125', 106, '2025-05-23 23:41:41'),
(4397, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=125', 10, '2025-05-23 23:41:53'),
(4398, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=125', 29, '2025-05-23 23:42:24'),
(4399, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=125', 3, '2025-05-23 23:42:30'),
(4400, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=125', 80, '2025-05-23 23:43:52'),
(4401, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=125', 22, '2025-05-23 23:44:17'),
(4402, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=125', 318, '2025-05-23 23:49:37'),
(4403, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=125', 23, '2025-05-23 23:50:03'),
(4404, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=125', 384, '2025-05-23 23:56:29'),
(4405, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=125', 16, '2025-05-23 23:56:47'),
(4406, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=125', 129, '2025-05-23 23:58:58'),
(4407, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=125', 12, '2025-05-23 23:59:12'),
(4408, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=125', 3, '2025-05-23 23:59:17'),
(4409, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=125', 6, '2025-05-23 23:59:25'),
(4410, 1, 'http://localhost/lineup_campus/ana-okulu-icerikler', 82, '2025-05-24 00:00:49'),
(4411, 1, 'http://localhost/lineup_campus/ana-okulu-icerikler-detay.php?id=125', 8, '2025-05-24 00:00:59'),
(4412, 1, 'http://localhost/lineup_campus/ana-okulu-icerikler', 85, '2025-05-24 00:02:27'),
(4413, 1, 'http://localhost/lineup_campus/ana-okulu-icerikler', 15, '2025-05-24 00:02:44'),
(4414, 1, 'http://localhost/lineup_campus/ana-okulu-icerikler-detay.php?id=125', 201, '2025-05-24 00:06:07'),
(4415, 1, 'http://localhost/lineup_campus/ana-okulu-icerikler-detay.php?id=125', 9, '2025-05-24 00:06:19'),
(4416, 1, 'http://localhost/lineup_campus/ana-okulu-icerikler-detay.php?id=125', 14, '2025-05-24 00:06:34'),
(4417, 1, 'http://localhost/lineup_campus/ana-okulu-icerikler-detay.php?id=125', 105, '2025-05-24 00:08:21'),
(4418, 1, 'http://localhost/lineup_campus/ana-okulu-icerikler-detay.php?id=125', 39, '2025-05-24 00:09:02'),
(4419, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-yonetimi', 8, '2025-05-24 00:10:39'),
(4420, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=125', 8, '2025-05-24 00:10:48'),
(4421, 1, 'http://localhost/lineup_campus/ana-okulu-icerikler-detay.php?id=125', 110, '2025-05-24 00:10:54'),
(4422, 1, 'http://localhost/lineup_campus/ana-okulu-icerikler-detay.php?id=125', 6, '2025-05-24 00:11:03'),
(4423, 1, 'http://localhost/lineup_campus/ana-okulu-icerikler-detay.php?id=125', 24, '2025-05-24 00:11:28'),
(4424, 1, 'http://localhost/lineup_campus/abonelik-buyume-oranlari', 39032, '2025-05-24 00:11:40'),
(4425, 1, 'http://localhost/lineup_campus/ana-okulu-icerikler-detay.php?id=125', 4, '2025-05-24 00:11:46'),
(4426, 1, 'http://localhost/lineup_campus/ana-okulu-icerikler', 2, '2025-05-24 00:11:50'),
(4427, 1, 'http://localhost/lineup_campus/ana-okulu-icerikler-detay.php?id=92', 6, '2025-05-24 00:11:58'),
(4428, 1, 'http://localhost/lineup_campus/ana-okulu-icerikler', 4, '2025-05-24 00:12:05'),
(4429, 1, 'http://localhost/lineup_campus/ana-okulu-icerikler-detay.php?id=125', 12, '2025-05-24 00:12:19'),
(4430, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-yonetimi', 4, '2025-05-24 00:12:25'),
(4431, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-yonetimi', 1, '2025-05-24 00:12:30'),
(4432, 1, 'http://localhost/lineup_campus/ana-okulu-icerikler-detay.php?id=125', 114, '2025-05-24 00:13:25'),
(4433, 1, 'http://localhost/lineup_campus/ana-okulu-icerikler-detay.php?id=125', 13, '2025-05-24 00:13:40'),
(4434, 1, 'http://localhost/lineup_campus/ana-okulu-icerikler-detay.php?id=125', 129, '2025-05-24 00:15:52'),
(4435, 1, 'http://localhost/lineup_campus/ana-okulu-icerikler-detay.php?id=125', 68, '2025-05-24 00:17:02'),
(4436, 1, 'http://localhost/lineup_campus/ana-okulu-icerikler-detay.php?id=125', 24, '2025-05-24 00:17:28'),
(4437, 1, 'http://localhost/lineup_campus/ana-okulu-icerikler-detay.php?id=125', 46, '2025-05-24 00:18:17'),
(4438, 1, 'http://localhost/lineup_campus/ana-okulu-icerikler-detay.php?id=125', 11, '2025-05-24 00:18:30'),
(4439, 1, 'http://localhost/lineup_campus/ana-okulu-icerikler-detay.php?id=125', 41, '2025-05-24 00:19:13'),
(4440, 1, 'http://localhost/lineup_campus/ana-okulu-icerikler-detay.php?id=125', 12, '2025-05-24 00:19:28'),
(4441, 1, 'http://localhost/lineup_campus/ana-okulu-icerikler-detay.php?id=125', 62, '2025-05-24 00:20:32'),
(4442, 1, 'http://localhost/lineup_campus/ana-okulu-icerikler-detay.php?id=125', 17, '2025-05-24 00:20:52'),
(4443, 1, 'http://localhost/lineup_campus/ana-okulu-icerikler-detay.php?id=125', 9, '2025-05-24 00:21:03'),
(4444, 1, 'http://localhost/lineup_campus/ana-okulu-icerikler-detay.php?id=125', 125, '2025-05-24 00:23:09'),
(4445, 1, 'http://localhost/lineup_campus/ana-okulu-icerikler-detay.php?id=94', 43, '2025-05-24 00:50:02'),
(4446, 1, 'http://localhost/lineup_campus/ana-okulu-icerikler-detay.php?id=94', 35, '2025-05-24 00:50:39'),
(4447, 1, 'http://localhost/lineup_campus/ana-okulu-icerikler-detay.php?id=94', 42, '2025-05-24 00:51:38'),
(4448, 1, 'http://localhost/lineup_campus/ana-okulu-icerikler-detay.php?id=94', 12, '2025-05-24 00:51:52'),
(4449, 1, 'http://localhost/lineup_campus/ana-okulu-icerikler-detay.php?id=94', 15, '2025-05-24 00:52:10'),
(4450, 1, 'http://localhost/lineup_campus/ana-okulu-icerikler-detay.php?id=94', 188, '2025-05-24 00:55:19'),
(4451, 1, 'http://localhost/lineup_campus/ana-okulu-icerikler-detay.php?id=94', 14, '2025-05-24 00:55:57'),
(4452, 1, 'http://localhost/lineup_campus/ana-okulu-icerikler-detay.php?id=94', 10, '2025-05-24 00:56:10'),
(4453, 1, 'http://localhost/lineup_campus/ana-okulu-icerikler-detay.php?id=94', 7, '2025-05-24 00:57:04'),
(4454, 1, 'http://localhost/lineup_campus/ana-okulu-icerikler-detay.php?id=94', 27, '2025-05-24 01:31:14'),
(4455, 1, 'http://localhost/lineup_campus/ana-okulu-icerikler-detay.php?id=94', 3, '2025-05-24 01:31:18'),
(4456, 1, 'http://localhost/lineup_campus/ana-okulu-icerikler-detay.php?id=94', 1, '2025-05-24 01:31:21'),
(4457, 1, 'http://localhost/lineup_campus/dashboard', 2, '2025-05-24 01:31:25'),
(4458, 1, 'http://localhost/lineup_campus/dashboard', 2, '2025-05-24 01:31:30'),
(4459, 1, 'http://localhost/lineup_campus/dashboard', 1, '2025-05-24 01:31:33'),
(4460, 1, 'http://localhost/lineup_campus/okullar', 4, '2025-05-24 01:31:39'),
(4461, 1, 'http://localhost/lineup_campus/ogretmenler', 3, '2025-05-24 01:31:44'),
(4462, 1, 'http://localhost/lineup_campus/duyurular', 1, '2025-05-24 01:31:47'),
(4463, 1, 'http://localhost/lineup_campus/dashboard', 27, '2025-05-24 01:32:16'),
(4464, 1, 'http://localhost/lineup_campus/dashboard', 52, '2025-05-24 01:33:11'),
(4465, 1, 'http://localhost/lineup_campus/dashboard', 11, '2025-05-24 01:33:23'),
(4466, 1, 'http://localhost/lineup_campus/dashboard', 7, '2025-05-24 01:33:33'),
(4467, 1, 'http://localhost/lineup_campus/ana-okulu-icerikler', 5, '2025-05-24 01:33:40'),
(4468, 1, 'http://localhost/lineup_campus/ana-okulu-icerikler-detay.php?id=125', 148, '2025-05-24 01:36:10'),
(4469, 1, 'http://localhost/lineup_campus/api-ayarlar', 13, '2025-05-24 01:36:25'),
(4470, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=125', 5135, '2025-05-24 01:36:25'),
(4471, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=125', 5033, '2025-05-24 01:36:25'),
(4472, 1, 'http://localhost/lineup_campus/dashboard', 5, '2025-05-24 01:37:49'),
(4473, 1, 'http://localhost/lineup_campus/dashboard', 7, '2025-05-24 10:22:56'),
(4474, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-yonetimi', 1, '2025-05-24 10:23:00'),
(4475, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-ekle', 157, '2025-05-24 10:25:39'),
(4476, 1, 'http://localhost/lineup_campus/dashboard', 3, '2025-05-24 12:26:29'),
(4477, 1, 'http://localhost/lineup_campus/ana-okulu-icerikler', 5, '2025-05-24 12:26:36'),
(4478, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-yonetimi', 0, '2025-05-24 12:26:38'),
(4479, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-ekle', 24, '2025-05-24 12:27:05'),
(4480, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-ekle', 96, '2025-05-24 12:28:43'),
(4481, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-ekle', 3, '2025-05-24 12:28:48'),
(4482, 1, 'http://localhost/lineup_campus/ana-okulu-icerikler', 3, '2025-05-24 12:28:53'),
(4483, 1, 'http://localhost/lineup_campus/ana-okulu-icerikler-detay.php?id=127', 69, '2025-05-24 12:30:05'),
(4484, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-yonetimi', 4, '2025-05-24 12:30:10'),
(4485, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=127', 22, '2025-05-24 12:30:34'),
(4486, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=127', 7, '2025-05-24 12:30:43'),
(4487, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=127', 12, '2025-05-24 12:30:58'),
(4488, 1, 'http://localhost/lineup_campus/ana-okulu-icerik-guncelle?id=127', 78, '2025-05-24 12:32:18'),
(4489, 1, 'http://localhost/lineup_campus/kullanici-odeme-raporu', 2, '2025-05-24 12:32:22');
INSERT INTO `timespend_lnp` (`id`, `user_id`, `sayfa_url`, `timeSpent`, `exitTime`) VALUES
(4490, 1, 'http://localhost/lineup_campus/grafik-rapor', 34, '2025-05-24 12:32:58'),
(4491, 1, 'http://localhost/lineup_campus/paket-bitisi-yaklasan-aboneler', 243, '2025-05-24 12:37:03'),
(4492, 1, 'http://localhost/lineup_campus/paket-bitisi-yaklasan-aboneler', 73, '2025-05-24 12:38:18'),
(4493, 1, 'http://localhost/lineup_campus/api-ayarlar', 7, '2025-05-24 12:38:27'),
(4494, 1, 'http://localhost/lineup_campus/api-ayarlar', 761, '2025-05-24 12:51:10'),
(4495, 1, 'http://localhost/lineup_campus/api-ayarlar', 291, '2025-05-24 12:56:03'),
(4496, 1, 'http://localhost/lineup_campus/api-ayarlar', 20, '2025-05-24 12:56:25'),
(4497, 1, 'http://localhost/lineup_campus/api-ayarlar', 37, '2025-05-24 12:57:04'),
(4498, 1, 'http://localhost/lineup_campus/api-ayarlar', 5, '2025-05-24 12:57:11'),
(4499, 1, 'http://localhost/lineup_campus/ayarlar', 97, '2025-05-24 12:58:50'),
(4500, 1, 'http://localhost/lineup_campus/ayarlar', 649, '2025-05-24 13:09:41'),
(4501, 1, 'http://localhost/lineup_campus/ayarlar', 4, '2025-05-24 13:09:48'),
(4502, 1, 'http://localhost/lineup_campus/api-ayarlar', 15, '2025-05-24 13:10:05'),
(4503, 1, 'http://localhost/lineup_campus/api-ayarlar', 42, '2025-05-24 13:10:49'),
(4504, 1, 'http://localhost/lineup_campus/api-ayarlar', 50, '2025-05-24 13:12:29'),
(4505, 1, 'http://localhost/lineup_campus/api-ayarlar', 106, '2025-05-24 13:15:32'),
(4506, 1, 'http://localhost/lineup_campus/api-ayarlar', 195, '2025-05-24 13:18:49'),
(4507, 1, 'http://localhost/lineup_campus/api-ayarlar', 12, '2025-05-24 13:19:03'),
(4508, 1, 'http://localhost/lineup_campus/api-ayarlar', 91, '2025-05-24 13:20:36'),
(4509, 1, 'http://localhost/lineup_campus/api-ayarlar', 29, '2025-05-24 13:21:07'),
(4510, 1, 'http://localhost/lineup_campus/api-ayarlar', 13, '2025-05-24 13:21:22'),
(4511, 1, 'http://localhost/lineup_campus/api-ayarlar', 14, '2025-05-24 13:21:39'),
(4512, 1, 'http://localhost/lineup_campus/api-ayarlar', 23, '2025-05-24 13:22:05'),
(4513, 1, 'http://localhost/lineup_campus/api-ayarlar', 2, '2025-05-24 13:22:08'),
(4514, 1, 'http://localhost/lineup_campus/dashboard', 10, '2025-05-24 13:41:14'),
(4515, 1, 'http://localhost/lineup_campus/fatura-excel-rapor', 12, '2025-05-24 13:41:28'),
(4516, 1, 'http://localhost/lineup_campus/api-ayarlar', 81488, '2025-05-25 12:19:38'),
(4517, 1, 'http://localhost/lineup_campus/kullanici-odeme-raporu', 0, '2025-05-25 12:19:42'),
(4518, 1, 'http://localhost/lineup_campus/grafik-rapor', 621, '2025-05-25 12:30:04'),
(4519, 1, 'http://localhost/lineup_campus/grafik-rapor', 11, '2025-05-25 12:30:17'),
(4520, 1, 'http://localhost/lineup_campus/grafik-rapor', 121, '2025-05-25 12:32:21'),
(4521, 1, 'http://localhost/lineup_campus/grafik-rapor', 61, '2025-05-25 12:33:23'),
(4522, 1, 'http://localhost/lineup_campus/grafik-rapor', 21, '2025-05-25 12:33:46'),
(4523, 1, 'http://localhost/lineup_campus/odeme-tipi-grafik-rapor', 100, '2025-05-25 12:35:29'),
(4524, 1, 'http://localhost/lineup_campus/odeme-tipi-grafik-rapor', 86, '2025-05-25 12:36:56'),
(4525, 1, 'http://localhost/lineup_campus/odeme-tipi-grafik-rapor', 62, '2025-05-25 12:38:00'),
(4526, 1, 'http://localhost/lineup_campus/odeme-tipi-grafik-rapor', 19, '2025-05-25 12:38:21'),
(4527, 1, 'http://localhost/lineup_campus/odeme-tipi-grafik-rapor', 96, '2025-05-25 12:40:00'),
(4528, 1, 'http://localhost/lineup_campus/odeme-tipi-grafik-rapor', 41, '2025-05-25 12:40:43'),
(4529, 1, 'http://localhost/lineup_campus/odeme-tipi-grafik-rapor', 47, '2025-05-25 12:41:32'),
(4530, 1, 'http://localhost/lineup_campus/odeme-tipi-grafik-rapor', 24, '2025-05-25 12:41:58'),
(4531, 1, 'http://localhost/lineup_campus/odeme-tipi-grafik-rapor', 18, '2025-05-25 12:42:19'),
(4532, 1, 'http://localhost/lineup_campus/odeme-tipi-grafik-rapor', 67, '2025-05-25 12:43:27'),
(4533, 1, 'http://localhost/lineup_campus/odeme-tipi-grafik-rapor', 141, '2025-05-25 12:45:50'),
(4534, 1, 'http://localhost/lineup_campus/odeme-tipi-grafik-rapor', 15, '2025-05-25 12:46:07'),
(4535, 1, 'http://localhost/lineup_campus/odeme-tipi-grafik-rapor', 39, '2025-05-25 12:46:48'),
(4536, 1, 'http://localhost/lineup_campus/odeme-tipi-grafik-rapor', 68, '2025-05-25 12:47:58'),
(4537, 1, 'http://localhost/lineup_campus/odeme-tipi-grafik-rapor', 14, '2025-05-25 12:48:15'),
(4538, 1, 'http://localhost/lineup_campus/odeme-tipi-grafik-rapor', 76, '2025-05-25 12:49:33'),
(4539, 1, 'http://localhost/lineup_campus/odeme-tipi-grafik-rapor', 26, '2025-05-25 12:50:01'),
(4540, 1, 'http://localhost/lineup_campus/odeme-tipi-grafik-rapor', 86, '2025-05-25 12:51:30'),
(4541, 1, 'http://localhost/lineup_campus/odeme-tipi-grafik-rapor', 11, '2025-05-25 12:51:42'),
(4542, 1, 'http://localhost/lineup_campus/odeme-tipi-grafik-rapor', 35, '2025-05-25 12:52:19'),
(4543, 1, 'http://localhost/lineup_campus/odeme-tipi-grafik-rapor', 93, '2025-05-25 12:53:58'),
(4544, 1, 'http://localhost/lineup_campus/odeme-tipi-grafik-rapor', 94, '2025-05-25 12:55:34'),
(4545, 1, 'http://localhost/lineup_campus/odeme-tipi-grafik-rapor', 35, '2025-05-25 12:56:12'),
(4546, 1, 'http://localhost/lineup_campus/odeme-tipi-grafik-rapor', 4, '2025-05-25 12:56:17'),
(4547, 1, 'http://localhost/lineup_campus/grafik-rapor', 159, '2025-05-25 12:58:59'),
(4548, 1, 'http://localhost/lineup_campus/grafik-rapor', 46, '2025-05-25 12:59:47'),
(4549, 1, 'http://localhost/lineup_campus/grafik-rapor', 124, '2025-05-25 13:01:54'),
(4550, 1, 'http://localhost/lineup_campus/grafik-rapor', 63, '2025-05-25 13:02:59'),
(4551, 1, 'http://localhost/lineup_campus/grafik-rapor', 28, '2025-05-25 13:03:30'),
(4552, 1, 'http://localhost/lineup_campus/grafik-rapor', 80, '2025-05-25 13:04:52'),
(4553, 1, 'http://localhost/lineup_campus/grafik-rapor', 109, '2025-05-25 13:06:43'),
(4554, 1, 'http://localhost/lineup_campus/grafik-rapor', 138, '2025-05-25 13:09:03'),
(4555, 1, 'http://localhost/lineup_campus/grafik-rapor', 15, '2025-05-25 13:09:21'),
(4556, 1, 'http://localhost/lineup_campus/grafik-rapor', 15, '2025-05-25 13:09:38'),
(4557, 1, 'http://localhost/lineup_campus/grafik-rapor', 27, '2025-05-25 13:10:07'),
(4558, 1, 'http://localhost/lineup_campus/grafik-rapor', 56, '2025-05-25 13:11:05'),
(4559, 1, 'http://localhost/lineup_campus/grafik-rapor', 34, '2025-05-25 13:11:42'),
(4560, 1, 'http://localhost/lineup_campus/grafik-rapor', 53, '2025-05-25 13:12:37'),
(4561, 1, 'http://localhost/lineup_campus/grafik-rapor', 32, '2025-05-25 13:13:11'),
(4562, 1, 'http://localhost/lineup_campus/grafik-rapor', 291, '2025-05-25 13:18:04'),
(4563, 1, 'http://localhost/lineup_campus/grafik-rapor', 19, '2025-05-25 13:18:25'),
(4564, 1, 'http://localhost/lineup_campus/grafik-rapor', 61, '2025-05-25 13:19:28'),
(4565, 1, 'http://localhost/lineup_campus/grafik-rapor', 12, '2025-05-25 13:19:42'),
(4566, 1, 'http://localhost/lineup_campus/grafik-rapor', 35, '2025-05-25 13:20:20'),
(4567, 1, 'http://localhost/lineup_campus/grafik-rapor', 54, '2025-05-25 13:21:16'),
(4568, 1, 'http://localhost/lineup_campus/grafik-rapor', 54, '2025-05-25 13:22:12'),
(4569, 1, 'http://localhost/lineup_campus/grafik-rapor', 141, '2025-05-25 13:24:35'),
(4570, 1, 'http://localhost/lineup_campus/grafik-rapor', 113, '2025-05-25 13:26:30'),
(4571, 1, 'http://localhost/lineup_campus/grafik-rapor', 42, '2025-05-25 13:27:14'),
(4572, 1, 'http://localhost/lineup_campus/grafik-rapor', 43, '2025-05-25 13:28:00'),
(4573, 1, 'http://localhost/lineup_campus/odeme-tipi-grafik-rapor', 348, '2025-05-25 13:33:50'),
(4574, 1, 'http://localhost/lineup_campus/kullanici-basina-gelir-raporu', 93, '2025-05-25 13:35:25'),
(4575, 1, 'http://localhost/lineup_campus/kullanici-basina-gelir-raporu', 9, '2025-05-25 13:35:37'),
(4576, 1, 'http://localhost/lineup_campus/odeme-tipi-grafik-rapor', 125, '2025-05-25 13:37:44'),
(4577, 1, 'http://localhost/lineup_campus/odeme-tipi-grafik-rapor', 95, '2025-05-25 13:39:21'),
(4578, 1, 'http://localhost/lineup_campus/odeme-tipi-grafik-rapor', 44, '2025-05-25 13:40:08'),
(4579, 1, 'http://localhost/lineup_campus/odeme-tipi-grafik-rapor', 31, '2025-05-25 13:40:41'),
(4580, 1, 'http://localhost/lineup_campus/grafik-rapor', 52, '2025-05-25 13:41:35'),
(4581, 1, 'http://localhost/lineup_campus/odeme-tipi-grafik-rapor', 21, '2025-05-25 13:41:58'),
(4582, 1, 'http://localhost/lineup_campus/grafik-rapor', 38, '2025-05-25 13:42:38'),
(4583, 1, 'http://localhost/lineup_campus/kullanici-basina-gelir-raporu', 62, '2025-05-25 13:43:43'),
(4584, 1, 'http://localhost/lineup_campus/kullanici-basina-gelir-raporu', 101, '2025-05-25 13:45:26'),
(4585, 1, 'http://localhost/lineup_campus/kullanici-basina-gelir-raporu', 88, '2025-05-25 13:46:56'),
(4586, 1, 'http://localhost/lineup_campus/kullanici-basina-gelir-raporu', 13, '2025-05-25 13:47:11'),
(4587, 1, 'http://localhost/lineup_campus/odeme-tipi-grafik-rapor', 2, '2025-05-25 13:47:15'),
(4588, 1, 'http://localhost/lineup_campus/kullanici-basina-gelir-raporu', 4, '2025-05-25 13:47:21'),
(4589, 1, 'http://localhost/lineup_campus/kullanici-basina-gelir-raporu', 43, '2025-05-25 13:48:06'),
(4590, 1, 'http://localhost/lineup_campus/paket-kullanim-durumlari', 47, '2025-05-25 13:48:56'),
(4591, 1, 'http://localhost/lineup_campus/paket-kullanim-durumlari', 20, '2025-05-25 13:49:18'),
(4592, 1, 'http://localhost/lineup_campus/paket-kullanim-durumlari', 14, '2025-05-25 13:49:34'),
(4593, 1, 'http://localhost/lineup_campus/abonelik-buyume-oranlari', 59, '2025-05-25 13:50:35'),
(4594, 1, 'http://localhost/lineup_campus/abonelik-buyume-oranlari', 11, '2025-05-25 13:50:48'),
(4595, 1, 'http://localhost/lineup_campus/abonelik-buyume-oranlari', 30, '2025-05-25 13:51:20'),
(4596, 1, 'http://localhost/lineup_campus/abonelik-buyume-oranlari', 56, '2025-05-25 13:52:19'),
(4597, 1, 'http://localhost/lineup_campus/abonelik-buyume-oranlari', 67, '2025-05-25 13:53:28'),
(4598, 1, 'http://localhost/lineup_campus/abonelik-buyume-oranlari', 41, '2025-05-25 13:54:11'),
(4599, 1, 'http://localhost/lineup_campus/abonelik-buyume-oranlari', 36, '2025-05-25 13:54:49'),
(4600, 1, 'http://localhost/lineup_campus/abonelik-buyume-oranlari', 83, '2025-05-25 13:56:14'),
(4601, 1, 'http://localhost/lineup_campus/abonelik-buyume-oranlari', 15, '2025-05-25 13:56:30'),
(4602, 1, 'http://localhost/lineup_campus/paket-kullanim-durumlari', 48, '2025-05-25 13:57:20'),
(4603, 1, 'http://localhost/lineup_campus/paket-kullanim-durumlari', 3, '2025-05-25 13:57:26'),
(4604, 1, 'http://localhost/lineup_campus/abonelik-buyume-oranlari', 132, '2025-05-25 13:59:41'),
(4605, 1, 'http://localhost/lineup_campus/abonelik-buyume-oranlari', 8, '2025-05-25 13:59:51'),
(4606, 1, 'http://localhost/lineup_campus/abonelik-kayip-oranlari', 2, '2025-05-25 13:59:55'),
(4607, 1, 'http://localhost/lineup_campus/paket-bitisi-yaklasan-aboneler', 7, '2025-05-25 14:00:04'),
(4608, 1, 'http://localhost/lineup_campus/abonelik-kayip-oranlari', 70, '2025-05-25 14:01:17'),
(4609, 1, 'http://localhost/lineup_campus/abonelik-kayip-oranlari', 35, '2025-05-25 14:01:53'),
(4610, 1, 'http://localhost/lineup_campus/abonelik-buyume-oranlari', 24, '2025-05-25 14:02:19'),
(4611, 1, 'http://localhost/lineup_campus/abonelik-buyume-oranlari', 3, '2025-05-25 14:02:25'),
(4612, 1, 'http://localhost/lineup_campus/abonelik-kayip-oranlari', 59, '2025-05-25 14:03:26'),
(4613, 1, 'http://localhost/lineup_campus/abonelik-kayip-oranlari', 92, '2025-05-25 14:05:00'),
(4614, 1, 'http://localhost/lineup_campus/abonelik-kayip-oranlari', 17, '2025-05-25 14:05:19'),
(4615, 1, 'http://localhost/lineup_campus/paket-bitisi-yaklasan-aboneler', 1, '2025-05-25 14:05:23');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `topics_lnp`
--

CREATE TABLE `topics_lnp` (
  `id` int(11) NOT NULL,
  `name` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci NOT NULL,
  `content` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci DEFAULT NULL,
  `short_desc` varchar(512) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci NOT NULL,
  `video_url` varchar(512) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `class_id` int(11) NOT NULL,
  `lesson_id` int(11) NOT NULL,
  `unit_id` int(11) NOT NULL,
  `is_question` int(1) NOT NULL,
  `slug` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `image` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `topics_lnp`
--

INSERT INTO `topics_lnp` (`id`, `name`, `content`, `short_desc`, `video_url`, `class_id`, `lesson_id`, `unit_id`, `is_question`, `slug`, `image`, `created_at`, `updated_at`) VALUES
(1, 'Deneme Konu', NULL, 'Deneme', NULL, 3, 4, 19, 0, 'deneme-konu', 'konuDefault.jpg', '2025-04-21 06:52:31', '2025-05-27 11:15:48');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `units_lnp`
--

CREATE TABLE `units_lnp` (
  `id` int(11) NOT NULL,
  `name` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci NOT NULL,
  `short_desc` varchar(512) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci NOT NULL,
  `school_id` int(11) NOT NULL,
  `class_id` int(11) NOT NULL,
  `lesson_id` int(11) NOT NULL,
  `slug` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `photo` varchar(512) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `units_lnp`
--

INSERT INTO `units_lnp` (`id`, `name`, `short_desc`, `school_id`, `class_id`, `lesson_id`, `slug`, `photo`, `created_at`, `updated_at`) VALUES
(1, 'Deneme Ünite', 'Deneme', 1, 3, 1, 'deneme-unite', 'uniteDefault.jpg', '2025-04-21 06:52:11', '2025-04-21 06:52:11'),
(2, 'Ses Öğretimi', 'Ses Öğretimi', 1, 3, 1, 'ses-ogretimi', 'uniteDefault.jpg', '2025-05-14 19:02:18', '2025-05-14 19:02:18'),
(3, 'Dil Bilgisi', 'Dil Bilgisi', 1, 3, 1, 'dil-bilgisi', 'uniteDefault.jpg', '2025-05-14 19:02:53', '2025-05-14 19:02:53'),
(4, 'Uzamsal İlişkiler ve Tartma', 'Uzamsal İlişkiler ve Tartma', 1, 3, 2, 'uzamsal-iliskiler-ve-tartma', 'uniteDefault.jpg', '2025-05-14 19:03:27', '2025-05-14 19:03:27'),
(5, 'Doğal Sayılar', 'Doğal Sayılar', 1, 3, 2, 'dogal-sayilar', 'uniteDefault.jpg', '2025-05-14 19:03:54', '2025-05-14 19:03:54'),
(6, 'Doğal Sayılarla Toplama İşlemi', 'Doğal Sayılarla Toplama İşlemi', 1, 3, 2, 'dogal-sayilarla-toplama-islemi', 'uniteDefault.jpg', '2025-05-14 19:04:25', '2025-05-14 19:04:25'),
(7, 'Doğal Sayılarla Çıkarma İşlemi', 'Doğal Sayılarla Çıkarma İşlemi', 1, 3, 2, 'dogal-sayilarla-cikarma-islemi', 'uniteDefault.jpg', '2025-05-14 19:04:49', '2025-05-14 19:04:49'),
(8, 'Paralarımız', 'Paralarımız', 1, 3, 2, 'paralarimiz', 'uniteDefault.jpg', '2025-05-14 19:05:27', '2025-05-14 19:05:27'),
(9, 'Geometrik Cisimler ve Şekiller', 'Geometrik Cisimler ve Şekiller', 1, 3, 2, 'geometrik-cisimler-ve-sekiller', 'uniteDefault.jpg', '2025-05-14 19:05:49', '2025-05-14 19:05:49'),
(10, 'Geometrik Örüntüler', 'Geometrik Örüntüler', 1, 3, 2, 'geometrik-oruntuler', 'uniteDefault.jpg', '2025-05-14 19:06:16', '2025-05-14 19:06:16'),
(11, 'Veri Toplama ve Değerlendirme', 'Veri Toplama ve Değerlendirme', 1, 3, 2, 'veri-toplama-ve-degerlendirme', 'uniteDefault.jpg', '2025-05-14 19:06:38', '2025-05-14 19:06:38'),
(12, 'Okulumuzda Hayat', 'Okulumuzda Hayat', 1, 3, 3, 'okulumuzda-hayat', 'uniteDefault.jpg', '2025-05-14 19:07:04', '2025-05-14 19:07:04'),
(13, 'Evimizde Hayat', 'Evimizde Hayat', 1, 3, 3, 'evimizde-hayat', 'uniteDefault.jpg', '2025-05-14 19:07:27', '2025-05-14 19:07:27'),
(14, 'Sağlıklı Hayat', 'Sağlıklı Hayat', 1, 3, 3, 'saglikli-hayat', 'uniteDefault.jpg', '2025-05-14 19:07:47', '2025-05-14 19:07:47'),
(15, 'Güvenli Hayat', 'Güvenli Hayat', 1, 3, 3, 'guvenli-hayat', 'uniteDefault.jpg', '2025-05-14 19:08:11', '2025-05-14 19:08:11'),
(16, 'Ülkemizde Hayat', 'Ülkemizde Hayat', 1, 3, 3, 'ulkemizde-hayat', 'uniteDefault.jpg', '2025-05-14 19:08:38', '2025-05-14 19:08:38'),
(17, 'Doğada Hayat', 'Doğada Hayat', 1, 3, 3, 'dogada-hayat', 'uniteDefault.jpg', '2025-05-14 19:09:01', '2025-05-14 19:09:01'),
(18, '1. Ünite', '1. Ünite', 1, 3, 4, '1-unite', 'uniteDefault.jpg', '2025-05-14 19:10:38', '2025-05-14 19:10:38'),
(19, '2. Ünite', '2. Ünite', 1, 3, 4, '2-unite', 'uniteDefault.jpg', '2025-05-14 19:11:09', '2025-05-14 19:11:09'),
(20, '3. Ünite', '3. Ünite', 1, 3, 4, '3-unite', 'uniteDefault.jpg', '2025-05-14 19:11:41', '2025-05-14 19:11:41'),
(21, '4 Ünite', '4 Ünite', 1, 3, 4, '4-unite', 'uniteDefault.jpg', '2025-05-14 19:12:38', '2025-05-14 19:12:38'),
(22, '5 Ünite', '5 Ünite', 1, 3, 4, '5-unite', 'uniteDefault.jpg', '2025-05-14 19:12:55', '2025-05-14 19:12:55'),
(23, '6 Ünite', '6 Ünite', 1, 3, 4, '6-unite', 'uniteDefault.jpg', '2025-05-14 19:13:12', '2025-05-14 19:13:12'),
(24, '7 Ünite', '7 Ünite', 1, 3, 4, '7-unite', 'uniteDefault.jpg', '2025-05-14 19:13:51', '2025-05-14 19:13:51'),
(25, '8. Ünite', '8. Ünite', 1, 3, 2, '8-unite', 'uniteDefault.jpg', '2025-05-14 19:14:43', '2025-05-14 19:14:43'),
(26, '9. Ünite', '9. Ünite', 1, 3, 4, '9-unite', 'uniteDefault.jpg', '2025-05-14 19:15:02', '2025-05-14 19:15:02'),
(27, 'Harf Bilgisi', 'Harf Bilgisi', 1, 4, 1, 'harf-bilgisi', 'uniteDefault.jpg', '2025-05-14 19:15:51', '2025-05-14 19:15:51'),
(28, 'Hece Bilgisi', 'Hece Bilgisi', 1, 3, 1, 'hece-bilgisi', 'uniteDefault.jpg', '2025-05-14 19:16:20', '2025-05-14 19:16:20'),
(29, 'Sözcük Bilgisi', 'Sözcük Bilgisi', 1, 4, 1, 'sozcuk-bilgisi', 'uniteDefault.jpg', '2025-05-14 19:16:38', '2025-05-14 19:16:38'),
(30, 'İsimler (Adlar)', 'İsimler (Adlar)', 1, 4, 1, 'isimler-adlar', 'uniteDefault.jpg', '2025-05-14 19:17:08', '2025-05-14 19:17:08'),
(31, 'Cümle Bilgisi', 'Cümle Bilgisi', 1, 3, 1, 'cumle-bilgisi', 'uniteDefault.jpg', '2025-05-14 19:17:27', '2025-05-14 19:17:27'),
(32, 'Noktalama İşaretleri', 'Noktalama İşaretleri', 1, 4, 1, 'noktalama-isaretleri', 'uniteDefault.jpg', '2025-05-14 19:17:48', '2025-05-14 19:17:48'),
(33, 'Atasözü ve Deyimler', 'Atasözü ve Deyimler', 1, 4, 1, 'atasozu-ve-deyimler', 'uniteDefault.jpg', '2025-05-14 19:18:13', '2025-05-14 19:18:13'),
(34, 'Eylemler (Fiiler)', 'Eylemler (Fiiler)', 1, 4, 1, 'eylemler-fiiler', 'uniteDefault.jpg', '2025-05-14 19:18:43', '2025-05-14 19:18:43'),
(35, 'Tek Başına Anlamı Olmayan Kelimeler', 'Tek Başına Anlamı Olmayan Kelimeler', 1, 4, 1, 'tek-basina-anlami-olmayan-kelimeler', 'uniteDefault.jpg', '2025-05-14 19:19:56', '2025-05-14 19:19:56'),
(36, 'Yazım Kuralları', 'Yazım Kuralları', 1, 4, 1, 'yazim-kurallari', 'uniteDefault.jpg', '2025-05-14 19:20:23', '2025-05-14 19:20:23'),
(37, 'Durum - Hal Ekleri', 'Durum - Hal Ekleri', 1, 4, 1, 'durum-hal-ekleri', 'uniteDefault.jpg', '2025-05-14 19:21:12', '2025-05-14 19:21:12'),
(38, 'Zamirler', 'Zamirler', 1, 4, 1, 'zamirler', 'uniteDefault.jpg', '2025-05-14 19:21:29', '2025-05-14 19:21:29'),
(39, 'Sıfatlar', 'Sıfatlar', 1, 4, 1, 'sifatlar', 'uniteDefault.jpg', '2025-05-14 19:22:00', '2025-05-14 19:22:00'),
(40, 'Metin Türleri', 'Metin Türleri', 1, 4, 1, 'metin-turleri', 'uniteDefault.jpg', '2025-05-14 19:22:20', '2025-05-14 19:22:20'),
(41, 'Doğal Sayılar', 'Doğal Sayılar', 1, 4, 2, 'dogal-sayilar-1', 'uniteDefault.jpg', '2025-05-14 19:22:43', '2025-05-14 19:22:43'),
(42, 'Doğal Sayılarla Toplama ve Çıkarma İşlemleri', 'Doğal Sayılarla Toplama ve Çıkarma İşlemleri', 1, 4, 2, 'dogal-sayilarla-toplama-ve-cikarma-islemleri', 'uniteDefault.jpg', '2025-05-14 19:23:22', '2025-05-14 19:23:22'),
(43, 'Doğal Sayılarda Çıkarma İşlemi', 'Doğal Sayılarda Çıkarma İşlemi', 1, 4, 1, 'dogal-sayilarda-cikarma-islemi', 'uniteDefault.jpg', '2025-05-14 19:24:05', '2025-05-14 19:24:05'),
(44, 'Sıvı Ölçme', 'Sıvı Ölçme', 1, 4, 2, 'sivi-olcme', 'uniteDefault.jpg', '2025-05-14 19:24:27', '2025-05-14 19:24:27'),
(45, 'Geometrik Cisim ve Şekiller', 'Geometrik Cisim ve Şekiller', 1, 4, 2, 'geometrik-cisim-ve-sekiller', 'uniteDefault.jpg', '2025-05-14 19:24:58', '2025-05-14 19:24:58'),
(46, 'Uzamsal İşlemler', 'Uzamsal İşlemler', 1, 4, 2, 'uzamsal-islemler', 'uniteDefault.jpg', '2025-05-14 19:25:19', '2025-05-14 19:25:19'),
(47, 'Doğal Sayılarla Çarpma İşlemi', 'Doğal Sayılarla Çarpma İşlemi', 1, 4, 2, 'dogal-sayilarla-carpma-islemi', 'uniteDefault.jpg', '2025-05-14 19:26:04', '2025-05-14 19:26:04'),
(48, 'Doğal Sayılarla Bölme İşlemi', 'Doğal Sayılarla Bölme İşlemi', 1, 4, 2, 'dogal-sayilarla-bolme-islemi', 'uniteDefault.jpg', '2025-05-14 19:26:28', '2025-05-14 19:26:28'),
(49, 'Kesirler', 'Kesirler', 1, 4, 2, 'kesirler', 'uniteDefault.jpg', '2025-05-14 19:26:50', '2025-05-14 19:26:50'),
(50, 'Zaman Ölçüleri', 'Zaman Ölçüleri', 1, 4, 2, 'zaman-olculeri', 'uniteDefault.jpg', '2025-05-14 19:27:08', '2025-05-14 19:27:08'),
(51, 'Paralarımız', 'Paralarımız', 1, 4, 2, 'paralarimiz-1', 'uniteDefault.jpg', '2025-05-14 19:27:37', '2025-05-14 19:27:37'),
(52, 'Veri Toplama ve Değerlendirme', 'Veri Toplama ve Değerlendirme', 1, 4, 2, 'veri-toplama-ve-degerlendirme-1', 'uniteDefault.jpg', '2025-05-14 19:28:04', '2025-05-14 19:28:04'),
(53, 'Veri Toplama ve Değerlendirme', 'Veri Toplama ve Değerlendirme', 1, 4, 2, 'veri-toplama-ve-degerlendirme-2', 'uniteDefault.jpg', '2025-05-14 19:29:46', '2025-05-14 19:29:46'),
(54, 'Uzunluk Ölçme', 'Uzunluk Ölçme', 1, 4, 2, 'uzunluk-olcme', 'uniteDefault.jpg', '2025-05-14 19:30:09', '2025-05-14 19:30:09'),
(55, 'Tartma', 'Tartma', 1, 4, 2, 'tartma', 'uniteDefault.jpg', '2025-05-14 19:30:26', '2025-05-14 19:30:26'),
(56, 'Okulumuzda Hayat', 'Okulumuzda Hayat', 1, 4, 3, 'okulumuzda-hayat-1', 'uniteDefault.jpg', '2025-05-14 19:31:22', '2025-05-14 19:31:22'),
(57, 'Evimizde Hayat', 'Evimizde Hayat', 1, 4, 3, 'evimizde-hayat-1', 'uniteDefault.jpg', '2025-05-14 19:31:41', '2025-05-14 19:31:41'),
(58, 'Sağlıklı Hayat', 'Sağlıklı Hayat', 1, 4, 3, 'saglikli-hayat-1', 'uniteDefault.jpg', '2025-05-14 19:33:30', '2025-05-14 19:33:30'),
(59, 'Güvenli Hayat', 'Güvenli Hayat', 1, 4, 3, 'guvenli-hayat-1', 'uniteDefault.jpg', '2025-05-14 19:33:50', '2025-05-14 19:33:50'),
(60, 'Ülkemizde Hayat', 'Ülkemizde Hayat', 1, 4, 3, 'ulkemizde-hayat-1', 'uniteDefault.jpg', '2025-05-14 19:34:18', '2025-05-14 19:34:18'),
(61, 'Doğada Hayat', 'Doğada Hayat', 1, 4, 3, 'dogada-hayat-1', 'uniteDefault.jpg', '2025-05-14 19:34:39', '2025-05-14 19:34:39'),
(62, 'Harf Bilgisi', 'Harf Bilgisi', 1, 5, 1, 'harf-bilgisi-1', 'uniteDefault.jpg', '2025-05-14 19:35:45', '2025-05-14 19:35:45'),
(63, 'Hece Bilgisi', 'Hece Bilgisi', 1, 5, 1, 'hece-bilgisi-1', 'uniteDefault.jpg', '2025-05-14 19:36:03', '2025-05-14 19:36:03'),
(64, 'Sözcük Bilgisi', 'Sözcük Bilgisi', 1, 5, 1, 'sozcuk-bilgisi-1', 'uniteDefault.jpg', '2025-05-14 19:39:05', '2025-05-14 19:39:05'),
(65, 'Cümle Bilgisi', 'Cümle Bilgisi', 1, 5, 1, 'cumle-bilgisi-1', 'uniteDefault.jpg', '2025-05-14 19:39:58', '2025-05-14 19:39:58'),
(66, 'Eylemler (Fiiler)', 'Eylemler (Fiiler)', 1, 5, 1, 'eylemler-fiiler-1', 'uniteDefault.jpg', '2025-05-14 19:40:27', '2025-05-14 19:40:27'),
(67, 'İsimler (Adlar)', 'İsimler (Adlar)', 1, 5, 1, 'isimler-adlar-1', 'uniteDefault.jpg', '2025-05-14 19:40:56', '2025-05-14 19:40:56'),
(68, 'Zamirler', 'Zamirler', 1, 5, 1, 'zamirler-1', 'uniteDefault.jpg', '2025-05-14 19:41:12', '2025-05-14 19:41:12'),
(69, 'Sıfatlar', 'Sıfatlar', 1, 5, 1, 'sifatlar-1', 'uniteDefault.jpg', '2025-05-14 19:42:37', '2025-05-14 19:42:37'),
(70, 'Tek Başına Anlamı Olmayan Kelimeler', 'Tek Başına Anlamı Olmayan Kelimeler', 1, 5, 1, 'tek-basina-anlami-olmayan-kelimeler-1', 'uniteDefault.jpg', '2025-05-14 19:44:14', '2025-05-14 19:44:14'),
(71, 'Yazım Kuralları', 'Yazım Kuralları', 1, 5, 1, 'yazim-kurallari-1', 'uniteDefault.jpg', '2025-05-14 19:47:10', '2025-05-14 19:47:10'),
(72, 'Noktalama İşaretleri', 'Noktalama İşaretleri', 1, 5, 1, 'noktalama-isaretleri-1', 'uniteDefault.jpg', '2025-05-14 19:47:31', '2025-05-14 19:47:31'),
(73, 'Durum - Hal Ekleri', 'Durum - Hal Ekleri', 1, 5, 1, 'durum-hal-ekleri-1', 'uniteDefault.jpg', '2025-05-14 19:48:48', '2025-05-14 19:48:48'),
(74, 'Metin Bilgisi', 'Metin Bilgisi', 1, 5, 1, 'metin-bilgisi', 'uniteDefault.jpg', '2025-05-14 19:49:20', '2025-05-14 19:49:20'),
(75, 'Doğal Sayılar', 'Doğal Sayılar', 1, 5, 2, 'dogal-sayilar-2', 'uniteDefault.jpg', '2025-05-14 19:50:05', '2025-05-14 19:50:05'),
(76, 'Doğal Sayılar', 'Doğal Sayılar', 1, 5, 2, 'dogal-sayilar-3', 'uniteDefault.jpg', '2025-05-14 19:51:03', '2025-05-14 19:51:03'),
(77, 'Doğal Sayılarla Toplama İşlemi', 'Doğal Sayılarla Toplama İşlemi', 1, 5, 2, 'dogal-sayilarla-toplama-islemi-1', 'uniteDefault.jpg', '2025-05-14 19:51:35', '2025-05-14 19:51:35'),
(78, 'Doğal Sayılarla Çıkarma İşlemi', 'Doğal Sayılarla Çıkarma İşlemi', 1, 5, 2, 'dogal-sayilarla-cikarma-islemi-1', 'uniteDefault.jpg', '2025-05-14 19:52:01', '2025-05-14 19:52:01'),
(79, 'Doğal Sayılarla Çarpma İşlemi', 'Doğal Sayılarla Çarpma İşlemi', 1, 5, 2, 'dogal-sayilarla-carpma-islemi-1', 'uniteDefault.jpg', '2025-05-14 19:52:25', '2025-05-14 19:52:25'),
(80, 'Doğal Sayılarla Bölme İşlemi', 'Doğal Sayılarla Bölme İşlemi', 1, 5, 2, 'dogal-sayilarla-bolme-islemi-1', 'uniteDefault.jpg', '2025-05-14 19:52:57', '2025-05-14 19:52:57'),
(81, 'Kesirler', 'Kesirler', 1, 5, 2, 'kesirler-1', 'uniteDefault.jpg', '2025-05-14 19:53:15', '2025-05-14 19:53:15'),
(82, 'Zaman Ölçme', 'Zaman Ölçme', 1, 5, 2, 'zaman-olcme', 'uniteDefault.jpg', '2025-05-14 19:53:48', '2025-05-14 19:53:48'),
(83, 'Paralarımız', 'Paralarımız', 1, 5, 2, 'paralarimiz-2', 'uniteDefault.jpg', '2025-05-14 19:58:28', '2025-05-14 19:58:28'),
(84, 'Tartma', 'Tartma', 1, 5, 2, 'tartma-1', 'uniteDefault.jpg', '2025-05-14 19:58:43', '2025-05-14 19:58:43'),
(85, 'Geometrik Cisim ve Şekiller', 'Geometrik Cisim ve Şekiller', 1, 5, 2, 'geometrik-cisim-ve-sekiller-1', 'uniteDefault.jpg', '2025-05-14 20:04:22', '2025-05-14 20:04:22'),
(86, 'Uzunluk Ölçme', 'Uzunluk Ölçme', 1, 5, 2, 'uzunluk-olcme-1', 'uniteDefault.jpg', '2025-05-14 20:04:44', '2025-05-14 20:04:44'),
(87, 'Çevre Ölçme', 'Çevre Ölçme', 1, 5, 2, 'cevre-olcme', 'uniteDefault.jpg', '2025-05-14 20:05:00', '2025-05-14 20:05:00'),
(88, 'Sıvı Ölçme', 'Sıvı Ölçme', 1, 5, 2, 'sivi-olcme-1', 'uniteDefault.jpg', '2025-05-14 20:05:18', '2025-05-14 20:05:18'),
(89, 'Veri Toplama ve Değerlendirme', 'Veri Toplama ve Değerlendirme', 1, 5, 2, 'veri-toplama-ve-degerlendirme-3', 'uniteDefault.jpg', '2025-05-14 20:05:47', '2025-05-14 20:05:47'),
(90, 'Gezegenimizi Tanıyalım', 'Gezegenimizi Tanıyalım', 1, 5, 5, 'gezegenimizi-taniyalim', 'uniteDefault.jpg', '2025-05-14 20:06:32', '2025-05-14 20:06:32'),
(91, 'Beş Duyumuz', 'Beş Duyumuz', 1, 5, 5, 'bes-duyumuz', 'uniteDefault.jpg', '2025-05-14 20:06:53', '2025-05-14 20:06:53'),
(92, 'Kuvveti Tanıyalım', 'Kuvveti Tanıyalım', 1, 5, 5, 'kuvveti-taniyalim', 'uniteDefault.jpg', '2025-05-14 20:18:11', '2025-05-14 20:18:11'),
(93, 'Maddeyi Tanıyalım', 'Maddeyi Tanıyalım', 1, 5, 5, 'maddeyi-taniyalim', 'uniteDefault.jpg', '2025-05-14 20:18:37', '2025-05-14 20:18:37'),
(94, 'Çevremizdeki Işık ve Sesler', 'Çevremizdeki Işık ve Sesler', 1, 5, 5, 'cevremizdeki-isik-ve-sesler', 'uniteDefault.jpg', '2025-05-14 20:18:56', '2025-05-14 20:18:56'),
(95, 'Canlılar Dünyasına Yolculuk', 'Canlılar Dünyasına Yolculuk', 1, 5, 5, 'canlilar-dunyasina-yolculuk', 'uniteDefault.jpg', '2025-05-14 20:20:29', '2025-05-14 20:20:29'),
(96, 'Elektrikli Araçlar', 'Elektrikli Araçlar', 1, 5, 5, 'elektrikli-araclar', 'uniteDefault.jpg', '2025-05-14 20:21:06', '2025-05-14 20:21:06'),
(97, 'Okulumuzda Hayat', 'Okulumuzda Hayat', 1, 5, 3, 'okulumuzda-hayat-2', 'uniteDefault.jpg', '2025-05-14 20:21:48', '2025-05-14 20:21:48'),
(98, 'Evimizde Hayat', 'Evimizde Hayat', 1, 5, 3, 'evimizde-hayat-2', 'uniteDefault.jpg', '2025-05-14 20:22:14', '2025-05-14 20:22:14'),
(99, 'Sağlıklı Hayat', 'Sağlıklı Hayat', 1, 5, 5, 'saglikli-hayat-2', 'uniteDefault.jpg', '2025-05-14 20:22:31', '2025-05-14 20:22:31'),
(100, 'Güvenli Hayat', 'Güvenli Hayat', 1, 5, 5, 'guvenli-hayat-2', 'uniteDefault.jpg', '2025-05-14 20:22:59', '2025-05-14 20:22:59'),
(101, 'Güvenli Hayat', 'Güvenli Hayat', 1, 5, 3, 'guvenli-hayat-3', 'uniteDefault.jpg', '2025-05-14 20:24:52', '2025-05-14 20:24:52'),
(102, 'Sağlıklı Hayat', 'Sağlıklı Hayat', 1, 5, 3, 'saglikli-hayat-3', 'uniteDefault.jpg', '2025-05-14 20:25:16', '2025-05-14 20:25:16'),
(103, 'Ülkemizde Hayat', 'Ülkemizde Hayat', 1, 5, 3, 'ulkemizde-hayat-2', 'uniteDefault.jpg', '2025-05-14 20:25:35', '2025-05-14 20:25:35'),
(104, 'Doğada Hayat', 'Doğada Hayat', 1, 5, 3, 'dogada-hayat-2', 'uniteDefault.jpg', '2025-05-14 20:25:53', '2025-05-14 20:25:53'),
(105, 'Greeting', 'Greeting', 1, 5, 4, 'greeting', 'uniteDefault.jpg', '2025-05-14 20:26:08', '2025-05-14 20:26:08'),
(106, 'Family Members', 'Family Members', 1, 5, 4, 'family-members', 'uniteDefault.jpg', '2025-05-14 20:26:22', '2025-05-14 20:26:22'),
(107, 'People I love', 'People I love', 1, 5, 4, 'people-i-love', 'uniteDefault.jpg', '2025-05-14 20:26:39', '2025-05-14 20:26:39'),
(108, 'Feelings', 'Feelings', 1, 5, 4, 'feelings', 'uniteDefault.jpg', '2025-05-14 20:26:53', '2025-05-14 20:26:53'),
(109, 'Toys and Games', 'Toys and Games', 1, 5, 4, 'toys-and-games', 'uniteDefault.jpg', '2025-05-14 20:27:07', '2025-05-14 20:27:07'),
(110, 'My House', 'My House', 1, 5, 4, 'my-house', 'uniteDefault.jpg', '2025-05-14 20:27:20', '2025-05-14 20:27:20'),
(111, 'In My City', 'In My City', 1, 5, 4, 'in-my-city', 'uniteDefault.jpg', '2025-05-14 20:27:44', '2025-05-14 20:27:44'),
(112, 'Transportations', 'Transportations', 1, 5, 4, 'transportations', 'uniteDefault.jpg', '2025-05-14 20:27:57', '2025-05-14 20:27:57'),
(113, 'Weather', 'Weather', 1, 5, 4, 'weather', 'uniteDefault.jpg', '2025-05-14 20:28:11', '2025-05-14 20:28:11'),
(114, 'Nature', 'Nature', 1, 5, 4, 'nature', 'uniteDefault.jpg', '2025-05-14 20:28:23', '2025-05-14 20:28:23'),
(115, 'Classroom Rules', 'Classroom Rules', 1, 6, 4, 'classroom-rules', 'uniteDefault.jpg', '2025-05-14 20:43:10', '2025-05-14 20:43:10'),
(116, 'Numbers', 'Numbers', 1, 6, 4, 'numbers', 'uniteDefault.jpg', '2025-05-14 20:43:29', '2025-05-14 20:43:29'),
(117, 'Nationalities', 'Nationalities', 1, 6, 4, 'nationalities', 'uniteDefault.jpg', '2025-05-14 20:44:04', '2025-05-14 20:44:04'),
(118, 'Free Time', 'Free Time', 1, 6, 4, 'free-time', 'uniteDefault.jpg', '2025-05-14 20:44:17', '2025-05-14 20:44:17'),
(119, 'My Day', 'My Day', 1, 6, 4, 'my-day', 'uniteDefault.jpg', '2025-05-14 20:44:33', '2025-05-14 20:44:33'),
(120, 'Cartoon Characters', 'Cartoon Characters', 1, 6, 4, 'cartoon-characters', 'uniteDefault.jpg', '2025-05-14 20:44:48', '2025-05-14 20:44:48'),
(121, 'Fun with Science', 'Fun with Science', 1, 6, 4, 'fun-with-science', 'uniteDefault.jpg', '2025-05-14 20:45:00', '2025-05-14 20:45:00'),
(122, 'My Clothes', 'My Clothes', 1, 6, 4, 'my-clothes', 'uniteDefault.jpg', '2025-05-14 20:45:16', '2025-05-14 20:45:16'),
(123, 'My Friends', 'My Friends', 1, 6, 4, 'my-friends', 'uniteDefault.jpg', '2025-05-14 20:45:30', '2025-05-14 20:45:30'),
(124, 'Birey ve Toplum', 'Birey ve Toplum', 1, 6, 6, 'birey-ve-toplum', 'uniteDefault.jpg', '2025-05-14 20:47:02', '2025-05-14 20:47:02'),
(125, 'Kültür ve Miras', 'Kültür ve Miras', 1, 6, 6, 'kultur-ve-miras', 'uniteDefault.jpg', '2025-05-14 20:47:19', '2025-05-14 20:47:19'),
(126, 'İnsanlar, Yerler ve Çevreler', 'İnsanlar, Yerler ve Çevreler', 1, 6, 6, 'insanlar-yerler-ve-cevreler', 'uniteDefault.jpg', '2025-05-14 20:47:40', '2025-05-14 20:47:40'),
(127, 'Bilim, Teknoloji ve Toplum', 'Bilim, Teknoloji ve Toplum', 1, 6, 6, 'bilim-teknoloji-ve-toplum', 'uniteDefault.jpg', '2025-05-14 20:48:03', '2025-05-14 20:48:03'),
(128, 'Üretim, Dağıtım ve Tüketim', 'Üretim, Dağıtım ve Tüketim', 1, 6, 6, 'uretim-dagitim-ve-tuketim', 'uniteDefault.jpg', '2025-05-14 20:48:29', '2025-05-14 20:48:29'),
(129, 'Etkin Vatandaşlık', 'Etkin Vatandaşlık', 1, 6, 6, 'etkin-vatandaslik', 'uniteDefault.jpg', '2025-05-14 20:48:50', '2025-05-14 20:48:50'),
(130, 'Kültürel Bağlantılar', 'Kültürel Bağlantılar', 1, 6, 6, 'kulturel-baglantilar', 'uniteDefault.jpg', '2025-05-14 20:49:13', '2025-05-14 20:49:13'),
(131, 'Yer Kabuğu ve Dünya’mızın Hareketleri', 'Yer Kabuğu ve Dünya’mızın Hareketleri', 1, 6, 5, 'yer-kabugu-ve-dunya-mizin-hareketleri', 'uniteDefault.jpg', '2025-05-14 20:51:26', '2025-05-14 20:51:26'),
(132, 'Besinlerimiz', 'Besinlerimiz', 1, 6, 5, 'besinlerimiz', 'uniteDefault.jpg', '2025-05-14 20:51:48', '2025-05-14 20:51:48'),
(133, 'Kuvveti Tanıyalım', 'Kuvveti Tanıyalım', 1, 6, 5, 'kuvveti-taniyalim-1', 'uniteDefault.jpg', '2025-05-14 20:52:12', '2025-05-14 20:52:12'),
(134, 'Maddenin Özellikleri', 'Maddenin Özellikleri', 1, 6, 5, 'maddenin-ozellikleri', 'uniteDefault.jpg', '2025-05-14 20:52:30', '2025-05-14 20:52:30'),
(135, 'Aydınlatma ve Ses Teknolojileri', 'Aydınlatma ve Ses Teknolojileri', 1, 6, 5, 'aydinlatma-ve-ses-teknolojileri', 'uniteDefault.jpg', '2025-05-14 20:52:51', '2025-05-14 20:52:51'),
(136, 'İnsan ve Çevre', 'İnsan ve Çevre', 1, 6, 5, 'insan-ve-cevre', 'uniteDefault.jpg', '2025-05-14 20:53:07', '2025-05-14 20:53:07'),
(137, 'Basit Elektrik Devreleri', 'Basit Elektrik Devreleri', 1, 6, 5, 'basit-elektrik-devreleri', 'uniteDefault.jpg', '2025-05-14 20:53:34', '2025-05-14 20:53:34'),
(138, 'Doğal Sayılar', 'Doğal Sayılar', 1, 6, 2, 'dogal-sayilar-4', 'uniteDefault.jpg', '2025-05-14 20:54:33', '2025-05-14 20:54:33'),
(139, 'Doğal Sayılarla Toplama İşlemi', 'Doğal Sayılarla Toplama İşlemi', 1, 6, 2, 'dogal-sayilarla-toplama-islemi-2', 'uniteDefault.jpg', '2025-05-14 21:01:06', '2025-05-14 21:01:06'),
(140, 'Doğal Sayılarla Çıkarma İşlemi', 'Doğal Sayılarla Çıkarma İşlemi', 1, 6, 2, 'dogal-sayilarla-cikarma-islemi-2', 'uniteDefault.jpg', '2025-05-14 21:01:25', '2025-05-14 21:01:25'),
(141, 'Doğal Sayılarla Çarpma İşlemi', 'Doğal Sayılarla Çarpma İşlemi', 1, 6, 2, 'dogal-sayilarla-carpma-islemi-2', 'uniteDefault.jpg', '2025-05-14 21:01:46', '2025-05-14 21:01:46'),
(142, 'Doğal Sayılarla Bölme İşlemi', 'Doğal Sayılarla Bölme İşlemi', 1, 6, 2, 'dogal-sayilarla-bolme-islemi-2', 'uniteDefault.jpg', '2025-05-14 21:02:09', '2025-05-14 21:02:09'),
(143, 'Kesirler', 'Kesirler', 1, 6, 2, 'kesirler-2', 'uniteDefault.jpg', '2025-05-14 21:02:26', '2025-05-14 21:02:26'),
(144, 'Geometrik Cisimler ve Şekiller', 'Geometrik Cisimler ve Şekiller', 1, 6, 2, 'geometrik-cisimler-ve-sekiller-1', 'uniteDefault.jpg', '2025-05-14 21:02:56', '2025-05-14 21:02:56'),
(145, 'Geometride Temel Kavramlar ve Uzamsal İlişkiler', 'Geometride Temel Kavramlar ve Uzamsal İlişkiler', 1, 6, 2, 'geometride-temel-kavramlar-ve-uzamsal-iliskiler', 'uniteDefault.jpg', '2025-05-14 21:03:35', '2025-05-14 21:03:35'),
(146, 'Uzunluk Ölçme', 'Uzunluk Ölçme', 1, 6, 2, 'uzunluk-olcme-2', 'uniteDefault.jpg', '2025-05-14 21:04:01', '2025-05-14 21:04:01'),
(147, 'Çevre Ölçme', 'Çevre Ölçme', 1, 6, 2, 'cevre-olcme-1', 'uniteDefault.jpg', '2025-05-14 21:04:17', '2025-05-14 21:04:17'),
(148, 'Tartma', 'Tartma', 1, 6, 2, 'tartma-2', 'uniteDefault.jpg', '2025-05-14 21:04:33', '2025-05-14 21:04:33'),
(149, 'Sıvı Ölçme', 'Sıvı Ölçme', 1, 6, 2, 'sivi-olcme-2', 'uniteDefault.jpg', '2025-05-14 21:04:51', '2025-05-14 21:04:51'),
(150, 'Harf Bilgisi', 'Harf Bilgisi', 1, 6, 1, 'harf-bilgisi-2', 'uniteDefault.jpg', '2025-05-14 21:05:34', '2025-05-14 21:05:34'),
(151, 'Hece Bilgisi', 'Hece Bilgisi', 1, 6, 1, 'hece-bilgisi-2', 'uniteDefault.jpg', '2025-05-14 21:05:51', '2025-05-14 21:05:51'),
(152, 'Sözcük Bilgisi', 'Sözcük Bilgisi', 1, 6, 1, 'sozcuk-bilgisi-2', 'uniteDefault.jpg', '2025-05-14 21:06:09', '2025-05-14 21:06:09'),
(153, 'Cümle Bilgisi', 'Cümle Bilgisi', 1, 6, 1, 'cumle-bilgisi-2', 'uniteDefault.jpg', '2025-05-14 21:06:29', '2025-05-14 21:06:29'),
(154, 'Eylemler (Fiiller)', 'Eylemler (Fiiller)', 1, 6, 1, 'eylemler-fiiller', 'uniteDefault.jpg', '2025-05-14 21:06:56', '2025-05-14 21:06:56'),
(155, 'İsimler (Adlar)', 'İsimler (Adlar)', 1, 6, 1, 'isimler-adlar-2', 'uniteDefault.jpg', '2025-05-14 21:07:19', '2025-05-14 21:07:19'),
(156, 'Zamirler', 'Zamirler', 1, 6, 1, 'zamirler-2', 'uniteDefault.jpg', '2025-05-14 21:07:35', '2025-05-14 21:07:35'),
(157, 'Sıfatlar', 'Sıfatlar', 1, 6, 1, 'sifatlar-2', 'uniteDefault.jpg', '2025-05-14 21:08:28', '2025-05-14 21:08:28'),
(158, 'Tek Başına Anlamı Olmayan Kelimeler', 'Tek Başına Anlamı Olmayan Kelimeler', 1, 6, 1, 'tek-basina-anlami-olmayan-kelimeler-2', 'uniteDefault.jpg', '2025-05-14 21:08:53', '2025-05-14 21:08:53'),
(159, 'Yazım Kuralları', 'Yazım Kuralları', 1, 6, 1, 'yazim-kurallari-2', 'uniteDefault.jpg', '2025-05-14 21:09:10', '2025-05-14 21:09:10'),
(160, 'Noktalama İşaretleri', 'Noktalama İşaretleri', 1, 6, 1, 'noktalama-isaretleri-2', 'uniteDefault.jpg', '2025-05-14 21:09:28', '2025-05-14 21:09:28'),
(161, 'Durum - Hal Ekleri', 'Durum - Hal Ekleri', 1, 6, 1, 'durum-hal-ekleri-2', 'uniteDefault.jpg', '2025-05-14 21:09:59', '2025-05-14 21:09:59'),
(162, 'Metin Bilgisi', 'Metin Bilgisi', 1, 6, 1, 'metin-bilgisi-1', 'uniteDefault.jpg', '2025-05-14 21:10:15', '2025-05-14 21:10:15'),
(163, 'Dil Bilgisi', 'Dil Bilgisi', 1, 6, 1, 'dil-bilgisi-1', 'uniteDefault.jpg', '2025-05-14 21:10:32', '2025-05-14 21:10:32'),
(164, 'Sağlıklı Hayat', 'Sağlıklı Hayat', 1, 4, 3, 'saglikli-hayat-4', 'uniteDefault.jpg', '2025-05-15 08:14:33', '2025-05-15 08:14:33'),
(165, 'Güvenli Hayat', 'Güvenli Hayat', 1, 4, 3, 'guvenli-hayat-4', 'uniteDefault.jpg', '2025-05-15 08:14:47', '2025-05-15 08:14:47'),
(166, 'Ülkemizde Hayat', 'Ülkemizde Hayat', 1, 4, 3, 'ulkemizde-hayat-3', 'uniteDefault.jpg', '2025-05-15 08:15:16', '2025-05-15 08:15:16'),
(167, 'Doğada Hayat', 'Doğada Hayat', 1, 4, 3, 'dogada-hayat-3', 'uniteDefault.jpg', '2025-05-15 08:16:49', '2025-05-15 08:16:49'),
(168, 'Words', 'Words', 1, 4, 4, 'words', 'uniteDefault.jpg', '2025-05-15 11:24:29', '2025-05-15 11:24:29'),
(169, 'Friends', 'Friends', 1, 4, 4, 'friends', 'uniteDefault.jpg', '2025-05-15 11:24:40', '2025-05-15 11:24:40'),
(170, 'In the Classroom', 'In the Classroom', 1, 4, 4, 'in-the-classroom', 'uniteDefault.jpg', '2025-05-15 11:25:01', '2025-05-15 11:25:01'),
(171, 'Numbers', 'Numbers', 1, 4, 4, 'numbers-1', 'uniteDefault.jpg', '2025-05-15 11:25:50', '2025-05-15 11:25:50'),
(172, 'Colors', 'Colors', 1, 4, 4, 'colors', 'uniteDefault.jpg', '2025-05-15 11:26:30', '2025-05-15 11:26:30'),
(173, 'At the Playground', 'At the Playground', 1, 4, 4, 'at-the-playground', 'uniteDefault.jpg', '2025-05-15 11:26:51', '2025-05-15 11:26:51'),
(174, 'Body Parts', 'Body Parts', 1, 4, 4, 'body-parts', 'uniteDefault.jpg', '2025-05-15 11:27:03', '2025-05-15 11:27:03'),
(175, 'Pets', 'Pets', 1, 4, 4, 'pets', 'uniteDefault.jpg', '2025-05-15 11:27:14', '2025-05-15 11:27:14'),
(176, 'Fruits', 'Fruits', 1, 4, 4, 'fruits', 'uniteDefault.jpg', '2025-05-15 11:27:24', '2025-05-15 11:27:24'),
(177, 'Animals', 'Animals', 1, 4, 4, 'animals', 'uniteDefault.jpg', '2025-05-15 11:27:38', '2025-05-15 11:27:38');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `userroles_lnp`
--

CREATE TABLE `userroles_lnp` (
  `id` int(11) NOT NULL,
  `name` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci NOT NULL,
  `slug` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `userroles_lnp`
--

INSERT INTO `userroles_lnp` (`id`, `name`, `slug`, `created_at`, `updated_at`) VALUES
(1, 'Süper Admin', 'super-admin', '2025-04-24 07:38:46', '2025-04-24 09:39:23'),
(2, 'Öğrenciler', 'ogrenciler', '2025-04-24 07:36:45', '2025-04-24 09:39:19'),
(3, 'Okul Koordinatörleri', 'okul-koordinatorleri', '2025-04-24 07:36:45', '2025-04-24 09:39:16'),
(4, 'Öğretmenler', 'ogretmenler', '2025-04-24 07:36:45', '2025-04-24 09:39:12'),
(5, 'Veliler', 'veliler', '2025-04-24 07:36:45', '2025-04-24 09:39:07'),
(6, 'Teknik Ekip Lider', 'teknik-ekip-lider', '2025-05-12 09:01:21', '2025-05-12 09:01:21'),
(7, 'Teknik Ekip', 'teknik-ekip', '2025-05-12 09:01:21', '2025-05-12 09:01:21'),
(8, 'Okul Admini', 'okul-admini', '2025-05-18 14:11:54', '2025-05-18 14:11:54'),
(10001, 'Ana Okulu Öğretmeni', 'ana-okulu-ogretmeni', '2025-05-08 10:28:02', '2025-05-08 10:28:12'),
(10002, 'Ana Okulu Öğrencisi', 'ana-okulu-ogrencisi', '2025-05-08 10:28:02', '2025-05-08 10:28:12');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `users_lnp`
--

CREATE TABLE `users_lnp` (
  `id` int(11) NOT NULL,
  `name` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci NOT NULL,
  `surname` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci NOT NULL,
  `username` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` int(11) NOT NULL,
  `active` int(11) NOT NULL,
  `telephone` varchar(21) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `birth_date` date DEFAULT NULL,
  `gender` varchar(5) NOT NULL,
  `identity_id` varchar(11) DEFAULT NULL,
  `photo` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci DEFAULT NULL,
  `district` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci DEFAULT NULL,
  `postcode` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci DEFAULT NULL,
  `city` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci DEFAULT NULL,
  `school_id` int(11) DEFAULT NULL,
  `teacher_id` int(11) DEFAULT NULL,
  `student_id` int(11) DEFAULT NULL,
  `class_id` int(11) DEFAULT NULL,
  `lesson_id` int(11) DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `child_id` int(11) DEFAULT NULL,
  `package_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `subscribed_at` datetime DEFAULT NULL,
  `subscribed_end` timestamp NULL DEFAULT NULL,
  `subscribed_updated` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `users_lnp`
--

INSERT INTO `users_lnp` (`id`, `name`, `surname`, `username`, `email`, `password`, `role`, `active`, `telephone`, `birth_date`, `gender`, `identity_id`, `photo`, `address`, `district`, `postcode`, `city`, `school_id`, `teacher_id`, `student_id`, `class_id`, `lesson_id`, `parent_id`, `child_id`, `package_id`, `created_at`, `updated_at`, `subscribed_at`, `subscribed_end`, `subscribed_updated`) VALUES
(1, 'Aydın', 'Epikman', 'aepikman', 'aepikman@gmail.com', '$2y$12$b1f8LLSQO7uf8MSSvsAt1ezPQ5fnJ3Ebblp3RZRbaH.V9LjLr0PLG', 1, 1, '', NULL, '', '34306744382', 'kiz.jpg', '', '', '', '', 1, NULL, NULL, 0, NULL, NULL, NULL, 0, '2025-02-18 09:12:48', '2025-05-21 13:45:09', NULL, '2025-05-17 15:39:01', NULL),
(25, 'Öğrenci', 'Soyad', 'ogrenci1', 'ogrenci@ogrenci.com', '$2y$12$b1f8LLSQO7uf8MSSvsAt1ezPQ5fnJ3Ebblp3RZRbaH.V9LjLr0PLG', 2, 1, '012345678', '2017-12-01', 'Erkek', NULL, 'soyad-73695.png', '', '', '', '', 1, NULL, NULL, 3, NULL, NULL, NULL, 0, '2025-03-13 13:39:24', '2025-05-23 11:47:48', NULL, '2025-05-21 15:39:01', NULL),
(26, 'Aydın', 'Epikman', 'dsafdsfsafdasiö', 'aepikman@gmail.comdasdsa', '$2y$10$dDIuppSZsH3WK3DsK/Vwq.0WOfGmIIWWJKHZQfYy8usJzO9KqFhS2', 2, 1, '05323854438', '2021-11-01', 'Erkek', NULL, 'erkek.jpg', '', '', '', '', 1, NULL, NULL, 3, NULL, 80, NULL, 0, '2025-03-13 14:06:35', '2025-05-23 11:48:16', NULL, '2025-04-21 15:39:01', NULL),
(27, 'Fatma', 'Ataman', 'fata', 'deneme@deneme.coma', '$2y$10$y4Jab.7GNGKqUA/QK5MwyeDdqER83lWnM/E5x9cZwLabnm8/Nolxm', 2, 1, '1234567', '2023-12-31', 'Erkek', NULL, 'erkek.jpg', '', '', '', '', 1, NULL, NULL, 6, NULL, 41, NULL, 0, '2025-03-13 14:07:37', '2025-04-07 11:54:16', NULL, NULL, NULL),
(28, 'sda', 'fasmkfsajkl', 'fndkjfls', 'sabagrup@outlook.com', '$2y$10$SmNr5tg0TsAMsDCFxKPST.vK2w85ocbw9DPNs0CEpSP5o4Mce6n.q', 2, 0, '54545454', '2025-12-01', 'Kız', NULL, 'erkek.jpg', '', '', '', '', 65, NULL, NULL, 3, NULL, NULL, NULL, 0, '2025-03-13 14:08:14', '2025-05-19 12:07:37', NULL, NULL, NULL),
(29, 'Deneme', 'Deneme', 'kdsakdsalkdal', 'deneme@deneme.comdasdsad', '$2y$10$WMbe2MVkAOcDjQh5sMUnM.6NNjxXxEP45KHmF/LIsfMkOk6.Mlal6', 2, 0, '1234567', '2024-11-30', 'Kız', NULL, 'kiz.jpg', '', '', '', '', 1, NULL, NULL, 3, NULL, NULL, NULL, 0, '2025-03-13 14:09:03', '2025-04-24 10:12:45', NULL, NULL, NULL),
(30, 'Fotolu', 'biri', 'fotolu', 'sertanaygun1977@gmail.comfdsdf', '$2y$10$MDNeSTdh/wg4dSMS4ZeocOMxV5oVnhUCgs/DkZnPfSX1tt6zb44SC', 2, 0, '1454845', '2006-12-01', 'Erkek', NULL, 'erkek.jpg', '', '', '', '', 1, NULL, NULL, 3, NULL, NULL, NULL, 0, '2025-03-13 14:12:50', '2025-03-13 16:11:02', NULL, NULL, NULL),
(31, 'jsdja', 'jkfdjskdf', 'qfdfdskjfk', 'dsankdashjfsa@gmail.com', '$2y$10$4MgNwbjDfYU.VVn.31y/EOBTR77OeWCO/PXYva5WJRn7CXevzUaDm', 2, 0, '1564545', '2025-12-31', 'Erkek', NULL, 'erkek.jpg', '', '', '', '', 1, NULL, NULL, 3, NULL, NULL, NULL, 0, '2025-03-13 14:13:52', '2025-04-24 10:58:14', NULL, NULL, NULL),
(32, 'Deneme', 'Deneme', 'denenne', 'epikman@gmail.com', '$2y$12$b1f8LLSQO7uf8MSSvsAt1ezPQ5fnJ3Ebblp3RZRbaH.V9LjLr0PLG', 2, 1, '1234567', '2025-01-01', 'Erkek', NULL, 'erkek.jpg', '', '', '', '', 1, NULL, NULL, 3, NULL, NULL, NULL, 0, '2025-03-16 18:42:18', '2025-05-16 08:23:37', NULL, NULL, NULL),
(33, 'Okul', 'Yönetici', 'okulyonetici', 'ankarayonetici@okul.com', '$2y$12$b1f8LLSQO7uf8MSSvsAt1ezPQ5fnJ3Ebblp3RZRbaH.V9LjLr0PLG', 3, 1, '03121234567', '1998-01-01', 'Kız', NULL, 'kiz.jpg', '', '', '', '', 67, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-03-17 12:49:14', '2025-03-17 13:04:32', NULL, NULL, NULL),
(34, 'Dördüncü', 'Sınıf', 'dorduncusinif', 'deneme@epikm11.com', '$2y$10$/7bA3rUbI785O9o5bAWDS.l.AUGxaahMNLZvsZlxh63HxudV3f0h.', 2, 1, '05325596570', '1999-11-11', 'Erkek', NULL, 'erkek.jpg', '', '', '', '', 1, NULL, NULL, 6, NULL, NULL, NULL, 0, '2025-03-17 14:42:12', '2025-03-17 14:42:12', NULL, NULL, NULL),
(35, 'Okul', 'Öğretmen', 'okulogretmen', 'ankaraogretmen@okul.com', '$2y$12$b1f8LLSQO7uf8MSSvsAt1ezPQ5fnJ3Ebblp3RZRbaH.V9LjLr0PLG', 4, 1, '03121234567', '1998-01-01', 'Kız', NULL, 'kiz.jpg', '', '', '', '', 67, NULL, NULL, 6, 1, NULL, NULL, 0, '2025-03-17 12:49:14', '2025-03-17 13:04:32', NULL, NULL, NULL),
(37, 'Okul', 'Öğretmen2', 'okulogretmen2', 'ogretmen@ogretmen.com', '$2y$10$dCM/umIQVytF4F2bZkN20eb.etea3GBWB7/GTZ6XzY11HPmj26sQO', 4, 1, '123154584', '2002-01-01', 'Erkek', NULL, 'erkek.jpg', '', '', '', '', 67, NULL, NULL, 3, 1, NULL, NULL, 0, '2025-03-17 21:42:16', '2025-03-17 21:42:16', NULL, NULL, NULL),
(38, 'Uğur', 'Görür', 'ugurgorur', 'ugurgorur@fafa.com', '$2y$10$gWNvZdJlu2nQV6ejvE2/OOA8yTIZLQl8TZY8qWYeHFmPtZRr/xLt6', 2, 1, '1754548', '2013-01-01', 'Erkek', NULL, 'erkek.jpg', '', '', '', '', 67, NULL, NULL, 3, NULL, 40, NULL, 0, '2025-03-18 07:25:32', '2025-04-04 07:02:43', NULL, NULL, NULL),
(40, 'Veli', 'Soyad', 'velisoyad', 'velisoyadi@gmail.com', '$2y$10$Zfe5a6z2o4Tr3jT4/L5r6ee1Xhf2i1eMCNw6reBBJ9HNFu4JOmJM.', 5, 1, '313134232321', '2000-01-01', 'Erkek', NULL, 'erkek.jpg', '', '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-04-04 07:02:43', '2025-04-04 07:02:43', NULL, NULL, NULL),
(41, 'Ayşe', 'Ataman', 'ayseAtaman', 'ayseAtaman@gmail.com', '$2y$10$vyrQTpVw5jTuYjJria0rBeIHJp2Fte.F/QSTOJUrBoUoI3sJMEFf6', 5, 1, '154545451', '1995-02-08', 'Kız', NULL, 'kiz.jpg', '', '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, 27, 0, '2025-04-07 11:54:16', '2025-04-07 11:54:16', NULL, NULL, NULL),
(42, 'Jeo', 'Log', 'jeolo@for.com', 'nnn@fma.com', '$2y$10$4MC8TI9DuXPQmVrtNo0LCeTJhxfuh/EmCmHXYi9SNRm/ktvkAykY6', 2, 1, '1234568', '2025-04-08', 'Kız', NULL, 'kiz.jpg', '', '', '', '', 1, NULL, NULL, 3, NULL, NULL, NULL, 0, '2025-04-24 11:36:35', '2025-04-24 11:36:35', NULL, NULL, NULL),
(68, 'Ana Okulu ', 'Öğrencisi', 'aokul', 'aokul@gmail.com', '$2y$12$b1f8LLSQO7uf8MSSvsAt1ezPQ5fnJ3Ebblp3RZRbaH.V9LjLr0PLG', 10001, 1, '', NULL, '', '13213123', 'kiz.jpg', '', '', '', '', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '2025-02-18 09:12:48', '2025-05-12 06:45:19', NULL, NULL, NULL),
(82, 'Ana Okulu ', 'Öğretmeni', 'aokul2', 'aokul2@gmail.com', '$2y$12$b1f8LLSQO7uf8MSSvsAt1ezPQ5fnJ3Ebblp3RZRbaH.V9LjLr0PLG', 10002, 1, '', NULL, '', '9995547', 'kiz.jpg', '', '', '', '', NULL, NULL, NULL, 10, NULL, NULL, NULL, 0, '2025-02-18 06:12:48', '2025-05-19 06:18:22', NULL, NULL, NULL),
(91, 'Rana', 'Mcguire', 'vejogym', 'javasu@mailinator.com', '$2y$10$Sb9wV3PeCgALK9k1kWaU/unrKf9t.uhtmx6XvfZv/SCN1vpA8bUZa', 2, 1, '180552', '1977-06-19', 'Kız', '34302744154', 'kiz.jpg', 'Sed fugiat est dolo', 'Quia possimus dolor', 'Cupiditate nesciunt', 'Giresun', 1, NULL, NULL, 3, NULL, 92, NULL, 5, '2025-05-17 16:39:02', '2025-05-21 13:45:51', '2025-05-17 18:39:01', '2026-05-17 19:37:02', NULL),
(92, 'MacKensie', 'Keith', 'vejogym-veli', NULL, '$2y$10$5yG5kS3bXzLLiQhX.foCQO6CykbQ40Hsgl8qPDK5hvO0UmhpJYqpe', 5, 1, '', NULL, '', NULL, '', '', '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, 91, 0, '2025-05-17 16:39:02', '2025-05-17 16:39:02', NULL, NULL, NULL),
(93, 'Bert', 'Booker', 'ludaj', 'birar@mailinator.com', '$2y$10$K4eiqbFpjc3zsBtKxTfHPOBSdggvjUzZpvvqcR22HhhUt0L5QBcI6', 2, 1, '05323854438', '1987-01-09', 'Kız', '34324743718', 'kiz.jpg', 'Ullam officia alias', 'Et aut voluptate rep', 'Quas consectetur no', 'Bitlis', 1, NULL, NULL, 11, NULL, 94, NULL, 2, '2025-05-17 18:16:03', '2025-05-26 08:53:25', '2025-05-17 22:37:02', '2025-05-26 10:46:14', NULL),
(94, 'Mechelle', 'Carney', 'ludaj-veli', NULL, '$2y$10$di/AE2XrPtZRtIuAThOM/ObGcCv45thENVABnZ9X1uuht6P1r5GJu', 5, 1, '', NULL, '', NULL, '', '', '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, 93, 0, '2025-05-17 18:16:03', '2025-05-17 18:16:03', NULL, NULL, NULL),
(100, 'Aydın', 'Epikman', 'xacopiqym', 'aepikmasnadb@gmail.com', '$2y$10$cV3ZluGcJ5qB/w8Y8rvWL.GdTkIi7DrH7eP2KlF/aCGqmGwFDmnhq', 2, 1, '5531105200', '1992-04-06', 'Kız', '21574521838', 'kiz.jpg', 'Pariatur Sed tempor', 'Illum itaque vel do', 'Nam dolor nihil est', 'Çanakkale', 1, NULL, NULL, 5, NULL, 101, NULL, 14, '2025-05-18 09:29:13', '2025-05-24 10:47:02', '2025-05-18 13:46:14', '2025-05-24 10:46:14', NULL),
(101, 'Azalia', 'Reid', 'xacopiqym-veli', NULL, '$2y$10$WTPxMKy3kA17hOkqoUb4x.P9.Aw6DL0Tj.gLRYV95AeNsYjPqpi9O', 5, 1, '', NULL, '', NULL, '', '', '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, 100, 0, '2025-05-18 09:29:13', '2025-05-18 11:46:14', NULL, NULL, NULL),
(102, 'Aydın', 'Epikman', 'wisufejig', 'aepikmana22db@gmail.com', '$2y$10$wBWdRulX.x9HxouMmMsy2ugrlRuoEzRLkAOAA9Q/B5h6bxHd6BXQS', 2, 1, '186855', '2011-10-19', 'Erkek', '34412744154', 'erkek.jpg', 'Excepteur reiciendis', 'Minus aute ea est id', 'Esse commodi dolor', 'Kırklareli', 1, NULL, NULL, 5, NULL, 103, NULL, 15, '2025-05-18 09:43:12', '2025-05-18 11:26:26', '2025-05-18 12:30:30', '2025-08-18 09:30:30', NULL),
(103, 'Aladdin', 'Forbes', 'wisufejig-veli', NULL, '$2y$10$4LRV1x.WaeImhA6CKKXSHOqL9GD4hBa567WkQMcHItiXEw5so.NWG', 5, 1, '', NULL, '', NULL, '', '', '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, 102, 0, '2025-05-18 09:43:13', '2025-05-18 10:30:30', NULL, NULL, NULL),
(104, 'Juliet', 'Jenkins', 'wavopyz', 'aepikmanadb@gmail.com', '$2y$10$IOYvakfDCYUjpIyyefYjYuJ/ngKmv7DbcVSXiaIauWfX56lGyRV6q', 10002, 1, '125292', '1973-04-20', 'Erkek', '34312744154', 'erkek.jpg', 'Ut dolore ea odio si', 'Nostrum cupidatat in', 'Dolor ad qui dolorib', 'Sivas', 1, NULL, NULL, 11, NULL, 105, NULL, 21, '2025-05-18 11:40:07', '2025-05-18 11:40:07', '2025-05-18 13:40:03', '2026-05-18 10:40:03', NULL),
(105, 'Phelan', 'Dominguez', 'wavopyz-veli', NULL, '$2y$10$fd2NugNiwJpyp2m6.cb8HussdEDeqQnomSLfzDVjtxBUtbkcdP4Qa', 5, 0, '', NULL, '', NULL, '', '', '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, 104, 0, '2025-05-18 11:40:07', '2025-05-18 11:40:07', NULL, NULL, NULL),
(106, 'Ankara Yeni', 'Admin', 'ankara-yeni-admin', 'admin@okulu.com', '$2y$10$EFLYi8CrdMBeFC8GQCfK6ej8JVvAAW4D9MkEqgom8kEZDFDX8jr62', 8, 1, '02151231513', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, 70, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-05-19 10:32:28', '2025-05-19 10:32:28', NULL, NULL, NULL),
(107, 'Ankara Yeni', 'Koordinatör', 'ankara-yeni-koordinator', 'koordinator@okulu.com', '$2y$10$7N4ktF5Dm/7jMCs44DTU/.iGwvLByMGs.Nqui.ILGgIzm/p456XJu', 3, 1, '05151651515', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, 70, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-05-19 10:32:28', '2025-05-19 10:32:28', NULL, NULL, NULL),
(110, 'fatih', 'avcı', 'avciman', 'avciman@gmail.com', '$2y$10$cV3ZluGcJ5qB/w8Y8rvWL.GdTkIi7DrH7eP2KlF/aCGqmGwFDmnhq', 2, 1, '148855', '1992-04-06', 'Kız', '21574521831', '', 'Pariatur Sed tempor', 'Illum itaque vel do', 'Nam dolor nihil est', 'Çanakkale', 1, NULL, NULL, 5, NULL, 101, NULL, 14, '2025-05-18 09:29:13', '2025-05-18 11:46:14', '2025-05-18 13:46:14', '2025-11-18 10:46:14', NULL),
(111, 'fatih', 'avcı', 'favci4', '25486977738@test.com', '$2y$10$de.rt7ALcPvhbQTfT62KBucqhS1/mL/uvvbJmRmizzdMX79DqMAJC', 10002, 0, '05531105200', '2025-05-17', 'Erkek', '25869777382', 'erkek.jpg', 'dada', 'Çankaya', '06550', 'Çankırı', 1, NULL, NULL, 11, NULL, 112, NULL, 21, '2025-05-21 12:54:54', '2025-05-21 12:58:10', NULL, NULL, NULL),
(112, 'test', 'tests', 'favci4-veli', NULL, '$2y$10$MXVHgsK6PxPbcqzTBKwEQetgbrN27.wp6Iw63F6vF82FvI67NFOES', 5, 0, '', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 111, 0, '2025-05-21 12:54:54', '2025-05-21 12:54:54', NULL, NULL, NULL),
(113, 'teste', 'test', 'Kullanıcı Adı', '66fatighavciq@test.com', '$2y$10$KyPDB/AhYM9rxA/KB6dOUePAYFZE4vV6a1GCc/MlkEk3GOhG6dWoK', 2, 1, '05531105201', '2025-05-02', 'Erkek', '25486977738', 'erkek.jpg', 'dsad', 'dasda', '0655', 'Bursa', 1, NULL, NULL, 5, NULL, 114, NULL, 13, '2025-05-21 12:58:30', '2025-05-21 13:01:42', '2025-05-21 15:01:42', '2026-05-21 12:01:42', NULL),
(114, 'test', 'test', 'Kullanıcı Adı-veli', NULL, '$2y$10$mAopJGDA3pJlGVAnJQt9h.0WSFm6XD7oe/5U83ppW4nnUZZFBFfcO', 5, 1, '', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 113, 0, '2025-05-21 12:58:30', '2025-05-21 13:01:42', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `user_grades_lnp`
--

CREATE TABLE `user_grades_lnp` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `class_id` int(11) NOT NULL,
  `lesson_id` int(11) NOT NULL,
  `unit_id` int(11) DEFAULT NULL,
  `topic_id` int(11) DEFAULT NULL,
  `subtopic_id` int(11) DEFAULT NULL,
  `test_id` int(11) NOT NULL,
  `test_rate` decimal(4,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `weekly_duty_lnp`
--

CREATE TABLE `weekly_duty_lnp` (
  `id` int(11) NOT NULL,
  `name` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci NOT NULL,
  `short_desc` varchar(512) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci NOT NULL,
  `class_id` int(11) NOT NULL,
  `lesson_id` int(11) NOT NULL,
  `unit_id` int(11) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `slug` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `image` varchar(512) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `weekly_duty_lnp`
--

INSERT INTO `weekly_duty_lnp` (`id`, `name`, `short_desc`, `class_id`, `lesson_id`, `unit_id`, `start_date`, `end_date`, `slug`, `image`, `created_at`, `updated_at`) VALUES
(1, 'Deneme Deneme', 'd', 3, 1, 1, '2025-03-31', '2025-04-06', 'deneme-deneme', 'konuDefault.jpg', '2025-03-30 22:41:40', '2025-03-30 22:41:40'),
(2, 'Deneme 2', 'DEneme', 3, 2, 9, '2025-03-03', '2025-03-09', 'deneme-2', 'konuDefault.jpg', '2025-04-02 08:44:19', '2025-04-02 08:44:19');

--
-- Dökümü yapılmış tablolar için indeksler
--

--
-- Tablo için indeksler `additional_packages_lnp`
--
ALTER TABLE `additional_packages_lnp`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `additional_package_payments_lnp`
--
ALTER TABLE `additional_package_payments_lnp`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `additional_pack_detail_lnp`
--
ALTER TABLE `additional_pack_detail_lnp`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `announcementsstatus_lnp`
--
ALTER TABLE `announcementsstatus_lnp`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `announcements_lnp`
--
ALTER TABLE `announcements_lnp`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `announcement_targets_lnp`
--
ALTER TABLE `announcement_targets_lnp`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_target` (`announcement_id`,`target_type`,`target_value`),
  ADD KEY `idx_announcement_target` (`announcement_id`,`target_type`,`target_value`);

--
-- Tablo için indeksler `announcement_views_lnp`
--
ALTER TABLE `announcement_views_lnp`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_view` (`announcement_id`,`user_id`),
  ADD KEY `idx_announcement_views` (`announcement_id`,`user_id`),
  ADD KEY `idx_user_views` (`user_id`,`viewed_at`);

--
-- Tablo için indeksler `audio_book_lnp`
--
ALTER TABLE `audio_book_lnp`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `category_titles_lnp`
--
ALTER TABLE `category_titles_lnp`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `classes_lnp`
--
ALTER TABLE `classes_lnp`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `coupon_lnp`
--
ALTER TABLE `coupon_lnp`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `games_lnp`
--
ALTER TABLE `games_lnp`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `homeworks_lnp`
--
ALTER TABLE `homeworks_lnp`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `important_weeks_lnp`
--
ALTER TABLE `important_weeks_lnp`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `lessons_lnp`
--
ALTER TABLE `lessons_lnp`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `logininfo_lnp`
--
ALTER TABLE `logininfo_lnp`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `loginlogs_lnp`
--
ALTER TABLE `loginlogs_lnp`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `mainschool_content_file_lnp`
--
ALTER TABLE `mainschool_content_file_lnp`
  ADD PRIMARY KEY (`id`),
  ADD KEY `main_id` (`main_id`);

--
-- Tablo için indeksler `mainschool_wordwall_lnp`
--
ALTER TABLE `mainschool_wordwall_lnp`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `main_school_classes_lnp`
--
ALTER TABLE `main_school_classes_lnp`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `main_school_content_lnp`
--
ALTER TABLE `main_school_content_lnp`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `main_school_primary_images`
--
ALTER TABLE `main_school_primary_images`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `menusparent_lnp`
--
ALTER TABLE `menusparent_lnp`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `menus_lnp`
--
ALTER TABLE `menus_lnp`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `money_transfer_discount_lnp`
--
ALTER TABLE `money_transfer_discount_lnp`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `money_transfer_list_lnp`
--
ALTER TABLE `money_transfer_list_lnp`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `notifications_lnp`
--
ALTER TABLE `notifications_lnp`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `idx_active_dates` (`is_active`,`start_date`,`expire_date`),
  ADD KEY `idx_created_by` (`created_by`),
  ADD KEY `idx_slug` (`slug`);

--
-- Tablo için indeksler `notification_targets_lnp`
--
ALTER TABLE `notification_targets_lnp`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_target` (`notification_id`,`target_type`,`target_value`),
  ADD KEY `idx_notification_targets` (`notification_id`);

--
-- Tablo için indeksler `notification_views_lnp`
--
ALTER TABLE `notification_views_lnp`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_read` (`notification_id`,`user_id`),
  ADD KEY `idx_notification_reads` (`notification_id`),
  ADD KEY `idx_user_reads` (`user_id`);

--
-- Tablo için indeksler `packages_lnp`
--
ALTER TABLE `packages_lnp`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `package_payments_lnp`
--
ALTER TABLE `package_payments_lnp`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `password_reset_lnp`
--
ALTER TABLE `password_reset_lnp`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `token` (`token`),
  ADD KEY `user_id` (`user_id`);

--
-- Tablo için indeksler `payment_instalment_lnp`
--
ALTER TABLE `payment_instalment_lnp`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `payment_status_lnp`
--
ALTER TABLE `payment_status_lnp`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `payment_types_lnp`
--
ALTER TABLE `payment_types_lnp`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `que_answ_lnp`
--
ALTER TABLE `que_answ_lnp`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `schools_lnp`
--
ALTER TABLE `schools_lnp`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Tablo için indeksler `settings_lnp`
--
ALTER TABLE `settings_lnp`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `sms_settings_lnp`
--
ALTER TABLE `sms_settings_lnp`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `solvedtest_lnp`
--
ALTER TABLE `solvedtest_lnp`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `solved_s_questions_lnp`
--
ALTER TABLE `solved_s_questions_lnp`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `subtopics_lnp`
--
ALTER TABLE `subtopics_lnp`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `supportassignto_lnp`
--
ALTER TABLE `supportassignto_lnp`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `support_center_device_info_lnp`
--
ALTER TABLE `support_center_device_info_lnp`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `support_center_lnp`
--
ALTER TABLE `support_center_lnp`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `support_center_subjects_lnp`
--
ALTER TABLE `support_center_subjects_lnp`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `support_importance_lnp`
--
ALTER TABLE `support_importance_lnp`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `support_importance_titles_lnp`
--
ALTER TABLE `support_importance_titles_lnp`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `suspicious_attempts_lnp`
--
ALTER TABLE `suspicious_attempts_lnp`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `s_questions_lnp`
--
ALTER TABLE `s_questions_lnp`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `techmenuparent_lnp`
--
ALTER TABLE `techmenuparent_lnp`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `techmenus_lnp`
--
ALTER TABLE `techmenus_lnp`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `tests_lnp`
--
ALTER TABLE `tests_lnp`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `test_class_option_count`
--
ALTER TABLE `test_class_option_count`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `test_custom_permission_lnp`
--
ALTER TABLE `test_custom_permission_lnp`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `test_questions_lnp`
--
ALTER TABLE `test_questions_lnp`
  ADD PRIMARY KEY (`id`),
  ADD KEY `test_id` (`test_id`);

--
-- Tablo için indeksler `test_question_files_lnp`
--
ALTER TABLE `test_question_files_lnp`
  ADD PRIMARY KEY (`id`),
  ADD KEY `test_question_id` (`question_id`);

--
-- Tablo için indeksler `test_question_options_lnp`
--
ALTER TABLE `test_question_options_lnp`
  ADD PRIMARY KEY (`id`),
  ADD KEY `test_question_id` (`question_id`);

--
-- Tablo için indeksler `test_question_option_files_lnp`
--
ALTER TABLE `test_question_option_files_lnp`
  ADD PRIMARY KEY (`id`),
  ADD KEY `test_question_option_files_lnp_ibfk_1` (`option_id`);

--
-- Tablo için indeksler `test_question_videos_lnp`
--
ALTER TABLE `test_question_videos_lnp`
  ADD PRIMARY KEY (`id`),
  ADD KEY `question_id` (`question_id`);

--
-- Tablo için indeksler `test_success_rate_lnp`
--
ALTER TABLE `test_success_rate_lnp`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `test_user_answers_lnp`
--
ALTER TABLE `test_user_answers_lnp`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `timespend_lnp`
--
ALTER TABLE `timespend_lnp`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `topics_lnp`
--
ALTER TABLE `topics_lnp`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `units_lnp`
--
ALTER TABLE `units_lnp`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Tablo için indeksler `userroles_lnp`
--
ALTER TABLE `userroles_lnp`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `users_lnp`
--
ALTER TABLE `users_lnp`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `identity_id` (`identity_id`);

--
-- Tablo için indeksler `user_grades_lnp`
--
ALTER TABLE `user_grades_lnp`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `weekly_duty_lnp`
--
ALTER TABLE `weekly_duty_lnp`
  ADD PRIMARY KEY (`id`);

--
-- Dökümü yapılmış tablolar için AUTO_INCREMENT değeri
--

--
-- Tablo için AUTO_INCREMENT değeri `additional_packages_lnp`
--
ALTER TABLE `additional_packages_lnp`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Tablo için AUTO_INCREMENT değeri `additional_package_payments_lnp`
--
ALTER TABLE `additional_package_payments_lnp`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Tablo için AUTO_INCREMENT değeri `additional_pack_detail_lnp`
--
ALTER TABLE `additional_pack_detail_lnp`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Tablo için AUTO_INCREMENT değeri `announcementsstatus_lnp`
--
ALTER TABLE `announcementsstatus_lnp`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Tablo için AUTO_INCREMENT değeri `announcements_lnp`
--
ALTER TABLE `announcements_lnp`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- Tablo için AUTO_INCREMENT değeri `announcement_targets_lnp`
--
ALTER TABLE `announcement_targets_lnp`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- Tablo için AUTO_INCREMENT değeri `announcement_views_lnp`
--
ALTER TABLE `announcement_views_lnp`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- Tablo için AUTO_INCREMENT değeri `audio_book_lnp`
--
ALTER TABLE `audio_book_lnp`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- Tablo için AUTO_INCREMENT değeri `category_titles_lnp`
--
ALTER TABLE `category_titles_lnp`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- Tablo için AUTO_INCREMENT değeri `classes_lnp`
--
ALTER TABLE `classes_lnp`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- Tablo için AUTO_INCREMENT değeri `coupon_lnp`
--
ALTER TABLE `coupon_lnp`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- Tablo için AUTO_INCREMENT değeri `games_lnp`
--
ALTER TABLE `games_lnp`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- Tablo için AUTO_INCREMENT değeri `homeworks_lnp`
--
ALTER TABLE `homeworks_lnp`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Tablo için AUTO_INCREMENT değeri `important_weeks_lnp`
--
ALTER TABLE `important_weeks_lnp`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- Tablo için AUTO_INCREMENT değeri `lessons_lnp`
--
ALTER TABLE `lessons_lnp`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Tablo için AUTO_INCREMENT değeri `logininfo_lnp`
--
ALTER TABLE `logininfo_lnp`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- Tablo için AUTO_INCREMENT değeri `loginlogs_lnp`
--
ALTER TABLE `loginlogs_lnp`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- Tablo için AUTO_INCREMENT değeri `mainschool_content_file_lnp`
--
ALTER TABLE `mainschool_content_file_lnp`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=177;

--
-- Tablo için AUTO_INCREMENT değeri `mainschool_wordwall_lnp`
--
ALTER TABLE `mainschool_wordwall_lnp`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- Tablo için AUTO_INCREMENT değeri `main_school_classes_lnp`
--
ALTER TABLE `main_school_classes_lnp`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- Tablo için AUTO_INCREMENT değeri `main_school_content_lnp`
--
ALTER TABLE `main_school_content_lnp`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=128;

--
-- Tablo için AUTO_INCREMENT değeri `main_school_primary_images`
--
ALTER TABLE `main_school_primary_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- Tablo için AUTO_INCREMENT değeri `menusparent_lnp`
--
ALTER TABLE `menusparent_lnp`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- Tablo için AUTO_INCREMENT değeri `menus_lnp`
--
ALTER TABLE `menus_lnp`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;

--
-- Tablo için AUTO_INCREMENT değeri `money_transfer_discount_lnp`
--
ALTER TABLE `money_transfer_discount_lnp`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Tablo için AUTO_INCREMENT değeri `money_transfer_list_lnp`
--
ALTER TABLE `money_transfer_list_lnp`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Tablo için AUTO_INCREMENT değeri `notifications_lnp`
--
ALTER TABLE `notifications_lnp`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Tablo için AUTO_INCREMENT değeri `notification_targets_lnp`
--
ALTER TABLE `notification_targets_lnp`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Tablo için AUTO_INCREMENT değeri `notification_views_lnp`
--
ALTER TABLE `notification_views_lnp`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- Tablo için AUTO_INCREMENT değeri `packages_lnp`
--
ALTER TABLE `packages_lnp`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- Tablo için AUTO_INCREMENT değeri `package_payments_lnp`
--
ALTER TABLE `package_payments_lnp`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- Tablo için AUTO_INCREMENT değeri `password_reset_lnp`
--
ALTER TABLE `password_reset_lnp`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Tablo için AUTO_INCREMENT değeri `payment_instalment_lnp`
--
ALTER TABLE `payment_instalment_lnp`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Tablo için AUTO_INCREMENT değeri `payment_status_lnp`
--
ALTER TABLE `payment_status_lnp`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Tablo için AUTO_INCREMENT değeri `payment_types_lnp`
--
ALTER TABLE `payment_types_lnp`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Tablo için AUTO_INCREMENT değeri `que_answ_lnp`
--
ALTER TABLE `que_answ_lnp`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Tablo için AUTO_INCREMENT değeri `schools_lnp`
--
ALTER TABLE `schools_lnp`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=72;

--
-- Tablo için AUTO_INCREMENT değeri `settings_lnp`
--
ALTER TABLE `settings_lnp`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Tablo için AUTO_INCREMENT değeri `sms_settings_lnp`
--
ALTER TABLE `sms_settings_lnp`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Tablo için AUTO_INCREMENT değeri `solvedtest_lnp`
--
ALTER TABLE `solvedtest_lnp`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- Tablo için AUTO_INCREMENT değeri `solved_s_questions_lnp`
--
ALTER TABLE `solved_s_questions_lnp`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Tablo için AUTO_INCREMENT değeri `subtopics_lnp`
--
ALTER TABLE `subtopics_lnp`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Tablo için AUTO_INCREMENT değeri `supportassignto_lnp`
--
ALTER TABLE `supportassignto_lnp`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Tablo için AUTO_INCREMENT değeri `support_center_device_info_lnp`
--
ALTER TABLE `support_center_device_info_lnp`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Tablo için AUTO_INCREMENT değeri `support_center_lnp`
--
ALTER TABLE `support_center_lnp`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Tablo için AUTO_INCREMENT değeri `support_center_subjects_lnp`
--
ALTER TABLE `support_center_subjects_lnp`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Tablo için AUTO_INCREMENT değeri `support_importance_lnp`
--
ALTER TABLE `support_importance_lnp`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Tablo için AUTO_INCREMENT değeri `support_importance_titles_lnp`
--
ALTER TABLE `support_importance_titles_lnp`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Tablo için AUTO_INCREMENT değeri `suspicious_attempts_lnp`
--
ALTER TABLE `suspicious_attempts_lnp`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Tablo için AUTO_INCREMENT değeri `s_questions_lnp`
--
ALTER TABLE `s_questions_lnp`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Tablo için AUTO_INCREMENT değeri `techmenuparent_lnp`
--
ALTER TABLE `techmenuparent_lnp`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Tablo için AUTO_INCREMENT değeri `techmenus_lnp`
--
ALTER TABLE `techmenus_lnp`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Tablo için AUTO_INCREMENT değeri `tests_lnp`
--
ALTER TABLE `tests_lnp`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- Tablo için AUTO_INCREMENT değeri `test_class_option_count`
--
ALTER TABLE `test_class_option_count`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Tablo için AUTO_INCREMENT değeri `test_custom_permission_lnp`
--
ALTER TABLE `test_custom_permission_lnp`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Tablo için AUTO_INCREMENT değeri `test_questions_lnp`
--
ALTER TABLE `test_questions_lnp`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- Tablo için AUTO_INCREMENT değeri `test_question_files_lnp`
--
ALTER TABLE `test_question_files_lnp`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- Tablo için AUTO_INCREMENT değeri `test_question_options_lnp`
--
ALTER TABLE `test_question_options_lnp`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- Tablo için AUTO_INCREMENT değeri `test_question_option_files_lnp`
--
ALTER TABLE `test_question_option_files_lnp`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Tablo için AUTO_INCREMENT değeri `test_question_videos_lnp`
--
ALTER TABLE `test_question_videos_lnp`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- Tablo için AUTO_INCREMENT değeri `test_success_rate_lnp`
--
ALTER TABLE `test_success_rate_lnp`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Tablo için AUTO_INCREMENT değeri `test_user_answers_lnp`
--
ALTER TABLE `test_user_answers_lnp`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Tablo için AUTO_INCREMENT değeri `timespend_lnp`
--
ALTER TABLE `timespend_lnp`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4616;

--
-- Tablo için AUTO_INCREMENT değeri `topics_lnp`
--
ALTER TABLE `topics_lnp`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Tablo için AUTO_INCREMENT değeri `units_lnp`
--
ALTER TABLE `units_lnp`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=178;

--
-- Tablo için AUTO_INCREMENT değeri `userroles_lnp`
--
ALTER TABLE `userroles_lnp`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10003;

--
-- Tablo için AUTO_INCREMENT değeri `users_lnp`
--
ALTER TABLE `users_lnp`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=115;

--
-- Tablo için AUTO_INCREMENT değeri `user_grades_lnp`
--
ALTER TABLE `user_grades_lnp`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Tablo için AUTO_INCREMENT değeri `weekly_duty_lnp`
--
ALTER TABLE `weekly_duty_lnp`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Dökümü yapılmış tablolar için kısıtlamalar
--

--
-- Tablo kısıtlamaları `announcement_targets_lnp`
--
ALTER TABLE `announcement_targets_lnp`
  ADD CONSTRAINT `announcement_targets_lnp_ibfk_1` FOREIGN KEY (`announcement_id`) REFERENCES `announcements_lnp` (`id`) ON DELETE CASCADE;

--
-- Tablo kısıtlamaları `announcement_views_lnp`
--
ALTER TABLE `announcement_views_lnp`
  ADD CONSTRAINT `announcement_views_lnp_ibfk_1` FOREIGN KEY (`announcement_id`) REFERENCES `announcements_lnp` (`id`) ON DELETE CASCADE;

--
-- Tablo kısıtlamaları `mainschool_content_file_lnp`
--
ALTER TABLE `mainschool_content_file_lnp`
  ADD CONSTRAINT `mainschool_content_file_lnp_ibfk_1` FOREIGN KEY (`main_id`) REFERENCES `main_school_content_lnp` (`id`) ON DELETE CASCADE;

--
-- Tablo kısıtlamaları `notifications_lnp`
--
ALTER TABLE `notifications_lnp`
  ADD CONSTRAINT `notifications_lnp_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users_lnp` (`id`) ON DELETE CASCADE;

--
-- Tablo kısıtlamaları `notification_targets_lnp`
--
ALTER TABLE `notification_targets_lnp`
  ADD CONSTRAINT `notification_targets_lnp_ibfk_1` FOREIGN KEY (`notification_id`) REFERENCES `notifications_lnp` (`id`) ON DELETE CASCADE;

--
-- Tablo kısıtlamaları `notification_views_lnp`
--
ALTER TABLE `notification_views_lnp`
  ADD CONSTRAINT `notification_views_lnp_ibfk_1` FOREIGN KEY (`notification_id`) REFERENCES `notifications_lnp` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `notification_views_lnp_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users_lnp` (`id`) ON DELETE CASCADE;

--
-- Tablo kısıtlamaları `password_reset_lnp`
--
ALTER TABLE `password_reset_lnp`
  ADD CONSTRAINT `password_reset_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users_lnp` (`id`) ON DELETE CASCADE;

--
-- Tablo kısıtlamaları `test_questions_lnp`
--
ALTER TABLE `test_questions_lnp`
  ADD CONSTRAINT `test_questions_lnp_ibfk_1` FOREIGN KEY (`test_id`) REFERENCES `tests_lnp` (`id`) ON DELETE CASCADE;

--
-- Tablo kısıtlamaları `test_question_files_lnp`
--
ALTER TABLE `test_question_files_lnp`
  ADD CONSTRAINT `test_question_files_lnp_ibfk_1` FOREIGN KEY (`question_id`) REFERENCES `test_questions_lnp` (`id`) ON DELETE CASCADE;

--
-- Tablo kısıtlamaları `test_question_options_lnp`
--
ALTER TABLE `test_question_options_lnp`
  ADD CONSTRAINT `test_question_options_lnp_ibfk_1` FOREIGN KEY (`question_id`) REFERENCES `test_questions_lnp` (`id`) ON DELETE CASCADE;

--
-- Tablo kısıtlamaları `test_question_option_files_lnp`
--
ALTER TABLE `test_question_option_files_lnp`
  ADD CONSTRAINT `test_question_option_files_lnp_ibfk_1` FOREIGN KEY (`option_id`) REFERENCES `test_question_options_lnp` (`id`) ON DELETE CASCADE;

--
-- Tablo kısıtlamaları `test_question_videos_lnp`
--
ALTER TABLE `test_question_videos_lnp`
  ADD CONSTRAINT `test_question_videos_lnp_ibfk_1` FOREIGN KEY (`question_id`) REFERENCES `test_questions_lnp` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
