<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once 'db/db.php';

// Controllo sicurezza ADMIN
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.html");
    exit();
}

$messaggio = "";

// --- GESTIONE BLOCCO/SBLOCCO (MODIFICA_STATO_VENDITA) ---
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['azione'])) {
    $idTarget = (int)$_POST['id_utente'];
    
    if ($_POST['azione'] == 'blocca') {
        // L'admin blocca il venditore [cite: 105]
        $stmt = $conn->prepare("UPDATE utenti SET statoVendita = 'bloccato' WHERE idUtente = ?");
        $stmt->bind_param("i", $idTarget);
        if ($stmt->execute()) {
            $messaggio = "<div class='alert success'>Utente bloccato. Non potrà più vendere saponi.</div>";
        }
    } elseif ($_POST['azione'] == 'sblocca') {
        // L'admin sblocca il venditore [cite: 108]
        $stmt = $conn->prepare("UPDATE utenti SET statoVendita = 'attivo' WHERE idUtente = ?");
        $stmt->bind_param("i", $idTarget);
        if ($stmt->execute()) {
            $messaggio = "<div class='alert success'>Utente riabilitato alla vendita!</div>";
        }
    }
}

// --- RECUPERO DATI E CALCOLO RECENSIONI NEGATIVE ---
// Calcoliamo quante recensioni con voto <= 2 ha ricevuto ogni utente
$sql_utenti = "
    SELECT u.idUtente, u.nome, u.cognome, u.email, u.statoVendita,
           COUNT(r.idRecensione) AS recensioni_negative,
           AVG(r2.voto) AS media_totale
    FROM utenti u
    -- Join per contare SOLO le recensioni negative (1 o 2 stelle)
    LEFT JOIN recensioni r ON u.idUtente = r.idDestinatario AND r.voto <= 2
    -- Join per calcolare la media totale dell'utente
    LEFT JOIN recensioni r2 ON u.idUtente = r2.idDestinatario
    GROUP BY u.idUtente
    ORDER BY recensioni_negative DESC, u.nome ASC
