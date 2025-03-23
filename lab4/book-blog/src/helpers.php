<?php

/**
 * Функция для валидации данных формы.
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
 */
function saveDataToFile($filePath, $bookData)
{
    $data = json_encode($bookData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    // Добавляем данные в конец файла
    return file_put_contents($filePath, $data . PHP_EOL, FILE_APPEND);
}
