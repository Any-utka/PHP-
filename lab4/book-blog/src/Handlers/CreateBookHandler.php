<?php

// Инициализация переменных для полей формы
$title = isset($_POST['title']) ? htmlspecialchars($_POST['title'], ENT_QUOTES, 'UTF-8') : '';
$author = isset($_POST['author']) ? htmlspecialchars($_POST['author'], ENT_QUOTES, 'UTF-8') : '';
$genre = isset($_POST['tags']) ? htmlspecialchars($_POST['tags'], ENT_QUOTES, 'UTF-8') : '';
$description = isset($_POST['description']) ? htmlspecialchars($_POST['description'], ENT_QUOTES, 'UTF-8') : '';

// Обработка данных из формы
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($title) && !empty($author) && !empty($genre) && !empty($description)) {
        // Загрузка существующих книг из файла
        $filePath = __DIR__ . '/../../storage/books.json';
        $books = file_exists($filePath) ? json_decode(file_get_contents($filePath), true) : [];

        // Добавление новой книги в массив
        $newBook = [
            'title' => $title,
            'author' => $author,
            'description' => $description,
            'tags' => [$genre]
        ];

        // Добавление новой книги в список
        $books[] = $newBook;

        // Сохранение обновленного списка книг в файл
        file_put_contents($filePath, json_encode($books, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        // Перенаправление на страницу с книгами после добавления
        header('Location: index.php');
        exit;
    } else {
        echo 'Заполните все поля формы!';
    }
}

