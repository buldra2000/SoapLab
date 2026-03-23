<?php 
session_start(); 
require_once 'db/db.php';

// 1. QUERY DINAMICA: Recuperiamo le inserzioni reali dal DB
// Usiamo LEFT JOIN sulle immagini per vedere l'inserzione anche se la foto mancasse
$sql = "SELECT i.idInserzione, i.titolo, i.prezzoTotale, i.pesoComplessivo, 
               c.nomeCategoria, img.percorso 
        FROM inserzioni i
        JOIN saponi s ON i.idInserzione = s.idInserzione
        JOIN categorie c ON s.idCategoria = c.idCategoria
        LEFT JOIN immagini img ON s.idSapone = img.idSapone
        GROUP BY i.idInserzione 
        ORDER BY i.idInserzione DESC";

$result = $conn->query($sql);

// 2. RECUPERO DATI UTENTE (Se loggato, per l'header)
$user = null;
if (isset($_SESSION['user_id'])) {
    $id = $_SESSION['user_id'];
    $res_user = $conn->query("SELECT nome, cognome FROM utenti WHERE idUtente = $id");
    $user = $res_user->fetch_assoc();
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/global.css">
    <title>SoapLab - Home</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background-color: #f4f7f6; margin: 0; }
        
        /* Header CSS coerente con Dashboard */
        header { background: #fff; padding: 10px 40px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); display: flex; justify-content: space-between; align-items: center; }
        header h1 { margin: 0; font-size: 24px; color: #333; }
        .dropdown { position: relative; display: inline-block; }
        .user-icon { font-size: 20px; cursor: pointer; padding: 8px; background: #f0f0f0; border-radius: 50%; width: 35px; height: 35px; display: flex; align-items: center; justify-content: center; transition: 0.3s; }
        .user-icon:hover { background: #e0e0e0; }
        .dropdown-content { display: none; position: absolute; right: 0; background-color: #fff; min-width: 200px; box-shadow: 0px 8px 16px rgba(0,0,0,0.1); z-index: 100; border-radius: 8px; overflow: hidden; border: 1px solid #eee; }
        .dropdown-content a { color: #444; padding: 12px 16px; text-decoration: none; display: block; font-size: 14px; transition: 0.2s; }
        .dropdown-content a:hover { background-color: #f8f9fa; color: #28a745; }
        .dropdown:hover .dropdown-content { display: block; }

        .shop-container { max-width: 1100px; margin: 40px auto; padding: 0 20px; }
        .product-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 25px; }
        
        .product-card {
            background: white; border-radius: 12px; overflow: hidden;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05); transition: 0.2s;
            border: 1px solid #eee; text-align: center;
        }
        .product-card:hover { transform: translateY(-5px); }
        .product-img { width: 100%; height: 200px; object-fit: cover; background: #eee; }
        .product-info { padding: 20px; }
        .category-tag { font-size: 11px; color: #28a745; font-weight: bold; text-transform: uppercase; }
        .product-info h3 { margin: 10px 0; font-size: 18px; color: #333; }
        .product-meta { font-size: 13px; color: #777; margin-bottom: 15px; }
        .product-price { font-size: 22px; font-weight: bold; color: #333; margin-bottom: 15px; }
        
        .btn-buy {
            background: #28a745; color: white; padding: 12px;
            text-decoration: none; border-radius: 6px; display: block;
            font-weight: bold; transition: 0.2s;
        }
        .btn-buy:hover { background: #218838; }
        
        .empty-msg { grid-column: 1/-1; padding: 50px; color: #888; font-style: italic; }
    </style>
</head>
<body>

    <header>
        <h1>SoapLab</h1>
        <div class="dropdown">
            <div class="user-icon">👤</div>
            <div class="dropdown-content">
                <?php if ($user): ?>
                    <a href="dashboard.php" style="text-align: center"><strong><?php echo htmlspecialchars($user['nome'] . ' ' . $user['cognome']); ?></strong></a>
                    <a href="vendita-sapone.php" style="color: #28a745; font-weight: bold; border-bottom: 1px solid #eee;">Vendi un sapone</a>
                    <a href="dashboard.php">La mia dashboard</a>
                    <a href="indirizzi.php">I miei indirizzi</a>
                    <a href="db/logout-process.php" style="color: #dc3545;">Logout</a>
                <?php else: ?>
                    <a href="login.html">Accedi</a>
                    <a href="registrazione.html">Registrati</a>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <div style="text-align: center; margin-top: 50px;">
        <h2>Benvenuto su SoapLab</h2>
        <p>Il tuo laboratorio digitale di saponi artigianali.</p>
    </div>

    <div class="shop-container">
        <div class="product-grid">
            <?php if ($result && $result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="product-card">
                        <?php 
                            $img_path = !empty($row['percorso']) ? $row['percorso'] : 'https://via.placeholder.com/300x200?text=SoapLab';
                        ?>
                        <img src="<?php echo $img_path; ?>" alt="Sapone" class="product-img">
                        
                        <div class="product-info">
                            <span class="category-tag"><?php echo htmlspecialchars($row['nomeCategoria']); ?></span>
                            <h3><?php echo htmlspecialchars($row['titolo']); ?></h3>
                            <p class="product-meta">Peso: <?php echo $row['pesoComplessivo']; ?>g</p>
                            <div class="product-price">€<?php echo number_format($row['prezzoTotale'], 2); ?></div>
                            <a href="inserzione.php?id=<?php echo $row['idInserzione']; ?>" class="btn-buy">Vedi Inserzione</a>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="empty-msg">Non ci sono ancora inserzioni disponibili. Sii il primo a vendere!</div>
            <?php endif; ?>
        </div>
    </div>

</body>
</html>