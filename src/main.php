<?php
session_start();

if (!isset($_SESSION['user'])) 
{
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Witaj</title>
</head>
<body>
    <h1>Witaj, <?= htmlspecialchars($_SESSION['user']) ?>!</h1>
    <a href="logout.php">Wyloguj</a>
</body>
</html>
