-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 23, 2026 at 11:55 AM
-- Server version: 8.0.42-0ubuntu0.20.04.1
-- PHP Version: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ee`
--

-- --------------------------------------------------------

--
-- Table structure for table `alerts`
--

CREATE TABLE `alerts` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `city_id` int NOT NULL,
  `alert_type` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `message` text COLLATE utf8mb4_unicode_ci,
  `is_read` tinyint(1) DEFAULT '0',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `api_logs`
--

CREATE TABLE `api_logs` (
  `id` int NOT NULL,
  `endpoint` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `response_code` int DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `api_logs`
--

INSERT INTO `api_logs` (`id`, `endpoint`, `response_code`, `created_at`) VALUES
(1, '/api/weather/budapest', 200, '2026-01-05 19:10:19'),
(2, 'weather/current', 200, '2026-01-14 09:53:26'),
(3, 'weather/forecast', 200, '2026-01-14 09:53:26'),
(4, 'city/search', 200, '2026-01-14 09:53:26'),
(5, 'weather/current', 401, '2026-01-14 09:53:26'),
(6, 'weather/forecast', 200, '2026-01-14 09:53:26'),
(7, 'city/search', 404, '2026-01-14 09:53:26'),
(8, 'weather/current', 200, '2026-01-14 09:53:26'),
(9, 'weather/forecast', 500, '2026-01-14 09:53:26'),
(10, 'user/login', 200, '2026-01-14 09:53:26'),
(11, 'user/register', 201, '2026-01-14 09:53:26'),
(12, 'weather/current', 200, '2026-01-14 09:53:26'),
(13, 'weather/forecast', 200, '2026-01-14 09:53:26'),
(14, 'city/search', 200, '2026-01-14 09:53:26'),
(15, 'weather/current', 200, '2026-01-14 09:53:26'),
(16, 'weather/forecast', 200, '2026-01-14 09:53:26');

-- --------------------------------------------------------

--
-- Table structure for table `cities`
--

CREATE TABLE `cities` (
  `id` int NOT NULL,
  `city_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `country` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lat` float NOT NULL,
  `lon` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cities`
--

INSERT INTO `cities` (`id`, `city_name`, `country`, `lat`, `lon`) VALUES
(12, 'Kula', 'TR', 38.5473, 28.6498),
(13, 'Párizs', 'FR', 48.8534, 2.3488),
(14, 'Krakkó', 'PL', 50.0833, 19.9167),
(15, 'Belgrád', 'RS', 44.804, 20.4651),
(16, 'Vrbas', 'RS', 45.5714, 19.6408),
(17, 'Kairó', 'EG', 30.0626, 31.2497),
(18, 'Krakkó', 'PL', 50.0833, 19.9167),
(19, 'Párizs', 'FR', 48.8534, 2.3488),
(20, 'Krakkó', 'PL', 50.0833, 19.9167),
(21, 'Szabadka', 'RS', 46.1, 19.6667),
(22, 'Budapest', 'HU', 47.498, 19.0399),
(23, 'Krakkó', 'PL', 50.0833, 19.9167),
(24, 'Krakkó', 'PL', 50.0833, 19.9167),
(25, 'Krakkó', 'PL', 50.0833, 19.9167),
(26, 'Krakkó', 'PL', 50.0833, 19.9167),
(27, 'Krakkó', 'PL', 50.0833, 19.9167),
(28, 'Krakkó', 'PL', 50.0833, 19.9167),
(29, 'Krakkó', 'PL', 50.0833, 19.9167),
(30, 'Krakkó', 'PL', 50.0833, 19.9167),
(31, 'Krakkó', 'PL', 50.0833, 19.9167),
(32, 'Krakkó', 'PL', 50.0833, 19.9167),
(33, 'Krakkó', 'PL', 50.0833, 19.9167),
(34, 'Krakkó', 'PL', 50.0833, 19.9167),
(35, 'Párizs', 'FR', 48.8534, 2.3488),
(36, 'Párizs', 'FR', 48.8534, 2.3488),
(37, 'Párizs', 'FR', 48.8534, 2.3488),
(38, 'Párizs', 'FR', 48.8534, 2.3488),
(39, 'Párizs', 'FR', 48.8534, 2.3488),
(40, 'Párizs', 'FR', 48.8534, 2.3488),
(41, 'Párizs', 'FR', 48.8534, 2.3488),
(42, 'Párizs', 'FR', 48.8534, 2.3488),
(43, 'Párizs', 'FR', 48.8534, 2.3488),
(44, 'Párizs', 'FR', 48.8534, 2.3488),
(45, 'Párizs', 'FR', 48.8534, 2.3488),
(46, 'Párizs', 'FR', 48.8534, 2.3488),
(47, 'Párizs', 'FR', 48.8534, 2.3488),
(48, 'Párizs', 'FR', 48.8534, 2.3488),
(49, 'Párizs', 'FR', 48.8534, 2.3488),
(50, 'Párizs', 'FR', 48.8534, 2.3488),
(51, 'Krakkó', 'PL', 50.0833, 19.9167),
(52, 'Krakkó', 'PL', 50.0833, 19.9167),
(53, 'Párizs', 'FR', 48.8534, 2.3488),
(54, 'Párizs', 'FR', 48.8534, 2.3488),
(55, 'Párizs', 'FR', 48.8534, 2.3488),
(56, 'Párizs', 'FR', 48.8534, 2.3488),
(57, 'Párizs', 'FR', 48.8534, 2.3488),
(58, 'Krakkó', 'PL', 50.0833, 19.9167),
(59, 'Krakkó', 'PL', 50.0833, 19.9167),
(60, 'Krakkó', 'PL', 50.0833, 19.9167),
(61, 'Belgrád', 'RS', 44.804, 20.4651),
(62, 'Belgrád', 'RS', 44.804, 20.4651),
(63, 'Belgrád', 'RS', 44.804, 20.4651),
(64, 'Párizs', 'FR', 48.8534, 2.3488),
(65, 'Paris', 'FR', 48.8534, 2.3488),
(66, 'Belgrade', 'RS', 44.804, 20.4651),
(67, 'Krakow', 'PL', 50.0833, 19.9167),
(68, 'Kanjiža', 'RS', 46.0667, 20.05),
(69, 'Kanjiža', 'RS', 46.0667, 20.05),
(70, 'Kanjiža', 'RS', 46.0667, 20.05),
(71, 'Kaniža', 'HR', 45.1089, 17.8866),
(72, 'San Francisco ', 'EC', -2.8998, -78.7498),
(73, 'Zenta', 'RS', 45.9275, 20.0772),
(74, 'Baku', 'AZ', 40.3777, 49.892),
(75, 'Kanjiža', 'RS', 46.0667, 20.05),
(76, 'Magyarkanizsa', 'RS', 46.0667, 20.05),
(77, 'Mol', 'RS', 45.7642, 20.1311),
(78, 'Mol', 'RS', 45.7642, 20.1311),
(79, 'London', 'GB', 51.5085, -0.1257),
(80, 'Washington', 'US', 47.5001, -120.502);

