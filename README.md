# Health Data

Health Data — cada consulta é um dado, cada dado é uma decisão!

## Requisitos

- PHP 8.4 ou superior
- Extensões `pdo_mysql`, `mysqli`, `mbstring`, `curl`, `openssl` e `fileinfo`
- MySQL compatível com conexões PDO

## Configuração local

1. Copie `.env.example` para `.env`.
2. Preencha em `.env` os dados do seu banco.
3. Se o banco exigir SSL, coloque o certificado em
   `server/src/config/certs/isrgrootx1.pem`, conforme esperado pela configuração
   atual.

O arquivo `.env` está ignorado pelo Git para impedir o envio de credenciais.

## Executar

Abra dois terminais na raiz do projeto.

Backend, na porta 8080:

```powershell
php -S localhost:8080 -t server/src
```

Frontend, na porta 3000:

```powershell
php -S localhost:3000 -t client/src/views
```

Depois, abra `http://localhost:3000/login.php` no navegador. O frontend já está
configurado para chamar a API em `http://localhost:8080`.

Use as credenciais de demonstração:

- Usuário: `admin`
- Senha: `admin`

## Modo sem banco de dados

Enquanto o MySQL não estiver configurado, médicos e pacientes são armazenados em
`server/storage/data.json`. A API de demonstração está em
`server/src/modules/records.php`. Depois, você pode trocar somente essa camada
por consultas PDO e manter as telas e os scripts JavaScript.

## Diagnóstico rápido

```powershell
php -v
php -m
```
