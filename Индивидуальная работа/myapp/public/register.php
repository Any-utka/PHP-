<?php
session_start();

if (!file_exists('includes/config.php')) {
    die('Файл config.php не найден');
}

require_once 'includes/config.php';
require_once 'includes/functions.php';

/**
 * Обработка POST-запроса для регистрации нового пользователя.
 * Проверка прав администратора для назначения роли.
 * 
 * Входные параметры: данные формы ($_POST['username'], $_POST['email'], $_POST['password'], $_POST['role'])
 * Выходные данные: сообщение о успешной регистрации.
 */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username']; // Имя пользователя
    $email = $_POST['email']; // Email пользователя
    $password = $_POST['password']; // Пароль пользователя
    
    // Роль по умолчанию — пользователь
    $role = 'user';
    // Если администратор, может назначать роль
    if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin' && !empty($_POST['role'])) {
        $role = $_POST['role'] === 'admin' ? 'admin' : 'user';
    }

    // Регистрация пользователя с заданными параметрами
    registerUser($username, $email, $password, $role);

    // Сообщение об успешной регистрации
    echo "Регистрация успешна! <a href='input.php'>Войти</a>";
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Регистрация</title>
    <link rel="stylesheet" type="text/css" href="../assets/style.css">
</head>
<body>
    <div class="container">
        <h1>Регистрация</h1>
        <form method="POST">
            <input type="text" name="username" placeholder="Имя пользователя" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Пароль" required>
            
            <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                <select name="role">
                    <option value="user">Пользователь</option>
                    <option value="admin">Администратор</option>
                </select>
            <?php endif; ?>
            
            <button type="submit">Зарегистрироваться</button>
        </form>
        <p><a href="../index.php">Назад</a></p>
    </div>
</body>
</html>
