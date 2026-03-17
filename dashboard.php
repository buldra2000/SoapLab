<?php
session_start();
require_once 'db/db.php'; // Il tuo file di connessione

// Se non è loggato, lo rispediamo al login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

$user_id = $_SESSION['user_id'];

// Prepariamo la query per prendere i dati reali
$sql = "SELECT username, nome, cognome, email FROM utenti WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

// Salviamo i dati reali in una variabile $user
if ($user = $result->fetch_assoc()) {
    // Dati trovati con successo
} else {
    die("Errore: Utente non trovato nel database.");
}

// Recuperiamo l'indirizzo dell'utente dalla tabella 'indirizzi'
$sql_addr = "SELECT via, città, cap, provincia FROM indirizzi WHERE utente_id = ? LIMIT 1";
$stmt_addr = $conn->prepare($sql_addr);
$stmt_addr->bind_param("i", $user_id);
$stmt_addr->execute();
$res_addr = $stmt_addr->get_result();
$indirizzo = $res_addr->fetch_assoc();

?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - SoapLab</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; margin: 0; background-color: #f4f7f6; }

        /* Header Styling */
        header {
            background-color: #ffffff;
            padding: 10px 40px;
            display: flex;
            align-items: center;
            justify-content:间; /* Distribuisce gli spazi tra gli elementi */
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        h1 { 
            margin: 0; 
            font-size: 24px; 
            color: #333;
            flex-grow: 1; /* Fa sì che il titolo occupi lo spazio rimanente */
        }

        /* Dropdown Container */
        .dropdown {
            position: relative;
            display: inline-block;
        }

        /* Simbolo Utente */
        .user-icon {
            font-size: 22px;
            cursor: pointer;
            padding: 8px;
            background: #f0f0f0;
            border-radius: 50%;
            width: 35px;
            height: 35px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background 0.3s;
        }

        .user-icon:hover { background: #e0e0e0; }

        /* Contenuto del Menu (Allineato a destra) */
        .dropdown-content {
            display: none;
            position: absolute;
            right: 0; /* Lo ancora al bordo destro del simbolo */
            background-color: #ffffff;
            min-width: 180px;
            box-shadow: 0px 8px 16px rgba(0,0,0,0.15);
            z-index: 100;
            border-radius: 8px;
            overflow: hidden;
            border: 1px solid #eee;
        }

        .dropdown-content a {
            color: #444;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
            font-size: 14px;
            transition: background 0.2s;
        }

        .dropdown-content a:hover { background-color: #f8f9fa; color: #007bff; }

        /* Mostra il menu al passaggio del mouse */
        .dropdown:hover .dropdown-content { display: block; }

        .logout-link { border-top: 1px solid #eee; color: #dc3545 !important; }

        .container { padding: 40px; max-width: 1000px; margin: auto; }

        h2 { margin-bottom: 30px; }

        /* Griglia delle Card */
        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }

        .card {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
            text-align: center;
            transition: transform 0.2s;
        }

        .card:hover { transform: translateY(-5px); }

        .card h3 { margin: 0; color: #666; font-size: 16px; text-transform: uppercase; }
        
        .card .value { 
            font-size: 32px; 
            font-weight: bold; 
            margin: 15px 0; 
            color: #007bff; 
        }

        /* Stelle delle recensioni */
        .stars { color: #ffc107; font-size: 24px; }
        .star-empty { color: #e4e4e4; }

        .back-link {
            display: inline-block;
            margin-top: 30px;
            text-decoration: none;
            color: #007bff;
        }

        /* Sezione Account */
        .account-info { background: #f4f7f6; padding: 30px; border-radius: 12px; padding: 0 20% 0 20%;}
        .info-row { display: flex; justify-content: space-between; padding: 12px 0; border-bottom: 1px solid #f0f0f0;}
        .info-row:last-child { border-bottom: none; }
        .label { font-weight: bold; color: #666; }
        
        /* Bottoni */
        .actions { margin-top: 30px; display: flex; gap: 15px; justify-content: center}
        .btn { padding: 10px 20px; border-radius: 6px; text-decoration: none; font-weight: bold; border: none; cursor: pointer; transition: 0.2s; }
        .btn-logout { background: #6c757d; color: white; }
        .btn-delete { background: #fee; color: #dc3545; }
        .btn-delete:hover { background: #dc3545; color: white; }
        
        .back-link { display: block; margin-top: 20px; text-decoration: none; color: #007bff; }
    </style>
</head>
<body>

<header>
        <h1>SoapLab</h1>

        <div class="dropdown">
            <div class="user-icon">👤</div>
            <div class="dropdown-content">
                <?php if (isset($_SESSION['username'])): ?>
                    <a href="index.php" style="text-align: center"><strong><?php echo $_SESSION['username']; ?></strong></a>
                    <a href="dashboard.php">La mia dashboard</a>
                    <a href="indirizzi.php">I miei indirizzi</a>
                    <a href="settings.php">Impostazioni</a>
                    <a href="db/logout.php" class="logout-link" style="text-align: center">Logout</a>
                <?php else: ?>
                    <a href="login.html">Accedi</a>
                    <a href="registrazione.html">Registrati</a>
                <?php endif; ?>
            </div>
        </div>
    </header>

<div class="container">
    <h2>Benvenuto nel tuo pannello di controllo</h2>

    <div class="grid">
        <div class="card">
            <h3>Vendite</h3>
            <div class="value"><?php echo $vendite; ?></div>
            <p>Ordini completati</p>
        </div>

        <div class="card">
            <h3>Acquisti</h3>
            <div class="value"><?php echo $acquisti; ?></div>
            <p>Prodotti ricevuti</p>
        </div>

        <div class="card">
            <h3>Recensioni</h3>
            <div class="stars">
                <?php 
                for ($i = 1; $i <= 5; $i++) {
                    if ($i <= $rating) {
                        echo "★";
                    } else {
                        echo "<span class='star-empty'>★</span>";
                    }
                }
                ?>
            </div>
            <p>Valutazione media</p>
        </div>
    </div>

    <a href="index.php" class="back-link">← Torna alla Home</a>
</div>

<h2 style="text-align: center">Il mio account</h2>
    <div class="account-info">
        <div class="info-row">
            <span class="label">Username:</span>
            <span><?php echo htmlspecialchars($user['username']); ?></span>
        </div>
        <div class="info-row">
            <span class="label">Nome completo:</span>
            <span><?php echo htmlspecialchars($user['nome'] . " " . $user['cognome']); ?></span>
        </div>
        <div class="info-row">
            <span class="label">Email:</span>
            <span><?php echo htmlspecialchars($user['email']); ?></span>
        </div>
</div>

<div class="info-row" style="padding: 2% 20% 0 20%">
    <span class="label">I miei indirizzi:</span>
    <span>
        <?php 
        // Controlliamo quanti indirizzi ha l'utente
        $sql_count = "SELECT COUNT(*) as totale FROM indirizzi WHERE utente_id = ?";
        $stmt_count = $conn->prepare($sql_count);
        $stmt_count->bind_param("i", $user_id);
        $stmt_count->execute();
        $res_count = $stmt_count->get_result();
        $count_data = $res_count->fetch_assoc();

        if ($count_data['totale'] > 0) {
            // Se ha almeno un indirizzo, mostriamo il tasto "Consulta"
            echo '<a href="indirizzi.php" class="btn-consult">Consulta i miei indirizzi (' . $count_data['totale'] . ')</a>';
        } else {
            // Se non ne ha, mostriamo il tasto "Aggiungi"
            echo '<a href="aggiungi-indirizzo.php" class="btn-add">+ Aggiungi indirizzo</a>';
        }
        ?>
    </span>
</div>

        <div class="actions">
            <a href="db/logout-process.php" class="btn btn-logout">Logout</a>
            
            <form action="db/delete-account.php" method="POST" onsubmit="return confirm('ATTENZIONE: Sei sicuro di voler eliminare il tuo account? L\'operazione è definitiva.');">
                <button type="submit" class="btn btn-delete">Elimina account</button>
            </form>
        </div>
    </div>

</body>
</html>