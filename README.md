# Agendamento de Coletas

Sistema para cadastro de motoristas e agendamento de coletas entre fornecedores e clientes, com validação das regras de negócio no backend e feedback imediato na interface.

Desenvolvido como teste técnico para a vaga de Desenvolvedor(a) Full Stack Júnior.

## Tecnologias

| Camada | Tecnologia | Versão |
|---|---|---|
| Frontend | Vue 3 + Quasar Framework (Vite) | Vue 3.5 · Quasar 2.29 · @quasar/app-vite 3.8 |
| Backend | Laravel (API REST) | Laravel 13 · PHP 8.3 |
| Banco de dados | MySQL | 8.4 |
| Estado (front) | Pinia | 4 |
| HTTP (front) | Axios | 1 |
| Testes | PHPUnit | 12 |
| Padrão de código | Laravel Pint · ESLint · Prettier | — |

## Pré-requisitos

- PHP 8.3+ com as extensões `pdo_mysql`, `mbstring`, `openssl`, `fileinfo`, `zip`
- Composer 2
- MySQL 8
- Node.js 22.22+ e npm
- Um banco de dados MySQL criado (por padrão, `agendamento_coletas`)

## Executando o backend

```bash
cd backend
cp .env.example .env
```

Ajuste as credenciais do banco no `.env` (`DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`). Em seguida:

```bash
composer install
php artisan key:generate
php artisan migrate --seed
php artisan serve
```

A API fica disponível em `http://localhost:8000/api`. O seeder cria 5 motoristas e 10 coletas de demonstração.

Alternativa em um único comando (após copiar e ajustar o `.env`):

```bash
composer setup
```

## Executando o frontend

```bash
cd frontend
cp .env.example .env
npm install
npm run dev
```

A aplicação abre em `http://localhost:9000`. A variável `QCLI_API_URL` no `.env` aponta para a API (padrão `http://localhost:8000/api`).

## Estrutura de pastas

```
olflog/
├── backend/                     API REST (Laravel)
│   ├── app/
│   │   ├── Exceptions/          Exceções de domínio (409)
│   │   ├── Http/
│   │   │   ├── Controllers/     Orquestração: recebem a requisição e chamam o service
│   │   │   ├── Requests/        Validação de entrada (FormRequest)
│   │   │   └── Resources/       Formato do JSON de saída
│   │   ├── Models/              Eloquent: relacionamentos, casts e mutators
│   │   ├── Rules/               Regras de validação reutilizáveis (CNPJ, placa)
│   │   ├── Services/            Regras de negócio
│   │   └── Support/             Normalização e formatação de CNPJ e placa
│   ├── database/
│   │   ├── factories/
│   │   ├── migrations/
│   │   └── seeders/
│   ├── lang/pt_BR/              Mensagens de validação em português
│   ├── routes/api.php
│   └── tests/
│       ├── Feature/             Testes das regras de negócio via HTTP
│       └── Unit/                Testes das rules de CNPJ e placa
└── frontend/                    SPA (Vue 3 + Quasar)
    └── src/
        ├── components/          Componentes de apresentação (tabelas e formulários)
        ├── composables/         Lógica reativa reutilizável (listagem, erros, confirmação)
        ├── layouts/
        ├── pages/               Telas de Coletas e Motoristas
        ├── router/
        ├── services/            Única camada que conhece o axios e os endpoints
        ├── stores/              Pinia
        └── utils/               Validação e formatação de CNPJ, placa e datas
```

Fluxo de uma requisição no backend:

```
Route → FormRequest → Controller → Service → Model → Resource
```

## Regras de negócio

| # | Regra | Onde é garantida |
|---|---|---|
| 1 | A data do agendamento deve ser maior que a data atual | `ColetaRequest` (`after:today`) |
| 2 | Cada fornecedor pode ter apenas uma coleta por data | `ColetaService` (409) + índice `unique (fornecedor_cnpj, data)` |
| 3 | O mesmo cliente pode se repetir | Sem restrição |
| 4 | Motorista é obrigatório | `ColetaRequest` (`required`, `exists`) + FK `not null` |
| 5 | Placa do veículo é obrigatória | `ColetaRequest` (`required`) + coluna `not null` |
| 6 | Um motorista não pode possuir duas placas vinculadas | `ColetaService` (409) |
| 7 | CNPJ do fornecedor com validação de máscara | `Rules\Cnpj` (máscara + dígitos verificadores) |
| 8 | CNPJ do cliente com validação de máscara | `Rules\Cnpj` (máscara + dígitos verificadores) |

