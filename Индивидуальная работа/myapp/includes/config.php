<?php

/**
 * Устанавливает соединение с базой данных MySQL.
 *
 * @param string $host     Хост базы данных.
 * @param string $dbname   Имя базы данных.
 * @param string $username Имя пользователя для подключения.
 * @param string $password Пароль пользователя.
 *
 * @return PDO Возвращает объект PDO для работы с базой данных.
 * @throws PDOException Если подключение не удастся, будет выброшено исключение.
 */
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

// Параметры подключения
$host = 'localhost';
$dbname = 'myapp';
$username = 'root'; 
$password = 'secret';

// Выполнение функции подключения к базе данных
$pdo = createDatabaseConnection($host, $dbname, $username, $password);
