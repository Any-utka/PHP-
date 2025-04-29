<?php

/**
 * Регистрирует нового пользователя.
 *
 * Проверяет, существует ли уже пользователь с таким же именем или email, и если нет,
 * создает нового пользователя с хешированным паролем.
 *
 * @param string $username Имя пользователя.
 * @param string $email Адрес электронной почты пользователя.
 * @param string $password Пароль пользователя.
 * @param string $role Роль пользователя (по умолчанию 'user').
 *
 * @return void
 */
function registerUser(string $username, string $email, string $password, string $role = 'user'): void {
    global $pdo;

    // Проверка: существует ли уже такой username или email
    $sql = "SELECT * FROM users WHERE username = :username OR email = :email";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':username' => $username,
        ':email' => $email
    ]);

    if ($stmt->fetch()) {
        echo "Пользователь с таким именем или email уже существует!";
        return;
    }

    // Хеширование пароля
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Вставка нового пользователя
    $sql = "INSERT INTO users (username, email, password, role) VALUES (:username, :email, :password, :role)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $hashedPassword);
    $stmt->bindParam(':role', $role);

    $stmt->execute();
}

/**
 * Аутентифицирует пользователя по email и паролю.
 *
 * Проверяет правильность пароля и, если все верно, сохраняет информацию о пользователе в сессии.
 *
 * @param string $email Адрес электронной почты пользователя.
 * @param string $password Пароль пользователя.
 *
 * @return bool Возвращает true, если аутентификация успешна, иначе false.
 */
function loginUser(string $email, string $password): bool {
    global $pdo;

    // Получаем пользователя из БД по email
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        // Сохраняем информацию о пользователе в сессии
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        return true;
    }
    return false;
}

/**
 * Проверяет, авторизован ли пользователь.
 *
 * @return bool Возвращает true, если пользователь авторизован, иначе false.
 */
function isAuthenticated(): bool {
    return isset($_SESSION['user_id']);
}

/**
 * Завершается сессия пользователя.
 *
 * Удаляет все данные сессии и уничтожает сессию.
 *
 * @return void
 */
function logoutUser(): void {
    session_unset();
    session_destroy();
}

/**
 * Получает всех пользователей, исключая администраторов.
 *
 * @return array Массив пользователей.
 */
function getAllUsers(): array {
    global $pdo;
    $sql = "SELECT * FROM users WHERE role != 'admin'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Удаляет пользователя по ID.
 *
 * @param int $userId ID пользователя, которого нужно удалить.
 *
 * @return void
 */
function deleteUser(int $userId): void {
    global $pdo;
    $sql = "DELETE FROM users WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $userId);
    $stmt->execute();
}

/**
 * Получает все статьи, отсортированные по дате создания (от новых к старым).
 *
 * @return array Массив статей.
 */
function getAllArticles(): array {
    global $pdo;
    $sql = "SELECT * FROM articles ORDER BY created_at DESC";
    $stmt = $pdo->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Получает статью по ID.
 *
 * @param int $articleId ID статьи.
 *
 * @return array|null Статья в виде ассоциативного массива или null, если статья не найдена.
 */
function getArticleById(int $articleId): ?array {
    global $pdo;
    $sql = "SELECT * FROM articles WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $articleId]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

/**
 * Добавляет новую статью в базу данных.
 *
 * @param string $title Заголовок статьи.
 * @param string $content Контент статьи.
 *
 * @return void
 */
function addArticle(string $title, string $content): void {
    global $pdo;
    $sql = "INSERT INTO articles (title, content, created_at) VALUES (:title, :content, NOW())";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':content', $content);
    $stmt->execute();
}

/**
 * Редактирует существующую статью.
 *
 * @param int $articleId ID статьи для редактирования.
 * @param string $title Новый заголовок статьи.
 * @param string $content Новый контент статьи.
 *
 * @return void
 */
function editArticle(int $articleId, string $title, string $content): void {
    global $pdo;
    $sql = "UPDATE articles SET title = :title, content = :content WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':content', $content);
    $stmt->bindParam(':id', $articleId);
    $stmt->execute();
}

/**
 * Удаляет статью по ID.
 *
 * @param int $articleId ID статьи, которую нужно удалить.
 *
 * @return void
 */
function deleteArticle(int $articleId): void {
    global $pdo;
    $sql = "DELETE FROM articles WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $articleId);
    $stmt->execute();
}
