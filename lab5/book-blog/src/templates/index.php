<?php
/**
 * Подключение к базе данных.
 * Используется для получения объекта PDO для выполнения запросов к базе данных.
 * 
 * @return PDO Объект подключения к базе данных MySQL.
 */
require_once '../db.php';

/**
 * Начало буферизации вывода.
 * Буферизация используется для сохранения HTML-контента в переменную $content.
 */
ob_start();

/**
 * Получение подключения к базе данных и выполнение SQL-запроса для получения всех книг.
 * 
 * @var PDO $pdo Объект подключения к базе данных.
 * @var PDOStatement $query Результат выполнения SQL-запроса.
 */
$pdo = getMySQLConnection();
$query = $pdo->query("SELECT * FROM books");

/**
 * Вывод общего количества книг.
 * 
 * @var int $query->rowCount() Количество строк, возвращенных запросом.
 */
echo "total books: " . $query->rowCount() . "<br>";

/**
 * Итерация по результатам запроса и вывод информации о каждой книге.
 * 
 * @var array $book Ассоциативный массив с данными о книге (id, title, category_id, author).
 */
while ($book = $query->fetch(PDO::FETCH_ASSOC)) {
    echo "<div class='book'>";
    echo "<h3><a href='/show/{$book['id']}'>{$book['title']}</a></h3>";
    echo "<p>Category: {$book['category_id']}</p>";
    echo "<p>Author: {$book['author']}</p>";
    echo "</div>";
}

/**
 * Завершение буферизации вывода и сохранение контента в переменную $content.
 * 
 * @var string $content Сохраненный HTML-контент страницы.
 */
$content = ob_get_clean();

/**
 * Включение основного шаблона страницы.
 * Контент страницы ($content) будет вставлен в шаблон layout.php.
 */
include 'layout.php';
