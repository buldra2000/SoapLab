<?php
session_start();
require_once 'db/db.php';

// Query fedele al requisito U7: media > 4 e almeno 10 recensioni
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
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #f4f7f6; color: #333; margin: 0; }
        header { background: white; padding: 20px 40px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); display: flex; justify-content: space-between; }
        .container { max-width: 900px; margin: 50px auto; padding: 20px; }
        .top-card { background: white; border-radius: 15px; padding: 30px; box-shadow: 0 10px 25px rgba(0,0,0,0.05); }
        h1 { color: #2e7d32; text-align: center; margin-bottom: 30px; }
        .seller-row { display: flex; align-items: center; padding: 20px; border-bottom: 1px solid #eee; transition: 0.3s; }
        .seller-row:hover { background: #f9f9f9; transform: translateX(10px); }
        .rank { font-size: 24px; font-weight: bold; color: #aaa; width: 50px; }
        .seller-info { flex-grow: 1; }
        .seller-name { font-size: 18px; font-weight: bold; display: block; }
        .seller-stats { font-size: 14px; color: #777; }
        .rating-badge { background: #e8f5e9; color: #2e7d32; padding: 8px 15px; border-radius: 20px; font-weight: bold; font-size: 18px; }
        .empty-msg { text-align: center; padding: 40px; color: #888; font-style: italic; }
    </style>
</head>
<body>

<header>
    <a href="index.php" style="text-decoration:none; color:inherit; font-weight:bold; font-size:24px;">SoapLab</a>
    <a href="dashboard.php" style="text-decoration:none; color:#2e7d32; font-weight:bold;">Torna alla Dashboard</a>
</header>

<div class="container">
    <div class="top-card">
        <h1>🏆 I Nostri Migliori Artigiani</h1>
        <p style="text-align: center; color: #666; margin-bottom: 40px;">
            Venditori con media superiore a 4.0 e almeno 10 feedback positivi.
        </p>

        <?php if ($res_top && $res_top->num_rows > 0): ?>
            <?php $rank = 1; while($row = $res_top->fetch_assoc()): ?>
                <div class="seller-row">
                    <div class="rank">#<?php echo $rank++; ?></div>
                    <div class="seller-info">
                        <span class="seller-name"><?php echo htmlspecialchars($row['nome'] . " " . $row['cognome']); ?></span>
                        <span class="seller-stats">
                            <?php echo $row['numero_recensioni']; ?> recensioni totali
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