document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.like-button').forEach(button => {
        button.addEventListener('click', function () {
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
                if (result === 'liked') {
                    self.textContent = '💖 Unlike';
                } else if (result === 'unliked') {
                    self.textContent = '🤍 Like';
                } else {
                    console.log(result);
                }
            });
        });
    });
});


