<?php
// 1. ABILITA ERRORI (Rimuovi in produzione)
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
require_once 'db/db.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$idInserzione = (int) $_GET['id'];

// 2. RECUPERO DATI UTENTE PER NAVBAR (Mancava!)
$user = null;
if (isset($_SESSION['user_id'])) {
    $id = $_SESSION['user_id'];
    $res_user = $conn->query("SELECT nome, cognome FROM utenti WHERE idUtente = $id");
    if($res_user) $user = $res_user->fetch_assoc();
}

// 3. RECUPERO DATI INSERZIONE
$sql_ins = "SELECT i.*, u.nome AS v_nome, u.cognome AS v_cognome 
            FROM inserzioni i 
            JOIN utenti u ON i.idUtente = u.idUtente 
            WHERE i.idInserzione = ?";
$stmt_ins = $conn->prepare($sql_ins);
$stmt_ins->bind_param("i", $idInserzione);
$stmt_ins->execute();
$res_ins = $stmt_ins->get_result();
$inserzione = $res_ins->fetch_assoc();

if (!$inserzione) {
    die("Inserzione non trovata.");
}

// 4. RECUPERO SAPONI
$sql_saponi = "SELECT s.*, c.nomeCategoria, cb.codiceStandard, cb.validita, img.percorso,
               GROUP_CONCAT(a.nomeAllergene SEPARATOR ', ') AS lista_allergeni
               FROM saponi s 
               JOIN categorie c ON s.idCategoria = c.idCategoria 
               LEFT JOIN certificazioni_bio cb ON s.idCertificazione = cb.idCertificazione 
               LEFT JOIN immagini img ON s.idSapone = img.idSapone 
               LEFT JOIN sapone_presenta_allergene spa ON s.idSapone = spa.idSapone
               LEFT JOIN allergeni a ON spa.idAllergene = a.idAllergene
               WHERE s.idInserzione = ?
               GROUP BY s.idSapone";
$stmt_sap = $conn->prepare($sql_saponi);
$stmt_sap->bind_param("i", $idInserzione);
$stmt_sap->execute();
$saponi = $stmt_sap->get_result();
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/global.css">
    <link rel="stylesheet" href="css/inserzione.css">
    <title><?php echo htmlspecialchars($inserzione['titolo']); ?> - SoapLab</title>
</head>
<body>

    <header>
        <h1><a href="index.php" style="text-decoration:none; color:inherit;">SoapLab</a></h1>
        <div class="dropdown">
            <div class="user-icon">👤</div>
            <div class="dropdown-content">
                <?php if ($user): ?>
                    <a href="dashboard.php" style="text-align: center"><strong><?php echo htmlspecialchars($user['nome'] . ' ' . $user['cognome']); ?></strong></a>
                    <a href="vendita-sapone.php" style="color: #28a745; font-weight: bold; border-bottom: 1px solid #eee;">Vendi un sapone</a>
                    <a href="dashboard.php">La mia dashboard</a>
                    <a href="indirizzi.php">I miei indirizzi</a>
                    <a href="top-venditori.php" style="color: #f39c12; font-weight: bold;">🏆 Top Venditori</a>
                    <a href="db/logout-process.php" class="logout-link">Logout</a>
                <?php else: ?>
                    <a href="login.html">Accedi</a>
                    <a href="registrazione.html">Registrati</a>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <div class="main-container">
        <div class="ins-header">
            <div class="ins-info">
                <h1><?php echo htmlspecialchars($inserzione['titolo']); ?></h1>
                <p>Venduto da: 
                    <strong>
                        <a href="profilo.php?id=<?php echo $inserzione['idUtente']; ?>" style="color: #2e7d32; text-decoration: none;">
                            <?php echo htmlspecialchars($inserzione['v_nome'] . " " . $inserzione['v_cognome']); ?>
                        </a>
                    </strong> 
                    | Peso Totale: <?php echo $inserzione['pesoComplessivo']; ?>g
                </p>
            </div>
            <div class="ins-price-box">
                <span class="total-price">€<?php echo number_format($inserzione['prezzoTotale'], 2); ?></span>
                <a href="db/acquisto-process.php?id=<?php echo $idInserzione; ?>" class="btn-buy">Acquista Ora</a>
            </div>
        </div>

        <h3 style="margin-bottom: 20px; color: #444;">Prodotti inclusi nell'offerta:</h3>

        <?php while ($sapone = $saponi->fetch_assoc()): ?>
            <div class="sapone-card">
                <?php $path = !empty($sapone['percorso']) ? $sapone['percorso'] : 'https://via.placeholder.com/250x200?text=SoapLab'; ?>
                <img src="<?php echo htmlspecialchars($path); ?>" class="sapone-img" alt="Foto Sapone">

                <div class="sapone-content">
                    <span class="badge badge-cat"><?php echo htmlspecialchars($sapone['nomeCategoria']); ?></span>
                    <?php if ($sapone['codiceStandard']): ?>
                        <span class="badge badge-bio">🍃 BIO: <?php echo htmlspecialchars($sapone['codiceStandard']); ?></span>
                    <?php endif; ?>

                    <h2><?php echo htmlspecialchars($sapone['nomeCommerciale']); ?></h2>

                    <div class="details-grid">
                        <div><span class="label">Pelle consigliata</span><?php echo htmlspecialchars($sapone['tipoPelleConsigliata'] ?? 'Tutti i tipi'); ?></div>
                        <div><span class="label">ID Prodotto</span>#<?php echo $sapone['idSapone']; ?></div>
                        <div style="grid-column: span 2;">
                            <span class="label">Allergeni</span>
                            <?php 
                            if (!empty($sapone['lista_allergeni'])) {
                                $array_all = explode(', ', $sapone['lista_allergeni']);
                                foreach ($array_all as $all) { echo '<span class="badge badge-allergene">' . htmlspecialchars($all) . '</span>'; }
                            } else { echo '<span style="color: #28a745; font-size: 13px;">✔ Nessun allergene</span>'; }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</body>
</html>