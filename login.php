<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="center-screen">
    <div class="auth-card">
        <h2>Login</h2>
        <form action="login_account.php" method="POST">
            <label>Gebruikersnaam:</label>
            <input type="text" name="gebruikersnaam" required>

            <label>Wachtwoord:</label>
            <input type="password" name="wachtwoord" required>

            <button type="submit">Inloggen</button>
        </form>
        <p style="text-align: center;">Nog geen account? <a href="register.php">Registreer hier</a></p>
    </div>
</body>
</html>
