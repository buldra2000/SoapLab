<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
require_once 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    
    $via = $_POST['via'];
    $numeroCivico = $_POST['numeroCivico'] ?? NULL;
    $citta = $_POST['citta'];
    $cap = $_POST['cap'];

    $sql = "INSERT INTO indirizzi (idUtente, via, numeroCivico, citta, cap) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    
    $stmt->bind_param("issss", $user_id, $via, $numeroCivico, $citta, $cap);

    if ($stmt->execute()) {
        header("Location: ../dashboard.php?status=address_saved");
        exit();
    } else {
        echo "Errore durante il salvataggio: " . $stmt->error;
    }
}
?>