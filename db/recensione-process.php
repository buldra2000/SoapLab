<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

require_once 'db.php';

/**
 * Processo recensione.
 *  1) Verifica richiesta POST
 *  2) Preparazione dati ricevuti (cast e trim)
 *  3) Query SQL
 *  4) Associazione valori a ?
 */

// 1) Verifica richiesta POST
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['user_id'])) {
    
    // 2) Preparazione dati ricevuti (cast e trim)
    $idMittente = (int)$_SESSION['user_id'];
    $idAcquisto = (int)$_POST['idAcquisto'];
    $idDestinatario = (int)$_POST['idDestinatario'];
    $voto = (int)$_POST['voto'];
    $commento = trim($_POST['commento']);

    // 3) Query SQL
    $sql = "INSERT INTO recensioni (idAcquisto, idMittente, idDestinatario, voto, commento) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        die("Errore nella preparazione della query: " . $conn->error);
    }

    // 4) Associazione valori a ?
    $stmt->bind_param("iiiis", $idAcquisto, $idMittente, $idDestinatario, $voto, $commento);

    if ($stmt->execute()) {
        header("Location: ../dashboard.php?msg=recensione_inviata");
        exit();
    } else {
        die("Errore durante il salvataggio: " . $stmt->error);
    }

} else {
    header("Location: ../dashboard.php");
    exit();
}
?>