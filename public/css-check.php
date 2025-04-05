<?php
// Скрипт для проверки доступности CSS-файла
header('Content-Type: text/plain');

$cssFile = __DIR__ . '/css/app.css';
$cssUrl = '/css/app.css';

echo "Проверка CSS-файла:\n\n";

// Проверка существования файла
if (file_exists($cssFile)) {
    echo "✅ Файл app.css существует\n";
    echo "Размер файла: " . filesize($cssFile) . " байт\n";
    echo "Последнее изменение: " . date("Y-m-d H:i:s", filemtime($cssFile)) . "\n";
} else {
    echo "❌ Файл app.css не найден\n";
}

// Проверка прав доступа
if (is_readable($cssFile)) {
    echo "✅ Файл app.css доступен для чтения\n";
} else {
    echo "❌ Файл app.css недоступен для чтения\n";
}

// Проверка MIME-типа
$finfo = finfo_open(FILEINFO_MIME_TYPE);
$mimeType = finfo_file($finfo, $cssFile);
finfo_close($finfo);
echo "MIME-тип файла: " . $mimeType . "\n";

// Проверка содержимого файла
if (file_exists($cssFile)) {
    $content = file_get_contents($cssFile);
    echo "Первые 100 символов содержимого:\n" . substr($content, 0, 100) . "...\n";
}

// Проверка заголовков ответа
echo "\nПроверка заголовков ответа:\n";
$headers = get_headers('http://' . $_SERVER['HTTP_HOST'] . $cssUrl);
if ($headers) {
    foreach ($headers as $header) {
        echo $header . "\n";
    }
} else {
    echo "Не удалось получить заголовки\n";
}

// Информация о сервере
echo "\nИнформация о сервере:\n";
echo "PHP версия: " . phpversion() . "\n";
echo "Сервер: " . $_SERVER['SERVER_SOFTWARE'] . "\n";
echo "Document Root: " . $_SERVER['DOCUMENT_ROOT'] . "\n";
echo "Script Filename: " . $_SERVER['SCRIPT_FILENAME'] . "\n";
echo "REQUEST_URI: " . $_SERVER['REQUEST_URI'] . "\n"; 