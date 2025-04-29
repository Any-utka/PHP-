<?php
/**
 * Обрабатывает POST-запрос для добавления рейтинга книги.
 * 
 * Проверяет, что метод запроса является POST и что переменная $book_id установлена.
 * Извлекает данные из формы и добавляет рейтинг в базу данных.
 * После успешного добавления перенаправляет пользователя на страницу просмотра книги.
 */


/**
 * Стартует сессию для хранения сообщений об ошибках или успехах.
 */
session_start();

/**
 * Проверяет метод HTTP-запроса.
 * Если метод не POST, устанавливает сообщение об ошибке в сессии
 * и перенаправляет пользователя на главную страницу.
 */
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $_SESSION['error'] = "Invalid request method.";
    header("Location: /");
    exit();
}

/**
 * Подключает файлы для работы с базой данных.
 * - db.php: содержит функции для работы с базой данных.
 * - config/db.php: содержит конфигурацию подключения к базе данных.
 */
require_once '../db.php';
require_once '../config/db.php';

/**
 * Получает данные из формы с использованием фильтров для валидации.
 * - $book_id: идентификатор книги (целое число).
 * - $user_id: идентификатор пользователя (целое число).
 * - $rating: оценка книги (целое число от 1 до 5).
 * - $comment: комментарий пользователя (строка, очищенная от HTML).
 */
$book_id = filter_input(INPUT_POST, 'book_id', FILTER_VALIDATE_INT);
$user_id = filter_input(INPUT_POST, 'user_id', FILTER_VALIDATE_INT);
$rating = filter_input(INPUT_POST, 'rating', FILTER_VALIDATE_INT);
$comment = filter_input(INPUT_POST, 'comment', FILTER_UNSAFE_RAW);
$comment = htmlspecialchars(trim($comment));

/**
 * Логирует данные, полученные из POST-запроса, для целей отладки.
 */
error_log("Received POST data: " . print_r($_POST, true));

/**
 * Проверяет валидность данных формы.
 * Если данные некорректны, устанавливает сообщение об ошибке в сессии
 * и перенаправляет пользователя на страницу книги.
 */
if (!$book_id || !$user_id || !$rating || $rating < 1 || $rating > 5) {
    $_SESSION['error'] = "Invalid form data.";
    header("Location: /show/$book_id");
    exit();
}

/**
 * Добавляет рейтинг книги в базу данных.
 * Использует подготовленный запрос для предотвращения SQL-инъекций.
 * В случае успеха устанавливает сообщение об успешной операции в сессии
 * и перенаправляет пользователя на страницу книги.
 * В случае ошибки логирует сообщение об ошибке и устанавливает сообщение об ошибке в сессии.
 *
 * @throws PDOException Если возникает ошибка при работе с базой данных.
 */
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
    error_log("PostgreSQL connection failed: " . $e->getMessage());
    $_SESSION['error'] = "Database error: " . $e->getMessage();
    header("Location: /show/$book_id");
    exit();
}
