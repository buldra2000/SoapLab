<?php
session_start();
require_once 'db/db.php';

// In un caso reale, useresti l'ID dall'URL: $id_inserzione = $_GET['id'];
// Qui usiamo dati d'esempio basati sulla tua proposta
$inserzione = [
    'nome_commerciale' => 'Sapone Artigianale alla Lavanda',
    'prezzo_totale' => 12.50,
    'peso_complessivo' => 150,
    'ingredienti' => 'Olio di oliva, acqua, olio essenziale di lavanda, fiori di lavanda essiccati',
    'proprieta' => 'Rilassante, lenitiva e antisettica',
    'pelle_consigliata' => 'Tutti i tipi, specialmente pelli sensibili',
    'categoria' => 'Viso e Corpo',
    'certificazione_bio' => 'Codice: BIO-IT-001 / Validità: 2027',
    'allergeni' => 'Linalool (naturalmente presente nell\'olio essenziale)',
    'immagine' => 'img/lavanda.jpg'
];
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title><?php echo $inserzione['nome_commerciale']; ?> - SoapLab</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background-color: #f4f7f6; margin: 0; padding: 0; }
        header { background: #fff; padding: 15px 40px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); display: flex; justify-content: space-between; align-items: center; }
        
        .container { max-width: 1000px; margin: 40px auto; background: white; padding: 30px; border-radius: 12px; display: flex; gap: 40px; }
        
        .image-section { flex: 1; }
        .image-section img { width: 100%; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
        
        .info-section { flex: 1.5; }
        .category { color: #28a745; font-weight: bold; text-transform: uppercase; font-size: 14px; }
        h1 { margin: 10px 0; color: #333; }
        .price { font-size: 28px; font-weight: bold; color: #007bff; margin: 15px 0; }
        
        .spec-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin: 20px 0; border-top: 1px solid #eee; padding-top: 20px; }
        .spec-item { font-size: 14px; }
        .spec-label { font-weight: bold; color: #666; display: block; }
        
        .description-box { background: #f9f9f9; padding: 15px; border-radius: 8px; margin-top: 20px; }
        .allergeni { color: #dc3545; font-size: 13px; margin-top: 10px; }

        .buy-section { margin-top: 30px; padding-top: 20px; border-top: 1px solid #eee; }
        .btn-buy-now { 
            background: #28a745; color: white; padding: 15px 40px; 
            text-decoration: none; border-radius: 8px; font-size: 18px; 
            font-weight: bold; display: inline-block; transition: 0.3s;
        }
        .btn-buy-now:hover { background: #218838; transform: translateY(-2px); }
    </style>
</head>
<body>

<header>
    <h1>SoapLab</h1>
    <a href="index.php" style="text-decoration: none; color: #007bff;">Torna allo Shop</a>
</header>

<div class="container">
    <div class="image-section">
        <img src="<?php echo $inserzione['immagine']; ?>" alt="Immagine Sapone">
    </div>

    <div class="info-section">
        <span class="category"><?php echo $inserzione['categoria']; ?></span>
        <h1><?php echo htmlspecialchars($inserzione['nome_commerciale']); ?></h1>
        <p class="price">€<?php echo number_format($inserzione['prezzo_totale'], 2); ?></p>
        <p><strong>Peso:</strong> <?php echo $inserzione['peso_complessivo']; ?>g</p>

        <div class="description-box">
            <p><strong>Ingredienti principali:</strong><br><?php echo htmlspecialchars($inserzione['ingredienti']); ?></p>
            <p><strong>Proprietà:</strong><br><?php echo htmlspecialchars($inserzione['proprieta']); ?></p>
        </div>

        <div class="spec-grid">
            <div class="spec-item">
                <span class="spec-label">Pelle consigliata</span>
                <?php echo htmlspecialchars($inserzione['pelle_consigliata']); ?>
            </div>
            <div class="spec-item">
                <span class="spec-label">Certificazione BIO</span>
                <?php echo htmlspecialchars($inserzione['certificazione_bio']); ?>
            </div>
        </div>

        <p class="allergeni"><strong>Attenzione allergeni:</strong> <?php echo htmlspecialchars($inserzione['allergeni']); ?></p>

        <div class="buy-section">
            <?php if(isset($_SESSION['user_id'])): ?>
                <a href="checkout.php" class="btn-buy-now">Compra ora</a>
            <?php else: ?>
                <a href="login.html" class="btn-buy-now" style="background: #6c757d;">Accedi per acquistare</a>
            <?php endif; ?>
        </div>
    </div>
</div>

</body>
</html>