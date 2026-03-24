<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once 'db/db.php';

// Controllo di sicurezza: verifichiamo che sia loggato un ADMIN
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.html");
    exit();
}

$messaggio = "";

// --- GESTIONE DEI FORM (INSERIMENTO DATI) ---
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // 1. Inserimento nuovo Ingrediente
    if (isset($_POST['azione']) && $_POST['azione'] == 'nuovo_ingrediente') {
        $nome = trim($_POST['nome_ingrediente']);
        if (!empty($nome)) {
            $stmt = $conn->prepare("INSERT INTO ingredienti (nomeIngrediente) VALUES (?)");
            $stmt->bind_param("s", $nome);
            if ($stmt->execute()) {
                $messaggio = "<div class='alert success'>Ingrediente aggiunto con successo!</div>";
            }
        }
    }

    // 2. Inserimento nuovo Beneficio
    if (isset($_POST['azione']) && $_POST['azione'] == 'nuovo_beneficio') {
        $nome = trim($_POST['nome_beneficio']);
        if (!empty($nome)) {
            $stmt = $conn->prepare("INSERT INTO benefici (nomeBeneficio) VALUES (?)");
            $stmt->bind_param("s", $nome);
            if ($stmt->execute()) {
                $messaggio = "<div class='alert success'>Beneficio aggiunto con successo!</div>";
            }
        }
    }

    // --- NUOVO: 3. Inserimento nuovo Allergene (Requisito S1) ---
    if (isset($_POST['azione']) && $_POST['azione'] == 'nuovo_allergene') {
        $nome = trim($_POST['nome_allergene']);
        $tipo = trim($_POST['tipo_allergene']);
        if (!empty($nome)) {
            $stmt = $conn->prepare("INSERT INTO allergeni (nomeAllergene, tipo) VALUES (?, ?)");
            $stmt->bind_param("ss", $nome, $tipo);
            if ($stmt->execute()) {
                $messaggio = "<div class='alert success'>Allergene inserito nel sistema!</div>";
            }
        }
    }

    // 4. Associazione Ingrediente - Beneficio
    if (isset($_POST['azione']) && $_POST['azione'] == 'associa') {
        $idIng = $_POST['id_ingrediente'];
        $idBen = $_POST['id_beneficio'];
        if (!empty($idIng) && !empty($idBen)) {
            $stmt = $conn->prepare("INSERT IGNORE INTO ingrediente_associato_beneficio (idIngrediente, idBeneficio) VALUES (?, ?)");
            $stmt->bind_param("ii", $idIng, $idBen);
            if ($stmt->execute()) {
                $messaggio = "<div class='alert success'>Associazione creata correttamente!</div>";
            }
        }
    }
}

// --- RECUPERO DATI ---
$ingredienti = $conn->query("SELECT * FROM ingredienti ORDER BY nomeIngrediente ASC");
$benefici = $conn->query("SELECT * FROM benefici ORDER BY nomeBeneficio ASC");
$lista_allergeni = $conn->query("SELECT * FROM allergeni ORDER BY nomeAllergene ASC");

$catalogo_sql = "
    SELECT i.nomeIngrediente, GROUP_CONCAT(b.nomeBeneficio SEPARATOR ', ') as benefici_associati
    FROM ingredienti i
    LEFT JOIN ingrediente_associato_beneficio a ON i.idIngrediente = a.idIngrediente
    LEFT JOIN benefici b ON a.idBeneficio = b.idBeneficio
    GROUP BY i.idIngrediente
    ORDER BY i.nomeIngrediente ASC
