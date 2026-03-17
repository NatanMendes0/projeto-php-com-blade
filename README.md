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