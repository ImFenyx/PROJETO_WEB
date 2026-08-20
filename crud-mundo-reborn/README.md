# CRUD Mundo — Reborn

Recriação completa do **CRUD Mundo** (países, cidades, continentes e governantes)
usando **React + TypeScript + Tailwind CSS** no frontend e uma **API REST em
Node.js (Express) + MySQL** no backend.

> Se o MySQL não estiver disponível, a API usa automaticamente um
> **armazenamento em memória** com dados de exemplo, permitindo testar o CRUD
> completo sem banco de dados.

## Stack

- **Frontend:** React 19, TypeScript, Vite, Tailwind CSS v4, React Router
- **Backend:** Node.js, Express 5, MySQL (`mysql2`)

## Pré-requisitos

- Node.js 20+ e npm
- (Opcional) MySQL/MariaDB com o banco `bd_mundo` criado

## Como rodar

```bash
# 1. Instale as dependências
npm install

# 2. (Opcional) Crie o banco e as tabelas no MySQL
mysql -u root < sql/db.sql

# 3. Em um terminal, inicie a API (porta 3001)
npm run dev:server

# 4. Em outro terminal, inicie o frontend (porta 5173)
npm run dev
```

Abra http://localhost:5173 no navegador.

O Vite faz o proxy de `/api` para a API em `http://127.0.0.1:3001`, então o
frontend usa apenas URLs relativas.

## Configuração da API (variáveis de ambiente)

| Variável     | Padrão        | Descrição                    |
| ------------ | ------------- | ---------------------------- |
| `PORT`       | `3001`        | Porta do servidor HTTP       |
| `DB_HOST`    | `127.0.0.1`   | Host do MySQL                |
| `DB_PORT`    | `3306`        | Porta do MySQL               |
| `DB_USER`    | `root`        | Usuário do MySQL             |
| `DB_PASSWORD`| *(vazio)*     | Senha do MySQL               |
| `DB_NAME`    | `bd_mundo`    | Nome do banco de dados       |

Exemplo:

```bash
DB_USER=root DB_PASSWORD=senha DB_NAME=bd_mundo npm run dev:server
```

## Endpoints da API

Todos seguem o mesmo padrão (substitua `{recurso}` por `paises`, `cidades`,
`continentes` ou `governantes`):

| Método | Rota               | Descrição                       |
| ------ | ------------------ | ------------------------------- |
| GET    | `/api/{recurso}`   | Lista registros (`?q=` busca)   |
| GET    | `/api/{recurso}/:id` | Busca um registro pelo id     |
| POST   | `/api/{recurso}`   | Cria um registro                |
| PUT    | `/api/{recurso}/:id` | Atualiza um registro          |
| DELETE | `/api/{recurso}/:id` | Exclui um registro            |

## Scripts

- `npm run dev` — servidor de desenvolvimento do frontend
- `npm run dev:server` — API em modo watch
- `npm run build` — build de produção do frontend
- `npm run typecheck` — verificação de tipos (frontend + backend)
- `npm run lint` — lint com Oxlint