-- --------------------------------------------------------

--
-- Table structure for table `favorite_cities`
--

CREATE TABLE `favorite_cities` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `city_id` int NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `favorite_cities`
--

INSERT INTO `favorite_cities` (`id`, `user_id`, `city_id`, `created_at`) VALUES
(40, 28, 67, '2026-02-22 18:20:22'),
(41, 28, 12, '2026-02-22 18:20:50');

-- --------------------------------------------------------

--
-- Table structure for table `outfit_recommendations`
--

CREATE TABLE `outfit_recommendations` (
  `id` int NOT NULL,
  `temp_min` float DEFAULT NULL,
  `temp_max` float DEFAULT NULL,
  `recommendation` text COLLATE utf8mb4_unicode_ci,
  `recommendation_en` text COLLATE utf8mb4_unicode_ci,
  `recommendation_sr` text COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `outfit_recommendations`
--

INSERT INTO `outfit_recommendations` (`id`, `temp_min`, `temp_max`, `recommendation`, `recommendation_en`, `recommendation_sr`) VALUES
(1, 5, 15, 'Vékony kabát, sál és zárt cipő ajánlott.', 'Thin jacket, scarf and closed shoes recommended.', 'Preporučuje se tanka jakna, šal i zatvorena obuća.'),
(2, -50, 0, 'Vastag télikabát, sál, sapka, kesztyű és bélelt bakancs.', 'Thick winter coat, scarf, hat, gloves and lined boots...', 'Debeli zimski kaput, šal, kapa, rukavice i postavljene čizme...'),
(3, 0.1, 10, 'Átmeneti kabát, pulóver, zárt cipő.', 'Transitional jacket, sweater, closed shoes.', 'Prolećna jakna, džemper, zatvorena obuća.'),
(4, 10.1, 18, 'Könnyű dzseki vagy kardigán, hosszú nadrág.', 'Light jacket or cardigan, long pants.', 'Laka jakna ili kardigan, duge pantalone.'),
(5, 18.1, 25, 'Póló, vékony nadrág vagy szoknya.', 'T-shirt, light pants or skirt.', 'Majica, lagane pantalone ili suknja.'),
(6, 25.1, 50, 'Rövidnadrág, trikó, szandál és sok víz!', 'Shorts, tank top, sandals and lots of water!', 'Šorts, majica na bretele, sandale i puno vode!');

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `id` int NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expires_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `password_resets`
--

INSERT INTO `password_resets` (`id`, `email`, `token`, `expires_at`) VALUES
(1, 'teszt.elemer@example.com', 'xyz-abc-123-token', '2026-01-06 18:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `activation_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT '0',
  `is_admin` tinyint(1) DEFAULT '0',
  `reset_token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `notify_email` tinyint(1) DEFAULT '0',
  `notify_push` tinyint(1) DEFAULT '0',
  `notify_alerts` tinyint(1) DEFAULT '0',
  `notify_daily` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `password`, `activation_code`, `is_active`, `is_admin`, `reset_token`, `created_at`, `notify_email`, `notify_push`, `notify_alerts`, `notify_daily`) VALUES
(1, 'teszt.elemer@example.com', 'hashed_password_123', 'ACT-9876', 1, 0, NULL, '2026-01-05 19:10:19', 0, 0, 0, 0),
(2, 'teszt.elek2@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, 1, 0, NULL, '2026-01-14 09:53:26', 0, 0, 0, 0),
(3, 'kovacs.janos2@freemail.hu', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, 1, 0, NULL, '2026-01-14 09:53:26', 0, 0, 0, 0),
(4, 'admin2@weatherapp.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, 1, 0, NULL, '2026-01-14 09:53:26', 0, 0, 0, 0),
(5, 'nagy.anna2@outlook.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, 0, 0, NULL, '2026-01-14 09:53:26', 0, 0, 0, 0),
(6, 'toth.bela2@citromail.hu', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, 1, 0, NULL, '2026-01-14 09:53:26', 0, 0, 0, 0),
(7, 'varga.kata2@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, 1, 0, NULL, '2026-01-14 09:53:26', 0, 0, 0, 0),
(8, 'maliboban@gmail.com', '$2y$10$ZN1A5xu7RMaQleGoEyk9../q6EivPlTGOMEq6F8UJT3/Ser49/GJi', '765cba4c5639d5f80db321c01eaebf13', 0, 0, NULL, '2026-01-15 20:33:05', 0, 0, 0, 0),
(28, 'dominik1588@gmail.com', '$2y$10$2quQWD6bAgtL/qA5jS6vNOA0P5JI16CkOYfiiEDzWH4WW9ZM925NS', NULL, 1, 1, NULL, '2026-02-22 18:19:46', 0, 0, 1, 0),
(29, 'newuser@example.com', '$2y$10$ROJD/NF4ypy4IikoAXEKNuP9tAozuKHMk.pgitVaFnaw4IznRvI7u', '29e8504db890c7b70d6f1624ce1a00d1', 0, 0, NULL, '2026-03-08 15:39:43', 0, 0, 0, 0),
(37, 'test@example.com', '$2y$10$P9pr4bvZ8n776wG9gClGy.DzlzdjN9NB9mcZAcofpv8x5mbELs8O2', '899577077923a27a4ed4b2ccbcf369f4', 0, 0, NULL, '2026-03-13 08:10:23', 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `visitor_tracking`
--

CREATE TABLE `visitor_tracking` (
  `id` int NOT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `device_type` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `browser` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `os` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_mobile` tinyint(1) DEFAULT '0',
  `country` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `timezone` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `region` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `latitude` decimal(10,7) DEFAULT NULL,
  `longitude` decimal(10,7) DEFAULT NULL,
  `visited_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `weather_archive`
--

CREATE TABLE `weather_archive` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `city_id` int NOT NULL,
  `temperature` float DEFAULT NULL,
  `humidity` int DEFAULT NULL,
  `wind_speed` float DEFAULT NULL,
  `date_time` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `weather_data`
--

CREATE TABLE `weather_data` (
  `id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  `city_id` int NOT NULL,
  `temperature` float DEFAULT NULL,
  `feels_like` float DEFAULT NULL,
  `humidity` int DEFAULT NULL,
  `wind_speed` float DEFAULT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `icon` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pressure` int DEFAULT NULL,
  `visibility` int DEFAULT NULL,
  `clouds` int DEFAULT NULL,
  `sunrise` datetime DEFAULT NULL,
  `sunset` datetime DEFAULT NULL,
  `dt` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `weather_data`
--

INSERT INTO `weather_data` (`id`, `user_id`, `city_id`, `temperature`, `feels_like`, `humidity`, `wind_speed`, `description`, `icon`, `pressure`, `visibility`, `clouds`, `sunrise`, `sunset`, `dt`) VALUES
(258, NULL, 67, 6.75, 4.23, 82, 3.6, 'broken clouds', '04d', NULL, NULL, NULL, NULL, NULL, '2026-02-22 15:22:49'),
(259, NULL, 67, 6.63, 4.4, 40, 3.09, 'overcast clouds', '04n', NULL, NULL, NULL, NULL, NULL, '2026-02-22 18:11:17'),
(260, NULL, 67, 6.63, 4.4, 40, 3.09, 'overcast clouds', '04n', NULL, NULL, NULL, NULL, NULL, '2026-02-22 18:20:20'),
(261, NULL, 67, 6.63, 4.4, 40, 3.09, 'overcast clouds', '04n', NULL, NULL, NULL, NULL, NULL, '2026-02-22 18:20:22'),
(262, NULL, 12, 4.26, 2.4, 88, 2.1, 'overcast clouds', '04n', NULL, NULL, NULL, NULL, NULL, '2026-02-22 18:20:48'),
(263, NULL, 12, 4.26, 2.4, 88, 2.1, 'overcast clouds', '04n', NULL, NULL, NULL, NULL, NULL, '2026-02-22 18:20:50'),
(264, NULL, 12, 4.26, 2.4, 88, 2.1, 'overcast clouds', '04n', NULL, NULL, NULL, NULL, NULL, '2026-02-22 18:22:51'),
(265, 28, 67, 6.63, 4.4, 40, 3.09, 'borús égbolt', '04n', NULL, NULL, NULL, NULL, NULL, '2026-02-22 18:24:38'),
(266, 28, 67, 6.63, 4.4, 40, 3.09, 'borús égbolt', '04n', NULL, NULL, NULL, NULL, NULL, '2026-02-22 18:25:01'),
(267, 28, 67, 6.63, 4.4, 40, 3.09, 'overcast clouds', '04n', NULL, NULL, NULL, NULL, NULL, '2026-02-22 18:25:24'),
(268, 28, 67, 6.09, 3.75, 41, 3.09, 'ниски облаци', '04n', NULL, NULL, NULL, NULL, NULL, '2026-02-22 18:25:44'),
(269, 28, 67, 6.63, 4.4, 40, 3.09, 'borús égbolt', '04n', NULL, NULL, NULL, NULL, NULL, '2026-02-22 18:26:03'),
(270, 28, 12, 3.91, 2.03, 89, 2.06, 'borús égbolt', '04n', NULL, NULL, NULL, NULL, NULL, '2026-02-22 19:05:08'),
(271, 28, 12, 3.91, 2.03, 89, 2.06, 'borús égbolt', '04n', NULL, NULL, NULL, NULL, NULL, '2026-02-22 19:09:22'),
(276, 28, 67, 6.09, 3.43, 41, 3.6, 'erős felhőzet', '04n', NULL, NULL, NULL, NULL, NULL, '2026-02-22 19:11:13'),
(279, 28, 67, 6.09, 3.43, 41, 3.6, 'erős felhőzet', '04n', NULL, NULL, NULL, NULL, NULL, '2026-02-22 19:13:55'),
(280, 28, 65, 11.33, 10.95, 93, 5.66, 'borús égbolt', '04n', NULL, NULL, NULL, NULL, NULL, '2026-02-22 19:13:58'),
(290, 28, 65, 11.33, 10.95, 93, 5.66, 'borús égbolt', '04n', NULL, NULL, NULL, NULL, NULL, '2026-02-22 19:18:33'),
(291, 28, 65, 11.33, 10.95, 93, 5.66, 'overcast clouds', '04n', NULL, NULL, NULL, NULL, NULL, '2026-02-22 19:18:57'),
(292, 28, 12, 3.81, 1.62, 91, 2.36, 'overcast clouds', '04n', NULL, NULL, NULL, NULL, NULL, '2026-02-22 19:59:33'),
(293, 28, 67, 5.54, 3.09, 46, 3.09, 'broken clouds', '04n', NULL, NULL, NULL, NULL, NULL, '2026-02-22 19:59:54'),
(294, 28, 66, 4.26, 1.55, 81, 3.09, 'clear sky', '01n', NULL, NULL, NULL, NULL, NULL, '2026-02-23 00:20:58'),
(295, 28, 67, 5.56, 3.96, 53, 2.06, 'broken clouds', '04n', NULL, NULL, NULL, NULL, NULL, '2026-02-23 00:21:12'),
(296, NULL, 65, 10.97, 10.52, 92, 4.63, 'broken clouds', '04d', NULL, NULL, NULL, NULL, NULL, '2026-02-23 09:40:25'),
(297, 28, 67, 6.67, 3.59, 55, 4.63, 'broken clouds', '04d', NULL, NULL, NULL, NULL, NULL, '2026-02-23 09:49:54'),
(298, 28, 67, 8.85, 5.9, 49, 5.66, 'erős felhőzet', '04d', NULL, NULL, NULL, NULL, NULL, '2026-02-23 10:25:43'),
(299, 28, 67, 8.85, 5.9, 49, 5.66, 'broken clouds', '04d', NULL, NULL, NULL, NULL, NULL, '2026-02-23 10:25:52'),
(300, 28, 67, 8.85, 5.9, 49, 5.66, 'broken clouds', '04d', NULL, NULL, NULL, NULL, NULL, '2026-02-23 10:26:52'),
(301, 28, 67, 8.85, 5.72, 49, 6.17, 'broken clouds', '04d', NULL, NULL, NULL, NULL, NULL, '2026-02-23 10:41:31'),
(302, 28, 67, 9.96, 6.98, 45, 6.69, 'broken clouds', '04d', NULL, NULL, NULL, NULL, NULL, '2026-02-23 12:08:11'),
(303, 28, 65, 14.6, 13.92, 69, 7.2, 'broken clouds', '04d', NULL, NULL, NULL, NULL, NULL, '2026-02-23 12:57:36'),
(304, 28, 65, 14.6, 13.92, 69, 7.2, 'broken clouds', '04d', NULL, NULL, NULL, NULL, NULL, '2026-02-23 12:57:39'),
(305, 28, 65, 14.6, 13.92, 69, 7.2, 'broken clouds', '04d', NULL, NULL, NULL, NULL, NULL, '2026-02-23 12:57:41'),
(306, NULL, 67, 6.65, 5.23, 50, 2.06, 'broken clouds', '04n', NULL, NULL, NULL, NULL, NULL, '2026-02-23 18:24:09'),
(307, NULL, 67, 6.65, 5.23, 51, 2.06, 'erős felhőzet', '04n', NULL, NULL, NULL, NULL, NULL, '2026-02-23 18:25:32'),
(308, NULL, 72, 20.76, 21.22, 89, 1.97, 'közepes eső', '10d', NULL, NULL, NULL, NULL, NULL, '2026-02-23 18:26:49'),
(309, NULL, 73, 10.25, 9.47, 82, 2.79, 'borús égbolt', '04n', NULL, NULL, NULL, NULL, NULL, '2026-02-23 18:26:52'),
(310, NULL, 72, 20.76, 21.22, 89, 1.97, 'közepes eső', '10d', NULL, NULL, NULL, NULL, NULL, '2026-02-23 18:27:35'),
(311, NULL, 12, 3.64, 1.9, 83, 1.9, 'tiszta égbolt', '01n', NULL, NULL, NULL, NULL, NULL, '2026-02-23 18:28:02'),
(312, 28, 65, 15.11, 14.32, 63, 5.14, 'borús égbolt', '04n', NULL, NULL, NULL, NULL, NULL, '2026-02-23 18:28:40'),
(313, NULL, 65, 13.95, 13.36, 75, 4.63, 'clear sky', '01n', NULL, NULL, NULL, NULL, NULL, '2026-02-23 21:12:56'),
(314, NULL, 12, 2.38, 2.38, 84, 1.2, 'clear sky', '01n', NULL, NULL, NULL, NULL, NULL, '2026-02-23 21:13:11'),
(315, NULL, 74, 6.03, 2.56, 93, 5.14, 'light intensity drizzle', '09n', NULL, NULL, NULL, NULL, NULL, '2026-02-23 21:13:20'),
(316, NULL, 21, 10.68, 9.47, 64, 7.22, 'scattered clouds', '03d', NULL, NULL, NULL, NULL, NULL, '2026-02-24 16:08:59'),
(317, NULL, 12, 8.12, 8.12, 59, 1.13, 'broken clouds', '04n', NULL, NULL, NULL, NULL, NULL, '2026-02-24 17:18:49'),
(318, NULL, 75, 8.97, 7.17, 81, 3.17, 'scattered clouds', '03n', NULL, NULL, NULL, NULL, NULL, '2026-02-25 19:16:13'),
(319, 28, 76, 8.97, 7.17, 81, 3.17, 'szórványos felhőzet', '03n', NULL, NULL, NULL, NULL, NULL, '2026-02-25 19:17:03'),
(320, 28, 65, 14.24, 13.73, 77, 2.06, 'tiszta égbolt', '01n', NULL, NULL, NULL, NULL, NULL, '2026-02-25 22:51:45'),
(321, 28, 67, 13.42, 12.46, 63, 2.06, 'clear sky', '01d', NULL, NULL, NULL, NULL, NULL, '2026-02-27 14:01:54'),
(322, NULL, 22, 12.29, 10.83, 48, 3.09, 'tiszta égbolt', '01d', NULL, NULL, NULL, NULL, NULL, '2026-02-27 14:03:08'),
(323, NULL, 12, 4.67, 2.03, 43, 3.11, 'szórványos felhőzet', '03d', NULL, NULL, NULL, NULL, NULL, '2026-02-27 14:03:08'),
(324, NULL, 15, 13.22, 11.77, 45, 2.57, 'tiszta égbolt', '01d', NULL, NULL, NULL, NULL, NULL, '2026-02-27 14:03:08'),
(325, NULL, 22, 12.29, 10.83, 48, 3.09, 'tiszta égbolt', '01d', NULL, NULL, NULL, NULL, NULL, '2026-02-27 14:03:08'),
(326, NULL, 12, 4.67, 2.03, 43, 3.11, 'szórványos felhőzet', '03d', NULL, NULL, NULL, NULL, NULL, '2026-02-27 14:03:08'),
(327, NULL, 15, 13.22, 11.77, 45, 2.57, 'tiszta égbolt', '01d', NULL, NULL, NULL, NULL, NULL, '2026-02-27 14:03:08'),
(328, NULL, 22, 12.29, 10.83, 48, 3.09, 'tiszta égbolt', '01d', NULL, NULL, NULL, NULL, NULL, '2026-02-27 14:03:15'),
(329, NULL, 12, 4.67, 2.03, 43, 3.11, 'szórványos felhőzet', '03d', NULL, NULL, NULL, NULL, NULL, '2026-02-27 14:03:16'),
(330, NULL, 22, 12.29, 10.83, 48, 3.09, 'tiszta égbolt', '01d', NULL, NULL, NULL, NULL, NULL, '2026-02-27 14:03:16'),
(331, NULL, 12, 4.67, 2.03, 43, 3.11, 'szórványos felhőzet', '03d', NULL, NULL, NULL, NULL, NULL, '2026-02-27 14:03:16'),
(332, NULL, 77, 13.72, 12.53, 53, 2, 'overcast clouds', '04d', NULL, NULL, NULL, NULL, NULL, '2026-03-04 16:55:28'),
(333, NULL, 78, 13.72, 12.53, 53, 2, 'overcast clouds', '04d', NULL, NULL, NULL, NULL, NULL, '2026-03-04 16:55:47'),
(334, NULL, 22, 12.11, 11.05, 64, 3.6, 'tiszta égbolt', '01d', NULL, NULL, NULL, NULL, NULL, '2026-03-05 09:37:25'),
(335, NULL, 12, 9.28, 9.28, 35, 1.05, 'kevés felhő', '02d', NULL, NULL, NULL, NULL, NULL, '2026-03-05 09:37:25'),
(336, NULL, 22, 12.11, 11.05, 64, 3.6, 'tiszta égbolt', '01d', NULL, NULL, NULL, NULL, NULL, '2026-03-05 09:37:25'),
(337, NULL, 12, 9.28, 9.28, 35, 1.05, 'kevés felhő', '02d', NULL, NULL, NULL, NULL, NULL, '2026-03-05 09:37:26'),
(338, NULL, 22, 12.11, 11.05, 64, 3.6, 'tiszta égbolt', '01d', NULL, NULL, NULL, NULL, NULL, '2026-03-05 09:39:27'),
(339, NULL, 22, 12.11, 11.05, 64, 3.6, 'tiszta égbolt', '01d', NULL, NULL, NULL, NULL, NULL, '2026-03-05 09:39:27'),
(340, NULL, 22, 12.11, 11.05, 64, 3.6, 'tiszta égbolt', '01d', NULL, NULL, NULL, NULL, NULL, '2026-03-05 09:39:32'),
(341, NULL, 14, 5.04, 3.35, 35, 2.06, 'tiszta égbolt', '01d', NULL, NULL, NULL, NULL, NULL, '2026-03-05 09:39:32'),
(342, NULL, 22, 12.11, 11.05, 64, 3.6, 'tiszta égbolt', '01d', NULL, NULL, NULL, NULL, NULL, '2026-03-05 09:39:32'),
(343, NULL, 14, 5.04, 3.35, 35, 2.06, 'tiszta égbolt', '01d', NULL, NULL, NULL, NULL, NULL, '2026-03-05 09:39:32'),
(344, NULL, 22, 12.11, 11.05, 64, 3.6, 'tiszta égbolt', '01d', NULL, NULL, NULL, NULL, NULL, '2026-03-05 09:46:12'),
(345, NULL, 76, 11.75, 10.7, 66, 1, 'erős felhőzet', '04d', NULL, NULL, NULL, NULL, NULL, '2026-03-05 09:46:12'),
(346, NULL, 22, 12.11, 11.05, 64, 3.6, 'tiszta égbolt', '01d', NULL, NULL, NULL, NULL, NULL, '2026-03-05 09:46:12'),
(347, NULL, 76, 11.75, 10.7, 66, 1, 'erős felhőzet', '04d', NULL, NULL, NULL, NULL, NULL, '2026-03-05 09:46:12'),
(348, 28, 67, 5.04, 3.35, 34, 2.06, 'tiszta égbolt', '01d', NULL, NULL, NULL, NULL, NULL, '2026-03-05 09:49:50'),
(349, NULL, 14, 10.64, 9.22, 56, 2.06, 'tiszta égbolt', '01d', NULL, NULL, NULL, NULL, NULL, '2026-03-07 10:21:16'),
(350, NULL, 22, 12.49, 11.1, 50, 1.34, 'tiszta égbolt', '01d', NULL, NULL, NULL, NULL, NULL, '2026-03-07 10:21:25'),
(351, 28, 79, 7.47, 5.11, 93, 3.6, 'borús égbolt', '04d', NULL, NULL, NULL, NULL, NULL, '2026-03-07 10:26:29'),
(352, 28, 13, 11.95, 11.5, 88, 3.09, 'gyenge köd', '50d', NULL, NULL, NULL, NULL, NULL, '2026-03-07 10:26:48'),
(353, 28, 79, 7.47, 5.11, 93, 3.6, 'borús égbolt', '04d', NULL, NULL, NULL, NULL, NULL, '2026-03-07 10:27:32'),
(354, NULL, 22, 17.72, 16.3, 29, 3.6, 'tiszta égbolt', '01d', NULL, NULL, NULL, NULL, NULL, '2026-03-08 15:40:49'),
(355, 28, 12, 10.81, 8.76, 31, 2.58, 'erős felhőzet', '04d', NULL, NULL, NULL, NULL, NULL, '2026-03-08 16:07:32'),
(365, 28, 12, 6.66, 5.64, 43, 1.67, 'kevés felhő', '02d', NULL, NULL, NULL, NULL, NULL, '2026-03-11 07:59:43'),
(366, 28, 12, 6.66, 5.64, 43, 1.67, 'kevés felhő', '02d', NULL, NULL, NULL, NULL, NULL, '2026-03-11 08:04:29'),
(367, 28, 12, 6.66, 5.64, 43, 1.67, 'kevés felhő', '02d', NULL, NULL, NULL, NULL, NULL, '2026-03-11 08:06:48'),
(368, 28, 21, 7.77, 5.17, 63, 4.18, 'szórványos felhőzet', '03d', NULL, NULL, NULL, NULL, NULL, '2026-03-11 08:11:32'),
(369, 28, 12, 13.35, 11.39, 25, 2.44, 'szórványos felhőzet', '03d', NULL, NULL, NULL, NULL, NULL, '2026-03-11 14:31:12'),
(370, 28, 12, 12.4, 10.51, 31, 2.52, 'szórványos felhőzet', '03d', NULL, NULL, NULL, NULL, NULL, '2026-03-11 15:57:30'),
(371, 28, 80, 0.19, 0.19, 88, 1.3, 'borús égbolt', '04d', NULL, NULL, NULL, NULL, NULL, '2026-03-11 15:58:26'),
(372, 28, 12, 12.4, 10.51, 31, 2.52, 'szórványos felhőzet', '03d', NULL, NULL, NULL, NULL, NULL, '2026-03-11 15:58:59'),
(373, 28, 16, 19.43, 18.63, 46, 2.48, 'kevés felhő', '02d', NULL, NULL, NULL, NULL, NULL, '2026-03-11 16:00:12'),
(374, 28, 12, 12.4, 10.51, 31, 2.52, 'szórványos felhőzet', '03d', NULL, NULL, NULL, NULL, NULL, '2026-03-11 16:00:58'),
(375, NULL, 12, 12.4, 10.51, 31, 2.52, 'szórványos felhőzet', '03d', NULL, NULL, NULL, NULL, NULL, '2026-03-11 16:01:55'),
(376, 28, 12, 12.4, 10.51, 31, 2.52, 'szórványos felhőzet', '03d', NULL, NULL, NULL, NULL, NULL, '2026-03-11 16:02:12'),
(377, NULL, 12, 12.4, 10.51, 31, 2.52, 'szórványos felhőzet', '03d', NULL, NULL, NULL, NULL, NULL, '2026-03-11 16:11:00'),
(378, NULL, 22, 17.07, 15.98, 44, 3.09, 'tiszta égbolt', '01d', NULL, NULL, NULL, NULL, NULL, '2026-03-11 16:16:36'),
(379, NULL, 22, 17.07, 15.98, 44, 3.09, 'tiszta égbolt', '01d', NULL, NULL, NULL, NULL, NULL, '2026-03-11 16:16:49'),
(380, 28, 12, 13.22, 11.59, 38, 4.51, 'tiszta égbolt', '01d', NULL, NULL, NULL, NULL, NULL, '2026-03-12 14:06:42'),
(396, 28, 22, 10.09, 9.32, 83, 1.34, 'tiszta égbolt', '01d', 1021, 10000, 0, '2026-03-13 06:01:33', '2026-03-13 17:45:23', '2026-03-13 08:29:14'),
(403, 28, 12, 11.67, 10.15, 48, 2.41, 'erős felhőzet', '04d', 1010, 10000, 72, '2026-03-18 05:12:32', '2026-03-18 17:14:35', '2026-03-18 09:22:10'),
(404, NULL, 22, 12.53, 11.09, 48, 8.23, 'tiszta égbolt', '01d', 1023, 10000, 0, '2026-03-18 05:51:27', '2026-03-18 17:52:32', '2026-03-18 09:22:21'),
(405, NULL, 12, 11.67, 10.15, 48, 2.41, 'erős felhőzet', '04d', 1010, 10000, 72, '2026-03-18 05:12:32', '2026-03-18 17:14:35', '2026-03-18 09:22:21'),
(406, NULL, 22, 12.53, 11.09, 48, 8.23, 'tiszta égbolt', '01d', 1023, 10000, 0, '2026-03-18 05:51:27', '2026-03-18 17:52:32', '2026-03-18 09:22:21'),
(407, NULL, 12, 11.67, 10.15, 48, 2.41, 'erős felhőzet', '04d', 1010, 10000, 72, '2026-03-18 05:12:32', '2026-03-18 17:14:35', '2026-03-18 09:22:21'),
(408, NULL, 22, 12.86, 11.43, 47, 8.23, 'tiszta égbolt', '01d', 1023, 10000, 0, '2026-03-18 05:51:27', '2026-03-18 17:52:32', '2026-03-18 09:26:49'),
(409, NULL, 12, 11.67, 10.15, 48, 2.41, 'erős felhőzet', '04d', 1010, 10000, 72, '2026-03-18 05:12:32', '2026-03-18 17:14:35', '2026-03-18 09:26:49'),
(410, NULL, 22, 12.86, 11.43, 47, 8.23, 'tiszta égbolt', '01d', 1023, 10000, 0, '2026-03-18 05:51:27', '2026-03-18 17:52:32', '2026-03-18 09:26:51'),
(411, NULL, 12, 11.67, 10.15, 48, 2.41, 'erős felhőzet', '04d', 1010, 10000, 72, '2026-03-18 05:12:32', '2026-03-18 17:14:35', '2026-03-18 09:26:51'),
(412, NULL, 22, 12.86, 11.43, 47, 8.23, 'tiszta égbolt', '01d', 1023, 10000, 0, '2026-03-18 05:51:27', '2026-03-18 17:52:32', '2026-03-18 09:27:09'),
(413, NULL, 12, 11.67, 10.15, 48, 2.41, 'erős felhőzet', '04d', 1010, 10000, 72, '2026-03-18 05:12:32', '2026-03-18 17:14:35', '2026-03-18 09:27:09'),
(414, NULL, 22, 12.86, 11.43, 47, 8.23, 'tiszta égbolt', '01d', 1023, 10000, 0, '2026-03-18 05:51:27', '2026-03-18 17:52:32', '2026-03-18 09:27:09'),
(415, NULL, 12, 11.67, 10.15, 48, 2.41, 'erős felhőzet', '04d', 1010, 10000, 72, '2026-03-18 05:12:32', '2026-03-18 17:14:35', '2026-03-18 09:27:09'),
(416, NULL, 22, 12.86, 11.43, 47, 8.23, 'tiszta égbolt', '01d', 1023, 10000, 0, '2026-03-18 05:51:27', '2026-03-18 17:52:32', '2026-03-18 09:27:34'),
(417, NULL, 12, 11.67, 10.15, 48, 2.41, 'erős felhőzet', '04d', 1010, 10000, 72, '2026-03-18 05:12:32', '2026-03-18 17:14:35', '2026-03-18 09:27:34'),
(418, NULL, 22, 12.86, 11.43, 47, 8.23, 'tiszta égbolt', '01d', 1023, 10000, 0, '2026-03-18 05:51:27', '2026-03-18 17:52:32', '2026-03-18 09:28:00'),
(419, NULL, 12, 11.67, 10.15, 48, 2.41, 'erős felhőzet', '04d', 1010, 10000, 72, '2026-03-18 05:12:32', '2026-03-18 17:14:35', '2026-03-18 09:28:00'),
(420, NULL, 13, 10.53, 9.39, 67, 5.14, 'tiszta égbolt', '01d', 1020, 10000, 0, '2026-03-18 06:58:12', '2026-03-18 18:59:17', '2026-03-18 09:28:00'),
(421, 28, 22, 12.86, 11.43, 47, 8.23, 'tiszta égbolt', '01d', 1023, 10000, 0, '2026-03-18 05:51:27', '2026-03-18 17:52:32', '2026-03-18 09:28:52'),
(422, 28, 12, 13.13, 11.67, 45, 1.92, 'erős felhőzet', '04d', 1010, 10000, 66, '2026-03-18 05:12:32', '2026-03-18 17:14:35', '2026-03-18 09:28:52'),
(423, 28, 13, 10.6, 9.47, 67, 5.14, 'tiszta égbolt', '01d', 1020, 10000, 0, '2026-03-18 06:58:12', '2026-03-18 18:59:17', '2026-03-18 09:28:52'),
(424, 28, 22, 12.86, 11.43, 47, 8.23, 'tiszta égbolt', '01d', 1023, 10000, 0, '2026-03-18 05:51:27', '2026-03-18 17:52:32', '2026-03-18 09:28:54'),
(425, 28, 12, 13.13, 11.67, 45, 1.92, 'erős felhőzet', '04d', 1010, 10000, 66, '2026-03-18 05:12:32', '2026-03-18 17:14:35', '2026-03-18 09:28:54'),
(426, 28, 13, 10.6, 9.47, 67, 5.14, 'tiszta égbolt', '01d', 1020, 10000, 0, '2026-03-18 06:58:12', '2026-03-18 18:59:17', '2026-03-18 09:28:54'),
(427, 28, 22, 12.86, 11.43, 47, 8.23, 'tiszta égbolt', '01d', 1023, 10000, 0, '2026-03-18 05:51:27', '2026-03-18 17:52:32', '2026-03-18 09:36:05'),
(428, 28, 22, 12.86, 11.43, 47, 8.23, 'tiszta égbolt', '01d', 1023, 10000, 0, '2026-03-18 05:51:27', '2026-03-18 17:52:32', '2026-03-18 09:36:08'),
(429, 28, 12, 13.13, 11.67, 45, 1.92, 'erős felhőzet', '04d', 1010, 10000, 66, '2026-03-18 05:12:32', '2026-03-18 17:14:35', '2026-03-18 09:36:08'),
(430, 28, 22, 12.86, 11.43, 47, 8.23, 'tiszta égbolt', '01d', 1023, 10000, 0, '2026-03-18 05:51:27', '2026-03-18 17:52:32', '2026-03-18 09:36:16'),
(431, 28, 12, 13.13, 11.67, 45, 1.92, 'erős felhőzet', '04d', 1010, 10000, 66, '2026-03-18 05:12:32', '2026-03-18 17:14:35', '2026-03-18 09:36:17'),
(432, 28, 22, 12.86, 11.43, 47, 8.23, 'tiszta égbolt', '01d', 1023, 10000, 0, '2026-03-18 05:51:27', '2026-03-18 17:52:32', '2026-03-18 09:36:21'),
(433, 28, 12, 13.13, 11.67, 45, 1.92, 'erős felhőzet', '04d', 1010, 10000, 66, '2026-03-18 05:12:32', '2026-03-18 17:14:35', '2026-03-18 09:36:21'),
(434, 28, 13, 11.39, 10.13, 59, 6.69, 'tiszta égbolt', '01d', 1020, 10000, 0, '2026-03-18 06:58:12', '2026-03-18 18:59:17', '2026-03-18 09:36:21'),
(435, NULL, 12, 4.01, 1.78, 86, 2.44, 'overcast clouds', '04d', 1003, 3170, 100, '2026-03-20 05:09:24', '2026-03-20 17:16:29', '2026-03-20 16:40:22'),
(436, NULL, 12, 4.01, 1.78, 86, 2.44, 'overcast clouds', '04d', 1003, 3170, 100, '2026-03-20 05:09:24', '2026-03-20 17:16:29', '2026-03-20 16:40:34');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `alerts`
--
ALTER TABLE `alerts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `city_id` (`city_id`);

--
-- Indexes for table `api_logs`
--
ALTER TABLE `api_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cities`
--
ALTER TABLE `cities`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `favorite_cities`
--
ALTER TABLE `favorite_cities`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `city_id` (`city_id`);

--
-- Indexes for table `outfit_recommendations`
--
ALTER TABLE `outfit_recommendations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `visitor_tracking`
--
ALTER TABLE `visitor_tracking`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_ip` (`ip_address`),
  ADD KEY `idx_visited` (`visited_at`);

--
-- Indexes for table `weather_archive`
--
ALTER TABLE `weather_archive`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `city_id` (`city_id`);

--
-- Indexes for table `weather_data`
--
ALTER TABLE `weather_data`
  ADD PRIMARY KEY (`id`),
  ADD KEY `city_id` (`city_id`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `alerts`
--
ALTER TABLE `alerts`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `api_logs`
--
ALTER TABLE `api_logs`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `cities`
--
ALTER TABLE `cities`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=81;

--
-- AUTO_INCREMENT for table `favorite_cities`
--
ALTER TABLE `favorite_cities`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `outfit_recommendations`
--
ALTER TABLE `outfit_recommendations`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `visitor_tracking`
--
ALTER TABLE `visitor_tracking`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `weather_archive`
--
ALTER TABLE `weather_archive`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `weather_data`
--
ALTER TABLE `weather_data`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=437;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `alerts`
--
ALTER TABLE `alerts`
  ADD CONSTRAINT `alerts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `alerts_ibfk_2` FOREIGN KEY (`city_id`) REFERENCES `cities` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `favorite_cities`
--
ALTER TABLE `favorite_cities`
  ADD CONSTRAINT `favorite_cities_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `favorite_cities_ibfk_2` FOREIGN KEY (`city_id`) REFERENCES `cities` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `weather_archive`
--
ALTER TABLE `weather_archive`
  ADD CONSTRAINT `weather_archive_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `weather_archive_ibfk_2` FOREIGN KEY (`city_id`) REFERENCES `cities` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `weather_data`
--
ALTER TABLE `weather_data`
  ADD CONSTRAINT `fk_weather_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_weather_user_server` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `weather_data_ibfk_1` FOREIGN KEY (`city_id`) REFERENCES `cities` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
