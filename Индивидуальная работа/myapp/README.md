# Индивидуальная работа

> Подготовили: Доцен Анна и Виктор Купчишин
> Группа IA2303

## Описание выполнения работы

1) Создаем базу данных ```myapp```

    ```sql
    CREATE DATABASE IF NOT EXISTS myapp;
    ```

2) Создаем две таблицы для хранения данных:

- Таблица хранения данных пользователя:

    ```sql
    CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(255) NOT NULL UNIQUE,
        email VARCHAR(255) NOT NULL UNIQUE,    
        password VARCHAR(255) NOT NULL,       
        role ENUM('user', 'admin') DEFAULT 'user',  
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP  
    );
    ```

- Таблица для хранения статей

    ```sql
    CREATE TABLE articles (
        id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(255) NOT NULL,
        content TEXT NOT NULL,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    );
    ALTER TABLE articles ADD COLUMN user_id INT NOT NULL;
    ALTER TABLE articles ADD COLUMN category VARCHAR(100) NOT NULL DEFAULT 'Без категории';
    ALTER TABLE articles ADD COLUMN is_public TINYINT(1) NOT NULL DEFAULT 1;
    ```

    Созданные таблицы в БД
    ![Img](https://imgur.com/tqBkgwN.png)

3) Создаем понятную и удобную структуру проекта

    ```plaintext
    │   index.php
    │   README.md
    │
    ├───assets
    │       style.css
    │
    ├───includes
    │       config.php
    │       functions.php
    │       validate.js
    │       validate_article.js
    │
    └───public
        │   admin.php
        │   dashboard.php
        │   input.php
        │   logout.php
        │   profile.php
        │   register.php
        │
        └───article
                create_article.php
                delete_article.php
                edit_article.php
                view_article.php
    ```

4) В файле ```config.php``` устанавливаем соединение с базой данных MySQL

    ```php
    function createDatabaseConnection(string $host, string $dbname, string $username, string $password): PDO
    {
        try {
            // Создаем подключение к базе данных MySQL с использованием переданных данных.
            $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
            // Устанавливаем режим обработки ошибок.
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $pdo;
        } catch (PDOException $e) {
            // Если не удалось подключиться, выводим ошибку и завершаем выполнение.
            echo "Ошибка подключения: " . $e->getMessage();
            exit;
        }
    }
    ```

5) В файле ```functions.php``` создаем функции для работы приложения

Функция для регистрации пользователя:

```php
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
```

Функция ```fetch()``` извлекает первую найденную строку из результата запроса. Если такая строка есть (то есть пользователь уже зарегистрирован с таким ```username``` или ```email```), ```fetch()``` вернёт массив данных, а условие ```if``` даст ```true```.

6) Создаем файлы для установки валидации, в файле ```validate_article.js``` устанавливаем валидацию формы создания статьи, а в файле ```validate.js``` делаем валидацию для формы поиска статьи.

7) Создаем файлы для работы со статьями:
   - Если пользователь хочет добавить статью не войдя в систему, ему выйдет предупреждение, что сначала необходимо войти в систему
  
    ```php
    if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = "Сначала войдите в систему.";
    header("Location: ../input.php");
    exit;
    }
    ```

    - Проверяем введенный заголовок на то, чтобы он не был пустым, также устанавоиваем ограничение по длине
  
    ```php
    if (empty($title)) {
        $errors[] = "Заголовок обязателен.";
    } elseif (mb_strlen($title) > 255) {
        $errors[] = "Заголовок не должен превышать 255 символов.";
    }
    ```

    - Устанавливаем, что контент должен быть больше 20 символов

    ```php
     if (mb_strlen($content) < 20) {
        $errors[] = "Содержимое должно быть не менее 20 символов.";
    }
    ```

    - Удаляем статью из БД
  
    ```php
    $sql = "DELETE FROM articles WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$articleId]);

    $_SESSION['message'] = 'Статья успешно удалена.';
        } else {
            $_SESSION['error'] = 'Статья не найдена или у вас нет прав для её удаления.';
        }
    else {
        $_SESSION['error'] = 'Неверный идентификатор статьи.';
    }
    ```

    - Редактируем статью
  
    ```php
    $sql = "UPDATE articles SET title = :title, content = :content WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':title' => $title,
        ':content' => $content,
        ':id' => $articleId
    ]);

    $_SESSION['success'] = "Статья успешно обновлена!";
    header("Location: view_article.php?id=" . $articleId);
    exit;
    else {
        $error = "Пожалуйста, заполните все поля.";
    }
    ```

8) Создаем файлы для работы с пользователями

   - Регистрация пользователя, используем обработку POST-запроса для регистарции, также, если у пользователя ```admin```, то он может добавить новых пользователей самостоятельно, также он им может присвоить роль администратора или обучного пользователя

   ```php
   if ($_SERVER['REQUEST_METHOD'] === 'POST')
    $username = $_POST['username']; 
    $email = $_POST['email'];
    $password = $_POST['password']; 
    
    // Роль по умолчанию — пользователь
    $role = 'user';
    // Если администратор, может назначать роль
    if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin' && !empty($_POST['role'])) {
        $role = $_POST['role'] === 'admin' ? 'admin' : 'user';
    }
    ```

    - Вход в систему, производим проверку существования пользователя в базе, если он в ней есть и введ верный пароль и логин, то он входит в личный кабинет, в противном случае, ему выходит надпись, что он либо *не зарегистрирован*, либо, что ввел *не верный пароль*
  
    ```php
    if (!$user) {
        $_SESSION['error'] = "Этот пользователь не зарегистрирован!";
        header("Location: register.php");
        exit;
    }

    if (!password_verify($password, $user['password'])) {
        $_SESSION['error'] = "Неверный пароль!";
        header("Location: input.php");
        exit;
    }
    ```

    - Создаем admi-панель, у администартора есть права по удалению и добавлению пользователя, также он может добавить, удалить и отредактировать статьи

### Примеры использования проекта

1) Для запуска проекта, заходим в терминал и вводим следующее ```php -S localhost:8080```
2) Стартовая страница
   ![Img-1](https://imgur.com/ht25lQK.png)
3) Выходит предупрежнение, что для поиска статьи необходимо заполнить хотя бы одно поле
   ![Img-2](https://imgur.com/aXtcdFa.png) ![Img-3](https://imgur.com/KWn8FIA.png)
4) Нельзя добавить статью, если не вошел в систему
   ![Img-4](https://imgur.com/Be61wyC.png)
5) После входа переходим на страницу и видим приветствие
   ![Img-5](https://imgur.com/93uql6m.png)
6) В admin-панеле администратор может добавить/удалить пользователя, также удалить/отредактировать статью
   ![Img-6](https://imgur.com/BhdrvIS.png)
   В профиле можно добавить статью
   ![Img-7](https://imgur.com/Q9GmLHc.png)
   Также, после выхода из системы выходит следующее
    ![Img-8](https://imgur.com/5OnABzd.png)
7) Если зайти в профиль, как простой пользователь и нажать на кнопку для редактирования статьи, видим следующее
   ![Img-9](https://imgur.com/gvo3o7h.png)

#### Использованные источники

[Link-1](https://moodle.usm.md/course/view.php?id=7161), [Link-2](https://github.com/MSU-Courses/advanced-web-programming), [Link-3](https://moodle.usm.md/course/view.php?id=7254)
  