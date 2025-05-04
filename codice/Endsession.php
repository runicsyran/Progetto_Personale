<?php
session_start();
session_unset();
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="refresh" content="5; url=index.php">
    <title>Logout</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            margin-top: 20%;
        }
        .spinner {
            margin: 20px auto;
            width: 40px;
            height: 40px;
            border: 4px solid rgba(0, 0, 0, 0.1);
            border-top: 4px solidrgb(113, 113, 78);
            border-radius: 50%;
            animation: spin 1s linear infinite;            <?php
            session_start();
            session_unset();
            ?>
            <!DOCTYPE html>
            <html lang="it">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <meta http-equiv="refresh" content="5; url=index.php">
                <title>Logout</title>
                <link rel="stylesheet" href="../css/style.css">
                <style>
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
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <h1>Stiamo svolgendo il logout...</h1>
    <div class="spinner"></div>
</body>
</html>