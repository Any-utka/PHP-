<?php
// Подключаем PHP-логику из файла
require_once('function.php'); // Предполагаем, что все функции вынесены в functions.php

?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Лабораторная № 3</title>
</head>
<body>
    <h1>Банковские транзакции</h1>
    
    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Дата</th>
                <th>Сумма</th>
                <th>Описание</th>
                <th>Организация</th>
                <th>Дней прошло</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($transactions as $transaction): ?>
                <tr>
                    <td><?= htmlspecialchars($transaction['id']) ?></td>
                    <td><?= htmlspecialchars($transaction['date']) ?></td>
                    <td><?= number_format($transaction['amount'], 2, '.', ' ') ?></td>
                    <td><?= htmlspecialchars($transaction['description']) ?></td>
                    <td><?= htmlspecialchars($transaction['merchant']) ?></td>
                    <td><?= daysSinceTransaction($transaction['date']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="5"><strong>Сумма всех транзакций:</strong></td>
                <td><strong><?= number_format(calculateTotalAmount($transactions), 2, '.', ' ') ?></strong></td>
            </tr>
        </tfoot>
    </table>

    <?php
    sortTransactionsByDate();
    ?>
    <h2>Транзакции, отсортированные по дате:</h2>
    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Дата</th>
                <th>Сумма</th>
                <th>Описание</th>
                <th>Организация</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($transactions as $transaction): ?>
                <tr>
                    <td><?= htmlspecialchars($transaction['id']) ?></td>
                    <td><?= htmlspecialchars($transaction['date']) ?></td>
                    <td><?= number_format($transaction['amount'], 2, '.', ' ') ?></td>
                    <td><?= htmlspecialchars($transaction['description']) ?></td>
                    <td><?= htmlspecialchars($transaction['merchant']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <?php
    sortTransactionsByAmountDesc();
    ?>
    <h2>Транзакции, отсортированные по сумме (по убыванию):</h2>
    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Дата</th>
                <th>Сумма</th>
                <th>Описание</th>
                <th>Организация</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($transactions as $transaction): ?>
                <tr>
                    <td><?= htmlspecialchars($transaction['id']) ?></td>
                    <td><?= htmlspecialchars($transaction['date']) ?></td>
                    <td><?= number_format($transaction['amount'], 2, '.', ' ') ?></td>
                    <td><?= htmlspecialchars($transaction['description']) ?></td>
                    <td><?= htmlspecialchars($transaction['merchant']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <h2>Галерея изображений</h2>
    <nav class="navbar">
        <a href="#">About Cats</a> |
        <a href="#">News</a> |
        <a href="#">Contacts</a>
    </nav>

    <h3>#cats</h3>
    <p class="subtext">Explore a world of cats</p>

    <table border="1" class="image-table">
    <tbody>
        <?php
        $columns = 3; 
        $count = count($images);
        
        // Печатаем для отладки количество изображений
        echo "<!-- Всего изображений: $count -->";
        
        // Разбиваем массив на строки с количеством столбцов
        for ($i = 0; $i < $count; $i += $columns): ?>
            <tr>
                <?php for ($j = 0; $j < $columns; $j++): ?>
                    <td>
                        <?php 
                        $index = $i + $j;  // Индекс изображения в массиве
                        if (isset($images[$index])):  // Проверяем, что изображение существует по этому индексу
                            echo "<img src='" . htmlspecialchars($images[$index]) . "' alt='Изображение'>";
                        endif;
                        ?>
                    </td>
                <?php endfor; ?>
            </tr>
        <?php endfor; ?>
    </tbody>
</table>

    <footer>
        USM &copy; 2025
    </footer>
</body>
</html>
