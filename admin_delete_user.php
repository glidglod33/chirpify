<?php
session_start();
require_once 'database_connection.php';

if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    header("Location: home.php");
    exit();
}

if (isset($_POST['user_id'])) {
    $userId = $_POST['user_id'];

    // Stap 1: Haal eerst alle tweets van deze gebruiker op
    $stmtTweets = $conn->prepare("SELECT id FROM tweets WHERE user_id = :user_id");
    $stmtTweets->bindParam(':user_id', $userId);
    $stmtTweets->execute();
    $tweets = $stmtTweets->fetchAll(PDO::FETCH_ASSOC);

    // Stap 2: Verwijder likes van anderen op deze tweets
    foreach ($tweets as $tweet) {
        $stmt = $conn->prepare("DELETE FROM likes WHERE tweet_id = :tweet_id");
        $stmt->bindParam(':tweet_id', $tweet['id']);
        $stmt->execute();
    }

    // Stap 3: Verwijder tweets van gebruiker
    $stmt = $conn->prepare("DELETE FROM tweets WHERE user_id = :user_id");
    $stmt->bindParam(':user_id', $userId);
    $stmt->execute();

    // Stap 4: Verwijder likes die deze gebruiker zelf heeft gedaan
    $stmt = $conn->prepare("DELETE FROM likes WHERE user_id = :user_id");
    $stmt->bindParam(':user_id', $userId);
    $stmt->execute();

    // Stap 5: Verwijder gebruiker
    $stmt = $conn->prepare("DELETE FROM accounts WHERE id = :id");
    $stmt->bindParam(':id', $userId);
    $stmt->execute();
}

header("Location: admin_panel.php");
exit;
