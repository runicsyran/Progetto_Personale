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

// Gestione dell'inserimento
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titolo = $conn->real_escape_string($_POST['titolo']);
    $regista = $conn->real_escape_string($_POST['regista']);
    $data_rilascio = $conn->real_escape_string($_POST['data_rilascio']);
    $lunghezza = intval($_POST['lunghezza']);
    $attori = $_POST['attori']; // Array di attori
    $generi = $_POST['generi']; // Array di generi

    // Inserisci il film nella tabella `film`
    $sql_film = "INSERT INTO film (titolo, regista, data_rilascio, lunghezza) 
                 VALUES ('$titolo', '$regista', '$data_rilascio', $lunghezza)";
    if ($conn->query($sql_film) === TRUE) {
        $film_id = $conn->insert_id; // Ottieni l'ID del film appena inserito

        // Inserisci gli attori nella tabella `film_attore`
        if (!empty($attori)) {
            $stmt_attore = $conn->prepare("INSERT INTO film_attore (film_id, attore_id) VALUES (?, ?)");
            foreach ($attori as $attore_id) {
                $stmt_attore->bind_param('ii', $film_id, $attore_id);
                $stmt_attore->execute();
            }
            $stmt_attore->close();
        }

        // Inserisci i generi nella tabella `film_genere`
        if (!empty($generi)) {
            $stmt_genere = $conn->prepare("INSERT INTO film_genere (film_id, genere_id) VALUES (?, ?)");
            foreach ($generi as $genere_id) {
                $stmt_genere->bind_param('ii', $film_id, $genere_id);
                $stmt_genere->execute();
            }
            $stmt_genere->close();
        }

        // Mostra il popup di successo
        echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                const popup = new Popup({
                    id: 'success-popup',
                    title: 'Successo!',
                    content: 'Il film \"$titolo\" è stato inserito correttamente!',
                    showImmediately: true, // Mostra il popup immediatamente
                    loadCallback: function () {
                    popup.show(); // Mostra il popup solo dopo l'inizializzazione
                }
                });
            });
        </script>";
    } else {
        echo "Errore durante l'inserimento del film: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inserisci Film</title>
    <link rel="stylesheet" href="/css/style.css">
    <script src="/js/popup.js"></script>
</head>
<body>
    <h1>Inserisci un nuovo film</h1>
    <form action="inseriscifilm.php" method="POST">
        <label for="titolo">Titolo:</label>
        <input type="text" id="titolo" name="titolo" required><br><br>

        <label for="regista">Regista:</label>
        <input type="text" id="regista" name="regista" required><br><br>

        <label for="data_rilascio">Data di Rilascio:</label>
        <input type="date" id="data_rilascio" name="data_rilascio" required><br><br>

        <label for="lunghezza">Lunghezza (minuti):</label>
        <input type="number" id="lunghezza" name="lunghezza" required><br><br>

        <label for="attori">Seleziona Attori:</label><br>
        <select name="attori[]" id="attori" multiple required>
            <?php
            $result_attori = $conn->query("SELECT id, nome_attore, cognome_attore FROM attore");
            while ($row = $result_attori->fetch_assoc()) {
                echo "<option value='" . $row['id'] . "'>" . $row['nome_attore'] . " " . $row['cognome_attore'] . "</option>";
            }
            ?>
        </select><br><br>

        <label for="generi">Seleziona Generi:</label><br>
        <select name="generi[]" id="generi" multiple required>
            <?php
            $result_generi = $conn->query("SELECT id, nome_genere FROM genere");
            while ($row = $result_generi->fetch_assoc()) {
                echo "<option value='" . $row['id'] . "'>" . $row['nome_genere'] . "</option>";
            }
            ?>
        </select><br><br>

        <input type="submit" value="Inserisci Film">
    </form>
    <a href="gestione.php" class="gestione_button">Torna a Gestione</a>
</body>
</html>