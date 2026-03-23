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
        body { font-family: sans-serif; background: #f4f7f6; display: flex; justify-content: center; padding-top: 50px; margin: 0; }
        form { background: white; padding: 25px; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); width: 320px; }
        h3 { text-align: center; color: #333; margin-top: 0; }
        input { width: 100%; padding: 10px; margin: 8px 0 15px 0; box-sizing: border-box; border: 1px solid #ddd; border-radius: 5px; }
        button { width: 100%; padding: 12px; background: #28a745; color: white; border: none; cursor: pointer; border-radius: 5px; font-weight: bold; transition: 0.3s; }
        button:hover { background: #218838; }
        .cancel-link { display: block; text-align: center; color: #888; text-decoration: none; margin-top: 15px; font-size: 14px; }
        .cancel-link:hover { color: #555; }
    </style>
</head>
<body>
    <form action="db/address-process.php" method="POST">
        <h3>Nuovo Indirizzo</h3>
        
        <input type="text" name="via" placeholder="Via (es. Via Roma)" required>
        <input type="text" name="numeroCivico" placeholder="Numero Civico (es. 12/B)" required>
        
        <input type="text" name="citta" placeholder="Città" required>
        
        <input type="text" name="cap" placeholder="CAP" maxlength="5" required>
        
        <button type="submit">Salva Indirizzo</button>
        
        <a href="indirizzi.php" class="cancel-link">Annulla e torna indietro</a>
    </form>
</body>
</html>