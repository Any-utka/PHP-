<?php
    /**
     * Проверяет, является ли переменная $id пустой.
     * Если $id пустой, завершает выполнение скрипта с сообщением об ошибке.
     *
     * @param mixed $id Идентификатор книги, который должен быть проверен.
     * @return void
     */
    if (empty($id)) {
        exit("Book not found!");
    }

    /**
     * Создает подключение к базе данных MySQL и выполняет запрос для получения данных книги.
     * Подготавливает SQL-запрос для выборки книги по идентификатору и выполняет его.
     *
     * @param PDO $pdo Объект подключения к базе данных.
     * @param int $id Идентификатор книги, используемый в запросе.
     * @return array|false Возвращает массив с данными книги или false, если книга не найдена.
     */
    $pdo = getMySQLConnection();
    $stmt = $pdo->prepare("SELECT * FROM books WHERE id = ?");
    $stmt->execute([$id]);
    $book = $stmt->fetch(PDO::FETCH_ASSOC);

    /**
     * Отображает информацию о книге, включая заголовок, категорию, автора, описание и теги.
     * Также предоставляет ссылки для редактирования и удаления книги.
     *
     * @var array $book Массив с данными книги, полученными из базы данных.
     */
ob_start(); ?>


<h2><?= htmlspecialchars($book['title']) ?></h2>

<p><strong>Category ID:</strong> <?= htmlspecialchars($book['category_id']) ?></p>
<p><strong>Author:</strong> <?= htmlspecialchars($book['author']) ?></p>
<p><strong>Description:</strong> <?= nl2br(htmlspecialchars($book['description'])) ?></p>
<p><strong>Tags:</strong> <?= htmlspecialchars($book['tags']) ?></p>

<a href="/edit/<?= $book['id'] ?>">Edit</a> 
<a href="/delete/<?= $book['id'] ?>">Delete</a>


<h3>Rate this Book</h3>
<form action="handlers/recipe/rate" method="POST">
    <input type="hidden" name="book_id" value="<?= $book['id'] ?>">
    
    <label for="user_id">User ID:</label>
    <input type="number" name="user_id" required><br><br>

    <label for="rating">Rating (1-5):</label>
    <input type="number" name="rating" min="1" max="5" required><br><br>

    <label for="comment">Comment:</label>
    <textarea name="comment"></textarea><br><br>

    <button type="submit">Submit Rating</button>
</form>


<?php $content = ob_get_clean(); ?>
<?php include '../templates/layout.php'; ?>