";
$lista_utenti = $conn->query($sql_utenti);
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Moderazione Utenti - SoapLab Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root { --sidebar-bg: #111827; --sidebar-hover: #1F2937; --accent: #10B981; --bg-light: #F3F4F6; --text-main: #1F2937; --card-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05); }
        body { font-family: 'Inter', sans-serif; background-color: var(--bg-light); margin: 0; color: var(--text-main); display: flex; min-height: 100vh; }
        
        .sidebar { width: 260px; background: var(--sidebar-bg); color: #E5E7EB; display: flex; flex-direction: column; }
        .sidebar-header { padding: 30px 25px 20px 25px; border-bottom: 1px solid #374151; margin-bottom: 15px; }
        .sidebar-header h2 { margin: 0; color: white; font-size: 22px; font-weight: 700; }
        .sidebar-header span { color: var(--accent); }
        .sidebar a { display: flex; align-items: center; color: #D1D5DB; padding: 14px 25px; text-decoration: none; font-size: 15px; font-weight: 500; border-left: 3px solid transparent; transition: 0.2s; }
        .sidebar a:hover, .sidebar a.active { background: var(--sidebar-hover); color: white; border-left: 3px solid var(--accent); }
        .sidebar .logout { margin-top: auto; margin-bottom: 20px; border-top: 1px solid #374151; padding-top: 20px; color: #F87171; }
        
        .main-content { flex: 1; padding: 40px; overflow-y: auto; }
        .header-panel { background: white; padding: 25px 35px; border-radius: 12px; box-shadow: var(--card-shadow); margin-bottom: 30px; }
        .header-panel h1 { margin: 0; font-size: 24px; }
        
        .card { background: white; padding: 25px; border-radius: 12px; box-shadow: var(--card-shadow); }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; font-size: 14px; }
        th, td { padding: 15px; text-align: left; border-bottom: 1px solid #E5E7EB; }
        th { background-color: #F9FAFB; font-weight: 600; color: #6B7280; text-transform: uppercase; font-size: 12px; }
        
        .badge { padding: 4px 10px; border-radius: 20px; font-size: 12px; font-weight: bold; }
        .badge-attivo { background: #D1FAE5; color: #065F46; }
        .badge-bloccato { background: #FEE2E2; color: #991B1B; }
        .badge-warning { background: #FEF3C7; color: #92400E; }
        
        .btn { padding: 8px 12px; border: none; border-radius: 6px; font-weight: bold; cursor: pointer; font-size: 12px; transition: 0.2s; }
        .btn-blocca { background: #EF4444; color: white; }
        .btn-blocca:hover { background: #DC2626; }
        .btn-sblocca { background: #10B981; color: white; }
        .btn-sblocca:hover { background: #059669; }
        .btn-disabled { background: #E5E7EB; color: #9CA3AF; cursor: not-allowed; }

        .alert { padding: 12px 15px; border-radius: 6px; margin-bottom: 20px; font-weight: 500; }
        .alert.success { background: #D1FAE5; color: #065F46; border: 1px solid #A7F3D0; }
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="sidebar-header">
            <h2>Soap<span>Lab</span></h2>
            <div style="font-size: 12px; color: #9CA3AF; margin-top: 5px;">Admin Panel</div>
        </div>
        <nav>
            <a href="admin-dashboard.php">📊 Dashboard</a>
            <a href="admin-categorie.php">📁 Gestione Categorie</a>
            <a href="admin-ingredienti.php">🌿 Ingredienti e Benefici</a>
            <a href="admin-proprieta.php">✨ Proprietà</a>
            <a href="admin-utenti.php" class="active">👥 Moderazione Utenti</a>
        </nav>
        <a href="db/logout-process.php" class="logout">🚪 Disconnetti</a>
    </div>

    <div class="main-content">
        <div class="header-panel">
            <h1>Moderazione Utenti</h1>
            <p>Monitora i venditori. Secondo le regole di sistema, puoi bloccare le vendite agli utenti con <strong>più di 10 recensioni negative</strong>.</p>
        </div>

        <?php echo $messaggio; ?>

        <div class="card">
            <table>
                <thead>
                    <tr>
                        <th>Venditore</th>
                        <th>Email</th>
                        <th>Media Voti</th>
                        <th>Recensioni Negative</th>
                        <th>Stato Vendita</th>
                        <th>Azione (Modifica Stato)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($u = $lista_utenti->fetch_assoc()): ?>
                        <tr>
                            <td><strong><?php echo htmlspecialchars($u['nome'] . " " . $u['cognome']); ?></strong></td>
                            <td style="color: #6B7280;"><?php echo htmlspecialchars($u['email']); ?></td>
                            <td>
                                <?php echo $u['media_totale'] ? number_format($u['media_totale'], 1) . " / 5" : "N/A"; ?>
                            </td>
                            <td>
                                <?php if ($u['recensioni_negative'] > 10): ?>
                                    <span class="badge badge-warning">⚠ <?php echo $u['recensioni_negative']; ?> negative</span>
                                <?php else: ?>
                                    <?php echo $u['recensioni_negative']; ?>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="badge <?php echo $u['statoVendita'] === 'attivo' ? 'badge-attivo' : 'badge-bloccato'; ?>">
                                    <?php echo strtoupper($u['statoVendita']); ?>
                                </span>
                            </td>
                            <td>
                                <form method="POST" style="margin: 0;">
                                    <input type="hidden" name="id_utente" value="<?php echo $u['idUtente']; ?>">
                                    
                                    <?php if ($u['statoVendita'] === 'attivo'): ?>
                                        <?php if ($u['recensioni_negative'] > 10): ?>
                                            <button type="submit" name="azione" value="blocca" class="btn btn-blocca" onclick="return confirm('Sei sicuro di voler bloccare le vendite per questo utente?');">Blocca Vendite</button>
                                        <?php else: ?>
                                            <button type="button" class="btn btn-disabled" title="L'utente non ha superato la soglia di 10 recensioni negative">Blocco non necessario</button>
                                        <?php endif; ?>
                                        
                                    <?php else: ?>
                                        <button type="submit" name="azione" value="sblocca" class="btn btn-sblocca">Sblocca Vendite</button>
                                    <?php endif; ?>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>