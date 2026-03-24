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

// --- GESTIONE DEI FORM (INSERIMENTO DATI) ---
$messaggio = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // 1. Inserimento nuovo Ingrediente
    if (isset($_POST['azione']) && $_POST['azione'] == 'nuovo_ingrediente') {
        $nome = trim($_POST['nome_ingrediente']);
        if (!empty($nome)) {
            $stmt = $conn->prepare("INSERT INTO ingredienti (nomeIngrediente) VALUES (?)");
            $stmt->bind_param("s", $nome);
            if ($stmt->execute()) {
                $messaggio = "<div class='alert success'>Ingrediente aggiunto con successo!</div>";
            } else {
                $messaggio = "<div class='alert error'>Errore durante l'inserimento dell'ingrediente.</div>";
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
            } else {
                $messaggio = "<div class='alert error'>Errore durante l'inserimento del beneficio.</div>";
            }
        }
    }

    // 3. Associazione Ingrediente - Beneficio
    if (isset($_POST['azione']) && $_POST['azione'] == 'associa') {
        $idIng = $_POST['id_ingrediente'];
        $idBen = $_POST['id_beneficio'];
        if (!empty($idIng) && !empty($idBen)) {
            // Verifica che l'associazione non esista già
            $check = $conn->prepare("SELECT * FROM ingrediente_associato_beneficio WHERE idIngrediente = ? AND idBeneficio = ?");
            $check->bind_param("ii", $idIng, $idBen);
            $check->execute();
            if ($check->get_result()->num_rows == 0) {
                $stmt = $conn->prepare("INSERT INTO ingrediente_associato_beneficio (idIngrediente, idBeneficio) VALUES (?, ?)");
                $stmt->bind_param("ii", $idIng, $idBen);
                if ($stmt->execute()) {
                    $messaggio = "<div class='alert success'>Associazione creata correttamente!</div>";
                }
            } else {
                $messaggio = "<div class='alert error'>Questa associazione esiste già.</div>";
            }
        }
    }
}

// --- RECUPERO DATI PER LE SELECT E LA TABELLA ---
$ingredienti = $conn->query("SELECT * FROM ingredienti ORDER BY nomeIngrediente ASC");
$benefici = $conn->query("SELECT * FROM benefici ORDER BY nomeBeneficio ASC");

