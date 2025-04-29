<?php
session_start();
require_once 'includes/config.php'; 
require_once 'includes/functions.php';

/**
 * Проверка авторизации пользователя.
 * Если пользователь не авторизован, перенаправляем на страницу входа.
 */
if (!isset($_SESSION['user_id'])) {
    header('Location: input.php'); // Перенаправление на страницу входа
    exit;
}

// Информация о пользователе, полученная из сессии
$username = $_SESSION['username']; // Имя пользователя
$role = $_SESSION['role'] ?? 'user'; // Роль пользователя, по умолчанию — 'user'
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Личный кабинет</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>

<div class="container">
    <h1>Добро пожаловать, <?= htmlspecialchars($username); ?>!</h1>
    <p>Это ваш личный кабинет.</p>

    <?php if ($role === 'admin'): ?>
        <!-- Ссылка на управление пользователями (только для администратора) -->
        <p><a href="admin.php" class="btn">Управление пользователями</a></p>
    <?php endif; ?>

    <a href="logout.php" class="btn">Выйти</a>
</div>

<?php if (isset($_SESSION['user_id'])): ?>
    <div class="user-content">
        <h2>Ваши статьи</h2>

        <!-- Ссылка на создание новой статьи -->
        <a href="article/create_article.php" class="btn">Добавить статью</a>

        <?php
        /**
         * Получение статей текущего пользователя.
         * Выполняется запрос к базе данных для получения всех статей пользователя, сортированных по дате.
         * 
         * @return array Массив статей текущего пользователя.
         */
        $sql = "SELECT * FROM articles WHERE user_id = ? ORDER BY created_at DESC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$_SESSION['user_id']]);
        $userArticles = $stmt->fetchAll(PDO::FETCH_ASSOC);
        ?>

        <?php if (count($userArticles) > 0): ?>
            <ul>
                <?php foreach ($userArticles as $article): ?>
                    <li>
                        <strong><?= htmlspecialchars($article['title']) ?></strong>
                        
                        <?php if ($role === 'admin' || $article['user_id'] == $_SESSION['user_id']): ?>
                            <a href="article/edit_article.php?id=<?= $article['id'] ?>">Редактировать</a>
                            <a href="article/delete_article.php?id=<?= $article['id'] ?>" 
                               onclick="return confirm('Вы уверены, что хотите удалить статью?')">Удалить</a>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>У вас пока нет своих статей.</p>
        <?php endif; ?>
    </div>
<?php endif; ?>

</body>
</html>
