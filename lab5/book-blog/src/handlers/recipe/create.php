<?php

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    exit("Invalid request method.");
}

$title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING);
$category_id = filter_input(INPUT_POST, 'category_id', FILTER_VALIDATE_INT);
$author = filter_input(INPUT_POST, 'author', FILTER_SANITIZE_STRING);
$description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING);
$tags = filter_input(INPUT_POST, 'tags', FILTER_SANITIZE_STRING);

// debug info to stderr
error_log("Title: $title, Category ID: $category_id, Author: $author, Description: $description, Tags: $tags", 0);

$pdo = getMySQLConnection(); // Подключение к MySQL
$stmt = $pdo->prepare("INSERT INTO books (title, category_id, author, description, tags) 
                            VALUES (?, ?, ?, ?, ?)");
$stmt->execute([$title, $category_id, $author, $description, $tags]);
$book_id = $pdo->lastInsertId(); // Получаем ID последней вставленной записи

header("Location: /show/{$book_id}");
