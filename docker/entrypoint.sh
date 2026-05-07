#!/bin/sh
set -e

echo "==> Verificando configuração Laravel..."

# Gerar APP_KEY se não existir
if [ -z "$APP_KEY" ] || [ "$APP_KEY" = "base64:GENERATE_SECURE_KEY_HERE" ]; then
    echo "==> Gerando APP_KEY..."
    php artisan key:generate --force
fi

# Executar migrations em produção
if [ "$APP_ENV" = "production" ]; then
    echo "==> Verificando migrations..."
    php artisan migrate --force --no-interaction

    # Verificar se existe usuário admin principal
    echo "==> Verificando usuário admin..."
    ADMIN_EXISTS=$(php artisan tinker --execute="echo \App\Models\Usuario::where('is_admin_principal', true)->exists();" 2>/dev/null || echo "0")
    
    if [ "$ADMIN_EXISTS" = "0" ]; then
        echo "==> Criando usuário admin..."
        php artisan db:seed --class=Database\\Seeders\\AdminUserSeeder --force 2>/dev/null || echo "Aviso: Configure KEYCLOAK_DEV_ADMIN_EMAIL e KEYCLOAK_DEV_ADMIN_PASSWORD_HASH no .env.prod"
    else
        echo "==> Usuário admin já existe"
    fi
fi

echo "==> Iniciando PHP-FPM..."
exec php-fpm