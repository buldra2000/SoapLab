<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}
?>
<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/aggiungi-indirizzo.css">
    <title>Aggiungi Indirizzo - SoapLab</title>
</head>

<body>
    <!--Form per indirizzi -->
    <form action="db/address-process.php" method="POST">
        <h3>Nuovo Indirizzo</h3>

        <input type="text" name="via" placeholder="Via (es. Via Roma)" required>
        <input type="text" name="numeroCivico" placeholder="Numero Civico (es. 12/B)" required>

        <input type="text" name="citta" placeholder="Città" required>

        <input type="text" name="cap" placeholder="CAP" maxlength="5" pattern="\d{5}"
            title="Inserisci un CAP valido (5 cifre)" required>

        <button type="submit">Salva Indirizzo</button>

        <a href="indirizzi.php" class="cancel-link">Annulla e torna indietro</a>
    </form>
</body>

</html>