<?php
/**
 * Стартует сессия и загружает конфигурацию.
 */
session_start();
require_once 'includes/config.php';

/**
 * Массив для хранения параметров поиска.
 *
 * @var array
 */
$searchParams = [
    'title' => '',
    'category' => '',
    'date_from' => '',
    'date_to' => '',
];

/**
 * Массив для хранения условий поиска.
 *
 * @var array
 */
$where = [];
$params = [];

/**
 * Обрабатывает запросы GET для поиска статей по заданным критериям.
 */
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['search'])) {
    // Очистка данных от лишних пробелов
    $searchParams = array_map('trim', $_GET);

    // Проверка, что хотя бы один параметр поиска был передан
    if (empty($searchParams['title']) && empty($searchParams['category'])) {
        $articles = [];
        $errorMessage = "Пожалуйста, укажите хотя бы заголовок или категорию для поиска.";
    } else {
        // Формирование условий для SQL-запроса
        if (!empty($searchParams['title'])) {
            $where[] = "title LIKE :title";
            $params[':title'] = '%' . $searchParams['title'] . '%';
        }

        if (!empty($searchParams['category'])) {
            $where[] = "category = :category";
            $params[':category'] = $searchParams['category'];
        }

        // Фильтрация только публичных статей
        $where[] = "is_public = 1"; // только публичные статьи

        // Формирование SQL-запроса с учетом фильтров
        $sql = "SELECT * FROM articles";
        if ($where) {
            $sql .= " WHERE " . implode(" AND ", $where);
        }
        $sql .= " ORDER BY created_at DESC LIMIT 5";

        // Подготовка и выполнение SQL-запроса
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $articles = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
} else {
    // По умолчанию выводим 5 последних публичных статей
    $sql = "SELECT * FROM articles WHERE is_public = 1 ORDER BY created_at DESC LIMIT 5";
    $stmt = $pdo->query($sql);
    $articles = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Главная страница</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<div class="container">
    <h1>Добро пожаловать в наше веб-приложение!</h1>

    <!-- Проверка, авторизован ли пользователь -->
    <?php if (isset($_SESSION['user_id'])): ?>
        <p>Здравствуйте, <?= htmlspecialchars($_SESSION['username']); ?>!</p>
        <a href="public/profile.php">Перейти в профиль</a>
    <?php else: ?>
        <p>Чтобы получить доступ к защищённым разделам, пожалуйста, <a href="public/input.php">войдите</a> или <a href="public/register.php">зарегистрируйтесь</a>.</p>
    <?php endif; ?>

    <a href="public/article/create_article.php" class="button">Добавить статью</a>

    <h2>Поиск статей</h2>

    <?php if (!empty($errorMessage)): ?>
        <p style="color: red;"><?= htmlspecialchars($errorMessage) ?></p>
    <?php endif; ?>
    
    <!-- Форма поиска -->
    <form method="get" action="" onsubmit="return validateForm()">
        <label>Заголовок:
            <input type="text" name="title" id="title" value="<?= htmlspecialchars($searchParams['title']) ?>">
        </label><br><br>

        <label>Категория:
            <select name="category" id="category">
                <option value="">-- Выберите --</option>
                <option value="Новости" <?= $searchParams['category'] === 'Новости' ? 'selected' : '' ?>>Новости</option>
                <option value="Технологии" <?= $searchParams['category'] === 'Технологии' ? 'selected' : '' ?>>Технологии</option>
                <option value="Развлечения" <?= $searchParams['category'] === 'Развлечения' ? 'selected' : '' ?>>Развлечения</option>
                <option value="Образование" <?= $searchParams['category'] === 'Образование' ? 'selected' : '' ?>>Образование</option>
            </select>
        </label><br><br>

        <button type="submit" name="search">Поиск</button>
    </form>

    <h2>Топ 5 статей</h2>

    <!-- Вывод списка статей -->
    <?php if (!empty($articles)): ?>
        <ul>
            <?php foreach ($articles as $article): ?>
                <li>
                    <h3><?= htmlspecialchars($article['title']) ?></h3>
                    <p><?= htmlspecialchars(substr($article['content'], 0, 100)) ?>...</p>
                    <a href="public/article/view_article.php?id=<?= $article['id'] ?>">Читать далее</a>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>Статей не найдено по заданным критериям.</p>
    <?php endif; ?>
</div>

<script src="includes/validate.js"></script>
</body>
</html>
