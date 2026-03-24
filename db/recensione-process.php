<?php
// Attiviamo gli errori per capire se qualcosa va storto
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

// Essendo già dentro la cartella 'db', includiamo db.php direttamente
require_once 'db.php';

// Controlliamo che i dati arrivino dal form e che l'utente sia loggato
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['user_id'])) {
    
    // 1. Raccogliamo tutti i dati
    $idMittente = $_SESSION['user_id'];
    $idAcquisto = (int)$_POST['idAcquisto'];
    $idDestinatario = (int)$_POST['idDestinatario'];
    $voto = (int)$_POST['voto'];
    $commento = trim($_POST['commento']);

    // 2. Prepariamo la query per la tabella 'recensioni'
    $sql = "INSERT INTO recensioni (idAcquisto, idMittente, idDestinatario, voto, commento) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        die("Errore nella preparazione della query: " . $conn->error);
    }

    // 3. Colleghiamo i parametri: 4 numeri interi (i) e 1 stringa (s) = "iiiis"
    $stmt->bind_param("iiiis", $idAcquisto, $idMittente, $idDestinatario, $voto, $commento);

    // 4. Eseguiamo e reindirizziamo
    if ($stmt->execute()) {
        header("Location: ../dashboard.php?msg=recensione_inviata");
        exit();
    } else {
        die("Errore durante il salvataggio: " . $stmt->error);
    }

} else {
    // Se qualcuno prova ad accedere a questa pagina senza usare il form
    header("Location: ../dashboard.php");
    exit();
}
?>