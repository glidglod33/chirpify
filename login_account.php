<?php
session_start();
require_once 'database_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['gebruikersnaam'];
    $password = $_POST['wachtwoord'];

    if (!empty($username) && !empty($password)) {
        $stmt = $conn->prepare("SELECT * FROM accounts WHERE username = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['is_admin'] = $user['is_admin'];

            header("Location: home.php");
            exit();
        } else {
            echo "Ongeldige gebruikersnaam of wachtwoord.";
        }
    } else {
        echo "Vul alle velden in.";
    }
}
?>
