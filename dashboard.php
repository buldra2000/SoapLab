<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once 'db/db.php';

//Controllo sessione, se non loggato -> login.html
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

$user_id = $_SESSION['user_id'];

//Recupero dati utente
$sql = "SELECT nome, cognome, email, statoVendita FROM utenti WHERE idUtente = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($user = $result->fetch_assoc()) {
} else {
    die("Errore: Utente non trovato.");
}
$stmt->close();

/**
 * Calcolo statistiche:
 *  - Conteggio acquisti utente
 *  - Conteggio vendite utente
 *  - Calcolo media voti
 */

//Conteggio acquisti utente
$sql_acquisti = "SELECT COUNT(*) AS num_acquisti 
                 FROM acquisti 
                 WHERE idUtente = ?";
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

//Conteggio vendite utente
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

// Calcolo media voti
$sql_rating = "SELECT AVG(voto) AS media_voti 
               FROM recensioni 
               WHERE idDestinatario = ?";
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

//Cronologia ordini JOIN e LEFT JOIN
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

//Recupero inserzioni utente
$pubblicazioni = [];
$sql_pub = "SELECT idInserzione, titolo, prezzoTotale, pesoComplessivo 
            FROM inserzioni 
            WHERE idUtente = ? 
            ORDER BY idInserzione DESC";

$stmt_pub = $conn->prepare($sql_pub);
if ($stmt_pub) {
    $stmt_pub->bind_param("i", $user_id);
    $stmt_pub->execute();
    $pubblicazioni = $stmt_pub->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt_pub->close();
}
?>

<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/global.css">
    <link rel="stylesheet" href="css/dashboard.css">
    <title>Dashboard - SoapLab</title>
</head>

<body>

    <header>
        <h1>SoapLab</h1>
        <div class="dropdown">
            <div class="user-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                    <circle cx="12" cy="7" r="4"></circle>
                </svg>
            </div>
            <div class="dropdown-content">
                <a href="index.php" style="text-align: center; background: #f8f9fa;">
                    <strong><?php echo htmlspecialchars($user['nome'] . ' ' . $user['cognome']); ?></strong>
                </a>

                <?php if ($user['statoVendita'] !== 'bloccato'): ?>
                    <a href="vendita-sapone.php" style="color: #28a745; font-weight: bold; border-bottom: 1px solid #eee;">
                        Vendi un sapone
                    </a>
                <?php endif; ?>

                <a href="dashboard.php">La mia dashboard</a>
                <a href="indirizzi.php">I miei indirizzi</a>
                <a href="top-venditori.php" style="color: #f39c12; font-weight: bold;">Top Venditori</a>
                <a href="db/logout-process.php" style="color: #dc3545; border-top: 1px solid #eee;">Logout</a>
            </div>
        </div>
    </header>

    <?php if (isset($_GET['msg'])): ?>
        <?php if ($_GET['msg'] === 'sapone_aggiunto'): ?>
            <div class="alert alert-success">Inserzione pubblicata con successo!</div>
        <?php elseif ($_GET['msg'] === 'welcome'): ?>
            <div class="alert alert-success">Registrazione completata! Benvenuto in SoapLab.</div>
        <?php endif; ?>
    <?php endif; ?>

    <!-- Statistiche utente -> Vendite, acquisti, rating, stato -->
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
                    <span
                        class="status-badge <?php echo ($user['statoVendita'] === 'bloccato') ? 'status-bloccato' : 'status-attivo'; ?>">
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

            <!-- Recupero acquisti -->
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
                                        if ($stato_pulito == 'in lavorazione')
                                            echo 'status-lavorazione';
                                        elseif ($stato_pulito == 'spedito')
                                            echo 'status-spedito';
                                        else
                                            echo 'status-attivo';
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
                                            <br><small style="color: #888;">Recensione inviata</small>
                                        <?php elseif ($stato_pulito !== 'consegnato'): ?>
                                            <br><small style="color: #999;">Disponibile dopo consegna</small>
                                        <?php endif; ?>

                                        <?php if (!empty($ordine['numeroTracking'])): ?>
                                            <br><small style="color: #888;">Tracking:
                                                <?php echo htmlspecialchars($ordine['numeroTracking']); ?></small>
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

            <!-- Recupero inserzioni -->
            <div class="orders-section" style="margin-top: 40px;">
                <h3>Le mie pubblicazioni</h3>
                <?php if (count($pubblicazioni) > 0): ?>
                    <table class="orders-table">
                        <thead>
                            <tr>
                                <th>Titolo Inserzione</th>
                                <th>Prezzo Totale</th>
                                <th>Peso</th>
                                <th>Azioni</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($pubblicazioni as $pub): ?>
                                <tr>
                                    <td><strong><?php echo htmlspecialchars($pub['titolo']); ?></strong></td>
                                    <td>€<?php echo number_format($pub['prezzoTotale'], 2); ?></td>
                                    <td><?php echo $pub['pesoComplessivo']; ?>g</td>
                                    <td>
                                        <a href="inserzione.php?id=<?php echo $pub['idInserzione']; ?>"
                                            style="color: #28a745; text-decoration: none; font-size: 13px; font-weight: bold;">
                                            🔍 Visualizza
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p style="color: #888; font-style: italic;">Non hai ancora pubblicato inserzioni.
                        <a href="vendita-sapone.php" style="color: #28a745;">Inizia ora!</a>
                    </p>
                <?php endif; ?>
            </div>

            <!-- Logout - Elimina utente -->
            <div style="text-align: center; margin-top: 30px;">
                <a href="db/logout-process.php" class="btn btn-logout">Logout</a>
                <form action="db/delete-account.php" method="POST" style="display: inline;">
                    <button type="submit" class="btn btn-delete"
                        onclick="return confirm('Eliminare l\'account?');">Elimina Account</button>
                </form>
            </div>
        </div>
    </div>

</body>

</html>