<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.html");
    exit();
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: ../index.php");
    exit();
}

$idUtente = $_SESSION['user_id'];
$idInserzione = (int)$_GET['id'];

$sql_addr = "SELECT idIndirizzo FROM indirizzi WHERE idUtente = ? LIMIT 1";
$stmt_addr = $conn->prepare($sql_addr);
$stmt_addr->bind_param("i", $idUtente);
$stmt_addr->execute();
$res_addr = $stmt_addr->get_result()->fetch_assoc();

if (!$res_addr) {
    die("Errore: Devi aggiungere almeno un indirizzo nel tuo profilo prima di acquistare! <a href='../aggiungi-indirizzo.php'>Aggiungilo ora</a>");
}
$idIndirizzo = $res_addr['idIndirizzo'];

$conn->begin_transaction();

try {
    $sql_acq = "INSERT INTO acquisti (idUtente, idInserzione, idIndirizzo, dataAcquisto) VALUES (?, ?, ?, NOW())";
    $stmt_acq = $conn->prepare($sql_acq);
    $stmt_acq->bind_param("iii", $idUtente, $idInserzione, $idIndirizzo);
    $stmt_acq->execute();
    $idAcquisto = $stmt_acq->insert_id;

    $esito = "Completato";
    $transazione_finta = "PAYID-" . strtoupper(bin2hex(random_bytes(8)));
    $sql_pag = "INSERT INTO pagamenti (idAcquisto, esitoPagamento, idTransazionePaypal) VALUES (?, ?, ?)";
    $stmt_pag = $conn->prepare($sql_pag);
    $stmt_pag->bind_param("iss", $idAcquisto, $esito, $transazione_finta);
    $stmt_pag->execute();

    $stato_iniziale = "In lavorazione";
    $sql_sped = "INSERT INTO spedizioni (idAcquisto, stato) VALUES (?, ?)";
    $stmt_sped = $conn->prepare($sql_sped);
    $stmt_sped->bind_param("is", $idAcquisto, $stato_iniziale);
    $stmt_sped->execute();

    $conn->commit();
    
    header("Location: ../dashboard.php?msg=acquisto_completato");
    exit();

} catch (Exception $e) {
    $conn->rollback();
    die("Errore critico durante l'acquisto: " . $e->getMessage());
}
?>