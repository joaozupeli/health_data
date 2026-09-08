<?php

require_once __DIR__ . '/env.php';

loadEnv(__DIR__ . '/../../../.env');

try {
    $dsn = sprintf(
        "mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4",
        $_ENV['DB_HOST'],
        $_ENV['DB_PORT'],
        $_ENV['DB_NAME']
    );

    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::MYSQL_ATTR_SSL_CA       => __DIR__ . '/certs/isrgrootx1.pem',
    ];

    $pdo = new PDO($dsn, $_ENV['DB_USER'], $_ENV['DB_PASSWORD'], $options);

} catch (PDOException $e) {
    die("Erro ao conectar no banco: " . $e->getMessage());
}