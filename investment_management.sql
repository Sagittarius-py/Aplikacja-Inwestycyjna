-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Lip 26, 2024 at 09:03 AM
-- Wersja serwera: 10.4.32-MariaDB
-- Wersja PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `investment_management`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `aneksy`
--

CREATE TABLE `aneksy` (
  `aneks_id` int(11) NOT NULL,
  `umowa_id` int(11) DEFAULT NULL,
  `aneks` varchar(20) NOT NULL,
  `data_zawarcia` date NOT NULL,
  `tresc_aneksu` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `faktury`
--

CREATE TABLE `faktury` (
  `faktura_id` int(11) NOT NULL,
  `umowa_id` int(11) NOT NULL,
  `nr_faktury` varchar(10) NOT NULL,
  `data_faktury` date NOT NULL,
  `kwota_faktury` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `fundusze`
--

CREATE TABLE `fundusze` (
  `fundusze_id` int(11) NOT NULL,
  `umowa_id` int(11) DEFAULT NULL,
  `plan` double NOT NULL,
  `rok` int(4) NOT NULL,
  `kwota_brutto` double NOT NULL,
  `pozostaly_fundusz` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `gwarancje`
--

CREATE TABLE `gwarancje` (
  `gwarancja_id` int(11) NOT NULL,
  `umowa_id` int(11) NOT NULL,
  `ilosc_miesiecy` int(4) NOT NULL,
  `termin_uplywu` date NOT NULL,
  `pierwszy_przeglad` date NOT NULL,
  `przeglady` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `kontrahenci`
--

CREATE TABLE `kontrahenci` (
  `kontrahent_id` int(11) NOT NULL,
  `kontrahent` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `kontrahenci_umowy`
--

CREATE TABLE `kontrahenci_umowy` (
  `kontrahent_id` int(11) NOT NULL,
  `umowa_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `umowy`
--

CREATE TABLE `umowy` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `nazwa_zadania` text DEFAULT NULL,
  `klasyfikacja` varchar(10) DEFAULT NULL,
  `nr_umowy` varchar(20) DEFAULT NULL,
  `data_zawarcia` varchar(10) DEFAULT NULL,
  `data_zakon` varchar(10) DEFAULT NULL,
  `tresc` longtext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indeksy dla zrzutów tabel
--

--
-- Indeksy dla tabeli `aneksy`
--
ALTER TABLE `aneksy`
  ADD PRIMARY KEY (`aneks_id`),
  ADD KEY `aneksy_umowa_fk` (`umowa_id`);

--
-- Indeksy dla tabeli `faktury`
--
ALTER TABLE `faktury`
  ADD PRIMARY KEY (`faktura_id`),
  ADD KEY `faktury_umowa_fk` (`umowa_id`);

--
-- Indeksy dla tabeli `fundusze`
--
ALTER TABLE `fundusze`
  ADD PRIMARY KEY (`fundusze_id`),
  ADD KEY `fundusze_umowa_fk` (`umowa_id`);

--
-- Indeksy dla tabeli `gwarancje`
--
ALTER TABLE `gwarancje`
  ADD PRIMARY KEY (`gwarancja_id`),
  ADD KEY `gwarancje_umowa_fk` (`umowa_id`);

--
-- Indeksy dla tabeli `kontrahenci`
--
ALTER TABLE `kontrahenci`
  ADD PRIMARY KEY (`kontrahent_id`);

--
-- Indeksy dla tabeli `kontrahenci_umowy`
--
ALTER TABLE `kontrahenci_umowy`
  ADD KEY `kontrahenci_umowy_kontrahent_fk` (`kontrahent_id`),
  ADD KEY `kontrahenci_umowy_umowa_fk` (`umowa_id`);

--
-- Indeksy dla tabeli `umowy`
--
ALTER TABLE `umowy`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indeksy dla tabeli `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `aneksy`
--
ALTER TABLE `aneksy`
  MODIFY `aneks_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `faktury`
--
ALTER TABLE `faktury`
  MODIFY `faktura_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `fundusze`
--
ALTER TABLE `fundusze`
  MODIFY `fundusze_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `gwarancje`
--
ALTER TABLE `gwarancje`
  MODIFY `gwarancja_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `kontrahenci`
--
ALTER TABLE `kontrahenci`
  MODIFY `kontrahent_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `umowy`
--
ALTER TABLE `umowy`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `aneksy`
--
ALTER TABLE `aneksy`
  ADD CONSTRAINT `aneksy_umowa_fk` FOREIGN KEY (`umowa_id`) REFERENCES `umowy` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `faktury`
--
ALTER TABLE `faktury`
  ADD CONSTRAINT `faktury_umowa_fk` FOREIGN KEY (`umowa_id`) REFERENCES `umowy` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `fundusze`
--
ALTER TABLE `fundusze`
  ADD CONSTRAINT `fundusze_umowa_fk` FOREIGN KEY (`umowa_id`) REFERENCES `umowy` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `gwarancje`
--
ALTER TABLE `gwarancje`
  ADD CONSTRAINT `gwarancje_umowa_fk` FOREIGN KEY (`umowa_id`) REFERENCES `umowy` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `kontrahenci_umowy`
--
ALTER TABLE `kontrahenci_umowy`
  ADD CONSTRAINT `kontrahenci_umowy_kontrahent_fk` FOREIGN KEY (`kontrahent_id`) REFERENCES `kontrahenci` (`kontrahent_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `kontrahenci_umowy_umowa_fk` FOREIGN KEY (`umowa_id`) REFERENCES `umowy` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `umowy`
--
ALTER TABLE `umowy`
  ADD CONSTRAINT `umowy_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
