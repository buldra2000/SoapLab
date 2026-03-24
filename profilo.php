<?php
session_start();
require_once 'db/db.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$idProfilo = (int)$_GET['id'];

// 1. Recupero dati base dell'utente (Nome, Cognome, Email, Stato) [cite: 114-118, 173-177]
$sql_user = "SELECT nome, cognome, email, statoVendita FROM utenti WHERE idUtente = ?";
$stmt_user = $conn->prepare($sql_user);
$stmt_user->bind_param("i", $idProfilo);
$stmt_user->execute();
$user = $stmt_user->get_result()->fetch_assoc();

if (!$user) { die("Utente non trovato."); }

// 2. Conteggio VENDITE: quante volte le sue inserzioni sono state acquistate [cite: 137-140, 189]
$sql_vendite = "SELECT COUNT(*) as tot FROM acquisti a JOIN inserzioni i ON a.idInserzione = i.idInserzione WHERE i.idUtente = ?";
$stmt_v = $conn->prepare($sql_vendite);
$stmt_v->bind_param("i", $idProfilo);
$stmt_v->execute();
$tot_vendite = $stmt_v->get_result()->fetch_assoc()['tot'];

// 3. Conteggio ACQUISTI: quanti acquisti ha effettuato l'utente [cite: 187-189]
$sql_acquisti = "SELECT COUNT(*) as tot FROM acquisti WHERE idUtente = ?";
$stmt_a = $conn->prepare($sql_acquisti);
$stmt_a->bind_param("i", $idProfilo);
$stmt_a->execute();
$tot_acquisti = $stmt_a->get_result()->fetch_assoc()['tot'];

// 4. Media Recensioni e numero feedback ricevuti [cite: 26, 64, 233]
$sql_feed = "SELECT AVG(voto) as media, COUNT(*) as num FROM recensioni WHERE idDestinatario = ?";
$stmt_f = $conn->prepare($sql_feed);
$stmt_f->bind_param("i", $idProfilo);
$stmt_f->execute();
$feedback = $stmt_f->get_result()->fetch_assoc();

// 5. Elenco delle Inserzioni pubblicate dall'utente [cite: 41-43, 112]
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
    <title>Profilo di <?php echo htmlspecialchars($user['nome']); ?> - SoapLab</title>
    <style>
        body { font-family: 'Inter', sans-serif; background: #f9fafb; margin: 0; color: #1f2937; }
        .container { max-width: 900px; margin: 40px auto; padding: 20px; }
        .profile-card { background: white; padding: 30px; border-radius: 16px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); margin-bottom: 30px; }
        
        /* Badge per lo stato vendita [cite: 30-31, 117, 262] */
        .status-badge { display: inline-block; padding: 6px 14px; border-radius: 20px; font-size: 13px; font-weight: 600; margin-top: 10px; }
        .status-attivo { background: #d1fae5; color: #065f46; }
        .status-bloccato { background: #fee2e2; color: #991b1b; }

        /* Griglia Statistiche [cite: 65, 67] */
        .stats-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; margin-top: 25px; }
        .stat-box { background: #f3f4f6; padding: 20px; border-radius: 12px; text-align: center; border: 1px solid #e5e7eb; }
        .stat-val { display: block; font-size: 28px; font-weight: bold; color: #10b981; }
        .stat-label { font-size: 12px; color: #6b7280; text-transform: uppercase; font-weight: 600; }

        /* Griglia Inserzioni [cite: 43, 137, 223] */
        .ins-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-top: 20px; }
        .ins-item { background: white; padding: 20px; border-radius: 12px; border: 1px solid #e5e7eb; text-decoration: none; color: inherit; transition: 0.2s; }
        .ins-item:hover { transform: translateY(-3px); box-shadow: 0 10px 15px rgba(0,0,0,0.05); border-color: #10b981; }
    </style>
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
                <div style="font-size: 14px; color: #9ca3af;"><?php echo $feedback['num']; [cite_start]?> recensioni ricevute [cite: 64]</div>
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

    [cite_start]<h2 style="font-size: 20px; margin-bottom: 15px;">Catalogo Inserzioni [cite: 11]</h2>
    <div class="ins-grid">
        <?php if ($inserzioni->num_rows > 0): ?>
            <?php while($ins = $inserzioni->fetch_assoc()): ?>
                <a href="inserzione.php?id=<?php echo $ins['idInserzione']; ?>" class="ins-item">
                    <h3 style="margin: 0 0 10px 0; font-size: 18px; color: #111827;"><?php echo htmlspecialchars($ins['titolo']); ?></h3>
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <span style="color: #059669; font-weight: 700; font-size: 18px;">€<?php echo number_format($ins['prezzoTotale'], 2); ?></span>
                        <span style="font-size: 13px; color: #6b7280;"><?php echo $ins['pesoComplessivo']; [cite_start]?>g complessivi [cite: 15, 43]</span>
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