-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Creato il: Mag 11, 2024 alle 17:18
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
(71, '2024-05-11', '12:22:50', 'Col maestro di yoga', 'Kemy', 1, 90),
(72, '2024-05-11', '12:23:25', 'Col maestro di yoga', 'amantini', 2, 90),
(73, '2024-05-11', '12:25:18', 'Col maestro di yoga', 'Kemy', 3, 90),
(74, '2024-05-11', '12:31:07', 'Aggiustare la scrivania per lo zio pippo', 'Kemy', 1, 91),
(75, '2024-05-11', '12:31:17', 'Materia TPSIT', 'Kemy', 1, 92),
(76, '2024-05-11', '12:31:25', 'Cercare di dimagrire', 'Kemy', 1, 93),
(77, '2024-05-11', '12:31:36', 'Facciamo festa ', 'Kemy', 1, 94),
(78, '2024-05-11', '12:31:59', 'Tavolo della cucina rotto', 'Kemy', 1, 95),
(79, '2024-05-11', '12:32:48', 'Aggiustare la scrivania per lo zio pippo', 'Kemy', 2, 91),
(80, '2024-05-11', '12:32:55', 'Facciamo festa ', 'Kemy', 2, 94),
(81, '2024-05-11', '12:33:12', 'Aggiustare la scrivania per lo zio pippo', 'amantini', 3, 91),
(82, '2024-05-11', '12:33:13', 'Materia TPSIT', 'amantini', 2, 92),
(141, '2024-05-11', '16:35:55', 'Cercare di dimagrire un passo alla volta', 'amantini', 1, 93),
(142, '2024-05-11', '16:36:29', 'Cercare di dimagrire un passo alla volta.', 'Kemy', 1, 93),
(143, '2024-05-11', '16:43:40', '100 Pagine da studiare', 'amantini', 1, 96),
(219, '2024-05-11', '16:49:26', '100 Pagine da studiaree', 'amantini', 1, 96),
(220, '2024-05-11', '16:49:49', '100 Pagine da studiare', 'amantini', 1, 96),
(221, '2024-05-11', '17:13:57', 'Pagina da finire', 'amantini', 1, 97);

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
  `titolo` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `task`
--

INSERT INTO `task` (`id`, `titolo`) VALUES
(90, 'Fare yoga'),
(91, 'Aggiustare Scrivania'),
(92, 'Prendere appunti'),
(93, 'Andare dal nutrizionista'),
(94, 'Andare al mare'),
(95, 'Sistemare il tavolo'),
(96, 'Studiare GPOI'),
(97, 'Rielabolare css');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=222;

--
-- AUTO_INCREMENT per la tabella `task`
--
ALTER TABLE `task`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=98;

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
