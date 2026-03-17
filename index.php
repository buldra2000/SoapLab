<?php session_start(); ?>
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

</body>
</html>