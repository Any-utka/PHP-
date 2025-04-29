<?php
session_start();

require_once 'includes/config.php';

/**
 * Скрипт отображает одну статью по её ID.
 * При ошибке перенаправляет пользователя на главную или профиль.
 */

// Проверка наличия ID статьи в запросе
if (!isset($_GET['id'])) {
    $_SESSION['error'] = "Неверный идентификатор статьи!";
    header("Location: profile.php");
    exit;
}

$articleId = $_GET['id'];

/**
 * Получение статьи из базы данных по ID.
 *
 * @param int $articleId Идентификатор статьи
 * @return array|null Данные статьи или null, если не найдена
 */
$sql = "SELECT * FROM articles WHERE id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$articleId]);
$article = $stmt->fetch(PDO::FETCH_ASSOC);

// Обработка случая, если статья не найдена
if (!$article) {
    $_SESSION['error'] = "Статья не найдена!";
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($article['title']) ?></title>
    <link rel="stylesheet" type="text/css" href="../../assets/style.css">
</head>
<body>
    <div class="container">
        <h1><?= htmlspecialchars($article['title']) ?></h1>
        <p><strong>Дата публикации:</strong> <?= htmlspecialchars($article['created_at']) ?></p>
        <div>
            <p><?= nl2br(htmlspecialchars($article['content'])) ?></p>
        </div>
        <p><a href="../../index.php">Назад</a></p>
    </div>
</body>
</html>
