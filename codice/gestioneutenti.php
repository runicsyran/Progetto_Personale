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

// Gestione della modifica del ruolo utente
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_id'], $_POST['user_role'])) {
    $user_id = intval($_POST['user_id']);
    $user_role = $_POST['user_role'] === 'admin' ? 'admin' : 'user'; // Valida il ruolo

    $sql_update = "UPDATE users SET user_role = ? WHERE id = ?";
    $stmt = $conn->prepare($sql_update);
    $stmt->bind_param('si', $user_role, $user_id);

    if ($stmt->execute()) {
        echo "<p style='color: green;'>Ruolo aggiornato con successo!</p>";
    } else {
        echo "<p style='color: red;'>Errore durante l'aggiornamento del ruolo: " . $conn->error . "</p>";
    }

    $stmt->close();
}

// Recupera tutti gli utenti
$sql_users = "SELECT id, username, email, user_role FROM users";
$result_users = $conn->query($sql_users);
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestione Utenti</title>
    <link rel="stylesheet" href="./style.css">
</head>
<body>
    <h1>Gestione Utenti</h1>
    <p>Modifica il ruolo degli utenti tra <strong>admin</strong> e <strong>user</strong>.</p>

    <table border="1" cellpadding="10" cellspacing="0">
        <thead>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Email</th>
                <th>Ruolo</th>
                <th>Azione</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result_users->num_rows > 0): ?>
                <?php while ($user = $result_users->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($user['id']) ?></td>
                        <td><?= htmlspecialchars($user['username']) ?></td>
                        <td><?= htmlspecialchars($user['email']) ?></td>
                        <td><?= htmlspecialchars($user['user_role']) ?></td>
                        <td>
                            <form action="gestioneutenti.php" method="POST" style="display: inline;">
                                <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                                <select name="user_role">
                                    <option value="user" <?= $user['user_role'] === 'user' ? 'selected' : '' ?>>User</option>
                                    <option value="admin" <?= $user['user_role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
                                </select>
                                <button type="submit">Aggiorna</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5">Nessun utente trovato.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
    <a href="gestione.php" style="display: inline-block; margin-top: 20px; padding: 10px 20px; background-color: #007BFF; color: white; text-decoration: none; border-radius: 5px;">Torna a Gestione</a>
    <?php $conn->close(); ?>
</body>
</html>