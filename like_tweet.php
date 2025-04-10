<?php
session_start();
require_once 'database_connection.php';

if (!isset($_SESSION['user_id'])) {
    echo "Niet ingelogd.";
    exit;
}

// Fix: check op juiste key "tweet_id"
if (!isset($_POST['tweet_id'])) {
    echo "Geen tweet_id meegegeven.";
    exit;
}

$tweet_id = $_POST['tweet_id'];
$user_id = $_SESSION['user_id'];

// Check of like al bestaat
$stmt = $conn->prepare("SELECT * FROM likes WHERE user_id = :user_id AND tweet_id = :tweet_id");
$stmt->bindParam(':user_id', $user_id);
$stmt->bindParam(':tweet_id', $tweet_id);
$stmt->execute();

if ($stmt->rowCount() > 0) {
    // Unlike
    $stmt = $conn->prepare("DELETE FROM likes WHERE user_id = :user_id AND tweet_id = :tweet_id");
    $stmt->bindParam(':user_id', $user_id);
    $stmt->bindParam(':tweet_id', $tweet_id);
    $stmt->execute();
    echo "unliked";
} else {
    // Like toevoegen
    $stmt = $conn->prepare("INSERT INTO likes (user_id, tweet_id) VALUES (:user_id, :tweet_id)");
    $stmt->bindParam(':user_id', $user_id);
    $stmt->bindParam(':tweet_id', $tweet_id);
    $stmt->execute();
    echo "liked";
}
?>
