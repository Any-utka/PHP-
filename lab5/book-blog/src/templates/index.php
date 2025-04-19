<?php require_once '../db.php'; ?>
<?php ob_start(); // Начинаем буферизацию вывода ?>

<h2>All Books</h2>

<?php
// Подключаем к базе данных MySQL и извлекаем все книги
$pdo = getMySQLConnection();

$query = $pdo->query("SELECT * FROM books");

echo "total books: " . $query->rowCount() . "<br>";

while ($book = $query->fetch(PDO::FETCH_ASSOC)) {
    echo "<div class='book'>";
    echo "<h3><a href='/show/{$book['id']}'>{$book['title']}</a></h3>";
    echo "<p>Category: {$book['category_id']}</p>";
    echo "<p>Author: {$book['author']}</p>";
    echo "</div>";
}
?>

<?php $content = ob_get_clean(); // Окончание буферизации вывода и сохранение контента ?>
<?php include 'layout.php'; // Вставляем контент в шаблон ?>
