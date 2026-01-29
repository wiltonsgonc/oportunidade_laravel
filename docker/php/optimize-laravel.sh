#!/bin/bash

echo "🚀 Otimizando Laravel para desenvolvimento..."

# Configurar OPcache
php -r "opcache_reset();"

# Limpar caches Laravel
php artisan optimize:clear

# Cache de configuração (apenas se não estiver em desenvolvimento pesado)
# php artisan config:cache

# Cache de rotas (apenas se não estiver modificando rotas)
# php artisan route:cache

# Otimizar autoload
composer dump-autoload -o

# Verificar e corrigir permissões
if [ -d "storage" ]; then
    chmod -R 775 storage bootstrap/cache
    chown -R www-data:www-data storage bootstrap/cache
fi

echo "✅ Laravel otimizado!"
echo "📊 Status OPcache:"
php -r "print_r(opcache_get_status()['opcache_statistics']);"