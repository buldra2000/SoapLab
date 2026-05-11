<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once '../db/db.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.html");
    exit();
}

$admin_id = $_SESSION['admin_id'];
$sql = "SELECT nome, cognome FROM amministratori WHERE idAdmin = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $admin_id);
$stmt->execute();
$result = $stmt->get_result();
$admin = $result->fetch_assoc();

$res_ing = $conn->query("SELECT COUNT(*) as tot FROM ingredienti");
$tot_ingredienti = $res_ing ? $res_ing->fetch_assoc()['tot'] : 0;

$res_utenti = $conn->query("SELECT COUNT(*) as tot FROM utenti");
$tot_utenti = $res_utenti ? $res_utenti->fetch_assoc()['tot'] : 0;

$res_cat = $conn->query("SELECT COUNT(*) as tot FROM categorie");
$tot_categorie = $res_cat ? $res_cat->fetch_assoc()['tot'] : 0;
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/admin-global.css">
    <title>Pannello di Controllo - SoapLab</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        .dashboard-grid { 
            display: grid; 
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); 
            gap: 25px; 
            margin-bottom: 35px;
        }
        .stat-card { 
            background: white; 
            padding: 30px; 
            border-radius: 12px; 
            box-shadow: var(--card-shadow); 
            position: relative;
            overflow: hidden;
            border: 1px solid #E5E7EB;
        }
        .stat-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; width: 100%; height: 4px;
        }
        .stat-card.green::before { background: var(--accent); }
        .stat-card.blue::before { background: #3B82F6; }
        .stat-card.purple::before { background: #8B5CF6; }
        
        .stat-card h3 { 
            margin: 0; 
            color: var(--text-muted); 
            font-size: 13px; 
            text-transform: uppercase; 
            letter-spacing: 0.5px;
            font-weight: 600;
        }
        .stat-card p { 
            font-size: 32px; 
            font-weight: 700; 
            margin: 10px 0 0 0; 
            color: var(--text-main); 
        }
    </style>
</head>
<body>

    <div class="admin-layout">
        <div class="sidebar">
            <div class="sidebar-header">
                <h2>Soap<span>Lab</span></h2>
                <div style="font-size: 12px; color: #9CA3AF; margin-top: 5px;">Admin Panel</div>
            </div>
            <nav>
                <a href="admin-dashboard.php" class="active">Dashboard</a>
                <a href="admin-categorie.php">Gestione Categorie</a>
                <a href="admin-ingredienti.php">Ingredienti e Benefici</a>
                <a href="admin-proprieta.php">Proprietà</a>
                <a href="admin-utenti.php">Moderazione Utenti</a>
            </nav>
            <a href="../db/logout-process.php" class="logout">Disconnetti</a>
        </div>

        <div class="main-content">
            <div class="header-panel">
                <div>
                    <h1>Bentornato, <?php echo htmlspecialchars($admin['nome']); ?></h1>
                    <p>Panoramica del sistema e gestione della piattaforma.</p>
                </div>
                <div style="background: #E5E7EB; padding: 10px 15px; border-radius: 8px; font-weight: 600; font-size: 14px;">
                    📅 <?php echo date('d/m/Y'); ?>
                </div>
            </div>
            <div class="dashboard-grid">
                <div class="stat-card green">
                    <h3>Totale Ingredienti</h3>
                    <p><?php echo $tot_ingredienti; ?></p>
                </div>
                <div class="stat-card blue">
                    <h3>Utenti Registrati</h3>
                    <p><?php echo $tot_utenti; ?></p>
                </div>
                <div class="stat-card purple">
                    <h3>Categorie Saponi</h3>
                    <p><?php echo $tot_categorie; ?></p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>