// Query complessa per mostrare la tabella riassuntiva con le associazioni
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
    <title>Gestione Ingredienti - SoapLab Admin</title>
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
            --card-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        }

        body { font-family: 'Inter', sans-serif; background-color: var(--bg-light); margin: 0; color: var(--text-main); }
        .admin-layout { display: flex; min-height: 100vh; }
        
        /* Sidebar Styling */
        .sidebar { width: 260px; background: var(--sidebar-bg); color: #E5E7EB; display: flex; flex-direction: column; }
        .sidebar-header { padding: 30px 25px 20px 25px; border-bottom: 1px solid #374151; margin-bottom: 15px; }
        .sidebar-header h2 { margin: 0; color: white; font-size: 22px; font-weight: 700; letter-spacing: -0.5px; }
        .sidebar-header span { color: var(--accent); }
        .sidebar nav { flex: 1; }
        .sidebar a { display: flex; align-items: center; color: #D1D5DB; padding: 14px 25px; text-decoration: none; font-size: 15px; font-weight: 500; border-left: 3px solid transparent; transition: 0.2s; }
        .sidebar a:hover, .sidebar a.active { background: var(--sidebar-hover); color: white; border-left: 3px solid var(--accent); }
        .sidebar .logout { margin-top: auto; margin-bottom: 20px; border-top: 1px solid #374151; padding-top: 20px; color: #F87171; }
        
        /* Main Content */
        .main-content { flex: 1; padding: 40px; overflow-y: auto; }
        .header-panel { background: white; padding: 25px 35px; border-radius: 12px; box-shadow: var(--card-shadow); margin-bottom: 30px; }
        .header-panel h1 { margin: 0; font-size: 24px; font-weight: 600; }
        .header-panel p { margin: 6px 0 0 0; color: var(--text-muted); font-size: 14px; }
        
        /* Grid Layout per Form e Tabella */
        .content-grid { display: grid; grid-template-columns: 1fr 2fr; gap: 30px; }
        
        .card { background: white; padding: 25px; border-radius: 12px; box-shadow: var(--card-shadow); margin-bottom: 25px; }
        .card h3 { margin-top: 0; border-bottom: 1px solid #E5E7EB; padding-bottom: 10px; color: var(--text-main); font-size: 16px; }
        
        /* Form Elements */
        label { display: block; font-size: 13px; font-weight: 600; color: var(--text-muted); margin-bottom: 6px; margin-top: 15px; }
        input[type="text"], select { width: 100%; padding: 10px 12px; border: 1px solid #D1D5DB; border-radius: 6px; box-sizing: border-box; font-family: 'Inter', sans-serif; font-size: 14px; }
        input[type="text"]:focus, select:focus { outline: none; border-color: var(--accent); ring: 2px solid var(--accent); }
        button { background: var(--accent); color: white; border: none; padding: 10px 15px; border-radius: 6px; font-weight: 600; cursor: pointer; width: 100%; margin-top: 15px; transition: 0.2s; }
        button:hover { background: var(--accent-hover); }
        
        /* Alerts */
        .alert { padding: 12px 15px; border-radius: 6px; margin-bottom: 20px; font-size: 14px; font-weight: 500; }
        .alert.success { background: #D1FAE5; color: #065F46; border: 1px solid #A7F3D0; }
        .alert.error { background: #FEE2E2; color: #991B1B; border: 1px solid #FECACA; }

        /* Table */
        table { width: 100%; border-collapse: collapse; margin-top: 15px; font-size: 14px; }
        th, td { padding: 12px 15px; text-align: left; border-bottom: 1px solid #E5E7EB; }
        th { background-color: #F9FAFB; font-weight: 600; color: var(--text-muted); text-transform: uppercase; font-size: 12px; }
        tr:hover { background-color: #F9FAFB; }
        .badge { background: #E0E7FF; color: #4338CA; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: 600; margin-right: 5px; display: inline-block; margin-bottom: 4px;}
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
                <a href="admin-ingredienti.php" class="active">🌿 Ingredienti e Benefici</a>
                <a href="admin-proprieta.php">✨ Proprietà</a>
                <a href="admin-utenti.php">👥 Moderazione Utenti</a>
            </nav>
            <a href="db/logout-process.php" class="logout">🚪 Disconnetti</a>
        </div>

        <div class="main-content">
            <div class="header-panel">
                <h1>Gestione Ingredienti e Benefici</h1>
                <p>Inserisci nuove voci a catalogo e crea le associazioni tra gli ingredienti e i loro effetti benefici.</p>
            </div>

            <?php echo $messaggio; ?>

            <div class="content-grid">
                
                <div>
                    <div class="card">
                        <h3>1. Aggiungi Ingrediente</h3>
                        <form method="POST">
                            <input type="hidden" name="azione" value="nuovo_ingrediente">
                            <label>Nome Ingrediente</label>
                            <input type="text" name="nome_ingrediente" required placeholder="Es. Olio d'Oliva">
                            <button type="submit">Salva Ingrediente</button>
                        </form>
                    </div>

                    <div class="card">
                        <h3>2. Aggiungi Beneficio</h3>
                        <form method="POST">
                            <input type="hidden" name="azione" value="nuovo_beneficio">
                            <label>Nome Beneficio</label>
                            <input type="text" name="nome_beneficio" required placeholder="Es. Idratante">
                            <button type="submit" style="background: #3B82F6;">Salva Beneficio</button>
                        </form>
                    </div>

                    <div class="card">
                        <h3>3. Associa Ingrediente a Beneficio</h3>
                        <form method="POST">
                            <input type="hidden" name="azione" value="associa">
                            <label>Seleziona Ingrediente</label>
                            <select name="id_ingrediente" required>
                                <option value="">-- Scegli --</option>
                                <?php 
                                $ingredienti->data_seek(0);
                                while($ing = $ingredienti->fetch_assoc()): 
                                ?>
                                    <option value="<?php echo $ing['idIngrediente']; ?>"><?php echo htmlspecialchars($ing['nomeIngrediente']); ?></option>
                                <?php endwhile; ?>
                            </select>

                            <label>Seleziona Beneficio associato</label>
                            <select name="id_beneficio" required>
                                <option value="">-- Scegli --</option>
                                <?php 
                                $benefici->data_seek(0);
                                while($ben = $benefici->fetch_assoc()): 
                                ?>
                                    <option value="<?php echo $ben['idBeneficio']; ?>"><?php echo htmlspecialchars($ben['nomeBeneficio']); ?></option>
                                <?php endwhile; ?>
                            </select>
                            
                            <button type="submit" style="background: #8B5CF6;">Crea Associazione</button>
                        </form>
                    </div>
                </div>

                <div>
                    <div class="card" style="height: 100%;">
                        <h3>Catalogo Ingredienti</h3>
                        <table>
                            <thead>
                                <tr>
                                    <th>Ingrediente</th>
                                    <th>Benefici Associati</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($catalogo && $catalogo->num_rows > 0): ?>
                                    <?php while($row = $catalogo->fetch_assoc()): ?>
                                        <tr>
                                            <td style="font-weight: 500;"><?php echo htmlspecialchars($row['nomeIngrediente']); ?></td>
                                            <td>
                                                <?php 
                                                if ($row['benefici_associati']) {
                                                    $bens = explode(', ', $row['benefici_associati']);
                                                    foreach($bens as $b) {
                                                        echo "<span class='badge'>" . htmlspecialchars($b) . "</span>";
                                                    }
                                                } else {
                                                    echo "<span style='color: #9CA3AF; font-size: 12px; font-style: italic;'>Nessun beneficio</span>";
                                                }
                                                ?>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="2" style="text-align: center; padding: 30px; color: #9CA3AF;">Nessun ingrediente presente nel sistema.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>

</body>
</html>