<?php
session_start();

require_once 'includes/config.php';
require_once 'includes/functions.php'; 

/**
 * Скрипт для редактирования статьи. Доступен только администраторам.
 */

$accessDenied = false;

// Проверка наличия ID статьи в запросе
if (!isset($_GET['id'])) {
    $_SESSION['error'] = "ID статьи не передан.";
    header("Location: ../profile.php");
    exit;
}

$articleId = $_GET['id'];

/**
 * Получение статьи по ID.
 *
 * @param int $articleId Идентификатор статьи
 * @return array|null Данные статьи или null, если не найдена
 */
$sql = "SELECT * FROM articles WHERE id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$articleId]);
$article = $stmt->fetch(PDO::FETCH_ASSOC);

// Проверка существования статьи
if (!$article) {
    $_SESSION['error'] = "Статья не найдена.";
    header("Location: ../profile.php");
    exit;
}

// Проверка роли пользователя
if ($_SESSION['role'] !== 'admin') {
    $accessDenied = true;
}

// Обработка формы при отправке данных
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$accessDenied) {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);

    // Валидация данных
    if ($title && $content) {
        /**
         * Обновление статьи в базе данных.
         *
         * @param string $title Новый заголовок
         * @param string $content Новое содержимое
         * @param int $articleId Идентификатор статьи
         */
        $sql = "UPDATE articles SET title = :title, content = :content WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':title' => $title,
            ':content' => $content,
            ':id' => $articleId
        ]);

        $_SESSION['success'] = "Статья успешно обновлена!";
        header("Location: view_article.php?id=" . $articleId);
        exit;
    } else {
        $error = "Пожалуйста, заполните все поля.";
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Редактировать статью</title>
    <link rel="stylesheet" href="../../assets/style.css">
</head>
<body>
    <div class="container">
        <h1>Редактировать статью</h1>

        <?php if ($accessDenied): ?>
            <p style="color: red;">Только администратор может редактировать статьи.</p>
        <?php elseif (isset($error)): ?>
            <p style="color: red;"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <?php if (!$accessDenied): ?>
            <form method="POST">
                <label for="title">Заголовок:</label><br>
                <input type="text" name="title" id="title" value="<?= htmlspecialchars($article['title']) ?>" required><br><br>

                <label for="content">Содержимое:</label><br>
                <textarea name="content" id="content" rows="10" required><?= htmlspecialchars($article['content']) ?></textarea><br><br>

                <button type="submit">Сохранить изменения</button>
            </form>
        <?php endif; ?>

        <a href="../profile.php">Назад</a>
    </div>
</body>
</html>