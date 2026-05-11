<?php
session_start();
require_once 'db/db.php';

// --- RECUPERO DATI UTENTE LOGGATO PER NAVBAR ---
$user = null;
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $res_user = $conn->query("SELECT nome, cognome FROM utenti WHERE idUtente = $user_id");
    if ($res_user) {
        $user = $res_user->fetch_assoc();
    }
}

// --- QUERY TOP VENDITORI ---
$sql_top = "SELECT u.nome, u.cognome, u.email, 
                   AVG(r.voto) AS media_voti, 
                   COUNT(r.idRecensione) AS numero_recensioni
            FROM utenti u
            JOIN recensioni r ON u.idUtente = r.idDestinatario
            GROUP BY u.idUtente
            HAVING numero_recensioni >= 10 AND media_voti > 4
            ORDER BY media_voti DESC, numero_recensioni DESC
            LIMIT 10";

$res_top = $conn->query($sql_top);
?>

<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <title>Top 10 Venditori - SoapLab</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/global.css">
    <style>
        .container {
            max-width: 900px;
            margin: 50px auto;
            padding: 20px;
        }

        /* CONTENUTI PAGINA */
        .top-card {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
        }

        .top-card h1 {
            color: #28a745;
            text-align: center;
            margin-bottom: 30px;
            border: none;
        }

        .seller-row {
            display: flex;
            align-items: center;
            padding: 20px;
            border-bottom: 1px solid #eee;
            transition: 0.3s;
        }

        .seller-row:hover {
            background: #f9f9f9;
            transform: translateX(10px);
        }

        .rank {
            font-size: 24px;
            font-weight: bold;
            color: #aaa;
            width: 50px;
        }

        .seller-info {
            flex-grow: 1;
        }

        .seller-name {
            font-size: 18px;
            font-weight: bold;
            display: block;
        }

        .seller-stats {
            font-size: 14px;
            color: #777;
        }

        .rating-badge {
            background: #e8f5e9;
            color: #2e7d32;
            padding: 8px 15px;
            border-radius: 20px;
            font-weight: bold;
            font-size: 18px;
        }

        .empty-msg {
            text-align: center;
            padding: 40px;
            color: #888;
            font-style: italic;
        }
    </style>
</head>

<body>

    <header>
        <h1><a href="index.php" style="text-decoration: none; color: inherit;">SoapLab</a></h1>
        <div class="dropdown">
            <div class="user-icon">👤</div>
            <div class="dropdown-content">
                <?php if (isset($user) && $user): ?>
                    <!-- Nome Utente -->
                    <a href="dashboard.php" style="background: #f9f9f9; font-weight: bold; text-align: center;">
                        <?php echo htmlspecialchars($user['nome'] . ' ' . $user['cognome']); ?>
                    </a>

                    <a href="vendita-sapone.php">Vendi un sapone</a>
                    <a href="dashboard.php">La mia dashboard</a>
                    <a href="indirizzi.php">I miei indirizzi</a>
                    <a href="top-venditori.php" style="color: #f39c12 !important;">🏆 Top Venditori</a>

                    <!-- Link Logout con classe speciale -->
                    <a href="db/logout-process.php" class="logout-link">Logout</a>
                <?php else: ?>
                    <a href="login.html">Accedi</a>
                    <a href="registrazione.html">Registrati</a>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <div class="container">
        <div class="top-card">
            <h1>🏆 I Nostri Migliori Artigiani</h1>
            <p style="text-align: center; color: #666; margin-bottom: 40px;">
                Venditori d'eccellenza con una media superiore a 4.0 basata su almeno 10 recensioni.
            </p>

            <?php if ($res_top && $res_top->num_rows > 0): ?>
                <?php $rank = 1;
                while ($row = $res_top->fetch_assoc()): ?>
                    <div class="seller-row">
                        <div class="rank">#<?php echo $rank++; ?></div>
                        <div class="seller-info">
                            <span
                                class="seller-name"><?php echo htmlspecialchars($row['nome'] . " " . $row['cognome']); ?></span>
                            <span class="seller-stats">
                                Basato su <?php echo $row['numero_recensioni']; ?> recensioni totali
                            </span>
                        </div>
                        <div class="rating-badge">
                            ⭐ <?php echo number_format($row['media_voti'], 1); ?>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="empty-msg">
                    <p>Al momento non ci sono venditori che soddisfano i criteri di eccellenza.<br>
                        Continua a fare acquisti per aiutare i nostri artigiani a scalare la classifica!</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

</body>

</html>