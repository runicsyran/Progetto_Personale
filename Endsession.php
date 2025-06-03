<?php
session_start();  // Avvia la sessione
session_unset();  // Rimuove tutte le variabili di sessione
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="refresh" content="3; url=index.php">
    <title>Logout</title>
    <link rel="stylesheet" href="/css/style.css"> <!-- Puoi aggiungere il tuo file CSS qui -->
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            margin-top: 20%;
        }

        .spinner {
            margin: 20px auto;
            width: 50px;
            height: 50px;
            border: 4px solid rgba(255, 255, 255, 0.1);
            border-top: 4px solid var(--neon-yellow);
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <header>
        <h1>Logout in corso...</h1>
    </header>
    <main>
        <div class="spinner"></div>
        <p>Verrai reindirizzato alla pagina principale tra pochi secondi.</p>
    </main>
    <footer>
        <p>Grazie per aver utilizzato il nostro servizio!</p>
    </footer>
</body>
</html>
