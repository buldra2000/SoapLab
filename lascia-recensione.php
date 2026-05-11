<?php
session_start();
require_once 'db/db.php';

if (!isset($_SESSION['user_id']) || !isset($_GET['idAcq']) || !isset($_GET['idVend'])) {
    header("Location: dashboard.php");
    exit();
}

$idAcquisto = (int) $_GET['idAcq'];
$idVenditore = (int) $_GET['idVend'];
?>

<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/lascia-recensione.css">
    <title>Lascia una Recensione - SoapLab</title>
</head>

<body>

    <div class="form-card">
        <h2>La tua opinione conta!</h2>
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
            <textarea name="commento" rows="4"
                placeholder="Scrivi qui cosa ne pensi del sapone e della spedizione..."></textarea>

            <button type="submit">Invia Recensione</button>
        </form>

        <a href="dashboard.php" class="btn-back">Annulla e torna alla Dashboard</a>
    </div>

</body>

</html>