<?php

require_once '../config/db.php'; // Подключаем конфигурацию для работы с БД
require_once '../db.php';
require_once '../helpers.php'; // Подключаем вспомогательные функции

// Простейшая маршрутизация
$r = $_SERVER['REQUEST_URI'];
$chunks = explode("/", $r);
$request = $chunks[1];
file_put_contents('php://stderr', "Request URI: $request\n"); // Отладочная информация

switch ($request) {
    case '':
        include '../templates/index.php';
        break;
    case 'create':
        include '../views/recipe/create.php';
        break;
    case 'edit':
        $id = $chunks[2] ?? null;
        include '../views/recipe/edit.php';
        break;
    case 'show':
        $id = $chunks[2] ?? null;
        include '../views/recipe/show.php';
        break;
    case 'delete':
        $id = $chunks[2] ?? null;
        include '../handlers/recipe/delete.php';
        break;
    default:
        http_response_code(404);
        echo "Page not found";
        break;
}