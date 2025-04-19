<?php

// Подключение к MySQL
function getMySQLConnection() {
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

// Подключение к PostgreSQL
function getPostgreSQLConnection() {
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
