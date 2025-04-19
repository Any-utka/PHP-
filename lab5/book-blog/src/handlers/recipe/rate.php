<?php
session_start();

// Проверка метода запроса
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $_SESSION['error'] = "Invalid request method.";
    header("Location: /");
    exit();
}

// Подключение к базе данных
require_once '../../db.php';
require_once '../../config/db.php';

// Получаем данные из формы
$book_id = filter_input(INPUT_POST, 'book_id', FILTER_VALIDATE_INT);
$user_id = filter_input(INPUT_POST, 'user_id', FILTER_VALIDATE_INT);
$rating = filter_input(INPUT_POST, 'rating', FILTER_VALIDATE_INT);
$comment = filter_input(INPUT_POST, 'comment', FILTER_UNSAFE_RAW);
$comment = htmlspecialchars(trim($comment)); 

// Логирование для отладки
error_log("Received POST data: " . print_r($_POST, true));

// Проверка валидности данных
if (!$book_id || !$user_id || !$rating || $rating < 1 || $rating > 5) {
    $_SESSION['error'] = "Invalid form data.";
    header("Location: /show/$book_id");
    exit();
}

// Добавление рейтинга в базу данных
try {
    $pdo = getPostgreSQLConnection();
    $stmt = $pdo->prepare("
        INSERT INTO book_ratings (book_id, user_id, rating, comment)
        VALUES (?, ?, ?, ?)
    ");
    $stmt->execute([$book_id, $user_id, $rating, $comment]);

    $_SESSION['success'] = "Оценка успешно добавлена.";
    header("Location: /show/$book_id");
    exit();
} catch (PDOException $e) {
    $_SESSION['error'] = "Database error: " . $e->getMessage();
    header("Location: /show/$book_id");
    exit();
}