Decisões complementares:

- CNPJ é recebido com máscara (`00.000.000/0000-00`), armazenado apenas com dígitos e devolvido formatado.
- Placa aceita o formato antigo (`ABC-1234`) e Mercosul (`ABC1D23`); é armazenada em maiúsculas sem hífen.
- Na edição, as regras 2 e 6 ignoram o próprio registro.
- Motorista com coletas agendadas não pode ser excluído (409).
- O frontend replica as validações de formato para feedback imediato; a fonte da verdade é o backend.

## Endpoints da API

Base: `http://localhost:8000/api`. Todas as respostas são JSON.

### Motoristas

| Método | Rota | Descrição |
|---|---|---|
| GET | `/motoristas?page=1&por_pagina=15` | Lista paginada, ordenada por nome |
| POST | `/motoristas` | Cria motorista |
| GET | `/motoristas/{id}` | Detalha motorista |
| PUT | `/motoristas/{id}` | Atualiza motorista |
| DELETE | `/motoristas/{id}` | Exclui motorista (409 se possuir coletas) |

Payload de criação/atualização:

```json
{ "nome": "Carlos Eduardo Silva" }
```

Resposta (201/200):

```json
{ "id": 1, "nome": "Carlos Eduardo Silva", "total_coletas": 0 }
```

### Coletas

| Método | Rota | Descrição |
|---|---|---|
| GET | `/coletas?page=1&por_pagina=15` | Lista paginada, ordenada por data, com motorista |
| POST | `/coletas` | Agenda coleta |
| GET | `/coletas/{id}` | Detalha coleta |
| PUT | `/coletas/{id}` | Atualiza coleta |
| DELETE | `/coletas/{id}` | Exclui coleta |

Payload de criação/atualização:

```json
{
  "data": "2026-09-10",
  "fornecedor_nome": "Fornecedor Ltda",
  "fornecedor_cnpj": "11.222.333/0001-81",
  "cliente_nome": "Cliente S.A.",
  "cliente_cnpj": "12.345.678/0001-95",
  "motorista_id": 1,
  "placa_veiculo": "ABC-1234"
}
```

Resposta (201/200):

```json
{
  "id": 1,
  "data": "2026-09-10",
  "fornecedor_nome": "Fornecedor Ltda",
  "fornecedor_cnpj": "11.222.333/0001-81",
  "cliente_nome": "Cliente S.A.",
  "cliente_cnpj": "12.345.678/0001-95",
  "motorista_id": 1,
  "motorista": { "id": 1, "nome": "Carlos Eduardo Silva" },
  "placa_veiculo": "ABC-1234"
}
```

### Listagens paginadas

```json
{
  "data": [],
  "links": { "first": "...", "last": "...", "prev": null, "next": null },
  "meta": { "current_page": 1, "per_page": 15, "total": 0, "last_page": 1 }
}
```

### Erros

| Status | Quando | Formato |
|---|---|---|
| 422 | Validação de entrada | `{ "message": "...", "errors": { "campo": ["mensagem"] } }` |
| 404 | Registro inexistente | `{ "message": "Registro não encontrado." }` |
| 409 | Regra de negócio violada | `{ "message": "O fornecedor já possui uma coleta agendada para esta data." }` |

Todas as mensagens são em português.

## Testes

```bash
cd backend
php artisan test
```

Os testes usam SQLite em memória e não dependem do MySQL. Cobrem o CRUD de motoristas e coletas, cada uma das oito regras de negócio, os códigos de erro (422, 404, 409) e as rules de CNPJ e placa.

Padrão de código:

```bash
cd backend && ./vendor/bin/pint --test
cd frontend && npm run lint:check
```

## Planejamento

O planejamento das tarefas está no Azure DevOps: _[link a ser adicionado]_
