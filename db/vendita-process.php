<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once 'db.php'; 

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.html");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $idUtente = $_SESSION['user_id'];
    
    // Raccogliamo i dati dal Form
    $titolo = trim($_POST['titolo']);
    $prezzo = floatval($_POST['prezzo']);
    $peso = intval($_POST['peso']);
    
    $nome_sapone = trim($_POST['nome_sapone']);
    $categoria = intval($_POST['categoria']); 
    $pelle = trim($_POST['pelle']);
    $codice_bio = trim($_POST['codice_bio']);
    
    // Iniziamo la Transazione (Se una query fallisce, nessuna viene salvata)
    $conn->begin_transaction();

    try {
        //Gestione Certificazione BIO (Opzionale)
        $idCertificazione = null;
        if (!empty($codice_bio)) {
            $sql_bio = "INSERT INTO certificazioni_bio (codiceStandard) VALUES (?)";
            $stmt_bio = $conn->prepare($sql_bio);
            $stmt_bio->bind_param("s", $codice_bio);
            $stmt_bio->execute();
            $idCertificazione = $stmt_bio->insert_id; 
            $stmt_bio->close();
        }

        //Creazione dell'Inserzione
        $sql_ins = "INSERT INTO inserzioni (idUtente, titolo, prezzoTotale, pesoComplessivo) VALUES (?, ?, ?, ?)";
        $stmt_ins = $conn->prepare($sql_ins);
        $stmt_ins->bind_param("isdi", $idUtente, $titolo, $prezzo, $peso);
        $stmt_ins->execute();
        $idInserzione = $stmt_ins->insert_id; 
        $stmt_ins->close();

        //Creazione del Sapone
        $sql_sap = "INSERT INTO saponi (idInserzione, idCategoria, idCertificazione, nomeCommerciale, tipoPelleConsigliata) VALUES (?, ?, ?, ?, ?)";
        $stmt_sap = $conn->prepare($sql_sap);
        $stmt_sap->bind_param("iiiss", $idInserzione, $categoria, $idCertificazione, $nome_sapone, $pelle);
        $stmt_sap->execute();
        $idSapone = $stmt_sap->insert_id; 
        $stmt_sap->close();

        // Gestione dell'Immagine
        if (isset($_FILES['foto_sapone']) && $_FILES['foto_sapone']['error'] === UPLOAD_ERR_OK) {
            
            $upload_dir = '../uploads/';
            
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }

            $file_name = time() . '_' . basename($_FILES['foto_sapone']['name']);
            $target_path = $upload_dir . $file_name;

            if (move_uploaded_file($_FILES['foto_sapone']['tmp_name'], $target_path)) {
                
                $percorso_db = 'uploads/' . $file_name;
                
                $sql_img = "INSERT INTO immagini (idSapone, percorso) VALUES (?, ?)";
                $stmt_img = $conn->prepare($sql_img);
                $stmt_img->bind_param("is", $idSapone, $percorso_db);
                $stmt_img->execute();
                $stmt_img->close();

            } else {
                throw new Exception("Impossibile spostare il file caricato sul server.");
            }
        } else {
            throw new Exception("Errore nel caricamento dell'immagine o file non selezionato.");
        }

        $conn->commit();
        
        header("Location: ../dashboard.php?msg=sapone_aggiunto");
        exit();

    } catch (Exception $e) {
        $conn->rollback();
        die("<h1>ERRORE DURANTE LA PUBBLICAZIONE</h1><p>" . $e->getMessage() . "</p><p><a href='../vendita-sapone.php'>Torna indietro</a></p>");
    }

} else {
    header("Location: ../vendita-sapone.php");
    exit();
}
?>