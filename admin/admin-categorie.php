<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once '../db/db.php';

// Controllo Admin
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.html");
    exit();
}

$messaggio = "";

// --- GESTIONE INSERIMENTO ---
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['azione']) && $_POST['azione'] == 'nuova_categoria') {
    $nome = trim($_POST['nome_categoria']);
    if (!empty($nome)) {
        $stmt = $conn->prepare("INSERT INTO categorie (nomeCategoria) VALUES (?)");
        $stmt->bind_param("s", $nome);
        if ($stmt->execute()) {
            $messaggio = "<div class='alert success'>✅ Categoria creata con successo!</div>";
        } else {
            $messaggio = "<div class='alert error'>❌ Errore durante l'inserimento.</div>";
        }
    }
}

// --- RECUPERO DATI ---
$categorie = $conn->query("SELECT * FROM categorie ORDER BY idCategoria ASC");
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Gestione Categorie - SoapLab Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        /* Stesso CSS di admin-ingredienti.php per coerenza */
        :root { --sidebar-bg: #111827; --sidebar-hover: #1F2937; --accent: #10B981; --accent-hover: #059669; --bg-light: #F3F4F6; --text-main: #1F2937; --text-muted: #6B7280; --card-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05); }
        body { font-family: 'Inter', sans-serif; background-color: var(--bg-light); margin: 0; color: var(--text-main); display: flex; min-height: 100vh; }
        .sidebar { width: 260px; background: var(--sidebar-bg); color: #E5E7EB; display: flex; flex-direction: column; }
        .sidebar-header { padding: 30px 25px 20px 25px; border-bottom: 1px solid #374151; margin-bottom: 15px; }
        .sidebar-header h2 { margin: 0; color: white; font-size: 22px; font-weight: 700; }
        .sidebar-header span { color: var(--accent); }
        .sidebar nav { flex: 1; }
        .sidebar a { display: flex; align-items: center; color: #D1D5DB; padding: 14px 25px; text-decoration: none; font-size: 15px; font-weight: 500; transition: 0.2s; border-left: 3px solid transparent; }
        .sidebar a:hover, .sidebar a.active { background: var(--sidebar-hover); color: white; border-left: 3px solid var(--accent); }
        .sidebar .logout { margin-top: auto; margin-bottom: 20px; border-top: 1px solid #374151; padding-top: 20px; color: #F87171; }
        .main-content { flex: 1; padding: 40px; overflow-y: auto; }
        .header-panel { background: white; padding: 25px 35px; border-radius: 12px; box-shadow: var(--card-shadow); margin-bottom: 30px; }
        .header-panel h1 { margin: 0; font-size: 24px; }
        .content-grid { display: grid; grid-template-columns: 1fr 2fr; gap: 30px; }
        .card { background: white; padding: 25px; border-radius: 12px; box-shadow: var(--card-shadow); margin-bottom: 25px; }
        .card h3 { margin-top: 0; border-bottom: 1px solid #E5E7EB; padding-bottom: 10px; font-size: 16px; }
        label { display: block; font-size: 13px; font-weight: 600; color: var(--text-muted); margin-bottom: 6px; margin-top: 15px; }
        input[type="text"] { width: 100%; padding: 10px 12px; border: 1px solid #D1D5DB; border-radius: 6px; box-sizing: border-box; }
        button { background: var(--accent); color: white; border: none; padding: 10px 15px; border-radius: 6px; font-weight: 600; cursor: pointer; width: 100%; margin-top: 15px; }
        button:hover { background: var(--accent-hover); }
        .alert { padding: 12px 15px; border-radius: 6px; margin-bottom: 20px; font-size: 14px; font-weight: 500; }
        .alert.success { background: #D1FAE5; color: #065F46; border: 1px solid #A7F3D0; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; font-size: 14px; }
        th, td { padding: 12px 15px; text-align: left; border-bottom: 1px solid #E5E7EB; }
        th { background-color: #F9FAFB; color: var(--text-muted); text-transform: uppercase; font-size: 12px; }
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="sidebar-header">
            <h2>Soap<span>Lab</span></h2>
            <div style="font-size: 12px; color: #9CA3AF; margin-top: 5px;">Admin Panel</div>
        </div>
        <nav>
            <a href="admin-dashboard.php">📊 Dashboard</a>
            <a href="admin-categorie.php" class="active">📁 Gestione Categorie</a>
            <a href="admin-ingredienti.php">🌿 Ingredienti e Benefici</a>
            <a href="admin-proprieta.php">✨ Proprietà</a>
            <a href="admin-utenti.php">👥 Moderazione Utenti</a>
        </nav>
        <a href="../db/logout-process.php" class="logout">🚪 Disconnetti</a>
    </div>

    <div class="main-content">
        <div class="header-panel">
            <h1>Gestione Categorie</h1>
            <p>Aggiungi e visualizza le categorie per classificare i saponi (es. Viso, Corpo, Capelli).</p>
        </div>

        <?php echo $messaggio; ?>

        <div class="content-grid">
            <div class="card">
                <h3>Aggiungi Nuova Categoria</h3>
                <form method="POST">
                    <input type="hidden" name="azione" value="nuova_categoria">
                    <label>Nome Categoria</label>
                    <input type="text" name="nome_categoria" required placeholder="Es. Sapone Solido">
                    <button type="submit">Salva Categoria</button>
                </form>
            </div>

            <div class="card">
                <h3>Categorie Esistenti</h3>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nome Categoria</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($cat = $categorie->fetch_assoc()): ?>
                            <tr>
                                <td style="color: #9CA3AF;">#<?php echo $cat['idCategoria']; ?></td>
                                <td style="font-weight: 500;"><?php echo htmlspecialchars($cat['nomeCategoria']); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>