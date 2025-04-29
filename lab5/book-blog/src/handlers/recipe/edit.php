<?php

/**
 * Обрабатывает POST-запрос для обновления информации о книге.
 * 
 * Проверяет, что метод запроса является POST и что переменная $id установлена.
 * Извлекает данные из формы и обновляет запись в базе данных.
 * После успешного обновления перенаправляет пользователя на страницу просмотра книги.
 */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($id)) {

    /**
     * @var string $title Название книги, полученное из формы.
     */
    $title = $_POST['title'];

    /**
     * @var int $category_id Идентификатор категории книги, полученный из формы.
     */
    $category_id = $_POST['category_id'];

    /**
     * @var string $author Автор книги, полученный из формы.
     */
    $author = $_POST['author'];

    /**
     * @var string $description Описание книги, полученное из формы.
     */
    $description = $_POST['description'];

    /**
     * @var string $tags Теги книги, полученные из формы.
     */
    $tags = $_POST['tags'];

    /**
     * Устанавливает соединение с базой данных и выполняет SQL-запрос для обновления записи о книге.
     * 
     * @uses getMySQLConnection() Для получения соединения с базой данных.
     * @throws PDOException Если выполнение SQL-запроса завершилось ошибкой.
     */
    $pdo = getMySQLConnection();
    $stmt = $pdo->prepare("UPDATE books 
                           SET title = ?, category_id = ?, author = ?, description = ?, tags = ? 
                           WHERE id = ?");
    $stmt->execute([$title, $category_id, $author, $description, $tags, $id]);

    /**
     * Перенаправляет пользователя на страницу просмотра обновленной книги.
     * 
     * @param string $id Идентификатор книги, используемый в URL для перенаправления.
     */
    header("Location: /show/{$id}");
}
