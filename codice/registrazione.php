<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrazione</title>
    <script src="js/scripts.js"></script>
    <script src="js/Popup.js"></script>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <h2>Registrazione</h2>
    <form action="registrazione.php" method="post" onsubmit="return validatePassword(THIS)">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required><br><br>
        
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br><br>
        
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br><br>
        
        <input type="submit" value="Registrati">
    </form>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "my_michelangelocuccui";

        // Crea la connessione al database
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Controlla connessione al database
        if ($conn->connect_error) {
            die("Connessione fallita: " . $conn->connect_error);
        }

        // Prendi i dati dal form
        $user = $_POST['username'];
        $pass = ($_POST['password']);
        $email = $_POST['email'];

        // Inserisci i dati nel database
        $sql = "INSERT INTO users (username, password, email) VALUES ('$user', '$pass', '$email')";

        if ($conn->query($sql) === TRUE) {
            echo "Registrazione avvenuta con successo";
            ?><meta http-equiv="refresh" content="0; url=https://michelangelocuccui.altervista.org/Ludoteca2/login.php"><?php       
            exit();
        } else {
            echo "Errore: " . $sql . "<br>" . $conn->error;
        }

        $conn->close();
    }
    ?>
</body>
</html>