<?php

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header("Access-Control-Allow-Headers: *");

require_once __DIR__ . '/../../config/database.php';


if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

class AuthController
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function login(string $username, string $passwordHash): array
    {
        $sql = '
        SELECT id
          ,username
          ,password
            FROM users
              WHERE username = ? 
              LIMIT 1
              ';
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        $receivedHash = strtolower($passwordHash);

        if (!$user || !hash_equals(strtolower((string) $user['password']), $receivedHash)) {
            return [
                'success' => false,
                'message' => 'Usuário ou senha inválidos',
            ];
        }

        return [
            'success' => true,
            'message' => 'Login realizado',
        ];
    }
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        'success' => false,
        'message' => 'Método não permitido',
    ]);
    exit;
}

$body = json_decode(file_get_contents('php://input'), true) ?? [];
$username = trim((string) ($body['username'] ?? ''));
$passwordHash = (string) ($body['password'] ?? '');

if ($username === '' || $passwordHash === '') {
    echo json_encode([
        'success' => false,
        'message' => 'Usuário e senha são obrigatórios',
    ]);
    exit;
}

try {
    $auth = new AuthController($pdo);
    echo json_encode($auth->login($username, $passwordHash));
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Erro ao autenticar',
    ]);
}
