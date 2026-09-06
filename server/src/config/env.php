<?php
function loadEnv(string $path): void
{
    if (!file_exists($path)) {
        throw new Exception("Arquivo .env não encontrado em: $path");
    }

    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    foreach ($lines as $line) {
        $line = trim($line);

        // ignora linhas em branco e comentários
        if ($line === '' || str_starts_with($line, '#')) {
            continue;
        }

        // só processa linhas no formato CHAVE=valor
        if (!str_contains($line, '=')) {
            continue;
        }

        [$name, $value] = explode('=', $line, 2);

        $name  = trim($name);
        $value = trim($value);

        // remove aspas se a pessoa colocar "valor" ou 'valor' no .env
        $value = trim($value, "\"'");

        $_ENV[$name] = $value;
        putenv("$name=$value"); // opcional, mas ajuda se algum lib usar getenv()
    }
}