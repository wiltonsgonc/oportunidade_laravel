# Sistema de Oportunidades SENAI CIMATEC

Portal para gerenciamento de vagas, editais, bolsas e programas da instituição.

## Requisitos do Sistema

### Requisitos Servidor

| Requisito | Versão Mínima |
|-----------|---------------|
| PHP | 8.2+ |
| MySQL | 8.0+ |
| Redis | 6.0+ |
| Nginx | 1.18+ |
| Composer | 2.0+ |

### Requisitos do Cliente

| Requisito | Versão |
|-----------|--------|
| Navegador | Moderno (Chrome, Firefox, Edge, Safari) |
| JavaScript | Habilitado |

### Dependências PHP

- Laravel Framework 12.x
- stevenmaguire/oauth2-keycloak (SSO)
- league/oauth2-client

---

## Instalação

### 1. Clonar o Repositório

```bash
git clone <repositorio> oportunidades
cd oportunidades
```

### 2. Instalar Dependências

```bash
composer install
```

### 3. Configurar Arquivo de Ambiente

```bash
cp .env.example .env
# ou
cp .env.dev .env  # Para desenvolvimento
```

### 4. Gerar Chave da Aplicação

```bash
php artisan key:generate
```

### 5. Configurar Banco de Dados

Edite o arquivo `.env` com as credenciais do banco:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=oportunidade
DB_USERNAME=seu_usuario
DB_PASSWORD=sua_senha
```

### 6. Executar Migrações

```bash
php artisan migrate
```

### 7. (Opcional) Popular Dados Iniciais

```bash
php artisan db:seed
```

### 8. Criar Link Simbólico para Arquivos

```bash
php artisan storage:link
```

---

## Configuração

### Autenticação SSO (Keycloak)

O sistema suporta autenticação via Single Sign-On (SSO) com Keycloak.

#### Variáveis de Ambiente

```env
# URL Base do Keycloak
KEYCLOAK_BASE_URL=https://keycloak.sua-empresa.com.br

# Realm configurado no Keycloak
KEYCLOAK_REALM=senai-cimatec

# Client ID cadastrado no Keycloak
KEYCLOAK_CLIENT_ID=vagas-senai

# Client Secret (gerado no Keycloak)
KEYCLOAK_CLIENT_SECRET=sua_chave_secreta

# URL de callback após autenticação
KEYCLOAK_REDIRECT_URI=https://seudominio.com.br/auth/callback

# Modo desenvolvimento (true = sem Keycloak)
KEYCLOAK_DEV_MODE=false

# Email para login automático em modo desenvolvimento
KEYCLOAK_DEV_MOCK_EMAIL=admin@cimatec.edu.br
```

#### Configuração no Keycloak

1. Acesse o painel administrativo do Keycloak
2. Crie um novo Client com as seguintes configurações:
   - **Client ID**: `vagas-senai`
   - **Client Protocol**: `openid-connect`
   - **Access Type**: `confidential`
   - **Valid Redirect URIs**: `https://seudominio.com.br/auth/callback`
   - **Web Origins**: `https://seudominio.com.br`
3. Configure as Roles:
   - `vagas-admin` - Acesso administrativo
   - `vagas-admin-principal` - Acesso de administrador principal
4. Gere o Client Secret e adicione ao `.env`

---

## Modo Desenvolvimento

Para desenvolvimento local sem Keycloak:

```env
KEYCLOAK_DEV_MODE=true
KEYCLOAK_DEV_MOCK_EMAIL=seu-email@localhost
```

Neste modo, o login será automático com o usuário especificado.

---

## Executando a Aplicação

### Servidor de Desenvolvimento

```bash
php artisan serve
# ou com Docker
docker-compose up -d
```

Acesse: `http://localhost:8000`

### Build para Produção

```bash
# Limpar e otimizar caches
php artisan optimize
php artisan config:cache
php artisan route:cache
```

---

## Assets (CSS/JS)

---

## Estrutura do Banco de Dados

### Tabelas Principais

| Tabela | Descrição |
|--------|-----------|
| `usuarios` | Usuários do sistema |
| `vagas` | Vagas/Editais |
| `vaga_anexos` | Anexos das vagas |
| `vaga_retificacoes` | Retificações |
| `vagas_auditoria` | Log de auditoria |
| `sistema_logs` | Logs do sistema |

---

## Funcionalidades

- [x] Gerenciamento de Vagas/Editais
- [x] Upload de Anexos (Edital, Resultados)
- [x] Retificação de Vagas
- [x] Encerramento Automático por Data Limite
- [x] Sistema de Auditoria
- [x] Autenticação SSO (Keycloak)
- [x] JIT Provisioning (criação automática de usuários)
- [x] Single Logout

---

## Variáveis de Ambiente (.env)

```env
APP_NAME=Oportunidade
APP_ENV=local
APP_KEY=base64:...
APP_DEBUG=true
APP_URL=http://localhost:8000

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=oportunidade
DB_USERNAME=root
DB_PASSWORD=

# Cache
CACHE_STORE=redis
REDIS_HOST=127.0.0.1
REDIS_PORT=6379

# Session
SESSION_DRIVER=redis

# Keycloak SSO
KEYCLOAK_BASE_URL=https://keycloak.sua-empresa.com.br
KEYCLOAK_REALM=senai-cimatec
KEYCLOAK_CLIENT_ID=vagas-senai
KEYCLOAK_CLIENT_SECRET=
KEYCLOAK_DEV_MODE=true
```

---

## Comandos Úteis

| Comando | Descrição |
|---------|-----------|
| `php artisan migrate` | Executar migrações |
| `php artisan migrate:rollback` | Reverter migrações |
| `php artisan db:seed` | Popular banco |
| `php artisan storage:link` | Criar link simbólico |
| `php artisan optimize:clear` | Limpar caches |
| `php artisan vagas:encerrar-vencidas` | Encerrar vagas vencidas |

