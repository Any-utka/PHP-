# Лабораторная работа №5, Работа с базой данных

> Доцен Анна

## Цель работы: Освоить архитектуру с единой точкой входа, подключение шаблонов для визуализации страниц, а также переход от хранения данных в файле к использованию базы данных

## Задание

1) Подготавливаем среду, создаем таблицы в БД, в них будет хранится вся информация
2) Подключаемся к БД
3) Реализуем следующие операции: добавление, удаление, редактирование, добавление отзывов на книгу

### Описание выполнения работы

1) Создаем две БД:
Одна таблица для хранения данных о книгах

```sql
CREATE DATABASE IF NOT EXISTS library_mysql;

USE library_mysql;

CREATE TABLE IF NOT EXISTS categories (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL UNIQUE,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS books (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(255) NOT NULL,
  category_id INT NOT NULL,
  author VARCHAR(255),
  description TEXT,
  tags TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
);
```

И еще одна таблица для хранения рейтинга и отзывов

```sql
CREATE DATABASE library_pg;


CREATE TABLE if not exists users (
  id SERIAL PRIMARY KEY,
  username VARCHAR(100) NOT NULL UNIQUE,
  email VARCHAR(255),
  registered_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE if not exists book_ratings (
  id SERIAL PRIMARY KEY,
  book_id INT NOT NULL,
  user_id INT NOT NULL,
  rating INT CHECK (rating BETWEEN 1 AND 5),
  comment TEXT,
  rated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

2) Создаем файл *db.php*, в котором подключчаем БД, также в файле *db.php*, где пишем функции для работы с БД

```php
function getMySQLConnection()
{
    $host = MYSQL_HOST;
    $dbname = MYSQL_DBNAME;
    $user = MYSQL_USER;
    $password = MYSQL_PASSWORD;

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        die("MySQL Connection failed: " . $e->getMessage());
    }
}
```

Эта функция устанавливает соединение с базой данных MySQL

```php
function getPostgreSQLConnection()
{
    $host = '127.0.0.1';
    $dbname = 'library_pg';
    $user = 'ANNA';
    $password = '1234';

    try {
        $pdo = new PDO("pgsql:host=$host;dbname=$dbname", $user, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        die("PostgreSQL Connection failed: " . $e->getMessage());
    }
}
```

Эта функция устанавливает соединение с базой данных PostgreSQL.

3) Создаем подключение к базе данных MySQL и выполняет вставку новой записи в таблицу `books`.

```php
$pdo = getMySQLConnection();
$stmt = $pdo->prepare("INSERT INTO books (title, category_id, author, description, tags) 
                            VALUES (?, ?, ?, ?, ?)");
$stmt->execute([$title, $category_id, $author, $description, $tags]);
$book_id = $pdo->lastInsertId();
```

4) Устанавливаем соединение с базой данных и выполняет SQL-запрос для обновления записи о книге.

```php
$pdo = getMySQLConnection();
$stmt = $pdo->prepare("UPDATE books 
                        SET title = ?, category_id = ?, author = ?, description = ?, tags = ? 
                        WHERE id = ?");
$stmt->execute([$title, $category_id, $author, $description, $tags, $id]);
```

5) Удаляем запись из таблицы "books" по указанному идентификатору.

```php
if (isset($id)) {
    $pdo = getMySQLConnection();
    $stmt = $pdo->prepare("DELETE FROM books WHERE id = ?");
    $stmt->execute([$id]);

    header("Location: /");
}
```

6) Запускаем проект через терминал при помощи команды: ```php -S localhost:8080 -t .\src\public\```

#### Ответы на контрольные вопросы

1) *Единая точка входа* — это подход, при котором все HTTP-запросы обрабатываются через один файл (например, ```index.php```, ```MainServlet```, ```DispatcherServlet``` и т. д.). Преимущества:

- Централизованная обработка запросов: легко реализовать общую логику (например, проверку авторизации, логгирование, обработку ошибок).
- Упрощение маршрутизации: централизованный контроль над URL-ами и перенаправлениями.
- Безопасность: можно фильтровать и валидировать все запросы в одном месте.
- Легче масштабировать и поддерживать: меньше дублирования логики и точек отказа.

2) **Преимущества использования шаблонов:**
*Шаблоны* (например, Thymeleaf, JSP, Handlebars) применяются для генерации HTML-страниц с динамическими данными. Их преимущества:

- Разделение логики и представления: программисты пишут бизнес-логику, а верстальщики — шаблоны.
- Повторное использование кода: шаблоны можно компоновать и наследовать (например, шапка, подвал, меню).
- Удобство локализации: легко заменять текстовые блоки.
- Безопасность: многие шаблонизаторы автоматически экранируют вводимые данные, предотвращая XSS.
  
3) **Преимущества хранения данных в базе по сравнению с файлами:**

- Поиск и фильтрация: базы данных позволяют быстро искать и сортировать информацию с помощью запросов.
- Целостность данных: ограничения (уникальность, связи, типы данных) контролируют структуру и корректность.
- Безопасность и доступ: можно контролировать доступ к данным по ролям.
- Масштабируемость: базы данных оптимизированы под работу с большими объемами данных.
- Резервное копирование и восстановление: поддерживаются системные средства резервирования.
- Транзакции: можно выполнять несколько операций как одну (атомарность).

4) *SQL-инъекция* — это уязвимость, при которой злоумышленник вставляет вредоносный SQL-код в пользовательский ввод, чтобы изменить поведение SQL-запроса.

Пример SQL-инъекции:

```php

<?php
$pdo = new PDO('mysql:host=localhost;dbname=testdb', 'root', '');

// Пользовательский ввод (например, из формы входа)
$username = $_POST['username'];
$password = $_POST['password'];

// Небезопасный SQL-запрос
$sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
$stmt = $pdo->query($sql);

if ($stmt->rowCount() > 0) {
    echo "Добро пожаловать!";
} else {
    echo "Неверный логин или пароль.";
}
```

Если пользователь введёт в поле логина ```admin' --```, то SQL станет:

```sql
SELECT * FROM users WHERE username = 'admin' --' AND password = ''
```

Код с защитой от SQL-инъекции:

```php

<?php
$pdo = new PDO('mysql:host=localhost;dbname=testdb', 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Пользовательский ввод
$username = $_POST['username'];
$password = $_POST['password'];

// Подготовленный запрос
$sql = "SELECT * FROM users WHERE username = :username AND password = :password";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':username', $username);
$stmt->bindParam(':password', $password);
$stmt->execute();

if ($stmt->rowCount() > 0) {
    echo "Добро пожаловать!";
} else {
    echo "Неверный логин или пароль.";
}
```

Здесь SQL и данные обрабатываются отдельно, что полностью исключает возможность внедрения вредоносного кода.
