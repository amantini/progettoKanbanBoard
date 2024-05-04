-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Creato il: Mag 04, 2024 alle 18:09
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
-- Database: `kanban`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `stati`
--

CREATE TABLE `stati` (
  `id` int(11) NOT NULL,
  `nome` varchar(500) DEFAULT NULL,
  `stati` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `stati`
--

INSERT INTO `stati` (`id`, `nome`, `stati`) VALUES
(1, 'Sistemare la scrivania', 1),
(2, 'Fare la spesa', 2),
(3, 'Studiare per lesame', 3),
(4, 'Allenarsi in palestra', 4),
(5, 'Preparare la cena', 1),
(6, 'Fare il bucato', 2),
(7, 'Mandare figlio a scuola', 3);

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `stati`
--
ALTER TABLE `stati`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `stati`
--
ALTER TABLE `stati`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
