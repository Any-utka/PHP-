<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($id)) {
    $title = $_POST['title'];
    $category_id = $_POST['category_id'];
    $author = $_POST['author'];
    $description = $_POST['description'];
    $tags = $_POST['tags'];

    // Используем MySQL для обновления данных о книге
    $pdo = getMySQLConnection();
    $stmt = $pdo->prepare("UPDATE books 
                           SET title = ?, category_id = ?, author = ?, description = ?, tags = ? 
                           WHERE id = ?");
    $stmt->execute([$title, $category_id, $author, $description, $tags, $id]);

    header("Location: /show/{$id}");
}
