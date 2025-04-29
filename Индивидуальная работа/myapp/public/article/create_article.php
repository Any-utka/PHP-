<?php
session_start();
require_once 'includes/config.php';
require_once 'includes/functions.php';

/**
 * Проверка наличия авторизации пользователя.
 * Если пользователь не авторизован, выводится сообщение об ошибке и происходит редирект на страницу входа.
 */
if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = "Сначала войдите в систему.";
    header("Location: ../input.php");
    exit;
}

// Массив доступных категорий для статей
$categories = ['Новости', 'Образование', 'Развлечения', 'Технологии'];

// Массив для хранения ошибок валидации
$errors = [];

// Проверка метода запроса на POST (форма отправлена)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Получаем и очищаем данные формы
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    $category = $_POST['category'] ?? '';
    
    // Проверка, была ли выбрана опция публичности (если нет - присваиваем 0)
    $is_public = isset($_POST['is_public']) && $_POST['is_public'] == '1' ? 1 : 0;
    
    // Получаем ID пользователя из сессии
    $userId = $_SESSION['user_id'];

    // Серверная валидация введённых данных
    // Проверка заголовка на пустоту и длину
    if (empty($title)) {
        $errors[] = "Заголовок обязателен.";
    } elseif (mb_strlen($title) > 255) {
        $errors[] = "Заголовок не должен превышать 255 символов.";
    }

    // Проверка содержимого на минимальную длину
    if (mb_strlen($content) < 20) {
        $errors[] = "Содержимое должно быть не менее 20 символов.";
    }

    // Проверка категории на допустимость
    if (!in_array($category, $categories)) {
        $errors[] = "Недопустимая категория.";
    }

    // Если ошибок нет, вставляем статью в базу данных
    if (empty($errors)) {
        // SQL-запрос для добавления новой статьи
        $sql = "INSERT INTO articles (title, content, category, is_public, created_at, user_id) 
                VALUES (:title, :content, :category, :is_public, NOW(), :user_id)";
        
        // Подготовка и выполнение SQL-запроса
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':title' => $title,
            ':content' => $content,
            ':category' => $category,
            ':is_public' => $is_public,
            ':user_id' => $userId
        ]);

        // Успешное добавление статьи, редирект на страницу профиля
        $_SESSION['success'] = "Статья успешно добавлена!";
        header("Location: ../profile.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Добавить статью</title>
    <link rel="stylesheet" href="../../assets/style.css">
</head>
<body>
<div class="container">
    <h1>Новая статья</h1>

    <?php if (!empty($errors)): ?>
        <!-- Отображение ошибок валидации -->
        <div style="color: red;">
            <ul>
                <?php foreach ($errors as $err): ?>
                    <li><?= htmlspecialchars($err) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <!-- Место для динамических ошибок при валидации на стороне клиента -->
    <div id="form-errors"></div>

    <!-- Форма для добавления статьи -->
    <form method="POST" id="create-article-form" name="articleForm">
        <!-- Поле для ввода заголовка -->
        <label for="title">Заголовок:</label><br>
        <input type="text" name="title" id="title" required maxlength="255"><br><br>

        <!-- Поле для ввода содержимого -->
        <label for="content">Содержимое:</label><br>
        <textarea name="content" id="content" rows="10" required></textarea><br><br>

        <!-- Селектор для выбора категории -->
        <label for="category">Категория:</label><br>
        <select name="category" id="category" required>
            <option value="">-- Выберите категорию --</option>
            <?php foreach ($categories as $cat): ?>
                <option value="<?= htmlspecialchars($cat) ?>"><?= htmlspecialchars($cat) ?></option>
            <?php endforeach; ?>
        </select><br><br>

        <!-- Радио кнопки для выбора публичности статьи -->
        <label>Сделать статью публичной?</label><br>
        <label><input type="radio" name="is_public" value="1" required> Да</label>
        <label><input type="radio" name="is_public" value="0"> Нет</label><br><br>

        <button type="submit">Сохранить</button>
    </form>

    <a href="../dashboard.php">Назад</a>
</div>

<script src="../../validate_article.js"></script>
</body>
</html>
