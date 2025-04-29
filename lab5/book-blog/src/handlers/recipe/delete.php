<?php

/**
 * Удаляет запись из таблицы "books" по указанному идентификатору.
 *
 * @param int $id Идентификатор записи, которая должна быть удалена.
 * @return void Функция не возвращает значения, но перенаправляет пользователя на главную страницу.
 *
 * @throws PDOException Если возникает ошибка при выполнении SQL-запроса.
 */

if (isset($id)) {
    $pdo = getMySQLConnection();
    $stmt = $pdo->prepare("DELETE FROM books WHERE id = ?");
    $stmt->execute([$id]);

    header("Location: /");
}
