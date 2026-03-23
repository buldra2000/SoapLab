<?php
// Mostra errori per aiutarti nel debug (toglili in produzione!)
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

// 1. Modifica query: tolto 'username', cambiato 'id' in 'idUtente'
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

// Inizializzazione variabili statistiche (da popolare con query reali nel tuo progetto)
$vendite = 0; 
$acquisti = 0;
$rating = 4; 
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - SoapLab</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; margin: 0; background-color: #f4f7f6; color: #333; }

        /* Header Styling */
        header {
            background-color: #ffffff;
            padding: 10px 40px;
            display: flex;
            align-items: center;
            justify-content: space-between; /* 2. Corretto il carattere cinese qui */
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        h1 { margin: 0; font-size: 24px; color: #333; flex-grow: 1; }

        /* Dropdown Style */
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

        /* Dashboard Layout */
        .container { padding: 40px; max-width: 1000px; margin: auto; }
        .grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 40px; }
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