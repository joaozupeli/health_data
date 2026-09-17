<?php
declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
require_once __DIR__ . '/../config/json_store.php';

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { http_response_code(204); exit; }

$type = (string) ($_GET['type'] ?? '');
if (!in_array($type, ['medicos', 'pacientes'], true)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Tipo de cadastro inválido']);
    exit;
}

$method = $_SERVER['REQUEST_METHOD'];
$body = json_decode(file_get_contents('php://input'), true) ?? [];
$data = readData();
$records = $data[$type] ?? [];
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if ($method === 'GET') {
    if ($id) {
        $record = current(array_filter($records, fn (array $item): bool => $item['id'] === $id));
        if (!$record) { http_response_code(404); echo json_encode(['success' => false, 'message' => 'Registro não encontrado']); exit; }
        echo json_encode(['success' => true, 'data' => $record]);
        exit;
    }
    echo json_encode(['success' => true, 'data' => array_values($records)]);
    exit;
}

if ($method === 'POST') {
    $body['id'] = empty($records) ? 1 : max(array_column($records, 'id')) + 1;
    $records[] = $body;
    $data[$type] = $records;
    writeData($data);
    http_response_code(201);
    echo json_encode(['success' => true, 'message' => 'Cadastro realizado', 'data' => $body]);
    exit;
}

if (!$id) { http_response_code(400); echo json_encode(['success' => false, 'message' => 'Informe um ID válido']); exit; }
$index = array_search($id, array_column($records, 'id'), true);
if ($index === false) { http_response_code(404); echo json_encode(['success' => false, 'message' => 'Registro não encontrado']); exit; }

if ($method === 'PUT') {
    $body['id'] = $id;
    $records[$index] = $body;
    $data[$type] = array_values($records);
    writeData($data);
    echo json_encode(['success' => true, 'message' => 'Cadastro atualizado', 'data' => $body]);
    exit;
}
if ($method === 'DELETE') {
    array_splice($records, $index, 1);
    $data[$type] = array_values($records);
    writeData($data);
    echo json_encode(['success' => true, 'message' => 'Cadastro excluído']);
    exit;
}

http_response_code(405);
echo json_encode(['success' => false, 'message' => 'Método não permitido']);
