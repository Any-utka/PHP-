<?php
declare(strict_types=1);

// Инициализируем сессии для хранения данных транзакций
session_start();

/**
 * Массив транзакций, содержащий информацию о каждой операции.
 */
if (!isset($_SESSION['transactions'])) {
    $_SESSION['transactions'] = [
        [
            'id' => 1,
            'date' => '2022-12-25',
            'amount' => 500.00,
            'description' => 'Salary for good studies',
            'merchant' => 'Work of Web'
        ],
        [
            'id' => 2,
            'date' => '2025-02-17',
            'amount' => 200.50,
            'description' => 'Social bursary',
            'merchant' => 'University of London'
        ],
        [
            'id' => 3,
            'date' => '2024-08-23',
            'amount' => 350.00,
            'description' => 'Online shopping',
            'merchant' => 'E-shop'
        ]
    ];
}

/**
 * Подсчитывает общую сумму всех транзакций.
 *
 * @param array $transactions Массив транзакций
 * @return float Общая сумма всех транзакций
 */
function calculateTotalAmount(array $transactions): float {
    return array_sum(array_column($transactions, 'amount'));
}

/**
 * Ищет транзакции по части описания.
 * 
 * @param string $descriptionPart Часть описания для поиска
 * @return array Массив транзакций, чьи описания содержат указанную строку
 */
function findTransactionByDescription(string $descriptionPart): array {
    $foundTransactions = [];

    // Используем цикл foreach для поиска
    foreach ($_SESSION['transactions'] as $transaction) {
        if (strpos($transaction['description'], $descriptionPart) !== false) {
            $foundTransactions[] = $transaction;
        }
    }

    return $foundTransactions;
}

/**
 * Ищет транзакцию по идентификатору с использованием цикла foreach.
 * 
 * @param int $id Идентификатор транзакции для поиска
 * @return array Массив с найденной транзакцией или пустой массив, если не найдена
 */
function findTransactionById(int $id): array {
    foreach ($_SESSION['transactions'] as $transaction) {
        if ($transaction['id'] === $id) {
            return [$transaction];  // Возвращаем найденную транзакцию как массив
        }
    }
    return [];  // Если транзакция не найдена
}

/**
 * Добавляет новую транзакцию в массив.
 * 
 * @param int $id Идентификатор транзакции
 * @param string $date Дата транзакции
 * @param float $amount Сумма транзакции
 * @param string $description Описание транзакции
 * @param string $merchant Организация, с которой связана транзакция
 */
function addTransaction(int $id, string $date, float $amount, string $description, string $merchant): void {
    $_SESSION['transactions'][] = [
        'id' => $id,
        'date' => $date,
        'amount' => $amount,
        'description' => $description,
        'merchant' => $merchant
    ];
}

/**
 * Подсчитывает количество дней, прошедших с момента транзакции.
 *
 * @param string $date Дата транзакции в формате YYYY-MM-DD
 * @return int Количество дней с момента транзакции
 */
function daysSinceTransaction(string $date): int {
    $date = new DateTime($date);
    $now = new DateTime();
    return $now->diff($date)->days;
}

/**
 * Сортирует транзакции по дате (по возрастанию).
 * Использует глобальную переменную $transactions.
 *
 * @return void
 */
function sortTransactionsByDate(): void {
    usort($_SESSION['transactions'], fn($a, $b) => strtotime($a['date']) <=> strtotime($b['date']));
}

/**
 * Сортирует транзакции по сумме (по убыванию).
 * Использует глобальную переменную $transactions.
 *
 * @return void
 */
function sortTransactionsByAmountDesc(): void {
    usort($_SESSION['transactions'], fn($a, $b) => $b['amount'] <=> $a['amount']);
}

// Переменные для поиска
$foundTransactionsByDescription = [];
$foundTransactionById = null;

// Добавление новой транзакции
if (isset($_POST['add_transaction'])) {
    $new_transaction = [
        'id' => (int)$_POST['new_id'],
        'date' => $_POST['new_date'],
        'amount' => (float)$_POST['new_amount'],
        'description' => $_POST['new_description'],
        'merchant' => $_POST['new_merchant'],
    ];
    // Добавляем транзакцию в сессию
    addTransaction($new_transaction['id'], $new_transaction['date'], $new_transaction['amount'], $new_transaction['description'], $new_transaction['merchant']);
}

// Поиск транзакции по ID
if (isset($_POST['search_id']) && !empty($_POST['transaction_id'])) {
    $transaction_id = (int)$_POST['transaction_id'];
    $foundTransactionById = findTransactionById($transaction_id);
}

// Поиск транзакций по описанию
if (isset($_POST['search_description']) && !empty($_POST['description_part'])) {
    $description_part = $_POST['description_part'];
    $foundTransactionsByDescription = findTransactionByDescription($description_part);
}

/**
 * Получает список изображений из указанной директории.
 *
 * @param string $dir Путь к директории с изображениями
 * @return array Список файлов изображений с полным путем
 */
function getImages(string $dir): array {
    if (!is_dir($dir)) {
        return [];
    }
    
    $files = scandir($dir);
    if ($files === false) {
        return [];
    }

    $files = array_values(array_diff(scandir($dir), ['.', '..']));

    return array_map(fn($file) => $dir . $file, $files);
}

// Получаем список изображений из директории 'images/'
$images = getImages('images/');
?>
