<?php

if (isset($id)) {
    // Используем MySQL для удаления книги
    $pdo = getMySQLConnection();
    $stmt = $pdo->prepare("DELETE FROM books WHERE id = ?");
    $stmt->execute([$id]);

    header("Location: /");
}
