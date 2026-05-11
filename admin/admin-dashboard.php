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
    <link rel="stylesheet" href="../css/admin-dashboard.css">
    <title>Pannello di Controllo - SoapLab</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
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