<?php
// Abilitazione errori per il debug
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once 'db/db.php';

// Controllo sessione utente
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

$user_id = $_SESSION['user_id'];

// 1. Recupero dati utente per l'header
$sql_user = "SELECT nome, cognome FROM utenti WHERE idUtente = ?";
$stmt_user = $conn->prepare($sql_user);
$stmt_user->bind_param("i", $user_id);
$stmt_user->execute();
$user = $stmt_user->get_result()->fetch_assoc();

if (!$user) {
    die("Errore: Utente non trovato.");
}

// 2. Recupero lista allergeni per il form (Requisito S1)
$sql_all = "SELECT idAllergene, nomeAllergene FROM allergeni ORDER BY nomeAllergene ASC";
$res_all = $conn->query($sql_all);
$allergeni = $res_all->fetch_all(MYSQLI_ASSOC);

// 3. Recupero categorie per il select
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
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f4f7f6; margin: 0; color: #333; }
        header { background: #fff; padding: 15px 40px; box-shadow: 0 2px 5px rgba(0,0,0,0.05); display: flex; justify-content: space-between; align-items: center; }
        .form-container { max-width: 700px; margin: 40px auto; background: white; padding: 35px; border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,0.05); }
        h2 { margin-bottom: 25px; color: #1f2937; border-bottom: 2px solid #28a745; display: inline-block; padding-bottom: 5px; }
        
        label { display: block; margin-top: 15px; font-weight: 600; color: #4b5563; font-size: 14px; }
        input, select, textarea { width: 100%; padding: 12px; margin-top: 8px; border: 1px solid #d1d5db; border-radius: 6px; box-sizing: border-box; font-size: 15px; }
        
        .sapone-block { background: #f9fafb; padding: 20px; border: 1px solid #e5e7eb; border-radius: 10px; margin-bottom: 20px; position: relative; }
        
        .allergeni-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(140px, 1fr)); gap: 10px; background: white; padding: 12px; border: 1px solid #d1d5db; border-radius: 6px; margin-top: 8px; }
        .allergen-item { font-size: 13px; font-weight: 400; display: flex; align-items: center; gap: 8px; cursor: pointer; }
        .allergen-item input { width: auto; margin: 0; }

        .btn-add { background: #007bff; color: white; border: none; padding: 12px; cursor: pointer; font-weight: bold; width: 100%; border-radius: 6px; margin-bottom: 20px; }
        .btn-submit { background: #28a745; color: white; border: none; padding: 16px; cursor: pointer; font-weight: bold; width: 100%; border-radius: 6px; font-size: 16px; }
        .btn-remove { background: #ef4444; color: white; border: none; padding: 8px; border-radius: 4px; cursor: pointer; margin-top: 15px; width: 100%; font-size: 13px; }
    </style>
</head>
<body>

<header>
    <h1 onclick="location.href='index.php'" style="cursor:pointer">SoapLab</h1>
    <div style="font-weight: 500;">👤 <?php echo htmlspecialchars($user['nome']); ?></div>
</header>

<div class="form-container">
    <h2>Nuova Inserzione</h2>
    <form action="db/vendita-process.php" method="POST" enctype="multipart/form-data">
        
        <label>Titolo Inserzione</label>
        <input type="text" name="titolo" required placeholder="Es: Kit Benessere Lavanda">

        <label>Descrizione Generale</label>
        <textarea name="descrizione" rows="3" required placeholder="Descrivi brevemente il contenuto del set..."></textarea>

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
    <?php foreach($categorie as $cat): ?>
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
document.getElementById('btn-aggiungi-sapone').addEventListener('click', function() {
    const container = document.getElementById('saponi-container');
    const blocks = container.getElementsByClassName('sapone-block');
    const index = blocks.length; 
    
    // Clonazione del primo blocco
    const newBlock = blocks[0].cloneNode(true);
    newBlock.querySelector('h4').innerText = 'Sapone ' + (index + 1);
    
    // Reset di tutti i campi input
    newBlock.querySelectorAll('input').forEach(input => {
        if(input.type === 'checkbox') {
            input.checked = false;
            // IMPORTANTE: Aggiorna l'indice per le checkbox degli allergeni
            input.name = `allergeni_${index}[]`;
        } else if(input.type !== 'file') {
            input.value = '';
        }
    });

    // Reset del select categoria
    newBlock.querySelectorAll('select').forEach(select => select.selectedIndex = 0);
    
    // Aggiunta tasto rimozione
    const removeBtn = document.createElement('button');
    removeBtn.type = 'button';
    removeBtn.innerText = 'Rimuovi questo sapone';
    removeBtn.className = 'btn-remove';
    removeBtn.onclick = function() { 
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