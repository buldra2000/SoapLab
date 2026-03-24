<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once 'db/db.php';

// Controllo di sicurezza: verifichiamo che sia loggato un ADMIN, non un utente normale
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin-login.php");
    exit();
}

$admin_id = $_SESSION['admin_id'];

// Recupero dati admin per l'intestazione
$sql = "SELECT nome, cognome FROM amministratori WHERE idAdmin = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $admin_id);
$stmt->execute();
$result = $stmt->get_result();
$admin = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Pannello di Controllo Admin - SoapLab</title>
    <link rel="stylesheet" href="css/global.css">
    <style>
        body { font-family: 'Segoe UI', sans-serif; background-color: #2c3e50; margin: 0; color: #333; }
        
        /* Layout a due colonne per l'area Admin */
        .admin-layout { display: flex; min-height: 100vh; }
        
        /* Sidebar laterale scura */
        .sidebar { width: 250px; background: #1a252f; color: white; padding-top: 20px; }
        .sidebar h2 { text-align: center; color: #18bc9c; margin-bottom: 30px; border-bottom: 1px solid #2c3e50; padding-bottom: 15px; }
        .sidebar a { display: block; color: #ecf0f1; padding: 15px 25px; text-decoration: none; transition: 0.2s; border-left: 4px solid transparent; }
        .sidebar a:hover { background: #2c3e50; border-left: 4px solid #18bc9c; }
        .sidebar a.logout { border-left: 4px solid #e74c3c; color: #e74c3c; margin-top: 50px; }
        .sidebar a.logout:hover { background: #e74c3c; color: white; }

        /* Contenuto Principale */
        .main-content { flex: 1; background: #ecf0f1; padding: 40px; }
        .header-panel { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.05); margin-bottom: 30px; }
        
        /* Griglia delle scorciatoie */
        .dashboard-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; }
        .stat-card { background: white; padding: 25px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.05); text-align: center; border-top: 4px solid #18bc9c; }
        .stat-card h3 { margin: 0; color: #7f8c8d; font-size: 16px; text-transform: uppercase; }
        .stat-card p { font-size: 24px; font-weight: bold; margin: 10px 0 0 0; color: #2c3e50; }
    </style>
</head>
<body>

    <div class="admin-layout">
        <div class="sidebar">
            <h2>SoapLab Admin</h2>
            <a href="admin-dashboard.php">📊 Dashboard</a>
            <a href="admin-categorie.php">📁 Gestione Categorie</a>
            <a href="admin-ingredienti.php">🌿 Ingredienti e Benefici</a>
            <a href="admin-proprieta.php">✨ Proprietà</a>
            <a href="admin-utenti.php">👥 Moderazione Utenti</a>
            <a href="db/admin-logout-process.php" class="logout">🚪 Logout Admin</a>
        </div>

        <div class="main-content">
            <div class="header-panel">
                <h1 style="margin:0;">Benvenuto, <?php echo htmlspecialchars($admin['nome']); ?></h1>
                <p style="color: #7f8c8d; margin-top: 5px;">Pannello di gestione e controllo piattaforma.</p>
            </div>

            <div class="dashboard-grid">
                <div class="stat-card">
                    <h3>Ingredienti a Sistema</h3>
                    <p>--</p> </div>
                <div class="stat-card" style="border-top-color: #e67e22;">
                    <h3>Utenti Venditori</h3>
                    <p>--</p>
                </div>
                <div class="stat-card" style="border-top-color: #e74c3c;">
                    <h3>Utenti da Revisionare</h3>
                    <p>--</p>
                </div>
            </div>
            
            <div style="margin-top: 40px; background: white; padding: 30px; border-radius: 8px;">
                <h2>Seleziona un'operazione dal menu laterale per iniziare.</h2>
            </div>
        </div>
    </div>

</body>
</html>