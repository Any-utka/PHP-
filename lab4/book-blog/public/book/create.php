<?php
require_once __DIR__ . '/../../src/Handlers/CreateBookHandler.php';
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Добавить книгу</title>
    <link rel="stylesheet" href="../styles.css">
</head>
<body>

    <h2>Добавить новую книгу</h2>

    <form method="POST" action="">

        <label for="title">Название книги:</label>
        <input type="text" id="title" name="title" required><br><br>

        <label for="author">Автор книги:</label>
        <input type="text" id="author" name="author" required><br><br>

        <label for="tags">Жанр:</label>
        <select name="tags" id="tags" required>
            <option value="">Выберите жанр</option>
            <option value="классика">Классика</option>
            <option value="фантастика">Фантастика</option>
            <option value="детектив">Детектив</option>
            <option value="научпоп">Научпоп</option>
            <option value="история">История</option>
            <option value="роман">Роман</option>
        </select><br><br>

        <label for="description">Описание книги:</label><br>
        <textarea id="description" name="description" required></textarea><br><br>

        <button type="submit">Добавить книгу</button>
    </form>

</body>
</html>
