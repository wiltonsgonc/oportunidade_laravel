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

Os arquivos `.env` não são enviados ao repositório por segurança. Use os modelos disponíveis:

```bash
# Para desenvolvimento
cp .env.dev .env

# Para produção
cp .env.prod .env
```

**Importante:** Após copiar, edite o arquivo `.env` com suas configurações reales.

---

## Configuração de Variáveis de Ambiente

### Variáveis Obrigatórias (Todos os Ambientes)

| Variável | Descrição | Exemplo |
|---------|-----------|--------|
| `APP_NAME` | Nome da aplicação | Oportunidade |
| `APP_ENV` | Ambiente | local, production |
| `APP_KEY` | Chave da aplicação | `base64:...` (gerar com `php artisan key:generate`) |
| `APP_DEBUG` | Modo debug | true (dev) / false (prod) |
| `APP_URL` | URL da aplicação | http://localhost:8000 ou https://seudominio.com.br |
| `APP_LOCALE` | Idioma padrão | pt_BR |
| `APP_FALLBACK_LOCALE` | Idioma alternativo | en |
| `APP_FAKER_LOCALE` | Idioma para dados fake | pt_BR |
| `APP_MAINTENANCE_DRIVER` | Driver de manutenção | file |

### Variáveis de Banco de Dados

| Variável | Descrição | Desenvolvimento | Produção |
|---------|-----------|---------------|----------|
| `DB_CONNECTION` | Driver do banco | mysql | mysql |
| `DB_HOST` | Host do banco | 127.0.0.1 | mysql_db |
| `DB_PORT` | Porta do banco | 3306 | 3306 |
| `DB_DATABASE` | Nome do banco | oportunidade | oportunidade |
| `DB_USERNAME` | Usuário do banco | oportunidad | oportunidad |
| `DB_PASSWORD` | Senha do banco | (sua senha) | (senha forte) |
| `DB_ROOT_PASSWORD` | Senha root | (opcional) | (senha forte) |

### Variáveis de Redis

| Variável | Descrição | Desenvolvimento | Produção |
|---------|-----------|---------------|----------|
| `REDIS_HOST` | Host do Redis | 127.0.0.1 | redis_prod |
| `REDIS_PASSWORD` | Senha do Redis | (vazio) | (senha forte) |
| `REDIS_PORT` | Porta do Redis | 6379 | 6379 |

### Variáveis de Sessão e Cache

| Variável | Desenvolvimento | Produção |
|---------|---------------|----------|
| `SESSION_DRIVER` | database | redis |
| `SESSION_LIFETIME` | 120 | 120 |
| `SESSION_ENCRYPT` | false | true |
| `CACHE_STORE` | file | redis |
| `BROADCAST_CONNECTION` | log | redis |
| `QUEUE_CONNECTION` | sync | redis |

### Variáveis de Session (Produção)

```env
SESSION_DRIVER=redis
SESSION_LIFETIME=120
SESSION_ENCRYPT=true
SESSION_PATH=/
SESSION_DOMAIN=seudominio.com.br
SESSION_SECURE_COOKIE=true
```

### Variáveis do Keycloak (SSO)

| Variável | Descrição | Desenvolvimento | Produção |
|---------|-----------|---------------|----------|
| `KEYCLOAK_BASE_URL` | URL do Keycloak | https://keycloak.local | https://keycloak.sua-empresa.com.br |
| `KEYCLOAK_REALM` | Nome do realm | senai-cimatec | senai-cimatec |
| `KEYCLOAK_CLIENT_ID` | ID do client | vagas-senai | vagas-senai |
| `KEYCLOAK_CLIENT_SECRET` | Segredo do client | (chave_teste) | (gerar no Keycloak) |
| `KEYCLOAK_REDIRECT_URI` | URL de callback | http://localhost:8000/auth/callback | https://seudominio.com.br/auth/callback |
| `KEYCLOAK_LOGOUT_URI` | URL de logout | http://localhost:8000/logout | https://seudominio.com.br/logout |
| `KEYCLOAK_DEV_MODE` | Modo desenvolvimento | true | false |
| `KEYCLOAK_DEV_MOCK_EMAIL` | Email para mock | admin@cimatec.edu.br | - |
| `KEYCLOAK_DEV_MOCK_PASSWORD_HASH` | Hash bcrypt da senha | (gerar com tinker) | - |
| `KEYCLOAK_DEV_ADMIN_EMAIL` | Email do admin (seeder) | admin@senai.com | - |
| `KEYCLOAK_DEV_ADMIN_PASSWORD_HASH` | Hash bcrypt da senha admin | (gerar com tinker) | - |

