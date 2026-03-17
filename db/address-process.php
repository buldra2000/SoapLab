<?php
session_start();
require_once 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $via = $_POST['via'];
    $citta = $_POST['citta'];
    $cap = $_POST['cap'];
    $provincia = $_POST['provincia'];

    $sql = "INSERT INTO indirizzi (utente_id, via, città, cap, provincia) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("issss", $user_id, $via, $citta, $cap, $provincia);

    if ($stmt->execute()) {
        header("Location: ../dashboard.php?status=address_saved");
    } else {
        echo "Errore durante il salvataggio.";
    }
}