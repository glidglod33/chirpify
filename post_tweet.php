<?php
session_start(); // Start sessie
require_once 'database_connection.php'; // Verbind database

// Als gebruiker niet is ingelogd, stuur terug naar login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$content = $_POST['content']; // Haal tweet tekst op uit formulier
$imagePath = null; // Standaard: geen afbeelding

// Check of er een afbeelding is geüpload
if (!empty($_FILES['tweetImage']['name'])) {
    $uploadDir = "uploads/";
    $fileName = uniqid() . "_" . $_FILES['tweetImage']['name'];
    $imagePath = $uploadDir . $fileName;

    // Verplaats afbeelding naar de uploads map
    move_uploaded_file($_FILES['tweetImage']['tmp_name'], $imagePath);
}

// Sla tweet op in de database
$stmt = $conn->prepare("INSERT INTO tweets (user_id, content, image_path) VALUES (:userId, :content, :imagePath)");
$stmt->bindParam(':userId', $_SESSION['user_id']);
$stmt->bindParam(':content', $content);
$stmt->bindParam(':imagePath', $imagePath);
$stmt->execute();

// Ga terug naar dashboard
header("Location: home.php");
exit;
