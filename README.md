# Agendamento de Coletas

![CI](https://github.com/GrazielaPaola/desafio/actions/workflows/ci.yml/badge.svg)

Sistema para cadastro de motoristas e agendamento de coletas entre fornecedores e clientes, com validação das regras de negócio no backend e feedback imediato na interface.

Desenvolvido como teste técnico para a vaga de Desenvolvedor(a) Full Stack Júnior.

## Funcionalidades

- **Visão geral**: indicadores (coletas hoje, próximos 7 dias, coletas futuras, motoristas ativos), próximas coletas e carga por motorista.
- **Coletas**: CRUD completo com busca por fornecedor/cliente/CNPJ, filtro por motorista e período, ordenação por coluna, paginação e exportação para CSV.
- **Agenda**: visão semanal das coletas, com cor por motorista e navegação entre semanas.
- **Motoristas**: CRUD em cartões, com busca, placa vinculada, próxima coleta e bloqueio de exclusão quando há coletas.
- Layout responsivo: menu lateral vira gaveta e as tabelas viram cards no celular.
- Documentação da API navegável (Swagger UI) e coleção do Postman.

## Interface

A interface segue um design próprio, fora do Material padrão do Quasar: tipografia Manrope, fundo bege, menu lateral escuro e âmbar como cor de destaque. Os tokens ficam em [frontend/src/css/app.scss](frontend/src/css/app.scss) como variáveis CSS, e as cores de marca em [frontend/src/css/quasar.variables.scss](frontend/src/css/quasar.variables.scss). Os ícones são SVG traçados definidos em [frontend/src/utils/icones.js](frontend/src/utils/icones.js), sem dependência externa.

## Tecnologias

| Camada | Tecnologia | Versão |
|---|---|---|
| Frontend | Vue 3 + Quasar Framework (Vite) | Vue 3.5 · Quasar 2.29 · @quasar/app-vite 3.8 |
| Backend | Laravel (API REST) | Laravel 13 · PHP 8.3 |
| Banco de dados | MySQL | 8.4 |
| Estado (front) | Pinia | 4 |
| HTTP (front) | Axios | 1 |
| Testes | PHPUnit · Vitest | 12 · 4 |
| Padrão de código | Laravel Pint · ESLint · Prettier | — |
| CI | GitHub Actions | — |

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

## Documentação da API

- **Swagger UI**: com o backend rodando, acesse `http://localhost:8000/docs/index.html`. A especificação fica em [backend/public/docs/openapi.yaml](backend/public/docs/openapi.yaml).
- **Postman**: importe [docs/agendamento-coletas.postman_collection.json](docs/agendamento-coletas.postman_collection.json). A variável `base_url` já aponta para `http://localhost:8000/api`.

## Estrutura de pastas

```
olflog/
├── .github/workflows/ci.yml     Pipeline: Pint, PHPUnit, ESLint/Prettier, Vitest e build
├── docs/                        Coleção do Postman
├── backend/                     API REST (Laravel)
│   ├── app/
│   │   ├── Exceptions/          Exceções de domínio (409)
│   │   ├── Http/
│   │   │   ├── Controllers/     Orquestração: recebem a requisição e chamam o service
│   │   │   ├── Requests/        Validação de entrada, filtros e ordenação (FormRequest)
│   │   │   └── Resources/       Formato do JSON de saída
│   │   ├── Models/              Eloquent: relacionamentos, casts, mutators e scopes
│   │   ├── Rules/               Regras de validação reutilizáveis (CNPJ, placa)
│   │   ├── Services/            Regras de negócio, listagens filtradas, resumo
│   │   └── Support/             Normalização de CNPJ e placa, geração de CSV
│   ├── database/                Migrations, factories e seeders
│   ├── lang/pt_BR/              Mensagens de validação em português
│   ├── public/docs/             Swagger UI + openapi.yaml
│   ├── routes/api.php
│   └── tests/
│       ├── Feature/             Regras de negócio, filtros, exportação e resumo via HTTP
│       └── Unit/                Rules de CNPJ e placa
└── frontend/                    SPA (Vue 3 + Quasar)
    ├── src/
    │   ├── components/          Componentes de apresentação (tabelas, formulários, filtros)
    │   ├── composables/         Listagem paginada, agenda, erros da API, confirmação
    │   ├── layouts/
    │   ├── pages/               Visão geral, Coletas, Agenda, Motoristas
    │   ├── router/
    │   ├── services/            Única camada que conhece o axios e os endpoints
    │   ├── stores/              Pinia
    │   └── utils/               Validação e formatação de CNPJ, placa e datas
    └── test/unit/               Testes das funções puras (Vitest)
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

Base: `http://localhost:8000/api`. Todas as respostas são JSON (exceto a exportação CSV).

### Motoristas

| Método | Rota | Descrição |
|---|---|---|
| GET | `/motoristas` | Lista paginada |
| POST | `/motoristas` | Cria motorista |
| GET | `/motoristas/{id}` | Detalha motorista |
| PUT | `/motoristas/{id}` | Atualiza motorista |
| DELETE | `/motoristas/{id}` | Exclui motorista (409 se possuir coletas) |

Parâmetros de listagem: `page`, `por_pagina` (1–100), `busca` (nome), `ordenar_por` (`nome`, `total_coletas`), `direcao` (`asc`, `desc`).

Payload de criação/atualização:

```json
{ "nome": "Carlos Eduardo Silva" }
```

Resposta (201/200):

```json
{
  "id": 1,
  "nome": "Carlos Eduardo Silva",
  "total_coletas": 2,
  "placa_veiculo": "ABC-1234",
  "proxima_coleta": "2026-09-10"
}
```

`placa_veiculo` é a placa vinculada ao motorista pela regra 6 e `proxima_coleta` é a data da próxima coleta futura. Ambos vêm nulos quando o motorista ainda não tem coletas.

### Coletas

| Método | Rota | Descrição |
|---|---|---|
| GET | `/coletas` | Lista paginada, com motorista |
| GET | `/coletas/exportar` | Exporta CSV (`;`, UTF-8 com BOM) respeitando os mesmos filtros |
| POST | `/coletas` | Agenda coleta |
| GET | `/coletas/{id}` | Detalha coleta |
| PUT | `/coletas/{id}` | Atualiza coleta |
| DELETE | `/coletas/{id}` | Exclui coleta |

Parâmetros de listagem e exportação: `page`, `por_pagina` (1–100), `busca` (fornecedor, cliente ou CNPJ com/sem máscara), `motorista_id`, `data_inicio`, `data_fim` (`YYYY-MM-DD`), `ordenar_por` (`data`, `fornecedor_nome`, `cliente_nome`, `placa_veiculo`, `motorista`), `direcao` (`asc`, `desc`).

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

### Resumo

| Método | Rota | Descrição |
|---|---|---|
| GET | `/resumo` | Indicadores da visão geral, as 5 próximas coletas e a carga por motorista |

```json
{
  "dias_proximos": 7,
  "coletas_hoje": 1,
  "coletas_proximos_dias": 6,
  "coletas_futuras": 10,
  "coletas_semana": 8,
  "total_motoristas": 5,
  "motoristas_com_coletas": 5,
  "proximas_coletas": [],
  "carga_motoristas": [{ "id": 1, "nome": "Carlos Eduardo Silva", "coletas_futuras": 2 }]
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

Backend (PHPUnit, SQLite em memória — não depende do MySQL):

```bash
cd backend
php artisan test
```

Cobre o CRUD de motoristas e coletas, cada uma das oito regras de negócio, filtros, ordenação, exportação CSV, resumo, os códigos de erro (422, 404, 409) e as rules de CNPJ e placa.

Frontend (Vitest):

```bash
cd frontend
npm test
```

Cobre as funções puras de validação e formatação de CNPJ, placa e datas.

Padrão de código:

```bash
cd backend && ./vendor/bin/pint --test
cd frontend && npm run lint:check
```

## Integração contínua

O workflow em `.github/workflows/ci.yml` roda a cada push: Pint e PHPUnit no backend; ESLint/Prettier, Vitest e build no frontend.

## Planejamento

O planejamento das tarefas está no Azure DevOps: _[link a ser adicionado]_
