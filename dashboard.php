<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once 'db/db.php'; 

if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

$user_id = $_SESSION['user_id'];

// Recupero dati utente
$sql = "SELECT nome, cognome, email, statoVendita FROM utenti WHERE idUtente = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($user = $result->fetch_assoc()) {
    // Dati caricati
} else {
    die("Errore: Utente non trovato.");
}
$stmt->close();

// --- 1. Calcolo Acquisti ---
$sql_acquisti = "SELECT COUNT(*) AS num_acquisti FROM acquisti WHERE idUtente = ?";
$stmt_acq = $conn->prepare($sql_acquisti);
if ($stmt_acq) {
    $stmt_acq->bind_param("i", $user_id);
    $stmt_acq->execute();
    $res_acq = $stmt_acq->get_result()->fetch_assoc();
    $acquisti = $res_acq['num_acquisti'] ?? 0;
    $stmt_acq->close();
} else {
    $acquisti = 0;
}

// --- 2. Calcolo Vendite ---
$sql_vendite = "SELECT COUNT(*) AS num_vendite 
                FROM acquisti a 
                JOIN inserzioni i ON a.idInserzione = i.idInserzione 
                WHERE i.idUtente = ?";
$stmt_ven = $conn->prepare($sql_vendite);
if ($stmt_ven) {
    $stmt_ven->bind_param("i", $user_id);
    $stmt_ven->execute();
    $res_ven = $stmt_ven->get_result()->fetch_assoc();
    $vendite = $res_ven['num_vendite'] ?? 0;
    $stmt_ven->close();
} else {
    $vendite = 0;
}

// --- 3. Calcolo Rating ---
// N.B: Ipotizzo che la Foreign Key in 'recensioni' sia 'idVenditore'
// --- 3. Calcolo Rating ---
// Aggiornato con il nome colonna corretto: idDestinatario
$sql_rating = "SELECT AVG(voto) AS media_voti FROM recensioni WHERE idDestinatario = ?"; 
$stmt_rat = $conn->prepare($sql_rating);
if ($stmt_rat) {
    $stmt_rat->bind_param("i", $user_id);
    $stmt_rat->execute();
    $res_rat = $stmt_rat->get_result()->fetch_assoc();
    $rating_db = $res_rat['media_voti'];
    $rating = ($rating_db !== null) ? round($rating_db, 1) . " / 5" : "Nessuna";
    $stmt_rat->close();
} else {
    $rating = "Nessuna";
}


// --- 4. Recupero Storico Ordini ---
$ordini = [];
$sql_ordini = "SELECT a.idAcquisto, a.dataAcquisto, i.titolo, i.prezzoTotale, 
                      i.idUtente AS idVenditore, s.stato, s.tracking AS numeroTracking,
                      r.idRecensione
               FROM acquisti a
               JOIN inserzioni i ON a.idInserzione = i.idInserzione
               LEFT JOIN spedizioni s ON a.idAcquisto = s.idAcquisto
               LEFT JOIN recensioni r ON a.idAcquisto = r.idAcquisto
               WHERE a.idUtente = ?
               ORDER BY a.dataAcquisto DESC";

