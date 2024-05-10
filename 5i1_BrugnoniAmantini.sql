-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Creato il: Mag 10, 2024 alle 21:03
-- Versione del server: 10.4.32-MariaDB
-- Versione PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `5i1_brugnoniamantini`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `modifiche`
--

CREATE TABLE `modifiche` (
  `id` int(11) NOT NULL,
  `data` date DEFAULT curdate(),
  `ora` time DEFAULT curtime(),
  `descrizione` varchar(255) DEFAULT NULL,
  `fk_utente` varchar(16) DEFAULT NULL,
  `fk_stato` int(11) DEFAULT NULL,
  `fk_task` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `modifiche`
--

INSERT INTO `modifiche` (`id`, `data`, `ora`, `descrizione`, `fk_utente`, `fk_stato`, `fk_task`) VALUES
(20, '2024-05-10', '17:43:02', 'Andare al parco col cane e farlo camminare', 'Kemy', 1, 85),
(21, '2024-05-10', '17:53:03', 'Yoga con listruttore Marco', 'Kemy', 1, 86),
(22, '2024-05-10', '18:10:09', 'Materia TPSIT', 'Kemy', 1, 87),
(23, '2024-05-10', '18:11:15', 'Cercare di dimagrire', 'Kemy', 1, 88),
(24, '2024-05-10', '18:14:31', 'Facciamo festa tra di noi', 'amantini', 1, 89),
(25, '2024-05-10', '18:20:11', 'Andare al parco col cane e farlo camminare', 'Kemy', 2, 85),
(28, '2024-05-10', '18:57:09', 'Materia TPSIT', 'amantini', 1, 87);

-- --------------------------------------------------------

--
-- Struttura della tabella `stati`
--

CREATE TABLE `stati` (
  `stato` int(11) NOT NULL,
  `descrizione` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `stati`
--

INSERT INTO `stati` (`stato`, `descrizione`) VALUES
(1, 'Da Fare'),
(2, 'In Esecuzione'),
(3, 'Fatto'),
(4, 'Terminato');

-- --------------------------------------------------------

--
-- Struttura della tabella `task`
--

CREATE TABLE `task` (
  `id` int(11) NOT NULL,
  `titolo` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `task`
--

INSERT INTO `task` (`id`, `titolo`) VALUES
(85, 'Andare al parco'),
(86, 'Fare yoga'),
(87, 'Prendere appunti'),
(88, 'Andare dal nutrizion'),
(89, 'Andare al mare');

-- --------------------------------------------------------

--
-- Struttura della tabella `utenti`
--

CREATE TABLE `utenti` (
  `username` varchar(16) NOT NULL,
  `nome` varchar(50) NOT NULL,
  `cognome` varchar(50) NOT NULL,
  `password` varchar(16) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `utenti`
--

INSERT INTO `utenti` (`username`, `nome`, `cognome`, `password`) VALUES
('amantini', 'Alessandro', 'Amantini', 'ama'),
('Kemy', 'Alexandro', 'Brugnoni', 'a');

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `modifiche`
--
ALTER TABLE `modifiche`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_utente` (`fk_utente`),
  ADD KEY `fk_task` (`fk_task`),
  ADD KEY `fk_stato` (`fk_stato`);

--
-- Indici per le tabelle `stati`
--
ALTER TABLE `stati`
  ADD PRIMARY KEY (`stato`);

--
-- Indici per le tabelle `task`
--
ALTER TABLE `task`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `utenti`
--
ALTER TABLE `utenti`
  ADD PRIMARY KEY (`username`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `modifiche`
--
ALTER TABLE `modifiche`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT per la tabella `task`
--
ALTER TABLE `task`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=90;

--
-- Limiti per le tabelle scaricate
--

--
-- Limiti per la tabella `modifiche`
--
ALTER TABLE `modifiche`
  ADD CONSTRAINT `modifiche_ibfk_1` FOREIGN KEY (`fk_utente`) REFERENCES `utenti` (`username`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `modifiche_ibfk_2` FOREIGN KEY (`fk_task`) REFERENCES `task` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `modifiche_ibfk_3` FOREIGN KEY (`fk_stato`) REFERENCES `stati` (`stato`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
