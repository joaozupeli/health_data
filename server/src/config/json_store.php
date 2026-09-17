<?php
declare(strict_types=1);

const DATA_FILE = __DIR__ . '/../../storage/data.json';

function initialData(): array
{
    return [
        'medicos' => [
            ['id' => 1, 'nome' => 'Dra. Ana Martins', 'crm' => 'CRM/SP 123456', 'especialidade' => 'Clínica Geral', 'telefone' => '(11) 99999-1001', 'email' => 'ana@healthdata.local'],
            ['id' => 2, 'nome' => 'Dr. Carlos Lima', 'crm' => 'CRM/SP 654321', 'especialidade' => 'Cardiologia', 'telefone' => '(11) 99999-1002', 'email' => 'carlos@healthdata.local'],
        ],
        'pacientes' => [
            ['id' => 1, 'nome' => 'Mariana Souza', 'cpf' => '123.456.789-00', 'nascimento' => '1992-04-18', 'telefone' => '(11) 98888-2001', 'email' => 'mariana@example.com'],
            ['id' => 2, 'nome' => 'João Oliveira', 'cpf' => '987.654.321-00', 'nascimento' => '1985-11-03', 'telefone' => '(11) 98888-2002', 'email' => 'joao@example.com'],
        ],
    ];
}

function ensureDataFile(): void
{
    $directory = dirname(DATA_FILE);
    if (!is_dir($directory)) { mkdir($directory, 0777, true); }
    if (!file_exists(DATA_FILE)) {
        file_put_contents(DATA_FILE, json_encode(initialData(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE), LOCK_EX);
    }
}

function readData(): array
{
    ensureDataFile();
    $data = json_decode(file_get_contents(DATA_FILE) ?: '', true);
    return is_array($data) ? $data : initialData();
}

function writeData(array $data): void
{
    $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    if ($json === false || file_put_contents(DATA_FILE, $json, LOCK_EX) === false) {
        throw new RuntimeException('Não foi possível salvar os dados.');
    }
}
