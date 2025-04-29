<?php
session_start();
require_once 'includes/functions.php';

// Удаление всех переменных сессии
$_SESSION = [];

// Удаление cookie сессии, если используется
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

// Завершение сессии
session_destroy();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Выход</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>

<div class="container">
    <h1>Выход из системы</h1>
    <p>Вы успешно завершили сеанс.</p>
    
    <a href="../index.php" class="btn">На главную</a>
</div>

</body>
</html>