$stmt_ord = $conn->prepare($sql_ordini);
if ($stmt_ord) {
    $stmt_ord->bind_param("i", $user_id);
    $stmt_ord->execute();
    $ordini = $stmt_ord->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt_ord->close();
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - SoapLab</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; margin: 0; background-color: #f4f7f6; color: #333; }

        header {
            background-color: #ffffff;
            padding: 10px 40px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        h1 { margin: 0; font-size: 24px; color: #333; flex-grow: 1; }

        .dropdown { position: relative; display: inline-block; }

        .user-icon {
            font-size: 20px; cursor: pointer; padding: 8px; background: #f0f0f0;
            border-radius: 50%; width: 35px; height: 35px; display: flex;
            align-items: center; justify-content: center; transition: 0.3s;
        }
        .user-icon:hover { background: #e0e0e0; }

        .dropdown-content {
            display: none; position: absolute; right: 0; background-color: #fff;
            min-width: 200px; box-shadow: 0px 8px 16px rgba(0,0,0,0.1);
            z-index: 100; border-radius: 8px; overflow: hidden; border: 1px solid #eee;
        }

        .dropdown-content a {
            color: #444; padding: 12px 16px; text-decoration: none;
            display: block; font-size: 14px; transition: 0.2s;
        }

        .dropdown-content a:hover { background-color: #f8f9fa; color: #28a745; }

        .dropdown:hover .dropdown-content { display: block; }

        .container { padding: 40px; max-width: 1000px; margin: auto; }
        .grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 40px; }
        .card { background: white; padding: 25px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); text-align: center; }
        .card .value { font-size: 32px; font-weight: bold; color: #28a745; margin: 10px 0; }
        
        .account-info { background: white; padding: 30px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
        .info-row { display: flex; justify-content: space-between; padding: 12px 0; border-bottom: 1px solid #f9f9f9; }
        .label { font-weight: bold; color: #777; }

        .btn { padding: 10px 20px; border-radius: 6px; text-decoration: none; font-weight: bold; cursor: pointer; display: inline-block; }
        .btn-logout { background: #6c757d; color: white; margin-top: 20px; }
        .btn-delete { background: #fee; color: #dc3545; border: none; margin-left: 10px; }

        .status-badge { padding: 4px 10px; border-radius: 20px; font-size: 12px; font-weight: bold; }
        .status-attivo { background: #e8f5e9; color: #2e7d32; }
        .status-bloccato { background: #ffebee; color: #c62828; }

        /* Stile per i messaggi di notifica */
        .alert { padding: 15px; text-align: center; font-weight: bold; }
        .alert-success { background-color: #d4edda; color: #155724; border-bottom: 1px solid #c3e6cb; }
    </style>
</head>
<body>

    <header>
        <h1>SoapLab</h1>
        <div class="dropdown">
            <div class="user-icon">👤</div>
            <div class="dropdown-content">
                <a href="index.php" style="text-align: center; background: #f8f9fa;">
                    <strong><?php echo htmlspecialchars($user['nome'] . ' ' . $user['cognome']); ?></strong>
                </a>
                
                <?php if ($user['statoVendita'] !== 'bloccato'): ?>
                    <a href="vendita-sapone.php" style="color: #28a745; font-weight: bold; border-bottom: 1px solid #eee;">
                        🧼 Vendi un sapone
                    </a>
                <?php endif; ?>

                <a href="dashboard.php">La mia dashboard</a>
                <a href="indirizzi.php">I miei indirizzi</a>
                <a href="settings.php">Impostazioni</a>
                <a href="db/logout-process.php" style="color: #dc3545; border-top: 1px solid #eee;">Logout</a>
            </div>
        </div>
    </header>

    <?php if (isset($_GET['msg'])): ?>
        <?php if ($_GET['msg'] === 'sapone_aggiunto'): ?>
            <div class="alert alert-success">✅ Inserzione pubblicata con successo!</div>
        <?php elseif ($_GET['msg'] === 'welcome'): ?>
            <div class="alert alert-success">🎉 Registrazione completata! Benvenuto in SoapLab.</div>
        <?php endif; ?>
    <?php endif; ?>

    <div class="container">
        <h2>Benvenuto, <?php echo htmlspecialchars($user['nome']); ?>!</h2>

        <div class="grid">
            <div class="card">
                <h3>Vendite</h3>
                <div class="value"><?php echo $vendite; ?></div>
                <p>Prodotti venduti</p>
            </div>
            <div class="card">
                <h3>Acquisti</h3>
                <div class="value"><?php echo $acquisti; ?></div>
                <p>Ordini effettuati</p>
            </div>
            
            <div class="card">
                <h3>Valutazione</h3>
                <div class="value"><?php echo htmlspecialchars($rating); ?></div>
                <p>Media Recensioni</p>
            </div>

            <div class="card">
                <h3>Stato Venditore</h3>
                <div style="margin-top: 15px;">
                    <span class="status-badge <?php echo ($user['statoVendita'] === 'bloccato') ? 'status-bloccato' : 'status-attivo'; ?>">
                        <?php echo strtoupper($user['statoVendita'] ?? 'ATTIVO'); ?>
                    </span>
                </div>
            </div>
        </div>

        <div class="account-info">
            <h3>I tuoi dati</h3>
            <div class="info-row">
                <span class="label">Email:</span>
                <span><?php echo htmlspecialchars($user['email']); ?></span>
            </div>
            <div class="info-row">
                <span class="label">Nome Completo:</span>
                <span><?php echo htmlspecialchars($user['nome'] . " " . $user['cognome']); ?></span>
            </div>
            <style>
                .orders-section { background: white; padding: 30px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); margin-top: 30px; }
.orders-table { width: 100%; border-collapse: collapse; margin-top: 15px; }
.orders-table th, .orders-table td { text-align: left; padding: 12px; border-bottom: 1px solid #eee; }
.orders-table th { color: #777; font-size: 13px; text-transform: uppercase; }
.status-pill { padding: 4px 8px; border-radius: 4px; font-size: 11px; font-weight: bold; background: #eee; }
.status-lavorazione { background: #fff3e0; color: #ef6c00; }
.status-spedito { background: #e3f2fd; color: #1565c0; }
                </style>

            <div class="orders-section">
    <h3>I miei acquisti</h3>
    <?php if (count($ordini) > 0): ?>
        <table class="orders-table">
            <thead>
                <tr>
                    <th>Data</th>
                    <th>Prodotto</th>
                    <th>Prezzo</th>
                    <th>Stato Spedizione</th>
                </tr>
            </thead>
            <tbody>
    <?php foreach ($ordini as $ordine): ?>
        <tr>
            <td><?php echo date('d/m/Y', strtotime($ordine['dataAcquisto'])); ?></td>
            <td><strong><?php echo htmlspecialchars($ordine['titolo']); ?></strong></td>
            <td>€<?php echo number_format($ordine['prezzoTotale'], 2); ?></td>
            <td>
                <?php $stato_pulito = strtolower(trim($ordine['stato'])); ?>
                <span class="status-pill <?php 
                    if ($stato_pulito == 'in lavorazione') echo 'status-lavorazione';
                    elseif ($stato_pulito == 'spedito') echo 'status-spedito';
                    else echo 'status-attivo'; 
                ?>">
                    <?php echo strtoupper($ordine['stato'] ?? 'PENDENTE'); ?>
                </span>

                <?php if ($stato_pulito === 'consegnato' && empty($ordine['idRecensione'])): ?>
                    <br>
                    <a href="lascia-recensione.php?idAcq=<?php echo $ordine['idAcquisto']; ?>&idVend=<?php echo $ordine['idVenditore']; ?>" 
                       style="display: inline-block; margin-top: 5px; color: #28a745; font-weight: bold; text-decoration: none; font-size: 12px;">
                       ⭐ Recensisci
                    </a>
                <?php elseif (!empty($ordine['idRecensione'])): ?>
                    <br><small style="color: #888;">Recensione inviata ✅</small>
                <?php elseif ($stato_pulito !== 'consegnato'): ?>
                    <br><small style="color: #999;">Disponibile dopo consegna</small>
                <?php endif; ?>

                <?php if (!empty($ordine['numeroTracking'])): ?>
                    <br><small style="color: #888;">Tracking: <?php echo htmlspecialchars($ordine['numeroTracking']); ?></small>
                <?php endif; ?>
            </td>
        </tr>
    <?php endforeach; ?>
</tbody>
        </table>
    <?php else: ?>
        <p style="color: #888; font-style: italic;">Non hai ancora effettuato acquisti.</p>
    <?php endif; ?>
</div>
            
            <div style="text-align: center; margin-top: 30px;">
                <a href="db/logout-process.php" class="btn btn-logout">Disconnetti</a>
                <form action="db/delete-account.php" method="POST" style="display: inline;">
                    <button type="submit" class="btn btn-delete" onclick="return confirm('Eliminare l\'account?');">Elimina Account</button>
                </form>
            </div>
        </div>
    </div>

</body>
</html>