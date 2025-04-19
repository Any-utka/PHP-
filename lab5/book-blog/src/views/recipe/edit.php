<?php
if(!empty($_POST)) {
    include_once "../handlers/recipe/edit.php";
    exit();
}
?> 

<?php ob_start(); ?>

<h2>Edit Book</h2>

<?php
// $id = $_GET['id'] ?? null;
// Получаем книгу по ID
if (empty($id)) {
    exit("Book not found!");
}

$pdo = getMySQLConnection();
$stmt = $pdo->prepare("SELECT * FROM books WHERE id = ?");
$stmt->execute([$id]);
$book = $stmt->fetch(PDO::FETCH_ASSOC);

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

<?php $content = ob_get_clean(); ?>
<?php include '../templates/layout.php'; ?>
