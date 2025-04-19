<?php
if(!empty($_POST)) {
    include_once "../handlers/recipe/create.php";
    exit();
}
?>

<?php ob_start(); ?>

<h2>Add a New Book</h2>

<?php
// Подключаемся к базе данных и пытаемся получить категории
try {
    $pdo = getMySQLConnection(); // Подключение к MySQL
    $categories = $pdo->query("SELECT id, name FROM categories")->fetchAll(PDO::FETCH_ASSOC);
    
/*    // Для отладки — выводим содержимое массива категорий
    echo '<pre>';
    var_dump($categories); // Выводим данные категорий для отладки
    echo '</pre>';*/
} catch (PDOException $e) {
    die("Error connecting to the database: " . $e->getMessage());
}

// Проверяем, есть ли категории
if (empty($categories)) {
    echo "No categories found!";
}
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

<?php $content = ob_get_clean(); ?>
<?php include '../templates/layout.php'; ?>
