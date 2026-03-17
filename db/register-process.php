<?php
require_once 'db.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recupero i dati usando i 'name' dell'HTML
    $username = $_POST['username'];
    $nome     = $_POST['name'];
    $cognome  = $_POST['surname'];
    $email    = $_POST['email'];
    $pass     = $_POST['password'];
    $conf_pass = $_POST['confirm-password'];

    // Controllo password
    if ($pass !== $conf_pass) {
        die("Le password non coincidono!");
    }

    // Criptazione
    $password_hash = password_hash($pass, PASSWORD_DEFAULT);

    // Query SQL - Assicurati che l'ordine delle colonne (nome, cognome, ecc.) 
    // sia quello che hai su phpMyAdmin
    $sql = "INSERT INTO utenti (username, nome, cognome, email, password) VALUES (?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($sql);

    if ($stmt) {

        $stmt->bind_param("sssss", $username, $nome, $cognome, $email, $password_hash);

        if ($stmt->execute()) {
            // Reindirizza l'utente a login.html
            header("Location: ../login.html?status=success");
            exit(); // Blocca l'esecuzione dello script dopo il reindirizzamento
        } else {
            echo "Errore: Username o Email già presenti nel sistema.";
        }

        $stmt->close();

    }
    $conn->close();
}
?>