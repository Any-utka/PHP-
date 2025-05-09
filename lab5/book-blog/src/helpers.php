<?php

/**
 * Экранирует специальные символы в строке для защиты от XSS атак.
 *
 * Преобразует специальные символы в HTML-сущности, чтобы предотвратить
 * выполнение вредоносного кода, внедренного в пользовательский ввод.
 *
 * @param string $data Входная строка, которая будет экранирована.
 * @return string Экранированная строка, безопасная для вывода в HTML.
 */
function escapeHtml($data) {
    return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
}


