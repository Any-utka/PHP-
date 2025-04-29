<?php
session_start();

require_once 'includes/config.php';
require_once 'includes/functions.php';

/**
 * Панель администратора:
 * - Управление пользователями и статьями.
 * - Только для пользователей с ролью 'admin'.
 */

// Проверка авторизации и прав доступа
if (!isAuthenticated() || $_SESSION['role'] !== 'admin') {
    header('Location: /myapp/index.php');
    exit();
}

// Удаление статьи
if (isset($_GET['delete_article'])) {
    $articleId = (int) $_GET['delete_article'];
    deleteArticle($articleId);
    header('Location: admin.php');
    exit();
}

// Удаление пользователя
if (isset($_GET['delete_user'])) {
    $userId = (int) $_GET['delete_user'];
    deleteUser($userId);
    header('Location: admin.php');
    exit();
}

// Получение всех пользователей, кроме администраторов
$sql = "SELECT * FROM users WHERE role != 'admin' ORDER BY username ASC";
$users = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);

// Получение всех статей
$sql = "SELECT * FROM articles ORDER BY created_at DESC";
$articles = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Администраторская панель</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>

    <h1>Администраторская панель</h1>
    <p><a href="logout.php" onclick="return confirm('Вы действительно хотите выйти?');" class="logout-button">Выйти</a></p>

    <div class="admin-panel">
        <h2>Управление пользователями</h2>

        <p><a href="register.php">Добавить нового пользователя</a></p>

        <?php foreach ($users as $user): ?>
            <h3>Пользователь: <?= htmlspecialchars($user['username']) ?></h3>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Заголовок</th>
                        <th>Дата создания</th>
                        <th>Действия</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $userArticles = array_filter($articles, fn($a) => $a['user_id'] == $user['id']);
                    foreach ($userArticles as $article): ?>
                        <tr>
                            <td><?= htmlspecialchars($article['id']) ?></td>
                            <td><?= htmlspecialchars($article['title']) ?></td>
                            <td><?= htmlspecialchars($article['created_at']) ?></td>
                            <td>
                                <a href="article/edit_article.php?id=<?= $article['id'] ?>">Редактировать</a>
                                <a href="?delete_article=<?= $article['id'] ?>" onclick="return confirm('Вы уверены, что хотите удалить эту статью?')">Удалить</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <p><a href="?delete_user=<?= $user['id'] ?>" onclick="return confirm('Вы уверены, что хотите удалить этого пользователя?')">Удалить пользователя</a></p>
        <?php endforeach; ?>

        <p><a href="profile.php">Перейти в профиль</a></p>
    </div>

</body>
</html>
