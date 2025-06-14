<?php  
session_start(); // Avvia la sessione  

if(!isset($_GET['idGenere'])) {     
    $_GET["idGenere"] = 17; // Imposta un valore predefinito se non è stato passato 
}  

// Verifica se l'utente è loggato
$is_logged_in = isset($_SESSION['username']);
$user_role = $is_logged_in ? $_SESSION['user_role'] : null; // Recupera il ruolo dell'utente se loggato
?>  

<!DOCTYPE html>  
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
?>  

<html lang="it">  
<head>      
    <meta charset="UTF-8">      
    <meta name="viewport" content="width=device-width, initial-scale=1.0">      
    <title>Home - Valutazione Film</title>      
    <link rel="stylesheet" href="/css/style.css">  
</head> 

<body>      
    <div style="text-align: right; margin: 10px;">
        <?php if ($is_logged_in): ?>
            <span>Benvenuto, <?= htmlspecialchars($_SESSION['username']) ?>!</span>
            <a href="Endsession.php" class="logout-button">Logout</a>
        <?php else: ?>
            <a href="login.php" class="login-button">Login</a>
            <a href="register.php" class="register-button">Registrati</a>
        <?php endif; ?>
    </div>

    <h1>Benvenuto nella piattaforma di valutazione film</h1>      
    <h2>Esplora i film per categoria</h2>        

    <?php if ($is_logged_in && $user_role === 'admin'): ?>          
        <p><a href="gestione.php" style="color: red;">Gestione Film (Solo Admin)</a></p>      
    <?php endif; ?>        

    <!-- Barra di ricerca -->      
    <form action="#" method="GET">          
        <input type="text" name="search" placeholder="Cerca un film..." value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>">          
        <button type="submit">Cerca</button>      
    </form>        

    <form action="#" method="GET">         
        <label for="selectIdGenere">Seleziona un genere:</label><br>         
        <select oninput="submit()" name="idGenere" id="selectIdGenere">             
            <option value="17" <?php if ($_GET["idGenere"] == 17) echo "selected"; ?>>Tutti</option>             
            <?php                 
                $result = $conn->query("SELECT * FROM genere");                 
                while ($row = $result->fetch_assoc()) {                     
                    ?>                     
                    <option value="<?php echo $row['id']; ?>" <?php if ($row["id"] == $_GET["idGenere"]) echo "selected"; ?>>                         
                        <?php echo $row['nome_genere']; ?>                     
                    </option>                     
                    <?php                 
                }             
            ?>         
        </select>     
    </form>        

    <?php      
    // Barra di ricerca: recupera i film in base alla ricerca      
    if (isset($_GET['search']) && !empty($_GET['search'])) {          
        $search = $conn->real_escape_string($_GET['search']);          
        $sql_film = "SELECT film.id, film.titolo, film.regista, film.data_rilascio, film.lunghezza                        
                     FROM film                        
                     WHERE film.titolo LIKE '%$search%'";          
        $result_film = $conn->query($sql_film);            

        echo "<h3>Risultati della ricerca per: " . htmlspecialchars($search) . "</h3>";          
        echo "<div class='film-container'>";            

        if ($result_film->num_rows > 0) {              
            while ($row_film = $result_film->fetch_assoc()) {                  
                $film_id = $row_film['id'];                  
                $titolo = $row_film['titolo'];                  
                $regista = $row_film['regista'];                  
                $data_rilascio = $row_film['data_rilascio'];                  
                $lunghezza = $row_film['lunghezza'];                    

                // Calcola la valutazione media del film                  
                $sql_valutazione = "SELECT AVG(valutazione) AS media_valutazione                                       
                                   FROM recensione                                       
                                   WHERE film_id = $film_id";                  
                $result_valutazione = $conn->query($sql_valutazione);                  
                $media_valutazione = $result_valutazione->num_rows > 0 ? $result_valutazione->fetch_assoc()['media_valutazione'] : null;                    

                echo "<div class='film-card'>";                  
                echo "<h4><a href='dettaglifilm.php?id=$film_id' style='text-decoration: none; color: white;'>$titolo</a></h4>";                  
                echo "<p><strong>Regista:</strong> $regista</p>";                  
                echo "<p><strong>Data di rilascio:</strong> $data_rilascio</p>";                  
                echo "<p><strong>Lunghezza:</strong> $lunghezza minuti</p>";                  
                if ($media_valutazione !== null) {                      
                    echo "<p><strong>Valutazione media:</strong> " . number_format($media_valutazione, 1) . " / 5</p>";                  
                } else {                      
                    echo "<p><strong>Valutazione media:</strong> Nessuna valutazione</p>";                  
                }
                
                echo "</div>";              
            }          
        } else {              
            echo "<p>Nessun film trovato per la ricerca.</p>";          
        }            

        echo "</div>";      
    } else {          
        // Mostra i film per categoria          
        echo "<div class='film-container'>";            

        // Modifica la query per mostrare i film in base al genere selezionato         
        if ($_GET["idGenere"] == 17) {             
            // Mostra tutti i film             
            $sql_film = "SELECT film.id, film.titolo, film.regista, film.data_rilascio, film.lunghezza FROM film";         
        } else {             
            // Mostra i film del genere selezionato             
            $sql_film = "SELECT film.id, film.titolo, film.regista, film.data_rilascio, film.lunghezza                           
                         FROM film                           
                         INNER JOIN film_genere ON film.id = film_genere.film_id                           
                         WHERE film_genere.genere_id = " . $_GET["idGenere"];         
        }          

        $result_film = $conn->query($sql_film);            

        if ($result_film->num_rows > 0) {              
            while ($row_film = $result_film->fetch_assoc()) {                  
                $film_id = $row_film['id'];                  
                $titolo = $row_film['titolo'];                  
                $regista = $row_film['regista'];                  
                $data_rilascio = $row_film['data_rilascio'];                  
                $lunghezza = $row_film['lunghezza'];                    

                // Calcola la valutazione media del film                  
                $sql_valutazione = "SELECT AVG(valutazione) AS media_valutazione                                       
                                   FROM recensione                                       
                                   WHERE film_id = $film_id";                  
                $result_valutazione = $conn->query($sql_valutazione);                  
                $media_valutazione = $result_valutazione->num_rows > 0 ? $result_valutazione->fetch_assoc()['media_valutazione'] : null;                    

                echo "<div class='film-card'>";                  
                echo "<h4><a href='dettaglifilm.php?id=$film_id' style='text-decoration: none; color: black;'>$titolo</a></h4>";                  
                echo "<p><strong>Regista:</strong> $regista</p>";                  
                echo "<p><strong>Data di rilascio:</strong> $data_rilascio</p>";                  
                echo "<p><strong>Lunghezza:</strong> $lunghezza minuti</p>";                  
                if ($media_valutazione !== null) {                      
                    echo "<p><strong>Valutazione media:</strong> " . number_format($media_valutazione, 1) . " / 5</p>";                  
                } else {                      
                    echo "<p><strong>Valutazione media:</strong> Nessuna valutazione</p>";                  
                }
                
                echo "</div>";              
            }          
        } else {              
            echo "<p>Nessun film disponibile in questa categoria.</p>";          
        }            

        echo "</div>";      
    }        

    $conn->close();      
    ?>  
</body>  
</html>