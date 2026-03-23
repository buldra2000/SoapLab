<?php
session_start();
require_once 'db.php';

// 1. Controllo sicurezza
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

// 2. Recuperiamo il primo indirizzo disponibile dell'utente (per semplicità)
// Nella versione completa, l'utente dovrebbe sceglierlo in una pagina intermedia
$sql_addr = "SELECT idIndirizzo FROM indirizzi WHERE idUtente = ? LIMIT 1";
$stmt_addr = $conn->prepare($sql_addr);
$stmt_addr->bind_param("i", $idUtente);
$stmt_addr->execute();
$res_addr = $stmt_addr->get_result()->fetch_assoc();

if (!$res_addr) {
    die("Errore: Devi aggiungere almeno un indirizzo nel tuo profilo prima di acquistare! <a href='../aggiungi-indirizzo.php'>Aggiungilo ora</a>");
}
$idIndirizzo = $res_addr['idIndirizzo'];

// 3. Iniziamo la Transazione per garantire l'integrità dei dati
$conn->begin_transaction();

try {
    // --- STEP 1: Registriamo l'Acquisto ---
    $sql_acq = "INSERT INTO acquisti (idUtente, idInserzione, idIndirizzo, dataAcquisto) VALUES (?, ?, ?, NOW())";
    $stmt_acq = $conn->prepare($sql_acq);
    $stmt_acq->bind_param("iii", $idUtente, $idInserzione, $idIndirizzo);
    $stmt_acq->execute();
    $idAcquisto = $stmt_acq->insert_id;

    // --- STEP 2: Registriamo il Pagamento (Simulazione PayPal) ---
    $esito = "Completato";
    $transazione_finta = "PAYID-" . strtoupper(bin2hex(random_bytes(8)));
    $sql_pag = "INSERT INTO pagamenti (idAcquisto, esitoPagamento, idTransazionePaypal) VALUES (?, ?, ?)";
    $stmt_pag = $conn->prepare($sql_pag);
    $stmt_pag->bind_param("iss", $idAcquisto, $esito, $transazione_finta);
    $stmt_pag->execute();

    // --- STEP 3: Creiamo la Spedizione ---
    $stato_iniziale = "In lavorazione";
    $sql_sped = "INSERT INTO spedizioni (idAcquisto, stato) VALUES (?, ?)";
    $stmt_sped = $conn->prepare($sql_sped);
    $stmt_sped->bind_param("is", $idAcquisto, $stato_iniziale);
    $stmt_sped->execute();

    // Se tutto è andato bene, confermiamo
    $conn->commit();
    
    // Reindirizziamo alla dashboard con messaggio di successo
    header("Location: ../dashboard.php?msg=acquisto_completato");
    exit();

} catch (Exception $e) {
    $conn->rollback();
    die("Errore critico durante l'acquisto: " . $e->getMessage());
}
?>