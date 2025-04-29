<?php
session_start();
require_once 'includes/config.php';
require_once 'includes/functions.php';

/**
 * Обработка формы входа:
 * - Проверка email и пароля.
 * - Установка данных в сессию при успешном входе.
 */

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Получаем пользователя из БД
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Проверка существования пользователя и пароля
    if (!$user) {
        $_SESSION['error'] = "Этот пользователь не зарегистрирован!";
        header("Location: register.php");
        exit;
    }

    if (!password_verify($password, $user['password'])) {
        $_SESSION['error'] = "Неверный пароль!";
        header("Location: input.php");
        exit;
    }

    // Успешная аутентификация
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['email'] = $user['email'];
    $_SESSION['role'] = $user['role'] ?? 'user';

    header("Location: dashboard.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Вход</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>

<div class="container">
    <h1>Вход</h1>

    <?php if (isset($_SESSION['error'])): ?>
        <p style="color: red"><?= htmlspecialchars($_SESSION['error']) ?></p>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <form method="POST">
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Пароль" required>
        <button type="submit">Войти</button>
    </form>

    <p>Нет аккаунта? <a href="register.php">Зарегистрироваться</a></p>
    <p><a href="../index.php">Назад</a></p>
</div>

</body>
</html>
