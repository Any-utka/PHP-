<?php
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

// Функция для подсчета общей суммы транзакций
function calculateTotalAmount(array $transactions): float {
    return array_sum(array_column($transactions, 'amount'));
}

// Функция для подсчета количества дней с момента транзакции
function daysSinceTransaction(string $date): int {
    $date = new DateTime($date);
    $now = new DateTime();
    return $now->diff($date)->days;
}

// Функция для сортировки транзакций по дате
function sortTransactionsByDate(): void {
    global $transactions;
    usort($transactions, fn($a, $b) => strtotime($a['date']) <=> strtotime($b['date']));
}

// Функция для сортировки транзакций по сумме (по убыванию)
function sortTransactionsByAmountDesc(): void {
    global $transactions;
    usort($transactions, fn($a, $b) => $b['amount'] <=> $a['amount']);
}

// Функция для получения списка изображений
function getImages(string $dir): array {
    if (!is_dir($dir)) {
        return [];
    }
    
    $files = scandir($dir);
    if ($files === false) {
        return [];
    }

    return array_filter(array_map(fn($file) => $dir . $file, $files), fn($file) => is_file($file) && preg_match('/\.(jpg|jpeg|png|gif)$/i', $file));
}

$images = getImages('images/');
