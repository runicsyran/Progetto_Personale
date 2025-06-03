<?php
session_start();

// Connessione al database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "my_michelangelocuccui";

$conn = new mysqli($servername, $username, $password, $dbname);

// Controlla connessione
if ($conn->connect_error) {
    die("Connessione fallita: " . $conn->connect_error);
}

// Gestione inserimento recensione
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['user_id'])) {
    if (isset($_POST['valutazione']) && isset($_POST['commento']) && isset($_POST['film_id'])) {
        $film_id = intval($_POST['film_id']);
        $_GET["id"] = $film_id;
        $valutazione = intval($_POST['valutazione']);
        $commento = trim($_POST['commento']);
        
        if ($valutazione >= 1 && $valutazione <= 5 && !empty($commento)) {
            // Controlla se l'utente ha già recensito questo film (opzionale)
            $check_sql = "SELECT id FROM recensione WHERE film_id = ? LIMIT 1";
            $check_stmt = $conn->prepare($check_sql);
            
            if ($check_stmt === false) {
                echo "<div class='error-message'>Errore nella preparazione del controllo: " . $conn->error . "</div>";
            } else {
                $check_stmt->bind_param("i", $film_id);
                $check_stmt->execute();
                $check_result = $check_stmt->get_result();
                $check_stmt->close();
                
                // Inserisci la nuova recensione
                $insert_sql = "INSERT INTO recensione (film_id, valutazione, commento) VALUES (?, ?, ?)";
                $insert_stmt = $conn->prepare($insert_sql);
                
                if ($insert_stmt === false) {
                    $error_message = "Errore nella preparazione della query: " . $conn->error;
                } else {
                    $insert_stmt->bind_param("iis", $film_id, $valutazione, $commento);
                    
                    if ($insert_stmt->execute()) {
                        $success_message = "Recensione inserita con successo!";
                    } else {
                        $error_message = "Errore nell'inserimento della recensione: " . $insert_stmt->error;
                    }
                    $insert_stmt->close();
                }
            }
        } else {
            $error_message = "Valutazione e commento sono obbligatori. La valutazione deve essere tra 1 e 5.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dettagli Film</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
    <?php
    // Mostra il pulsante logout solo se l'utente è loggato
    if (isset($_SESSION['user_id'])) {
        echo '<a href="Endsession.php" class="logout-button">Logout</a>';
    } else {
        echo '<a href="login.php" class="login-button">Login</a>';
    }
    ?>
    
    <div style="padding: 3vw;">
        <?php

        // Recupera l'ID del film dalla query string
        if (isset($_GET['id']) && is_numeric($_GET['id'])) {
            $film_id = intval($_GET['id']);
            
            // Recupera i dettagli del film
            $sql_film = "SELECT * FROM film WHERE id = ?";
            $stmt_film = $conn->prepare($sql_film);
            $stmt_film->bind_param("i", $film_id);
            $stmt_film->execute();
            $result_film = $stmt_film->get_result();

            if ($film = $result_film->fetch_assoc()) {
                echo "<h1>" . htmlspecialchars($film['titolo']) . "</h1>";
                echo "<p><strong>Regista:</strong> " . htmlspecialchars($film['regista']) . "</p>";
                echo "<p><strong>Data di rilascio:</strong> " . htmlspecialchars($film['data_rilascio']) . "</p>";
                echo "<p><strong>Lunghezza:</strong> " . htmlspecialchars($film['lunghezza']) . " minuti</p>";
                echo "<p><strong>Descrizione:</strong> " . htmlspecialchars($film['descrizione']) . "</p>";

                // Form per inserire recensione (per tutti gli utenti loggati)
                if (isset($_SESSION['user_id']) || isset($_SESSION['id']) || isset($_SESSION['username'])) {
                    echo "<h3>Aggiungi la tua recensione</h3>";
                    echo "<form method='POST' action='/dettaglifilm.php' style='align-items: center; gap: 10px; margin: 20px 0;'>";
                    echo "<input type='hidden' name='film_id' value='" . $film_id . "'>";
                    echo "<input type='text' name='commento' placeholder='Scrivi il tuo commento...' required style='flex: 1; padding: 8px; border: 1px solid #ccc;'>";
                    
                    echo "<select name='valutazione' required style='padding: 8px; border: 1px solid #ccc;'>";
                    echo "<option value=''>Voto</option>";
                    for ($i = 1; $i <= 5; $i++) {
                        echo "<option value='$i'>$i</option>";
                    }
                    echo "</select>";
                    
                    echo "<button type='submit' style='padding: 8px 15px; background-color: #007bff; color: white; border: none; cursor: pointer;'>Invio</button>";
                    echo "</form>";
                } else {
                    echo "<p>Per lasciare una recensione, <a href='login.php'>effettua il login</a> o <a href='register.php'>registrati</a>.</p>";
                }

                // Recupera le valutazioni e i commenti
                $sql_recensioni = "SELECT valutazione, commento 
                                FROM recensione 
                                WHERE film_id = ?";
                $stmt_recensioni = $conn->prepare($sql_recensioni);
                
                if ($stmt_recensioni === false) {
                    echo "<p>Errore nella preparazione della query recensioni: " . $conn->error . "</p>";
                } else {
                    $stmt_recensioni->bind_param("i", $film_id);
                    $stmt_recensioni->execute();
                    $result_recensioni = $stmt_recensioni->get_result();

                    echo "<h2>Recensioni</h2>";
                    if (isset($result_recensioni) && $result_recensioni->num_rows > 0) {
                        while ($recensione = $result_recensioni->fetch_assoc()) {
                            echo "<div class='recensione'>";
                            echo "<p><strong>Valutazione:</strong> ";
                            
                            // Mostra stelle invece di numero
                            for ($i = 1; $i <= 5; $i++) {
                                if ($i <= $recensione['valutazione']) {
                                    echo "★";
                                } else {
                                    echo "☆";
                                }
                            }
                            echo " (" . htmlspecialchars($recensione['valutazione']) . "/5)</p>";
                            
                            echo "<p><strong>Commento:</strong> " . htmlspecialchars($recensione['commento']) . "</p>";
                            echo "</div>";
                        }
                    } else {
                        echo "<p>Nessuna recensione disponibile per questo film.</p>";
                    }
                    
                    $stmt_recensioni->close();
                }
            } else {
                echo "<p>Film non trovato.</p>";
            }
            
            $stmt_film->close();
        } else {
            echo "<p>ID film non valido.</p>";
        }

        $conn->close();
        ?>
        
        <a href="home.php" class="home_button">Torna alla Home</a>
    </div>
</body>
</html>