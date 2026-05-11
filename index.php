<?php
session_start();
require_once 'db/db.php';

// --- 1. RECUPERO CATEGORIE PER I FILTRI ---
$sql_cat = "SELECT * FROM categorie ORDER BY nomeCategoria ASC";
$res_cat = $conn->query($sql_cat);

// --- 2. LOGICA DEL FILTRO (Requisito S4) ---
$where_clause = "";
if (isset($_GET['categoria']) && is_numeric($_GET['categoria'])) {
    $idFiltro = (int) $_GET['categoria'];
    // Se c'è un filtro, aggiungiamo la condizione alla query [cite: 58-59]
    $where_clause = "WHERE c.idCategoria = $idFiltro";
}

// --- 3. QUERY DINAMICA INSERZIONI ---
$sql = "SELECT i.idInserzione, i.titolo, i.prezzoTotale, i.pesoComplessivo, 
               c.nomeCategoria, c.idCategoria, img.percorso 
        FROM inserzioni i
        JOIN saponi s ON i.idInserzione = s.idInserzione
        JOIN categorie c ON s.idCategoria = c.idCategoria
        LEFT JOIN immagini img ON s.idSapone = img.idSapone
        $where_clause
        GROUP BY i.idInserzione 
        ORDER BY i.idInserzione DESC";

$result = $conn->query($sql);

// --- 4. RECUPERO DATI UTENTE ---
$user = null;
if (isset($_SESSION['user_id'])) {
    $id = $_SESSION['user_id'];
    $res_user = $conn->query("SELECT nome, cognome FROM utenti WHERE idUtente = $id");
    $user = $res_user->fetch_assoc();
}
?>
<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/global.css">
    <link rel="stylesheet" href="css/index.css">
    <title>SoapLab - Home</title>
</head>

<body>

    <header>
        <h1>SoapLab</h1>
        <div class="dropdown">
            <div class="user-icon">👤</div>
            <div class="dropdown-content">
                <?php if ($user): ?>
                    <a href="dashboard.php"
                        style="text-align: center"><strong><?php echo htmlspecialchars($user['nome'] . ' ' . $user['cognome']); ?></strong></a>
                    <a href="vendita-sapone.php"
                        style="color: #28a745; font-weight: bold; border-bottom: 1px solid #eee;">Vendi un sapone</a>
                    <a href="dashboard.php">La mia dashboard</a>
                    <a href="indirizzi.php">I miei indirizzi</a>
                    <a href="top-venditori.php" style="color: #f39c12; font-weight: bold;">🏆 Top Venditori</a>
                    <a href="db/logout-process.php" style="color: #dc3545;">Logout</a>
                <?php else: ?>
                    <a href="login.html">Accedi</a>
                    <a href="registrazione.html">Registrati</a>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <div style="text-align: center; margin-top: 50px;">
        <h2>Benvenuto su SoapLab</h2>
        <p>Il tuo laboratorio digitale di saponi artigianali.</p>
    </div>

    <div class="shop-container">

        <div class="filter-bar">
            <a href="index.php" class="filter-btn <?php echo !isset($_GET['categoria']) ? 'active' : ''; ?>">
                Tutte le categorie
            </a>

            <?php while ($cat = $res_cat->fetch_assoc()): ?>
                <a href="index.php?categoria=<?php echo $cat['idCategoria']; ?>"
                    class="filter-btn <?php echo (isset($_GET['categoria']) && $_GET['categoria'] == $cat['idCategoria']) ? 'active' : ''; ?>">
                    <?php echo htmlspecialchars($cat['nomeCategoria']); ?>
                </a>
            <?php endwhile; ?>
        </div>

        <div class="product-grid">
            <?php if ($result && $result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="product-card">
                        <?php
                        $img_path = !empty($row['percorso']) ? $row['percorso'] : 'https://via.placeholder.com/300x200?text=SoapLab';
                        ?>
                        <img src="<?php echo $img_path; ?>" alt="Sapone" class="product-img">

                        <div class="product-info">
                            <span class="category-tag"><?php echo htmlspecialchars($row['nomeCategoria']); ?></span>
                            <h3><?php echo htmlspecialchars($row['titolo']); ?></h3>
                            <p class="product-meta">Peso: <?php echo $row['pesoComplessivo']; ?>g</p>
                            <div class="product-price">€<?php echo number_format($row['prezzoTotale'], 2); ?></div>
                            <a href="inserzione.php?id=<?php echo $row['idInserzione']; ?>" class="btn-buy">Vedi Inserzione</a>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="empty-msg">
                    <h3>Nessun sapone trovato</h3>
                    <p>Non ci sono ancora inserzioni disponibili per questa categoria.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>

</html>