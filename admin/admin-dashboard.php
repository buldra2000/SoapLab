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

// Recupero dati admin
$sql = "SELECT nome, cognome FROM amministratori WHERE idAdmin = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $admin_id);
$stmt->execute();
$result = $stmt->get_result();
$admin = $result->fetch_assoc();

// --- QUERY PER LE STATISTICHE DELLA DASHBOARD ---
// 1. Totale Ingredienti
$res_ing = $conn->query("SELECT COUNT(*) as tot FROM ingredienti");
$tot_ingredienti = $res_ing ? $res_ing->fetch_assoc()['tot'] : 0;

// 2. Totale Utenti Registrati
$res_utenti = $conn->query("SELECT COUNT(*) as tot FROM utenti");
$tot_utenti = $res_utenti ? $res_utenti->fetch_assoc()['tot'] : 0;

// 3. Totale Categorie
$res_cat = $conn->query("SELECT COUNT(*) as tot FROM categorie");
$tot_categorie = $res_cat ? $res_cat->fetch_assoc()['tot'] : 0;
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Pannello di Controllo - SoapLab</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --sidebar-bg: #111827;
            --sidebar-hover: #1F2937;
            --accent: #10B981;
            --accent-hover: #059669;
            --bg-light: #F3F4F6;
            --text-main: #1F2937;
            --text-muted: #6B7280;
            --card-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
        }

        body { 
            font-family: 'Inter', sans-serif; 
            background-color: var(--bg-light); 
            margin: 0; 
            color: var(--text-main); 
            -webkit-font-smoothing: antialiased;
        }
        
        .admin-layout { display: flex; min-height: 100vh; }
        
        /* Sidebar Styling */
        .sidebar { 
            width: 260px; 
            background: var(--sidebar-bg); 
            color: #E5E7EB; 
            display: flex;
            flex-direction: column;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
        }
        .sidebar-header {
            padding: 30px 25px 20px 25px;
            border-bottom: 1px solid #374151;
            margin-bottom: 15px;
        }
        .sidebar-header h2 { 
            margin: 0; 
            color: white; 
            font-size: 22px; 
            font-weight: 700;
            letter-spacing: -0.5px;
        }
        .sidebar-header span { color: var(--accent); }
        
        .sidebar nav { flex: 1; }
        .sidebar a { 
            display: flex; 
            align-items: center;
            color: #D1D5DB; 
            padding: 14px 25px; 
            text-decoration: none; 
            font-size: 15px;
            font-weight: 500;
            transition: all 0.2s ease; 
            border-left: 3px solid transparent;
        }
        .sidebar a:hover { 
            background: var(--sidebar-hover); 
            color: white;
            border-left: 3px solid var(--accent); 
        }
        .sidebar a.active {
            background: var(--sidebar-hover); 
            color: white;
            border-left: 3px solid var(--accent); 
        }
        .sidebar a i { margin-right: 12px; font-size: 18px; }
        
        .sidebar .logout { 
            margin-top: auto; 
            margin-bottom: 20px;
            border-top: 1px solid #374151; 
            padding-top: 20px;
            color: #F87171;
        }
        .sidebar .logout:hover { border-left-color: #EF4444; color: #FCA5A5; }

        .main-content { flex: 1; padding: 40px; overflow-y: auto; }
        
        .header-panel { 
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: white; 
            padding: 25px 35px; 
            border-radius: 12px; 
            box-shadow: var(--card-shadow); 
            margin-bottom: 35px; 
        }
        .header-panel h1 { margin: 0; font-size: 24px; color: var(--text-main); font-weight: 600;}
        .header-panel p { margin: 6px 0 0 0; color: var(--text-muted); font-size: 14px; }
        
        /* Stats Grid */
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

        /* Welcome Banner */
        .welcome-banner {
            background: linear-gradient(135deg, #10B981 0%, #059669 100%);
            border-radius: 12px;
            padding: 35px;
            color: white;
            box-shadow: var(--card-shadow);
        }
        .welcome-banner h2 { margin: 0 0 10px 0; font-size: 22px; }
        .welcome-banner p { margin: 0; opacity: 0.9; font-size: 15px; line-height: 1.5; max-width: 600px; }
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
                <a href="admin-dashboard.php" class="active">📊 Dashboard</a>
                <a href="admin-categorie.php">📁 Gestione Categorie</a>
                <a href="admin-ingredienti.php">🌿 Ingredienti e Benefici</a>
                <a href="admin-proprieta.php">✨ Proprietà</a>
                <a href="admin-utenti.php">👥 Moderazione Utenti</a>
            </nav>
            <a href="../db/logout-process.php" class="logout">🚪 Disconnetti</a>
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
            
            <div class="welcome-banner">
                <h2>Tutto sotto controllo.</h2>
                <p>Usa il menu laterale per popolare il database con nuovi ingredienti e proprietà, oppure per moderare i venditori presenti sulla piattaforma SoapLab assicurandoti che rispettino gli standard di qualità.</p>
            </div>

        </div>
    </div>

</body>
</html>