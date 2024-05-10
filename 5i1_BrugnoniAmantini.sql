-- phpMyAdmin SQL Dump
-- version 5.2.1deb1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Creato il: Mag 10, 2024 alle 07:16
-- Versione del server: 10.11.6-MariaDB-0+deb12u1
-- Versione PHP: 8.2.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `5i1_BrugnoniAmantini`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `modifica`
--

CREATE TABLE `modifica` (
  `id` int(11) NOT NULL,
  `data` date DEFAULT curdate(),
  `ora` time DEFAULT curtime(),
  `descrizione` varchar(255) DEFAULT NULL,
  `fk_utente` varchar(16) DEFAULT NULL,
  `fk_stato` int(11) DEFAULT NULL,
  `fk_task` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `stato`
--

CREATE TABLE `stato` (
  `stato` int(11) NOT NULL,
  `descrizione` varchar(16) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `task`
--

CREATE TABLE `task` (
  `id` int(11) NOT NULL,
  `titolo` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `utente`
--

CREATE TABLE `utente` (
  `username` varchar(16) NOT NULL,
  `nome` varchar(50) NOT NULL,
  `cognome` varchar(50) NOT NULL,
  `password` varchar(16) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `utente`
--

INSERT INTO `utente` (`username`, `nome`, `cognome`, `password`) VALUES
('Kemy', 'Alexandro', 'Brugnoni', 'a');

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `modifica`
--
ALTER TABLE `modifica`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_utente` (`fk_utente`),
  ADD KEY `fk_task` (`fk_task`),
  ADD KEY `fk_stato` (`fk_stato`);

--
-- Indici per le tabelle `stato`
--
ALTER TABLE `stato`
  ADD PRIMARY KEY (`stato`);

--
-- Indici per le tabelle `task`
--
ALTER TABLE `task`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `utente`
--
ALTER TABLE `utente`
  ADD PRIMARY KEY (`username`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `modifica`
--
ALTER TABLE `modifica`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `task`
--
ALTER TABLE `task`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Limiti per le tabelle scaricate
--

--
-- Limiti per la tabella `modifica`
--
ALTER TABLE `modifica`
  ADD CONSTRAINT `modifica_ibfk_1` FOREIGN KEY (`fk_utente`) REFERENCES `utente` (`username`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `modifica_ibfk_2` FOREIGN KEY (`fk_task`) REFERENCES `task` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `modifica_ibfk_3` FOREIGN KEY (`fk_stato`) REFERENCES `stato` (`stato`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
