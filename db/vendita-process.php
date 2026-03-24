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
    
    // 1. DATI DELL'INSERZIONE
    $titolo = trim($_POST['titolo']);
    $prezzo = floatval($_POST['prezzo']);
    $peso = intval($_POST['peso']);
    
    // 2. DATI DEI SAPONI
    $nomi_saponi = $_POST['nome_sapone']; 
    $categorie = $_POST['categoria'];
    $pelli = $_POST['pelle'];
    $codici_bio = $_POST['codice_bio'];
    $date_bio = $_POST['data_bio'];
    
    $conn->begin_transaction();

    try {
        // --- CREAZIONE DELL'INSERZIONE ---
        $sql_ins = "INSERT INTO inserzioni (idUtente, titolo, prezzoTotale, pesoComplessivo) VALUES (?, ?, ?, ?)";
        $stmt_ins = $conn->prepare($sql_ins);
        $stmt_ins->bind_param("isdi", $idUtente, $titolo, $prezzo, $peso);
        $stmt_ins->execute();
        $idInserzione = $stmt_ins->insert_id; 
        $stmt_ins->close();

        // --- CICLO PER OGNI SAPONE ---
        foreach ($nomi_saponi as $index => $nome_raw) {
            $nome_sapone = trim($nome_raw);
            $idCat = intval($categorie[$index]);
            $tipoPelle = trim($pelli[$index]);
            $codBio = trim($codici_bio[$index]);
            $dataVscadenza = !empty($date_bio[$index]) ? $date_bio[$index] : null;

            // Gestione Certificazione BIO
            $idCertificazione = null;
            if (!empty($codBio) && !empty($dataVscadenza)) {
                $sql_bio = "INSERT INTO certificazioni_bio (codiceStandard, validita) VALUES (?, ?)";
                $stmt_bio = $conn->prepare($sql_bio);
                $stmt_bio->bind_param("ss", $codBio, $dataVscadenza);
                $stmt_bio->execute();
                $idCertificazione = $stmt_bio->insert_id;
                $stmt_bio->close();
            }

            // Creazione del Sapone
            $sql_sap = "INSERT INTO saponi (idInserzione, idCategoria, idCertificazione, nomeCommerciale, tipoPelleConsigliata) VALUES (?, ?, ?, ?, ?)";
            $stmt_sap = $conn->prepare($sql_sap);
            $stmt_sap->bind_param("iiiss", $idInserzione, $idCat, $idCertificazione, $nome_sapone, $tipoPelle);
            $stmt_sap->execute();
            $idSapone = $stmt_sap->insert_id;
            $stmt_sap->close();

            // --- NUOVO: GESTIONE ALLERGENI (Relazione PRESENTA) ---
            // Recuperiamo l'array dinamico basato sull'indice del sapone (allergeni_0, allergeni_1...)
            $chiave_allergeni = "allergeni_" . $index;
            if (isset($_POST[$chiave_allergeni]) && is_array($_POST[$chiave_allergeni])) {
                foreach ($_POST[$chiave_allergeni] as $idAllergene) {
                    $sql_all_link = "INSERT INTO sapone_presenta_allergene (idSapone, idAllergene) VALUES (?, ?)";
                    $stmt_all = $conn->prepare($sql_all_link);
                    $stmt_all->bind_param("ii", $idSapone, $idAllergene);
                    $stmt_all->execute();
                    $stmt_all->close();
                }
            }

            // Gestione Immagine
            if (isset($_FILES['foto_sapone']['name'][$index]) && $_FILES['foto_sapone']['error'][$index] === UPLOAD_ERR_OK) {
                $upload_dir = '../uploads/';
                $file_name = time() . '_' . $index . '_' . basename($_FILES['foto_sapone']['name'][$index]);
                $target_path = $upload_dir . $file_name;

                if (move_uploaded_file($_FILES['foto_sapone']['tmp_name'][$index], $target_path)) {
                    $percorso_db = 'uploads/' . $file_name;
                    $sql_img = "INSERT INTO immagini (idSapone, percorso) VALUES (?, ?)";
                    $stmt_img = $conn->prepare($sql_img);
                    $stmt_img->bind_param("is", $idSapone, $percorso_db);
                    $stmt_img->execute();
                    $stmt_img->close();
                }
            }
        }

        $conn->commit();
        header("Location: ../dashboard.php?msg=sapone_aggiunto");
        exit();

    } catch (Exception $e) {
        $conn->rollback();
        die("<h1>ERRORE PUBBLICAZIONE</h1><p>" . $e->getMessage() . "</p>");
    }
}