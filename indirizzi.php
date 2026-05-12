<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once 'db/db.php';

/**
 * Gestione indirizzi utente.
 *  1) Controllo user_id
 *  2) Recupero informazioni utente
 *  3) Recupero indirizzi
 */

// 1) Controllo user_id
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

$user_id = $_SESSION['user_id'];

// 2) Recupero informazioni utente
$sql_user = "SELECT nome, cognome FROM utenti WHERE idUtente = ?";
$stmt_user = $conn->prepare($sql_user);
$stmt_user->bind_param("i", $user_id);
$stmt_user->execute();
$res_user = $stmt_user->get_result();
$user = $res_user->fetch_assoc();

if (!$user) {
    die("Errore: Utente non trovato.");
}

// 3) Recupero indirizzi
$sql_addr = "SELECT * FROM indirizzi WHERE idUtente = ?";
$stmt_addr = $conn->prepare($sql_addr);
$stmt_addr->bind_param("i", $user_id);
$stmt_addr->execute();
$result = $stmt_addr->get_result();
?>

<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/global.css">
    <link rel="stylesheet" href="css/indirizzi.css">
    <title>SoapLab - I miei indirizzi</title>
</head>

<body>

    <header>
        <h1><a href="index.php" style="text-decoration: none; color: inherit;">SoapLab</a></h1>
        <div class="dropdown">
            <div class="user-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                <circle cx="12" cy="7" r="4"></circle>
            </svg>
            </div>
            <div class="dropdown-content">
                <?php if (isset($user) && $user): ?>
                    <a href="dashboard.php" style="text-align: center">
                        <strong><?php echo htmlspecialchars($user['nome'] . ' ' . $user['cognome']); ?></strong>
                    </a>
                    <a href="vendita-sapone.php" style="color: #28a745; font-weight: bold; border-bottom: 1px solid #eee;">
                        Vendi un sapone
                    </a>
                    <a href="dashboard.php">La mia dashboard</a>
                    <a href="indirizzi.php">I miei indirizzi</a>
                    <a href="top-venditori.php" style="color: #f39c12; font-weight: bold;">🏆 Top Venditori</a>
                    <a href="db/logout-process.php" style="color: #dc3545;">Logout</a>
                <?php else: ?>
                    <a href="login.html">Accedi</a>
                    <a href="registrazione.html">Registrati</a>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <div class="container">
        <h2>I tuoi indirizzi di spedizione</h2>

        <div class="address-grid">
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="address-card">
                        <div class="address-details">
                            <strong><?php echo htmlspecialchars($row['via'] . ', ' . $row['numeroCivico']); ?></strong>
                            <p><?php echo htmlspecialchars($row['citta']); ?></p>
                            <p>CAP: <?php echo htmlspecialchars($row['cap']); ?></p>
                        </div>

                        <a href="db/delete-address.php?id=<?php echo $row['idIndirizzo']; ?>" class="btn-delete"
                            onclick="return confirm('Vuoi davvero eliminare questo indirizzo?');">
                            Elimina
                        </a>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div style="grid-column: 1/-1; padding: 40px; background: #fff; border-radius: 12px; color: #888;">
                    <p>Non hai ancora salvato nessun indirizzo.</p>
                </div>
            <?php endif; ?>
        </div>

        <div class="add-section">
            <a href="aggiungi-indirizzo.php" class="btn-add-new">+ Aggiungi un nuovo indirizzo</a>
            <a href="dashboard.php" class="back-link">← Torna alla Dashboard</a>
        </div>
    </div>

</body>

</html>