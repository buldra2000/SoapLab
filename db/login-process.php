<?php
session_start(); //Log utente per le pagine
require_once 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username_input = $_POST['username'];
    $password_input = $_POST['password'];

    // 1. Cerchiamo l'utente nel database tramite username
    $sql = "SELECT id, username, password FROM utenti WHERE username = ?";
    $stmt = $conn->prepare($sql);
    
    if ($stmt) {
        $stmt->bind_param("s", $username_input);
        $stmt->execute();
        $result = $stmt->get_result();

        // 2. Verifichiamo se l'utente esiste
        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();

            // 3. Confrontiamo la password inserita con quella criptata nel DB
            if (password_verify($password_input, $user['password'])) {
                // Password corretta! Salviamo i dati in sessione
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];

                echo "Login effettuato! Benvenuto, " . $user['username'];
                header("Location: ../index.php");
            } else {
                echo "Password errata.";
            }
        } else {
            echo "Username non trovato.";
        }
        $stmt->close();
    }
    $conn->close();
}
?>