**Configuração de Desenvolvimento:**
```env
KEYCLOAK_DEV_MODE=true
KEYCLOAK_DEV_MOCK_EMAIL=admin@cimatec.edu.br
KEYCLOAK_DEV_MOCK_PASSWORD_HASH='$2y$10$...'
```

**Configuração de Produção:**
```env
KEYCLOAK_DEV_MODE=false
KEYCLOAK_BASE_URL=https://keycloak.sua-empresa.com.br
KEYCLOAK_REALM=senai-cimatec
KEYCLOAK_CLIENT_ID=vagas-senai
KEYCLOAK_CLIENT_SECRET=chave_gerada_no_keycloak
```

### Variáveis de Log

| Variável | Desenvolvimento | Produção |
|---------|---------------|----------|
| `LOG_CHANNEL` | stack | stack |
| `LOG_STACK` | single | single |
| `LOG_DEPRECATIONS_CHANNEL` | null | null |
| `LOG_LEVEL` | debug | warning |

---

## Executando a Aplicação

### Desenvolvimento Local (sem Docker)

```bash
php artisan key:generate
php artisan migrate
php artisan serve
```

Acesse: `http://localhost:8000`

### Gerando Hash de Senha para Desenvolvimento

Para configurar a senha do usuário mock em desenvolvimento, gere a hash usando o Laravel Tinker:

```bash
php artisan tinker
>>> echo password_hash('password', PASSWORD_BCRYPT, ['cost' => 10]);
```

Copie a hash gerada e adicione no `.env`:

```env
KEYCLOAK_DEV_MOCK_PASSWORD_HASH='$2y$10$...'
```

Substitua `password` pela senha desejada.

### Desenvolvimento com Docker

```bash
# Desenvolvimento
podman compose -f docker-compose.yml up -d --build

# Produção
podman compose -f docker-compose.prod.yml up -d --build
```

---

## Configuração do Keycloak (Produção)

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
4. Gere o Client Secret:
   - No painel do Keycloak, vá em **Clients** → **vagas-senai** → **Credentials**
   - Clique em **Regenerate** ou copie o **Client Secret** existente
5. Adicione ao `.env.prod`:
   ```env
   KEYCLOAK_CLIENT_SECRET=seu_client_secret_aqui
   ```

### Exemplos de Client Secret

| Tipo | Exemplo |
|------|---------|
| Gerado pelo Keycloak | `a1b2c3d4-e5f6-g7h8-i9j0-k1l2m3n4o5p6` |
| Para desenvolvimento | `teste` (apenas para teste local) |

---

## Níveis de Usuário

O sistema possui três níveis de acesso:

| Nível | Permissão | Acesso |
|------|-----------|--------|
| **Padrão** | Restrito | Apenas às vagas criadas por ele |
| **Admin** | Administrativo | Gerenciar todas as vagas e usuários não admin |
| **Admin Principal** | Total | Acesso completo ao sistema, incluindo gestão de admins |

### Detalhamento das Permissões

#### Usuário Padrão
- Criar novas vagas
- Editar e excluir vagas criadas por ele
- Acompanhar status das vagas
- Download de editais e resultados

#### Admin (is_admin = true)
- Todas as permissões de usuário padrão
- Visualizar todas as vagas do sistema
- Editar e excluir qualquer vaga
- Gerenciar usuários (criar, editar, excluir)
- Acessar área administrativa


#### Admin Principal (is_admin_principal = true)
- Todas as permissões de Admin
- Criar e remover outros administradores
- Acessar configurações do sistema
- Histórico de auditoria
- Restaurar/excluir permanentemente registries da lixeira

### Criação de Usuários em Produção

Em produção, os usuários são criados automaticamente via **JIT (Just-In-Time Provisioning)**:

1. Usuário faz login via Keycloak (SSO)
2. Sistema verifica se já existe no banco de dados
3. Se não existir, cria automaticamente mapeando as roles do Keycloak

**Mapeamento de roles Keycloak → Sistema:**
```
vagas-admin-principal → Admin Principal (is_admin_principal + is_admin)
vagas-admin          → Admin (is_admin)
(outro/nenhum)     → Usuário Padrão (is_admin = false)
```

### Criação do Admin Inicial em Produção

O admin inicial pode ser criado automaticamente no primeiro deploy:

