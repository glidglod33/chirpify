<?php
require_once 'database_connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['gebruikersnaam'];
    $password = $_POST['wachtwoord'];

    if (empty($username) || empty($password)) {
        header("Location: register.php?error=empty_fields");
        exit;
    }

    // Check of gebruikersnaam al bestaat
    $checkStmt = $conn->prepare("SELECT id FROM accounts WHERE username = :username");
    $checkStmt->bindParam(':username', $username);
    $checkStmt->execute();

    if ($checkStmt->rowCount() > 0) {
        header("Location: register.php?error=username_taken");
        exit;
    }

    // Maak account aan
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO accounts (username, password) VALUES (:username, :password)");
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':password', $hashedPassword);

    if ($stmt->execute()) {
        header("Location: login.php?message=registered");
        exit;
    } else {
        header("Location: register.php?error=unknown");
        exit;
    }
}
?>
