<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once 'db/db.php';

/**
 * Vendita sapone.
 *  1) Controllo user_id
 *  2) Informazioni venditore
 *  3) Recupero opzioni DB (lista allergeni e categorie)
 */

// 1) Controllo user_id
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

$user_id = $_SESSION['user_id'];

// 2) Informazioni venditore
$sql_user = "SELECT nome, cognome FROM utenti WHERE idUtente = ?";
$stmt_user = $conn->prepare($sql_user);
$stmt_user->bind_param("i", $user_id);
$stmt_user->execute();
$user = $stmt_user->get_result()->fetch_assoc();

if (!$user) {
    die("Errore: Utente non trovato.");
}

// 3) Recupero opzioni DB (lista allergeni e categorie)
$sql_all = "SELECT idAllergene, nomeAllergene FROM allergeni ORDER BY nomeAllergene ASC";
$res_all = $conn->query($sql_all);
$allergeni = $res_all->fetch_all(MYSQLI_ASSOC);
$sql_cat = "SELECT idCategoria, nomeCategoria FROM categorie ORDER BY nomeCategoria ASC";
$res_cat = $conn->query($sql_cat);
$categorie = $res_cat->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <title>Vendi Sapone - SoapLab</title>
    <link rel="stylesheet" href="css/global.css">
    <link rel="stylesheet" href="css/vendita-sapone.css">
</head>

<body>

    <header>
        <h1><a href="index.php" style="text-decoration:none; color:inherit;">SoapLab</a></h1>
        <div class="dropdown">
            <div class="user-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                <circle cx="12" cy="7" r="4"></circle>
            </svg>
            </div>
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

    <div class="form-container">
        <h2>Nuova Inserzione</h2>
        <form action="db/vendita-process.php" method="POST" enctype="multipart/form-data">

            <label>Titolo Inserzione</label>
            <input type="text" name="titolo" required placeholder="Es: Kit Benessere Lavanda">

            <label>Descrizione Generale</label>
            <textarea name="descrizione" rows="3" required
                placeholder="Descrivi brevemente il contenuto del set..."></textarea>

            <div style="display: flex; gap: 20px;">
                <div style="flex: 1;">
                    <label>Prezzo Set (€)</label>
                    <input type="number" step="0.01" name="prezzo" required>
                </div>
                <div style="flex: 1;">
                    <label>Peso Totale (g)</label>
                    <input type="number" name="peso" required>
                </div>
            </div>

            <hr style="margin: 30px 0; border: 0; border-top: 1px solid #eee;">

            <h3>Dettagli Saponi</h3>
            <div id="saponi-container">
                <div class="sapone-block">
                    <h4 style="margin-top: 0; color: #059669;">Sapone 1</h4>

                    <label>Nome Commerciale</label>
                    <input type="text" name="nome_sapone[]" required>

                    <label>Categoria</label>
                    <select name="categoria[]">
                        <?php foreach ($categorie as $cat): ?>
                            <option value="<?php echo $cat['idCategoria']; ?>">
                                <?php echo htmlspecialchars($cat['nomeCategoria']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <label>Tipo di Pelle Consigliata</label>
                    <input type="text" name="pelle[]" placeholder="Es: Pelli secche">

                    <div style="display: flex; gap: 15px;">
                        <div style="flex: 2;">
                            <label>Codice BIO (Opzionale)</label>
                            <input type="text" name="codice_bio[]">
                        </div>
                        <div style="flex: 1;">
                            <label>Validità</label>
                            <input type="date" name="data_bio[]">
                        </div>
                    </div>

                    <label>Allergeni Presenti</label>
                    <div class="allergeni-grid">
                        <?php foreach ($allergeni as $all): ?>
                            <label class="allergen-item">
                                <input type="checkbox" name="allergeni_0[]" value="<?php echo $all['idAllergene']; ?>">
                                <?php echo htmlspecialchars($all['nomeAllergene']); ?>
                            </label>
                        <?php endforeach; ?>
                    </div>

                    <label>Foto Prodotto</label>
                    <input type="file" name="foto_sapone[]" required accept="image/*">
                </div>
            </div>

            <button type="button" id="btn-aggiungi-sapone" class="btn-add">+ Aggiungi altro sapone al set</button>
            <button type="submit" class="btn-submit">Pubblica su SoapLab</button>
        </form>
    </div>

    <script>
    document.getElementById('btn-aggiungi-sapone').addEventListener('click', function () {
        const container = document.getElementById('saponi-container');
        const blocks = container.getElementsByClassName('sapone-block');
        const index = blocks.length;

        // Clonazione del primo blocco
        const newBlock = blocks[0].cloneNode(true);
        newBlock.querySelector('h4').innerText = 'Sapone ' + (index + 1);

        // Reset campi testo, numero, date, checkbox
        newBlock.querySelectorAll('input').forEach(input => {
            if (input.type === 'checkbox') {
                input.checked = false;
                input.name = `allergeni_${index}[]`;
            } else if (input.type !== 'file') {
                input.value = '';
            }
        });

        // Reset campo file (non resettabile con .value, va ricreato)
        newBlock.querySelectorAll('input[type="file"]').forEach(input => {
            const newInput = document.createElement('input');
            newInput.type = 'file';
            newInput.name = input.name;
            newInput.accept = input.accept;
            newInput.required = input.required;
            input.replaceWith(newInput);
        });

        // Reset textarea
        newBlock.querySelectorAll('textarea').forEach(t => t.value = '');

        // Reset select categoria
        newBlock.querySelectorAll('select').forEach(select => select.selectedIndex = 0);

        // Aggiunta tasto rimozione
        const removeBtn = document.createElement('button');
        removeBtn.type = 'button';
        removeBtn.innerText = 'Rimuovi questo sapone';
        removeBtn.className = 'btn-remove';
        removeBtn.onclick = function () {
            newBlock.remove();
            // Rinumera i titoli rimasti
            Array.from(container.getElementsByClassName('sapone-block')).forEach((b, i) => {
                b.querySelector('h4').innerText = 'Sapone ' + (i + 1);
            });
        };

        newBlock.appendChild(removeBtn);
        container.appendChild(newBlock);
    });
</script>

</body>

</html>