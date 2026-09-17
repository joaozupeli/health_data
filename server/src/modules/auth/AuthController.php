<?php
declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { http_response_code(204); exit; }
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Método não permitido']);
    exit;
}

$body = json_decode(file_get_contents('php://input'), true) ?? [];
$username = trim((string) ($body['username'] ?? ''));
$password = (string) ($body['password'] ?? '');

if ($username === '' || $password === '') {
    http_response_code(422);
    echo json_encode(['success' => false, 'message' => 'Usuário e senha são obrigatórios']);
    exit;
}

// Modo de demonstração. Depois, substitua por PDO e password_verify().
if ($username !== 'admin' || $password !== 'admin') {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Usuário ou senha inválidos']);
    exit;
}

echo json_encode([
    'success' => true,
    'message' => 'Login realizado',
    'user' => ['name' => 'Administrador', 'username' => 'admin'],
]);
