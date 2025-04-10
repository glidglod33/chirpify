<?php
session_start();
require_once 'database_connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1): ?>
<?php endif;

$stmt = $conn->prepare("
    SELECT tweets.*, accounts.username,
    EXISTS (
        SELECT 1 FROM likes 
        WHERE likes.tweet_id = tweets.id 
        AND likes.user_id = :user_id
    ) AS liked_by_user,
    (
        SELECT COUNT(*) FROM likes WHERE likes.tweet_id = tweets.id
    ) AS likes
    FROM tweets 
    JOIN accounts ON tweets.user_id = accounts.id 
    ORDER BY tweets.created_at DESC
");

$stmt->execute(['user_id' => $_SESSION['user_id']]);
$tweets = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Chirpify</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<nav class="navbar">
    <div class="navbar-left">
        🐦 <span class="logo-text">Chirpify</span>
    </div>
    <div class="navbar-right">
        <a href="home.php">Home</a>
        <a href="logout.php" class="logout-button">Logout</a>
        <a href="admin_panel.php" class="logout-button">Admin Panel</a>
    </div>
</nav>




<div class="content">
    <h1><span class="username">Hi <?= htmlspecialchars($_SESSION['username']) ?>!</span></h1>

    <h3>Post something:</h3>
    <form action="post_tweet.php" method="post" enctype="multipart/form-data">
        <textarea name="content" placeholder="What's on your mind?" required></textarea><br>
        <input type="file" name="tweetImage"><br>
        <input type="submit" value="Post">
    </form>

    <h3>All Chirps:</h3>

    <?php foreach ($tweets as $tweet): ?>
        <div class="tweet">
            <strong>@<?= htmlspecialchars($tweet['username']) ?></strong><br>
            <?= nl2br(htmlspecialchars($tweet['content'])) ?><br>

            <?php if (!empty($tweet['image_path'])): ?>
                <img src="<?= htmlspecialchars($tweet['image_path']) ?>" style="max-width: 200px;"><br>
            <?php endif; ?>

            <button class="like-button" data-tweet-id="<?= $tweet['id'] ?>">
                <?= $tweet['liked_by_user'] ? '💖 Unlike' : '🤍 Like' ?>
            </button>
            <span class="like-count" data-tweet-id="<?= $tweet['id'] ?>">
                (<?= $tweet['likes'] ?>)
            </span>

            <?php if ($tweet['user_id'] == $_SESSION['user_id']): ?>
                <form action="delete_tweet.php" method="post" class="delete-form">
                    <input type="hidden" name="tweetId" value="<?= $tweet['id'] ?>">
                    <button type="submit">🗑️</button>
                </form>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.like-button').forEach(button => {
        button.addEventListener('click', function (e) {
            e.preventDefault();

            const tweetId = this.dataset.tweetId;
            const self = this;

            fetch('like_tweet.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'tweet_id=' + encodeURIComponent(tweetId)
            })
            .then(response => response.text())
            .then(result => {
                const likeCountSpan = document.querySelector(`.like-count[data-tweet-id="${tweetId}"]`);
                let currentCount = parseInt(likeCountSpan.textContent.replace(/\D/g, '')) || 0;

                if (result === 'liked') {
                    self.textContent = '💖 Unlike';
                    likeCountSpan.textContent = `(${currentCount + 1})`;
                } else if (result === 'unliked') {
                    self.textContent = '🤍 Like';
                    likeCountSpan.textContent = `(${currentCount - 1})`;
                }
            })
            .catch(error => {
                console.error("Fout bij like-verzoek:", error);
            });
        });
    });
});
</script>

</body>
</html>
