<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
require_once 'db.php';

/**
 * Processo registrazione utente.
 * 1) Verifica richiesta metodo POST
 * 2) Recupero e pulizia form
 * 3) Controllo password = conferma password
 * 4) Controllo email univoca in 'utenti'
 * 5) Hash password & Prepared Statements
 * 6) Reindirizzamenti
 */

// 1) Verifica richiesta metodo POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // 2) Recupero e pulizia form
    $nome = trim($_POST['name']);
    $cognome = trim($_POST['surname']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm-password']);

    // 3) Controllo password = conferma password
    if ($password !== $confirm_password) {
        header("Location: ../registrazione.html?error=password_mismatch");
        exit();
    }

    // 4) Controllo email univoca in 'utenti'
    $check_sql = "SELECT idUtente FROM utenti WHERE email = ?";
    $stmt_check = $conn->prepare($check_sql);
    $stmt_check->bind_param("s", $email);
    $stmt_check->execute();
    $stmt_check->store_result();

    if ($stmt_check->num_rows > 0) {
        header("Location: ../registrazione.html?error=email_exists");
        exit();
    }
    $stmt_check->close();

    // 5) Hash password & Prepared Statements
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $insert_sql = "INSERT INTO utenti (nome, cognome, email, password) VALUES (?, ?, ?, ?)";
    $stmt_insert = $conn->prepare($insert_sql);
    
    if (!$stmt_insert) {
        die("Errore di preparazione query: " . $conn->error);
    }

    $stmt_insert->bind_param("ssss", $nome, $cognome, $email, $hashed_password);

    //6) Reindirizzamenti
    if ($stmt_insert->execute()) {
        $_SESSION['user_id'] = $stmt_insert->insert_id;
        header("Location: ../dashboard.php?msg=welcome");
        exit();
    } else {
        header("Location: ../registrazione.html?error=insert_failed");
        exit();
    }

    $stmt_insert->close();
} else {
    header("Location: ../registrazione.html");
    exit();
}
$conn->close();
?>