<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
require_once 'db.php'; // Assicurati che il percorso sia corretto

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 1. Recuperiamo i dati dal form (niente più username!)
    $nome = trim($_POST['name']);
    $cognome = trim($_POST['surname']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm-password'];

    // 2. Controllo base: le password coincidono?
    if ($password !== $confirm_password) {
        header("Location: ../registrazione.html?error=password_mismatch");
        exit();
    }

    // 3. Controllo se l'email esiste già
    $check_sql = "SELECT idUtente FROM utenti WHERE email = ?";
    $stmt_check = $conn->prepare($check_sql);
    $stmt_check->bind_param("s", $email);
    $stmt_check->execute();
    $stmt_check->store_result();

    if ($stmt_check->num_rows > 0) {
        // L'email è già registrata
        header("Location: ../registrazione.html?error=email_exists");
        exit();
    }
    $stmt_check->close();

    // 4. Criptiamo la password per sicurezza (Standard di settore)
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // 5. Inseriamo il nuovo utente nel database
    // Lo statoVendita sarà 'attivo' di default come impostato su phpMyAdmin
    $insert_sql = "INSERT INTO utenti (nome, cognome, email, password) VALUES (?, ?, ?, ?)";
    $stmt_insert = $conn->prepare($insert_sql);
    
    if (!$stmt_insert) {
        die("Errore di preparazione query: " . $conn->error);
    }

    $stmt_insert->bind_param("ssss", $nome, $cognome, $email, $hashed_password);

    if ($stmt_insert->execute()) {
        // Registrazione completata con successo, lo logghiamo in automatico
        $_SESSION['user_id'] = $stmt_insert->insert_id; // Prende l'idUtente appena creato
        header("Location: ../dashboard.php?msg=welcome");
        exit();
    } else {
        // Errore generico
        header("Location: ../registrazione.html?error=insert_failed");
        exit();
    }

    $stmt_insert->close();
} else {
    // Accesso diretto non consentito
    header("Location: ../registrazione.html");
    exit();
}
$conn->close();
?>