<?php
session_start();
require_once 'database_connection.php';

$tweetId = $_POST['tweetId'];
$userId = $_SESSION['user_id'];

// Eerst de likes van deze tweet verwijderen
$stmt = $conn->prepare("DELETE FROM likes WHERE tweet_id = :tweetId");
$stmt->bindParam(':tweetId', $tweetId);
$stmt->execute();

// Daarna de tweet verwijderen (alleen als deze van jou is)
$stmt = $conn->prepare("DELETE FROM tweets WHERE id = :tweetId AND user_id = :userId");
$stmt->bindParam(':tweetId', $tweetId);
$stmt->bindParam(':userId', $userId);
$stmt->execute();

// Terug naar home
header("Location: home.php");
exit;
