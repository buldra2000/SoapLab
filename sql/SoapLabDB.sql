-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Creato il: Mar 25, 2026 alle 15:00
-- Versione del server: 10.4.28-MariaDB
-- Versione PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `SoapLabDB`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `acquisti`
--

CREATE TABLE `acquisti` (
  `idAcquisto` int(11) NOT NULL,
  `idUtente` int(11) NOT NULL,
  `idInserzione` int(11) NOT NULL,
  `idIndirizzo` int(11) NOT NULL,
  `dataAcquisto` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `acquisti`
--

INSERT INTO `acquisti` (`idAcquisto`, `idUtente`, `idInserzione`, `idIndirizzo`, `dataAcquisto`) VALUES
(1, 8, 3, 3, '2026-03-23 17:19:15'),
(2, 8, 3, 3, '2026-03-24 13:51:47'),
(14, 1, 9, 1, '2026-03-24 15:20:56'),
(15, 1, 9, 1, '2026-03-24 15:20:56'),
(16, 1, 9, 1, '2026-03-24 15:20:56'),
(17, 1, 9, 1, '2026-03-24 15:20:56'),
(18, 1, 9, 1, '2026-03-24 15:20:56'),
(19, 1, 9, 1, '2026-03-24 15:26:42'),
(20, 1, 9, 1, '2026-03-24 15:26:42'),
(21, 1, 9, 1, '2026-03-24 15:26:42'),
(22, 1, 9, 1, '2026-03-24 15:26:42'),
(23, 1, 9, 1, '2026-03-24 15:26:42'),
(24, 1, 9, 1, '2026-03-24 15:28:42'),
(25, 1, 9, 1, '2026-03-24 15:46:00'),
(26, 1, 9, 1, '2026-03-24 15:46:00'),
(27, 1, 9, 1, '2026-03-24 15:46:00'),
(28, 1, 9, 1, '2026-03-24 15:46:00'),
(29, 1, 9, 1, '2026-03-24 15:46:00'),
(30, 1, 9, 1, '2026-03-24 15:46:00'),
(31, 1, 9, 1, '2026-03-24 15:46:00'),
(32, 1, 9, 1, '2026-03-24 15:46:00'),
(33, 1, 9, 1, '2026-03-24 15:46:00'),
(34, 1, 9, 1, '2026-03-24 15:46:00'),
(35, 1, 9, 1, '2026-03-24 15:46:00'),
(36, 1, 9, 1, '2026-03-24 15:46:00'),
(37, 1, 9, 1, '2026-03-24 15:46:00'),
(38, 1, 9, 1, '2026-03-24 15:46:00'),
(39, 1, 9, 1, '2026-03-24 15:46:00'),
(40, 1, 9, 1, '2026-03-24 15:46:00'),
(41, 1, 9, 1, '2026-03-24 15:46:00'),
(42, 1, 9, 1, '2026-03-24 15:46:00'),
(43, 1, 9, 1, '2026-03-24 15:46:00'),
(44, 1, 9, 1, '2026-03-24 15:46:00'),
(45, 1, 9, 1, '2026-03-24 15:46:00'),
(46, 1, 9, 1, '2026-03-24 15:46:00'),
(47, 1, 9, 1, '2026-03-24 15:46:00'),
(48, 1, 9, 1, '2026-03-24 15:46:00'),
(49, 1, 9, 1, '2026-03-24 15:46:00'),
(50, 1, 9, 1, '2026-03-24 15:46:00'),
(51, 1, 9, 1, '2026-03-24 15:46:00'),
(52, 1, 9, 1, '2026-03-24 15:46:00'),
(53, 1, 9, 1, '2026-03-24 15:46:00'),
(54, 1, 9, 1, '2026-03-24 15:46:00'),
(56, 1, 9, 1, '2026-03-24 15:47:24'),
(57, 1, 9, 1, '2026-03-24 15:47:24'),
(58, 1, 9, 1, '2026-03-24 15:47:24'),
(59, 1, 9, 1, '2026-03-24 15:47:24'),
(60, 1, 9, 1, '2026-03-24 15:47:24'),
(61, 1, 9, 1, '2026-03-24 15:47:24'),
(62, 1, 9, 1, '2026-03-24 15:47:24'),
(63, 1, 9, 1, '2026-03-24 15:47:24'),
(64, 1, 9, 1, '2026-03-24 15:47:24'),
(65, 1, 9, 1, '2026-03-24 15:47:24'),
(66, 1, 9, 1, '2026-03-24 15:47:24'),
(67, 1, 9, 1, '2026-03-24 15:47:24'),
(68, 1, 9, 1, '2026-03-24 15:47:24'),
(69, 1, 9, 1, '2026-03-24 15:47:24'),
(70, 1, 9, 1, '2026-03-24 15:47:24'),
(71, 1, 9, 1, '2026-03-24 15:47:24'),
(72, 1, 9, 1, '2026-03-24 15:47:24'),
(73, 1, 9, 1, '2026-03-24 15:47:24'),
(74, 1, 9, 1, '2026-03-24 15:47:24'),
(75, 1, 9, 1, '2026-03-24 15:47:24'),
(76, 1, 9, 1, '2026-03-24 15:47:24'),
(77, 1, 9, 1, '2026-03-24 15:47:24'),
(78, 1, 9, 1, '2026-03-24 15:47:24'),
(79, 1, 9, 1, '2026-03-24 15:47:24'),
(80, 1, 9, 1, '2026-03-24 15:47:24'),
(81, 1, 9, 1, '2026-03-24 15:47:24'),
(82, 1, 9, 1, '2026-03-24 15:47:24'),
(83, 1, 9, 1, '2026-03-24 15:47:24'),
(84, 1, 9, 1, '2026-03-24 15:47:24'),
(85, 1, 9, 1, '2026-03-24 15:47:24');

-- --------------------------------------------------------

--
-- Struttura della tabella `allergeni`
--

CREATE TABLE `allergeni` (
  `idAllergene` int(11) NOT NULL,
  `nomeAllergene` varchar(50) NOT NULL,
  `tipo` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `allergeni`
--

INSERT INTO `allergeni` (`idAllergene`, `nomeAllergene`, `tipo`) VALUES
(1, 'arachide', 'frutta');

-- --------------------------------------------------------

--
-- Struttura della tabella `amministratori`
--

CREATE TABLE `amministratori` (
  `idAdmin` int(11) NOT NULL,
  `nome` varchar(50) NOT NULL,
  `cognome` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `amministratori`
--

INSERT INTO `amministratori` (`idAdmin`, `nome`, `cognome`, `email`, `password`) VALUES
(1, 'Matteo', 'Buldrini', 'admin@soaplab.it', '$2y$10$nVnAynjbujvr1.Vok5pY4OGjcF1H92WXm5UggsHRyFwwYUvAfjIjC');

-- --------------------------------------------------------

--
-- Struttura della tabella `benefici`
--

CREATE TABLE `benefici` (
  `idBeneficio` int(11) NOT NULL,
  `nomeBeneficio` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `benefici`
--

INSERT INTO `benefici` (`idBeneficio`, `nomeBeneficio`) VALUES
(1, 'idrata');

-- --------------------------------------------------------

--
-- Struttura della tabella `categorie`
--

CREATE TABLE `categorie` (
  `idCategoria` int(11) NOT NULL,
  `nomeCategoria` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `categorie`
--

INSERT INTO `categorie` (`idCategoria`, `nomeCategoria`) VALUES
(1, 'Viso'),
(2, 'Corpo'),
(3, 'Shampoo'),
(4, 'Mani');

-- --------------------------------------------------------

--
-- Struttura della tabella `certificazioni_bio`
--

CREATE TABLE `certificazioni_bio` (
  `idCertificazione` int(11) NOT NULL,
  `codiceStandard` varchar(50) NOT NULL,
  `validita` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `certificazioni_bio`
--

INSERT INTO `certificazioni_bio` (`idCertificazione`, `codiceStandard`, `validita`) VALUES
(1, 'IT-BIO-252910', '2030-05-03'),
(2, 'IT-BIO-252911', '2034-12-07'),
(3, 'IT-BIO-2523232', '2027-01-03');

-- --------------------------------------------------------

--
-- Struttura della tabella `immagini`
--

CREATE TABLE `immagini` (
  `idImmagine` int(11) NOT NULL,
  `idSapone` int(11) NOT NULL,
  `percorso` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `immagini`
--

INSERT INTO `immagini` (`idImmagine`, `idSapone`, `percorso`) VALUES
(2, 2, 'https://picsum.photos/seed/soap1/300/200'),
(3, 3, 'https://picsum.photos/seed/soap2/300/200'),
(4, 4, 'https://picsum.photos/seed/soap3/300/200'),
(5, 6, 'uploads/1774361514_0_booking-ar21.svg'),
(6, 7, 'uploads/1774361514_1_centro.cattolica.jpg');

-- --------------------------------------------------------

--
-- Struttura della tabella `indirizzi`
--

CREATE TABLE `indirizzi` (
  `idIndirizzo` int(11) NOT NULL,
  `idUtente` int(11) NOT NULL,
  `via` varchar(255) NOT NULL,
  `numeroCivico` varchar(10) DEFAULT NULL,
  `citta` varchar(100) DEFAULT NULL,
  `cap` varchar(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `indirizzi`
--

INSERT INTO `indirizzi` (`idIndirizzo`, `idUtente`, `via`, `numeroCivico`, `citta`, `cap`) VALUES
(1, 3, 'Via Ferrara 274', NULL, 'Cesena', '47521'),
(2, 3, 'Viale Veneto 274', NULL, 'Riccione', '47838'),
(3, 8, 'Via Ferrara', '274', '47521', 'Cesen'),
(4, 1, 'Via delle Prove 1', NULL, NULL, '47521');

-- --------------------------------------------------------

--
-- Struttura della tabella `ingrediente_associato_beneficio`
--

CREATE TABLE `ingrediente_associato_beneficio` (
  `idIngrediente` int(11) NOT NULL,
  `idBeneficio` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `ingrediente_associato_beneficio`
--

INSERT INTO `ingrediente_associato_beneficio` (`idIngrediente`, `idBeneficio`) VALUES
(1, 1);

-- --------------------------------------------------------

--
-- Struttura della tabella `ingredienti`
--

CREATE TABLE `ingredienti` (
  `idIngrediente` int(11) NOT NULL,
  `nomeIngrediente` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `ingredienti`
--

INSERT INTO `ingredienti` (`idIngrediente`, `nomeIngrediente`) VALUES
(1, 'olio');

-- --------------------------------------------------------

--
-- Struttura della tabella `inserzioni`
--

CREATE TABLE `inserzioni` (
  `idInserzione` int(11) NOT NULL,
  `idUtente` int(11) NOT NULL,
  `titolo` varchar(100) NOT NULL,
  `prezzoTotale` decimal(10,2) NOT NULL,
  `pesoComplessivo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `inserzioni`
--

INSERT INTO `inserzioni` (`idInserzione`, `idUtente`, `titolo`, `prezzoTotale`, `pesoComplessivo`) VALUES
(2, 1, 'Kit Corpo Agrumi e Miele', 22.00, 400),
(3, 1, 'Shampoo Solido Purificante', 9.90, 80),
(4, 1, 'Sapone Mani Igienizzante', 7.50, 100),
(7, 8, 'sapone francese', 10.00, 100),
(8, 8, 'kit saponi', 20.00, 200),
(9, 7, 'Sapone di Test per Blocco', 10.00, 100);

-- --------------------------------------------------------

--
-- Struttura della tabella `pagamenti`
--

CREATE TABLE `pagamenti` (
  `idPagamento` int(11) NOT NULL,
  `idAcquisto` int(11) NOT NULL,
  `esitoPagamento` varchar(50) DEFAULT NULL,
  `idTransazionePaypal` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `pagamenti`
--

INSERT INTO `pagamenti` (`idPagamento`, `idAcquisto`, `esitoPagamento`, `idTransazionePaypal`) VALUES
(1, 1, 'Completato', 'PAYID-29FD8243B09AEE0B'),
(2, 2, 'Completato', 'PAYID-AAE5EB98F3DD1FF2');

-- --------------------------------------------------------

--
-- Struttura della tabella `proprieta`
--

CREATE TABLE `proprieta` (
  `idProprieta` int(11) NOT NULL,
  `nomeProprieta` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `recensioni`
--

CREATE TABLE `recensioni` (
  `idRecensione` int(11) NOT NULL,
  `idAcquisto` int(11) NOT NULL,
  `idMittente` int(11) NOT NULL,
  `idDestinatario` int(11) NOT NULL,
  `voto` int(11) DEFAULT NULL CHECK (`voto` >= 1 and `voto` <= 5),
  `commento` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `recensioni`
--

INSERT INTO `recensioni` (`idRecensione`, `idAcquisto`, `idMittente`, `idDestinatario`, `voto`, `commento`) VALUES
(1, 1, 8, 1, 4, 'Tanta roba'),
(24, 14, 1, 7, 1, 'Test 5 recensioni'),
(25, 15, 1, 7, 1, 'Test 5 recensioni'),
(26, 16, 1, 7, 1, 'Test 5 recensioni'),
(27, 17, 1, 7, 1, 'Test 5 recensioni'),
(28, 18, 1, 7, 1, 'Test 5 recensioni'),
(31, 19, 1, 7, 1, 'Feedback negativo aggiuntivo'),
(32, 20, 1, 7, 1, 'Feedback negativo aggiuntivo'),
(33, 21, 1, 7, 1, 'Feedback negativo aggiuntivo'),
(34, 22, 1, 7, 1, 'Feedback negativo aggiuntivo'),
(35, 23, 1, 7, 1, 'Feedback negativo aggiuntivo'),
(38, 24, 1, 7, 1, 'Prodotto arrivato rotto e venditore poco cortese.'),
(39, 25, 1, 7, 5, 'Prodotto eccellente, profumo fantastico e spedizione rapida!'),
(40, 26, 1, 7, 5, 'Prodotto eccellente, profumo fantastico e spedizione rapida!'),
(41, 27, 1, 7, 5, 'Prodotto eccellente, profumo fantastico e spedizione rapida!'),
(42, 28, 1, 7, 5, 'Prodotto eccellente, profumo fantastico e spedizione rapida!'),
(43, 29, 1, 7, 5, 'Prodotto eccellente, profumo fantastico e spedizione rapida!'),
(44, 30, 1, 7, 5, 'Prodotto eccellente, profumo fantastico e spedizione rapida!'),
(45, 31, 1, 7, 5, 'Prodotto eccellente, profumo fantastico e spedizione rapida!'),
(46, 32, 1, 7, 5, 'Prodotto eccellente, profumo fantastico e spedizione rapida!'),
(47, 33, 1, 7, 5, 'Prodotto eccellente, profumo fantastico e spedizione rapida!'),
(48, 34, 1, 7, 5, 'Prodotto eccellente, profumo fantastico e spedizione rapida!'),
(49, 35, 1, 7, 5, 'Prodotto eccellente, profumo fantastico e spedizione rapida!'),
(50, 36, 1, 7, 5, 'Prodotto eccellente, profumo fantastico e spedizione rapida!'),
(51, 37, 1, 7, 5, 'Prodotto eccellente, profumo fantastico e spedizione rapida!'),
(52, 38, 1, 7, 5, 'Prodotto eccellente, profumo fantastico e spedizione rapida!'),
(53, 39, 1, 7, 5, 'Prodotto eccellente, profumo fantastico e spedizione rapida!'),
(54, 40, 1, 7, 5, 'Prodotto eccellente, profumo fantastico e spedizione rapida!'),
(55, 41, 1, 7, 5, 'Prodotto eccellente, profumo fantastico e spedizione rapida!'),
(56, 42, 1, 7, 5, 'Prodotto eccellente, profumo fantastico e spedizione rapida!'),
(57, 43, 1, 7, 5, 'Prodotto eccellente, profumo fantastico e spedizione rapida!'),
(58, 44, 1, 7, 5, 'Prodotto eccellente, profumo fantastico e spedizione rapida!'),
(59, 45, 1, 7, 5, 'Prodotto eccellente, profumo fantastico e spedizione rapida!'),
(60, 46, 1, 7, 5, 'Prodotto eccellente, profumo fantastico e spedizione rapida!'),
(61, 47, 1, 7, 5, 'Prodotto eccellente, profumo fantastico e spedizione rapida!'),
(62, 48, 1, 7, 5, 'Prodotto eccellente, profumo fantastico e spedizione rapida!'),
(63, 49, 1, 7, 5, 'Prodotto eccellente, profumo fantastico e spedizione rapida!'),
(64, 50, 1, 7, 5, 'Prodotto eccellente, profumo fantastico e spedizione rapida!'),
(65, 51, 1, 7, 5, 'Prodotto eccellente, profumo fantastico e spedizione rapida!'),
(66, 52, 1, 7, 5, 'Prodotto eccellente, profumo fantastico e spedizione rapida!'),
(67, 53, 1, 7, 5, 'Prodotto eccellente, profumo fantastico e spedizione rapida!'),
(68, 54, 1, 7, 5, 'Prodotto eccellente, profumo fantastico e spedizione rapida!'),
(70, 56, 1, 7, 5, 'Prodotto eccellente, profumo fantastico e spedizione rapida!'),
(71, 57, 1, 7, 5, 'Prodotto eccellente, profumo fantastico e spedizione rapida!'),
(72, 58, 1, 7, 5, 'Prodotto eccellente, profumo fantastico e spedizione rapida!'),
(73, 59, 1, 7, 5, 'Prodotto eccellente, profumo fantastico e spedizione rapida!'),
(74, 60, 1, 7, 5, 'Prodotto eccellente, profumo fantastico e spedizione rapida!'),
(75, 61, 1, 7, 5, 'Prodotto eccellente, profumo fantastico e spedizione rapida!'),
(76, 62, 1, 7, 5, 'Prodotto eccellente, profumo fantastico e spedizione rapida!'),
(77, 63, 1, 7, 5, 'Prodotto eccellente, profumo fantastico e spedizione rapida!'),
(78, 64, 1, 7, 5, 'Prodotto eccellente, profumo fantastico e spedizione rapida!'),
(79, 65, 1, 7, 5, 'Prodotto eccellente, profumo fantastico e spedizione rapida!'),
(80, 66, 1, 7, 5, 'Prodotto eccellente, profumo fantastico e spedizione rapida!'),
(81, 67, 1, 7, 5, 'Prodotto eccellente, profumo fantastico e spedizione rapida!'),
(82, 68, 1, 7, 5, 'Prodotto eccellente, profumo fantastico e spedizione rapida!'),
(83, 69, 1, 7, 5, 'Prodotto eccellente, profumo fantastico e spedizione rapida!'),
(84, 70, 1, 7, 5, 'Prodotto eccellente, profumo fantastico e spedizione rapida!'),
(85, 71, 1, 7, 5, 'Prodotto eccellente, profumo fantastico e spedizione rapida!'),
(86, 72, 1, 7, 5, 'Prodotto eccellente, profumo fantastico e spedizione rapida!'),
(87, 73, 1, 7, 5, 'Prodotto eccellente, profumo fantastico e spedizione rapida!'),
(88, 74, 1, 7, 5, 'Prodotto eccellente, profumo fantastico e spedizione rapida!'),
(89, 75, 1, 7, 5, 'Prodotto eccellente, profumo fantastico e spedizione rapida!'),
(90, 76, 1, 7, 5, 'Prodotto eccellente, profumo fantastico e spedizione rapida!'),
(91, 77, 1, 7, 5, 'Prodotto eccellente, profumo fantastico e spedizione rapida!'),
(92, 78, 1, 7, 5, 'Prodotto eccellente, profumo fantastico e spedizione rapida!'),
(93, 79, 1, 7, 5, 'Prodotto eccellente, profumo fantastico e spedizione rapida!'),
(94, 80, 1, 7, 5, 'Prodotto eccellente, profumo fantastico e spedizione rapida!'),
(95, 81, 1, 7, 5, 'Prodotto eccellente, profumo fantastico e spedizione rapida!'),
(96, 82, 1, 7, 5, 'Prodotto eccellente, profumo fantastico e spedizione rapida!'),
(97, 83, 1, 7, 5, 'Prodotto eccellente, profumo fantastico e spedizione rapida!'),
(98, 84, 1, 7, 5, 'Prodotto eccellente, profumo fantastico e spedizione rapida!'),
(99, 85, 1, 7, 5, 'Prodotto eccellente, profumo fantastico e spedizione rapida!');

-- --------------------------------------------------------

--
-- Struttura della tabella `sapone_contiene_ingrediente`
--

CREATE TABLE `sapone_contiene_ingrediente` (
  `idSapone` int(11) NOT NULL,
  `idIngrediente` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `sapone_ha_proprieta`
--

CREATE TABLE `sapone_ha_proprieta` (
  `idSapone` int(11) NOT NULL,
  `idProprieta` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `sapone_presenta_allergene`
--

CREATE TABLE `sapone_presenta_allergene` (
  `idSapone` int(11) NOT NULL,
  `idAllergene` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `saponi`
--

CREATE TABLE `saponi` (
  `idSapone` int(11) NOT NULL,
  `idInserzione` int(11) NOT NULL,
  `idCategoria` int(11) DEFAULT NULL,
  `idCertificazione` int(11) DEFAULT NULL,
  `nomeCommerciale` varchar(100) NOT NULL,
  `tipoPelleConsigliata` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `saponi`
--

INSERT INTO `saponi` (`idSapone`, `idInserzione`, `idCategoria`, `idCertificazione`, `nomeCommerciale`, `tipoPelleConsigliata`) VALUES
(2, 2, 2, NULL, 'Sapone Esfoliante Arancio', 'Tutti i tipi'),
(3, 3, 3, NULL, 'Shampoo Argilla Verde', 'Capelli Grassi'),
(4, 4, 4, NULL, 'Mani Propoli e Timo', 'Uso frequente'),
(5, 7, 4, 1, 'sapone francese', 'pelle secca'),
(6, 8, 3, 2, 'sapone1', 'secce'),
(7, 8, 2, 3, 'Sapone2', 'sensibili');

-- --------------------------------------------------------

--
-- Struttura della tabella `spedizioni`
--

CREATE TABLE `spedizioni` (
  `idSpedizione` int(11) NOT NULL,
  `idAcquisto` int(11) NOT NULL,
  `stato` varchar(50) DEFAULT 'In lavorazione',
  `dataSpedizione` date DEFAULT NULL,
  `dataConsegnaPrevista` date DEFAULT NULL,
  `tracking` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `spedizioni`
--

INSERT INTO `spedizioni` (`idSpedizione`, `idAcquisto`, `stato`, `dataSpedizione`, `dataConsegnaPrevista`, `tracking`) VALUES
(1, 1, 'Consegnato', '2026-03-23', '2026-03-26', 'IT1234567890ABC'),
(2, 2, 'In lavorazione', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Struttura della tabella `utenti`
--

CREATE TABLE `utenti` (
  `idUtente` int(11) NOT NULL,
  `nome` varchar(50) NOT NULL,
  `cognome` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `statoVendita` varchar(20) DEFAULT 'attivo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `utenti`
--

INSERT INTO `utenti` (`idUtente`, `nome`, `cognome`, `email`, `password`, `statoVendita`) VALUES
(1, 'Matteo', 'Buldrini', 'matteo@gmail.com', '$2y$10$aaRp3NrxmqquL4y1PIjR8eSP36qDZeC8T/ukeCOwMxrxbx6A3SUg.', 'attivo'),
(2, 'Buldra', 'Buldra', 'buldra@gmail.com', '$2y$10$aVyljSHhiJ8AZgEPissG1O.1ByxPOAdLYHAOO.ib4jaLkO./ps4uy', 'attivo'),
(3, 'Mario', 'Mario', 'Mario@gmail.com', '$2y$10$koTGr1hOyxfLGGKHyobPq.VFlWOrEpjNOe0sBSgw5hvnW7dvB8Dnm', 'attivo'),
(7, 'Giacomo', 'Lollo', 'giacomo@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'bloccato'),
(8, 'Matteo', 'Buldrini', 'buldra00@icloud.com', '$2y$10$/R.AJYaG7Bs2tXYkVnwZE.WmzO4.jlns4U0Y.RiRZJ04BngHC.tbK', 'attivo');

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `acquisti`
--
ALTER TABLE `acquisti`
  ADD PRIMARY KEY (`idAcquisto`),
  ADD KEY `idUtente` (`idUtente`),
  ADD KEY `idInserzione` (`idInserzione`),
  ADD KEY `idIndirizzo` (`idIndirizzo`);

--
-- Indici per le tabelle `allergeni`
--
ALTER TABLE `allergeni`
  ADD PRIMARY KEY (`idAllergene`);

--
-- Indici per le tabelle `amministratori`
--
ALTER TABLE `amministratori`
  ADD PRIMARY KEY (`idAdmin`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indici per le tabelle `benefici`
--
ALTER TABLE `benefici`
  ADD PRIMARY KEY (`idBeneficio`);

--
-- Indici per le tabelle `categorie`
--
ALTER TABLE `categorie`
  ADD PRIMARY KEY (`idCategoria`);

--
-- Indici per le tabelle `certificazioni_bio`
--
ALTER TABLE `certificazioni_bio`
  ADD PRIMARY KEY (`idCertificazione`);

--
-- Indici per le tabelle `immagini`
--
ALTER TABLE `immagini`
  ADD PRIMARY KEY (`idImmagine`),
  ADD KEY `idSapone` (`idSapone`);

--
-- Indici per le tabelle `indirizzi`
--
ALTER TABLE `indirizzi`
  ADD PRIMARY KEY (`idIndirizzo`),
  ADD KEY `utente_id` (`idUtente`);

--
-- Indici per le tabelle `ingrediente_associato_beneficio`
--
ALTER TABLE `ingrediente_associato_beneficio`
  ADD PRIMARY KEY (`idIngrediente`,`idBeneficio`),
  ADD KEY `idBeneficio` (`idBeneficio`);

--
-- Indici per le tabelle `ingredienti`
--
ALTER TABLE `ingredienti`
  ADD PRIMARY KEY (`idIngrediente`);

--
-- Indici per le tabelle `inserzioni`
--
ALTER TABLE `inserzioni`
  ADD PRIMARY KEY (`idInserzione`),
  ADD KEY `idUtente` (`idUtente`);

--
-- Indici per le tabelle `pagamenti`
--
ALTER TABLE `pagamenti`
  ADD PRIMARY KEY (`idPagamento`),
  ADD KEY `idAcquisto` (`idAcquisto`);

--
-- Indici per le tabelle `proprieta`
--
ALTER TABLE `proprieta`
  ADD PRIMARY KEY (`idProprieta`);

--
-- Indici per le tabelle `recensioni`
--
ALTER TABLE `recensioni`
  ADD PRIMARY KEY (`idRecensione`),
  ADD KEY `idAcquisto` (`idAcquisto`),
  ADD KEY `idMittente` (`idMittente`),
  ADD KEY `idDestinatario` (`idDestinatario`);

--
-- Indici per le tabelle `sapone_contiene_ingrediente`
--
ALTER TABLE `sapone_contiene_ingrediente`
  ADD PRIMARY KEY (`idSapone`,`idIngrediente`),
  ADD KEY `idIngrediente` (`idIngrediente`);

--
-- Indici per le tabelle `sapone_ha_proprieta`
--
ALTER TABLE `sapone_ha_proprieta`
  ADD PRIMARY KEY (`idSapone`,`idProprieta`),
  ADD KEY `idProprieta` (`idProprieta`);

--
-- Indici per le tabelle `sapone_presenta_allergene`
--
ALTER TABLE `sapone_presenta_allergene`
  ADD PRIMARY KEY (`idSapone`,`idAllergene`),
  ADD KEY `idAllergene` (`idAllergene`);

--
-- Indici per le tabelle `saponi`
--
ALTER TABLE `saponi`
  ADD PRIMARY KEY (`idSapone`),
  ADD KEY `idInserzione` (`idInserzione`),
  ADD KEY `idCategoria` (`idCategoria`),
  ADD KEY `idCertificazione` (`idCertificazione`);

--
-- Indici per le tabelle `spedizioni`
--
ALTER TABLE `spedizioni`
  ADD PRIMARY KEY (`idSpedizione`),
  ADD KEY `idAcquisto` (`idAcquisto`);

--
-- Indici per le tabelle `utenti`
--
ALTER TABLE `utenti`
  ADD PRIMARY KEY (`idUtente`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `acquisti`
--
ALTER TABLE `acquisti`
  MODIFY `idAcquisto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=87;

--
-- AUTO_INCREMENT per la tabella `allergeni`
--
ALTER TABLE `allergeni`
  MODIFY `idAllergene` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT per la tabella `amministratori`
--
ALTER TABLE `amministratori`
  MODIFY `idAdmin` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT per la tabella `benefici`
--
ALTER TABLE `benefici`
  MODIFY `idBeneficio` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT per la tabella `categorie`
--
ALTER TABLE `categorie`
  MODIFY `idCategoria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT per la tabella `certificazioni_bio`
--
ALTER TABLE `certificazioni_bio`
  MODIFY `idCertificazione` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT per la tabella `immagini`
--
ALTER TABLE `immagini`
  MODIFY `idImmagine` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT per la tabella `indirizzi`
--
ALTER TABLE `indirizzi`
  MODIFY `idIndirizzo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT per la tabella `ingredienti`
--
ALTER TABLE `ingredienti`
  MODIFY `idIngrediente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT per la tabella `inserzioni`
--
ALTER TABLE `inserzioni`
  MODIFY `idInserzione` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT per la tabella `pagamenti`
--
ALTER TABLE `pagamenti`
  MODIFY `idPagamento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT per la tabella `proprieta`
--
ALTER TABLE `proprieta`
  MODIFY `idProprieta` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `recensioni`
--
ALTER TABLE `recensioni`
  MODIFY `idRecensione` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=101;

--
-- AUTO_INCREMENT per la tabella `saponi`
--
ALTER TABLE `saponi`
  MODIFY `idSapone` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT per la tabella `spedizioni`
--
ALTER TABLE `spedizioni`
  MODIFY `idSpedizione` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT per la tabella `utenti`
--
ALTER TABLE `utenti`
  MODIFY `idUtente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Limiti per le tabelle scaricate
--

--
-- Limiti per la tabella `acquisti`
--
ALTER TABLE `acquisti`
  ADD CONSTRAINT `acquisti_ibfk_1` FOREIGN KEY (`idUtente`) REFERENCES `utenti` (`idUtente`),
  ADD CONSTRAINT `acquisti_ibfk_2` FOREIGN KEY (`idInserzione`) REFERENCES `inserzioni` (`idInserzione`),
  ADD CONSTRAINT `acquisti_ibfk_3` FOREIGN KEY (`idIndirizzo`) REFERENCES `indirizzi` (`idIndirizzo`);

--
-- Limiti per la tabella `immagini`
--
ALTER TABLE `immagini`
  ADD CONSTRAINT `immagini_ibfk_1` FOREIGN KEY (`idSapone`) REFERENCES `saponi` (`idSapone`) ON DELETE CASCADE;

--
-- Limiti per la tabella `indirizzi`
--
ALTER TABLE `indirizzi`
  ADD CONSTRAINT `fk_indirizzi_utenti` FOREIGN KEY (`idUtente`) REFERENCES `utenti` (`idUtente`) ON DELETE CASCADE;

--
-- Limiti per la tabella `ingrediente_associato_beneficio`
--
ALTER TABLE `ingrediente_associato_beneficio`
  ADD CONSTRAINT `ingrediente_associato_beneficio_ibfk_1` FOREIGN KEY (`idIngrediente`) REFERENCES `ingredienti` (`idIngrediente`),
  ADD CONSTRAINT `ingrediente_associato_beneficio_ibfk_2` FOREIGN KEY (`idBeneficio`) REFERENCES `benefici` (`idBeneficio`);

--
-- Limiti per la tabella `inserzioni`
--
ALTER TABLE `inserzioni`
  ADD CONSTRAINT `inserzioni_ibfk_1` FOREIGN KEY (`idUtente`) REFERENCES `utenti` (`idUtente`) ON DELETE CASCADE;

--
-- Limiti per la tabella `pagamenti`
--
ALTER TABLE `pagamenti`
  ADD CONSTRAINT `pagamenti_ibfk_1` FOREIGN KEY (`idAcquisto`) REFERENCES `acquisti` (`idAcquisto`) ON DELETE CASCADE;

--
-- Limiti per la tabella `recensioni`
--
ALTER TABLE `recensioni`
  ADD CONSTRAINT `recensioni_ibfk_1` FOREIGN KEY (`idAcquisto`) REFERENCES `acquisti` (`idAcquisto`),
  ADD CONSTRAINT `recensioni_ibfk_2` FOREIGN KEY (`idMittente`) REFERENCES `utenti` (`idUtente`),
  ADD CONSTRAINT `recensioni_ibfk_3` FOREIGN KEY (`idDestinatario`) REFERENCES `utenti` (`idUtente`);

--
-- Limiti per la tabella `sapone_contiene_ingrediente`
--
ALTER TABLE `sapone_contiene_ingrediente`
  ADD CONSTRAINT `sapone_contiene_ingrediente_ibfk_1` FOREIGN KEY (`idSapone`) REFERENCES `saponi` (`idSapone`),
  ADD CONSTRAINT `sapone_contiene_ingrediente_ibfk_2` FOREIGN KEY (`idIngrediente`) REFERENCES `ingredienti` (`idIngrediente`);

--
-- Limiti per la tabella `sapone_ha_proprieta`
--
ALTER TABLE `sapone_ha_proprieta`
  ADD CONSTRAINT `sapone_ha_proprieta_ibfk_1` FOREIGN KEY (`idSapone`) REFERENCES `saponi` (`idSapone`),
  ADD CONSTRAINT `sapone_ha_proprieta_ibfk_2` FOREIGN KEY (`idProprieta`) REFERENCES `proprieta` (`idProprieta`);

--
-- Limiti per la tabella `sapone_presenta_allergene`
--
ALTER TABLE `sapone_presenta_allergene`
  ADD CONSTRAINT `sapone_presenta_allergene_ibfk_1` FOREIGN KEY (`idSapone`) REFERENCES `saponi` (`idSapone`),
  ADD CONSTRAINT `sapone_presenta_allergene_ibfk_2` FOREIGN KEY (`idAllergene`) REFERENCES `allergeni` (`idAllergene`);

--
-- Limiti per la tabella `saponi`
--
ALTER TABLE `saponi`
  ADD CONSTRAINT `saponi_ibfk_1` FOREIGN KEY (`idInserzione`) REFERENCES `inserzioni` (`idInserzione`) ON DELETE CASCADE,
  ADD CONSTRAINT `saponi_ibfk_2` FOREIGN KEY (`idCategoria`) REFERENCES `categorie` (`idCategoria`),
  ADD CONSTRAINT `saponi_ibfk_3` FOREIGN KEY (`idCertificazione`) REFERENCES `certificazioni_bio` (`idCertificazione`);

--
-- Limiti per la tabella `spedizioni`
--
ALTER TABLE `spedizioni`
  ADD CONSTRAINT `spedizioni_ibfk_1` FOREIGN KEY (`idAcquisto`) REFERENCES `acquisti` (`idAcquisto`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
