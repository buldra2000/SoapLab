<?php
session_start();
require_once 'db/db.php';

// Se l'utente non è loggato o mancano i parametri, lo rimandiamo indietro
if (!isset($_SESSION['user_id']) || !isset($_GET['idAcq']) || !isset($_GET['idVend'])) {
    header("Location: dashboard.php");
    exit();
}

$idAcquisto = (int)$_GET['idAcq'];
$idVenditore = (int)$_GET['idVend'];
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lascia una Recensione - SoapLab</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background-color: #f4f7f6; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .form-card { background: white; padding: 40px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); width: 100%; max-width: 450px; }
        h2 { margin-top: 0; color: #333; }
        p { color: #666; margin-bottom: 20px; }
        label { display: block; font-weight: bold; margin-bottom: 8px; color: #444; }
        select, textarea { width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 6px; box-sizing: border-box; margin-bottom: 20px; font-family: inherit; }
        button { background: #28a745; color: white; border: none; padding: 15px; width: 100%; border-radius: 6px; cursor: pointer; font-weight: bold; font-size: 16px; transition: 0.3s; }
        button:hover { background: #218838; }
        .btn-back { display: block; text-align: center; margin-top: 15px; color: #777; text-decoration: none; font-size: 14px; }
        .btn-back:hover { color: #333; }
    </style>
</head>
<body>

    <div class="form-card">
        <h2>La tua opinione conta! ⭐</h2>
        <p>Valuta il venditore e l'esperienza di acquisto.</p>
        
        <form action="db/recensione-process.php" method="POST">
            <input type="hidden" name="idAcquisto" value="<?php echo $idAcquisto; ?>">
            <input type="hidden" name="idDestinatario" value="<?php echo $idVenditore; ?>">
            
            <label>Voto (da 1 a 5 stelle):</label>
            <select name="voto" required>
                <option value="5">⭐⭐⭐⭐⭐ - Eccellente</option>
                <option value="4">⭐⭐⭐⭐ - Molto Buono</option>
                <option value="3">⭐⭐⭐ - Soddisfacente</option>
                <option value="2">⭐⭐ - Scarso</option>
                <option value="1">⭐ - Pessimo</option>
            </select>

            <label>Commento (facoltativo):</label>
            <textarea name="commento" rows="4" placeholder="Scrivi qui cosa ne pensi del sapone e della spedizione..."></textarea>

            <button type="submit">Invia Recensione</button>
        </form>
        
        <a href="dashboard.php" class="btn-back">Annulla e torna alla Dashboard</a>
    </div>

</body>
</html>