<?php
/**
 * database.php
 * Carrega o .env e abre a conexão PDO com a TiDB Cloud.
 * Qualquer view que precisar do banco só dá require_once nesse arquivo.
 */

require_once __DIR__ . '/env.php';

// __DIR__ aqui = server/src/config
// sobe 3 níveis (config -> src -> server -> raiz) até achar o .env
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

    $pdo = new PDO($dsn, $_ENV['DB_USER'], $_ENV['DB_PASS'], $options);

} catch (PDOException $e) {
    // em produção isso viraria log, mas pra desafio de faculdade
    // mostrar o erro ajuda muito a debugar na hora
    die("Erro ao conectar no banco: " . $e->getMessage());
}