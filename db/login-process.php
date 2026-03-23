<?php
session_start();
require_once 'db.php'; // Assicurati che il percorso del file di connessione sia corretto

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 1. Recupera l'email e la password inviate dal form HTML
    $email = $_POST['email'];
    $password = $_POST['password'];

    // 2. Prepara la query cercando l'utente tramite la sua email
    // Nota: recuperiamo idUtente, non più id
    $sql = "SELECT idUtente, password, statoVendita FROM utenti WHERE email = ?";
    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        die("Errore di preparazione: " . $conn->error);
    }

    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    // 3. Verifica se l'email esiste nel database
    if ($user = $result->fetch_assoc()) {
        
        // 4. Verifica della password 
        // Se nel file di registrazione usi password_hash(), qui devi usare password_verify()
        // Se per ora stai salvando le password in chiaro, usa: if ($password === $user['password'])
        if (password_verify($password, $user['password'])) {
            
            // (Opzionale ma consigliato) Controlla se l'utente è stato bloccato dall'admin
            if ($user['statoVendita'] === 'bloccato') {
                // Se vuoi impedire del tutto il login agli utenti bloccati
                // header("Location: ../login.html?error=blocked");
                // exit();
            }

            // 5. Login effettuato con successo: salviamo l'idUtente in sessione
            $_SESSION['user_id'] = $user['idUtente'];
            
            // Reindirizziamo alla dashboard
            header("Location: ../dashboard.php");
            exit();
            
        } else {
            // Password errata
            header("Location: ../login.html?error=wrongpassword");
            exit();
        }
    } else {
        // L'email non è presente nel database
        header("Location: ../login.html?error=usernotfound");
        exit();
    }
} else {
    // Accesso diretto alla pagina non consentito
    header("Location: ../login.html");
    exit();
}
?>