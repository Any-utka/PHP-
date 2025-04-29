<?php
session_start();

require_once 'includes/config.php';

/**
 * Удаление статьи, если пользователь авторизован и статья ему принадлежит.
 */

// Проверка авторизации пользователя
if (!isset($_SESSION['user_id'])) {
    header('Location: ../input.php');
    exit;
}

// Проверка наличия ID статьи в GET-запросе
if (isset($_GET['id'])) {
    $articleId = $_GET['id'];

    /**
     * Получение статьи, принадлежащей текущему пользователю.
     *
     * @param int $articleId Идентификатор статьи.
     * @param int $userId ID текущего пользователя.
     * @return array|null Ассоциативный массив с данными статьи или null, если не найдена.
     */
    $sql = "SELECT * FROM articles WHERE id = ? AND user_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$articleId, $_SESSION['user_id']]);
    $article = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($article) {
        /**
         * Удаление статьи из базы данных.
         *
         * @param int $articleId ID статьи для удаления.
         * @return void
         */
        $sql = "DELETE FROM articles WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$articleId]);

        $_SESSION['message'] = 'Статья успешно удалена.';
    } else {
        $_SESSION['error'] = 'Статья не найдена или у вас нет прав для её удаления.';
    }
} else {
    $_SESSION['error'] = 'Неверный идентификатор статьи.';
}

// Перенаправление на страницу профиля
header('Location: ../profile.php');
exit;
