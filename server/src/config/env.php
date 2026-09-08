<?php
function loadEnv(string $path): void
{
    if (!file_exists($path)) {
        throw new Exception("Arquivo .env não encontrado em: $path");
    }

    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    foreach ($lines as $line) {
        $line = trim($line);

        if ($line === '' || str_starts_with($line, '#')) {
            continue;
        }

        if (!str_contains($line, '=')) {
            continue;
        }

        [$name, $value] = explode('=', $line, 2);

        $name  = trim($name);
        $value = trim($value);

        $value = trim($value, "\"'");

        $_ENV[$name] = $value;
        putenv("$name=$value");
    }
}