<!DOCTYPE html>

<?php

session_start(); // Avvia la sessione



if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $username = $_POST['username'];

    $password = $_POST['password'];



    // Connessione al database

    $conn = new mysqli('localhost', 'root', '', 'my_michelangelocuccui');



    // Controllo connessione

    if ($conn->connect_error) {

        die("Connessione fallita: " . $conn->connect_error);

    }



    // Query per verificare le credenziali - recuperiamo anche la password hashata

    $sql = "SELECT username, password, user_role FROM users WHERE username = ?";

    $stmt = $conn->prepare($sql);

    $stmt->bind_param('s', $username);

    $stmt->execute();

    $result = $stmt->get_result();



    // Controllo credenziali

    if ($result->num_rows > 0) {

        $user = $result->fetch_assoc();

        

        // Verifica la password usando password_verify()

        if (password_verify($password, $user['password'])) {

            $_SESSION['username'] = $user['username'];

            $_SESSION['user_role'] = $user['user_role']; // Salva il ruolo nella sessione



            echo "Login effettuato con successo!";

            ?><meta http-equiv="refresh" content="0; url=home.php"><?php

        } else {

            echo "Nome utente o password errati";

        }

    } else {

        echo "Nome utente o password errati";

    }



    $stmt->close();

    $conn->close();

}

?>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login Utente</title>

    <link rel="stylesheet" href="/css/style.css">

</head>

<body>

    <form action="" method="post">

        <label for="username">Nome Utente:</label>

        <input type="text" id="username" name="username" required>

        <br>

        <label for="password">Password:</label>

        <input type="password" id="password" name="password" required>

        <br>

        <input type="submit" value="Login">

        <p>Non sei registrato? <a href="registrazione.php">Registrati qui</a></p>

    </form>
    <a href="index.php" class="index_button">Torna Pagina Principale</a>
</body>

</html>