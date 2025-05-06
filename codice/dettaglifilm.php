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
    <div style="width: 93vw;">

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



        $conn->close();

        ?>
        <a href="home.php" class="home_button">Torna alla Home</a>
    </div>
</body>

</html>