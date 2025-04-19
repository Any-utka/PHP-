<?php

// Функция для защиты от XSS атак
function escapeHtml($data) {
    return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
}


