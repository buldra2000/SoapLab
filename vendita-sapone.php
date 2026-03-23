<?php
// Mostra errori per aiutarti nel debug
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once 'db/db.php';

// Controllo sessione
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

$user_id = $_SESSION['user_id'];

// Recuperiamo nome e cognome usando la chiave corretta idUtente
$sql = "SELECT nome, cognome FROM utenti WHERE idUtente = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    die("Errore: Utente non trovato.");
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Vendi Sapone - SoapLab</title>
    <link rel="stylesheet" href="css/global.css">
    <style>
        body { font-family: 'Segoe UI', sans-serif; background-color: #f4f7f6; margin: 0; }
        
        /* Stili Header Allineati alla Dashboard */
        header { background: #fff; padding: 10px 40px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); display: flex; justify-content: space-between; align-items: center; }
        header h1 { margin: 0; font-size: 24px; color: #333; }
        .dropdown { position: relative; display: inline-block; }
        .user-icon { font-size: 20px; cursor: pointer; padding: 8px; background: #f0f0f0; border-radius: 50%; width: 35px; height: 35px; display: flex; align-items: center; justify-content: center; transition: 0.3s; }
        .user-icon:hover { background: #e0e0e0; }
        .dropdown-content { display: none; position: absolute; right: 0; background-color: #fff; min-width: 200px; box-shadow: 0px 8px 16px rgba(0,0,0,0.1); z-index: 100; border-radius: 8px; overflow: hidden; border: 1px solid #eee; }
        .dropdown-content a { color: #444; padding: 12px 16px; text-decoration: none; display: block; font-size: 14px; transition: 0.2s; }
        .dropdown-content a:hover { background-color: #f8f9fa; color: #28a745; }
        .dropdown:hover .dropdown-content { display: block; }

        /* Stili Form */
        .form-container { max-width: 600px; margin: 50px auto; background: white; padding: 30px; border-radius: 12px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
        h2, h3 { color: #333; margin-top: 0; }
        label { display: block; margin-top: 15px; font-weight: bold; color: #666; font-size: 14px; }
        input, select, textarea { width: 100%; padding: 12px; margin: 8px 0 15px 0; border: 1px solid #ddd; border-radius: 5px; box-sizing: border-box; font-size: 15px; }
        input:focus, select:focus { outline: none; border-color: #28a745; }
        .btn-submit { background: #28a745; color: white; border: none; padding: 15px; cursor: pointer; font-weight: bold; width: 100%; border-radius: 5px; font-size: 16px; transition: 0.3s; margin-top: 10px; }
        .btn-submit:hover { background: #218838; }
        hr { border: 0; border-top: 1px solid #eee; margin: 25px 0; }
    </style>
</head>
<body>
    <header>
        <h1>SoapLab</h1>
        <div class="dropdown">
            <div class="user-icon">👤</div>
            <div class="dropdown-content">
                <a href="index.php" style="text-align: center; background: #f8f9fa;">
                    <strong><?php echo htmlspecialchars($user['nome'] . ' ' . $user['cognome']); ?></strong>
                </a>
                
                <a href="vendita-sapone.php" style="color: #28a745; font-weight: bold; border-bottom: 1px solid #eee;">
                    🧼 Vendi un sapone
                </a>

                <a href="dashboard.php">La mia dashboard</a>
                <a href="indirizzi.php">I miei indirizzi</a>
                <a href="settings.php">Impostazioni</a>
                <a href="db/logout-process.php" style="color: #dc3545; border-top: 1px solid #eee;">Logout</a>
            </div>
        </div>
    </header>

    <div class="form-container">
        <h2>Pubblica la tua inserzione</h2>
        <form action="db/process-vendita.php" method="POST" enctype="multipart/form-data">
            
            <label>Titolo Inserzione</label>
            <input type="text" name="titolo" required placeholder="Es: Set Saponi Lavanda">

            <div style="display: flex; gap: 20px;">
                <div style="flex: 1;">
                    <label>Prezzo Totale (€)</label>
                    <input type="number" step="0.01" name="prezzo" required>
                </div>
                <div style="flex: 1;">
                    <label>Peso Complessivo (g)</label>
                    <input type="number" name="peso" required>
                </div>
            </div>

            <hr>
            <h3>Dettagli Sapone</h3>

            <label>Nome Commerciale Sapone</label>
            <input type="text" name="nome_sapone" required placeholder="Es: Lavanda Bio Relax">

            <label>Categoria</label>
            <select name="categoria">
                <option value="1">Viso</option>
                <option value="2">Corpo</option>
                <option value="3">Shampoo</option>
                <option value="4">Mani</option>
            </select>

            <label>Tipo di Pelle Consigliata</label>
            <input type="text" name="pelle" placeholder="Es: Pelli grasse o sensibili">

            <label>Codice BIO (Certificazione)</label>
            <input type="text" name="codice_bio">

            <label>Immagine Prodotto</label>
            <input type="file" name="foto_sapone" required accept="image/*">

            <button type="submit" class="btn-submit">Pubblica Ora</button>
        </form>
        <p style="text-align: center; margin-top: 20px;"><a href="dashboard.php" style="color: #666; text-decoration: none; font-size: 14px;">← Torna alla Dashboard</a></p>
    </div>
</body>
</html>