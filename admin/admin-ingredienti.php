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

$messaggio = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/admin-global.css">
    <title>Gestione Catalogo - SoapLab Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>

    <div class="admin-layout">
        <div class="sidebar">
            <div class="sidebar-header">
                <h2>Soap<span>Lab</span></h2>
                <div style="font-size: 12px; color: #9CA3AF; margin-top: 5px;">Admin Panel</div>
            </div>
            <nav>
                <a href="admin-dashboard.php">Dashboard</a>
                <a href="admin-categorie.php">Gestione Categorie</a>
                <a href="admin-ingredienti.php">Ingredienti e Benefici</a>
                <a href="admin-proprieta.php">Proprietà</a>
                <a href="admin-utenti.php">Moderazione Utenti</a>
            </nav>
            <a href="../db/logout-process.php" class="logout">Disconnetti</a>
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
                        <h3>Aggiungi Allergene</h3>
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
                        <h3>Aggiungi Ingrediente</h3>
                        <form method="POST">
                            <input type="hidden" name="azione" value="nuovo_ingrediente">
                            <input type="text" name="nome_ingrediente" required placeholder="Es. Burro di Karité">
                            <button type="submit">Salva Ingrediente</button>
                        </form>
                    </div>

                    <div class="card">
                        <h3>Associa Beneficio</h3>
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