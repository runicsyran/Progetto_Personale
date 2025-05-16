<?php
session_start();
?>
<!DOCTYPE html>

<html lang="it">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Dettagli Film</title>

    <link rel="stylesheet" href="../css/style.css">

</head>
<a href="Endsession.php" class="logout-button">Logout</a>
<body>
    <div style="padding: 3vw;">

        <?php
    
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

        // Recupera user_role e user_id dalla sessione
        
        $user_id = $_SESSION['user_id'] ?? null;
        $user_role = $_SESSION['user_role'] ?? 'guest';
;
        
        // Recupera l'ID del film dalla query string

        if (isset($_GET['id']) && is_numeric($_GET['id'])) {

            $film_id = intval($_GET['id']);

            

            // Recupera i dettagli del film

            $sql_film = "SELECT * 

                        FROM film 

                        WHERE id = $film_id";

            $result_film = $conn->query($sql_film);


            if ($film = $result_film->fetch_assoc()) {

                echo "<h1>" . htmlspecialchars($film['titolo']) . "</h1>";

                echo "<p><strong>Regista:</strong> " . htmlspecialchars($film['regista']) . "</p>";

                echo "<p><strong>Data di rilascio:</strong> " . htmlspecialchars($film['data_rilascio']) . "</p>";

                echo "<p><strong>Lunghezza:</strong> " . htmlspecialchars($film['lunghezza']) . " minuti</p>";

                echo "<p><strong>Descrizione:</strong> " . htmlspecialchars($film['descrizione']) . "</p>";



                // Recupera le valutazioni e i commenti

                $sql_recensioni = "SELECT valutazione, commento 

                                FROM recensione 

                                WHERE film_id = $film_id";

                $result_recensioni = $conn->query($sql_recensioni);



                echo "<h2>Recensioni</h2>";

                if ($result_recensioni->num_rows > 0) {

                    while ($recensione = $result_recensioni->fetch_assoc()) {

                        echo "<div class='recensione'>";

                        echo "<p><strong>Valutazione:</strong> " . htmlspecialchars($recensione['valutazione']) . " / 5</p>";

                        echo "<p><strong>Commento:</strong> " . htmlspecialchars($recensione['commento']) . "</p>";

                        echo "</div>";

                    }

                } else {

                    echo "<p>Nessuna recensione disponibile per questo film.</p>";

                }

            } else {

                echo "<p>Film non trovato.</p>";

            }

        } else {

            echo "<p>ID film non valido.</p>";

        }

        // Gestione inserimento commento
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['commento'], $_POST['valutazione']) && $user_role != 'guest') {
            $commento = $conn->real_escape_string($_POST['commento']);
            $valutazione = intval($_POST['valutazione']);
            if ($valutazione >= 1 && $valutazione <= 5 && !empty($commento)) {
                $sql_inserisci = "INSERT INTO recensione (film_id, user_id, valutazione, commento) VALUES ($film_id, $user_id, $valutazione, '$commento')";
                $conn->query($sql_inserisci);
                // Refresh per mostrare subito il commento
                echo "<meta http-equiv='refresh' content='0'>";
            }
        }

        // Mostra il form solo se l'utente è loggato e non guest
        if ($user_role != 'guest' && isset($user_id)) {
            echo '
            <span id="comment-form">
                <form method="POST" style="margin: 60px 0; display: flex; gap: 20px; align-items: center;">
                    <textarea name="commento" placeholder="Scrivi un commento..." required style="flex:4; resize:vertical; min-height:40px;"></textarea>
                    <select name="valutazione" required style="flex:1;">
                        <option value="">Voto</option>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                    </select>
                    <button type="submit" style="flex:2;">Invia</button>
                </form>
            </span>
            ';
        } else {
            echo "<p style='color:orange; text-align:center;'>Devi essere loggato per lasciare una recensione.</p>";
        }

        $conn->close();

        ?>
        <a href="home.php" class="home_button">Torna alla Home</a>
    </div>
</body>

</html>