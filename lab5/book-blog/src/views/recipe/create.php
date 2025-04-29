<?php
/**
 * Проверяет, была ли отправлена форма методом POST.
 * Если данные отправлены, подключает обработчик создания рецепта
 * и завершает выполнение скрипта.
 */
if (!empty($_POST)) {
    include_once "../handlers/recipe/create.php";
    exit();
}
/**
 * Подключается к базе данных и получает список категорий.
 * Если подключение не удалось, выводит сообщение об ошибке и завершает выполнение.
 * Если категории отсутствуют, выводит соответствующее сообщение.
 *
 * @throws PDOException Если не удалось подключиться к базе данных.
 */
try {
    $pdo = getMySQLConnection();
    $categories = $pdo->query("SELECT id, name FROM categories")->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error connecting to the database: " . $e->getMessage());
}

// Проверяем, есть ли категории
if (empty($categories)) {
    echo "No categories found!";
}
/**
 * HTML-форма для добавления новой книги.
 * Поля формы:
 * - title (string): Название книги (обязательное поле).
 * - category_id (int): Идентификатор категории (обязательное поле).
 * - author (string): Автор книги (необязательное поле).
 * - description (string): Описание книги (необязательное поле).
 * - tags (string): Теги книги (необязательное поле).
 *
 * Данные отправляются методом POST на маршрут "/create".
 */
?>
<form action="/create" method="POST">
    <label for="title">Title:</label>
    <input type="text" name="title" required><br><br>

    <label for="category_id">Category:</label>
    <select name="category_id" required>
        <option value="">-- Select Category --</option>
        <?php foreach ($categories as $cat): ?>
            <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
        <?php endforeach; ?>
    </select><br><br>

    <label for="author">Author:</label>
    <input type="text" name="author"><br><br>

    <label for="description">Description:</label>
    <textarea name="description"></textarea><br><br>

    <label for="tags">Tags:</label>
    <textarea name="tags"></textarea><br><br>

    <button type="submit">Add Book</button>
</form>

<?php
/**
 * Буферизирует вывод HTML-контента и подключает общий шаблон.
 * Шаблон layout.php использует переменную $content для отображения содержимого.
 */
$content = ob_get_clean();
include '../templates/layout.php';

