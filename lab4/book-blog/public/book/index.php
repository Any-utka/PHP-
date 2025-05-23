<?php
/**
 * Чтение и фильтрация книг по жанру.
 *
 * Этот файл читает данные из JSON-файла, фильтрует книги по жанру (если выбран), а также
 * предоставляет возможность отображать последние 2 книги. Пользователь может добавить
 * книгу через ссылку на другую страницу.
 */

$filePath = __DIR__ . '/../../storage/books.json';

$books = file_exists($filePath) ? json_decode(file_get_contents($filePath), true) : [];

$filteredBooks = [];

if (isset($_GET['tags']) && $_GET['tags']) {
    $genreFilter = $_GET['tags'];
    foreach ($books as $book) {
        if (isset($book['tags']) && in_array($genreFilter, $book['tags'])) {
            $filteredBooks[] = $book;
        }
    }
} else {
    $filteredBooks = $books;
}

$showLastTwo = isset($_GET['last']) && $_GET['last'] === '1';
if ($showLastTwo) {
    $filteredBooks = array_slice($filteredBooks, -2);
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Библиотека</title>
    <link rel="stylesheet" href="../styles.css">
</head>
<body>

    <h2>Библиотека</h2>

    <form method="GET" action="">
        <label for="tags">Выберите жанр:</label>
        <select name="tags" id="tags">
            <option value="">Все</option>
            <option value="классика" <?= isset($_GET['tags']) && $_GET['tags'] == 'классика' ? 'selected' : '' ?>>Классика</option>
            <option value="фантастика" <?= isset($_GET['tags']) && $_GET['tags'] == 'фантастика' ? 'selected' : '' ?>>Фантастика</option>
            <option value="детектив" <?= isset($_GET['tags']) && $_GET['tags'] == 'детектив' ? 'selected' : '' ?>>Детектив</option>
            <option value="научпоп" <?= isset($_GET['tags']) && $_GET['tags'] == 'научпоп' ? 'selected' : '' ?>>Научпоп</option>
            <option value="история" <?= isset($_GET['tags']) && $_GET['tags'] == 'история' ? 'selected' : '' ?>>История</option>
            <option value="роман" <?= isset($_GET['tags']) && $_GET['tags'] == 'роман' ? 'selected' : '' ?>>Роман</option>
        </select>
        <button type="submit">Фильтровать</button>
    </form>

    <?php if (empty($filteredBooks)): ?>
        <p>Нет книг для отображения по выбранному жанру.</p>
    <?php else: ?>
        <ul>
            <?php foreach ($filteredBooks as $book): ?>
                <li>
                    <strong><?= htmlspecialchars($book['title']) ?></strong> - 
                    <em><?= htmlspecialchars($book['author']) ?></em><br>
                    <strong>Жанр:</strong> <?= isset($book['tags']) && is_array($book['tags']) ? htmlspecialchars(implode(', ', $book['tags'])) : 'Не указан' ?><br>
                    <strong>Описание:</strong> <?= htmlspecialchars($book['description']) ?><br>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <?php if (!$showLastTwo): ?>
        <form method="GET" action="">
            <?php if (isset($_GET['tags']) && $_GET['tags']): ?>
                <input type="hidden" name="tags" value="<?= htmlspecialchars($_GET['tags']) ?>">
            <?php endif; ?>
            <input type="hidden" name="last" value="1">
            <button type="submit">Показать последние 2 книги</button>
        </form>
    <?php else: ?>
        <form method="GET" action="">
            <?php if (isset($_GET['tags']) && $_GET['tags']): ?>
                <input type="hidden" name="tags" value="<?= htmlspecialchars($_GET['tags']) ?>">
            <?php endif; ?>
            <button type="submit">Показать все книги</button>
        </form>
    <?php endif; ?>

    <a href="create.php">Добавить книгу</a>

</body>
</html>
