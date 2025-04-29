<?php

/**
 * Устанавливает соединение с базой данных MySQL.
 *
 * @return PDO Возвращает объект PDO для взаимодействия с базой данных.
 * @throws PDOException Если соединение с базой данных не удалось, выбрасывается исключение.
 */
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

/**
 * Устанавливает соединение с базой данных PostgreSQL.
 *
 * @return PDO Возвращает объект PDO для взаимодействия с базой данных.
 * @throws PDOException Если соединение с базой данных не удалось, выбрасывается исключение.
 */
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

/**
 * Получает всех пользователей из базы данных.
 *
 * Эта функция устанавливает соединение с базой данных PostgreSQL,
 * выполняет SQL-запрос для получения всех записей из таблицы "users"
 * и возвращает их в виде ассоциативного массива.
 *
 * @return array Ассоциативный массив, содержащий данные всех пользователей.
 * @throws PDOException Если возникает ошибка при выполнении SQL-запроса.
 */
function getAllUsers()
{
    $conn = getPostgreSQLConnection();
    $stmt = $conn->prepare("SELECT * FROM users");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
