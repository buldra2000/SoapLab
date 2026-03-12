<?php
$host = "localhost";
$user = "root";      // Su XAMPP l'utente è sempre root
$pass = "";          // Su XAMPP la password è vuota
$dbname = "SoapLabDB";

$conn = new mysqli($host, $user, $pass, $dbname);

// Controllo connessione
if ($conn->connect_error) {
    die("Connessione fallita: " . $conn->connect_error);
} 
// Se vuoi testare se funziona, decommenta la riga sotto:
// echo "Connesso con successo al database SoapLabDB!";
?>