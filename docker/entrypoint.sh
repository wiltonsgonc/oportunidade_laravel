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
fi

echo "==> Iniciando PHP-FPM..."
exec php-fpm