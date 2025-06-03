<!DOCTYPE html>

<html lang="it">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrazione</title>
    <script src="/js/script.js"></script>
    <script src="/js/popup.js"></script>
    <link rel="stylesheet" href="/css/style.css">

</head>

<body>

    <h2>Registrazione</h2>

    <form action="registrazione.php" method="post" onsubmit="return validateForm(this)">
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
        $db_username = "root";
        $db_password = "";
        $dbname = "my_michelangelocuccui";



        // Crea la connessione al database
        $conn = new mysqli($servername, $db_username, $db_password, $dbname);



        // Controlla connessione al database
        if ($conn->connect_error) {
            die("Connessione fallita: " . $conn->connect_error);
        }

        // Prendi i dati dal form
        $user = $_POST['username'];
        $pass = $_POST['password'];
        $email = $_POST['email'];

        // Controlla se l'username o l'email esistono già
        $check_sql = "SELECT username, email FROM users WHERE username = ? OR email = ?";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();

        

        if ($check_result->num_rows > 0) {

            ?>
            <h3 style="color: red;">Username o email già esistenti</h3>
            <script>
                showPopupErrorRegistration();
            </script>
            <?php

        } else {

            // Hash della password
            $hashed_password = password_hash($pass, PASSWORD_DEFAULT);
            

            // Inserisci i dati nel database con password hashata
            $sql = "INSERT INTO users (username, password, email) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);



            if ($stmt->execute()) {

                ?>
                <h3>Registrazione avvenuta con successo</h3>
                <script>
                    showPopupSuccessRegistration();
                </script>
                <meta http-equiv="refresh" content="3; url=https://michelangelocuccui.altervista.org///login.php">
                <?php
                exit();
            } else {
                ?>
                <script>
                    showPopupErrorRegistration();
                </script>
                <?php
            }

            $stmt->close();
        }

        $check_stmt->close();
        $conn->close();
    }
    ?>
    <a href="index.php" class="index_button">Torna Pagina Principale</a>
</body>

</html>