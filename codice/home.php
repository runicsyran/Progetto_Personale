<!DOCTYPE html>
<?php
session_start();
if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin') {
    echo '<div style="position: absolute; top: 10px; right: 10px;">';
    echo '<a href="gestione.php" style="text-decoration: none; color: blue;">Gestione</a>';
    echo '</div>';
}
?>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - Valutazione Film</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <h1>Benvenuto nella piattaforma di valutazione film</h1>
    <h2>Esplora i film per categoria</h2>

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

    // Recupera i generi dalla tabella 'genere'
    $sql_genere = "SELECT id, nome_genere FROM genere";
    $result_genere = $conn->query($sql_genere);

    if ($result_genere->num_rows > 0) {
        while ($row_genere = $result_genere->fetch_assoc()) {
            $genere_id = $row_genere['id'];
            $nome_genere = $row_genere['nome_genere'];

            echo "<h3>$nome_genere</h3>";
            echo "<div class='film-container'>";

            // Recupera i film associati al genere corrente
            $sql_film = "SELECT film.id, film.titolo, film.regista, film.data_rilascio, film.lunghezza 
                         FROM film 
                         INNER JOIN film_genere ON film.id = film_genere.film_id 
                         WHERE film_genere.genere_id = $genere_id";
            $result_film = $conn->query($sql_film);

            if ($result_film->num_rows > 0) {
                while ($row_film = $result_film->fetch_assoc()) {
                    $titolo = $row_film['titolo'];
                    $regista = $row_film['regista'];
                    $data_rilascio = $row_film['data_rilascio'];
                    $lunghezza = $row_film['lunghezza'];

                    echo "<div class='film-card'>";
                    echo "<h4>$titolo</h4>";
                    echo "<p><strong>Regista:</strong> $regista</p>";
                    echo "<p><strong>Data di rilascio:</strong> $data_rilascio</p>";
                    echo "<p><strong>Lunghezza:</strong> $lunghezza minuti</p>";
                    echo "</div>";
                }
            } else {
                echo "<p>Nessun film disponibile in questa categoria.</p>";
            }

            echo "</div>";
        }
    } else {
        echo "<p>Nessuna categoria trovata.</p>";
    }

    $conn->close();
    ?>
</body>
</html>