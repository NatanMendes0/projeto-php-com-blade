# Projeto: Sistema de Pedidos de Impressão 3D

Este projeto é um sistema Laravel para gerenciar **pedidos de impressão 3D**, com:

- Cadastro e autenticação de usuários (login/logout)
- CRUD completo de **pedidos de impressão**
- Histórico de pedidos, status e detalhes (preço de custo, preço cobrado, data da venda, etc.)
- Módulo de usuário (perfil) básico

> 🔥 Em breve: controle de estoque de filamentos e suprimentos.

---

## 🚀 Funcionalidades principais

### ✅ Autenticação
- Cadastro de usuários (registro)
- Login / Logout
- Proteção de rotas via `auth`

### ✅ Pedidos de impressão
- Criar pedido (modelo, material, dimensões, quantidade)
- Visualizar lista de pedidos (por usuário e administrador)
- Editar / Excluir pedidos
- Atualizar status (ex: pendente → em produção → concluído)

### ✅ Estrutura prevista (futuro)
- Controle de estoque de peças 3D já impressas

---

## 🧩 Estrutura do projeto (Laravel)

Principais pastas usadas:

- `app/Models` — modelos Eloquent (ex: `Order`, `User`)
- `app/Http/Controllers` — controladores (ex: `OrderController`)
- `routes/web.php` — rotas web (CRUD, dashboard, etc.)
- `resources/views` — views Blade (quadro de pedidos, login, etc.)
- `database/migrations` — migrations para criar tabelas (orders, users, etc.)

---

## ▶️ Como rodar (local / Docker)

### 1) Instalar dependências
```bash
composer install
npm install
```

### 2) Configurar o `.env`
Copie `.env.example` para `.env` e ajuste as credenciais do banco.

### 3) Rodar o Docker (MySQL + app)
```bash
docker compose up -d
```

### 3.1) Rodar tambem o CRUD Node de estoque de pecas 3D
O projeto agora inclui um app Node.js separado em `node-inventory/`.

- Laravel continua em `http://localhost:8080`
- CRUD Node de estoque fica em `http://localhost:3000/parts`
- Laravel tambem consome a API Node em `http://node-app:3000/api/v1` (rede Docker)

Para subir todos os servicos com build:

```bash
docker compose up -d --build
```

### 4) Executar migrations + seeders
```bash
docker exec laravel-app php artisan migrate
docker exec laravel-app php artisan db:seed
```

### 5) Iniciar o servidor
```bash
docker exec laravel-app php artisan serve
```

Acesse: `http://localhost:8000`

## 📦 CRUD Node.js de Estoque (Pecas 3D)

Campos por card:

- Preco de custo
- Preco de venda
- Quantidade disponivel
- Acoes de editar e deletar

### Estrutura do app Node

- `node-inventory/src/server.js` - bootstrap do Express
- `node-inventory/src/config/db.js` - conexao MySQL
- `node-inventory/src/routes/parts.routes.js` - rotas CRUD
- `node-inventory/src/controllers/parts.controller.js` - regras de negocio/validacao
- `node-inventory/src/repositories/parts.repository.js` - SQL parametrizado
- `node-inventory/src/views/parts/` - telas EJS

### Rotas principais do CRUD Node

- `GET /parts` - listar cards
- `GET /parts/new` - formulario de criacao
- `POST /parts` - criar peca
- `GET /parts/:id/edit` - formulario de edicao
- `PUT /parts/:id` - atualizar peca
- `DELETE /parts/:id` - remover peca

### Integracao Laravel -> API Node

O Laravel possui um CRUD de estoque que consome a API JSON do Node via API key simples.

- Variaveis: `NODE_API_BASE_URL` e `NODE_API_KEY`
- Tela no Laravel (auth): `http://localhost:8080/inventory-parts`
- Header enviado nas chamadas server-side: `x-api-key`

Rotas API do Node (protegidas por API key):

- `GET /api/v1/parts`
- `GET /api/v1/parts/:id`
- `POST /api/v1/parts`
- `PUT /api/v1/parts/:id`
- `DELETE /api/v1/parts/:id`

---

## 🧪 Testes

Rode as suites de teste com:

```bash
docker exec laravel-app php artisan test
```

---

## 🔜 Próximos passos (planejado)

- [ ] Calculadora de preço baseada na disponibilizada pela [calculadora de preços da Objeto3D](https://objeto3d.com.br/calculadora-custos)
- [ ] Sistema de controle de estoque (filamentos, resinas, peças)
- [ ] Upload de arquivos STL + visualização rápida

-- 

## 📄 Licença
Este projeto é licenciado sob a [MIT License](LICENSE). Sinta-se à vontade para usar, modificar e distribuir!