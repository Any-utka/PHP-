<?php
/**
 * Подключение конфигурации базы данных.
 * Файл содержит параметры для подключения к базе данных.
 */
require_once '../config/db.php';

/**
 * Подключение функций для работы с базой данных.
 * Файл содержит вспомогательные функции для выполнения запросов к БД.
 */
require_once '../db.php';

/**
 * Подключение вспомогательных функций.
 * Файл содержит функции общего назначения, используемые в проекте.
 */
require_once '../helpers.php';

/**
 * Получение URI запроса и разбиение его на части.
 * 
 * @var string $requestUri Полный URI запроса.
 * @var array $chunks Массив частей URI, разделенных символом "/".
 * @var string $request Первый сегмент URI, определяющий маршрут.
 */
$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$chunks = array_filter(explode("/", trim($requestUri, "/"))); // удаляем пустые
$chunks = array_values($chunks); // переиндексация массива
$request = $chunks[0] ?? '';

/**
 * Простейшая маршрутизация.
 * В зависимости от значения $request подключается соответствующий файл.
 * 
 * @switch string $request Первый сегмент URI, определяющий маршрут.
 */
switch ($request) {

    /**
     * Главная страница.
     * Подключает шаблон главной страницы.
     */
    case '':
    case 'index.php':
        include '../templates/index.php';
        break;

    /**
     * Страница создания книги.
     * Подключает форму для создания нового книги.
     */
    case 'create':
        include '../views/recipe/create.php';
        break;

    /**
     * Страница редактирования книги.
     * 
     * @var int|null $id Идентификатор книги для редактирования.
     * Если ID отсутствует или некорректен, выводится сообщение об ошибке.
     */
    case 'edit':
        $id = $chunks[1] ?? null;
        if ($id && is_numeric($id)) {
            $_GET['id'] = (int)$id;
            include '../views/recipe/edit.php';
        } else {
            echo "Некорректный ID для редактирования.";
        }
        break;

    /**
     * Страница просмотра книги.
     * 
     * @var int|null $id Идентификатор книги для просмотра.
     * Если ID отсутствует или некорректен, выводится сообщение об ошибке.
     * Если в URI присутствует сегмент "rate", подключается обработчик оценки книги.
     */
    case 'show':
        $id = $chunks[1] ?? null;
        if (isset($chunks[3]) && $chunks[3] == 'rate') {
            include '../handlers/recipe/rate.php';
            break;
        }
        if ($id && is_numeric($id)) {
            $_GET['id'] = (int)$id;
            include '../views/recipe/show.php';
        } else {
            echo "Некорректный ID для просмотра.";
        }
        break;

    /**
     * Удаление книги.
     * 
     * @var int|null $id Идентификатор книги для удаления.
     * Если ID отсутствует или некорректен, выводится сообщение об ошибке.
     */
    case 'delete':
        $id = $chunks[1] ?? null;
        if ($id && is_numeric($id)) {
            $_GET['id'] = (int)$id;
            include '../handlers/recipe/delete.php';
        } else {
            echo "Некорректный ID для удаления.";
        }
        break;

    /**
     * Оценка книги.
     * Подключает обработчик оценки книги.
     */
    case 'rate':
        include '../handlers/recipe/rate.php';
        break;

    /**
     * Обработка неизвестного маршрута.
     * Устанавливает код ответа 404 и выводит сообщение об ошибке.
     */
    default:
        http_response_code(404);
        echo "Страница не найдена!";
        break;
}
