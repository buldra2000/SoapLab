<?php
session_start();
require_once 'db/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

$user_id = $_SESSION['user_id'];

$sql = "SELECT * FROM indirizzi WHERE utente_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SoapLab - Indirizzi Associati</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background-color: #f4f7f6; margin: 0; padding: 0; text-align: center; }
        
        header { background: #fff; padding: 20px 40px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); display: flex; justify-content: space-between; align-items: center; text-align: left; }
        header h1 { margin: 0; font-size: 22px; color: #333; }

        .container { max-width: 900px; margin: 40px auto; padding: 0 20px; }
        
        /* Titolo centrato e senza bordo a tutta larghezza */
        h2 { color: #444; margin-bottom: 30px; padding-bottom: 10px; display: inline-block; border-bottom: 2px solid #ddd; }

        /* Griglia centrata */
        .address-grid { 
            display: grid; 
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); 
            gap: 20px; 
            justify-content: center; 
        }

        .address-card {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
            border: 1px solid #eee;
            /* Flexbox verticale per centrare il contenuto della card */
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .address-details { margin-bottom: 20px; }
        .address-details p { margin: 5px 0; color: #555; line-height: 1.4; }
        .address-details strong { color: #222; font-size: 1.2em; display: block; margin-bottom: 8px; }

        /* Pulsante Elimina centrato */
        .btn-delete {
            background-color: #ffeded;
            color: #dc3545;
            padding: 8px 20px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 13px;
            font-weight: bold;
            transition: 0.3s;
            border: 1px solid #ffcccc;
        }
        .btn-delete:hover { background-color: #dc3545; color: white; }

        .add-section { margin-top: 40px; }
        
        .btn-add-new {
            background-color: #28a745;
            color: white;
            padding: 15px 30px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
            font-size: 16px;
            display: inline-block;
            transition: 0.3s;
            box-shadow: 0 4px 6px rgba(40, 167, 69, 0.2);
        }
        .btn-add-new:hover { background-color: #218838; transform: translateY(-2px); }

        .back-link { display: block; margin-top: 25px; color: #007bff; text-decoration: none; font-weight: 500; }
    </style>
</head>
<body>

<header>
    <h1>SoapLab</h1>
    <a href="dashboard.php" style="text-decoration: none; color: #007bff;">Dashboard</a>
</header>

<div class="container">
    <h2>Indirizzi associati</h2>

    <div class="address-grid">
        <?php if ($result->num_rows > 0): ?>
            <?php while($row = $result->fetch_assoc()): ?>
                <div class="address-card">
                    <div class="address-details">
                        <p><strong><?php echo htmlspecialchars($row['via']); ?></strong></p>
                        <p><?php echo htmlspecialchars($row['città']); ?> (<?php echo htmlspecialchars($row['provincia']); ?>)</p>
                        <p>CAP: <?php echo htmlspecialchars($row['cap']); ?></p>
                    </div>
                    
                    <a href="db/delete-address.php?id=<?php echo $row['id']; ?>" 
                       class="btn-delete" 
                       onclick="return confirm('Sei sicuro di voler eliminare questo indirizzo?');">
                       Elimina
                    </a>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p style="grid-column: 1/-1; color: #888;">Nessun indirizzo trovato. Aggiungine uno qui sotto.</p>
        <?php endif; ?>
    </div>

    <div class="add-section">
        <a href="aggiungi-indirizzo.php" class="btn-add-new">+ Aggiungi un nuovo indirizzo</a>
        <a href="dashboard.php" class="back-link">← Torna alla Dashboard</a>
    </div>
</div>

</body>
</html>