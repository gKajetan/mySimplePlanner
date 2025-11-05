<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Logowanie</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
    <div class="auth-container">
        <h1>Logowanie</h1>

        <?php if (isset($message) && $message): ?>
            <p class="error-message"><?= htmlspecialchars($message) ?></p>
        <?php endif; ?>

        <form method="post" action="/">
            <div>
                <label for="name">Nazwa użytkownika:</label>
                <input type="text" name="name" id="name" required>
            </div>
            <div>
                <label for="password">Hasło:</label>
                <input type="password" name="password" id="password" required>
            </div>
            <button type="submit" class="btn btn-primary">Zaloguj</button>
        </form>
        
        <p>Nie masz konta? <a href="/register">Zarejestruj się</a>.</p>
    </div>
</body>
</html>