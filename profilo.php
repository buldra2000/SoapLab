<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once 'db/db.php';

/**
 * Profilo utente.
 *  2) Controllo id
 *  3) RECUPERO DATI UTENTE: Ottiene le informazioni base del profilo.
 *  4) CONTEGGIO VENDITE: Join tra acquisti e inserzioni per contare quanti oggetti ha venduto questo utente.
 *  5) CONTEGGIO ACQUISTI: Conta quante volte l'utente ha comprato da altri.
 *  6) MEDIA FEEDBACK: Calcola il voto medio e il numero totale di recensioni ricevute.
 *  7) ELENCO INSERZIONI: Recupera tutte le inserzioni attive create da questo utente.
 */

// 2) Controllo id
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$idProfilo = (int) $_GET['id'];

// 3. RECUPERO DATI UTENTE: Ottiene le informazioni base del profilo.
$sql_user = "SELECT nome, cognome, email, statoVendita FROM utenti WHERE idUtente = ?";
$stmt_user = $conn->prepare($sql_user);
$stmt_user->bind_param("i", $idProfilo);
$stmt_user->execute();
$user = $stmt_user->get_result()->fetch_assoc();

if (!$user) {
    die("Utente non trovato.");
}

// 4. CONTEGGIO VENDITE: Join tra acquisti e inserzioni per contare quanti oggetti ha venduto questo utente.
$sql_vendite = "SELECT COUNT(*) as tot FROM acquisti a JOIN inserzioni i ON a.idInserzione = i.idInserzione WHERE i.idUtente = ?";
$stmt_v = $conn->prepare($sql_vendite);
$stmt_v->bind_param("i", $idProfilo);
$stmt_v->execute();
$tot_vendite = $stmt_v->get_result()->fetch_assoc()['tot'];

// 5. CONTEGGIO ACQUISTI: Conta quante volte l'utente ha comprato da altri.
$sql_acquisti = "SELECT COUNT(*) as tot FROM acquisti WHERE idUtente = ?";
$stmt_a = $conn->prepare($sql_acquisti);
$stmt_a->bind_param("i", $idProfilo);
$stmt_a->execute();
$tot_acquisti = $stmt_a->get_result()->fetch_assoc()['tot'];

// 6. MEDIA FEEDBACK: Calcola il voto medio e il numero totale di recensioni ricevute.
$sql_feed = "SELECT AVG(voto) as media, COUNT(*) as num FROM recensioni WHERE idDestinatario = ?";
$stmt_f = $conn->prepare($sql_feed);
$stmt_f->bind_param("i", $idProfilo);
$stmt_f->execute();
$feedback = $stmt_f->get_result()->fetch_assoc();

// 7. ELENCO INSERZIONI: Recupera tutte le inserzioni attive create da questo utente.
$sql_ins = "SELECT * FROM inserzioni WHERE idUtente = ? ORDER BY idInserzione DESC";
$stmt_ins = $conn->prepare($sql_ins);
$stmt_ins->bind_param("i", $idProfilo);
$stmt_ins->execute();
$inserzioni = $stmt_ins->get_result();
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/profilo.css">
    <title>Profilo di <?php echo htmlspecialchars($user['nome']); ?> - SoapLab</title>
</head>
<body>

    <div class="container">
        <header style="margin-bottom: 20px;">
            <a href="index.php" style="text-decoration: none; color: #10b981; font-weight: 600;">← Esplora Inserzioni</a>
        </header>

        <div class="profile-card">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <h1 style="margin: 0; font-size: 28px;"><?php echo htmlspecialchars($user['nome'] . " " . $user['cognome']); ?></h1>
                    <p style="color: #6b7280; margin: 5px 0;"><?php echo htmlspecialchars($user['email']); ?></p>
                    <span class="status-badge status-<?php echo strtolower($user['statoVendita']); ?>">
                        Stato Venditore: <?php echo ucfirst($user['statoVendita']); ?>
                    </span>
                </div>

                <div style="text-align: right;">
                    <div style="font-size: 36px; font-weight: bold;">⭐ <?php echo $feedback['media'] ? number_format($feedback['media'], 1) : "0.0"; ?></div>
                    <div style="font-size: 14px; color: #9ca3af;"><?php echo $feedback['num']; ?> recensioni ricevute</div>
                </div>
            </div>

            <div class="stats-grid">
                <div class="stat-box">
                    <span class="stat-val"><?php echo $tot_vendite; ?></span>
                    <span class="stat-label">Vendite Concluse</span>
                </div>
                <div class="stat-box">
                    <span class="stat-val"><?php echo $tot_acquisti; ?></span>
                    <span class="stat-label">Acquisti Effettuati</span>
                </div>
            </div>
        </div>

        <h2 style="font-size: 20px; margin-bottom: 15px;">Catalogo Inserzioni</h2>
        <div class="ins-grid">
            <?php if ($inserzioni->num_rows > 0): ?>
                <?php while ($ins = $inserzioni->fetch_assoc()): ?>
                    <a href="inserzione.php?id=<?php echo $ins['idInserzione']; ?>" class="ins-item">
                        <h3 style="margin: 0 0 10px 0; font-size: 18px; color: #111827;"><?php echo htmlspecialchars($ins['titolo']); ?></h3>
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span style="color: #059669; font-weight: 700; font-size: 18px;">€<?php echo number_format($ins['prezzoTotale'], 2); ?></span>
                            <span style="font-size: 13px; color: #6b7280;"><?php echo $ins['pesoComplessivo']; ?>g complessivi</span>
                        </div>
                    </a>
                <?php endwhile; ?>
            <?php else: ?>
                <p style="color: #9ca3af; grid-column: span 2; text-align: center; padding: 40px; background: #fff; border-radius: 12px; border: 1px dashed #d1d5db;">
                    Nessuna inserzione attiva per questo utente.
                </p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>