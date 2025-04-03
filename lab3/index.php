<?php
// Подключаем файл с функциями
require_once('function.php');

// Проверка на наличие транзакций в сессии
if (isset($_SESSION['transactions'])) {
    $transactions = $_SESSION['transactions']; // Доступ к транзакциям из сессии
} else {
    $transactions = []; // Если транзакции не существуют, создаём пустой массив
}

// Переменные для поиска
$foundTransactionsByDescription = [];
$foundTransactionById = null;

// Поиск транзакций по описанию
if (isset($_POST['search_description']) && !empty($_POST['description_part'])) {
    $description_part = $_POST['description_part'];
    $foundTransactionsByDescription = findTransactionByDescription($description_part);
}

// Поиск транзакции по ID
if (isset($_POST['search_id']) && !empty($_POST['transaction_id'])) {
    $transaction_id = $_POST['transaction_id'];
    $foundTransactionById = findTransactionById($transaction_id);
}

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
    // Сортировка транзакций по дате
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
    // Сортировка транзакций по сумме (по убыванию)
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

    <!-- Форма для поиска по описанию -->
    <h2>Поиск транзакций по описанию</h2>
    <form method="POST">
        <label for="description_part">Часть описания:</label>
        <input type="text" id="description_part" name="description_part" required>
        <button type="submit" name="search_description">Поиск</button>
    </form>

    <?php if (!empty($foundTransactionsByDescription)): ?>
        <h3>Результаты поиска по описанию:</h3>
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
                <?php foreach ($foundTransactionsByDescription as $transaction): ?>
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
    <?php endif; ?>

    <!-- Форма для поиска по ID -->
    <h2>Поиск транзакции по ID</h2>
    <form method="POST">
        <label for="transaction_id">ID транзакции:</label>
        <input type="number" id="transaction_id" name="transaction_id" required>
        <button type="submit" name="search_id">Поиск</button>
    </form>

    <?php if (!empty($foundTransactionById)): ?>
        <h3>Результаты поиска по ID:</h3>
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
                <?php foreach ($foundTransactionById as $transaction): ?>
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
    <?php endif; ?>

    <!-- Форма для добавления новой транзакции -->
    <h2>Добавить новую транзакцию</h2>
    <form method="POST">
        <label for="new_id">ID транзакции:</label>
        <input type="number" id="new_id" name="new_id" required><br>

        <label for="new_date">Дата (YYYY-MM-DD):</label>
        <input type="date" id="new_date" name="new_date" required><br>

        <label for="new_amount">Сумма:</label>
        <input type="number" step="0.01" id="new_amount" name="new_amount" required><br>

        <label for="new_description">Описание:</label>
        <input type="text" id="new_description" name="new_description" required><br>

        <label for="new_merchant">Организация:</label>
        <input type="text" id="new_merchant" name="new_merchant" required><br>

        <button type="submit" name="add_transaction">Добавить транзакцию</button>
    </form>

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
