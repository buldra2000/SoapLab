<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once '../db/db.php';

/**
 * Admin - Proprietà
 *  1) Verifica admin loggato
 *  2) Gestione POST inserimento nuova proprietà
 *  3) Recupero proprietà
 */

// 1) Verifica admin loggato
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.html");
    exit();
}

$messaggio = "";

// 2) Gestione POST inserimento nuova proprietà
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['azione']) && $_POST['azione'] == 'nuova_proprieta') {
    $nome = trim($_POST['nome_proprieta']);
    if (!empty($nome)) {
        $stmt = $conn->prepare("INSERT INTO proprieta (nomeProprieta) VALUES (?)");
        $stmt->bind_param("s", $nome);
        if ($stmt->execute()) {
            $messaggio = "<div class='alert success'>Proprietà registrata!</div>";
        } else {
            $messaggio = "<div class='alert error'>Errore durante l'inserimento.</div>";
        }
    }
}

// 3) Recupero proprietà
$proprieta = $conn->query("SELECT * FROM proprieta ORDER BY nomeProprieta ASC");
?>
<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/admin-global.css">
    <title>Gestione Proprietà - SoapLab Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>

<body>
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
            <h1>Definizione Proprietà</h1>
            <p>Inserisci le proprietà e le caratteristiche associabili ai saponi (es. Idratante, Purificante).</p>
        </div>

        <?php echo $messaggio; ?>

        <div class="content-grid">
            <div class="card">
                <h3>Aggiungi Nuova Proprietà</h3>
                <form method="POST">
                    <input type="hidden" name="azione" value="nuova_proprieta">
                    <label>Nome Proprietà</label>
                    <input type="text" name="nome_proprieta" required placeholder="Es. Esfoliante">
                    <button type="submit">Salva Proprietà</button>
                </form>
            </div>

            <div class="card">
                <h3>Elenco Proprietà</h3>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nome Proprietà</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($prop = $proprieta->fetch_assoc()): ?>
                            <tr>
                                <td style="color: #9CA3AF;">#<?php echo $prop['idProprieta']; ?></td>
                                <td style="font-weight: 500;"><?php echo htmlspecialchars($prop['nomeProprieta']); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>

</html>