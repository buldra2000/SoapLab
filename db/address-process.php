<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
require_once 'db.php';
/**
 * Processo indirizzi.
 *  1) Verifica richiesta POST
 *  2) Recupero ID dalla sessione
 *  3) Recupera e pulisci form
 *  4) Prepara query inserimento
 *  5) Associazione parametri alla query
 *  6) Esecuzione query
 */

// 1) Verifica richiesta POST
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['user_id'])) {

    // 2) Recupero ID dalla sessione
    $user_id = $_SESSION['user_id'];
    
    // 3) Recupera e pulisci form
    $via = trim($_POST['via']);
    $numeroCivico = isset($_POST['numeroCivico']) ? trim($_POST['numeroCivico']) : NULL;
    $citta = trim($_POST['citta']);
    $cap = trim($_POST['cap']);

    // 4) Prepara query inserimento
    $sql = "INSERT INTO indirizzi (idUtente, via, numeroCivico, citta, cap) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    
    // 5) Associazione parametri alla query
    $stmt->bind_param("issss", $user_id, $via, $numeroCivico, $citta, $cap);

    // 6) Esecuzione query
    if ($stmt->execute()) {
        header("Location: ../dashboard.php?status=address_saved");
        exit();
    } else {
        echo "Errore durante il salvataggio: " . $stmt->error;
    }
}
?>