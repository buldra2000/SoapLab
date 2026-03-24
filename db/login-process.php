<?php
session_start();
require_once 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // --- 1. CONTROLLO TABELLA UTENTI ---
    $sql_u = "SELECT idUtente, password, statoVendita FROM utenti WHERE email = ?";
    $stmt_u = $conn->prepare($sql_u);
    
    if (!$stmt_u) {
        die("Errore di preparazione: " . $conn->error);
    }

    $stmt_u->bind_param("s", $email);
    $stmt_u->execute();
    $res_u = $stmt_u->get_result();

    if ($user = $res_u->fetch_assoc()) {
        if (password_verify($password, $user['password'])) {
            // (Opzionale) Controllo stato bloccato
            if ($user['statoVendita'] === 'bloccato') {
                // header("Location: ../login.html?error=blocked");
                // exit();
            }
            $_SESSION['user_id'] = $user['idUtente'];
            header("Location: ../dashboard.php");
            exit();
        } else {
            header("Location: ../login.html?error=wrongpassword");
            exit();
        }
    }

    // --- 2. CONTROLLO TABELLA AMMINISTRATORE ---
    // Se il codice arriva qui, l'email non era nella tabella utenti. Cerchiamo in amministratore.
    $sql_a = "SELECT idAdmin, password FROM amministratori WHERE email = ?";
    $stmt_a = $conn->prepare($sql_a);
    $stmt_a->bind_param("s", $email);
    $stmt_a->execute();
    $res_a = $stmt_a->get_result();

    if ($admin = $res_a->fetch_assoc()) {
        if (password_verify($password, $admin['password'])) {
            $_SESSION['admin_id'] = $admin['idAdmin'];
            header("Location: ../admin-dashboard.php");
            exit();
        } else {
            header("Location: ../login.html?error=wrongpassword");
            exit();
        }
    }

    // --- 3. NESSUN RISULTATO ---
    // L'email non esiste in nessuna delle due tabelle
    header("Location: ../login.html?error=usernotfound");
    exit();

} else {
    header("Location: ../login.html");
    exit();
}
?>