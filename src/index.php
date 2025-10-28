<?php
session_start();

// Database connection
$host = getenv('MYSQL_HOST');
$db   = getenv('MYSQL_DATABASE');
$user = getenv('MYSQL_USER');
$pass = getenv('MYSQL_PASSWORD');
$charset = getenv('MYSQL_CHARSET');

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = 
[
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try 
{
    $pdo = new PDO($dsn, $user, $pass, $options);
} 
catch (\PDOException $e) 
{
    echo "Błąd połączenia z bazą: " . $e->getMessage();
    exit;
}

// Login
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') 
    {
    $name = $_POST['name'] ?? '';

    $stmt = $pdo->prepare("SELECT * FROM users WHERE name = ?");
    $stmt->execute([$name]);
    $user = $stmt->fetch();

    if ($user) {
        $_SESSION['user'] = $user['name'];
        header("Location: main.php");
        exit;
    } else {
        $message = 'Nieprawidłowa nazwa użytkownika.';
    }
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Logowanie</title>
</head>
<body>
    <h1>Logowanie</h1>

    <?php if ($message): ?>
        <p style="color:red;"><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>

    <form method="post">
        <label for="name">Nazwa użytkownika:</label>
        <input type="text" name="name" id="name" required>
        <button type="submit">Zaloguj</button>
    </form>
</body>
</html>
