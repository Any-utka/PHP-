# Массивы и Функции

> Лабораторная работа №3, Доцен Анна

## Цель работы

Освоить работу с массивами в PHP, применяя различные операции: создание, добавление, удаление, сортировка и поиск. Закрепить навыки работы с функциями, включая передачу аргументов, возвращаемые значения и анонимные функции.

## Задание

1. Работать с массивами
2. Работать с файловой системой

### Описание выполнения работы

1. Вначале файла подключаем строгую типизацию ``` declare(strict_types=1); ```
2. Создаем массив студентов, используем id, дату совершения транзакции, сумму транзакции, ее описание и название организации.
3. Выводим список транзакицй в таблице
4. Реализуем ряд функций:
   - *calculateTotalAmount(array $transactions): float*, которая вычисляет общую сумму всех транзакций.

     ```php
     function calculateTotalAmount(array $transactions): float {
     return array_sum(array_column($transactions, 'amount'));
     }
     ```

   - *findTransactionByDescription(string $descriptionPart)*, которая ищет транзакцию по части описания.

     ```php
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
     ```

   - *findTransactionById(int $id)*, которая ищет транзакцию по идентификатору.

      ```php
      function findTransactionById(int $id): array {
       foreach ($_SESSION['transactions'] as $transaction) {
           if ($transaction['id'] === $id) {
               return [$transaction];  // Возвращаем найденную транзакцию как массив
           }
       }
       return [];  // Если транзакция не найдена
      }
     ```

   - *daysSinceTransaction(string $date): int*, которая возвращает количество дней между датой транзакции и текущим днем.

     ```php
      function daysSinceTransaction(string $date): int {
       $date = new DateTime($date);
       $now = new DateTime();
       return $now->diff($date)->days;
      }
      ```

   - *addTransaction(int $id, string $date, float $amount, string $description, string $merchant): void* для добавления новой транзакции.

     ```php
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
     ```

5. Сортиртируем транзакции
   - Сортировка тарнзакций по дате:

        ```php
        function sortTransactionsByDate(): void {
            usort($_SESSION['transactions'], fn($a, $b) => strtotime($a['date']) <=> strtotime($b['date']));
        }
        ```

   - Сортировка транзакций по сумме (по убыванию):

     ```php
     function sortTransactionsByAmountDesc(): void {
       usort($_SESSION['transactions'], fn($a, $b) => $b['amount'] <=> $a['amount']);
      }
     ```

6. Работаем с файловой системой
   - Создаем директорию *image*, в которой храним изображения.
   - В файле *index.php* определяем веб-страницу с хедером, меню, контентом и футером.
   - Выводим изображения на страницу.
  
        ```php
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
        ```

### Ответы на контрольные вопросы

1. Массивы в PHP — это переменные, которые могут содержать несколько значений, каждое из которых может быть доступно по ключу. Массивы позволяют хранить и работать с множеством данных в одной переменной. В PHP существуют два типа массивов:

- Индексиованные массивы (или массивы с числовыми индексами) — элементы массива индексируются с использованием чисел.

- Ассоциативные массивы — элементы массива индексируются строками, то есть каждому значению массива присваивается строковой ключ.

2. В PHP массивы создаются с помощью функции array(), а также с помощью сокращённого синтаксиса через квадратные скобки [].

3. Цикл foreach в PHP используется для перебора элементов массива. Он позволяет работать с каждым элементом массива без необходимости использования индекса. Цикл foreach особенно удобен, когда нужно пройти по всем элементам массива, не заботясь о их индексах.
