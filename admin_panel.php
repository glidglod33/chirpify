<?php
session_start();
require_once 'database_connection.php';

// Alleen admins mogen deze pagina zien
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    header("Location: home.php");
    exit();
}

// Alle gebruikers ophalen
$stmtUsers = $conn->query("SELECT id, username, is_admin FROM accounts");
$users = $stmtUsers->fetchAll(PDO::FETCH_ASSOC);

// Alle tweets ophalen
$stmtTweets = $conn->query("
    SELECT tweets.id, tweets.content, tweets.user_id, accounts.username
    FROM tweets
    JOIN accounts ON tweets.user_id = accounts.id
    ORDER BY tweets.created_at DESC
");
$tweets = $stmtTweets->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Panel</title>
    <link rel="stylesheet" href="style.css">
    <style>
        table { width: 100%; border-collapse: collapse; margin-bottom: 2em; }
        th, td { border: 1px solid #ccc; padding: 10px; text-align: left; }
        .danger { background-color: #ffdddd; color: red; padding: 5px 10px; border: none; }
    </style>
</head>
<body>
<nav class="navbar">
    <div class="navbar-left">🐦 <span class="logo-text">Admin Panel</span></div>
    <div class="navbar-right">
        <a href="home.php">Home</a>
        <a href="logout.php" class="logout-button">Logout</a>
    </div>
</nav>

<div class="content">
    <h1>Welkom, beheerder <?= htmlspecialchars($_SESSION['username']) ?></h1>

    <!-- Gebruikerslijst -->
    <h3>Gebruikers:</h3>
    <table>
        <tr>
            <th>ID</th>
            <th>Gebruikersnaam</th>
            <th>Is Admin</th>
            <th>Actie</th>
        </tr>
        <?php foreach ($users as $user): ?>
            <tr>
                <td><?= $user['id'] ?></td>
                <td><?= htmlspecialchars($user['username']) ?></td>
                <td><?= $user['is_admin'] ? 'Ja' : 'Nee' ?></td>
                <td>
                    <?php if ($user['id'] != $_SESSION['user_id']): ?>
                        <form action="admin_delete_user.php" method="post" onsubmit="return confirm('Weet je zeker dat je deze gebruiker wilt verwijderen?');">
                            <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                            <button type="submit" class="danger">Verwijder gebruiker</button>
                        </form>
                    <?php else: ?>
                        <em>Eigen account</em>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>

    <!-- Tweetlijst -->
    <h3>Tweets beheren:</h3>
    <table>
        <tr>
            <th>ID</th>
            <th>Gebruiker</th>
            <th>Inhoud</th>
            <th>Actie</th>
        </tr>
        <?php foreach ($tweets as $tweet): ?>
            <tr>
                <td><?= $tweet['id'] ?></td>
                <td><?= htmlspecialchars($tweet['username']) ?></td>
                <td><?= htmlspecialchars($tweet['content']) ?></td>
                <td>
                    <form action="admin_delete_tweet.php" method="post" onsubmit="return confirm('Tweet verwijderen?');">
                        <input type="hidden" name="tweet_id" value="<?= $tweet['id'] ?>">
                        <button type="submit" class="danger">Verwijder tweet</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>
</body>
</html>
