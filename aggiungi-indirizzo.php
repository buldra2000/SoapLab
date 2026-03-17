<?php
session_start();
if (!isset($_SESSION['user_id'])) { header("Location: login.html"); exit(); }
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Aggiungi Indirizzo - SoapLab</title>
    <style>
        body { font-family: sans-serif; background: #f4f7f6; display: flex; justify-content: center; padding-top: 50px; }
        form { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); width: 300px; }
        input { width: 100%; padding: 8px; margin: 10px 0; box-sizing: border-box; }
        button { width: 100%; padding: 10px; background: #28a745; color: white; border: none; cursor: pointer; }
    </style>
</head>
<body>
    <form action="db/address-process.php" method="POST">
        <h3>Nuovo Indirizzo</h3>
        <input type="text" name="via" placeholder="Via e Numero Civico" required>
        <input type="text" name="citta" placeholder="Città" required>
        <input type="text" name="cap" placeholder="CAP" maxlength="5" required>
        <input type="text" name="provincia" placeholder="Provincia (ES: MI)" maxlength="2" required>
        <button type="submit">Salva Indirizzo</button>
        <br><br>
        <a href="dashboard.php">Annulla</a>
    </form>
</body>
</html>