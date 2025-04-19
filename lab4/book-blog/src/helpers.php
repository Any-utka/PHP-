<?php

/**
 * Функция для валидации данных формы.
 * 
 * Проверяет, что все обязательные поля (название, автор, жанр и описание) заполнены.
 * Возвращает массив ошибок, если какие-либо поля пустые.
 *
 * @param string $title
 * @param string $author 
 * @param string $genre 
 * @param string $description 
 * 
 * @return array Массив ошибок, если есть пустые поля.
 */
function validateForm($title, $author, $genre, $description)
{
    $errors = [];

    if (empty($title)) {
        $errors[] = 'Название книги не может быть пустым.';
    }

    if (empty($author)) {
        $errors[] = 'Автор не может быть пустым.';
    }

    if (empty($genre)) {
        $errors[] = 'Жанр книги не может быть пустым.';
    }

    if (empty($description)) {
        $errors[] = 'Описание книги не может быть пустым.';
    }

    return $errors;
}

/**
 * Функция для сохранения данных в файл.
 * 
 * Записывает данные о книге в файл в формате JSON.
 * Добавляет данные в конец файла с сохранением форматирования.
 *
 * @param string $filePath Путь к файлу для сохранения данных.
 * @param array $bookData Данные книги, которые нужно сохранить.
 * 
 * @return bool|int Возвращает количество записанных байтов, или `false` в случае ошибки.
 */
function saveDataToFile($filePath, $bookData)
{
    // Преобразование данных книги в формат JSON с красивым форматированием и без экранирования юникода
    $data = json_encode($bookData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

    // Запись данных в файл
    return file_put_contents($filePath, $data . PHP_EOL, FILE_APPEND);
}
