<?php
if (count($images) < 1) {
    echo "<p>В галерее пока нет изображений.</p>";
} else {
    echo "<p>Найдено " . count($images) . " изображений.</p>";
    foreach ($images as $index => $image) {
        // Выводим информацию о каждом изображении для проверки
        echo "<p>Изображение #" . ($index + 1) . ": " . htmlspecialchars($image) . "</p>";

        // Проверка существования файла перед отображением
        if (file_exists($image)) {
            echo "<img src='" . htmlspecialchars($image) . "' alt='Изображение'>";
        } else {
            echo "<p>Изображение '$image' не найдено.</p>";
        }
    }
}
