<?php
session_start();
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SoapLab - Home</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            text-align: center;
            background-color: #f4f7f6;
        }
        header {
            background-color: #ffffff;
            padding: 20px 0;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        h1 {
            margin: 0;
            color: #333;
            letter-spacing: 2px;
            text-transform: uppercase;
        }
        .content {
            margin-top: 50px;
        }
        .nav-links a {
            margin: 0 15px;
            text-decoration: none;
            color: #007bff;
            font-weight: bold;
        }
        .logout-btn {
            color: #dc3545 !class;
        }
    </style>
</head>
<body>

    <header>
        <h1>SoapLab</h1>
    </header>

    <div class="content">
        <?php if (isset($_SESSION['username'])): ?>
            <h2>Benvenuto, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h2>
            <p>Sei loggato nel tuo pannello personale.</p>
            <div class="nav-links">
                <a href="db/logout-process.php" class="logout-btn">Esci</a>
            </div>
        <?php else: ?>
            <h2>Benvenuto su SoapLab</h2>
            <p>Accedi per gestire i tuoi dati.</p>
            <div class="nav-links">
                <a href="login.html">Accedi</a>
                <a href="registrazione.html">Registrati</a>
            </div>
        <?php endif; ?>
    </div>

</body>
</html>