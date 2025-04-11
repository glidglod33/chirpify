<?php
session_start();
require_once 'database_connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Profielgegevens ophalen
$stmt = $conn->prepare("SELECT username, email FROM accounts WHERE id = :id");
$stmt->bindParam(':id', $_SESSION['user_id']);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Als formulier verzonden is
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newUsername = $_POST['username'];
    $newEmail = $_POST['email'];

    // Optioneel wachtwoord wijzigen
    if (!empty($_POST['new_password'])) {
        $newPassword = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE accounts SET username = :username, email = :email, password = :password WHERE id = :id");
        $stmt->execute([
            'username' => $newUsername,
            'email' => $newEmail,
            'password' => $newPassword,
            'id' => $_SESSION['user_id']
        ]);
    } else {
        $stmt = $conn->prepare("UPDATE accounts SET username = :username, email = :email WHERE id = :id");
        $stmt->execute([
            'username' => $newUsername,
            'email' => $newEmail,
            'id' => $_SESSION['user_id']
        ]);
    }

    // Update sessie-username
    $_SESSION['username'] = $newUsername;

    header("Location: home.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Profiel bewerken</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<nav class="navbar">
    <div class="navbar-left">🐦 <span class="logo-text">Chirpify</span></div>
    <div class="navbar-right">
        <a href="home.php">Home</a>
        <a href="logout.php" class="logout-button">Logout</a>
    </div>
</nav>

<div class="content">
    <h2>Profiel bewerken</h2>
    <form method="POST">
        <label>Gebruikersnaam:</label><br>
        <input type="text" name="username" value="<?= htmlspecialchars($user['username']) ?>" required><br><br>

        <label>Email:</label><br>
        <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required><br><br>

        <label>Nieuw wachtwoord (optioneel):</label><br>
        <input type="password" name="new_password"><br><br>

        <button type="submit">Opslaan</button>
    </form>
</div>
</body>
</html>
