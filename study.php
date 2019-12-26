<?php
function inverse($x) {
    if (!$x) {
        throw new Exception('Деление на ноль.');
    }
    return 1/$x;
}

try {
    echo inverse(5) . "\n";
    echo inverse(0) . "\n";
} finally {
    echo 'Выброшено исключение: ', "\n";
} 

// Продолжение выполнения
echo "Привет, мир\n";
?> 