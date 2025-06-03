<?php

session_start();

// Controlla se l'utente è loggato e se è un admin

if (!isset($_SESSION['username']) || $_SESSION['user_role'] !== 'admin') {
    die("Accesso negato. Solo gli amministratori possono accedere a questa pagina.");
}



// Connessione al database

$conn = new mysqli('localhost', 'root', '', 'my_michelangelocuccui');
if ($conn->connect_error) {
    die("Connessione fallita: " . $conn->connect_error);
}

?>

<!DOCTYPE html>

<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestione - Admin</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<a href="Endsession.php" class="logout-button">Logout</a>
<body>

    <h1>Benvenuto, Admin!</h1>
    <p>Benvenuto nella pagina di gestione. Da qui puoi gestire i film e gli utenti.</p>



    <div>
        <button onclick="location.href='inseriscifilm.php'" style="padding: 10px 20px; margin: 10px;">Inserisci un film</button>
        <button onclick="location.href='gestioneutenti.php'" style="padding: 10px 20px; margin: 10px;">Gestisci utenti</button>
    </div>
    <a href="home.php" class="home_button">Torna a Gestione</a>
</body>
</html>