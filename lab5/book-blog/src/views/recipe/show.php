<?php
   require_once '../config/db.php';
   require_once '../db.php';
   require_once '../helpers.php';
   // Подключаемся к PostgreSQL
    $pdo = getPostgreSQLConnection();
    // Получаем информацию о книге
    $book_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
    if (!$book_id) {
        exit("Book not found!");
    }

    $stmt = $pdo->prepare("SELECT * FROM books WHERE id = ?");
    $stmt->execute([$book_id]);
    $book = $stmt->fetch(PDO::FETCH_ASSOC);

    // Получаем список пользователей для выпадающего списка
    $stmt = $pdo->query("SELECT id, name FROM users ORDER BY name");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
   ?>

<?php
    if(empty($id)) {
        exit("Book not found!");
    }
    $pdo = getMySQLConnection();
    $stmt = $pdo->prepare("SELECT * FROM books WHERE id = ?");
    $stmt->execute([$id]);
    $book = $stmt->fetch(PDO::FETCH_ASSOC);
?>
<?php ob_start(); ?>


<h2><?= htmlspecialchars($book['title']) ?></h2>

<p><strong>Category ID:</strong> <?= htmlspecialchars($book['category_id']) ?></p>
<p><strong>Author:</strong> <?= htmlspecialchars($book['author']) ?></p>
<p><strong>Description:</strong> <?= nl2br(htmlspecialchars($book['description'])) ?></p>
<p><strong>Tags:</strong> <?= htmlspecialchars($book['tags']) ?></p>

<a href="/edit/<?= $book['id'] ?>">Edit</a> 
<a href="/delete/<?= $book['id'] ?>">Delete</a>

<h3>Rate this Book</h3>
<form action="/handlers/recipe/rate.php" method="post">
    <input type="hidden" name="book_id" value="<?= htmlspecialchars($book['id']) ?>">

    <label for="user_id">Select User:</label>
    <select name="user_id" id="user_id" required>
        <option value="" disabled selected>Choose user</option>
        <?php foreach ($users as $user): ?>
            <option value="<?= $user['id'] ?>">
                <?= htmlspecialchars($user['name']) ?> (ID: <?= $user['id'] ?>)
            </option>
        <?php endforeach; ?>
    </select><br><br>

    <label for="rating">Rating (1-5):</label>
    <input type="number" id="rating" name="rating" min="1" max="5" required><br><br>

    <label for="comment">Comment:</label>
    <textarea id="comment" name="comment"></textarea><br><br>

    <button type="submit">Submit Rating</button>
</form>


<?php $content = ob_get_clean(); ?>
<?php include '../templates/layout.php'; ?>