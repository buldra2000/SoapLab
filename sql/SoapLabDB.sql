-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Creato il: Mar 23, 2026 alle 16:12
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

-- --------------------------------------------------------

--
-- Struttura della tabella `allergeni`
--

CREATE TABLE `allergeni` (
  `idAllergene` int(11) NOT NULL,
  `nomeAllergene` varchar(50) NOT NULL,
  `tipo` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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

-- --------------------------------------------------------

--
-- Struttura della tabella `benefici`
--

CREATE TABLE `benefici` (
  `idBeneficio` int(11) NOT NULL,
  `nomeBeneficio` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `categorie`
--

CREATE TABLE `categorie` (
  `idCategoria` int(11) NOT NULL,
  `nomeCategoria` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `certificazioni_bio`
--

CREATE TABLE `certificazioni_bio` (
  `idCertificazione` int(11) NOT NULL,
  `codiceStandard` varchar(50) NOT NULL,
  `validita` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `immagini`
--

CREATE TABLE `immagini` (
  `idImmagine` int(11) NOT NULL,
  `idSapone` int(11) NOT NULL,
  `percorso` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(2, 3, 'Viale Veneto 274', NULL, 'Riccione', '47838');

-- --------------------------------------------------------

--
-- Struttura della tabella `ingrediente_associato_beneficio`
--

CREATE TABLE `ingrediente_associato_beneficio` (
  `idIngrediente` int(11) NOT NULL,
  `idBeneficio` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `ingredienti`
--

CREATE TABLE `ingredienti` (
  `idIngrediente` int(11) NOT NULL,
  `nomeIngrediente` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(7, 'Giacomo', 'Lollo', 'giacomo@gmail.com', '$2y$10$SoeWsVoGWCZSQd8F8BUjZuQG9ynKcbNI9akVYAOrmRdEQ0jiyLXI2', 'attivo');

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
  MODIFY `idAcquisto` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `allergeni`
--
ALTER TABLE `allergeni`
  MODIFY `idAllergene` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `amministratori`
--
ALTER TABLE `amministratori`
  MODIFY `idAdmin` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `benefici`
--
ALTER TABLE `benefici`
  MODIFY `idBeneficio` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `categorie`
--
ALTER TABLE `categorie`
  MODIFY `idCategoria` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `certificazioni_bio`
--
ALTER TABLE `certificazioni_bio`
  MODIFY `idCertificazione` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `immagini`
--
ALTER TABLE `immagini`
  MODIFY `idImmagine` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `indirizzi`
--
ALTER TABLE `indirizzi`
  MODIFY `idIndirizzo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT per la tabella `ingredienti`
--
ALTER TABLE `ingredienti`
  MODIFY `idIngrediente` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `inserzioni`
--
ALTER TABLE `inserzioni`
  MODIFY `idInserzione` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `pagamenti`
--
ALTER TABLE `pagamenti`
  MODIFY `idPagamento` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `proprieta`
--
ALTER TABLE `proprieta`
  MODIFY `idProprieta` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `recensioni`
--
ALTER TABLE `recensioni`
  MODIFY `idRecensione` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `saponi`
--
ALTER TABLE `saponi`
  MODIFY `idSapone` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `spedizioni`
--
ALTER TABLE `spedizioni`
  MODIFY `idSpedizione` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `utenti`
--
ALTER TABLE `utenti`
  MODIFY `idUtente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

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
