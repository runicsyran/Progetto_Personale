<!DOCTYPE html>
<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Connessione al database
    $conn = new mysqli('localhost', 'root', '', 'my_michelangelocuccui');

    // Controllo connessione
    if ($conn->connect_error) {
        die("Connessione fallita: " . $conn->connect_error);
    }

    // Query per verificare le credenziali
    $sql = "SELECT * FROM users WHERE username = ? AND password = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ss', $username, ($password));
    $stmt->execute();
    $result = $stmt->get_result();

    // Controllo credenziali
    if ($result->num_rows > 0) {
        echo "Login effettuato con successo!";
        ?><meta http-equiv="refresh" content="0; url=https://michelangelocuccui.altervista.org/Ludoteca2/home.php"><?php
    } else {
        echo "Nome utente o password errati / non presenti";
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
    <link rel="stylesheet" href="css/style.css">
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
</body>
</html>