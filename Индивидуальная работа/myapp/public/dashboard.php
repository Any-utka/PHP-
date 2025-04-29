<?php
session_start();
require_once 'includes/functions.php';

/**
 * Проверка, авторизован ли пользователь. 
 * Если пользователь не авторизован, происходит перенаправление на страницу регистрации.
 */
if (!isAuthenticated()) {
    header('Location: register.php');
    exit(); // Завершаем выполнение скрипта после редиректа
}

// Получаем имя пользователя из сессии и роль, если она установлена, иначе по умолчанию 'user'.
$username = htmlspecialchars($_SESSION['username']);
$role = $_SESSION['role'] ?? 'user'; // получаем роль пользователя
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Профиль</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>

<div class="profile-card">
    <!-- Приветственное сообщение с именем пользователя -->
    <h1>Добро пожаловать, <?= $username ?>!</h1>
    <p>Вы успешно вошли в систему.</p>

    <!-- Ссылка на личный кабинет -->
    <p><a href="profile.php">Перейти в личный кабинет</a></p>

    <!-- Если роль пользователя 'admin', показываем ссылку на админ-панель -->
    <?php if ($role === 'admin'): ?>
        <p><a href="admin.php" style="color: darkred;">Перейти в админ-панель</a></p>
    <?php endif; ?>

    <!-- Кнопки для создания и редактирования статей для администраторов -->
    <?php if ($role === 'admin'): ?>
        <p><a href="article/create_article.php" class="button">Создать статью</a></p>
        <p><a href="article/edit_article.php" class="button">Редактировать статью</a></p>
    <?php endif; ?>

    <p><a href="logout.php">Выйти</a></p>
</div>

</body>
</html>
