<?php
session_start();
require_once 'database_connection.php';

if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    header("Location: home.php");
    exit();
}

if (isset($_POST['tweet_id'])) {
    $tweetId = $_POST['tweet_id'];

    // Eerst alle likes van de tweet verwijderen
    $stmtLikes = $conn->prepare("DELETE FROM likes WHERE tweet_id = :tweet_id");
    $stmtLikes->bindParam(':tweet_id', $tweetId);
    $stmtLikes->execute();

    // Dan pas de tweet zelf verwijderen
    $stmtTweet = $conn->prepare("DELETE FROM tweets WHERE id = :id");
    $stmtTweet->bindParam(':id', $tweetId);
    $stmtTweet->execute();
}

header("Location: admin_panel.php");
exit;