";
$catalogo = $conn->query($catalogo_sql);
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Gestione Catalogo - SoapLab Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        /* (Manteniamo i tuoi stili CSS esistenti) */
        :root { --sidebar-bg: #111827; --sidebar-hover: #1F2937; --accent: #10B981; --accent-hover: #059669; --bg-light: #F3F4F6; --text-main: #1F2937; --text-muted: #6B7280; --card-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05); }
        body { font-family: 'Inter', sans-serif; background-color: var(--bg-light); margin: 0; color: var(--text-main); }
        .admin-layout { display: flex; min-height: 100vh; }
        .sidebar { width: 260px; background: var(--sidebar-bg); color: #E5E7EB; display: flex; flex-direction: column; }
        .sidebar-header { padding: 30px 25px 20px 25px; border-bottom: 1px solid #374151; margin-bottom: 15px; }
        .sidebar-header h2 { margin: 0; color: white; font-size: 22px; font-weight: 700; }
        .sidebar-header span { color: var(--accent); }
        .sidebar a { display: flex; align-items: center; color: #D1D5DB; padding: 14px 25px; text-decoration: none; font-size: 15px; font-weight: 500; border-left: 3px solid transparent; transition: 0.2s; }
        .sidebar a:hover, .sidebar a.active { background: var(--sidebar-hover); color: white; border-left: 3px solid var(--accent); }
        .sidebar .logout { margin-top: auto; margin-bottom: 20px; border-top: 1px solid #374151; padding-top: 20px; color: #F87171; }
        .main-content { flex: 1; padding: 40px; overflow-y: auto; }
        .header-panel { background: white; padding: 25px 35px; border-radius: 12px; box-shadow: var(--card-shadow); margin-bottom: 30px; }
        .content-grid { display: grid; grid-template-columns: 1fr 2fr; gap: 30px; }
        .card { background: white; padding: 25px; border-radius: 12px; box-shadow: var(--card-shadow); margin-bottom: 25px; }
        .card h3 { margin-top: 0; border-bottom: 1px solid #E5E7EB; padding-bottom: 10px; color: var(--text-main); font-size: 16px; }
        label { display: block; font-size: 13px; font-weight: 600; color: var(--text-muted); margin-bottom: 6px; margin-top: 15px; }
        input[type="text"], select { width: 100%; padding: 10px 12px; border: 1px solid #D1D5DB; border-radius: 6px; box-sizing: border-box; font-size: 14px; }
        button { background: var(--accent); color: white; border: none; padding: 10px 15px; border-radius: 6px; font-weight: 600; cursor: pointer; width: 100%; margin-top: 15px; }
        .alert { padding: 12px 15px; border-radius: 6px; margin-bottom: 20px; font-size: 14px; font-weight: 500; }
        .alert.success { background: #D1FAE5; color: #065F46; border: 1px solid #A7F3D0; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; font-size: 14px; }
        th, td { padding: 12px 15px; text-align: left; border-bottom: 1px solid #E5E7EB; }
        th { background-color: #F9FAFB; font-weight: 600; color: var(--text-muted); text-transform: uppercase; font-size: 11px; }
        .badge { background: #E0E7FF; color: #4338CA; padding: 4px 8px; border-radius: 4px; font-size: 11px; font-weight: 600; margin-right: 5px; display: inline-block; }
        .badge-red { background: #FEE2E2; color: #991B1B; }
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
                <a href="admin-dashboard.php">📊 Dashboard</a>
                <a href="admin-categorie.php">📁 Gestione Categorie</a>
                <a href="admin-ingredienti.php" class="active">🌿 Catalogo Ingredienti</a>
                <a href="admin-proprieta.php">✨ Proprietà</a>
                <a href="admin-utenti.php">👥 Moderazione Utenti</a>
            </nav>
            <a href="db/logout-process.php" class="logout">🚪 Disconnetti</a>
        </div>

        <div class="main-content">
            <div class="header-panel">
                <h1>Gestione Catalogo Dinamico</h1>
                <p>Configura gli allergeni e gli ingredienti che i venditori potranno selezionare.</p>
            </div>

            <?php echo $messaggio; ?>

            <div class="content-grid">
                
                <div>
                    <div class="card" style="border-top: 4px solid #F87171;">
                        <h3>⚠️ Aggiungi Allergene</h3>
                        <form method="POST">
                            <input type="hidden" name="azione" value="nuovo_allergene">
                            <label>Nome Allergene</label>
                            <input type="text" name="nome_allergene" required placeholder="Es. Arachidi">
                            <label>Tipo/Categoria</label>
                            <input type="text" name="tipo_allergene" placeholder="Es. Frutta a guscio">
                            <button type="submit" style="background: #EF4444;">Salva Allergene</button>
                        </form>
                    </div>

                    <div class="card">
                        <h3>🌿 Aggiungi Ingrediente</h3>
                        <form method="POST">
                            <input type="hidden" name="azione" value="nuovo_ingrediente">
                            <input type="text" name="nome_ingrediente" required placeholder="Es. Burro di Karité">
                            <button type="submit">Salva Ingrediente</button>
                        </form>
                    </div>

                    <div class="card">
                        <h3>✨ Associa Beneficio</h3>
                        <form method="POST">
                            <input type="hidden" name="azione" value="associa">
                            <label>Ingrediente</label>
                            <select name="id_ingrediente" required>
                                <?php $ingredienti->data_seek(0); while($ing = $ingredienti->fetch_assoc()): ?>
                                    <option value="<?php echo $ing['idIngrediente']; ?>"><?php echo htmlspecialchars($ing['nomeIngrediente']); ?></option>
                                <?php endwhile; ?>
                            </select>
                            <label>Effetto Benefico</label>
                            <select name="id_beneficio" required>
                                <?php $benefici->data_seek(0); while($ben = $benefici->fetch_assoc()): ?>
                                    <option value="<?php echo $ben['idBeneficio']; ?>"><?php echo htmlspecialchars($ben['nomeBeneficio']); ?></option>
                                <?php endwhile; ?>
                            </select>
                            <button type="submit" style="background: #8B5CF6;">Collega</button>
                        </form>
                    </div>
                </div>

                <div>
                    <div class="card">
                        <h3>Allergeni Censiti</h3>
                        <table>
                            <thead>
                                <tr><th>Nome</th><th>Tipo</th></tr>
                            </thead>
                            <tbody>
                                <?php while($all = $lista_allergeni->fetch_assoc()): ?>
                                    <tr>
                                        <td><span class="badge badge-red"><?php echo htmlspecialchars($all['nomeAllergene']); ?></span></td>
                                        <td style="color: #6B7280; font-size: 12px;"><?php echo htmlspecialchars($all['tipo']); ?></td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="card">
                        <h3>Catalogo Associazioni</h3>
                        <table>
                            <thead>
                                <tr><th>Ingrediente</th><th>Benefici</th></tr>
                            </thead>
                            <tbody>
                                <?php while($row = $catalogo->fetch_assoc()): ?>
                                    <tr>
                                        <td style="font-weight: 500;"><?php echo htmlspecialchars($row['nomeIngrediente']); ?></td>
                                        <td>
                                            <?php 
                                            if ($row['benefici_associati']) {
                                                foreach(explode(', ', $row['benefici_associati']) as $b) {
                                                    echo "<span class='badge'>$b</span>";
                                                }
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
</body>
</html>