<?php
/**
 * Проверяет, есть ли данные в массиве $_POST, и если да, подключает обработчик редактирования рецепта.
 * Завершает выполнение скрипта после подключения обработчика.
 */
if (!empty($_POST)) {
    include_once "../handlers/recipe/edit.php";
    exit();
}
ob_start(); 
/**
 * Проверяет, передан ли идентификатор книги. Если идентификатор отсутствует, завершает выполнение скрипта с сообщением об ошибке.
 */
if (empty($id)) {
    exit("Book not found!");
}

/**
 * Создает подключение к базе данных MySQL и выполняет запрос для получения данных книги по её идентификатору.
 * 
 * @var PDO $pdo Объект подключения к базе данных.
 * @var PDOStatement $stmt Подготовленный запрос для получения данных книги.
 * @var array $book Ассоциативный массив с данными книги.
 */
$pdo = getMySQLConnection();
$stmt = $pdo->prepare("SELECT * FROM books WHERE id = ?");
$stmt->execute([$id]);
$book = $stmt->fetch(PDO::FETCH_ASSOC);

/**
 * Форма для редактирования книги.
 * 
 * @var string $book['id'] Идентификатор книги.
 * @var string $book['title'] Название книги.
 * @var string $book['category_id'] Идентификатор категории книги.
 * @var string $book['author'] Автор книги.
 * @var string $book['description'] Описание книги.
 * @var string $book['tags'] Теги книги.
 */
?>
<form action="/edit/<?= $book['id'] ?>" method="POST">
    <label for="title">Title:</label>
    <input type="text" name="title" value="<?= htmlspecialchars($book['title']) ?>" required><br><br>

    <label for="category_id">Category ID:</label>
    <input type="text" name="category_id" value="<?= htmlspecialchars($book['category_id']) ?>" required><br><br>

    <label for="author">Author:</label>
    <input type="text" name="author" value="<?= htmlspecialchars($book['author']) ?>"><br><br>

    <label for="description">Description:</label>
    <textarea name="description"><?= htmlspecialchars($book['description']) ?></textarea><br><br>

    <label for="tags">Tags:</label>
    <textarea name="tags"><?= htmlspecialchars($book['tags']) ?></textarea><br><br>

    <button type="submit">Update Book</button>
</form>

<?php
/**
 * Завершает буферизацию вывода и сохраняет содержимое в переменную $content.
 * Подключает шаблон layout.php для отображения содержимого страницы.
 * 
 * @var string $content Содержимое страницы.
 */
$content = ob_get_clean();
include '../templates/layout.php';
