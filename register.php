<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <link rel="stylesheet" href="style.css">
    <script>
        window.onload = function () {
            const params = new URLSearchParams(window.location.search);
            if (params.has('error')) {
                let error = params.get('error');
                if (error === 'username_taken') {
                    alert("Deze gebruikersnaam bestaat al. Kies een andere.");
                } else if (error === 'empty_fields') {
                    alert("Vul alle velden in voordat je registreert.");
                } else if (error === 'unknown') {
                    alert("Er is iets fout gegaan. Probeer het opnieuw.");
                }
            }
        };
    </script>
</head>
<body class="center-screen">
    <div class="auth-card">
        <h2>Register</h2>
        <form action="register_account.php" method="post">
            <label for="gebruikersnaam">Gebruikersnaam</label>
            <input type="text" name="gebruikersnaam" id="gebruikersnaam" required>

            <label for="wachtwoord">Wachtwoord</label>
            <input type="password" name="wachtwoord" id="wachtwoord" required>

            <input type="submit" value="Create account">
        </form>
    </div>
</body>
