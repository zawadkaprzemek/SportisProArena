-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Czas generowania: 30 Paź 2022, 23:24
-- Wersja serwera: 8.0.30-0ubuntu0.22.04.1
-- Wersja PHP: 7.4.32

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Baza danych: `sportisarena_app`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `training_dictionary`
--

CREATE TABLE `training_dictionary` (
  `id` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `info_text` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `requirements` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Zrzut danych tabeli `training_dictionary`
--

INSERT INTO `training_dictionary` (`id`, `name`, `type`, `created_at`, `updated_at`, `info_text`, `requirements`) VALUES
(1, 'Kategoria młodzieżowa', 'ageCategory', '2022-06-17 23:23:50', '2022-06-17 23:23:50', 'dla zawodników poniżej 13 roku życia, zastosowanie piłki rozmiar 4 oraz grafik młodzieżowe', NULL),
(2, 'Kategoria open', 'ageCategory', '2022-06-17 23:25:22', '2022-06-17 23:25:22', 'dla zawodników powyżej 13 roku życia, zastosowanie piłki rozmiar 5 oraz grafiki\r\nw rozmiarach pełnowymiarowych', NULL),
(3, 'Indywidualny', 'trainingType', '2022-06-17 23:26:17', '2022-06-17 23:26:17', NULL, NULL),
(4, 'Trening w parze', 'trainingType', '2022-06-17 23:26:53', '2022-06-17 23:26:53', NULL, NULL),
(5, 'U7', 'trainingSubGroups', '2022-06-19 23:28:31', '2022-06-19 23:28:31', NULL, 'youth'),
(6, 'U8', 'trainingSubGroups', '2022-06-19 23:28:31', '2022-06-19 23:28:31', NULL, 'youth'),
(7, 'U9', 'trainingSubGroups', '2022-06-19 23:28:31', '2022-06-19 23:28:31', NULL, 'youth'),
(8, 'U10', 'trainingSubGroups', '2022-06-19 23:28:31', '2022-06-19 23:28:31', NULL, 'youth'),
(9, 'U11', 'trainingSubGroups', '2022-06-19 23:28:31', '2022-06-19 23:28:31', NULL, 'youth'),
(10, 'U12', 'trainingSubGroups', '2022-06-19 23:28:31', '2022-06-19 23:28:31', NULL, 'youth'),
(11, 'U13', 'trainingSubGroups', '2022-06-19 23:28:31', '2022-06-19 23:28:31', NULL, 'youth'),
(12, 'U14', 'trainingSubGroups', '2022-06-19 23:28:31', '2022-06-19 23:28:31', NULL, 'open'),
(13, 'U15', 'trainingSubGroups', '2022-06-19 23:28:31', '2022-06-19 23:28:31', NULL, 'open'),
(14, 'U16', 'trainingSubGroups', '2022-06-19 23:28:31', '2022-06-19 23:28:31', NULL, 'open'),
(15, 'Junior', 'trainingSubGroups', '2022-06-19 23:28:31', '2022-06-19 23:28:31', NULL, 'open'),
(16, 'Senior', 'trainingSubGroups', '2022-06-19 23:28:31', '2022-06-19 23:28:31', NULL, 'open'),
(17, 'łatwy', 'trainingLevels', '2022-06-19 23:37:03', '2022-06-19 23:37:03', NULL, NULL),
(18, 'średniozaawansowany', 'trainingLevels', '2022-06-19 23:37:03', '2022-06-19 23:37:03', NULL, NULL),
(19, 'zaawansowany', 'trainingLevels', '2022-06-19 23:37:03', '2022-06-19 23:37:03', NULL, NULL),
(20, 'trudny', 'trainingLevels', '2022-06-19 23:37:03', '2022-06-19 23:37:03', NULL, NULL),
(21, 'pro', 'trainingLevels', '2022-06-19 23:37:03', '2022-06-19 23:37:03', NULL, NULL),
(22, 'strzelecki', 'trainingGroup', '2022-07-25 00:08:34', '2022-07-25 00:08:34', NULL, NULL),
(23, 'podania', 'trainingGroup', '2022-07-25 00:08:34', '2022-07-25 00:08:34', NULL, NULL),
(24, 'przyjęcie', 'trainingGroup', '2022-07-25 00:08:34', '2022-07-25 00:08:34', NULL, NULL),
(25, 'wprowadzenie', 'trainingGroup', '2022-07-25 00:08:34', '2022-07-25 00:08:34', NULL, NULL),
(26, 'percepcja', 'trainingGroup', '2022-07-25 00:08:34', '2022-07-25 00:08:34', NULL, NULL),
(27, 'rzuty karne', 'trainingGroup', '2022-07-25 00:08:34', '2022-07-25 00:08:34', NULL, NULL),
(28, 'bramkarski', 'trainingGroup', '2022-07-25 00:08:34', '2022-07-25 00:08:34', NULL, NULL),
(29, 'mieszany', 'trainingGroup', '2022-07-25 00:08:34', '2022-07-25 00:08:34', NULL, NULL);

--
-- Indeksy dla zrzutów tabel
--

--
-- Indeksy dla tabeli `training_dictionary`
--
ALTER TABLE `training_dictionary`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT dla zrzuconych tabel
--

--
-- AUTO_INCREMENT dla tabeli `training_dictionary`
--
ALTER TABLE `training_dictionary`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
