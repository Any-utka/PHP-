<?php
/**
 * Проверяет метод HTTP-запроса и завершает выполнение скрипта, если метод не POST.
 * 
 * @return void
 */
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    exit("Invalid request method.");
}

/**
 * Получает и фильтрует входные данные из POST-запроса.
 * 
 * @var string|null $title Название книги, очищенное от нежелательных символов.
 * @var int|null $category_id Идентификатор категории, прошедший валидацию как целое число.
 * @var string|null $author Имя автора, очищенное от нежелательных символов.
 * @var string|null $description Описание книги, очищенное от нежелательных символов.
 * @var string|null $tags Теги книги, очищенные от нежелательных символов.
 */
$title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING);
$category_id = filter_input(INPUT_POST, 'category_id', FILTER_VALIDATE_INT);
$author = filter_input(INPUT_POST, 'author', FILTER_SANITIZE_STRING);
$description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING);
$tags = filter_input(INPUT_POST, 'tags', FILTER_SANITIZE_STRING);

/**
 * Логирует отфильтрованные данные для отладки.
 * 
 * @param string $message Сообщение для записи в лог.
 * @return void
 */
error_log("Title: $title, Category ID: $category_id, Author: $author, Description: $description, Tags: $tags", 0);

/**
 * Создает подключение к базе данных MySQL и выполняет вставку новой записи в таблицу `books`.
 * 
 * @uses getMySQLConnection() Для получения объекта PDO для подключения к базе данных.
 * @var PDO $pdo Объект подключения к базе данных.
 * @var PDOStatement $stmt Подготовленный SQL-запрос для вставки данных.
 * @var int $book_id Идентификатор последней вставленной записи.
 * 
 * @throws PDOException Если выполнение SQL-запроса завершилось ошибкой.
 */
$pdo = getMySQLConnection();
$stmt = $pdo->prepare("INSERT INTO books (title, category_id, author, description, tags) 
                            VALUES (?, ?, ?, ?, ?)");
$stmt->execute([$title, $category_id, $author, $description, $tags]);
$book_id = $pdo->lastInsertId();

/**
 * Перенаправляет пользователя на страницу с информацией о созданной книге.
 * 
 * @param string $url URL для перенаправления.
 * @return void
 */
header("Location: /show/{$book_id}");