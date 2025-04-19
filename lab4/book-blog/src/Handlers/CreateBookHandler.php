<?php

/**
 * Обработка данных формы для добавления книги.
 * 
 * Этот скрипт принимает данные из формы, проверяет их на заполненность, 
 * добавляет книгу в файл JSON и перенаправляет пользователя на главную страницу.
 * 
 * @package Library
 * @subpackage Handlers
 */


$title = isset($_POST['title']) ? htmlspecialchars($_POST['title'], ENT_QUOTES, 'UTF-8') : '';

$author = isset($_POST['author']) ? htmlspecialchars($_POST['author'], ENT_QUOTES, 'UTF-8') : '';

$genre = isset($_POST['tags']) ? htmlspecialchars($_POST['tags'], ENT_QUOTES, 'UTF-8') : '';

$description = isset($_POST['description']) ? htmlspecialchars($_POST['description'], ENT_QUOTES, 'UTF-8') : '';

// Обработка данных после отправки формы
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    /**
     * Проверка, что все обязательные поля формы заполнены.
     * 
     * Если хотя бы одно поле пустое, то выводится сообщение об ошибке.
     */
    if (!empty($title) && !empty($author) && !empty($genre) && !empty($description)) {
        $filePath = __DIR__ . '/../../storage/books.json';

        /**
         * Загрузка существующих книг из файла.
         * Если файл существует, то декодируем его в ассоциативный массив.
         * 
         * @var array
         */
        $books = file_exists($filePath) ? json_decode(file_get_contents($filePath), true) : [];

        /**
         * Новый массив с данными книги.
         * 
         * @var array
         */
        $newBook = [
            'title' => $title,
            'author' => $author,
            'description' => $description,
            'tags' => [$genre]
        ];

        $books[] = $newBook;

        /**
         * Сохранение обновленного списка книг в файл.
         * Данные кодируются в формат JSON с красивым форматированием и без экранирования юникодных символов.
         */
        file_put_contents($filePath, json_encode($books, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        // Перенаправление на главную страницу после успешного добавления книги
        header('Location: index.php');
        exit;
    } else {
        /**
         * Вывод сообщения об ошибке, если не все поля формы были заполнены.
         * 
         * @var string
         */
        echo 'Заполните все поля формы!';
    }
}
?>
