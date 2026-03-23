<?php
session_start();
require_once 'db/db.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$idInserzione = (int)$_GET['id'];

// 1. Recupero i dati generali dell'INSERZIONE e del VENDITORE
$sql_ins = "SELECT i.*, u.nome AS v_nome, u.cognome AS v_cognome 
            FROM inserzioni i 
            JOIN utenti u ON i.idUtente = u.idUtente 
            WHERE i.idInserzione = ?";
$stmt_ins = $conn->prepare($sql_ins);
$stmt_ins->bind_param("i", $idInserzione);
$stmt_ins->execute();
$res_ins = $stmt_ins->get_result();
$inserzione = $res_ins->fetch_assoc();

if (!$inserzione) {
    die("Inserzione non trovata.");
}

// 2. Recupero TUTTI i SAPONI collegati a questa inserzione
// Includiamo Categoria e Certificazione Bio per ogni singolo sapone
$sql_saponi = "SELECT s.*, c.nomeCategoria, cb.codiceStandard, img.percorso 
               FROM saponi s 
               JOIN categorie c ON s.idCategoria = c.idCategoria 
               LEFT JOIN certificazioni_bio cb ON s.idCertificazione = cb.idCertificazione 
               LEFT JOIN immagini img ON s.idSapone = img.idSapone 
               WHERE s.idInserzione = ?";
$stmt_sap = $conn->prepare($sql_saponi);
$stmt_sap->bind_param("i", $idInserzione);
$stmt_sap->execute();
$saponi = $stmt_sap->get_result();
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($inserzione['titolo']); ?> - SoapLab</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background-color: #f4f7f6; margin: 0; color: #333; }
        header { background: #fff; padding: 15px 40px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); display: flex; justify-content: space-between; align-items: center; }
        header a { text-decoration: none; color: #333; font-weight: bold; }
        
        .main-container { max-width: 1100px; margin: 40px auto; padding: 0 20px; }
        
        /* Box Inserzione */
        .ins-header { background: white; padding: 30px; border-radius: 15px; box-shadow: 0 4px 10px rgba(0,0,0,0.05); margin-bottom: 30px; display: flex; justify-content: space-between; align-items: center; }
        .ins-info h1 { margin: 0; color: #222; }
        .ins-info p { color: #777; margin: 5px 0 0 0; }
        .ins-price-box { text-align: right; }
        .total-price { font-size: 32px; font-weight: bold; color: #28a745; display: block; }
        
        /* Lista Saponi */
        .sapone-card { background: white; border-radius: 12px; margin-bottom: 20px; display: flex; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.05); border: 1px solid #eee; }
        .sapone-img { width: 250px; height: 200px; object-fit: cover; background: #fafafa; }
        .sapone-content { padding: 20px; flex-grow: 1; }
        
        .badge { display: inline-block; padding: 3px 10px; border-radius: 15px; font-size: 11px; font-weight: bold; text-transform: uppercase; margin-bottom: 8px; }
        .badge-cat { background: #e8f5e9; color: #2e7d32; }
        .badge-bio { background: #fff3e0; color: #e65100; margin-left: 5px; }
        
        h2 { margin: 0 0 10px 0; color: #444; font-size: 20px; }
        .details-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; font-size: 14px; }
        .label { font-weight: bold; color: #888; display: block; }
        
        .btn-buy { background: #28a745; color: white; padding: 15px 40px; text-decoration: none; border-radius: 8px; font-weight: bold; font-size: 18px; transition: 0.3s; display: inline-block; }
        .btn-buy:hover { background: #218838; transform: scale(1.02); }
    </style>
</head>
<body>

<header>
    <a href="index.php" style="font-size: 24px;">SoapLab</a>
    <a href="dashboard.php">La mia Dashboard</a>
</header>

<div class="main-container">
    <div class="ins-header">
        <div class="ins-info">
            <h1><?php echo htmlspecialchars($inserzione['titolo']); ?></h1>
            <p>Venduto da: <strong><?php echo htmlspecialchars($inserzione['v_nome'] . " " . $inserzione['v_cognome']); ?></strong> 
               | Peso Totale: <?php echo $inserzione['pesoComplessivo']; ?>g</p>
        </div>
        <div class="ins-price-box">
            <span class="total-price">€<?php echo number_format($inserzione['prezzoTotale'], 2); ?></span>
            <a href="db/acquisto-process.php?id=<?php echo $inserzione['idInserzione']; ?>" class="btn-buy">Acquista Ora</a>
        </div>
    </div>

    <h3>Prodotti inclusi nell'offerta:</h3>

    <?php while($sapone = $saponi->fetch_assoc()): ?>
        <div class="sapone-card">
            <?php 
                $path = !empty($sapone['percorso']) ? $sapone['percorso'] : 'https://via.placeholder.com/250x200?text=SoapLab';
            ?>
            <img src="<?php echo htmlspecialchars($path); ?>" class="sapone-img" alt="Foto Sapone">
            
            <div class="sapone-content">
                <span class="badge badge-cat"><?php echo htmlspecialchars($sapone['nomeCategoria']); ?></span>
                <?php if ($sapone['codiceStandard']): ?>
                    <span class="badge badge-bio">🍃 BIO: <?php echo htmlspecialchars($sapone['codiceStandard']); ?></span>
                <?php endif; ?>

                <h2><?php echo htmlspecialchars($sapone['nomeCommerciale']); ?></h2>
                
                <div class="details-grid">
                    <div>
                        <span class="label">Pelle consigliata</span>
                        <?php echo htmlspecialchars($sapone['tipoPelleConsigliata'] ?? 'Tutti i tipi'); ?>
                    </div>
                    <div>
                        <span class="label">ID Prodotto</span>
                        #<?php echo $sapone['idSapone']; ?>
                    </div>
                </div>
            </div>
        </div>
    <?php endwhile; ?>
</div>

</body>
</html>