<?php
/**
 * Массив транзакций, содержащий информацию о каждой операции.
 */
$transactions = [
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
    global $transactions;
    usort($transactions, fn($a, $b) => strtotime($a['date']) <=> strtotime($b['date']));
}

/**
 * Сортирует транзакции по сумме (по убыванию).
 * Использует глобальную переменную $transactions.
 *
 * @return void
 */
function sortTransactionsByAmountDesc(): void {
    global $transactions;
    usort($transactions, fn($a, $b) => $b['amount'] <=> $a['amount']);
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
