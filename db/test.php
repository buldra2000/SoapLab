<?php
include 'db.php';

if ($conn) {
    echo "<h1 style='color: green;'>Successo!</h1>";
    echo "<p>Il tuo sito SoapLab è collegato correttamente al database <b>" . $dbname . "</b>.</p>";
    echo "Versione server MySQL: " . $conn->server_info;
} else {
    echo "<h1 style='color: red;'>Errore!</h1>";
    echo "La connessione non è attiva.";
}
?>
