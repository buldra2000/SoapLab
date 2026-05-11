<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once '../db/db.php';

/**
 * Admin - Categorie
 */

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.html");
    exit();
}

$messaggio = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['azione']) && $_POST['azione'] == 'nuova_categoria') {
    $nome = trim($_POST['nome_categoria']);
    if (!empty($nome)) {
        $stmt = $conn->prepare("INSERT INTO categorie (nomeCategoria) VALUES (?)");
        $stmt->bind_param("s", $nome);
        if ($stmt->execute()) {
            $messaggio = "<div class='alert success'>Categoria creata con successo!</div>";
        } else {
            $messaggio = "<div class='alert error'>Errore durante l'inserimento.</div>";
        }
    }
}

$categorie = $conn->query("SELECT * FROM categorie ORDER BY idCategoria ASC");
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/admin-global.css">
    <title>Gestione Categorie - SoapLab Admin</title>
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