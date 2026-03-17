<?php session_start(); 
$inserzioni_finte = [
    [
        'titolo' => 'Sapone Viso Bio Lavanda',
        'prezzo' => 12.50,
        'peso' => 150,
        'categoria' => 'Viso',
        'immagine' => 'https://via.placeholder.com/200x150?text=Sapone+Viso'
    ],
    [
        'titolo' => 'Kit Corpo Agrumi e Miele',
        'prezzo' => 22.00,
        'peso' => 400,
        'categoria' => 'Corpo',
        'immagine' => 'https://via.placeholder.com/200x150?text=Kit+Corpo'
    ],
    [
        'titolo' => 'Shampoo Solido Purificante',
        'prezzo' => 9.90,
        'peso' => 80,
        'categoria' => 'Shampoo',
        'immagine' => 'https://via.placeholder.com/200x150?text=Shampoo'
    ]
];?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SoapLab - Home</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; margin: 0; background-color: #f4f7f6; }

        /* Header Styling */
        header {
            background-color: #ffffff;
            padding: 10px 40px;
            display: flex;
            align-items: center;
            justify-content:间;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        h1 { 
            margin: 0; 
            font-size: 24px; 
            color: #333;
            flex-grow: 1;
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
        .dropdown:hover .dropdown-content { display: block; }
        .logout-link { border-top: 1px solid #eee; color: #dc3545 !important; }

        .shop-container { max-width: 1100px; margin: 50px auto; padding: 0 20px; }
        .product-grid { 
            display: grid; 
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); 
            gap: 25px; 
        }
        .product-card {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
            transition: transform 0.2s;
            border: 1px solid #eee;
            text-align: center;
        }
        .product-card:hover { transform: translateY(-5px); }
        .product-img { width: 100%; height: 180px; object-fit: cover; }
        .product-info { padding: 20px; }
        .category-tag { font-size: 11px; color: #28a745; font-weight: bold; text-transform: uppercase; }
        .product-info h3 { margin: 10px 0; font-size: 18px; color: #333; }
        .product-meta { font-size: 13px; color: #777; margin-bottom: 15px; }
        .product-price { font-size: 22px; font-weight: bold; color: #333; margin-bottom: 15px; }
        .btn-buy {
            background: #007bff; color: white; padding: 10px;
            text-decoration: none; border-radius: 6px; display: block;
            font-weight: bold; transition: background 0.2s;
        }
        .btn-buy:hover { background: #0056b3; }
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

    <div style="text-align: center; margin-top: 50px;">
        <h2>Benvenuto su SoapLab</h2>
        <p>Il tuo laboratorio digitale.</p>
    </div>

    <div class="shop-container">
        <div class="product-grid">
            <?php foreach ($inserzioni_finte as $item): ?>
                <div class="product-card">
                    <img src="<?php echo $item['immagine']; ?>" alt="Sapone" class="product-img">
                    <div class="product-info">
                        <span class="category-tag"><?php echo $item['categoria']; ?></span>
                        <h3><?php echo htmlspecialchars($item['titolo']); ?></h3>
                        <p class="product-meta">Peso complessivo: <?php echo $item['peso']; ?>g</p>
                        <div class="product-price">€<?php echo number_format($item['prezzo'], 2); ?></div>
                        <a href="inserzione.php" class="btn-buy">Vedi Inserzione</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

</body>
</html>