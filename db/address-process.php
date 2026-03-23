<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
require_once 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    
    // Recuperiamo i dati dal form (assicurati che i 'name' nell'HTML corrispondano)
    $via = $_POST['via'];
    $numeroCivico = $_POST['numeroCivico'] ?? NULL; // Aggiunto perché presente nel DB
    $citta = $_POST['citta'];
    $cap = $_POST['cap'];

    // Query corretta basata sulla tua struttura reale
    $sql = "INSERT INTO indirizzi (idUtente, via, numeroCivico, citta, cap) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    
    // Bind dei parametri: "issss" -> integer, string, string, string, string
    $stmt->bind_param("issss", $user_id, $via, $numeroCivico, $citta, $cap);

    if ($stmt->execute()) {
        header("Location: ../dashboard.php?status=address_saved");
        exit();
    } else {
        echo "Errore durante il salvataggio: " . $stmt->error;
    }
}
?>