1. Configure o `.env.prod`:
   ```env
   KEYCLOAK_DEV_ADMIN_EMAIL=admin@seudominio.com.br
   KEYCLOAK_DEV_ADMIN_PASSWORD_HASH='$2y$12$...'
   ```

2. Execute o build:
   ```bash
   docker-compose -f docker-compose.prod.yml up -d --build
   ```

3. O entrypoint.sh criará o automaticamente se não existir admin principal no banco.

---

## Build para Produção

### Sem Docker (manual)

```bash
# Limpar e otimizar caches
php artisan optimize
php artisan config:cache
php artisan route:cache
php artisan view:cache
composer dump-autoload --optimize --classmap-authoritative
```

### Com Docker

O Dockerfile.prod já executa automaticamente:
- `composer install --no-dev`
- `composer dump-autoload --optimize`
- `php artisan config:cache`
- `php artisan route:cache`
- `php artisan view:cache`

O entrypoint.sh executa automaticamente:
- `php artisan key:generate` (se necessário)
- `php artisan migrate` (em produção)
- `php artisan db:seed --class=AdminUserSeeder` (se admin não existir em produção)

---

## Variáveis de Ambiente por Ambiente

### Desenvolvimento (.env.dev)

```env
APP_NAME=Oportunidade
APP_ENV=local
APP_KEY=base64:GENERATE_SECURE_KEY_HERE
APP_DEBUG=true
APP_URL=http://localhost:8000

APP_LOCALE=pt_BR
APP_FALLBACK_LOCALE=en
APP_FAKER_LOCALE=pt_BR

LOG_CHANNEL=stack
LOG_STACK=single
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=oportunidade
DB_USERNAME=root
DB_PASSWORD=

SESSION_DRIVER=file
CACHE_STORE=file

KEYCLOAK_BASE_URL=http://localhost:8080
KEYCLOAK_REALM=senai-cimatec
KEYCLOAK_CLIENT_ID=vagas-senai
KEYCLOAK_CLIENT_SECRET=teste
KEYCLOAK_DEV_MODE=true
KEYCLOAK_DEV_MOCK_EMAIL=admin@cimatec.edu.br
KEYCLOAK_DEV_MOCK_PASSWORD_HASH='$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'
```

### Produção (.env.prod)

```env
APP_NAME=Oportunidade
APP_ENV=production
APP_KEY=base64:GENERATE_SECURE_KEY_HERE
APP_DEBUG=false
APP_URL=https://seudominio.com.br

APP_LOCALE=pt_BR
APP_FALLBACK_LOCALE=en
APP_FAKER_LOCALE=pt_BR

LOG_CHANNEL=stack
LOG_STACK=single
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=warning

DB_CONNECTION=mysql
DB_HOST=mysql_db
DB_PORT=3306
DB_DATABASE=oportunidade
DB_USERNAME=oportunidade
DB_PASSWORD=senha_forte_aqui
DB_ROOT_PASSWORD=senha_root_forte

SESSION_DRIVER=redis
SESSION_LIFETIME=120
SESSION_ENCRYPT=true
SESSION_PATH=/
SESSION_DOMAIN=seudominio.com.br
SESSION_SECURE_COOKIE=true

CACHE_STORE=redis
BROADCAST_CONNECTION=redis
QUEUE_CONNECTION=redis
REDIS_HOST=redis_prod
REDIS_PASSWORD=senha_redis_forte
REDIS_PORT=6379

KEYCLOAK_BASE_URL=https://keycloak.sua-empresa.com.br
KEYCLOAK_REALM=senai-cimatec
KEYCLOAK_CLIENT_ID=vagas-senai
KEYCLOAK_CLIENT_SECRET=chave_gerada_no_keycloak
KEYCLOAK_REDIRECT_URI=https://seudominio.com.br/auth/callback
KEYCLOAK_LOGOUT_URI=https://seudominio.com.br/logout
KEYCLOAK_DEV_MODE=false
```

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

## Comandos Úteis

| Comando | Descrição |
|---------|-----------|
| `php artisan migrate` | Executar migrações |
| `php artisan migrate:rollback` | Reverter migrações |
| `php artisan db:seed` | Popular banco |
| `php artisan storage:link` | Criar link simbólico |
| `php artisan optimize:clear` | Limpar caches |
| `php artisan vagas:encerrar-vencidas` | Encerrar vagas vencidas |
| `php artisan key:generate` | Gerar chave da aplicação |