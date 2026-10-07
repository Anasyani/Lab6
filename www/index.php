<?php

require 'vendor/autoload.php';

use App\RedisExample;
use App\ElasticExample;
use App\ClickhouseExample;

function section(string $title, callable $fn): void
{
    echo "<h2>" . htmlspecialchars($title) . "</h2><pre>";
    try {
        $fn();
    } catch (\Throwable $e) {
        echo "Ошибка: " . htmlspecialchars($e->getMessage());
    }
    echo "</pre>";
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Лабораторная №6</title>
</head>
<body>
<h1>Лабораторная №6: Redis, Elasticsearch, ClickHouse</h1>
<?php

// Redis
section('Redis: ключ-значение', function () {
    $redis = new RedisExample();
    $redis->setValue('framework', 'predis');
    echo htmlspecialchars($redis->getValue('framework'));
});

// Redis: тема варианта 1 — пользователи
section('Redis: пользователи (тема варианта)', function () {
    $redis = new RedisExample();
    $redis->addUser(1, 'Ivan', 'ivan@example.com');
    $redis->addUser(2, 'Maria', 'maria@example.com');
    $redis->addUser(3, 'Anastasia', 'anastasia@example.com');
    echo htmlspecialchars(print_r($redis->getAllUsers(), true));
});

// Elasticsearch
section('Elasticsearch', function () {
    $elastic = new ElasticExample();
    echo htmlspecialchars($elastic->indexDocument('books', 1, ['title' => '1984', 'author' => 'Orwell'])) . "\n\n";
    echo htmlspecialchars($elastic->search('books', ['author' => 'Orwell']));
});

// ClickHouse
section('ClickHouse', function () {
    $click = new ClickhouseExample();
    echo "Таблиц в system.tables: " . htmlspecialchars($click->query('SELECT count() FROM system.tables'));
    $click->query("CREATE TABLE IF NOT EXISTS users (
        id UInt32,
        name String,
        age UInt8
    ) ENGINE = MergeTree()
    ORDER BY id");
    if (trim($click->query("SELECT count() FROM users")) === '0') {
        $click->query("INSERT INTO users (id, name, age) VALUES (1, 'Ivan', 25), (2, 'Maria', 30)");
    }
    echo "\nSELECT * FROM users:\n" . htmlspecialchars($click->query("SELECT * FROM users FORMAT TSV"));
});
?>
</body>
</html>
