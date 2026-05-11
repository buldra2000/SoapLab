<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once '../db/db.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.html");
    exit();
}

$messaggio = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['azione'])) {
    $idTarget = (int)$_POST['id_utente'];
    
    if ($_POST['azione'] == 'blocca') {
        $stmt = $conn->prepare("UPDATE utenti SET statoVendita = 'bloccato' WHERE idUtente = ?");
        $stmt->bind_param("i", $idTarget);
        if ($stmt->execute()) {
            $messaggio = "<div class='alert success'>Utente bloccato. Non potrà più vendere saponi.</div>";
        }
    } elseif ($_POST['azione'] == 'sblocca') {
        $stmt = $conn->prepare("UPDATE utenti SET statoVendita = 'attivo' WHERE idUtente = ?");
        $stmt->bind_param("i", $idTarget);
        if ($stmt->execute()) {
            $messaggio = "<div class='alert success'>Utente riabilitato alla vendita!</div>";
        }
    }
}

$sql_utenti = "
    SELECT u.idUtente, u.nome, u.cognome, u.email, u.statoVendita,
           -- Conta 1 solo se il voto è <= 2, altrimenti 0
           SUM(CASE WHEN r.voto <= 2 THEN 1 ELSE 0 END) AS recensioni_negative,
           -- Calcola la media su tutti i voti presenti
           AVG(r.voto) AS media_totale
    FROM utenti u
    LEFT JOIN recensioni r ON u.idUtente = r.idDestinatario
    GROUP BY u.idUtente
    ORDER BY recensioni_negative DESC, u.nome ASC
";
$lista_utenti = $conn->query($sql_utenti);
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/admin-global.css">
    <link rel="stylesheet" href="../css/admin-utenti.css">
    <title>Moderazione Utenti - SoapLab Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="sidebar">
        <div class="sidebar-header">
            <h2>Soap<span>Lab</span></h2>
            <div style="font-size: 12px; color: #9CA3AF; margin-top: 5px;">Admin Panel</div>
        </div>
        <nav>
            <a href="admin-dashboard.php">Dashboard</a>
            <a href="admin-categorie.php">Gestione Categorie</a>
            <a href="admin-ingredienti.php">Ingredienti e Benefici</a>
            <a href="admin-proprieta.php">Proprietà</a>
            <a href="admin-utenti.php">Moderazione Utenti</a>
        </nav>
        <a href="../db/logout-process.php" class="logout">Disconnetti</a>
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