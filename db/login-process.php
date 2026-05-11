<?php
session_start();
require_once 'db.php';

/**
 * Processo login utente.
 *  1) Verifica richiesta metodo POST
 *  2) Recupero e pulizia form
 *  3) Ricerca email in 'utenti'
 *  4) Verifica password e stato
 *  5) Ricerca email in 'amministratori'
 *  6) Fallimento autenticazione
 */

// 1) Verifica richiesta metodo POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // 2) Recupero e pulizia form
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // 3) Ricerca email in 'utenti'
    $sql_u = "SELECT idUtente, password, statoVendita FROM utenti WHERE email = ?";
    $stmt_u = $conn->prepare($sql_u);
    
    if (!$stmt_u) {
        die("Errore di preparazione: " . $conn->error);
    }

    $stmt_u->bind_param("s", $email);
    $stmt_u->execute();
    $res_u = $stmt_u->get_result();

    // 4) Verifica password e stato
    if ($user = $res_u->fetch_assoc()) {
        if (password_verify($password, $user['password'])) {
            if ($user['statoVendita'] === 'bloccato') {
                header("Location: ../login.html?error=blocked");
                exit();
            }
            $_SESSION['user_id'] = $user['idUtente'];
            header("Location: ../dashboard.php");
            exit();
        } else {
            header("Location: ../login.html?error=wrongpassword");
            exit();
        }
    }

    // 5) Ricerca email in 'amministratori'
    $sql_a = "SELECT idAdmin, password FROM amministratori WHERE email = ?";
    $stmt_a = $conn->prepare($sql_a);
    $stmt_a->bind_param("s", $email);
    $stmt_a->execute();
    $res_a = $stmt_a->get_result();

    if ($admin = $res_a->fetch_assoc()) {
        if (password_verify($password, $admin['password'])) {
            $_SESSION['admin_id'] = $admin['idAdmin'];
            header("Location: ../admin/admin-dashboard.php");
            exit();
        } else {
            header("Location: ../login.html?error=wrongpassword");
            exit();
        }
    }

    // 6) Fallimento autenticazione
    header("Location: ../login.html?error=usernotfound");
    exit();

} else {
    header("Location: ../login.html");
    exit();
}
?>