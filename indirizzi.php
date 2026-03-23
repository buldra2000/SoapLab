<?php
// Mostra errori per aiutarti nel debug
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once 'db/db.php';

// 1. Controllo sessione
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

$user_id = $_SESSION['user_id'];

// 2. RECUPERO DATI UTENTE (Rimosso username, usato idUtente)
$sql_user = "SELECT nome, cognome FROM utenti WHERE idUtente = ?";
$stmt_user = $conn->prepare($sql_user);
$stmt_user->bind_param("i", $user_id);
$stmt_user->execute();
$res_user = $stmt_user->get_result();
$user = $res_user->fetch_assoc();

if (!$user) {
    die("Errore: Utente non trovato.");
}

// 3. RECUPERO INDIRIZZI (Usato idUtente come Foreign Key fedele al diagramma)
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
    <title>SoapLab - I miei indirizzi</title>
    <style>
        /* Contenuto */
        .container { max-width: 900px; margin: 40px auto; padding: 0 20px; text-align: center; }
        h2 { color: #444; margin-bottom: 30px; padding-bottom: 10px; display: inline-block; border-bottom: 2px solid #28a745; }
        
        .address-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 20px; }
        
        .address-card { background: white; padding: 25px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); border: 1px solid #eee; transition: 0.3s; }
        .address-card:hover { transform: translateY(-5px); }

        .address-details strong { color: #222; font-size: 1.1em; display: block; margin-bottom: 10px; }
        .address-details p { margin: 5px 0; color: #666; }

        .btn-delete { display: inline-block; margin-top: 15px; background-color: #fff; color: #dc3545; padding: 8px 16px; border: 1px solid #dc3545; border-radius: 6px; text-decoration: none; font-size: 13px; font-weight: bold; transition: 0.2s; }
        .btn-delete:hover { background-color: #dc3545; color: white; }

        .add-section { margin-top: 40px; }
        .btn-add-new { background-color: #28a745; color: white; padding: 15px 30px; border-radius: 8px; text-decoration: none; font-weight: bold; display: inline-block; }
        .back-link { display: block; margin-top: 25px; color: #007bff; text-decoration: none; }
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
                <a href="vendita-sapone.php" style="color: #28a745; font-weight: bold; border-bottom: 1px solid #eee;">
                    🧼 Vendi un sapone
                </a>
                <a href="dashboard.php">La mia dashboard</a>
                <a href="indirizzi.php">I miei indirizzi</a>
                <a href="settings.php">Impostazioni</a>
                <a href="db/logout-process.php" style="color: #dc3545; border-top: 1px solid #eee;">Logout</a>
            </div>
        </div>
    </header>

    <div class="container">
        <h2>I tuoi indirizzi di spedizione</h2>

        <div class="address-grid">
            <?php if ($result->num_rows > 0): ?>
                <?php while($row = $result->fetch_assoc()): ?>
                    <div class="address-card">
                        <div class="address-details">
                            <strong><?php echo htmlspecialchars($row['via'] . ', ' . $row['numeroCivico']); ?></strong>
                            <p><?php echo htmlspecialchars($row['citta']); ?></p>
                            <p>CAP: <?php echo htmlspecialchars($row['cap']); ?></p>
                        </div>
                        
                        <a href="db/delete-address.php?id=<?php echo $row['idIndirizzo']; ?>" 
                           class="btn-delete" 
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